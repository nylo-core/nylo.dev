# Metro CLI tool

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Install](#install "Installing Metro Alias for Nylo")
- Make Commands
  - [Make controller](#make-controller "Make a new controller with Metro")
  - [Make model](#make-model "Make a new model with Metro")
  - [Make page](#make-page "Make a new page with Metro")
  - [Make stateless widget](#make-stateless-widget "Make a new stateless widget with Metro")
  - [Make stateful widget](#make-stateful-widget "Make a new stateful widget with Metro")
  - [Make API Service](#make-api-service "Make a new API Service with Metro")
  - [Make Event](#make-event "Make a new Event with Metro")
  - [Make Provider](#make-provider "Make a new provider with Metro")
  - [Make Theme](#make-theme "Make a new theme with Metro")
- App Icons
  - [Building App Icons](#build-app-icons "Building App Icons with Metro")


<div id="introduction"></div>
## Introduction

Metro is a CLI tool that works under the hood of the Nylo framework. 
It provides a lot of helpful tools to speed up development.

<div id="install"></div>
## Install

Mac guide

1. **Open your bash\_profile**

``` bash
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

You should see an output similar to the below.

``` bash
Usage: 
    command [options] [arguments]

Options
    -h

All commands:

 make
  make:controller
  make:model
  make:page
  make:stateless_widget
  make:stateful_widget
  make:theme
  make:event
  make:provider
  make:api_service
  make:theme

 appicons
  appicons:build
```

<div id="make-controller"></div>

## Make controller

You can make a new controller by running the below in the terminal.

``` bash
flutter pub run nylo_framework:main make:controller profile_controller
```

Or with the alias metro

``` bash
metro make:controller profile_controller
```

This is will create a new controller if it doesn't exist within the `lib/app/controllers/` directory.

<div id="make-model"></div>

## Make model

You can make a new model by running the below in the terminal.

``` bash
flutter pub run nylo_framework:main make:model product
```

Or with the alias metro

``` bash
metro make:model product
```

It will place the newly created model in `lib/app/models/`.

If you plan to use the same model to represent objects from your API's, add it to your `lib/config/decoders.dart` modelDecoders map.

#### Arguments:

Using the `--force` flag will overwrite an exiting class if it already exists.

``` bash
metro make:model product --force
```

Using the `--storage` flag will create a new model that is storable to the users local storage.

``` bash
metro make:model product --storable
```

Learn more on Storable models <a href="/docs/3.x/storage#introduction-to-storable-models">here</a>.


<div id="make-page"></div>

## Make page

You can make a new page by running the below in the terminal.

``` bash
flutter pub run nylo_framework:main make:page product_page
```

Or with the alias metro

``` bash
metro make:page product_page
```

This is will create a new page if it doesn't exist within the `lib/resources/pages/` directory.
Nylo also supports the use of controllers. Use the below command to create a new page with a controller.

``` bash
metro make:page product_page -c
```

Create a page with a controller.

<div id="make-stateless-widget"></div>

## Make stateless widget

You can make a new stateless widget by running the below in the terminal.

``` bash
flutter pub run nylo_framework:main make:stateless_widget product_rating_widget
```

Or with the alias metro

``` bash
metro make:stateless_widget product_rating_widget
```

The above will create a new widget if it doesn't exist within the `lib/resources/widgets/` directory.

<div id="make-stateful-widget"></div>

## Make stateful widget

You can make a new stateful widget by running the below in the terminal.

``` bash
flutter pub run nylo_framework:main make:stateful_widget product_rating_widget
```
Or with the alias metro
``` bash
metro make:stateful_widget product_rating_widget
```

The above will create a new widget if it doesn't exist within the `lib/resources/widgets/` directory.

<div id="make-api-service"></div>

## Make API Service

You can make a new API service by running the below in the terminal.

``` bash
flutter pub run nylo_framework:main make:api_service user
```

Or with the alias metro

``` bash
metro make:api_service user_api_service
```

You can provide the `--url="https://myapi.com"` option to create an API Service that will use the `url` as the Base URL.

``` bash
flutter pub run nylo_framework:main make:api_service user --url="https://myapi-baseurl.com"
```

You can provide a `--model="User"` option to tell Nylo to create a new API Service with the following methods: <b>find</b>, <b>create</b>, <b>delete</b>, <b>update</b> and <b>fetchAll</b>.

``` bash
flutter pub run nylo_framework:main make:api_service user --model="User"
```

It will place the newly created API service in `lib/app/networking/`.

<div id="make-event"></div>

## Make event

You can make a new event by running the below in the terminal.

``` bash
flutter pub run nylo_framework:main make:event login_event
```

Or with the alias metro

``` bash
metro make:api_service login_event
```

This is will create a new event in `lib/app/events`.

You will also need to add the event to your `lib/config/events.dart` map for it to be used.


<div id="make-provider"></div>

## Make provider

Create new providers in your application using the below command.

``` bash
flutter pub run nylo_framework:main make:provider firebase_provider
```

Or with the alias metro

``` bash
metro make:provider firebase_provider
```

It will place the newly created provider in `lib/app/providers/`.

You will also need to add the provider to your `lib/config/providers.dart` map for it to be used.


<div id="make-theme"></div>

## Make theme

You can make themes by running the below in the terminal.

``` bash
flutter pub run nylo_framework:main make:theme bright_theme
```

Or with the alias metro

``` bash
metro make:theme bright_theme
```

<div id="build-app-icons"></div>

## Build App Icons

You can generate all the app icons for IOS and Android by running the below command.

``` bash
flutter pub run flutter_launcher_icons:main
```

This uses the <b>flutter_icons</b> configuration in your `pubspec.yaml` file.
