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
  UNIQUE (valmistaja, malli)
);

CREATE TABLE Paivakirja(
  id SERIAL PRIMARY KEY, 
  kayttaja_id INTEGER NOT NULL REFERENCES Kayttaja(id) ON DELETE CASCADE,
  partahoyla_id INTEGER NOT NULL REFERENCES Partahoyla(id),
  aggressiivisuus INTEGER NOT NULL,
  tera_id INTEGER NOT NULL REFERENCES Tera(id),
  teravyys INTEGER NOT NULL,
  pehmeys INTEGER NOT NULL,
  pvm TIMESTAMP NOT NULL,
  saippua varchar(50) NOT NULL, 
  kommentit text NOT NULL,
  julkisuus boolean DEFAULT FALSE
);

CREATE VIEW Hoylanakyma AS 
  SELECT ph.id, valmistaja, malli, COALESCE(SUM(pvk.aggressiivisuus), 0) AS aggressiivisuus, 
  COUNT(pvk.partahoyla_id) AS viittauksia 
  FROM Partahoyla ph 
  LEFT JOIN Paivakirja pvk ON ph.id=pvk.partahoyla_id 
  GROUP BY ph.id, valmistaja, malli;

CREATE VIEW Teranakyma AS 
  SELECT pt.id, valmistaja, malli, COALESCE(SUM(pvk.teravyys), 0) AS teravyys, COALESCE(SUM(pvk.pehmeys), 0) AS pehmeys, 
  COUNT(pvk.tera_id) AS viittauksia 
  FROM Tera pt 
  LEFT JOIN Paivakirja pvk ON pt.id=pvk.tera_id 
  GROUP BY pt.id, valmistaja, malli;
