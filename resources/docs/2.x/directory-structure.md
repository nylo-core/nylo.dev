# Directory Structure

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction to Directory structures in Nylo")
- [App Directories](#app-directories "App directories")
- [Public assets](#public-assets "Public assets")
  - [Retrieving image assets](#retrieving-image-assets "Retrieving image assets")
  - [Retrieving public assets](#retrieving-public-assets "Retrieving public assets")

<div id="introduction"></div>
<br>
## Introduction

Keeping projects organized can be difficult when there are a lot of moving parts.

Nylo tries to solve this with a simple project structure. 

<div id="app-directories"></div>
<br>

## App Directories

All the app directories are listed inside the `lib` directory where your main.dart file runs.

- `app` this folder includes any files relating to models, controllers and networking.
  - `controllers` Include your controllers here for your Widget pages.
  - `models` Create your model classes here.
  - `networking` Add any networking classes here for managing APIs or fetching data from the internet.

- `config` this folder includes configuration files like for the theme or anything else you need.

- `resources` this folder includes any files that are key components for your users UI experience like pages, widgets and themes.
  - `pages` Include your Widgets here that you will use as Page's in your project. E.g. home\_page.dart.
  - `themes` By default we include two themes here but you can add more if your project needs to support more.
  - `widgets` Any widgets you need to create can be inserted here like a date\_picker\_widget.dart file.

- `routes` this folder includes any files relating to models, controllers and networking.
  - router.dart contains the router for your application.

---

<div id="public-assets"></div>
<br>

## Public assets

All public assets can be found in `public/assets`. This directory is commonly used for images, fonts and more files that you may want to include in your project.

> It's important to add any new files into the `pubspec.yaml` file too.

- `app_icon` This is used for generating app\_icons for the project.
- `images` Include any images here in this directory.
- `fonts` Add any custom fonts here.

<div id="retrieving-image-assets"></div>
<br>

## Retrieving an image asset

To get a image asset you can use `getImageAsset(String key)`

``` dart
Image.asset(
  getImageAsset("nylo_logo.png"),
  height: 50,
  width: 50,
),
```

In this example, our `public/assets/images/` directory has one file `nylo_logo.png`.

- public/assets/images/nylo_logo.png

<div id="retrieving-public-assets"></div>
<br>

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