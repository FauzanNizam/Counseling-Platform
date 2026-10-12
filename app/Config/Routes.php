
<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Client\Dashboard::index', [
    'filter' => 'role:client',
]);

$routes->get('client', 'Client\Dashboard::index', [
    'filter' => 'role:client',
]);
$routes->get('admin', 'Admin\Dashboard::index', [
    'filter' => 'role:admin',
]);
$routes->get('psychologist', 'Psychologist\Dashboard::index', [
    'filter' => 'role:psychologist',
]);

$routes->get('login', 'Auth\Login::index');
$routes->post('login', 'Auth\Login::authenticate');

$routes->get('register', 'Auth\Register::index');
$routes->post('register', 'Auth\Register::store');
$routes->post('logout', 'Auth\Login::logout');


$routes->get('setup-admin', 'SetupAdmin::create');
$routes->get('setup-psychologist', 'SetupPsychologist::create');
