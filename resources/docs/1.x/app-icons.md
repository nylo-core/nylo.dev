# App Icons

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Generating app icons](#generating-app-icons "Generating app icons")
- [Adding your app icon](#adding-your-app-icon)
- [App icon filename](#app-icon-filenames "App icon filenames")
- [App icon filetype](#app-icon-filetype "App icon filetype")
- [Configuration](#configuration "Configuration for app icons")


<div id="introduction"></div>
<br>
## Introduction

You can manage your app icon in Nylo from the `/public/assets/app_icon` directory. We understand that generating all the required dimensions for your app icon can be a laborious task so we have provided a command you can run using our `Metro` Cli tool to auto-generate all your (iOS and Android) icons for you. 

>  Your app icon should be a `.png` with the size dimensions of 1024x1024px

If you have custom icons for different operating systems you can also just add them manually. This guide will be useful for those needing a tool to build their icons for them.

Nylo uses the [flutter\_launcher\_icons](https://pub.dev/packages/flutter_launcher_icons) library to build these icons, to understand the library more you can check out their documentation too.

<div id="generating-app-icons"></div>
<br>

## Generating app icons


You can run the below command from the terminal to auto-generate your app icons.
``` dart
metro appicons:build
```

> You will need the metro alias setup from the [installation](/docs/1.x/installation) steps

This command will use the app icon located in your `/public/assets/app_icon`  directory to make the IOS and Android app icons to the correct dimensions.

---

<div id="adding-your-app-icon"></div>
<br>

## Adding your app icon

You can place your 'app icon' inside the `/public/assets/app_icon` directory. 

> Make your the icon filesize is **1024x1024** for the best results. 

Once you’ve added your app icon you’ll then need to update the **image\_path** if you’re filename is different to the default Nylo app icon name. 

Open your pubspec.yaml file and look for **image\_path** section, this is where you can update the image path for the file. Make sure that the “image\_path” matches the location for your new app icon.

---

<div id="app-icon-filenames"></div>
<br>

## App icon filenames

Your filenames shouldn’t include and special characters, it’s best to keep it simple like “app\_icon.jpg” or “icon.png”.

---

<div id="app-icon-filetype"></div>
<br>

## App icon filetypes

App icon needs to be a `.png` mime type, both Apple and Google have this in their guidelines for icons.
 
App icon attributes

| Attribute  | Value  |
|---|---|
|  Format |  png |
|  Size |  1024x1024px |
|  Layers |  Flattened with no transparency |

If you are interested in learning more, you can view the official guidelines from both Google and Apple.

- Apple’s human interface guideline is [here](https://developer.apple.com/design/human-interface-guidelines/ios/icons-and-images/app-icon/ "Apple’s human interface guideline for App Icons")
- Google’s icon design specifications are [here](https://developer.android.com/google-play/resources/icon-design-specifications "Google’s guidelines for icon design")

---

<div id="configuration"></div>
<br>

## Configuration

You can also modify the settings when generating your app icons.
Inside the `pubspec.yaml` file, look for the `flutter_icons` section and here you can make changes to the configuration.

Check out the official [flutter\_launcher\_icons](https://pub.dev/packages/flutter_launcher_icons) library to see what's possible.