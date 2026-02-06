# Directory Structure

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction to Directory structures in {{ config('app.name') }}")
- [App Directories](#app-directories "App directories")
- [Public assets](#public-assets "Public assets")
  - [Retrieving image assets](#retrieving-image-assets "Retrieving image assets")
  - [Retrieving public assets](#retrieving-public-assets "Retrieving public assets")

<div id="introduction"></div>

## Introduction

Every {{ config('app.name') }} project comes with a simple boilerplate for managing your files. It has this structure to streamline the development of your projects.

The directory structure was inspired by <a href="https://github.com/laravel/laravel" target="_BLANK">Laravel</a>.

<div id="app-directories"></div>

## App Directories

The below app directories are listed inside the <b>lib</b> folder.

- `app` This folder includes any files relating to models, controllers and networking.
  - `controllers` Include your controllers here for your Widget pages.
  - `models` Create your model classes here.
  - `networking` Add any API services here for managing APIs or fetching data from the internet.
  - `events` Add all your event classes here.
  - `providers` Add any provider classes here that need booting when your app runs.

- `config` This folder contains configuration files such as your font, theme and localization settings.
  - `font` Mange the font for your app with this config file.
  - `theme` Set and configure the themes you want your flutter app to use.
  - `localization` Manage the localization, language and other things relating to locale in this file.
  - `decoders` Register modelDecoders and apiDecoders.
  - `events` Register your events in the Map object.
  - `providers` Register your providers in the Map object.
  - `validation_rules` Register your custom validation rules.

- `resources` This folder includes any files that are key components for your user's UI experience like pages, widgets and themes.
  - `pages` Include your Widgets here that you will use as Page's in your project. E.g. home\_page.dart.
  - `themes` By default, you'll find two themes here for light and dark mode, but you can add more.
  - `widgets` Any widgets you need to create can be inserted here, like a date\_picker.dart file.

- `routes` This folder includes any files relating to routing.
  - `router.dart` The default router for your application, where you can add routes.

<div id="public-assets"></div>

## Public assets

Public assets can be found in the `public/assets`. This directory is used for images, fonts and more files that you may want to include in your project.

> It's important to add any new files into the `pubspec.yaml` file too.

- `app_icon` This is used for generating app\_icons for the project.
- `images` Include any images here in this directory.
- `fonts` Add any custom fonts here.

<div id="retrieving-image-assets"></div>

## Retrieving an image asset

You can use the normal, standard way in Flutter by running the following:
``` dart
Image.asset(
  Image.asset('public/assets/images/my_logo.png'),
  height: 50,
  width: 50,
),
```

Or you can use `getImageAsset(String key)` helper

``` dart
Image.asset(
  getImageAsset("nylo_logo.png"),
  height: 50,
  width: 50,
),
```

In this example, our <b>public/assets/images/</b> directory has one file `nylo_logo.png`.

- public/assets/images/nylo_logo.png

<div id="retrieving-public-assets"></div>

## Retrieving a public asset

You can get any public asset using `getPublicAsset(String key)`

``` dart
_controller = VideoPlayerController.asset(
    getPublicAsset('videos/intro.mp4'))
  ..initialize().then((_) {
    
    setState(() {});
  });
```

In this example, our `public/assets/videos/` directory has one file `intro.mp4`.

- public/assets/images/intro.mp4
