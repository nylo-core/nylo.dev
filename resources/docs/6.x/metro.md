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
  - [Make journey widget](#make-journey-widget "Make a new journey widget with Metro")
  - [Make API Service](#make-api-service "Make a new API Service with Metro")
    - [Using Postman](#make-api-service-using-postman "Create API services with Postman")
  - [Make Event](#make-event "Make a new Event with Metro")
  - [Make Provider](#make-provider "Make a new provider with Metro")
  - [Make Theme](#make-theme "Make a new theme with Metro")
  - [Make Forms](#make-forms "Make a new form with Metro")
  - [Make Route Guard](#make-route-guard "Make a new route guard with Metro")
  - [Make Config File](#make-config-file "Make a new config file with Metro")
  - [Make Command](#make-command "Make a new command with Metro")
- App Icons
  - [Building App Icons](#build-app-icons "Building App Icons with Metro")
- Slate
  - [Publishing files](#slate-publish "Publishing files with Slate")
  - [Install and publish](#slate-install "Install and publish with Slate")
- Custom Commands
  - [Creating custom commands](#creating-custom-commands "Creating Custom Commands")
  - [Running Custom Commands](#running-custom-commands "Running Custom Commands")
  - [Adding options to commands](#adding-options-to-custom-commands "Adding options to Custom Commands")
  - [Adding flags to commands](#adding-flags-to-custom-commands "Adding flags to Custom Commands")
  - [Helper methods](#custom-command-helper-methods "Custom Command Helper Methods")
  - [Interactive input methods](#interactive-input-methods "Interactive Input Methods")


<div id="introduction"></div>
<br>

## Introduction

Metro is a CLI tool that works under the hood of the {{ config('app.name') }} framework. 
It provides a lot of helpful tools to speed up development.

<div id="install"></div>
<br>

## Install

#### Mac guide

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
 
[Widget Commands]
  make:page
  make:stateful_widget
  make:stateless_widget
  make:state_managed_widget
  make:navigation_hub
  make:journey_widget
  make:form

[App Commands]
  make:model
  make:provider
  make:api_service
  make:controller
  make:event
  make:theme
  make:route_guard
  make:config
  make:interceptor
  make:command

[Custom Commands]
  motivational:quote
```

#### Windows Guide

1. Open PowerShell as an administrator.
2. Create a PowerShell profile if you don't have one:

``` bash
if (!(Test-Path -Path $PROFILE)) {
    New-Item -ItemType File -Path $PROFILE -Force
}
```

3. Open the profile in a text editor:

``` bash
notepad $PROFILE
```

4. Add the following line to the profile:

``` bash
function metro { dart run nylo_framework:main @args }
```

5. Save the file and close the editor.
6. Reload your PowerShell profile:

``` bash
. $PROFILE
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

<div id="make-journey-widget"></div>
<br>

## Make journey widget

- [Making a new journey widget](#making-a-new-journey-widget "Make a new journey widget with Metro")
- [Forcefully make a journey widget](#forcefully-make-a-journey-widget "Forcefully make a new journey widget with Metro")

<div id="making-a-new-journey-widget"></div>
<br>

### Making a new journey widget

You can make a new journey widget by running the below in the terminal.

``` bash
dart run nylo_framework:main make:journey_widget product_journey --parent="[NAVIGATION_HUB]"

# or with the alias metro
metro make:journey_widget product_journey --parent="[NAVIGATION_HUB]"

# Full example if you have a BaseNavigationHub
dart run nylo_framework:main make:journey_widget welcome,user_dob,user_photos --parent="Base"
```

The above will create a new widget if it doesn't exist within the `lib/resources/widgets/` directory.

The `--parent` argument is used to specify the parent widget that the new journey widget will be added to.

Example

``` bash
metro make:navigation_hub onboarding
```

Next, add the new journey widgets.
``` bash
dart run nylo_framework:main make:journey_widget welcome,user_dob,user_photos --parent="onboarding"

# or with the alias metro
metro make:journey_widget welcome,user_dob,user_photos --parent="onboarding"
```

<div id="forcefully-make-a-journey-widget"></div>
<br>

### Forcefully make a journey widget
**Arguments:**
Using the `--force` or `-f` flag will overwrite an existing widget if it already exists.

``` bash
dart run nylo_framework:main make:journey_widget product_journey --force -- parent="[YOUR_NAVIGATION_HUB]"
# or with the alias metro
metro make:journey_widget product_journey --force -- parent="[YOUR_NAVIGATION_HUB]"
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


<div id="make-command"></div>
<br>

## Make Command

- [Making a new command](#making-a-new-command "Make a new command with Metro")
- [Forcefully make a command](#forcefully-make-a-command "Forcefully make a new command with Metro")

<div id="making-a-new-command"></div>
<br>

### Making a new command

You can make a new command by running the below in the terminal.

``` bash
dart run nylo_framework:main make:command my_command
# or with the alias metro
metro make:command my_command
```

This will create a new command in `lib/app/commands`.

<div id="forcefully-make-a-command"></div>
<br>

### Forcefully make a command

**Arguments:**
Using the `--force` or `-f` flag will overwrite an existing command if it already exists.

``` bash
dart run nylo_framework:main make:command my_command --force
# or with the alias metro
metro make:command my_command --force
```


<div id="build-app-icons"></div>
<br>

## Build App Icons

You can generate all the app icons for IOS and Android by running the below command.

``` bash
dart run flutter_launcher_icons:main
```

This uses the <b>flutter_icons</b> configuration in your `pubspec.yaml` file.

<div id="slate-publish"></div>
<br>

## Publishing files with Slate

If you are new to Slate packages, you can read more about them [here](/docs/6.x/slates).

- [Publishing files with Slate](#publishing-files-with-slate "Publishing files with Slate")
- [Forcefully publish files with Slate](#forcefully-publish-files-with-slate "Forcefully publish files with Slate")

<div id="publishing-files-with-slate"></div>
<br>

### Publishing files with Slate

You can publish files with Slate by running the below in the terminal.

``` bash
dart run nylo_framework:main slate:publish my_slate_package
# or with the alias metro
metro slate:publish my_slate_package
```

This will publish all the files in your project.

<div id="forcefully-publish-files-with-slate"></div>
<br>

### Forcefully publish files with Slate

**Arguments:**

Using the `--force` or `-f` flag will overwrite any existing files in your project.

``` bash
dart run nylo_framework:main slate:publish --force
# or with the alias metro
metro slate:publish --force
```


<div id="slate-install"></div>
<br>

## Install and publish with Slate

You can install and publish files with Slate by running the below in the terminal.

``` bash
dart run nylo_framework:main slate:install my_slate_package
# or with the alias metro
metro slate:install my_slate_package
```

This will install the Slate package and publish all the files in your project.

<div id="custom-commands"></div>
<br>

## Custom Commands

Custom commands allow you to extend Nylo's CLI with your own project-specific commands. This feature enables you to automate repetitive tasks, implement deployment workflows, or add any custom functionality directly into your project's command-line tools.

- [Creating custom commands](#creating-custom-commands)
- [Running Custom Commands](#running-custom-commands)
- [Adding options to commands](#adding-options-to-custom-commands)
- [Adding flags to commands](#adding-flags-to-custom-commands)
- [Helper methods](#custom-command-helper-methods)

> **Note:** You currently cannot import nylo_framework.dart in your custom commands, please use ny_cli.dart instead.

<div id="creating-custom-commands"></div>
<br>

## Creating Custom Commands

To create a new custom command, you can use the `make:command` feature:

```bash
# Using Metro
metro make:command current_time

# Using Dart directly
dart run nylo_framework:main make:command current_time
```

You can specify a category for your command using the `--category` option:

```bash
# Specify a category
metro make:command current_time --category="project"
```

This will create a new command file at `lib/app/commands/current_time.dart` with the following structure:

``` dart
import 'package:nylo_framework/metro/ny_cli.dart';

void main(arguments) => _CurrentTimeCommand(arguments).run();

/// Current Time Command
///
/// Usage:
///   [From Terminal] dart run nylo_framework:main app:current_time
///   [With Metro]    metro app:current_time
class _CurrentTimeCommand extends NyCustomCommand {
  _CurrentTimeCommand(super.arguments);

  @override
  CommandBuilder builder(CommandBuilder command) {
    command.addOption('format', defaultValue: 'HH:mm:ss');
    return command;
  }

  @override
  Future<void> handle(CommandResult result) async {
      final format = result.getString("format");

      // Get the current time
      final now = DateTime.now();
      final DateFormat dateFormat = DateFormat(format);

      // Format the current time
      final formattedTime = dateFormat.format(now);
      info("The current time is " + formattedTime);
  }
}
```

The command will automatically be registered in the `lib/app/commands/custom_commands.json` file, which contains a list of all registered commands:

```json
[
  {
    "name": "install_firebase",
    "category": "project",
    "script": "install_firebase.dart"
  },
  {
    "name": "current_time",
    "category": "app",
    "script": "current_time.dart"
  }
]
```

<div id="running-custom-commands"></div>
<br>

## Running Custom Commands

Once created, you can run your custom command using either the Metro shorthand or the full Dart command:

```bash
# Using Metro
metro app:current_time

# Using Dart directly
dart run nylo_framework:main app:current_time
```

When you run `metro` or `dart run nylo_framework:main` without arguments, you'll see your custom commands listed in the menu under the "Custom Commands" section:

```
[Custom Commands]
  app:app_icon
  app:clear_pub
  project:install_firebase
  project:deploy
```

To display help information for your command, use the `--help` or `-h` flag:

```bash
metro project:install_firebase --help
```

<div id="adding-options-to-custom-commands"></div>
<br>

## Adding Options to Commands

Options allow your command to accept additional input from users. You can add options to your command in the `builder` method:

```dart
@override
CommandBuilder builder(CommandBuilder command) {
  
  // Add an option with a default value
  command.addOption(
    'environment',     // option name 
    abbr: 'e',         // short form abbreviation
    help: 'Target deployment environment', // help text
    defaultValue: 'development',  // default value
    allowed: ['development', 'staging', 'production'] // allowed values
  );
  
  return command;
}
```

Then access the option value in your command's `handle` method:

```dart
@override
Future<void> handle(CommandResult result) async {
  final environment = result.getString('environment');
  info('Deploying to $environment environment...');
  
  // Command implementation...
}
```

Example usage:

```bash
metro project:deploy --environment=production
# or using abbreviation
metro project:deploy -e production
```

<div id="adding-flags-to-custom-commands"></div>
<br>

## Adding Flags to Commands

Flags are boolean options that can be toggled on or off. Add flags to your command using the `addFlag` method:

```dart
@override
CommandBuilder builder(CommandBuilder command) {
  
  command.addFlag(
    'verbose',       // flag name
    abbr: 'v',       // short form abbreviation
    help: 'Enable verbose output', // help text 
    defaultValue: false  // default to off
  );
  
  return command;
}
```

Then check the flag state in your command's `handle` method:

```dart
@override
Future<void> handle(CommandResult result) async {
  final verbose = result.getBool('verbose');
  
  if (verbose) {
    info('Verbose mode enabled');
    // Additional logging...
  }
  
  // Command implementation...
}
```

Example usage:

```bash
metro project:deploy --verbose
# or using abbreviation
metro project:deploy -v
```

<div id="custom-command-helper-methods"></div>
<br>

## Helper Methods

The `NyCustomCommand` base class provides several helper methods to assist with common tasks:

#### Printing Messages

Here are some methods for printing messages in different colors:

| |  |
|-------------|-------------|
| [`info`](#custom-command-helper-formatting)      | Print an info message in blue text |
| [`error`](#custom-command-helper-formatting)     | Print an error message in red text |
| [`success`](#custom-command-helper-formatting)   | Print a success message in green text |
| [`warning`](#custom-command-helper-formatting)   | Print a warning message in yellow text |

#### Running Processes

Run processes and display their output in the console:

| |  |
|-------------|-------------|
| [`addPackage`](#custom-command-helper-add-package) | Add a package to `pubspec.yaml` |
| [`addPackages`](#custom-command-helper-add-packages) | Add multiple packages to `pubspec.yaml` |
| [`runProcess`](#custom-command-helper-run-process) | Run an external process and display output in the console |
| [`prompt`](#custom-command-helper-prompt)    | Collect user input as text |
| [`confirm`](#custom-command-helper-confirm)   | Ask a yes/no question and return a boolean result |
| [`select`](#custom-command-helper-select)    | Present a list of options and let the user select one |
| [`multiSelect`](#custom-command-helper-multi-select) | Allow the user to select multiple options from a list |

#### Network Requests

Making network requests via the console:

| |  |
|-------------|-------------|
| [`api`](#custom-command-helper-multi-select) | Make an API call using the Nylo API client |


#### Loading Spinner

Display a loading spinner while executing a function:

| |  |
|-------------|-------------|
| [`withSpinner`](#using-with-spinner) | Show a loading spinner while executing a function |
| [`createSpinner`](#manual-spinner-control) | Create a spinner instance for manual control |

#### Custom Command Helpers

You can also use the following helper methods to manage command arguments:

| |  |
|-------------|-------------|
| [`getString`](#custom-command-helper-get-string) | Get a string value from command arguments |
| [`getBool`](#custom-command-helper-get-bool)   | Get a boolean value from command arguments |
| [`getInt`](#custom-command-helper-get-int)    | Get an integer value from command arguments |
| [`sleep`](#custom-command-helper-sleep) | Pause execution for a specified duration |


### Running External Processes

```dart
// Run a process with output displayed in the console
await runProcess('flutter build web --release');

// Run a process silently
await runProcess('flutter pub get', silent: true);

// Run a process in a specific directory
await runProcess('git pull', workingDirectory: './my-project');
```

### Package Management

<div id="custom-command-helper-add-package"></div>
<div id="custom-command-helper-add-packages"></div>
<br>

```dart
// Add a package to pubspec.yaml
addPackage('firebase_core', version: '^2.4.0');

// Add a dev package to pubspec.yaml
addPackage('build_runner', dev: true);

// Add multiple packages at once
addPackages(['firebase_auth', 'firebase_storage', 'quickalert']);
```

<div id="custom-command-helper-formatting"></div>
<br>

### Output Formatting

```dart
// Print status messages with color coding
info('Processing files...');    // Blue text
error('Operation failed');      // Red text
success('Deployment complete'); // Green text
warning('Outdated package');    // Yellow text
```

<div id="interactive-input-methods"></div>
<br>

## Interactive Input Methods

The `NyCustomCommand` base class provides several methods for collecting user input in the terminal. These methods make it easy to create interactive command-line interfaces for your custom commands.

<div id="custom-command-helper-prompt"></div>
<br>

### Text Input

```dart
String prompt(String question, {String defaultValue = ''})
```

Displays a question to the user and collects their text response.

**Parameters:**
- `question`: The question or prompt to display
- `defaultValue`: Optional default value if the user just presses Enter

**Returns:** The user's input as a string, or the default value if no input was provided

**Example:**
```dart
final name = prompt('What is your project name?', defaultValue: 'my_app');
final description = prompt('Enter a project description:');
```

<div id="custom-command-helper-confirm"></div>
<br>

### Confirmation

```dart
bool confirm(String question, {bool defaultValue = false})
```

Asks the user a yes/no question and returns a boolean result.

**Parameters:**
- `question`: The yes/no question to ask
- `defaultValue`: The default answer (true for yes, false for no)

**Returns:** `true` if the user answered yes, `false` if they answered no

**Example:**
```dart
if (confirm('Would you like to continue?', defaultValue: true)) {
  // User confirmed or pressed Enter (accepting the default)
  await runProcess('flutter pub get');
} else {
  // User declined
  info('Operation canceled');
}
```

<div id="custom-command-helper-select"></div>
<br>

### Single Selection

```dart
String select(String question, List<String> options, {String? defaultOption})
```

Presents a list of options and lets the user select one.

**Parameters:**
- `question`: The selection prompt
- `options`: List of available options
- `defaultOption`: Optional default selection

**Returns:** The selected option as a string

**Example:**
```dart
final environment = select(
  'Select deployment environment:',
  ['development', 'staging', 'production'],
  defaultOption: 'development'
);

info('Deploying to $environment environment...');
```

<div id="custom-command-helper-multi-select"></div>
<br>

### Multiple Selection

```dart
List<String> multiSelect(String question, List<String> options)
```

Allows the user to select multiple options from a list.

**Parameters:**
- `question`: The selection prompt
- `options`: List of available options

**Returns:** A list of the selected options

**Example:**
```dart
final packages = multiSelect(
  'Select packages to install:',
  ['firebase_auth', 'dio', 'provider', 'shared_preferences', 'path_provider']
);

if (packages.isNotEmpty) {
  info('Installing ${packages.length} packages...');
  addPackages(packages);
  await runProcess('flutter pub get');
}
```

<div id="custom-command-helper-api"></div>
<br>

## API Helper Method

The `api` helper method simplifies making network requests from your custom commands.

```dart
Future<T?> api<T>(Future<T?> Function(ApiService) request) async
```

## Basic Usage Examples

### GET Request

```dart
// Fetch data
final userData = await api((request) => 
  request.get('https://api.example.com/users/1')
);
```

### POST Request

```dart
// Create a resource
final result = await api((request) => 
  request.post(
    'https://api.example.com/items',
    data: {'name': 'New Item', 'price': 19.99}
  )
);
```

### PUT Request

```dart
// Update a resource
final updateResult = await api((request) => 
  request.put(
    'https://api.example.com/items/42',
    data: {'name': 'Updated Item', 'price': 29.99}
  )
);
```

### DELETE Request

```dart
// Delete a resource
final deleteResult = await api((request) => request.delete('https://api.example.com/items/42'));
```

### PATCH Request

```dart
// Partially update a resource
final patchResult = await api((request) => request.patch(
    'https://api.example.com/items/42',
    data: {'price': 24.99}
  )
);
```

### With Query Parameters

```dart
// Add query parameters
final searchResults = await api((request) => request.get(
    'https://api.example.com/search',
    queryParameters: {'q': 'keyword', 'limit': 10}
  )
);
```

### With Spinner

```dart
// Using with spinner for better UI
final data = await withSpinner(
  task: () async {
    final data = await api((request) => request.get('https://api.example.com/config'));
    // Process the data
  },
  message: 'Loading configuration',
);
```


<div id="using-spinners"></div>
<br>

## Spinner Functionality

Spinners provide visual feedback during long-running operations in your custom commands. They display an animated indicator along with a message while your command executes asynchronous tasks, enhancing the user experience by showing progress and status.

- [Using with spinner](#using-with-spinner)
- [Manual spinner control](#manual-spinner-control)
- [Examples](#spinner-examples)

<div id="using-with-spinner"></div>
<br>

## Using with spinner

The `withSpinner` method lets you wrap an asynchronous task with a spinner animation that automatically starts when the task begins and stops when it completes or fails:

```dart
Future<T> withSpinner<T>({
  required Future<T> Function() task,
  required String message,
  String? successMessage,
  String? errorMessage,
}) async
```

**Parameters:**
- `task`: The asynchronous function to execute
- `message`: Text to display while the spinner is running
- `successMessage`: Optional message to display upon successful completion
- `errorMessage`: Optional message to display if the task fails

**Returns:** The result of the task function

**Example:**
```dart
@override
Future<void> handle(CommandResult result) async {
  // Run a task with a spinner
  final projectFiles = await withSpinner(
    task: () async {
      // Long-running task (e.g., analyzing project files)
      await sleep(2);
      return ['pubspec.yaml', 'lib/main.dart', 'README.md'];
    },
    message: 'Analyzing project structure',
    successMessage: 'Project analysis complete',
    errorMessage: 'Failed to analyze project',
  );
  
  // Continue with the results
  info('Found ${projectFiles.length} key files');
}
```

<div id="manual-spinner-control"></div>
<br>

## Manual Spinner Control

For more complex scenarios where you need to control the spinner state manually, you can create a spinner instance:

```dart
ConsoleSpinner createSpinner(String message)
```

**Parameters:**
- `message`: Text to display while the spinner is running

**Returns:** A `ConsoleSpinner` instance that you can manually control

**Example with manual control:**
```dart
@override
Future<void> handle(CommandResult result) async {
  // Create a spinner instance
  final spinner = createSpinner('Deploying to production');
  spinner.start();
  
  try {
    // First task
    await runProcess('flutter clean', silent: true);
    spinner.update(message: 'Building release version');
    
    // Second task
    await runProcess('flutter build web --release', silent: true);
    spinner.update(message: 'Uploading to server');
    
    // Third task
    await runProcess('./deploy.sh', silent: true);
    
    // Complete successfully
    spinner.stop(completionMessage: 'Deployment completed successfully', success: true);
  } catch (e) {
    // Handle failure
    spinner.stop(completionMessage: 'Deployment failed: $e', success: false);
    rethrow;
  }
}
```

<div id="spinner-examples"></div>
<br>

## Examples

### Simple Task with Spinner

```dart
@override
Future<void> handle(CommandResult result) async {
  await withSpinner(
    task: () async {
      // Install dependencies
      await runProcess('flutter pub get', silent: true);
      return true;
    },
    message: 'Installing dependencies',
    successMessage: 'Dependencies installed successfully',
  );
}
```

### Multiple Consecutive Operations

```dart
@override
Future<void> handle(CommandResult result) async {
  // First operation with spinner
  await withSpinner(
    task: () => runProcess('flutter clean', silent: true),
    message: 'Cleaning project',
  );
  
  // Second operation with spinner
  await withSpinner(
    task: () => runProcess('flutter pub get', silent: true),
    message: 'Updating dependencies',
  );
  
  // Third operation with spinner
  final buildSuccess = await withSpinner(
    task: () async {
      await runProcess('flutter build apk --release', silent: true);
      return true;
    },
    message: 'Building release APK',
    successMessage: 'Release APK built successfully',
  );
  
  if (buildSuccess) {
    success('Build process completed');
  }
}
```

### Complex Workflow with Manual Control

```dart
@override
Future<void> handle(CommandResult result) async {
  final spinner = createSpinner('Starting deployment process');
  spinner.start();
  
  try {
    // Run multiple steps with status updates
    for (var i = 1; i <= 5; i++) {
      spinner.update(message: 'Deployment step $i of 5');
      
      // Simulate work for each step
      await sleep(1);
      
      // Pretend step 3 has a warning we want to show
      if (i == 3) {
        // Temporarily pause the spinner to show a warning
        spinner.pause();
        warning('Configuration file missing, using defaults');
        // Resume the spinner
        spinner.resume();
      }
    }
    
    // Complete the process
    spinner.stop(completionMessage: 'Deployment completed successfully', success: true);
    
  } catch (e) {
    spinner.stop(completionMessage: 'Deployment failed', success: false);
    error('Error details: $e');
  }
}
```

Using spinners in your custom commands provides clear visual feedback to users during long-running operations, creating a more polished and professional command-line experience.

<div id="custom-command-helper-get-string"></div>
<br>

### Get a string value from options

```dart
String getString(String name, {String defaultValue = ''})
```

**Parameters:**

- `name`: The name of the option to retrieve
- `defaultValue`: Optional default value if the option is not provided

**Returns:** The value of the option as a string

**Example:**
```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addOption("name", defaultValue: "Anthony");
  return command;
}

Future<void> handle(CommandResult result) async {
  final name = result.getString('name');
  info('Hello, $name!');
}
```

<div id="custom-command-helper-get-bool"></div>
<br>

### Get a bool value from options

```dart
bool getBool(String name, {bool defaultValue = false})
```

**Parameters:**
- `name`: The name of the option to retrieve
- `defaultValue`: Optional default value if the option is not provided

**Returns:** The value of the option as a boolean


**Example:**
```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addFlag("verbose", defaultValue: false);
  return command;
}

Future<void> handle(CommandResult result) async {
  final verbose = result.getBool('verbose');
  if (verbose) {
    info('Verbose mode enabled');
  } else {
    info('Verbose mode disabled');
  }
}
```

<div id="custom-command-helper-get-int"></div>
<br>

### Get an int value from options

```dart
int getInt(String name, {int defaultValue = 0})
```

**Parameters:**
- `name`: The name of the option to retrieve
- `defaultValue`: Optional default value if the option is not provided

**Returns:** The value of the option as an integer

**Example:**
```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addOption("count", defaultValue: 5);
  return command;
}

Future<void> handle(CommandResult result) async {
  final count = result.getInt('count');
  info('Count is set to $count');
}
```

<div id="custom-command-helper-sleep"></div>
<br>

### Sleep for a specified duration

```dart
void sleep(int seconds)
```

**Parameters:**
- `seconds`: The number of seconds to sleep

**Returns:** None

**Example:**
```dart
@override
Future<void> handle(CommandResult result) async {
  info('Sleeping for 5 seconds...');
  sleep(5);
  info('Awake now!');
}
```