# Modular Laravel


## Installation

To install via composer, by run the following command:

> composer require escapepixel/laravel-ca-modules

#### Publishing (Optional)

If you want to publish package's configuration file named `module.js`, please run the following command in your terminal:

> php artisan vendor:publish --tag=config

#### Autoloading

By default, the module classes are not loaded automatically. You can autoload your modules using psr-4 by adding this snippet in composer.json
```
{
  "autoload": {
    "psr-4": {
      "App\\": "app/",
      "modules\\": "modules/",
      "Database\\Factories\\": "database/factories/",
      "Database\\Seeders\\": "database/seeders/"
  }

}
```
**Tip: don't forget to run composer dump-autoload afterwards.**

## Usage 

You can create new **central** module by running the following artisan command:

> **php artisan make:module `name`**

The new **tenant** module can be created by running the following artisan command:

> **php artisan make:module `name` --tenant**


Tip: you have to add service provider of this module in app.php. For example 

> \modules\Post\V1\Providers\PostServiceProvider::class
