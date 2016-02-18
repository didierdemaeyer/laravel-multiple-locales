# Laravel Multiple Locales

This Laravel package will make `domain.com/{locale}/your/routes` available to your website.
Routes that don't need a locale prefix can be added to the `'skip_locales'` array in `config/app.php`.

## Installation

### Step 1: Require the package

Require the package using composer:

```bash
composer require didierdemaeyer/laravel-multiple-locales "1.*"
```

### Step 2: Register the package

Add the package to the `'providers'` array in `config/app.php`:

```php
'providers' => [
    ...
    DidierDeMaeyer\MultipleLocales\Providers\MultipleLocalesServiceProvider::class,
],
```

### Step 3: Install and setup your locales

Run `php artisan multiple-locales:install` to install the package.

Update the `locales` and `skip_locales` array with your locales in `config/app.php`:

```php
'locales' => ['en' => 'English', 'nl' => 'Dutch'],
'skip_locales' => ['admin', 'api'],
```

And you're done! Happy translating!

<br />

If you want to remove the package, just run: `php artisan multiple-locales:remove` and it will remove the published code from your package and reset your `RouteServiceProvider.php`.
