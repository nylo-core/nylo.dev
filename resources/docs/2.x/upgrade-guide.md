# Upgrade Guide

---

- <span class="text-grey">Sponsors</span>
- [Become a sponsor](https://nylo.dev/contributions)

<a name="section-1"></a>
- [Changelog](#changelog "Changelog")
- [Upgrading to 2.x from 1.x](#upgrading-to-2.x-from-1.x "Upgrading to 2.x from 1.x")


<a name="changelog"></a>
<br>

## Changelog

- New `BaseStyles` class to make accessing global colours easier
- Migrate the project to use nylo_framework 2.x
- Pubspec.yaml dependency updates

<br>

The changes introduced in 2.x focus on 3 key areas. Structure, maintainability and optimization.

 ### Structure

 Now in your Nylo project (2.x), there is a new directory in "lib/resources/themes/" named `styles`. 

 This new directory contains all the base styles for your project. e.g. for buttons, bottom nav bar and more.

 In the next future releases, we'll add more BaseStyles to make managing your theme styles easier.

### Maintainability

As Nylo matures, we want to maintain the core underlying framework, Flutter.

We know Flutter and Dart frequently releases updates. Our focus here is to ensure that we can safely update logic whilst not introducing too many breaking changes.

One of the steps that have been taken is removing redundant dependencies from the base repository.

### Optimization

A lot of code in the nylo_support library has been optimized for simplicity. 

Metro CLI tool has also had a big refactor to fix some bugs.

<a name="upgrading-to-2.x-from-1.x"></a>
<br>

## Upgrading to 2.x from 1.x

Steps:
- Upgrade `nylo_framework` to **2.0.2** in your pubspec.yaml file.
- Remove `nylo_support` in your pubspec.yaml file.
- Update the Dart sdk version to `2.14.0`

Next, update your router to use `nyRoutes` instead of `nyCreateRoutes`.

``` dart
buildRouter() => nyRoutes((router) {
  ...
  router.route("/settings-page", (context) => SettingsPage());

  // add more routes
  // router.route('/home', (context) => HomePage());

});
```

Remove any use of nylo_support in your project.

Inside main.dart, create your instance of Nylo like the below example
``` dart
...
void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  Nylo nylo = await Nylo.init(router: buildRouter());

...
```

### Wrapping up.

You may find it easier to download the latest version of Nylo (2.x) and manually add your routes and widgets into the project.

In future releases of Nylo, we plan to make migrating across an easier process. We don't anticipate launching major releases often and I appreciate it might be an inconvenience to those on 1.x but it's worth it.

Any feedback, please email support@nylo.dev