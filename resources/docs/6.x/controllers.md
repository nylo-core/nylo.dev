# Controllers

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction to controllers")
- [Creating pages and controllers](#creating-pages-and-controllers "Creating pages and controllers")
- [Using controllers](#using-controllers "Using controllers")
- [Singleton Controller](#singleton-controller "Singleton Controller")

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

routeTo(ProfilePage.path, data: user);
```

When we navigate to the 'Profile' page, we can call `data()` to get the data from the previous page.

``` dart 
...
class _ProfilePageState extends NyPage<ProfilePage> {
  
  @override
  get init => () async {
    User user = data(); // data passed from previous page

    print(user.firstName); // Anthony
  };
```

The `routeTo(String routeName, data: dynamic)` **data** parameter accepts dynamic types so you can cast the object after it’s returned.

<div id="using-controllers"></div>
<br>

## Using controllers

In your page, you can access the controller using `widget.controller`.

Your controller must added to the NyStatefulWidget class like in the below example:

`NyStatefulWidget<HomeController>`

``` dart
import 'package:nylo_framework/nylo_framework.dart';
import '/app/controllers/my_controller.dart';

class HomePage extends NyStatefulWidget<HomeController> {
  
  static RouteView path = ("/home", (_) => HomePage());

  HomePage() : super(child: () => _HomePageState());
}

class _HomePageState extends NyPage<HomePage> {
    
    // init - called when the page is created
    get init => () async {
        // access the controller
        widget.controller.data(); // data passed from a previous page
        widget.controller.queryParameters(); // query parameters passed from a previous page
    };

    @override
    Widget view(BuildContext context) {
        return Scaffold(
            appBar: AppBar(
                title: Text("My Page"),
            ),
            body: Column(
                children: [
                    Text("My Page").onTap(() {
                        // call an action from that controller
                        widget.controller.doSomething();
                    }),
                    TextField(
                        controller: widget.controller.myController, 
                        // access the controller's properties
                    ),
                ],
            )
        );
    }
}
```

### Controller Decoders

In {{ config('app.name') }} your project will contain a `config/decoders.dart` file. 

Inside this file there is a variable named `controllers` which is a map of all your controllers.

``` dart
import 'package:nylo_framework/nylo_framework.dart';
...

final Map<Type, BaseController> controllers = {
  HomeController: () => HomeController(),

  MyNewController: () => MyNewController(), // new controller
  // ...
};
```

<div id="singleton-controller"></div>
<br>

## Singleton controller

You can use the `singleton` property in your controller to make it a singleton.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class HomeController extends Controller {

  @override
  bool get singleton => true;

  ...
```

This will make sure that the controller is only created once and will be reused throughout the app.


