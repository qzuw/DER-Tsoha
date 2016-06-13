<?php

function check_logged_in(){
    BaseController::check_logged_in();
}

$routes->get('/kirjaudu_ulos', 'check_logged_in', function() {
    KayttajaController::kirjaudu_ulos();
});

$routes->get('/kirjaudu', function() {
    KayttajaController::kirjaudu();
});

$routes->post('/kirjautuminen', function() {
    KayttajaController::kirjautuminen();
});

$routes->post('/rekisteroityminen', function() {
    KayttajaController::rekisteroityminen();
});

$routes->post('/muuta_salasana', 'check_logged_in', function() {
    KayttajaController::muuta_salasana();
});

$routes->get('/poista_tunnus', 'check_logged_in', function() {
    KayttajaController::poistolomake();
});

$routes->post('/poistaminen', 'check_logged_in', function() {
    KayttajaController::poista();
});

$routes->get('/omat_tiedot', 'check_logged_in', function() {
    KayttajaController::omat_tiedot();
});

$routes->post('/omat_tiedot', 'check_logged_in', function() {
    KayttajaController::omat_tiedot();
});

$routes->get('/nayta_kayttaja/:id', 'check_logged_in', function($id) {
    KayttajaController::nayta($id);
});

$routes->get('/listaa_kayttajat/:sivu', 'check_logged_in', function($sivu) {
    KayttajaController::index($sivu);
});

$routes->get('/listaa_kayttajat', 'check_logged_in', function() {
    KayttajaController::index(1);
});

$routes->get('/listaa_terat/:sivu', function($sivu) {
    TeraController::index($sivu);
});

$routes->get('/listaa_terat', function() {
    TeraController::index(1);
});

$routes->get('/uusi_tera', 'check_logged_in', function() {
    TeraController::uusi();
});

$routes->post('/lisaa_tera', 'check_logged_in', function() {
    TeraController::lisaa();
});

$routes->get('/nayta_tera/:id', function($id) {
    TeraController::nayta($id);
});

$routes->get('/poista_tera/:id', 'check_logged_in', function($id) {
    TeraController::poista($id);
});

$routes->get('/listaa_hoylat', function() {
    HoylaController::index(1);
});

$routes->get('/listaa_hoylat/:sivu', function($sivu) {
    HoylaController::index($sivu);
});

$routes->get('/uusi_hoyla', 'check_logged_in', function() {
    HoylaController::uusi();
});

$routes->post('/lisaa_hoyla', 'check_logged_in', function() {
    HoylaController::lisaa();
});

$routes->get('/nayta_hoyla/:id', function($id) {
    HoylaController::nayta($id);
});

$routes->get('/poista_hoyla/:id', 'check_logged_in', function($id) {
    HoylaController::poista($id);
});

$routes->get('/', function() {
    HelloController::index();
});



$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});

$routes->get('/suunnitelmat/etusivu', function() {
    HelloWorldController::etusivu();
});

$routes->get('/suunnitelmat/kirjaudu', function() {
    HelloWorldController::kirjaudu();
});

$routes->get('/suunnitelmat/omat_tiedot', function() {
    HelloWorldController::omat_tiedot();
});

$routes->get('/suunnitelmat/nayta_hoyla', function() {
    HelloWorldController::nayta_hoyla();
});

$routes->get('/suunnitelmat/nayta_tera', function() {
    HelloWorldController::nayta_tera();
});

$routes->get('/suunnitelmat/uusi_hoyla', function() {
    HelloWorldController::lisaa_hoyla();
});

$routes->get('/suunnitelmat/uusi_tera', function() {
    HelloWorldController::lisaa_tera();
});

$routes->get('/suunnitelmat/listaa_terat', function() {
    HelloWorldController::listaa_terat();
});

$routes->get('/suunnitelmat/ajopaivakirja', function() {
    HelloWorldController::ajopaivakirja();
});

$routes->get('/suunnitelmat/uusi_ajopaivakirja', function() {
    HelloWorldController::lisaa_ajopaivakirja();
});

$routes->get('/suunnitelmat/listaa_ajot', function() {
    HelloWorldController::listaa_ajot();
});

