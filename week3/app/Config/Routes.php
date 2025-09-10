<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('hello', 'Dosen::index');
$routes->get('mahasiswa', 'Mahasiswa::index');
$routes->get('mahasiswa/create', 'Mahasiswa::create');
$routes->post('mahasiswa', 'Mahasiswa::store');
// Tempatkan edit/delete di atas detail agar tidak bentrok dengan rute generic
$routes->get('mahasiswa/(:segment)/edit', 'Mahasiswa::edit/$1');
$routes->post('mahasiswa/(:segment)', 'Mahasiswa::update/$1');
$routes->get('mahasiswa/(:segment)/delete', 'Mahasiswa::delete/$1');
$routes->get('mahasiswa/(:segment)', 'Mahasiswa::show/$1');
$routes->get('/home', 'Home::index');
$routes->get('/berita', 'Berita::index');

// Auth
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attempt');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::store');
$routes->get('logout', 'Auth::logout');