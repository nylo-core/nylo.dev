# Installation

---

<a name="section-1"></a>
- [Install](#install "Install")
- [Running the Project](#running-the-project "Running the project")
- [Metro CLI](#metro-cli "Metro CLI")


<div id="install"></div>

## Install

### 1. Install nylo_installer globally

``` bash
dart pub global activate nylo_installer
```

This installs the {{ config('app.name') }} CLI tool globally on your system.

### 2. Create a new project

``` bash
nylo new my_app
```

This command clones the {{ config('app.name') }} template, configures the project with your app name, and installs all dependencies automatically.

### 3. Set up Metro CLI alias

``` bash
cd my_app
nylo init
```

This configures the `metro` command for your project, allowing you to use Metro CLI commands without the full `dart run` syntax.

After installation, you'll have a complete Flutter project structure with:
- Pre-configured routing and navigation
- API service boilerplate
- Theme and localization setup
- Metro CLI for code generation


<div id="running-the-project"></div>

## Running the Project

{{ config('app.name') }} projects run like any standard Flutter app.

### Using the Terminal

``` bash
flutter run
```

### Using an IDE

- **Android Studio**: <a href="https://docs.flutter.dev/tools/android-studio#running-and-debugging" target="_BLANK">Running and debugging</a>
- **VS Code**: <a href="https://docs.flutter.dev/tools/vs-code#run-app-without-breakpoints" target="_BLANK">Run app without breakpoints</a>

If the build is successful, the app will display {{ config('app.name') }}'s default landing screen.


<div id="metro-cli"></div>

## Metro CLI

{{ config('app.name') }} includes a CLI tool called **Metro** for generating project files.

### Running Metro

``` bash
metro
```

This displays the Metro menu:

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
```

### Metro Commands Reference

| Command | Description |
|---------|-------------|
| `make:page` | Create a new page |
| `make:stateful_widget` | Create a stateful widget |
| `make:stateless_widget` | Create a stateless widget |
| `make:state_managed_widget` | Create a state-managed widget |
| `make:navigation_hub` | Create a navigation hub (bottom nav) |
| `make:journey_widget` | Create a journey widget for navigation hub |
| `make:bottom_sheet_modal` | Create a bottom sheet modal |
| `make:button` | Create a custom button widget |
| `make:form` | Create a form with validation |
| `make:model` | Create a model class |
| `make:provider` | Create a provider |
| `make:api_service` | Create an API service |
| `make:controller` | Create a controller |
| `make:event` | Create an event |
| `make:theme` | Create a theme |
| `make:route_guard` | Create a route guard |
| `make:config` | Create a config file |
| `make:interceptor` | Create a network interceptor |
| `make:command` | Create a custom Metro command |
| `make:env` | Generate environment config from .env |

### Example Usage

``` bash
# Create a new page
metro make:page settings_page

# Create a model
metro make:model User

# Create an API service
metro make:api_service user_api_service
```
