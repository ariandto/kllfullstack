<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


//Memblokit perintah migrate
Artisan::command('migrate', function () {
    $this->error('Perintah migrate tidak diperbolehkan.');
    return 1; // Mengembalikan kode error
})->describe('Run the database migrations');

Artisan::command('migrate:fresh', function () {
    $this->error('Perintah migrate:fresh tidak diperbolehkan.');
    return 1; // Mengembalikan kode error
})->describe('Drop all tables and re-run all migrations');

Artisan::command('migrate:refresh', function () {
    $this->error('Perintah migrate:refresh tidak diperbolehkan.');
    return 1; // Mengembalikan kode error
})->describe('Reset and re-run all migrations');

Artisan::command('migrate:rollback', function () {
    $this->error('Perintah migrate:rollback tidak diperbolehkan.');
    return 1; // Mengembalikan kode error
})->describe('Rollback the last database migration');

Artisan::command('migrate:reset', function () {
    $this->error('Perintah migrate:reset tidak diperbolehkan.');
    return 1; // Mengembalikan kode error
})->describe('Rollback all database migrations');

Artisan::command('migrate', function () {
    $this->error('Perintah migrate:status tidak diperbolehkan.');
    return 1; // Mengembalikan kode error
})->describe('Show the status of each migration');


// Memblokir perintah seeder
Artisan::command('db:seed', function () {
    $this->error('Perintah db:seed tidak diperbolehkan.');
    return 1; // Mengembalikan kode error
})->describe('Seed the database with records');

Artisan::command('db:seed --class={class}', function ($class) {
    $this->error('Perintah db:seed dengan class tidak diperbolehkan.');
    return 1; // Mengembalikan kode error
})->describe('Seed the database with a specific seeder class');


// Memblokir perintah seeder lainnya
Artisan::command('migrate:install', function () {
    $this->error('Perintah migrate:install tidak diperbolehkan.');
    return 1; // Mengembalikan kode error
})->describe('Create the migration repository');

Artisan::command('migrate:refresh --seed', function () {
    $this->error('Perintah migrate:refresh dengan --seed tidak diperbolehkan.');
    return 1; // Mengembalikan kode error
})->describe('Reset and re-run all migrations with seeding');