# Slates

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Creating a Slate package](#creating-a-slate-package "Creating a Slate package")
- Packages
    - [Auth Slate](#packages-auth-slate "Auth Slate")
    - [Laravel Authentication Slate](#packages-laravel-auth-slate "Laravel Authentication Slate")


<div id="introduction"></div>

## Introduction

Slates are packages you can download from [pub.dev](https://pub.dev) to quickly scaffold your app.

Once you install your slate package, you can run from the **terminal** using the `publish:all` command.
Each package you install will use a different command to publish all the files.

Here's an example below, i.e. installing the [ny_auth_slate](https://pub.dev/packages/ny_auth_slate).

``` dart
dart run nylo_framework:main slate:publish example_slate_package
// or with Metro 
metro slate:publish example_slate_package
```

Download a fresh copy of {{ config('app.name') }} and try it in your project [ny_auth_slate](https://pub.dev/packages/ny_auth_slate)

<div id="creating-a-slate-package"></div>

## Creating a Slate package

You can build a new Slate package by first using our public template <a href="https://github.com/nylo-core/package-skeleton-slate" target="_BLANK">here</a> to get started.

Navigate to the **my_slate_template.dart** file and modify the run() method.

``` dart
List<NyTemplate> run() => [
      /// Example
      NyTemplate(
        name: "login_page", // name of the file
        saveTo: pagesFolder, // folder to save to
        pluginsRequired: [], // dependencies that are required for the stub
        stub: stubLoginPage(), // stub you want to generate in you /stubs directory
      ),

    /// add more templates...
];
```

Once you've built your Slate package, publish it to pub.dev as a package for the community to download.

<div id="packages-auth-slate"></div>

## Auth Slate

The Auth Slate package is great starting point for your app. It includes the following:

- Login Page
- Register Page
- Buttons
- Text Fields

You can download the Auth Slate package from [pub.dev](https://pub.dev/packages/ny_auth_slate).

To install the Auth Slate package, run the below command in your terminal.

``` dart
dart run nylo_framework:main slate:publish ny_auth_slate
// or with Metro
metro slate:publish ny_auth_slate
```

<div id="packages-laravel-auth-slate"></div>

## Laravel Authentication Slate

If your backend is [Laravel](https://laravel.com), you can use the Laravel Authentication Slate package. 

It includes the following:

- Pages
    - LoginPage
    - RegisterPage
    - LandingPage
    - DashboardPage
    - AuthLandingPage
- Controllers
    - LoginController
    - RegisterController
    - ForgotPasswordController
- Events
    - LaravelAuthEvent
- Networking
    - LaravelApiService
    - LaravelAuthService
- Models
    - AuthUser
    - AuthResponse

To install the Laravel Authentication Slate package, run the below command in your terminal.

``` dart
dart run nylo_framework:main slate:publish laravel_auth_slate
// or with Metro
metro slate:publish laravel_auth_slate
```

### Update your Nylo .env file

You will need to update your `.env` file with the following:

``` bash
APP_URL="https://nylo.dev" // old url

APP_URL="http://examplelaravel.test" // your laravel project url
```


### Update your Nylo auth model

You will need to change this line in your `config/events.dart` file.

``` dart
// from
SyncAuthToBackpackEvent: SyncAuthToBackpackEvent<User>(),

// to 
SyncAuthToBackpackEvent: SyncAuthToBackpackEvent<LaravelAuthResponse>(),
```

### Update the initial route

You will need to update the initial route in your `config/routes.dart` file.

``` dart
appRouter() => nyRoutes((router) {
 ...
 router.route(AuthLandingPage.path, (_) => AuthLandingPage(), initialRoute: true); // set to initial route
 
});
```


### Laravel Package

You will also need to install the Laravel composer package.

``` bash
composer require nylo/laravel-nylo-auth
```

You can publish with:

``` bash
php artisan vendor:publish --provider="Nylo\LaravelNyloAuth\LaravelNyloAuthServiceProvider"
```

Now, you should be able to login, register, and reset your password with the Laravel Authentication Slate package.
