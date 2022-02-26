function selectGen(idSelect,max,incAmount){
  this.id = idSelect;
  this.max = max;
  this.inc = incAmount;
  this.generate = function(){
    var i=0;
    var select = document.createElement("select"); //creo un elemento select e lo salvo in una variabile
    var optionDef = document.createElement("option"); //creo un elemento option e lo salvo in una variabile
    select.id = this.id ; //Imposto l'id del tag select
    optionDef.value = 0; //Imposto l'opzione di default a random e so che vale 0
    optionDef.text = "Random";
    select.appendChild(optionDef);
    for(i;i<this.max;i+=this.inc){
      var option = document.createElement("option"); //creo un elemento option e lo salvo in una variabile
      option.value = i+1; //imposto l'attributo value
      option.text = option.value; //imposto il testo che va dentro l'elemento option
      select.appendChild(option);
    }
    document.getElementById("selezioneMinuti").appendChild(select);
  }
}
