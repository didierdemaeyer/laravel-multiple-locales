# Laravel Multiple Locales

This Laravel package will make `domain.com/{locale}` available to your website.
Routes that don't need a locale prefix can be added to the `'skip_locales'` array in `config/app.php`.

## Installation

### Step 1: Cloning the package

To begin you have to clone the [Laravel Multiple Locales package](https://github.com/didierdemaeyer/laravel-multiple-locales) in `packages/DidierDeMaeyer/LaravelMultipleLocales/`.

### Step 2: Registering the package

Autoload the package in your `composer.json` file:

```json
"autoload": {
    "classmap": [
        "..."
    ],
    "psr-4": {
        "..."
        "DidierDeMaeyer\\MultipleLocales\\": "packages/DidierDeMaeyer/LaravelMultipleLocales/src/"
    }
}
```

Then execute `composer dump-autoload` in the command line in the project directory.

Now add the package to the `'providers'` array in `config/app.php` as follows:

```php
'providers' => [
    ...
    DidierDeMaeyer\MultipleLocales\MultipleLocalesServiceProvider::class,
],
```

### Step 3: Running the install command

Run `php artisan multiple-locales:install` to install the package.

If you want to remove the package, run: `php artisan multiple-locales:remove`.

Add the necessary `locales` and `skip_locales` in `config/app.php`:

```php
'locales' => ['en' => 'English', 'nl' => 'Dutch'],
'skip_locales' => ['admin', 'api'],
```

That's it! Go translate!
