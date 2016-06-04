-- Lisää INSERT INTO lauseet tähän tiedostoon
INSERT INTO Kayttaja (id, tunnus, salasana) VALUES (1, 'Parranajaja', 'salasana123');
INSERT INTO Kayttaja (id, tunnus, salasana) VALUES (2, 'Partapekka', 'salasana321');

INSERT INTO Tera (valmistaja, malli) VALUES ('Astra', 'Superior Platinum');
INSERT INTO Tera (valmistaja, malli) VALUES ('Personna', 'Platinum');
INSERT INTO Tera (valmistaja, malli) VALUES ('Rapira', 'Platinum');
INSERT INTO Tera (valmistaja, malli) VALUES ('Rapira', 'Voskhod');
INSERT INTO Tera (valmistaja, malli) VALUES ('Polsilver', 'Super Iridium');
INSERT INTO Tera (valmistaja, malli) VALUES ('Rapira', 'Swedish Supersteel');
INSERT INTO Tera (valmistaja, malli) VALUES ('Feather', 'Hi-Stainless');
INSERT INTO Tera (valmistaja, malli) VALUES ('Gillette', '7 o''clock SharpEdge');
INSERT INTO Tera (valmistaja, malli) VALUES ('Gillette', '7 o''clock Super Stainless');
INSERT INTO Tera (valmistaja, malli) VALUES ('Bolzano', 'superinox');
INSERT INTO Tera (valmistaja, malli) VALUES ('Astra', 'Superior Stainless');
INSERT INTO Tera (valmistaja, malli) VALUES ('Shark', 'Super Stainless');
INSERT INTO Tera (valmistaja, malli) VALUES ('Derby', 'Extra');
INSERT INTO Tera (valmistaja, malli) VALUES ('Treet', 'Platinum');
INSERT INTO Tera (valmistaja, malli) VALUES ('Treet', 'Classic');
INSERT INTO Tera (valmistaja, malli) VALUES ('Treet', 'Dura Sharp');
INSERT INTO Tera (valmistaja, malli) VALUES ('Lord', 'Big Ben');
INSERT INTO Tera (valmistaja, malli) VALUES ('Perma-Sharp', 'Super');
INSERT INTO Tera (valmistaja, malli) VALUES ('Tiger', 'Superior Stainless');

INSERT INTO Partahoyla (id, valmistaja, malli) VALUES (1, 'Parker', '24C');
INSERT INTO Partahoyla (id, valmistaja, malli) VALUES (2, 'Merkur', '34c');

INSERT INTO KayttajanHoylat (partahoyla_id, kayttaja_id) VALUES (1, 1);
INSERT INTO KayttajanHoylat (partahoyla_id, kayttaja_id) VALUES (2, 1);
INSERT INTO KayttajanHoylat (partahoyla_id, kayttaja_id) VALUES (1, 2);

INSERT INTO Paivakirja (partahoyla_id, kayttaja_id, tera_id, pvm, saippua, kommentit, julkisuus) VALUES (1, 1, 1, '2016-04-14 00:00:00', 'MWF', 'Hyvä ajo, vakiokombo Parker, Astra ja MWF ei taaskaan pettänyt. Ajon viimeisteli Nivean aftershave-balsami.', true);
