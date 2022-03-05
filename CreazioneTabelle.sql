Create Table allenamentoPredefinito (
  IdWorkoutPre int AUTO_INCREMENT NOT NULL PRIMARY KEY,
  Descrizione Text(65000)
);

Create Table gruppoMuscolare (
  IdGruppoMuscolare int AUTO_INCREMENT NOT NULL PRIMARY KEY,
  Nome varchar(60)
);

Create Table Obiettivo (
  IdObiettivo int AUTO_INCREMENT NOT NULL PRIMARY KEY,
  AllenamentoPre int,
  GruppoMuscolare int,
  FOREIGN KEY (AllenamentoPre) REFERENCES allenamentoPredefinito(IdWorkoutPre),
  FOREIGN KEY (GruppoMuscolare) REFERENCES gruppoMuscolare(IdGruppoMuscolare)
);

Create Table Esercizio (
  IdEsercizio int AUTO_INCREMENT NOT NULL PRIMARY KEY,
  Nome varchar(30),
  MinRep int,
  MaxRep int,
  Tipologia BIT(1)
);

Create Table Appartenenza (
  IdAppartenenza int AUTO_INCREMENT NOT NULL PRIMARY KEY,
  Esercizio int,
  GruppoMuscolare int,
  FOREIGN KEY (Esercizio) REFERENCES Esercizio(IdEsercizio),
  FOREIGN KEY (GruppoMuscolare) REFERENCES gruppoMuscolare(IdGruppoMuscolare)
);

Create Table allenamentoGenerato (
  IdWorkoutGen int AUTO_INCREMENT NOT NULL PRIMARY KEY,
  NumEsercizi int,
  KcalBruciate varchar(5),
  Data date,
  Descrizione text(65000)
);

Create Table ComposizioneWE (
  IdComposizione int AUTO_INCREMENT NOT NULL PRIMARY KEY,
  NumRep int,
  NumSets int,
  AllenamentoGenerato int,
  Esercizio int,
  FOREIGN KEY (AllenamentoGenerato) REFERENCES allenamentoGenerato(IdWorkoutGen),
  FOREIGN KEY (Esercizio) REFERENCES Esercizio(IdEsercizio)
);

Create Table Utente (
  IdUtente int AUTO_INCREMENT NOT NULL PRIMARY KEY,
  Nome varchar(30),
  Cognome varchar(30),
  PesoCorporeo int,
  Età int
);

Create Table Svolgimento (
  IdSvolgimento int AUTO_INCREMENT NOT NULL PRIMARY KEY,
  Utente int,
  AllenamentoGenerato int,
  FOREIGN KEY (Utente) REFERENCES Utente(IdUtente),
  FOREIGN KEY (AllenamentoGenerato) REFERENCES allenamentoGenerato(IdWorkoutGen)
);

Create Table Tabata (
  IdTabata int AUTO_INCREMENT NOT NULL PRIMARY KEY,
  SecON int,
  SecOFF int,
  Durata int,
  AllenamentoGenerato int,
  FOREIGN KEY (AllenamentoGenerato) REFERENCES allenamentoGenerato(IdWorkoutGen)
);

Create Table Amrap (
  IdAmrap int AUTO_INCREMENT NOT NULL PRIMARY KEY,
  Durata int,
  RoundFatti int,
  AllenamentoGenerato int,
  FOREIGN KEY (AllenamentoGenerato) REFERENCES allenamentoGenerato(IdWorkoutGen)
);

Create Table Emom (
  IdEmom int AUTO_INCREMENT NOT NULL PRIMARY KEY,
  Durata int,
  Recuperi BIT(1),
  AllenamentoGenerato int,
  FOREIGN KEY (AllenamentoGenerato) REFERENCES allenamentoGenerato(IdWorkoutGen)
);

Create Table ForTime (
  IdForTime int AUTO_INCREMENT NOT NULL PRIMARY KEY,
  RoundPrevisti int,
  TimeCap int,
  AllenamentoGenerato int,
  FOREIGN KEY (AllenamentoGenerato) REFERENCES allenamentoGenerato(IdWorkoutGen)
);

Create Table Scheda (
  IdScheda int AUTO_INCREMENT NOT NULL PRIMARY KEY,
  gruppiInteressati varchar(60),
  AllenamentoGenerato int,
  FOREIGN KEY (AllenamentoGenerato) REFERENCES allenamentoGenerato(IdWorkoutGen)
);
