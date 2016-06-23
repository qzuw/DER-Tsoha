<?php

function check_logged_in() {
    BaseController::check_logged_in();
}

function check_logged_out() {
    BaseController::check_logged_out();
}

$routes->get('/kirjaudu_ulos', 'check_logged_in', function() {
    KayttajaController::log_out();
});

$routes->get('/kirjaudu', 'check_logged_out', function() {
    KayttajaController::login_page();
});

$routes->post('/kirjautuminen', 'check_logged_out', function() {
    KayttajaController::log_in();
});

$routes->post('/rekisteroityminen', 'check_logged_out', function() {
    KayttajaController::registration();
});

$routes->post('/muuta_salasana', 'check_logged_in', function() {
    KayttajaController::change_passwd();
});

$routes->get('/poista_tunnus', 'check_logged_in', function() {
    KayttajaController::delete_page();
});

$routes->post('/poistaminen', 'check_logged_in', function() {
    KayttajaController::delete_user();
});

$routes->get('/omat_tiedot', 'check_logged_in', function() {
    KayttajaController::own_info();
});

$routes->post('/omat_tiedot', 'check_logged_in', function() {
    KayttajaController::own_info();
});

$routes->post('/lisaa_oma_hoyla', 'check_logged_in', function() {
    KayttajaController::add_owned_razor();
});

$routes->post('/poista_oma_hoyla', 'check_logged_in', function() {
    KayttajaController::remove_owned_razor();
});

$routes->get('/nayta_kayttaja/:id', 'check_logged_in', function($user_id) {
    KayttajaController::show($user_id);
});

$routes->get('/listaa_kayttajat/:sivu', 'check_logged_in', function($page) {
    KayttajaController::index($page);
});

$routes->get('/listaa_kayttajat', 'check_logged_in', function() {
    KayttajaController::index(1);
});

$routes->get('/listaa_terat/:sivu', function($page) {
    TeraController::index($page);
});

$routes->get('/listaa_terat', function() {
    TeraController::index(1);
});

$routes->get('/uusi_tera', 'check_logged_in', function() {
    TeraController::create_page();
});

$routes->post('/lisaa_tera', 'check_logged_in', function() {
    TeraController::create();
});

$routes->get('/muokkaa_tera/:id', 'check_logged_in', function($blade_id) {
    TeraController::edit_page($blade_id);
});

$routes->post('/paivita_tera/:id', 'check_logged_in', function($blade_id) {
    TeraController::update($blade_id);
});

$routes->get('/nayta_tera/:id', function($blade_id) {
    TeraController::show($blade_id);
});

$routes->get('/poista_tera/:id', 'check_logged_in', function($blade_id) {
    TeraController::remove($blade_id);
});

$routes->get('/listaa_hoylat', function() {
    HoylaController::index(1);
});

$routes->get('/listaa_hoylat/:sivu', function($page) {
    HoylaController::index($page);
});

$routes->get('/uusi_hoyla', 'check_logged_in', function() {
    HoylaController::create_page();
});

$routes->post('/lisaa_hoyla', 'check_logged_in', function() {
    HoylaController::create();
});

$routes->get('/nayta_hoyla/:id', function($razor_id) {
    HoylaController::show($razor_id);
});

$routes->get('/muokkaa_hoyla/:id', 'check_logged_in', function($razor_id) {
    HoylaController::edit_page($razor_id);
});

$routes->post('/paivita_hoyla/:id', 'check_logged_in', function($razor_id) {
    HoylaController::update($razor_id);
});

$routes->get('/poista_hoyla/:id', 'check_logged_in', function($razor_id) {
    HoylaController::remove($razor_id);
});

$routes->get('/listaa_paivakirjat', 'check_logged_in', function() {
    PvkController::index(1);
});

$routes->get('/listaa_paivakirjat/:sivu', 'check_logged_in', function($page) {
    PvkController::index($page);
});

$routes->get('/listaa_omat_paivakirjat', 'check_logged_in', function() {
    PvkController::index_user(1);
});

$routes->get('/listaa_omat_paivakirjat/:sivu', 'check_logged_in', function($page) {
    PvkController::index_user($page);
});

$routes->get('/nayta_paivakirja/:id', 'check_logged_in', function($diary_id) {
    PvkController::show($diary_id);
});

$routes->post('/uusi_pvk', 'check_logged_in', function() {
    PvkController::create();
});

$routes->get('/uusi_paivakirja', 'check_logged_in', function() {
    PvkController::create_page();
});

$routes->post('/muokkaa_pvk/:id', 'check_logged_in', function($diary_id) {
    PvkController::update($diary_id);
});

$routes->get('/muokkaa_paivakirja/:id', 'check_logged_in', function($diary_id) {
    PvkController::edit_page($diary_id);
});

$routes->get('/poista_paivakirja/:id', 'check_logged_in', function($diary_id) {
    PvkController::remove($diary_id);
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

