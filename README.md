# Laravel Multiple Locales

This Laravel package will make `domain.com/{locale}` available to your website.
Routes that don't need a locale prefix can be added to the `'skip_locales'` array in `config/app.php`.

## Installation

### Step 1: Cloning the package

To begin you have to clone the Laravel Multiple Locales package in `packages/DidierDeMaeyer/LaravelMultipleLocales/`.

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

### Step 3: Install and setup your locales

Run `php artisan multiple-locales:install` to install the package.

Add the necessary `locales` and `skip_locales` in `config/app.php`:

```php
'locales' => ['en' => 'English', 'nl' => 'Dutch'],
'skip_locales' => ['admin', 'api'],
```

That's it! Go translate!

<br />

If you want to remove the package, just run: `php artisan multiple-locales:remove` and it will remove the published code from your package and reset your `RouteServiceProvider.php`.
