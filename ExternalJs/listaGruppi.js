class listaGruppi {
  constructor(){
    this.count = 0;
    this.scelte = [];
  }

  addElement(id){
    this.count++;
    this.scelte.push(id);
  }

  removeElement(id){
    this.count--;
    this.scelte = this.scelte.filter(function(ele){
            return ele != id;
        });
  }

  printList(){
    for(var i=0;i<this.count;i++){
      alert(this.scelte[i]);
    }
  }
}
