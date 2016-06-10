<?php

$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});

$routes->get('/etusivu', function() {
    HelloWorldController::etusivu();
});

$routes->get('/kirjaudu', function() {
    HelloWorldController::kirjaudu();
});

$routes->get('/omat_tiedot', function() {
    HelloWorldController::omat_tiedot();
});

$routes->get('/nayta_hoyla', function() {
    HelloWorldController::nayta_hoyla();
});

$routes->get('/nayta_tera', function() {
    HelloWorldController::nayta_tera();
});

$routes->get('/ajopaivakirja', function() {
    HelloWorldController::ajopaivakirja();
});

$routes->get('/uusi_ajopaivakirja', function() {
    HelloWorldController::lisaa_ajopaivakirja();
});

$routes->get('/listaa_ajot', function() {
    HelloWorldController::listaa_ajot();
});

$routes->get('/', function() {
    HelloController::index();
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

