<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
#$routes->get('/', 'Home::index');
$routes->get('/', 'Auth::index');
$routes->get('/register', 'Auth::register');
$routes->post('/register/process', 'Auth::processRegister');
$routes->get('/login', 'Auth::login');
$routes->post('/login/processLogin', 'Auth::processLogin');
$routes->get('/timeline', 'Auth::timeline');
$routes->get('/logout', 'Auth::logout');

#$routes->post('timeline', 'Auth::timeline');
