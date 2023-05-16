# Requirements

---

<a name="section-1"></a>
- [Installing Flutter](#installing-flutter "Installing Flutter")
- [Set up an editor](#set-up-an-editor "Set up an editor")

<a name="introduction"></a>
<br>
## Installing Flutter

To use {{ config('app.name') }}, you'll need to have Flutter installed. Check out the <a href="https://flutter.dev/docs/get-started/install" target="_blank">Flutter docs</a> for how to get set up.

**Minimum Flutter version: v3.7.0**

**Minimum Dart version: v2.19.0**

---

You can check your Flutter version by running the below command.

``` bash
flutter --version
```

If your version is not higher than v3.7.0 then you can run the below to get a stable release.

``` bash
flutter channel stable
flutter upgrade
flutter doctor -v
```

<a name="set-up-an-editor"></a>
<br>

## Set up an editor

<a href="https://developer.android.com/studio">Android Studio</a> or <a href="https://code.visualstudio.com">VSCode</a> are great options for a Flutter environment.
- Set up <a href="https://docs.flutter.dev/get-started/editor?tab=androidstudio" target="_BLANK">Android Studio</a>
- Set up <a href="https://docs.flutter.dev/get-started/editor?tab=vscode" target="_BLANK">VS Code</a>

When your editor is ready, you can start building Flutter applications.