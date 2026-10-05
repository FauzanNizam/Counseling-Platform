<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setAutoRoute(true);
$routes->get('/', 'Home::index');

$routes->get('/profile', 'Profile::index');

$routes->get('/users', 'Users::index');
$routes->get('/login', 'Users::login');
$routes->post('/login/process', 'Users::postlogin');
$routes->get('/home', 'Home::index');
$routes->get('/catalog', 'Home::catalog');
$routes->get('/helper', 'Users::index');

// Routes untuk Library controller
$routes->get('/library', 'Library::index');
$routes->get('/library/login', 'Library::login');
$routes->post('/library/dologin', 'Library::dologin');
$routes->get('/library/logout', 'Library::logout');

$routes->group('blog', ['namespace' => 'App\Modules\Blog\Controllers'], static function ($routes) {
    $routes->get('/', 'Blog::index');
    $routes->get('/satu', 'Blog::satu');
});
