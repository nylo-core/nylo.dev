# Metro CLI tool

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Install](#install "Installing Metro Alias for {{ config('app.name') }}")
- Make Commands
  - [Make controller](#make-controller "Make a new controller with Metro")
  - [Make model](#make-model "Make a new model with Metro")
  - [Make page](#make-page "Make a new page with Metro")
  - [Make stateless widget](#make-stateless-widget "Make a new stateless widget with Metro")
  - [Make stateful widget](#make-stateful-widget "Make a new stateful widget with Metro")
  - [Make API Service](#make-api-service "Make a new API Service with Metro")
    - [Using Postman](#make-api-service-using-postman "Create API services with Postman")
  - [Make Event](#make-event "Make a new Event with Metro")
  - [Make Provider](#make-provider "Make a new provider with Metro")
  - [Make Theme](#make-theme "Make a new theme with Metro")
  - [Make Forms](#make-forms "Make a new form with Metro")
  - [Make Route Guard](#make-route-guard "Make a new route guard with Metro")
  - [Make Config File](#make-config-file "Make a new config file with Metro")
- App Icons
  - [Building App Icons](#build-app-icons "Building App Icons with Metro")


<div id="introduction"></div>
<br>
## Introduction

Metro is a CLI tool that works under the hood of the {{ config('app.name') }} framework. 
It provides a lot of helpful tools to speed up development.

<div id="install"></div>
<br>
## Install

Mac guide

**Run the below command from the terminal**

``` bash
echo "alias metro='dart run nylo_framework:main'" >>~/.bash_profile && source ~/.bash_profile
```

If you open a project that uses {{ config('app.name') }}, try to run the following in the terminal.

``` bash
dart run nylo_framework:main
# or with the alias
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
  make:stateful_widget
  make:stateless_widget
  make:provider
  make:event
  make:api_service
  make:interceptor
  make:theme
  make:form
  make:route_guard
  make:config
  
slate
  slate:publish
  slate:install
```

<div id="make-controller"></div>
<br>

## Make controller

- [Making a new controller](#making-a-new-controller "Make a new controller with Metro")
- [Forcefully make a controller](#forcefully-make-a-controller "Forcefully make a new controller with Metro")
<div id="making-a-new-controller"></div>
<br>

### Making a new controller

You can make a new controller by running the below in the terminal.

``` bash
dart run nylo_framework:main make:controller profile_controller
# or with the alias metro
metro make:controller profile_controller
```

This will create a new controller if it doesn't exist within the `lib/app/controllers/` directory.

<div id="forcefully-make-a-controller"></div>
<br>

### Forcefully make a controller

**Arguments:**

Using the `--force` or `-f` flag will overwrite an existing controller if it already exists.

``` bash
metro make:controller profile_controller --force
```

<div id="make-model"></div>
<br>

## Make model

- [Making a new model](#making-a-new-model "Make a new model with Metro")
- [Make model from JSON](#make-model-from-json "Make a new model from JSON with Metro")
- [Forcefully make a model](#forcefully-make-a-model "Forcefully make a new model with Metro")
<div id="making-a-new-model"></div>
<br>

### Making a new model

You can make a new model by running the below in the terminal.

``` bash
dart run nylo_framework:main make:model product
# or with the alias metro
metro make:model product
```

It will place the newly created model in `lib/app/models/`.

<div id="make-model-from-json"></div>
<br>

### Make a model from JSON

**Arguments:**

Using the `--json` or `-j` flag will create a new model from a JSON payload.

``` bash
dart run nylo_framework:main make:model product --json
# or with the alias metro
metro make:model product --json
```

Then, you can paste your JSON into the terminal and it will generate a model for you.

<div id="forcefully-make-a-model"></div>
<br>

### Forcefully make a model

**Arguments:**

Using the `--force` or `-f` flag will overwrite an existing model if it already exists.

``` bash
dart run nylo_framework:main make:model product --force
# or with the alias metro
metro make:model product --force
```

<div id="make-page"></div>
<br>

## Make page

- [Making a new page](#making-a-new-page "Make a new page with Metro")
- [Create a page with a controller](#create-a-page-with-a-controller "Make a new page with a controller with Metro")
- [Create an auth page](#create-an-auth-page "Make a new auth page with Metro")
- [Create an initial page](#create-an-initial-page "Make a new initial page with Metro")
- [Create a bottom navigation page](#create-a-bottom-navigation-page "Make a new bottom navigation page with Metro")
- [Forcefully make a page](#forcefully-make-a-page "Forcefully make a new page with Metro")

<div id="making-a-new-page"></div>
<br>

### Making a new page

You can make a new page by running the below in the terminal.

``` bash
dart run nylo_framework:main make:page product_page
# or with the alias metro
metro make:page product_page
```

This will create a new page if it doesn't exist within the `lib/resources/pages/` directory.

<div id="create-a-page-with-a-controller"></div>
<br>

### Create a page with a controller

You can make a new page with a controller by running the below in the terminal.

**Arguments:**

Using the `--controller` or `-c` flag will create a new page with a controller.

``` bash
dart run nylo_framework:main make:page product_page --controller
# or with the alias metro
metro make:page product_page -c
```

<div id="create-an-auth-page"></div>
<br>

### Create an auth page

You can make a new auth page by running the below in the terminal.

**Arguments:**

Using the `--auth` or `-a` flag will create a new auth page.

``` bash
dart run nylo_framework:main make:page login_page --auth
# or with the alias metro
metro make:page login_page -a
```

<div id="create-an-initial-page"></div>
<br>

### Create an initial page

You can make a new initial page by running the below in the terminal.

**Arguments:**

Using the `--initial` or `-i` flag will create a new initial page.

``` bash
dart run nylo_framework:main make:page home_page --initial
# or with the alias metro
metro make:page home_page -i
```

<div id="create-a-bottom-navigation-page"></div>
<br>

### Create a bottom navigation page

You can make a new bottom navigation page by running the below in the terminal.

**Arguments:**

Using the `--bottom-nav` or `-b` flag will create a new bottom navigation page.

``` bash
dart run nylo_framework:main make:page dashboard --bottom-nav
# or with the alias metro
metro make:page dashboard -b
```

<div id="forcefully-make-a-page"></div>
<br>

### Forcefully make a page

**Arguments:**

Using the `--force` or `-f` flag will overwrite an existing page if it already exists.

``` bash
dart run nylo_framework:main make:page product_page --force
# or with the alias metro
metro make:page product_page --force
```

<div id="make-stateless-widget"></div>
<br>

## Make stateless widget

- [Making a new stateless widget](#making-a-new-stateless-widget "Make a new stateless widget with Metro")
- [Forcefully make a stateless widget](#forcefully-make-a-stateless-widget "Forcefully make a new stateless widget with Metro")
<div id="making-a-new-stateless-widget"></div>
<br>

### Making a new stateless widget

You can make a new stateless widget by running the below in the terminal.

``` bash
dart run nylo_framework:main make:stateless_widget product_rating_widget
# or with the alias metro
metro make:stateless_widget product_rating_widget
```

The above will create a new widget if it doesn't exist within the `lib/resources/widgets/` directory.

<div id="forcefully-make-a-stateless-widget"></div>
<br>

### Forcefully make a stateless widget

**Arguments:**

Using the `--force` or `-f` flag will overwrite an existing widget if it already exists.

``` bash
dart run nylo_framework:main make:stateless_widget product_rating_widget --force
# or with the alias metro
metro make:stateless_widget product_rating_widget --force
```

<div id="make-stateful-widget"></div>
<br>

## Make stateful widget

- [Making a new stateful widget](#making-a-new-stateful-widget "Make a new stateful widget with Metro")
- [Forcefully make a stateful widget](#forcefully-make-a-stateful-widget "Forcefully make a new stateful widget with Metro")

<div id="making-a-new-stateful-widget"></div>
<br>

### Making a new stateful widget

You can make a new stateful widget by running the below in the terminal.

``` bash
dart run nylo_framework:main make:stateful_widget product_rating_widget
# or with the alias metro
metro make:stateful_widget product_rating_widget
```

The above will create a new widget if it doesn't exist within the `lib/resources/widgets/` directory.

<div id="forcefully-make-a-stateful-widget"></div>
<br>

### Forcefully make a stateful widget

**Arguments:**

Using the `--force` or `-f` flag will overwrite an existing widget if it already exists.

``` bash
dart run nylo_framework:main make:stateful_widget product_rating_widget --force
# or with the alias metro
metro make:stateful_widget product_rating_widget --force
```

<div id="make-api-service"></div>
<br>

## Make API Service

- [Making a new API Service](#making-a-new-api-service "Make a new API Service with Metro")
- [Making a new API Service with a model](#making-a-new-api-service-with-a-model "Make a new API Service with a model with Metro")
- [Make API Service using Postman](#make-api-service-using-postman "Create API services with Postman")
- [Forcefully make an API Service](#forcefully-make-an-api-service "Forcefully make a new API Service with Metro")

<div id="making-a-new-api-service"></div>
<br>

### Making a new API Service

You can make a new API service by running the below in the terminal.

``` bash
dart run nylo_framework:main make:api_service user
# or with the alias metro
metro make:api_service user_api_service
```

It will place the newly created API service in `lib/app/networking/`.

<div id="making-a-new-api-service-with-a-model"></div>
<br>

### Making a new API Service with a model

You can make a new API service with a model by running the below in the terminal.

**Arguments:**

Using the `--model` or `-m` option will create a new API service with a model.

``` bash
dart run nylo_framework:main make:api_service user --model="User"
# or with the alias metro
metro make:api_service user --model="User"
```

It will place the newly created API service in `lib/app/networking/`.

<div id="make-api-service-using-postman"></div>
<br>

### Use Postman to Make API Services

You can make new API services using Postman v2.1 collection files.

First, you must export your postman collection into a JSON file (using v2.1).

Copy the exported file into the `public/assets/postman/collections` directory.

After copying the file into the above directory, go to the terminal and run the following command.

**Arguments:**

Using the `--postman` or `-p` option will create a new API service using a Postman collection.

``` bash
dart run nylo_framework:main make:api_service my_collection_name --postman
# or with the alias metro
metro make:api_service my_collection_name --postman
```

This will prompt you to select a collection from the `public/assets/postman/collections` directory.

#### Postman Environments

If your postman collection has an environment, export the file and add it to the `public/assets/postman/environments` directory.

Then, run the below command.

``` bash
dart run nylo_framework:main make:api_service collection --postman
# or with the alias metro
metro make:api_service collection --postman
```

It will prompt you to select an environment from the `public/assets/postman/environments` directory.

#### What does it do?

It will also add **Models** to your project if your Postman collections have a saved response.
You can check if your collection has saved responses by going to your Postman collection, then selecting a request. You should see a dropdown which will contain any saved responses.

The saved response **name** will be used for the **Model** name that {{ config('app.name') }} will create.

<div id="forcefully-make-an-api-service"></div>
<br>

### Forcefully make an API Service

**Arguments:**

Using the `--force` or `-f` flag will overwrite an existing API Service if it already exists.

``` bash
dart run nylo_framework:main make:api_service user --force
# or with the alias metro
metro make:api_service user --force
```

<div id="make-event"></div>
<br>

## Make event

- [Making a new event](#making-a-new-event "Make a new event with Metro")
- [Forcefully make an event](#forcefully-make-an-event "Forcefully make a new event with Metro")

<div id="making-a-new-event"></div>
<br>

### Making a new event

You can make a new event by running the below in the terminal.

``` bash
dart run nylo_framework:main make:event login_event
# or with the alias metro
metro make:event login_event
```

This will create a new event in `lib/app/events`.

<div id="forcefully-make-an-event"></div>
<br>

### Forcefully make an event

**Arguments:**

Using the `--force` or `-f` flag will overwrite an existing event if it already exists.

``` bash
dart run nylo_framework:main make:event login_event --force
# or with the alias metro
metro make:event login_event --force
```

<div id="make-provider"></div>
<br>

## Make provider

- [Making a new provider](#making-a-new-provider "Make a new provider with Metro")
- [Forcefully make a provider](#forcefully-make-a-provider "Forcefully make a new provider with Metro")

<div id="making-a-new-provider"></div>
<br>

### Making a new provider

Create new providers in your application using the below command.

``` bash
dart run nylo_framework:main make:provider firebase_provider
# or with the alias metro
metro make:provider firebase_provider
```

It will place the newly created provider in `lib/app/providers/`.

<div id="forcefully-make-a-provider"></div>
<br>

### Forcefully make a provider

**Arguments:**

Using the `--force` or `-f` flag will overwrite an existing provider if it already exists.

``` bash
dart run nylo_framework:main make:provider firebase_provider --force
# or with the alias metro
metro make:provider firebase_provider --force
```

<div id="make-theme"></div>
<br>

## Make theme

- [Making a new theme](#making-a-new-theme "Make a new theme with Metro")
- [Forcefully make a theme](#forcefully-make-a-theme "Forcefully make a new theme with Metro")

<div id="making-a-new-theme"></div>
<br>

### Making a new theme

You can make themes by running the below in the terminal.

``` bash
dart run nylo_framework:main make:theme bright_theme
# or with the alias metro
metro make:theme bright_theme
```

This will create a new theme in `lib/app/themes`.

<div id="forcefully-make-a-theme"></div>
<br>

### Forcefully make a theme

**Arguments:**

Using the `--force` or `-f` flag will overwrite an existing theme if it already exists.

``` bash
dart run nylo_framework:main make:theme bright_theme --force
# or with the alias metro
metro make:theme bright_theme --force
```

<div id="make-forms"></div>
<br>

## Make Forms

- [Making a new form](#making-a-new-form "Make a new form with Metro")
- [Forcefully make a form](#forcefully-make-a-form "Forcefully make a new form with Metro")

<div id="making-a-new-form"></div>
<br>

### Making a new form

You can make a new form by running the below in the terminal.

``` bash
dart run nylo_framework:main make:form car_advert_form
# or with the alias metro
metro make:form car_advert_form
```

This will create a new form in `lib/app/forms`.

<div id="forcefully-make-a-form"></div>
<br>

### Forcefully make a form

**Arguments:**

Using the `--force` or `-f` flag will overwrite an existing form if it already exists.

``` bash
dart run nylo_framework:main make:form login_form --force
# or with the alias metro
metro make:form login_form --force
```

<div id="make-route-guard"></div>
<br>

## Make Route Guard

- [Making a new route guard](#making-a-new-route-guard "Make a new route guard with Metro")
- [Forcefully make a route guard](#forcefully-make-a-route-guard "Forcefully make a new route guard with Metro")

<div id="making-a-new-route-guard"></div>
<br>

### Making a new route guard

You can make a route guard by running the below in the terminal.

``` bash
dart run nylo_framework:main make:route_guard premium_content
# or with the alias metro
metro make:route_guard premium_content
```

This will create a new route guard in `lib/app/route_guards`.

<div id="forcefully-make-a-route-guard"></div>
<br>

### Forcefully make a route guard

**Arguments:**

Using the `--force` or `-f` flag will overwrite an existing route guard if it already exists.

``` bash
dart run nylo_framework:main make:route_guard premium_content --force
# or with the alias metro
metro make:route_guard premium_content --force
```

<div id="make-config-file"></div>
<br>

## Make Config File

- [Making a new config file](#making-a-new-config-file "Make a new config file with Metro")
- [Forcefully make a config file](#forcefully-make-a-config-file "Forcefully make a new config file with Metro")

<div id="making-a-new-config-file"></div>
<br>

### Making a new config file

You can make a new config file by running the below in the terminal.

``` bash
dart run nylo_framework:main make:config shopping_settings
# or with the alias metro
metro make:config shopping_settings
```

This will create a new config file in `lib/app/config`.

<div id="forcefully-make-a-config-file"></div>
<br>

### Forcefully make a config file

**Arguments:**

Using the `--force` or `-f` flag will overwrite an existing config file if it already exists.

``` bash
dart run nylo_framework:main make:config app_config --force
# or with the alias metro
metro make:config app_config --force
```

<div id="build-app-icons"></div>
<br>

## Build App Icons

You can generate all the app icons for IOS and Android by running the below command.

``` bash
dart run flutter_launcher_icons:main
```

This uses the <b>flutter_icons</b> configuration in your `pubspec.yaml` file.
