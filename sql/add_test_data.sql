-- Lisää INSERT INTO lauseet tähän tiedostoon
INSERT INTO Kayttaja (id, tunnus, salasana) VALUES (1, 'Parranajaja', '$6$mnzhNalN48wlwKOB$PbGNJEogv5/0UOKqQ3Lc45SPJlidMlpvaFutGTgLd9zT5.nCF0zm79ZA.IIGCdJKgMgxtU8yIq/GMhVgrSS1n.');
INSERT INTO Kayttaja (id, tunnus, salasana) VALUES (2, 'Partapekka', '$6$mnzhNalN48wlwKOB$PbGNJEogv5/0UOKqQ3Lc45SPJlidMlpvaFutGTgLd9zT5.nCF0zm79ZA.IIGCdJKgMgxtU8yIq/GMhVgrSS1n.');

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
INSERT INTO Partahoyla (id, valmistaja, malli) VALUES (2, 'Merkur', '34C HD');
INSERT INTO Partahoyla (id, valmistaja, malli) VALUES (3, 'Pearl', 'SSH-02');
INSERT INTO Partahoyla (id, valmistaja, malli) VALUES (4, 'Fatip', 'Piccolo');
INSERT INTO Partahoyla (id, valmistaja, malli) VALUES (5, 'Merkur', '37C');

INSERT INTO KayttajanHoylat (partahoyla_id, kayttaja_id) VALUES (1, 1);
INSERT INTO KayttajanHoylat (partahoyla_id, kayttaja_id) VALUES (2, 1);
INSERT INTO KayttajanHoylat (partahoyla_id, kayttaja_id) VALUES (1, 2);

INSERT INTO Paivakirja (partahoyla_id, kayttaja_id, tera_id, pvm, saippua, kommentit, julkisuus) VALUES (1, 1, 1, '2016-04-14 18:00:00', 'MWF', 'Hyvä ajo, vakiokombo Parker, Astra ja MWF ei taaskaan pettänyt. Ajon viimeisteli Nivean aftershave-balsami.', TRUE);
INSERT INTO Paivakirja (partahoyla_id, kayttaja_id, tera_id, pvm, saippua, kommentit, julkisuus) VALUES (4, 1, 1, '2016-06-14 19:00:00', 'MWF', 'Huolimatta MWN tarjoamasta suojasta, veri virtasi vuolaasti. Paikkailin haavoja alunalla.', FALSE);
INSERT INTO Paivakirja (partahoyla_id, kayttaja_id, tera_id, pvm, saippua, kommentit, julkisuus) VALUES (1, 2, 1, '2016-06-12 21:00:00', 'LEA Classic-voide', 'Perusajo Parkerilla ja Astralla. LEA ei ehkä ole liukkain mahdollinen, mutta mukava tuoksu. Ajon viimeisteli jälleen Nivean aftershave-balsami.', TRUE);
