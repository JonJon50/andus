<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home')->name('home');
Route::view('/about', 'pages.about')->name('about');
Route::view('/services', 'pages.services')->name('services');
Route::view('/events', 'pages.events')->name('events');
Route::view('/contact', 'pages.contact')->name('contact');
