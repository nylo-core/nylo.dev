# Metro Cli tool

---

- <span class="text-grey">Sponsors</span>
- [Become a sponsor](https://nylo.dev/contributions)

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Install](#install "Installing Metro Alias for Nylo")
- Make Commands
  - [Make controller](#make-controller "Make a new controller with Metro")
  - [Make model](#make-model "Make a new model with Metro")
  - [Make page](#make-page "Make a new page with Metro")
  - [Make stateless widget](#make-stateless-widget "Make a new stateless widget with Metro")
  - [Make stateful widget](#make-stateful-widget "Make a new stateful widget with Metro")
  - [Make Theme](#make-theme "Make a new theme with Metro")
- Appicons
  - [Building appicons](#build-app-icons "Building app icons with Metro")


<a name="introduction"></a>
<br>
## Introduction

Metro is a CLI tool that works under the hood of the Nylo framework. 
It provides a lot of helpful tools to speed up development.

<a name="install"></a>
<br>
## Install

Mac guide

1. **Open your bash\_profile**

``` dart
sudo open ~/.bash_profile
```

2. **Add this alias to your bash\_profile**
``` bash
...
alias metro='flutter pub run nylo_framework:main'
```

3. **Then run the following**
``` bash
source ~/.bash_profile
```

If you open a project that uses Nylo, try to run the following in the terminal.

``` bash
metro
```

You should get an output similar to the below.

``` bash
Metro - Nylo\'s Companion to Build Flutter apps by Anthony Gordon

Usage: 
    command [options] [arguments]

Options
    -h

All commands:
 project
  project:init

 make
  make:controller
  make:model
  make:page
  make:stateless_widget
  make:stateful_widget
  make:theme

 appicons
  appicons:build
```

<a name="make-controller"></a>
<br>

## Make controller

You can make a new controller by running the below in the terminal.

``` bash
flutter pub run nylo_framework:main make:controller profile_controller
```

Or with the alias metro

``` bash
metro make:controller profile_controller
```

This is will create a new controller if it doesn't exist within the `app/controllers` directory.

<a name="make-model"></a>
<br>

## Make model

You can make a new model by running the below in the terminal.

``` bash
flutter pub run nylo_framework:main make:model product
```

Or with the alias metro

``` bash
metro make:model product
```

This is will create a new model if it doesn't exist within the `app/models` directory.

``` bash
metro make:model product --storable
```
You can also make a Storable model which can be saved to the users local storage.

Learn more on Storable models [here](/docs/2.x/storage)


<a name="make-page"></a>
<br>

## Make page

You can make a new page by running the below in the terminal.

``` bash
flutter pub run nylo_framework:main make:page product_page
```

Or with the alias metro

``` bash
metro make:page product_page
```

This is will create a new page if it doesn't exist within the `resources/pages/` directory.
Nylo also supports the use of controllers. Use the below command to create a new page with a controller.

``` bash
metro make:page product_page -c
```

Create a page with a controller.

<a name="make-stateless-widget"></a>
<br>

## Make stateless widget

You can make a new stateless widget by running the below in the terminal.

``` bash
flutter pub run nylo_framework:main make:stateless_widget product_rating_widget
```

Or with the alias metro

``` bash
metro make:stateless_widget product_rating_widget
```

This is will create a new widget if it doesn't exist within the `resources/widgets` directory.

<a name="make-stateful-widget"></a>
<br>

## Make stateful widget

You can make a new stateful widget by running the below in the terminal.

``` bash
flutter pub run nylo_framework:main make:stateful_widget product_rating_widget
```
Or with the alias metro
``` bash
metro make:stateful_widget product_rating_widget
```

This is will create a new widget if it doesn't exist within the `resources/widgets` directory.

<a name="make-theme"></a>
<br>

## Make theme

You can make themes by running the below in the terminal.

``` bash
flutter pub run nylo_framework:main make:theme bright_theme
```

Or with the alias metro

``` bash
metro make:theme bright_theme
```

This is will create a new theme in `resources/themes`.


<a name="build-app-icons"></a>
<br>

## Building app icons

You can generate all the app icons for IOS and Android by running the below command.

``` bash
flutter pub run nylo_framework:main appicons:build
```

Or with the alias metro

``` dart
metro appicons:build
```

This uses the pubspec.yaml configuration for your app icon. Check out the next section which explains how you can update your app icon in more detail.
