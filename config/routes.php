<?php

$routes->get('/', function() {
    HelloWorldController::index();
});

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

$routes->get('/uusi_hoyla', function() {
    HelloWorldController::lisaa_hoyla();
});

$routes->get('/nayta_hoyla', function() {
    HelloWorldController::nayta_hoyla();
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

$routes->get('/listaa_terat/:sivu', function($sivu) {
    TeraController::index($sivu);
});

$routes->get('/listaa_terat', function() {
    TeraController::index_s();
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

$routes->get('/nayta_tera', function() {
    HelloWorldController::nayta_tera();
});
