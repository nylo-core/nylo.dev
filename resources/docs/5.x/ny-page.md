# NyPage

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Creating a New Page](#creating-a-new-page "Creating a new page")
- [Using Controllers](#using-controllers "Using Controllers")
- [How to Use NyPage](#how-to-use-nypage "How to use NyPage")
    - [Initializing Data](#initializing-data "Initializing Data")
    - [Loading Data from an API](#loading-data-from-an-api "Loading Data from an API")
- [Passing Data to Different Pages](#passing-data-to-different-pages "Passing data to different pages")
- [Helper](#helpers "Helpers")
    - [Refresh Page](#refresh-page "Refresh Page") 
    - [Showing Toast Notifications](#showing-toast-notifications "Showing toast notifications")
    - [Change Language](#change-language "Change language")
    - [Validation](#validation "Validation")


<div id="introduction"></div>
<br>

## Introduction

In this section, we will learn about `NyPage`.

Most Flutter projects will likely need to create pages for example, a login page, a dashboard page, a profile page, etc.

Nylo's `NyPage` class helps you build the UI quicker with less code, let's take a deep dive.

---

In the terminal, if you run the below command, it will create a new page for you.

``` bash
dart run nylo_framework:main make:page dashboard
```

The new page will be created in **resources/pages/**

`resources/pages/dashboard_page.dart`

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';

class DashboardPage extends NyPage {

  static String path = '/dashboard';

  @override
  init() async {
    // initialize the page
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text("Dashboard")
      ),
      body: SafeArea(
         child: Container(
             child: Text("Hello World") // Build your UI
         ),
      ),
    );
  }
}
```

Here are some important things to note:

- `path` is the **route** path for the page. You can use this path to navigate to the page for example `routeTo(Dashboard.path)`.

- `init` is called when the page is created. You can use this method to load data, set data in the class or do any other initialization work.

- `build` contains the **Widget** that will be displayed on the UI.

Continue reading to learn more about `NyPage`

<div id="creating-a-new-page"></div>
<br>

## Creating a new page

You can create a new page by running the below command in the terminal.

```bash
dart run nylo_framework:main make:page settings
```

The new page will be created in **resources/pages/**

`resources/pages/settings_page.dart`

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';

class SettingsPage extends NyPage {

  static String path = '/settings';

  @override
  init() async {
    // initialize the page
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text("Settings")
      ),
      body: SafeArea(
         child: Container(
             child: Text("Hello World") // Build your UI
         ),
      ),
    );
  }
}
```

<div id="using-controllers"></div>
<br>

## Using Controllers

Controllers are a great way to separate your business logic from your UI.

The easiest way to create a **page** and **controller** is by running the below command in the terminal.

```bash
dart run nylo_framework:main make:page dashboard -c
# or if you have Metro
metro make:page dashboard -c
```

The new page and controller will be created in **resources/pages/** and **app/controllers/**

This is the code it will generate for you.

---

**Controller** - DashboardController 

<u>app/controllers/dashboard_controller.dart</u>

``` dart
import 'controller.dart';
import 'package:flutter/widgets.dart';

class DashboardController extends Controller {
  
  // add variables/methods/functions here

  construct(BuildContext context) {
    super.construct(context);

  }

  // Example method
  bool isProUser() {
    User user = Backpack.instance.auth();
    if (user.isPro) {
      return true;
    }
    return false;
  }
}
```

**Page** - DashboardPage 

<u>resources/pages/dashboard_page.dart</u>

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';
import '/app/controllers/dashboard_controller.dart';

class DashboardPage extends NyPage<DashboardController> {

  static String path = '/dashboard';

  @override
  init() async {
    // initialize the page
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text("Dashboard")
      ),
      body: SafeArea(
         child: Container(
            child: InkWell(
              onTap: () {
                bool isProUser = controller?.isProUser(); // call a method from the controller
                /// do something
              },
              child: Text("Subscribe") // Build your UI
            )
         ),
      ),
    );
  }
}
```

You can learn more about controllers [here](/docs/{{$version}}/controllers)


<div id="how-to-use-nypage"></div>
<br>

## How to use NyPage

In this section, we'll take a look at:
- [Initializing Data](#initializing-data "Initializing Data")
- [Loading Data from an API](#loading-data-from-an-api "Loading data from an API")

Flutter apps often need to load data from an API, pass data from one page to another or initialize classes. 

When using `NyPage` we'll show how you can handle some of these scenarios.


<div id="initializing-data"></div>
<br>

### Initializing Data

Each page has an `init` method that is called when the page is created.

You can use this method to initialize data, classes, etc.

Try not to set data in the `NyPage` class, instead use a **controller** to set data/variables.

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';
import '/app/controllers/dashboard_controller.dart';
import 'package:firebase_core/firebase_core.dart';

class DashboardPage extends NyPage<DashboardController> {

  static String path = '/dashboard';

  @override
  init() async {
    /* Example with Firebase
    await Firebase.initializeApp(
      options: DefaultFirebaseOptions.currentPlatform,
    ); 
    */
  }

  Future<List<String>> _getFavouriteFood() async {
    await Future.delayed(Duration(seconds: 2));
    return ["Rice", "Pasta", "Burger"];
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
          title: Text("Dashboard")
      ),
      body: SafeArea(
        child: NyListView(
            child: (BuildContext context, dynamic food) {
              return ListTile(
                  title: Text(food)
              );
            },
            data: _getFavouriteFood
        ),
      ),
    );
  }
}
```

<div id="loading-data-from-an-api"></div>
<br>

### Loading Data from an API

When loading data from an API, it's recommended to use an [API Service](/docs/{{$version}}/networking#using-an-api-service).

This is how you can load data using NyPage.

First, create a controller, e.g. `DashboardController`.

``` dart
import 'package:flutter_app/app/networking/api_service.dart';
import 'package:flutter_app/bootstrap/helpers.dart';
import 'controller.dart';
import 'package:flutter/widgets.dart';

class DashboardController extends Controller {

  List<dynamic> jsonPlaceHolderData = [];

  construct(BuildContext context) {
    super.construct(context);

  }

  loadJsonPlaceHolderData() async {
    jsonPlaceHolderData = await api<ApiService>((request) => request.get("https://jsonplaceholder.typicode.com/posts"));
  }
} 
```

Then, use the Controller in your `NyPage`.

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';
import '/app/controllers/dashboard_controller.dart';

class DashboardPage extends NyPage<DashboardController> { // uses DashboardController

  static String path = '/dashboard';

  @override
  init() async {

  }

  @override
  boot() async {
    await Future.delayed(Duration(seconds: 2));
    await controller?.loadJsonPlaceHolderData();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
          title: Text("Dashboard")
      ),
      body: SafeArea(
        child: NyListView(
            child: (BuildContext context, dynamic data) {
              return ListTile(
                  title: Text(data['title'])
              );
            },
            data: () async {
              return controller?.jsonPlaceHolderData; // returns the data from the controller
            }
        ),
      ),
    );
  }

  @override
  Widget loading(BuildContext context) {
    return Scaffold(
      body: loadingWidget, // This Widget will be used when the page is loading
    );
  }
}
```

You'll notice in the above example that we are using the `boot` method to load the data.

The boot method allows you to load data. While the page is loading, it will display the `loading` widget as seen in the example above.

You can override it to customize the look for the loading state.

Learn more about networking [here](/docs/{{$version}}/networking).


<div id="passing-data-to-different-pages"></div>
<br>

## Passing Data to Different Pages

You can pass data to different pages by using the `routeTo` method.

Let's imagine we have two pages, **DashboardPage** and **ProfilePage**.

From the DashboardPage we want to pass data to the ProfilePage.

This is how we can do it.

``` dart
/// Dashboard Page
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';
import '/app/controllers/dashboard_controller.dart';

class DashboardPage extends NyPage<DashboardController> {

  static String path = '/dashboard';

  @override
  init() async {

  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
          title: Text("Dashboard")
      ),
      body: SafeArea(
        child: Column(
          children: [
            InkWell(
              child: Text("Route To Profile"),
              onTap: () {
                routeTo(ProfilePage.path, data: {"name": "Anthony"});
              },
            )
          ],
        )
      ),
    );
  }
}
```

And here's the ProfilePage.

``` dart
/// Profile Page
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';
import '/app/controllers/dashboard_controller.dart';

class ProfilePage extends NyPage {

  static String path = '/profile';

  @override
  init() async {
    dynamic data = controller?.data();
    NyLogger.debug(data); // {"name": "Anthony"}
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
          title: Text("Profile")
      ),
    );
  }
}
```

<div id="helpers"></div>
<br>

## Helpers

The `NyPage` class has some great helpers you can use.

The helpers are:

- [Refresh Page](#refresh-page "Refresh Page")
- [Showing Toast Notifications](#showing-toast-notifications "Showing toast notifications")
- [Change Language](#change-language "Change language")
- [Validation](#validation "Validation")

You can call these helpers in your `NyPage`, let's take a look at some examples.

<div id="refresh-page"></div>
<br>

### Refresh Page

You can refresh the page by calling the `refreshPage` method.

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';
import '/app/controllers/dashboard_controller.dart';

class DashboardPage extends NyPage<DashboardController> {

  static String path = '/dashboard';

  @override
  boot() async {
      await Future.delayed(Duration(seconds: 2));
      await controller?.loadProfileData();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text("Dashboard"),
        actions: [
          IconButton(onPressed: () {
            refreshPage(); // refreshes the page. The `boot` method will be called again
          }, icon: Icon(Icons.refresh))
        ],
      ),
      body: SafeArea(
        child: Column(
          children: [
            // Widgets...
          ],
        )
      ),
    );
  }
}
```

#### Updating the state

You can update the state by using the `setState` parameter on the `refreshPage` method too.

``` dart
refreshPage(setState: () {
  // update the state here
});
```

Here's an example in a widget.

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';
import '/app/controllers/dashboard_controller.dart';

class DashboardPage extends NyPage<DashboardController> {

  static String path = '/dashboard';

  @override
  init() async {
    
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
          title: Text("Dashboard"),
        actions: [
          IconButton(onPressed: () {
            refreshPage(setState: () {
              controller?.name = "Josh"; // update variables in the state
            });
          }, icon: Icon(Icons.refresh))
        ],
      ),
    );
  }
}
```

<div id="showing-toast-notifications"></div>
<br>

### Showing Toast Notifications

You can show toast notifications by calling one of the following:
- `showToastSuccess(description: "Welcome back")`
- `showToastDanger(description: "Your card has expired")`
- `showToastWarning(description: "That email is already taken")`
- `showToastInfo(description: "5 new messages")`

If you want to customize the look of notifications, check out the **resources/widgets/toast_notification_widget.dart** file.

<div id="change-language"></div>
<br>

### Change Language

You can change the language by calling the `changeLanguage` method.

``` dart
changeLanguage("es"); // switch to Spanish locale
```

Read the docs on localization [here](/docs/{{$version}}/localization) to understand more.

<div id="validation"></div>
<br>

### Validation

You can validate a form by calling the `validate` method.

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';

class DashboardPage extends NyPage {

  static String path = '/dashboard';

  @override
  init() async {

  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
          title: Text("Dashboard")
      ),
      body: SafeArea(
        child: InkWell(
          child: Text("Login"),
          onTap: _login,
        )
      ),
    );
  }

  _login() {
    String email = "anthony@mail.com";
    String password = "password1";

    validate(rules: {
      "email": "email",
      "password": r'regex:([0-9]+)' // password contains one number
    }, data: {
      "email": email,
      "password": password
    }, onSuccess: () {
      NyLogger.debug("Success!");
    });
  }
}
```

Read the docs on validation [here](/docs/{{$version}}/validation) to understand more.
