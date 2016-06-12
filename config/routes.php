<?php

$routes->get('/kirjaudu', function() {
    KayttajaController::kirjaudu();
});

$routes->post('/kirjautuminen', function() {
    KayttajaController::kirjautuminen();
});

$routes->post('/rekisteroityminen', function() {
    KayttajaController::rekisteroityminen();
});

$routes->get('/omat_tiedot', function() {
    KayttajaController::nayta();
});

$routes->post('/omat_tiedot', function() {
    KayttajaController::nayta();
});

$routes->get('/listaa_terat/:sivu', function($sivu) {
    TeraController::index($sivu);
});

$routes->get('/listaa_terat', function() {
    TeraController::index(1);
});

$routes->get('/uusi_tera', function() {
    TeraController::uusi();
});

$routes->post('/lisaa_tera', function() {
    TeraController::lisaa();
});

$routes->get('/nayta_tera/:id', function($id) {
    TeraController::nayta($id);
});

$routes->get('/poista_tera/:id', function($id) {
    TeraController::poista($id);
});

$routes->get('/listaa_hoylat', function() {
    HoylaController::index(1);
});

$routes->get('/listaa_hoylat/:sivu', function($sivu) {
    HoylaController::index($sivu);
});

$routes->get('/uusi_hoyla', function() {
    HoylaController::uusi();
});

$routes->post('/lisaa_hoyla', function() {
    HoylaController::lisaa();
});

$routes->get('/nayta_hoyla/:id', function($id) {
    HoylaController::nayta($id);
});

$routes->get('/poista_hoyla/:id', function($id) {
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

