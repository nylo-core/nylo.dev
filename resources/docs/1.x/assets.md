# Assets

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction to assets")
- Files
  - [Displaying images](#displaying-images "Displaying images")
  - [Returning files](#returning-files "Returning files")
- Managing assets
  - [Adding new files](#adding-new-files "Adding new files")
  - [Pubspec.yaml](#pubspec-yaml-assets "Pubspec yaml assets")


<div id="introduction"></div>
<br>
## Introduction

In this section, we'll look into how you can manage assets throughout your widgets.
Nylo provides a few helper methods which make it easy to fetch images, files and more from your `public/assets` directory.

<div id="displaying-images"></div>
<br>

## Displaying images
You can return images by calling the below helper method.

``` dart
getImageAsset('nylo_logo.png');
```

In your widget, it would look something like the below.

``` dart
Image.asset(
  getImageAsset("nylo_logo.png"),
  height: 100,
  width: 100,
)
```

<div id="returning-files"></div>
<br>

## Returning files

You can call the below helper method to get the full file path for an asset.

``` dart
getPublicAsset('/images/nylo_logo.png');
```

This could also be any file within the `public/assets` directory too

``` dart
getPublicAsset('/video/welcome.mp4');
```

<div id="adding-new-files"></div>
<br>

## Adding new files

To add new files open the `public/assets` directory and include your files in a new folder or an existing one.

<div id="pubspec-yaml-assets"></div>
<br>

## Pubspec yaml assets

> If you add a new file to the public/assets/ directory, you also need to include it within your pubspec.yaml file under "assets".

You can include your new files like the below example.

Example new file: `public/assets/video/welcome.mp4`

<br>

#### pubspec.yaml file
``` yaml
...
assets:
  - public/assets/video/welcome.mp4
  - public/assets/fonts/
  - public/assets/images/nylo_logo.png
  - lang/en.json
  - .env
```