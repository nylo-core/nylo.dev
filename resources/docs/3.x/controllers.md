# Controllers

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction to controllers")
- [Creating pages and controllers](#creating-pages-and-controllers "Creating pages and controllers")

<div id="introduction"></div>
<br>

## Introduction

Before starting, let's go over what a controller is for those new. 

Here's a quick summary by <a href="https://www.tutorialspoint.com/mvc_framework/mvc_framework_controllers.htm#:~:text=Asp.net%20MVC%20Controllers%20are,perform%20one%20or%20more%20actions" target="_BLANK">tutorialspoint.com</a>.

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

Call the controller from your widget.

``` dart
class _MyHomePageState extends NyState<MyHomePage> {
  ...
  MaterialButton(
  child: Text(
    "Documentation",
    style: Theme.of(context).textTheme.bodyText1,
  ),
  onPressed: widget.controller.onTapDocumentation,
),
```

If your widget has a controller, you can use `widget.controller` to access its properties.

You can use `flutter pub run nylo_framework:main make:page account --controller` command to create a new page and controller automatically for you.

<div id="creating-pages-and-controllers"></div>
<br>

## Creating pages and controllers

You can use the Metro CLI tool to create your pages & controllers automatically. 

``` dart 
flutter pub run nylo_framework:main make:page my_cool_page --controller
```

This will create a new controller in your **app/controllers** directory and a page in your **resources/pages** directory.

Or you can create a single controller using the below command.

``` dart 
flutter pub run nylo_framework:main make:controller profile_controller
```


#### Retrieving arguments from routes

If you need to pass data from one widget to another, you can send the data using `Navigator` class or use the `routeTo` helper.

``` dart 
// User object
User user = new User();
user.firstName = 'Anthony';

Navigator.pushNamed(context, '/profile', arguments: user);
// or
routeTo('/profile', data: user);
```

When we navigate to that page we can call `widget.data()` to get the data.

``` dart 
...
class _ProfilePageState extends NyState<ProfilePage> {
  @override
  void initState() {
    super.initState();
    User user = widget.data();
    print(user.firstName); // Anthony

  }
```

The `routeTo(String routeName, data: dynamic)` **data** parameter accepts dynamic types so you can cast the object after it’s returned.
