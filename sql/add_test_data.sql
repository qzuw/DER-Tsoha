-- Lisää INSERT INTO lauseet tähän tiedostoon
INSERT INTO Kayttaja (id, tunnus, salasana, yllapito) VALUES (1, 'Parranajaja', 'salasana123', false);
INSERT INTO Kayttaja (id, tunnus, salasana, yllapito) VALUES (2, 'Admin', 'salasana321', true);

INSERT INTO Tera (valmistaja, malli) VALUES ('Astra', 'Superior Platinum');
INSERT INTO Tera (valmistaja, malli) VALUES ('Personna', 'Blue');

INSERT INTO Partahoyla (id, valmistaja, malli) VALUES (1, 'Parker', '24C');
INSERT INTO Partahoyla (id, valmistaja, malli) VALUES (2, 'Merkur', '34c');

INSERT INTO KayttajanHoylat (partahoyla_id, kayttaja_id) VALUES (1, 1);
INSERT INTO KayttajanHoylat (partahoyla_id, kayttaja_id) VALUES (2, 1);
INSERT INTO KayttajanHoylat (partahoyla_id, kayttaja_id) VALUES (1, 2);
