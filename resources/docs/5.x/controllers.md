# Controllers

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction to controllers")
- [Creating pages and controllers](#creating-pages-and-controllers "Creating pages and controllers")
- [Using controllers with NyPage](#using-controllers-with-ny-page "Using controllers with NyPage")

<div id="introduction"></div>
<br>

## Introduction

Before starting, let's go over what a controller is for those new. 

Here's a quick summary from <a href="https://www.tutorialspoint.com/mvc_framework/mvc_framework_controllers.htm#:~:text=Asp.net%20MVC%20Controllers%20are,perform%20one%20or%20more%20actions" target="_BLANK">tutorialspoint.com</a>.

> The Controller is responsible for controlling the application logic and acts as the coordinator between the View and the Model. The Controller receives an input from the users via the View, then processes the user's data with the help of Model and passes the results back to the View.

<br>

Controller with services
``` dart
...
class HomeController extends Controller {

  AnalyticsService analyticsService;
  NotificationService notificationService;

  @override
  construct(BuildContext context) {
    // example services
    analyticsService = AnalyticsService();
    notificationService = NotificationService();
  }

  bool sendMessage(String message) async {
    bool success = await this.notificationService.sendMessage(message);
    if (success == false) {
      this.analyticsService.sendError("failed to send message");
    }
    return success;
  }

  onTapDocumentation() {
    launch("https://nylo.dev/docs");
  }

  ...
```

<br>

Call the controller from your page.

``` dart
class MyHomePage extends NyPage<HomeController> {
  ...
  MaterialButton(
    child: Text("Documentation"),
    onPressed: controller?.onTapDocumentation, // call the action
  ),
```

Or from your Widget

``` dart
class _MyHomePageState extends NyState<MyHomePage> {
  ...
  MaterialButton(
    child: Text("Documentation"),
    onPressed: widget.controller.onTapDocumentation, // call the action
  ),
```

If your widget has a controller, you can use `widget.controller` to access its properties.

You can use `dart run nylo_framework:main make:page account --controller` command to create a new page and controller automatically for you.

<div id="creating-pages-and-controllers"></div>
<br>

## Creating pages and controllers

You can use the Metro CLI tool to create your pages & controllers automatically. 

``` dart 
dart run nylo_framework:main make:page dashboard_page --controller
// or
dart run nylo_framework:main make:page dashboard_page -c
```

This will create a new controller in your **app/controllers** directory and a page in your **resources/pages** directory.

Or you can create a single controller using the below command.

``` dart 
dart run nylo_framework:main make:controller profile_controller
```


#### Retrieving arguments from routes

If you need to pass data from one widget to another, you can send the data using `Navigator` class or use the `routeTo` helper.

``` dart 
// Send an object to another page
User user = new User();
user.firstName = 'Anthony';

Navigator.pushNamed(context, '/profile', arguments: user);
// or
routeTo(ProfilePage.path, data: user);
```

When we navigate to the 'Profile' page, we can call `data()` to get the data from the previous page.

``` dart 
...
class ProfilePage extends NyPage {
  
  @override
  init() async {
    super.init();
    dynamic data = data(); // data passed from previous page
    
    User user = data;
    print(user.firstName); // Anthony
  }
```

Or from your `StatefulWidget`

``` dart 
...
class _ProfilePageState extends NyState<ProfilePage> {
  
  @override
  init() async {
    super.init();
    dynamic data = widget.data(); // data passed from previous page
    
    User user = data;
    print(user.firstName); // Anthony
  }
```

The `routeTo(String routeName, data: dynamic)` **data** parameter accepts dynamic types so you can cast the object after it’s returned.

<div id="using-controllers-with-ny-page"></div>
<br>

## Using controllers with NyPage

The `NyPage` widget makes it easy to use controllers.

You can use controllers by first extending the `NyPage` class and then set the controller like in the below example.

``` dart

import 'package:nylo_framework/nylo_framework.dart';
import '/app/controllers/my_controller.dart'; // import your controller

class HomePage extends NyPage<MyController> {
    
    // init - called when the page is created
    init() async {
        // access the controller
        controller.doSomething(); // call an action from that controller
        controller.data(); // data passed from a previous page
        controller.queryParameters(); // query parameters passed from a previous page
    }

    @override
    Widget build(BuildContext context) {
        return Scaffold(
        appBar: AppBar(
            title: Text("My Page"),
        ),
        body: Center(
            child: Text("Hello World"),
        ),
        );
    }
}
```

It's important that your controller is set in your `config/decoders.dart` file like in the below example.

``` dart

import 'package:nylo_framework/nylo_framework.dart';

...

final Map<Type, BaseController> controllers = {
  HomeController: HomeController(),

  MyNewController: MyNewController(), // new controller
  // ...
};
```

You should also add all your controllers to Nylo like in the below example:

1. Open `app/providers/app_provider.dart`

2. Add your controllers

``` dart

import 'package:nylo_framework/nylo_framework.dart';

...

class AppProvider implements NyProvider {
  @override
  boot(Nylo nylo) async {
    ...
    nylo.addControllers(controllers); // adds all controllers to Nylo

    return nylo;
  
  }
...
```

Now you should be ready to use a controller with your `NyPage`.

Learn more about NyPage [here](/docs/{{$version}}/ny-page).
