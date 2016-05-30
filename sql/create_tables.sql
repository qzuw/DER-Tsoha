-- Lisää CREATE TABLE lauseet tähän tiedostoon
CREATE TABLE Kayttaja(
  id SERIAL PRIMARY KEY, 
  tunnus varchar(50) NOT NULL, 
  salasana varchar(50) NOT NULL,
  yllapito boolean
);

CREATE TABLE Partahoyla(
  id SERIAL PRIMARY KEY, 
  valmistaja varchar(50) NOT NULL, 
  malli varchar(50) NOT NULL,
  poistettu BOOLEAN,
  aggressiivisuus REAL,
  viittauksia INTEGER
);

CREATE TABLE KayttajanHoylat(
  id SERIAL PRIMARY KEY, 
  kayttaja_id INTEGER REFERENCES Kayttaja(id),
  partahoyla_id INTEGER REFERENCES Partahoyla(id),
);

CREATE TABLE Tera(
  id SERIAL PRIMARY KEY, 
  valmistaja varchar(50) NOT NULL, 
  malli varchar(50) NOT NULL,
  poistettu BOOLEAN,
  teravyys REAL,
  pehmeys REAL,
  viittauksia INTEGER
);

CREATE TABLE Paivakirja(
  id SERIAL PRIMARY KEY, 
  kayttaja_id INTEGER REFERENCES Kayttaja(id),
  partahoyla_id INTEGER REFERENCES Partahoyla(id),
  tera_id INTEGER REFERENCES Tera(id),
  pvm TIMESTAMP NOT NULL,
  saippua varchar(50) NOT NULL, 
  yleiskommentit text NOT NULL,
  kommentit_terasta text NOT NULL,
  kommentit_hoylasta text NOT NULL
);

