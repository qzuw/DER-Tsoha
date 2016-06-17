-- Lisää CREATE TABLE lauseet tähän tiedostoon
CREATE TABLE Kayttaja(
  id SERIAL PRIMARY KEY, 
  tunnus varchar(150) UNIQUE NOT NULL, 
  salasana varchar(250) NOT NULL
);

CREATE TABLE Partahoyla(
  id SERIAL PRIMARY KEY, 
  valmistaja varchar(50) NOT NULL, 
  malli varchar(50) NOT NULL,
  aggressiivisuus INTEGER DEFAULT 0,
  viittauksia INTEGER DEFAULT 0,
  UNIQUE (valmistaja, malli)
);

CREATE TABLE KayttajanHoylat(
  kayttaja_id INTEGER REFERENCES Kayttaja(id) ON DELETE CASCADE,
  partahoyla_id INTEGER REFERENCES Partahoyla(id),
  PRIMARY KEY(kayttaja_id, partahoyla_id)
);

CREATE TABLE Tera(
  id SERIAL PRIMARY KEY, 
  valmistaja varchar(50) NOT NULL, 
  malli varchar(50) NOT NULL,
  teravyys INTEGER DEFAULT 0,
  pehmeys INTEGER DEFAULT 0,
  viittauksia INTEGER DEFAULT 0,
  UNIQUE (valmistaja, malli)
);

CREATE TABLE Paivakirja(
  id SERIAL PRIMARY KEY, 
  kayttaja_id INTEGER NOT NULL REFERENCES Kayttaja(id) ON DELETE CASCADE,
  partahoyla_id INTEGER NOT NULL REFERENCES Partahoyla(id),
  tera_id INTEGER NOT NULL REFERENCES Tera(id),
  pvm TIMESTAMP NOT NULL,
  saippua varchar(50) NOT NULL, 
  kommentit text NOT NULL,
  julkisuus boolean DEFAULT FALSE
);

