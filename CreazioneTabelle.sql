--- Creazione Tabelle DB ---
Create Table Allenamento_predefinito (
  ID_WorkoutPre int AUTO_INCREMENT NOT NULL PRIMARY KEY,
  Descrizione Text(65000)
);

Create Table Gruppo_muscolare (
  ID_GruppoMuscolare int AUTO_INCREMENT NOT NULL PRIMARY KEY,
  Nome varchar(60)
);

Create Table Obiettivo (
  ID_Obiettivo int AUTO_INCREMENT NOT NULL PRIMARY KEY,
  AllenamentoPre int,
  GruppoMuscolare int,
  FOREIGN KEY (AllenamentoPre) REFERENCES Allenamento_predefinito(ID_WorkoutPre),
  FOREIGN KEY (GruppoMuscolare) REFERENCES Gruppo_muscolare(ID_GruppoMuscolare)
);

Create Table Esercizio (
  ID_Esercizio int AUTO_INCREMENT NOT NULL PRIMARY KEY,
  Nome varchar(30),
  MinRep int,
  MaxRep int,
  Tipologia BIT(1)
);

Create Table Appartenenza (
  ID_Appartenenza int AUTO_INCREMENT NOT NULL PRIMARY KEY,
  Esercizio int,
  GruppoMuscolare int,
  FOREIGN KEY (Esercizio) REFERENCES Esercizio(ID_Esercizio),
  FOREIGN KEY (GruppoMuscolare) REFERENCES Gruppo_muscolare(ID_GruppoMuscolare)
);

Create Table Allenamento_Generato (
  ID_WorkoutGen int AUTO_INCREMENT NOT NULL PRIMARY KEY,
  NumEsercizi int,
  KcalBruciate varchar(5), --- forse da levare --- 
  Data date,
  Descrizione text(65000)
);

Create Table ComposizioneWE (
  ID_Composizione int AUTO_INCREMENT NOT NULL PRIMARY KEY,
  NumRep int,
  AllenamentoGenerato int,
  Esercizio int,
  FOREIGN KEY (AllenamentoGenerato) REFERENCES Allenamento_Generato(ID_WorkoutGen),
  FOREIGN KEY (Esercizio) REFERENCES Esercizio(ID_Esercizio)
);

Create Table Utente (
  ID_Utente int AUTO_INCREMENT NOT NULL PRIMARY KEY,
  Nome varchar(30),
  Cognome varchar(30),
  PesoCorporeo int,
  Età int
);

Create Table Svolgimento (
  ID_Svolgimento int AUTO_INCREMENT NOT NULL PRIMARY KEY,
  Utente int,
  AllenamentoGenerato int,
  FOREIGN KEY (Utente) REFERENCES Utente(ID_Utente),
  FOREIGN KEY (AllenamentoGenerato) REFERENCES Allenamento_Generato(ID_WorkoutGen)
);

Create Table Tabata (
  ID_Tabata int AUTO_INCREMENT NOT NULL PRIMARY KEY,
  SecON int,
  SecOFF int,
  Durata int,
  AllenamentoGenerato int,
  FOREIGN KEY (AllenamentoGenerato) REFERENCES Allenamento_Generato(ID_WorkoutGen)
);

Create Table Amrap (
  ID_Amrap int AUTO_INCREMENT NOT NULL PRIMARY KEY,
  Durata int,
  RoundFatti int,
  AllenamentoGenerato int,
  FOREIGN KEY (AllenamentoGenerato) REFERENCES Allenamento_Generato(ID_WorkoutGen)
);

Create Table Emom (
  ID_Emom int AUTO_INCREMENT NOT NULL PRIMARY KEY,
  Durata int,
  Recuperi BIT(1),
  AllenamentoGenerato int,
  FOREIGN KEY (AllenamentoGenerato) REFERENCES Allenamento_Generato(ID_WorkoutGen)
);

Create Table For_Time (
  ID_ForTime int AUTO_INCREMENT NOT NULL PRIMARY KEY,
  RoundPrevisti int,
  TimeCap int,
  AllenamentoGenerato int,
  FOREIGN KEY (AllenamentoGenerato) REFERENCES Allenamento_Generato(ID_WorkoutGen)
);

Create Table Scheda (
  ID_Scheda int AUTO_INCREMENT NOT NULL PRIMARY KEY,
  Gruppi_Interessati varchar(60),
  AllenamentoGenerato int,
  FOREIGN KEY (AllenamentoGenerato) REFERENCES Allenamento_Generato(ID_WorkoutGen)
);
