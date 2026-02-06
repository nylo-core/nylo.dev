# Metro CLI tool

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Install](#install "Installing Metro Alias for {{ config('app.name') }}")
- Make Commands
  - [Make controller](#make-controller "Make a new controller")
  - [Make model](#make-model "Make a new model")
  - [Make page](#make-page "Make a new page")
  - [Make stateless widget](#make-stateless-widget "Make a new stateless widget")
  - [Make stateful widget](#make-stateful-widget "Make a new stateful widget")
  - [Make journey widget](#make-journey-widget "Make a new journey widget")
  - [Make API Service](#make-api-service "Make a new API Service")
  - [Make Event](#make-event "Make a new Event")
  - [Make Provider](#make-provider "Make a new provider")
  - [Make Theme](#make-theme "Make a new theme")
  - [Make Forms](#make-forms "Make a new form")
  - [Make Route Guard](#make-route-guard "Make a new route guard")
  - [Make Config File](#make-config-file "Make a new config file")
  - [Make Command](#make-command "Make a new command")
  - [Make State Managed Widget](#make-state-managed-widget "Make a new state managed widget")
  - [Make Navigation Hub](#make-navigation-hub "Make a new navigation hub")
  - [Make Bottom Sheet Modal](#make-bottom-sheet-modal "Make a new bottom sheet modal")
  - [Make Button](#make-button "Make a new button")
  - [Make Interceptor](#make-interceptor "Make a new interceptor")
  - [Make Env File](#make-env-file "Make a new env file")
  - [Make Key](#make-key "Generate APP_KEY")
- App Icons
  - [Building App Icons](#build-app-icons "Building App Icons with Metro")
- Custom Commands
  - [Creating custom commands](#creating-custom-commands "Creating Custom Commands")
  - [Running Custom Commands](#running-custom-commands "Running Custom Commands")
  - [Adding options to commands](#adding-options-to-custom-commands "Adding options to Custom Commands")
  - [Adding flags to commands](#adding-flags-to-custom-commands "Adding flags to Custom Commands")
  - [Helper methods](#custom-command-helper-methods "Custom Command Helper Methods")
  - [Interactive input methods](#interactive-input-methods "Interactive Input Methods")
  - [Output formatting](#output-formatting "Output Formatting")
  - [File system helpers](#file-system-helpers "File System Helpers")
  - [JSON and YAML helpers](#json-yaml-helpers "JSON and YAML Helpers")
  - [Case conversion helpers](#case-conversion-helpers "Case Conversion Helpers")
  - [Project path helpers](#project-path-helpers "Project Path Helpers")
  - [Platform helpers](#platform-helpers "Platform Helpers")
  - [Dart and Flutter commands](#dart-flutter-commands "Dart and Flutter Commands")
  - [Dart file manipulation](#dart-file-manipulation "Dart File Manipulation")
  - [Directory helpers](#directory-helpers "Directory Helpers")
  - [Validation helpers](#validation-helpers "Validation Helpers")
  - [File scaffolding](#file-scaffolding "File Scaffolding")
  - [Task runner](#task-runner "Task Runner")
  - [Table output](#table-output "Table Output")
  - [Progress bar](#progress-bar "Progress Bar")


<div id="introduction"></div>

## Introduction

Metro is a CLI tool that works under the hood of the {{ config('app.name') }} framework. 
It provides a lot of helpful tools to speed up development.

<div id="install"></div>

## Install

When you create a new Nylo project using `nylo init`, the `metro` command is automatically configured for your terminal. You can start using it immediately in any Nylo project.

Run `metro` from your project directory to see all available commands:

``` bash
metro
```

You should see an output similar to the below.

``` bash
Metro - Nylo's Companion to Build Flutter apps by Anthony Gordon

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
  make:bottom_sheet_modal
  make:button
  make:form

[Helper Commands]
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
  make:env
  make:key
```

<div id="make-controller"></div>

## Make controller

- [Making a new controller](#making-a-new-controller "Make a new controller with Metro")
- [Forcefully make a controller](#forcefully-make-a-controller "Forcefully make a new controller with Metro")
<div id="making-a-new-controller"></div>

### Making a new controller

You can make a new controller by running the below in the terminal.

``` bash
metro make:controller profile_controller
```

This will create a new controller if it doesn't exist within the `lib/app/controllers/` directory.

<div id="forcefully-make-a-controller"></div>

### Forcefully make a controller

**Arguments:**

Using the `--force` or `-f` flag will overwrite an existing controller if it already exists.

``` bash
metro make:controller profile_controller --force
```

<div id="make-model"></div>

## Make model

- [Making a new model](#making-a-new-model "Make a new model with Metro")
- [Make model from JSON](#make-model-from-json "Make a new model from JSON with Metro")
- [Forcefully make a model](#forcefully-make-a-model "Forcefully make a new model with Metro")
<div id="making-a-new-model"></div>

### Making a new model

You can make a new model by running the below in the terminal.

``` bash
metro make:model product
```

It will place the newly created model in `lib/app/models/`.

<div id="make-model-from-json"></div>

### Make a model from JSON

**Arguments:**

Using the `--json` or `-j` flag will create a new model from a JSON payload.

``` bash
metro make:model product --json
```

Then, you can paste your JSON into the terminal and it will generate a model for you.

<div id="forcefully-make-a-model"></div>

### Forcefully make a model

**Arguments:**

Using the `--force` or `-f` flag will overwrite an existing model if it already exists.

``` bash
metro make:model product --force
```

<div id="make-page"></div>

## Make page

- [Making a new page](#making-a-new-page "Make a new page with Metro")
- [Create a page with a controller](#create-a-page-with-a-controller "Make a new page with a controller with Metro")
- [Create an auth page](#create-an-auth-page "Make a new auth page with Metro")
- [Create an initial page](#create-an-initial-page "Make a new initial page with Metro")
- [Forcefully make a page](#forcefully-make-a-page "Forcefully make a new page with Metro")

<div id="making-a-new-page"></div>

### Making a new page

You can make a new page by running the below in the terminal.

``` bash
metro make:page product_page
```

This will create a new page if it doesn't exist within the `lib/resources/pages/` directory.

<div id="create-a-page-with-a-controller"></div>

### Create a page with a controller

You can make a new page with a controller by running the below in the terminal.

**Arguments:**

Using the `--controller` or `-c` flag will create a new page with a controller.

``` bash
metro make:page product_page -c
```

<div id="create-an-auth-page"></div>

### Create an auth page

You can make a new auth page by running the below in the terminal.

**Arguments:**

Using the `--auth` or `-a` flag will create a new auth page.

``` bash
metro make:page login_page -a
```

<div id="create-an-initial-page"></div>

### Create an initial page

You can make a new initial page by running the below in the terminal.

**Arguments:**

Using the `--initial` or `-i` flag will create a new initial page.

``` bash
metro make:page home_page -i
```

<div id="forcefully-make-a-page"></div>

### Forcefully make a page

**Arguments:**

Using the `--force` or `-f` flag will overwrite an existing page if it already exists.

``` bash
metro make:page product_page --force
```

<div id="make-stateless-widget"></div>

## Make stateless widget

- [Making a new stateless widget](#making-a-new-stateless-widget "Make a new stateless widget with Metro")
- [Forcefully make a stateless widget](#forcefully-make-a-stateless-widget "Forcefully make a new stateless widget with Metro")
<div id="making-a-new-stateless-widget"></div>

### Making a new stateless widget

You can make a new stateless widget by running the below in the terminal.

``` bash
metro make:stateless_widget product_rating_widget
```

The above will create a new widget if it doesn't exist within the `lib/resources/widgets/` directory.

<div id="forcefully-make-a-stateless-widget"></div>

### Forcefully make a stateless widget

**Arguments:**

Using the `--force` or `-f` flag will overwrite an existing widget if it already exists.

``` bash
metro make:stateless_widget product_rating_widget --force
```

<div id="make-stateful-widget"></div>

## Make stateful widget

- [Making a new stateful widget](#making-a-new-stateful-widget "Make a new stateful widget with Metro")
- [Forcefully make a stateful widget](#forcefully-make-a-stateful-widget "Forcefully make a new stateful widget with Metro")

<div id="making-a-new-stateful-widget"></div>

### Making a new stateful widget

You can make a new stateful widget by running the below in the terminal.

``` bash
metro make:stateful_widget product_rating_widget
```

The above will create a new widget if it doesn't exist within the `lib/resources/widgets/` directory.

<div id="forcefully-make-a-stateful-widget"></div>

### Forcefully make a stateful widget

**Arguments:**

Using the `--force` or `-f` flag will overwrite an existing widget if it already exists.

``` bash
metro make:stateful_widget product_rating_widget --force
```

<div id="make-journey-widget"></div>

## Make journey widget

- [Making a new journey widget](#making-a-new-journey-widget "Make a new journey widget with Metro")
- [Forcefully make a journey widget](#forcefully-make-a-journey-widget "Forcefully make a new journey widget with Metro")

<div id="making-a-new-journey-widget"></div>

### Making a new journey widget

You can make a new journey widget by running the below in the terminal.

``` bash
metro make:journey_widget product_journey --parent="[NAVIGATION_HUB]"

# Full example if you have a BaseNavigationHub
metro make:journey_widget welcome,user_dob,user_photos --parent="Base"
```

The above will create a new widget if it doesn't exist within the `lib/resources/widgets/` directory.

The `--parent` argument is used to specify the parent widget that the new journey widget will be added to.

Example

``` bash
metro make:navigation_hub onboarding
```

Next, add the new journey widgets.
``` bash
metro make:journey_widget welcome,user_dob,user_photos --parent="onboarding"
```

<div id="forcefully-make-a-journey-widget"></div>

### Forcefully make a journey widget
**Arguments:**
Using the `--force` or `-f` flag will overwrite an existing widget if it already exists.

``` bash
metro make:journey_widget product_journey --force --parent="[YOUR_NAVIGATION_HUB]"
```

<div id="make-api-service"></div>

## Make API Service

- [Making a new API Service](#making-a-new-api-service "Make a new API Service with Metro")
- [Making a new API Service with a model](#making-a-new-api-service-with-a-model "Make a new API Service with a model with Metro")
- [Make API Service using Postman](#make-api-service-using-postman "Create API services with Postman")
- [Forcefully make an API Service](#forcefully-make-an-api-service "Forcefully make a new API Service with Metro")

<div id="making-a-new-api-service"></div>

### Making a new API Service

You can make a new API service by running the below in the terminal.

``` bash
metro make:api_service user_api_service
```

It will place the newly created API service in `lib/app/networking/`.

<div id="making-a-new-api-service-with-a-model"></div>

### Making a new API Service with a model

You can make a new API service with a model by running the below in the terminal.

**Arguments:**

Using the `--model` or `-m` option will create a new API service with a model.

``` bash
metro make:api_service user --model="User"
```

It will place the newly created API service in `lib/app/networking/`.

### Forcefully make an API Service

**Arguments:**

Using the `--force` or `-f` flag will overwrite an existing API Service if it already exists.

``` bash
metro make:api_service user --force
```

<div id="make-event"></div>

## Make event

- [Making a new event](#making-a-new-event "Make a new event with Metro")
- [Forcefully make an event](#forcefully-make-an-event "Forcefully make a new event with Metro")

<div id="making-a-new-event"></div>

### Making a new event

You can make a new event by running the below in the terminal.

``` bash
metro make:event login_event
```

This will create a new event in `lib/app/events`.

<div id="forcefully-make-an-event"></div>

### Forcefully make an event

**Arguments:**

Using the `--force` or `-f` flag will overwrite an existing event if it already exists.

``` bash
metro make:event login_event --force
```

<div id="make-provider"></div>

## Make provider

- [Making a new provider](#making-a-new-provider "Make a new provider with Metro")
- [Forcefully make a provider](#forcefully-make-a-provider "Forcefully make a new provider with Metro")

<div id="making-a-new-provider"></div>

### Making a new provider

Create new providers in your application using the below command.

``` bash
metro make:provider firebase_provider
```

It will place the newly created provider in `lib/app/providers/`.

<div id="forcefully-make-a-provider"></div>

### Forcefully make a provider

**Arguments:**

Using the `--force` or `-f` flag will overwrite an existing provider if it already exists.

``` bash
metro make:provider firebase_provider --force
```

<div id="make-theme"></div>

## Make theme

- [Making a new theme](#making-a-new-theme "Make a new theme with Metro")
- [Forcefully make a theme](#forcefully-make-a-theme "Forcefully make a new theme with Metro")

<div id="making-a-new-theme"></div>

### Making a new theme

You can make themes by running the below in the terminal.

``` bash
metro make:theme bright_theme
```

This will create a new theme in `lib/resources/themes/`.

<div id="forcefully-make-a-theme"></div>

### Forcefully make a theme

**Arguments:**

Using the `--force` or `-f` flag will overwrite an existing theme if it already exists.

``` bash
metro make:theme bright_theme --force
```

<div id="make-forms"></div>

## Make Forms

- [Making a new form](#making-a-new-form "Make a new form with Metro")
- [Forcefully make a form](#forcefully-make-a-form "Forcefully make a new form with Metro")

<div id="making-a-new-form"></div>

### Making a new form

You can make a new form by running the below in the terminal.

``` bash
metro make:form car_advert_form
```

This will create a new form in `lib/app/forms`.

<div id="forcefully-make-a-form"></div>

### Forcefully make a form

**Arguments:**

Using the `--force` or `-f` flag will overwrite an existing form if it already exists.

``` bash
metro make:form login_form --force
```

<div id="make-route-guard"></div>

## Make Route Guard

- [Making a new route guard](#making-a-new-route-guard "Make a new route guard with Metro")
- [Forcefully make a route guard](#forcefully-make-a-route-guard "Forcefully make a new route guard with Metro")

<div id="making-a-new-route-guard"></div>

### Making a new route guard

You can make a route guard by running the below in the terminal.

``` bash
metro make:route_guard premium_content
```

This will create a new route guard in `lib/app/route_guards`.

<div id="forcefully-make-a-route-guard"></div>

### Forcefully make a route guard

**Arguments:**

Using the `--force` or `-f` flag will overwrite an existing route guard if it already exists.

``` bash
metro make:route_guard premium_content --force
```

<div id="make-config-file"></div>

## Make Config File

- [Making a new config file](#making-a-new-config-file "Make a new config file with Metro")
- [Forcefully make a config file](#forcefully-make-a-config-file "Forcefully make a new config file with Metro")

<div id="making-a-new-config-file"></div>

### Making a new config file

You can make a new config file by running the below in the terminal.

``` bash
metro make:config shopping_settings
```

This will create a new config file in `lib/app/config`.

<div id="forcefully-make-a-config-file"></div>

### Forcefully make a config file

**Arguments:**

Using the `--force` or `-f` flag will overwrite an existing config file if it already exists.

``` bash
metro make:config app_config --force
```


<div id="make-command"></div>

## Make Command

- [Making a new command](#making-a-new-command "Make a new command with Metro")
- [Forcefully make a command](#forcefully-make-a-command "Forcefully make a new command with Metro")

<div id="making-a-new-command"></div>

### Making a new command

You can make a new command by running the below in the terminal.

``` bash
metro make:command my_command
```

This will create a new command in `lib/app/commands`.

<div id="forcefully-make-a-command"></div>

### Forcefully make a command

**Arguments:**
Using the `--force` or `-f` flag will overwrite an existing command if it already exists.

``` bash
metro make:command my_command --force
```


<div id="make-state-managed-widget"></div>

## Make State Managed Widget

You can make a new state managed widget by running the below in the terminal.

``` bash
metro make:state_managed_widget product_rating_widget
```

The above will create a new widget in `lib/resources/widgets/`.

Using the `--force` or `-f` flag will overwrite an existing widget if it already exists.

``` bash
metro make:state_managed_widget product_rating_widget --force
```

<div id="make-navigation-hub"></div>

## Make Navigation Hub

You can make a new navigation hub by running the below in the terminal.

``` bash
metro make:navigation_hub dashboard
```

This will create a new navigation hub in `lib/resources/pages/` and add the route automatically.

**Arguments:**

| Flag | Short | Description |
|------|-------|-------------|
| `--auth` | `-a` | Create as an auth page |
| `--initial` | `-i` | Create as the initial page |
| `--force` | `-f` | Overwrite if exists |

``` bash
# Create as the initial page
metro make:navigation_hub dashboard --initial
```

<div id="make-bottom-sheet-modal"></div>

## Make Bottom Sheet Modal

You can make a new bottom sheet modal by running the below in the terminal.

``` bash
metro make:bottom_sheet_modal payment_options
```

This will create a new bottom sheet modal in `lib/resources/widgets/`.

Using the `--force` or `-f` flag will overwrite an existing modal if it already exists.

``` bash
metro make:bottom_sheet_modal payment_options --force
```

<div id="make-button"></div>

## Make Button

You can make a new button widget by running the below in the terminal.

``` bash
metro make:button checkout_button
```

This will create a new button widget in `lib/resources/widgets/`.

Using the `--force` or `-f` flag will overwrite an existing button if it already exists.

``` bash
metro make:button checkout_button --force
```

<div id="make-interceptor"></div>

## Make Interceptor

You can make a new network interceptor by running the below in the terminal.

``` bash
metro make:interceptor auth_interceptor
```

This will create a new interceptor in `lib/app/networking/dio/interceptors/`.

Using the `--force` or `-f` flag will overwrite an existing interceptor if it already exists.

``` bash
metro make:interceptor auth_interceptor --force
```

<div id="make-env-file"></div>

## Make Env File

You can make a new environment file by running the below in the terminal.

``` bash
metro make:env .env.staging
```

This will create a new `.env` file in your project root.

<div id="make-key"></div>

## Make Key

Generate a secure `APP_KEY` for environment encryption. This is used for encrypted `.env` files in v7.

``` bash
metro make:key
```

**Arguments:**

| Flag / Option | Short | Description |
|---------------|-------|-------------|
| `--force` | `-f` | Overwrite existing APP_KEY |
| `--file` | `-e` | Target .env file (default: `.env`) |

``` bash
# Generate key and overwrite existing
metro make:key --force

# Generate key for a specific env file
metro make:key --file=.env.production
```

<div id="build-app-icons"></div>

## Build App Icons

You can generate all the app icons for IOS and Android by running the below command.

``` bash
dart run flutter_launcher_icons:main
```

This uses the <b>flutter_icons</b> configuration in your `pubspec.yaml` file.

<div id="custom-commands"></div>

## Custom Commands

Custom commands allow you to extend Nylo's CLI with your own project-specific commands. This feature enables you to automate repetitive tasks, implement deployment workflows, or add any custom functionality directly into your project's command-line tools.

- [Creating custom commands](#creating-custom-commands)
- [Running Custom Commands](#running-custom-commands)
- [Adding options to commands](#adding-options-to-custom-commands)
- [Adding flags to commands](#adding-flags-to-custom-commands)
- [Helper methods](#custom-command-helper-methods)

> **Note:** You currently cannot import nylo_framework.dart in your custom commands, please use ny_cli.dart instead.

<div id="creating-custom-commands"></div>

## Creating Custom Commands

To create a new custom command, you can use the `make:command` feature:

```bash
metro make:command current_time
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
///   metro app:current_time
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

The command will automatically be registered in the `lib/app/commands/commands.json` file, which contains a list of all registered commands:

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

## Running Custom Commands

Once created, you can run your custom command using either the Metro shorthand or the full Dart command:

```bash
metro app:current_time
```

When you run `metro` without arguments, you'll see your custom commands listed in the menu under the "Custom Commands" section:

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

```dart
// Add a package to pubspec.yaml
addPackage('firebase_core', version: '^2.4.0');

// Add a dev package to pubspec.yaml
addPackage('build_runner', dev: true);

// Add multiple packages at once
addPackages(['firebase_auth', 'firebase_storage', 'quickalert']);
```

<div id="custom-command-helper-formatting"></div>

### Output Formatting

```dart
// Print status messages with color coding
info('Processing files...');    // Blue text
error('Operation failed');      // Red text
success('Deployment complete'); // Green text
warning('Outdated package');    // Yellow text
```

<div id="interactive-input-methods"></div>

## Interactive Input Methods

The `NyCustomCommand` base class provides several methods for collecting user input in the terminal. These methods make it easy to create interactive command-line interfaces for your custom commands.

<div id="custom-command-helper-prompt"></div>

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

## Spinner Functionality

Spinners provide visual feedback during long-running operations in your custom commands. They display an animated indicator along with a message while your command executes asynchronous tasks, enhancing the user experience by showing progress and status.

- [Using with spinner](#using-with-spinner)
- [Manual spinner control](#manual-spinner-control)
- [Examples](#spinner-examples)

<div id="using-with-spinner"></div>

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
    spinner.update('Building release version');

    // Second task
    await runProcess('flutter build web --release', silent: true);
    spinner.update('Uploading to server');

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
    spinner.update('Step 1: Cleaning project');
    await runProcess('flutter clean', silent: true);

    spinner.update('Step 2: Fetching dependencies');
    await runProcess('flutter pub get', silent: true);

    spinner.update('Step 3: Building release');
    await runProcess('flutter build web --release', silent: true);

    // Complete the process
    spinner.stop(completionMessage: 'Deployment completed successfully', success: true);

  } catch (e) {
    spinner.stop(completionMessage: 'Deployment failed: $e', success: false);
  }
}
```

Using spinners in your custom commands provides clear visual feedback to users during long-running operations, creating a more polished and professional command-line experience.

<div id="custom-command-helper-get-string"></div>

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
  await sleep(5);
  info('Awake now!');
}
```

<div id="output-formatting"></div>

## Output Formatting

Beyond the basic `info`, `error`, `success`, and `warning` methods, `NyCustomCommand` provides additional output helpers:

```dart
@override
Future<void> handle(CommandResult result) async {
  // Print plain text (no color)
  line('Processing your request...');

  // Print blank lines
  newLine();       // one blank line
  newLine(3);      // three blank lines

  // Print a muted comment (gray text)
  comment('This is a background note');

  // Print a prominent alert box
  alert('Important: Please read carefully');

  // Ask is an alias for prompt
  final name = ask('What is your name?');

  // Hidden input for sensitive data (e.g., passwords, API keys)
  final apiKey = promptSecret('Enter your API key:');

  // Abort the command with an error message and exit code
  if (name.isEmpty) {
    abort('Name is required');  // exits with code 1
  }
}
```

| Method | Description |
|--------|-------------|
| `line(String message)` | Print plain text without color |
| `newLine([int count = 1])` | Print blank lines |
| `comment(String message)` | Print muted/gray text |
| `alert(String message)` | Print a prominent alert box |
| `ask(String question, {String defaultValue})` | Alias for `prompt` |
| `promptSecret(String question)` | Hidden input for sensitive data |
| `abort([String? message, int exitCode = 1])` | Exit the command with an error |

<div id="file-system-helpers"></div>

## File System Helpers

`NyCustomCommand` includes built-in file system helpers so you don't need to manually import `dart:io` for common operations.

### Reading and Writing Files

```dart
@override
Future<void> handle(CommandResult result) async {
  // Check if a file exists
  if (fileExists('lib/config/app.dart')) {
    info('Config file found');
  }

  // Check if a directory exists
  if (directoryExists('lib/app/models')) {
    info('Models directory found');
  }

  // Read a file (async)
  String content = await readFile('pubspec.yaml');

  // Read a file (sync)
  String contentSync = readFileSync('pubspec.yaml');

  // Write to a file (async)
  await writeFile('lib/generated/output.dart', 'class Output {}');

  // Write to a file (sync)
  writeFileSync('lib/generated/output.dart', 'class Output {}');

  // Append content to a file
  await appendFile('log.txt', 'New log entry\n');

  // Ensure a directory exists (creates it if missing)
  await ensureDirectory('lib/generated');

  // Delete a file
  await deleteFile('lib/generated/output.dart');

  // Copy a file
  await copyFile('lib/config/app.dart', 'lib/config/app.bak.dart');
}
```

| Method | Description |
|--------|-------------|
| `fileExists(String path)` | Returns `true` if the file exists |
| `directoryExists(String path)` | Returns `true` if the directory exists |
| `readFile(String path)` | Read file as string (async) |
| `readFileSync(String path)` | Read file as string (sync) |
| `writeFile(String path, String content)` | Write content to file (async) |
| `writeFileSync(String path, String content)` | Write content to file (sync) |
| `appendFile(String path, String content)` | Append content to file |
| `ensureDirectory(String path)` | Create directory if it doesn't exist |
| `deleteFile(String path)` | Delete a file |
| `copyFile(String source, String destination)` | Copy a file |

<div id="json-yaml-helpers"></div>

## JSON and YAML Helpers

Read and write JSON and YAML files with built-in helpers.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Read a JSON file as a Map
  Map<String, dynamic> config = await readJson('config.json');

  // Read a JSON file as a List
  List<dynamic> items = await readJsonArray('lib/app/commands/commands.json');

  // Write data to a JSON file (pretty printed by default)
  await writeJson('output.json', {'name': 'MyApp', 'version': '1.0.0'});

  // Write compact JSON
  await writeJson('output.json', data, pretty: false);

  // Append an item to a JSON array file
  // If the file contains [{"name": "a"}], this adds to that array
  await appendToJsonArray(
    'lib/app/commands/commands.json',
    {'name': 'my_command', 'category': 'app', 'script': 'my_command.dart'},
    uniqueKey: 'name',  // prevents duplicates by this key
  );

  // Read a YAML file as a Map
  Map<String, dynamic> pubspec = await readYaml('pubspec.yaml');
  info('Project: ${pubspec['name']}');
}
```

| Method | Description |
|--------|-------------|
| `readJson(String path)` | Read JSON file as `Map<String, dynamic>` |
| `readJsonArray(String path)` | Read JSON file as `List<dynamic>` |
| `writeJson(String path, dynamic data, {bool pretty = true})` | Write data as JSON |
| `appendToJsonArray(String path, Map item, {String? uniqueKey})` | Append to a JSON array file |
| `readYaml(String path)` | Read YAML file as `Map<String, dynamic>` |

<div id="case-conversion-helpers"></div>

## Case Conversion Helpers

Convert strings between naming conventions without importing the `recase` package.

```dart
@override
Future<void> handle(CommandResult result) async {
  String input = 'user profile page';

  info(snakeCase(input));    // user_profile_page
  info(camelCase(input));    // userProfilePage
  info(pascalCase(input));   // UserProfilePage
  info(titleCase(input));    // User Profile Page
  info(kebabCase(input));    // user-profile-page
  info(constantCase(input)); // USER_PROFILE_PAGE
}
```

| Method | Output Format | Example |
|--------|--------------|---------|
| `snakeCase(String input)` | `snake_case` | `user_profile` |
| `camelCase(String input)` | `camelCase` | `userProfile` |
| `pascalCase(String input)` | `PascalCase` | `UserProfile` |
| `titleCase(String input)` | `Title Case` | `User Profile` |
| `kebabCase(String input)` | `kebab-case` | `user-profile` |
| `constantCase(String input)` | `CONSTANT_CASE` | `USER_PROFILE` |

<div id="project-path-helpers"></div>

## Project Path Helpers

Getters for standard {{ config('app.name') }} project directories. These return paths relative to the project root.

```dart
@override
Future<void> handle(CommandResult result) async {
  info(modelsPath);       // lib/app/models
  info(controllersPath);  // lib/app/controllers
  info(widgetsPath);      // lib/resources/widgets
  info(pagesPath);        // lib/resources/pages
  info(commandsPath);     // lib/app/commands
  info(configPath);       // lib/config
  info(providersPath);    // lib/app/providers
  info(eventsPath);       // lib/app/events
  info(networkingPath);   // lib/app/networking
  info(themesPath);       // lib/resources/themes

  // Build a custom path relative to the project root
  String customPath = projectPath('lib/app/services/auth_service.dart');
}
```

| Property | Path |
|----------|------|
| `modelsPath` | `lib/app/models` |
| `controllersPath` | `lib/app/controllers` |
| `widgetsPath` | `lib/resources/widgets` |
| `pagesPath` | `lib/resources/pages` |
| `commandsPath` | `lib/app/commands` |
| `configPath` | `lib/config` |
| `providersPath` | `lib/app/providers` |
| `eventsPath` | `lib/app/events` |
| `networkingPath` | `lib/app/networking` |
| `themesPath` | `lib/resources/themes` |
| `projectPath(String relativePath)` | Resolves a relative path within the project |

<div id="platform-helpers"></div>

## Platform Helpers

Check the platform and access environment variables.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Platform checks
  if (isWindows) {
    info('Running on Windows');
  } else if (isMacOS) {
    info('Running on macOS');
  } else if (isLinux) {
    info('Running on Linux');
  }

  // Current working directory
  info('Working in: $workingDirectory');

  // Read system environment variables
  String home = env('HOME', '/default/path');
}
```

| Property / Method | Description |
|-------------------|-------------|
| `isWindows` | `true` if running on Windows |
| `isMacOS` | `true` if running on macOS |
| `isLinux` | `true` if running on Linux |
| `workingDirectory` | Current working directory path |
| `env(String key, [String defaultValue = ''])` | Read system environment variable |

<div id="dart-flutter-commands"></div>

## Dart and Flutter Commands

Run common Dart and Flutter CLI commands as helper methods. Each returns the process exit code.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Format a Dart file or directory
  await dartFormat('lib/app/models/user.dart');

  // Run dart analyze
  int analyzeResult = await dartAnalyze('lib/');

  // Run flutter pub get
  await flutterPubGet();

  // Run flutter clean
  await flutterClean();

  // Build for a target with additional args
  await flutterBuild('apk', args: ['--release', '--split-per-abi']);
  await flutterBuild('web', args: ['--release']);

  // Run flutter test
  await flutterTest();
  await flutterTest('test/unit/');  // specific directory
}
```

| Method | Description |
|--------|-------------|
| `dartFormat(String path)` | Run `dart format` on a file or directory |
| `dartAnalyze([String? path])` | Run `dart analyze` |
| `flutterPubGet()` | Run `flutter pub get` |
| `flutterClean()` | Run `flutter clean` |
| `flutterBuild(String target, {List<String> args})` | Run `flutter build <target>` |
| `flutterTest([String? path])` | Run `flutter test` |

<div id="dart-file-manipulation"></div>

## Dart File Manipulation

Helpers for programmatically editing Dart files, useful when building scaffolding tools.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Add an import statement to a Dart file (avoids duplicates)
  await addImport(
    'lib/bootstrap/providers.dart',
    "import '/app/providers/firebase_provider.dart';",
  );

  // Insert code before the last closing brace in a file
  // Useful for adding entries to registration maps
  await insertBeforeClosingBrace(
    'lib/bootstrap/providers.dart',
    '  FirebaseProvider(),',
  );

  // Check if a file contains a specific string
  bool hasImport = await fileContains(
    'lib/bootstrap/providers.dart',
    'firebase_provider',
  );

  // Check if a file matches a regex pattern
  bool hasClass = await fileContainsPattern(
    'lib/app/models/user.dart',
    RegExp(r'class User'),
  );
}
```

| Method | Description |
|--------|-------------|
| `addImport(String filePath, String importStatement)` | Add import to Dart file (skips if already present) |
| `insertBeforeClosingBrace(String filePath, String code)` | Insert code before last `}` in file |
| `fileContains(String filePath, String identifier)` | Check if file contains a string |
| `fileContainsPattern(String filePath, Pattern pattern)` | Check if file matches a pattern |

<div id="directory-helpers"></div>

## Directory Helpers

Helpers for working with directories and finding files.

```dart
@override
Future<void> handle(CommandResult result) async {
  // List directory contents
  var entities = listDirectory('lib/app/models');
  for (var entity in entities) {
    info(entity.path);
  }

  // List recursively
  var allEntities = listDirectory('lib/', recursive: true);

  // Find files matching criteria
  List<File> dartFiles = findFiles(
    'lib/app/models',
    extension: '.dart',
    recursive: true,
  );

  // Find files by name pattern
  List<File> testFiles = findFiles(
    'test/',
    namePattern: RegExp(r'_test\.dart$'),
  );

  // Delete a directory recursively
  await deleteDirectory('build/');

  // Copy a directory (recursive)
  await copyDirectory('lib/templates', 'lib/generated');
}
```

| Method | Description |
|--------|-------------|
| `listDirectory(String path, {bool recursive = false})` | List directory contents |
| `findFiles(String directory, {String? extension, Pattern? namePattern, bool recursive = true})` | Find files matching criteria |
| `deleteDirectory(String path)` | Delete directory recursively |
| `copyDirectory(String source, String destination)` | Copy directory recursively |

<div id="validation-helpers"></div>

## Validation Helpers

Helpers for validating and cleaning user input for code generation.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Validate a Dart identifier
  if (!isValidDartIdentifier('MyClass')) {
    error('Invalid Dart identifier');
  }

  // Require a non-empty first argument
  String name = requireArgument(result, message: 'Please provide a name');

  // Clean a class name (PascalCase, remove suffixes)
  String className = cleanClassName('user_model', removeSuffixes: ['_model']);
  // Returns: 'User'

  // Clean a file name (snake_case with extension)
  String fileName = cleanFileName('UserModel', extension: '.dart');
  // Returns: 'user_model.dart'
}
```

| Method | Description |
|--------|-------------|
| `isValidDartIdentifier(String name)` | Validate a Dart identifier name |
| `requireArgument(CommandResult result, {String? message})` | Require non-empty first argument or abort |
| `cleanClassName(String name, {List<String> removeSuffixes})` | Clean and PascalCase a class name |
| `cleanFileName(String name, {String extension = '.dart'})` | Clean and snake_case a file name |

<div id="file-scaffolding"></div>

## File Scaffolding

Create one or many files with content using the scaffolding system.

### Single File

```dart
@override
Future<void> handle(CommandResult result) async {
  await scaffold(
    path: 'lib/app/services/auth_service.dart',
    content: '''
class AuthService {
  Future<bool> login(String email, String password) async {
    // TODO: implement login
    return false;
  }
}
''',
    force: false,  // don't overwrite if exists
    successMessage: 'AuthService created',
  );
}
```

### Multiple Files

```dart
@override
Future<void> handle(CommandResult result) async {
  await scaffoldMany([
    ScaffoldFile(
      path: 'lib/app/models/product.dart',
      content: 'class Product {}',
      successMessage: 'Product model created',
    ),
    ScaffoldFile(
      path: 'lib/app/networking/product_api_service.dart',
      content: 'class ProductApiService {}',
      successMessage: 'Product API service created',
    ),
  ], force: false);
}
```

The `ScaffoldFile` class accepts:

| Property | Type | Description |
|----------|------|-------------|
| `path` | `String` | File path to create |
| `content` | `String` | File content |
| `successMessage` | `String?` | Message shown on success |

<div id="task-runner"></div>

## Task Runner

Run a series of named tasks with automatic status output.

### Basic Task Runner

```dart
@override
Future<void> handle(CommandResult result) async {
  await runTasks([
    CommandTask(
      'Clean project',
      () => runProcess('flutter clean', silent: true),
    ),
    CommandTask(
      'Fetch dependencies',
      () => runProcess('flutter pub get', silent: true),
    ),
    CommandTask(
      'Run tests',
      () => runProcess('flutter test', silent: true),
      stopOnError: true,  // stop pipeline if this fails (default)
    ),
  ]);
}
```

### Task Runner with Spinner

```dart
@override
Future<void> handle(CommandResult result) async {
  await runTasksWithSpinner([
    CommandTask(
      name: 'Preparing release',
      action: () async {
        await flutterClean();
        await flutterPubGet();
      },
    ),
    CommandTask(
      name: 'Building APK',
      action: () => flutterBuild('apk', args: ['--release']),
    ),
  ]);
}
```

The `CommandTask` class accepts:

| Property | Type | Default | Description |
|----------|------|---------|-------------|
| `name` | `String` | required | Task name shown in output |
| `action` | `Future<void> Function()` | required | Async function to execute |
| `stopOnError` | `bool` | `true` | Whether to stop remaining tasks if this one fails |

<div id="table-output"></div>

## Table Output

Display formatted ASCII tables in the console.

```dart
@override
Future<void> handle(CommandResult result) async {
  table(
    ['Name', 'Version', 'Status'],
    [
      ['nylo_framework', '7.0.0', 'installed'],
      ['nylo_support', '7.0.0', 'installed'],
      ['dio', '5.4.0', 'installed'],
    ],
  );
}
```

Output:

```
┌─────────────────┬─────────┬───────────┐
│ Name            │ Version │ Status    │
├─────────────────┼─────────┼───────────┤
│ nylo_framework  │ 7.0.0   │ installed │
│ nylo_support    │ 7.0.0   │ installed │
│ dio             │ 5.4.0   │ installed │
└─────────────────┴─────────┴───────────┘
```

<div id="progress-bar"></div>

## Progress Bar

Display a progress bar for operations with known item counts.

### Manual Progress Bar

```dart
@override
Future<void> handle(CommandResult result) async {
  // Create a progress bar for 100 items
  final progress = progressBar(100, message: 'Processing files');
  progress.start();

  for (int i = 0; i < 100; i++) {
    await Future.delayed(Duration(milliseconds: 50));
    progress.tick();  // increment by 1
  }

  progress.complete('All files processed');
}
```

### Process Items with Progress

```dart
@override
Future<void> handle(CommandResult result) async {
  final files = findFiles('lib/', extension: '.dart');

  // Process items with automatic progress tracking
  final results = await withProgress<File, String>(
    items: files,
    process: (file, index) async {
      // process each file
      return file.path;
    },
    message: 'Analyzing Dart files',
    completionMessage: 'Analysis complete',
  );

  info('Processed ${results.length} files');
}
```

### Synchronous Progress

```dart
@override
Future<void> handle(CommandResult result) async {
  final items = ['a', 'b', 'c', 'd', 'e'];

  final results = withProgressSync<String, String>(
    items: items,
    process: (item, index) {
      // synchronous processing
      return item.toUpperCase();
    },
    message: 'Converting items',
  );

  info('Results: $results');
}
```

The `ConsoleProgressBar` class provides:

| Method | Description |
|--------|-------------|
| `start()` | Start the progress bar |
| `tick([int amount = 1])` | Increment progress |
| `update(int value)` | Set progress to a specific value |
| `updateMessage(String newMessage)` | Change the displayed message |
| `complete([String? completionMessage])` | Complete with optional message |
| `stop()` | Stop without completing |
| `current` | Current progress value (getter) |
| `percentage` | Progress as a percentage (getter) |