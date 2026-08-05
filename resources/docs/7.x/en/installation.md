# Installation

---

<a name="section-1"></a>
- [Install](#install "Install")
- [Running the Project](#running-the-project "Running the project")
- [Metro CLI](#metro-cli "Metro CLI")


<div id="install"></div>

Three commands take you from an empty folder to a running Flutter app with routing, networking, themes and code generation already wired up.

<x-doc-strip label="Before you start" items="Flutter SDK installed, Dart 3" linkText="Full requirements" linkHref="/docs/{{ $version }}/requirements" />

## Install

Run these in order. Each one is safe to re-run.

<x-doc-steps>
<x-doc-step number="1" title="Install the Nylo CLI globally">
Puts the `nylo` command on your system.

``` bash
dart pub global activate nylo_installer
```
</x-doc-step>

<x-doc-step number="2" title="Create a new project">
Clones the {{ config('app.name') }} template, renames it to your app, and installs dependencies.

``` bash
nylo new my_app
```
</x-doc-step>

<x-doc-step number="3" title="Set up the Metro alias">
Lets you type `metro` instead of the full `dart run` syntax.

``` bash
cd my_app
nylo init
```
</x-doc-step>
</x-doc-steps>

<x-doc-panel title="What you get" items="Pre-configured routing and navigation
API service boilerplate
Theme and localization setup
Metro CLI for code generation" />


<div id="running-the-project"></div>

## Running the Project

{{ config('app.name') }} projects run like any standard Flutter app.

<x-doc-tabs tabs="Terminal, Android Studio, VS Code">
<x-doc-tab label="Terminal">

``` bash
flutter run
```

On a successful build the app opens on {{ config('app.name') }}'s default landing screen.
</x-doc-tab>

<x-doc-tab label="Android Studio">
Open the project folder, pick a device from the target selector, then press **Run**.

<a href="https://docs.flutter.dev/tools/android-studio#running-and-debugging" target="_BLANK">Flutter docs: running and debugging ↗</a>
</x-doc-tab>

<x-doc-tab label="VS Code">
Open the project folder, then run **Debug: Start Without Debugging** from the command palette.

<a href="https://docs.flutter.dev/tools/vs-code#run-app-without-breakpoints" target="_BLANK">Flutter docs: run app without breakpoints ↗</a>
</x-doc-tab>
</x-doc-tabs>


<div id="metro-cli"></div>

## Metro CLI

Metro generates project files for you. Run it bare to see the menu, or call a command directly.

``` plaintext
$ metro

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
  make:route_guard
  make:config
  make:interceptor
  make:command
  make:env
```

### Command reference

Every command takes a name, e.g. `metro make:page settings_page`

<x-doc-commands title="Widget commands" rows="
metro make:page | Create a new page
metro make:stateful_widget | Create a stateful widget
metro make:stateless_widget | Create a stateless widget
metro make:state_managed_widget | Create a state-managed widget
metro make:navigation_hub | Create a navigation hub (bottom nav)
metro make:journey_widget | Create a journey widget for a navigation hub
metro make:bottom_sheet_modal | Create a bottom sheet modal
metro make:button | Create a custom button widget
metro make:form | Create a form with validation
" />

<x-doc-commands title="Helper commands" rows="
metro make:model | Create a model class
metro make:provider | Create a provider
metro make:api_service | Create an API service
metro make:controller | Create a controller
metro make:event | Create an event
metro make:route_guard | Create a route guard
metro make:config | Create a config file
metro make:interceptor | Create a network interceptor
metro make:command | Create a custom Metro command
metro make:env | Generate environment config from .env
" />

### Example usage

``` bash
# Create a new page
metro make:page settings_page

# Create a model
metro make:model User

# Create an API service
metro make:api_service user_api_service
```
