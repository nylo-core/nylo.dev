# Installation

---

<a name="section-1"></a>
- [Install](#install "Install")
  - [Adding your .env file](#adding-your-env-file "Adding your env file")
  - [Running the project](#running-the-project "Running the project")  
- [Metro CLI](#metro-cli "Metro CLI")
  - [Set up an alias for Metro CLI (Mac)](#set-up-metro-alias-for-mac "Set up an alias for Metro CLI (Mac)")

<div id="install"></div>

## Install

You can either download {{ config('app.name') }} <a href="/download">here</a> or clone the git repository using the below command.

```bash
git clone https://github.com/nylo-core/nylo.git nylo_app
```

<b>Note:</b> run `flutter pub get` when opening the project for the first time to fetch all dependencies.

<div id="adding-your-env-file"></div>

## Adding your .env file

You will also need to add a `.env` file to your project at the root level. If you've downloaded {{ config('app.name') }} through our site or on GitHub, this file will already be there.

> The project must have a `.env` file to build successfully.

<div id="running-the-project"></div>

## Running the project

{{ config('app.name') }} projects run in the exact 'normal' way you'd build a Flutter app. Depending on which IDE you have chosen, this part will be slightly different.

Check this guide here for <a href="https://docs.flutter.dev/development/tools/android-studio#running-and-debugging" target="_BLANK">Android Studio</a> or <a  target="_BLANK" href="https://docs.flutter.dev/development/tools/vs-code#run-app-without-breakpoints">Visual Studio Code</a>.

Once you have done the above steps, try running the project.
If the build is successful, the app will display {{ config('app.name') }}'s default landing screen.


<div id="metro-cli"></div>

## Metro CLI tool

{{ config('app.name') }} provides a CLI tool called <b>Metro</b>. 
It's been built so you can run commands in the terminal to create things. Using Metro, you can create the following in your projects:

- Models
- Controllers
- Pages
- Stateful widgets and stateless widgets
- Events
- Providers
- API Services
- Themes


E.g. Running `flutter pub run nylo_framework:main make:model Property` will create a new 'Property' model in your project.

To access the menu, you can run the below in the terminal.

  - `flutter pub run nylo_framework:main`

<div id="set-up-metro-alias-for-mac"></div>

## Creating an alias for Metro (Mac guide)

Typing `flutter pub run nylo_framework:main` each time you want to run a command is long.
So, to make things easier, create an alias.

If you're new to aliases, they allow you to create alternative names for your commands.

E.g. `flutter pub run nylo_framework:main` can become `metro`.

---

1. Open your bash\_profile
``` bash
sudo open ~/.bash_profile
```

2. Add this alias to your bash\_profile
``` bash
...
alias metro='flutter pub run nylo_framework:main'
```

3. Then run the following
``` bash
source ~/.bash_profile
```

Now you can type `metro` from your terminal to run commands in your {{ config('app.name') }} project.

### Can't find your bash\_profile?

If you are unsure where to add the above, check out some guides online for where to find your <b>bash\_profile</b> file.
The above example assumes that your bash_profile is in your `~/` location.
