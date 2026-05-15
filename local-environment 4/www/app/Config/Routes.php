<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'LandingController::index');

$routes->get('/sign-up', 'AuthController::signUp');
$routes->post('/sign-up', 'AuthController::signUpPost');

$routes->get('/sign-in', 'AuthController::signIn');
$routes->post('/sign-in', 'AuthController::signInPost');

$routes->get('/home', 'Home::index');
$routes->get('/profile', 'ProfileController::index');
$routes->get('/post/create', 'PostController::create');
$routes->get('/logout', 'AuthController::logout');