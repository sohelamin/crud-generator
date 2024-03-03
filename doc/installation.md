## Installation

To get started, you should add the `appzcoder/crud-generator` Composer dependency to your project:
```
composer require appzcoder/crud-generator --dev
```
Once the package is installed, you should publish its assets using the `vendor:publish` Artisan command:
```
php artisan vendor:publish --provider="Appzcoder\CrudGenerator\CrudGeneratorServiceProvider"
```
Before you begin, it's important to install the Laravel Breeze package with the Blade template, as there is a dependency on it.
```
php artisan breeze:install
 
php artisan migrate
npm install
npm run dev
```

[&larr; Back to index](README.md)
