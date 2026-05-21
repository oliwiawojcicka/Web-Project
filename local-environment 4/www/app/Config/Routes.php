<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ─── PUBLIC ROUTES (guests only) ────────────────────────────────────────────

$routes->get('/', 'LandingController::index');
$routes->get('/sign-up', 'AuthController::signUp');
$routes->post('/sign-up', 'AuthController::signUpPost');
$routes->get('/sign-in', 'AuthController::signIn');
$routes->post('/sign-in', 'AuthController::signInPost');

// ─── AUTH REQUIRED ROUTES ────────────────────────────────────────────────────

$routes->group('', ['filter' => 'authfilter'], function ($routes) {

    // Logout
    $routes->get('/sign-out', 'AuthController::signOut');

    // Homepage
    $routes->get('/home', 'Home::index');

    // Post pages
    $routes->get('/post/create', 'PostController::create');
    $routes->get('/post/edit/(:num)', 'PostController::edit/$1');

    // Profile
    $routes->get('/profile', 'ProfileController::index');
    $routes->post('/profile', 'ProfileController::update');
    $routes->post('/profile/delete', 'ProfileController::deleteAccount');

    // ── API: Posts ────────────────────────────────────────────────────────────
    $routes->get('/posts', 'PostController::index');
    $routes->post('/posts', 'PostController::store');
    $routes->get('/posts/(:num)', 'PostController::show/$1');
    $routes->put('/posts/(:num)', 'PostController::update/$1');
    // Allow POST with _method=PUT override from HTML forms
    $routes->post('/posts/(:num)', 'PostController::update/$1');
    $routes->delete('/posts/(:num)', 'PostController::delete/$1');

    // ── API: Likes ────────────────────────────────────────────────────────────
    $routes->post('/posts/(:num)/like', 'LikeController::like/$1');
    $routes->delete('/posts/(:num)/like', 'LikeController::unlike/$1');

    // ── API: Comments ─────────────────────────────────────────────────────────
    $routes->get('/posts/(:num)/comments', 'CommentController::index/$1');
    $routes->post('/posts/(:num)/comments', 'CommentController::store/$1');
    $routes->delete('/comments/(:num)', 'CommentController::delete/$1');

    // ── API: AI ───────────────────────────────────────────────────────────────
    $routes->post('/ai/improve', 'AIController::improve');
});