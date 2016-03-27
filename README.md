# Laravel Module Management
Modular Pattern &amp; Module Management for Laravel 5

## Installation

The best way to install this package is through your terminal via Composer.

Add the following line to the `composer.json` file and fire `composer update`

```
"furkankadioglu/laravelmodulemanagement": "dev-master"
```
Once this operation is complete, simply add the service provider to your project's `config/app.php`

#### Service Provider
```
furkankadioglu\LaravelModuleManagement\ModuleServiceProvider::class,
```

## Getting started

The built in Artisan command `php artisan make:module name [--no-migration] [--no-translation]` generates a ready to use module in the `app/Modules` folder and a migration if necessary.

```
laravel-project/
    app/
    |-- Modules/
        |-- FooBar/
            |-- Controllers/
                |-- FooBarController.php
                |-- FooBarApiController.php
                |-- FooBarAdminController.php
            |-- Models/
                |-- FooBar.php
            |-- Views/
                |-- default/
                    |-- index.blade.php
                |-- admin/
                    |-- index.blade.php
                |-- api/
                    |-- index.blade.php
            |-- Translations/
                |-- en/
                    |-- general.php
                |-- tr/
                    |-- general.php
            |-- routes.php
            |-- helper.php
            |-- details.php
```

## Config 

Getting to module config file and generators:
```
php artisan vendor:publish
```

Files


```
laravel-project/
    config/
    |-- modulemanagement.php
    app/
    |-- BaseHelpers.php
    |-- Http/
        |-- Controllers/
            |-- AdminTemplateController.php
            |-- MainTemplateController.php
            |-- AdminController.php
            |-- MainController.php

```

### General

Based on [L5 Modular](https://github.com/Artem-Schander/L5Modular), thanks to Artem Schander.

### To Do List
- Modular Pattern Generator with arrays
- Relocate arrays in config folder
- Generate a Module Management Controller
- Generate a Module Management View(s)
