# Slates

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Creating a Slate package](#creating-a-slate-package "Creating a Slate package")


<div id="introduction"></div>
## Introduction

Slates are packages you can download from [pub.dev](https://:pub.dev) to quickly scaffold your app.

Once you install your slate package, you can run from the **terminal** using the `publish:all` command.
Each package you install will use a different command to publish all the files.

Here's an example below, i.e. installing the [ny_auth_slate](https://pub.dev/packages/ny_auth_slate).

``` dart
dart run nylo_framework:main publish:slate example_slate_package
// or with Metro 
metro publish:slate example_slate_package
```

Download a fresh copy of {{ config('app.name') }} and try it in your project [ny_auth_slate](https://pub.dev/packages/ny_auth_slate)

<div id="creating-a-slate-package"></div>

## Creating a Slate package

You can build a new Slate package by first using our public template <a href="https://github.com/nylo-core/package-skeleton-slate" target="_BLANK">here</a> to get started.

Navigate to the **my_slate_template.dart** file and modify the run() method.

``` dart
List<NyTemplate> run() => [
      /// Example
      NyTemplate(
        name: "login_page", // name of the file
        saveTo: pagesFolder, // folder to save to
        pluginsRequired: [], // dependencies that are required for the stub
        stub: stubLoginPage(), // stub you want to generate in you /stubs directory
      ),

    /// add more templates...
];
```

Once you've built your Slate package, publish it to pub.dev as a package for the community to download.
