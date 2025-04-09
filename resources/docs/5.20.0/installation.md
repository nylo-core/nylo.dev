# Installation

---

<a name="section-1"></a>
- [Install](#install "Install")
- [Running the project](#running-the-project "Running the project")  
- [Metro CLI](#metro-cli "Metro CLI")
  - [Installing Metro Alias (Mac)](#installing-metro-alias "Installing Metro Alias (Mac)")

<div id="install"></div>
<br>

## Install

You can either download {{ config('app.name') }} <a href="{{ route('landing.download') }}">here</a> or clone the git repository using the below command.

```bash
git clone https://github.com/nylo-core/nylo.git nylo_app
```

> <b>Note:</b> run `flutter pub get` when opening the project for the first time to fetch all dependencies.

<div id="running-the-project"></div>
<br>

## Running the project

{{ config('app.name') }} projects run in the exact 'normal' way you'd build a Flutter app. Depending on which IDE you have chosen, this part will be slightly different.

Check this guide here for <a href="https://docs.flutter.dev/development/tools/android-studio#running-and-debugging" target="_BLANK">Android Studio</a> or <a  target="_BLANK" href="https://docs.flutter.dev/development/tools/vs-code#run-app-without-breakpoints">Visual Studio Code</a>.

Once you have done the above steps, try running the project.
If the build is successful, the app will display {{ config('app.name') }}'s **default** landing screen.


<div id="metro-cli"></div>
<br>

## Metro CLI tool

{{ config('app.name') }} provides a CLI tool called <b>Metro</b>. 
It's been built, so you can run commands in the terminal to create things. With Metro, you can create the following in your project:

- Models
- Controllers
- Pages
- Stateful widgets and stateless widgets
- Events
- Providers
- API Services
- Themes
- Route Guards
- Forms
- Configs
- Interceptors

E.g. Running `dart run nylo_framework:main make:model Property` will create a new '**Property**' model in your project.

To access the menu, you can run the below in the terminal.

`dart run nylo_framework:main`

<div id="installing-metro-alias"></div>
<br>

## Installing Metro alias (Mac)

Typing `dart run nylo_framework:main` each time you want to run a command is long.
So, to make things easier, create an alias.

If you're new to aliases, they allow you to create alternative names for your commands.

E.g. `dart run nylo_framework:main` can become `metro`.

### Adding the alias

In the terminal run the following:

``` bash
sudo echo "alias metro='dart run nylo_framework:main'" >>~/.bash_profile && source ~/.bash_profile
```

> This will add the `metro` alias to your bash_profile and reload it.

--- 

Try using `metro` in your terminal. You should see the below output.

``` bash
Metro - Nylo's Companion to Build Flutter apps by Anthony Gordon

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

Now you can type `metro` from your terminal to run commands in your {{ config('app.name') }} project.

E.g. `metro make:controller profile_controller`

### Can't find your bash\_profile?

If you are unsure where to add the above, check out some guides online for where to find your <b>bash\_profile</b> file.
The above example assumes that your bash_profile is in your `~/` location.
