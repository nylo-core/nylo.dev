# Assets

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction to assets")
- Files
  - [Displaying images](#displaying-images "Displaying images")
  - [Returning files](#returning-files "Returning files")
- Managing assets
  - [Adding new files](#adding-new-files "Adding new files")


<div id="introduction"></div>
## Introduction

In this section, we'll look into how you can manage assets throughout your widgets.
{{ config('app.name') }} provides a few helper methods which make it easy to fetch images, files and more from your `public/assets` directory.

<div id="displaying-images"></div>

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

## Adding new files

To add new files open the `public/assets` directory and include your files in a new folder or an existing one.