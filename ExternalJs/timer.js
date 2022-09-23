//Dichiarazione di costanti
const FULL_DASH_ARRAY = 283;
const WARNING_THRESHOLD = 60;
const ALERT_THRESHOLD = 10;

const COLOR_CODES = { //Oggetto con i colori che variano in base al tempo
  info: {
    color: "blue"
  },
  warning: {
    color: "orange",
    threshold: WARNING_THRESHOLD
  },
  alert: {
    color: "red",
    threshold: ALERT_THRESHOLD
  }
};

let timePassed = 0;
let timeLeft = TIME_LIMIT;
let timerInterval = null;
let remainingPathColor = COLOR_CODES.info.color;

//Inserisco nel documento il timer
document.getElementById("app").innerHTML = `
<div class="base-timer">
  <svg class="base-timer__svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
    <g class="base-timer__circle">
      <circle class="base-timer__path-elapsed" cx="50" cy="50" r="45"></circle>
      <path
        id="base-timer-path-remaining"
        stroke-dasharray="283"
        class="base-timer__path-remaining ${remainingPathColor}"
        d="
          M 50, 50
          m -45, 0
          a 45,45 0 1,0 90,0
          a 45,45 0 1,0 -90,0
        "
      ></path>
    </g>
  </svg>
  <span id="base-timer-label" class="base-timer__label"> ${formatTime(timeLeft)}  </span>
</div>
`;


function onTimesUp() {//Funzione scatenata nel momento in cui il timer giunge al termine
  clearInterval(timerInterval); /* Funzione globale che termina il tempo di "azione" impostato per un oggetto */
  //Reinizializza il timer
  timerInterval = null;
  timeLeft = TIME_LIMIT;
  timePassed = 0;
  document.getElementById("opzioniAllenamento").style.display = "block";
  document.getElementById("request").disabled= false;
  document.getElementById("start").disabled = true;
  document.getElementById("displayEsercizi").innerHTML = " ";
  //Reset dei colori sul timer
  document.getElementById("base-timer-path-remaining").classList.remove(COLOR_CODES.alert.color);
  document.getElementById("base-timer-path-remaining").classList.add(COLOR_CODES.info.color);
  setRemainingPathColor(120); //Reimposto il colore iniziale
  setCircleDasharray(); //Riporto il cerchio all'inizio
}


function startTimer() { /* Funzione di avvio del timer r ricalcolo dei parametri del timer */
  timerInterval = setInterval(() => { /* Closure richiamata periodicamente */
    timePassed = timePassed += 1;
    timeLeft = TIME_LIMIT - timePassed;
    document.getElementById("base-timer-label").innerHTML = formatTime(timeLeft); //Aggiorna il display
    setCircleDasharray();
    setRemainingPathColor(timeLeft);

    if (timeLeft === 0) { //Quando il tempo è scaduto
      onTimesUp();
      //Aggiunta mia - 17/06/2022 -start
      document.getElementById("stop").disabled= true;
      document.getElementById("pause").disabled= true;
      document.getElementById("resume").disabled= true;
      //stopWorkout(); //Richiamo della funzione presente nelle pagine di allenamento
      var event = new Event('workoutFinito');
      document.getElementById("fineAllenamento").dispatchEvent(event); //Lancio di un evento che fa scatutire la funzione di salvataggio del workout
      // - end
    }
  }, 1000);
}

function formatTime(time) { //Funzione che formatta il tempo in minuti : secondi partendo da un intero
  const minutes = Math.floor(time / 60);
  let seconds = time % 60;

  if (seconds < 10) {
    seconds = `0${seconds}`;
  }

  return `${minutes}:${seconds}`;
}

function setRemainingPathColor(timeLeft) { //Gestisco le logiche relative al cambio del colore del timer
  const { alert, warning, info } = COLOR_CODES;
  if (timeLeft <= alert.threshold) {
    document.getElementById("base-timer-path-remaining").classList.remove(warning.color);
    document.getElementById("base-timer-path-remaining").classList.add(alert.color);
  } else if (timeLeft <= warning.threshold) {
    document.getElementById("base-timer-path-remaining").classList.remove(info.color);
    document.getElementById("base-timer-path-remaining").classList.add(warning.color);
  }
}

function calculateTimeFraction() {
  const rawTimeFraction = timeLeft / TIME_LIMIT;
  return rawTimeFraction - (1 / TIME_LIMIT) * (1 - rawTimeFraction);
}

function setCircleDasharray() {//Si occupa nella setInterval ricorrente di aggiornare l'avanzamento del cerchio
  const circleDasharray = `${(calculateTimeFraction() * FULL_DASH_ARRAY).toFixed(0)} 283`;
  document.getElementById("base-timer-path-remaining").setAttribute("stroke-dasharray", circleDasharray);
}
//Aggiunta mia - 17/06/2022 - start
function stopTimer(){ //Funzione aggiunta per gestire uno stop non programmato del timer
  onStopWorkout();
  document.getElementById("stop").disabled= true;
  document.getElementById("pause").disabled= true;
  document.getElementById("start").disabled= true;
  document.getElementById("resume").disabled= true;
  document.getElementById("opzioniAllenamento").style.display = "block";
  setRemainingPathColor(120); //Reimposto il colore iniziale
  setCircleDasharray(); //Riporto il cerchio all'inizio
}

function onStopWorkout() { //Reinizializza il timer
  clearInterval(timerInterval);
  timerInterval = null;
  timeLeft = TIME_LIMIT;
  timePassed = 0;
  document.getElementById("tempo").value = 0;
  document.getElementById("base-timer-label").innerHTML = formatTime(0) ;
  document.getElementById("base-timer-path-remaining").classList.remove(COLOR_CODES.alert.color);
  document.getElementById("base-timer-path-remaining").classList.remove(COLOR_CODES.warning.color);
  document.getElementById("base-timer-path-remaining").classList.add(COLOR_CODES.info.color);
} // - end
