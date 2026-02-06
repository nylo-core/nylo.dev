# What is {{ config('app.name') }}?

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- App Development
    - [New to Flutter?](#new-to-flutter "New to Flutter?")
    - [Maintenance and Release Schedule](#maintenance-and-release-schedule "Maintenance and Release Schedule")
- Credits
    - [Framework Dependencies](#framework-dependencies "Framework Dependencies")
    - [Contributors](#contributors "Contributors")


<div id="introduction"></div>

## Introduction

{{ config('app.name') }} is a micro-framework for Flutter designed to help simplify app development. It provides a structured boilerplate with pre-configured essentials so you can focus on building your app's features instead of setting up infrastructure.

Out of the box, {{ config('app.name') }} includes:

- **Routing** - Simple, declarative route management with guards and deep linking
- **Networking** - API services with Dio, interceptors, and response morphing
- **State Management** - Reactive state with NyState and global state updates
- **Localization** - Multi-language support with JSON translation files
- **Themes** - Light/dark mode with theme switching
- **Local Storage** - Secure storage with Backpack and NyStorage
- **Forms** - Form handling with validation and field types
- **Push Notifications** - Local and remote notification support
- **CLI Tool (Metro)** - Generate pages, controllers, models, and more

<div id="new-to-flutter"></div>

## New to Flutter?

If you're new to Flutter, start with the official resources:

- <a href="https://flutter.dev" target="_BLANK">Flutter Documentation</a> - Comprehensive guides and API reference
- <a href="https://www.youtube.com/c/flutterdev" target="_BLANK">Flutter YouTube Channel</a> - Tutorials and updates
- <a href="https://docs.flutter.dev/cookbook" target="_BLANK">Flutter Cookbook</a> - Practical recipes for common tasks

Once you're comfortable with Flutter basics, {{ config('app.name') }} will feel intuitive as it builds on standard Flutter patterns.


<div id="maintenance-and-release-schedule"></div>

## Maintenance and Release Schedule

{{ config('app.name') }} follows <a href="https://semver.org" target="_BLANK">Semantic Versioning</a>:

- **Major releases** (7.x → 8.x) - Once a year for breaking changes
- **Minor releases** (7.0 → 7.1) - New features, backwards compatible
- **Patch releases** (7.0.0 → 7.0.1) - Bug fixes and minor improvements

Bug fixes and security patches are handled promptly via the GitHub repositories.


<div id="framework-dependencies"></div>

## Framework Dependencies

{{ config('app.name') }} v7 is built on these open source packages:

### Core Dependencies

| Package | Purpose |
|---------|---------|
| <a href="https://pub.dev/packages/dio" target="_BLANK">dio</a> | HTTP client for API requests |
| <a href="https://pub.dev/packages/flutter_secure_storage" target="_BLANK">flutter_secure_storage</a> | Secure local storage |
| <a href="https://pub.dev/packages/intl" target="_BLANK">intl</a> | Internationalization and formatting |
| <a href="https://pub.dev/packages/rxdart" target="_BLANK">rxdart</a> | Reactive extensions for streams |
| <a href="https://pub.dev/packages/equatable" target="_BLANK">equatable</a> | Value equality for objects |

### UI & Widgets

| Package | Purpose |
|---------|---------|
| <a href="https://pub.dev/packages/skeletonizer" target="_BLANK">skeletonizer</a> | Skeleton loading effects |
| <a href="https://pub.dev/packages/flutter_styled_toast" target="_BLANK">flutter_styled_toast</a> | Toast notifications |
| <a href="https://pub.dev/packages/pull_to_refresh_flutter3" target="_BLANK">pull_to_refresh_flutter3</a> | Pull-to-refresh functionality |
| <a href="https://pub.dev/packages/flutter_staggered_grid_view" target="_BLANK">flutter_staggered_grid_view</a> | Staggered grid layouts |
| <a href="https://pub.dev/packages/date_field" target="_BLANK">date_field</a> | Date picker fields |

### Notifications & Connectivity

| Package | Purpose |
|---------|---------|
| <a href="https://pub.dev/packages/flutter_local_notifications" target="_BLANK">flutter_local_notifications</a> | Local push notifications |
| <a href="https://pub.dev/packages/connectivity_plus" target="_BLANK">connectivity_plus</a> | Network connectivity status |
| <a href="https://pub.dev/packages/app_badge_plus" target="_BLANK">app_badge_plus</a> | App icon badges |

### Utilities

| Package | Purpose |
|---------|---------|
| <a href="https://pub.dev/packages/url_launcher" target="_BLANK">url_launcher</a> | Open URLs and apps |
| <a href="https://pub.dev/packages/recase" target="_BLANK">recase</a> | String case conversion |
| <a href="https://pub.dev/packages/uuid" target="_BLANK">uuid</a> | UUID generation |
| <a href="https://pub.dev/packages/path_provider" target="_BLANK">path_provider</a> | File system paths |
| <a href="https://pub.dev/packages/mask_text_input_formatter" target="_BLANK">mask_text_input_formatter</a> | Input masking |


<div id="contributors"></div>

## Contributors

Thank you to everyone who has contributed to {{ config('app.name') }}! If you've contributed, reach out via <a href="mailto:support@nylo.dev">support@nylo.dev</a> to be added here.

- <a href="https://github.com/agordn52" target="_BLANK">Anthony Gordon</a> (Creator)
- <a href="https://github.com/Abdulrasheed1729" target="_BLANK">Abdulrasheed1729</a>
- <a href="https://github.com/Rashid-Khabeer" target="_BLANK">Rashid-Khabeer</a>
- <a href="https://github.com/lpdevit" target="_BLANK">lpdevit</a>
- <a href="https://github.com/youssefKadaouiAbbassi" target="_BLANK">youssefKadaouiAbbassi</a>
- <a href="https://github.com/jeremyhalin" target="_BLANK">jeremyhalin</a>
- <a href="https://github.com/abdulawalarif" target="_BLANK">abdulawalarif</a>
- <a href="https://github.com/lepresk" target="_BLANK">lepresk</a>
- <a href="https://github.com/joshua1996" target="_BLANK">joshua1996</a>
- <a href="https://github.com/stensonb" target="_BLANK">stensonb</a>
- <a href="https://github.com/ruwiss" target="_BLANK">ruwiss</a>
- <a href="https://github.com/rytisder" target="_BLANK">rytisder</a>
- <a href="https://github.com/israelins85" target="_BLANK">israelins85</a>
- <a href="https://github.com/voytech-net" target="_BLANK">voytech-net</a>
- <a href="https://github.com/sadobass" target="_BLANK">sadobass</a>
