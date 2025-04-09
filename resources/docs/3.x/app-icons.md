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

You can build all your app icons using `flutter pub run flutter_launcher_icons:main` from the command line. 
This will take your current app icon in <b>/public/assets/app_icon/</b> and auto-generate all your iOS and Android icons.

> Your app icon should be a `.png` with the size dimensions of 1024x1024px

If you have custom icons for different operating systems you can also just add them manually.

Nylo uses the <a href="https://pub.dev/packages/flutter_launcher_icons" target="_BLANK">flutter_launcher_icons</a> library to build icons, to understand the library more you can check out their documentation too.

<div id="generating-app-icons"></div>
<br>

## Generating app icons


You can run the below command from the terminal to auto-generate your app icons.
``` dart
flutter pub run flutter_launcher_icons:main
```

This command will use the app icon located in your `/public/assets/app_icon`  directory to make the IOS and Android app icons to the correct dimensions.


<div id="adding-your-app-icon"></div>
<br>

## Adding your app icon

You can place your 'app icon' inside the `/public/assets/app_icon` directory. 

> Make your icon filesize is **1024x1024** for the best results. 

Once you’ve added your app icon you’ll then need to update the **image\_path** if you’re filename is different to the default Nylo app icon name. 

Open your pubspec.yaml file and look for **image\_path** section, this is where you can update the image path for the file. Make sure that the “image\_path” matches the location for your new app icon.


<div id="app-icon-filenames"></div>
<br>

## App icon filenames

Your filenames shouldn’t include special characters. It’s best to keep it simple like “app\_icon.jpg” or “icon.png”.


<div id="app-icon-filetype"></div>
<br>

## App icon filetypes

App icon needs to be a .png type.

<b>App icon attributes.</b>

| Attribute  | Value  |
|---|---|
|  Format |  png |
|  Size |  1024x1024px |
|  Layers |  Flattened with no transparency |

If you are interested in learning more, you can view the official guidelines from both Google and Apple.

- Apple’s human interface guideline is <a href="https://developer.apple.com/design/human-interface-guidelines/ios/icons-and-images/app-icon/" target="_BLANK">here</a>
- Google’s icon design specifications are <a href="https://developer.android.com/google-play/resources/icon-design-specifications" target="_BLANK">here</a>


<div id="configuration"></div>
<br>

## Configuration

You can also modify the settings when generating your app icons.
Inside the `pubspec.yaml` file, look for the `flutter_icons` section and here you can make changes to the configuration.

Check out the official <a href="https://pub.dev/packages/flutter_launcher_icons" target="_BLANK">flutter_launcher_icons</a> library to see what's possible.