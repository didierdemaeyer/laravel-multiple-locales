# Laravel Multiple Locales: Further Development

This Laravel package will make `domain.com/{locale}` available to your website.
Routes that don't need a locale prefix can be added to the `'skip_locales'` array in `config/app.php`.

## Installation

### Step 1: Require the package

Require the package using composer:

```bash
composer require didierdemaeyer/laravel-multiple-locales "0.1.*"
```

### Step 2: Register the package

Add the package to the `'providers'` array in `config/app.php`:

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
