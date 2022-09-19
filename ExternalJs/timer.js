const FULL_DASH_ARRAY = 283;
const WARNING_THRESHOLD = 10;
const ALERT_THRESHOLD = 5;

const COLOR_CODES = {
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


function onTimesUp() {
  clearInterval(timerInterval);
  //Reinizializza il timer
  timerInterval = null;
  timeLeft = TIME_LIMIT;
  timePassed = 0;
  // Aggiunta mia - Commit precedenti - start
  /*for(let i = 0;i< workoutParameters.length;i++){
    workoutParameters[i].style.display = "block";
  }*/
  document.getElementById("opzioniAllenamento").style.display = "block";
  document.getElementById("request").disabled= false;
  document.getElementById("start").disabled = true;
  document.getElementById("displayEsercizi").innerHTML = " ";
  //Aggiunta mia - end

  document.getElementById("base-timer-path-remaining").classList.remove(COLOR_CODES.alert.color);
  document.getElementById("base-timer-path-remaining").classList.add(COLOR_CODES.info.color);
}


function startTimer() {
  timerInterval = setInterval(() => {
    timePassed = timePassed += 1;
    timeLeft = TIME_LIMIT - timePassed;
    document.getElementById("base-timer-label").innerHTML = formatTime(timeLeft);
    setCircleDasharray();
    setRemainingPathColor(timeLeft);

    if (timeLeft === 0) {
      onTimesUp();
      //Aggiunta mia - 17/06/2022 -start
      document.getElementById("stop").disabled= true;
      document.getElementById("pause").disabled= true;
      //document.getElementById("start").disabled= false;
      document.getElementById("resume").disabled= true;
      //setMinuteOnTimer();
      stopWorkout();
      var event = new Event('workoutFinito');
      document.getElementById("fineAllenamento").dispatchEvent(event);
      // - end
    }
  }, 1000);
}

function formatTime(time) {
  const minutes = Math.floor(time / 60);
  let seconds = time % 60;

  if (seconds < 10) {
    seconds = `0${seconds}`;
  }

  return `${minutes}:${seconds}`;
}

function setRemainingPathColor(timeLeft) {
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

function setCircleDasharray() {
  const circleDasharray = `${(calculateTimeFraction() * FULL_DASH_ARRAY).toFixed(0)} 283`;
  document.getElementById("base-timer-path-remaining").setAttribute("stroke-dasharray", circleDasharray);
}
//Aggiunta mia - 17/06/2022 - start
function stopTimer(){
  /*timeLeft = 0;
  clearInterval(timerInterval);
  timePassed = 0;*/
  onStopWorkout();
  //document.getElementById("base-timer-label").innerHTML = formatTime(timeLeft);
  //document.getElementById("nomeWorkout").innerHTML = " ";
  document.getElementById("stop").disabled= true;
  document.getElementById("pause").disabled= true;
  document.getElementById("start").disabled= true;
  document.getElementById("resume").disabled= true;
  /*for(let i = 0; i<workoutParameters.length; i++){
    workoutParameters[i].style.display = "block";
  }*/
  document.getElementById("opzioniAllenamento").style.display = "block";
  //setMinuteOnTimer();
  setRemainingPathColor(60);
  //setRemainingPathColor(timeLeft);
  setCircleDasharray();
}

function onStopWorkout() {
  clearInterval(timerInterval);
  //Reinizializza il timer
  timerInterval = null;
  timeLeft = TIME_LIMIT;
  timePassed = 0;
  document.getElementById("tempo").value = 0;
  document.getElementById("base-timer-label").innerHTML = formatTime(0) ;
  document.getElementById("base-timer-path-remaining").classList.remove(COLOR_CODES.alert.color);
  document.getElementById("base-timer-path-remaining").classList.remove(COLOR_CODES.warning.color);
  document.getElementById("base-timer-path-remaining").classList.add(COLOR_CODES.info.color);
} // - end
