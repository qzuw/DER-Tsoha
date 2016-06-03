-- Lisää INSERT INTO lauseet tähän tiedostoon
INSERT INTO Kayttaja (id, tunnus, salasana) VALUES (1, 'Parranajaja', 'salasana123');
INSERT INTO Kayttaja (id, tunnus, salasana) VALUES (2, 'Admin', 'salasana321');

INSERT INTO Tera (valmistaja, malli) VALUES ('Astra', 'Superior Platinum');
INSERT INTO Tera (valmistaja, malli) VALUES ('Personna', 'Blue');

INSERT INTO Partahoyla (id, valmistaja, malli) VALUES (1, 'Parker', '24C');
INSERT INTO Partahoyla (id, valmistaja, malli) VALUES (2, 'Merkur', '34c');

INSERT INTO KayttajanHoylat (partahoyla_id, kayttaja_id) VALUES (1, 1);
INSERT INTO KayttajanHoylat (partahoyla_id, kayttaja_id) VALUES (2, 1);
INSERT INTO KayttajanHoylat (partahoyla_id, kayttaja_id) VALUES (1, 2);

INSERT INTO Paivakirja (partahoyla_id, kayttaja_id, tera_id, pvm, saippua, kommentit, julkisuus) VALUES (1, 1, 1, '2016-04-14 00:00:00', 'MWF', 'Hyvä ajo, vakiokombo Parker, Astra ja MWF ei taaskaan pettänyt. Ajon viimeisteli Nivean aftershave-balsami.', true);
