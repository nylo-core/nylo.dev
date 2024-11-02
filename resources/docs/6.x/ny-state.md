# NyState

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [How to use NyState](#how-to-use-nystate "How to use NyState")
- [Loading Style](#loading-style "Loading Style")
- [State Management](#state-management "State Management")
- [State Actions](#state-actions "State Actions")
- [Helpers](#helpers "Helpers")


<a name="introduction"></a>
<br>
## Introduction

When you create a <a href="/docs/{{$version}}/metro#make-page" target="_BLANK">page</a> in {{ config('app.name') }}, it will extend the `NyState` class. This class overrides Flutter's `State` class and provides additional features to make development easier.

You can **interact** with the state extactly like you would with a normal Flutter state, but with the added benefits of the NyState.

Let's cover how to use NyState.

<a name="how-to-use-nystate"></a>
<br>

## How to use NyState

You can start using this class by extending it.

Example 

``` dart
class _HomePageState extends NyState<HomePage> {

  @override
  get init => () async {
    
  };

  @override
  view(BuildContext context) {
    return Scaffold(
        body: Text("The page loaded")
    );
  }
```

The `init` method is used to initialize the state of the page. You can use this method as async or without async and behind the scenes, it will handle the async call and display a loader.

The `view` method is used to display the UI for the page.

#### Creating a new page 

To create a new page in {{ config('app.name') }}, you can run the below command.

``` bash
dart run nylo_framework:main make:page product_page
// or with the alias metro
metro make:page product_page
```

<a name="loading-style"></a>
<br>

## Loading Style

You can use the `loadingStyle` property to set the loading style for your page.

Example

``` dart
class _HomePageState extends NyState<HomePage> {

  @override
  LoadingStyleType get loadingStyle => LoadingStyleType.normal();

  @override
  get init => () async {
    await sleep(3); // simulate a network call for 3 seconds
  };
```

The **default** `loadingStyle` will be your loading Widget (resources/widgets/loader_widget.dart).
You can customize the `loadingStyle` to update the loading style.

Here's a table for the different loading styles you can use:
// normal, skeletonizer, none

| Style | Description |
| --- | --- |
| normal | Default loading style |
| skeletonizer | Skeleton loading style |
| none | No loading style |

You can change the loading style like this:

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal();
// or
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer();
```

If you want to update the loading Widget in one of the styles, you can pass a `child` to the `LoadingStyle`.

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal(
    child: Center(
        child: Text("Loading..."),
    ),
);
// same for skeletonizer
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer(
    child: Container(
        child: PageLayoutForSkeletonizer(),
    )
);
```

Now, when the tab is loading, the text "Loading..." will be displayed.

Example below:

``` dart
class _HomePageState extends NyState<HomePage> {
    get init => () async {
        await sleep(3); // simulate a network call for 3 seconds
    };

    @override
    LoadingStyle get loadingStyle => LoadingStyle.normal(
        child: Center(
            child: Text("Loading..."),
        ),
    );

    @override
    Widget view(BuildContext context) {
        return Scaffold(
            body: Text("The page loaded")
        );
    }
    ... 
}
```


<a name="state-management"></a>
<br>

## State Management

``` dart
class _SettingsTabState extends NyState<SettingsTab> {

  _SettingsTabState() {
    stateName = SettingsTab.state;
  }

  @override
  get init => () async { 
    // handle how you want to initialize the state
    // 'stateData' will contain the data you pass to the state
  };
  
  @override
  void stateUpdated(data) {
    // e.g. to update this state from another class
    // updateState(SettingsTab.state, data: "example payload");
  }

  @override
  Widget view(BuildContext context) {
    return Container(
      child: Cart(),
    );
  }
}
```

Learn more about state management <a href="/docs/{{$version}}/state-management" target="_BLANK">here</a>.
You can also watch our YouTube video on State Management <a href="https://youtu.be/X5EVh1KooFk?si=8hYQcXV9lvrSJgEL" target="_BLANK">here</a>.

<a name="state-actions"></a>
<br>

## State Actions

State actions are methods that can be called from other classes to update the state.
Out the box, you can use the follow methods to update the state.

- `refreshPage` - Refresh the page.
- `pop` - Pop the page.
- `showToastSorry` - Display a sorry toast notification.
- `showToastWarning` - Display a warning toast notification.
- `showToastInfo` - Display an info toast notification.
- `showToastDanger` - Display a danger toast notification.
- `showToastOops` - Display an oops toast notification.
- `showToastSuccess` - Display a success toast notification.
- `showToastCustom` - Display a custom toast notification.
- `validate` - Validate data from your widget.
- `changeLanguage` - Update the language in the application.
- `confirmAction` - Perform a confirm action.

Example

``` dart
class HomeController extends Controller {

  actions() {    
    // from the controller, refresh the state of the notification icon
    StateAction.refreshPage(NotificationIcon.state);

    // from the controller, refresh the state of the pull to refresh widget
    StateAction.refreshPage(NyPullToRefresh.state);

    // from the controller, pop the current page
    StateAction.pop(HomeController.path);

  }
}
```

You can use the `StateAction` class to update the state of any page/widget in your application as long as the widget is state managed.

<a name="helpers"></a>
<br>

## Helpers

|  |  |
| --- | ----------- |
| [color](#color "color") | [lockRelease](#lock-release "lockRelease") |
| [showToast](#showToast "showToast") | [isLoading](#is-loading "isLoading") |
| [validate](#validate "validate") | [afterLoad](#after-load "afterLoad") |
| [afterNotLocked](#afterNotLocked "afterNotLocked") | [afterNotNull](#after-not-null "afterNotNull") |
| [whenEnv](#when-env "whenEnv") | [setLoading](#set-loading "setLoading") |
| [pop](#pop "pop") | [isLocked](#is-locked "isLocked") |
| [changeLanguage](#change-language "changeLanguage") | [confirmAction](#confirm-action "confirmAction") |
| [showToastSuccess](#show-toast-success "showToastSuccess") | [showToastOops](#show-toast-oops "showToastOops") |
| [showToastDanger](#show-toast-danger "showToastDanger") | [showToastInfo](#show-toast-info "showToastInfo") |
| [showToastWarning](#show-toast-warning "showToastWarning") | [showToastSorry](#show-toast-sorry "showToastSorry") |


<a name="color"></a>
<br>

### Color

Returns a color from your current <a href="/docs/{{$version}}/themes#theme-colors" target="_BLANK">theme</a>.

Example

```dart
class _HomePageState extends NyState<HomePage> {

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        body: Text("The page loaded", style: TextStyle(
          color: color().primaryContent
        )
      )
    );
  }
```

<a name="reboot"></a>
<br>

### Reboot

This method will re-run the `init` method in your state. It's useful if you want to refresh the data on the page.

Example
```dart
class _HomePageState extends NyState<HomePage> {

  List<User> users = [];

  @override
  get init => () async {
    users = await api<ApiService>((request) => request.fetchUsers());
  };

  @override
  Widget view(BuildContext context) {
    return Scaffold(
        appBar: AppBar(
          title: Text("Users"),
          actions: [
            IconButton(
              icon: Icon(Icons.refresh),
              onPressed: () {
                reboot(); // refresh the data
              },
            )
          ],
        ),
        body: ListView.builder(
          itemCount: users.length,
          itemBuilder: (context, index) {
            return Text(users[index].firstName);
          }
        ),
    );
  }
}
```

<a name="pop"></a>
<br>
### Pop

`pop` - Remove the current page from the stack.

Example

``` dart
class _HomePageState extends NyState<HomePage> {
  
  popView() {
    pop();
  }

  @override
  Widget view(BuildContext context) {
    return Scaffold(
      body: InkWell(
        onTap: popView,
        child: Text("Pop current view")
      )
    );
  }
```


<a name="showToast"></a>
<br>

### showToast

Show a toast notification on the context.

Example

```dart
class _HomePageState extends NyState<HomePage> {
  
  displayToast() {
    showToast(
        title: "Hello",
        description: "World", 
        icon: Icons.account_circle,
        duration: Duration(seconds: 2),
        style: ToastNotificationStyleType.INFO // SUCCESS, INFO, DANGER, WARNING
    );
  }

  @override
  Widget view(BuildContext context) {
    return Scaffold(
      body: InkWell(
        onTap: displayToast,
        child: Text("Display a toast")
      )
    );
  }
```


<a name="validate"></a>
<br>

### validate

The `validate` helper performs a validation check on data. 

You can learn more about the validator <a href="/docs/{{$version}}/validation" target="_BLANK">here</a>.

Example

``` dart
class _HomePageState extends NyState<HomePage> {
TextEditingController _textFieldControllerEmail = TextEditingController();

  handleForm() {
    String textEmail = _textFieldControllerEmail.text;

    validate(rules: {
        "email address": [textEmail, "email"]
      }, onSuccess: () {
      print('passed validation')
    });
  }
```


<a name="change-language"></a>
<br>

### changeLanguage

You can call `changeLanguage` to change the json **/lang** file used on the device.

Learn more about localization <a href="/docs/{{$version}}/localization" target="_BLANK">here</a>.

Example

``` dart
class _HomePageState extends NyState<HomePage> {
  
  changeLanguageES() {
    await changeLanguage('es');
  }

  @override
  Widget view(BuildContext context) {
    return Scaffold(
      body: InkWell(
        onTap: changeLanguageES,
        child: Text("Change Language".tr())
      )
    );
  }
```


<a name="when-env"></a>
<br>

### whenEnv

You can use `whenEnv` to run a function when your application is in a certain state. 
E.g. your **APP_ENV** variable inside your `.env` file is set to 'developing', `APP_ENV=developing`.

Example

```dart
class _HomePageState extends NyState<HomePage> {

  TextEditingController _textEditingController = TextEditingController();
  
  @override
  get init => () {
    whenEnv('developing', perform: () {
      _textEditingController.text = 'test-email@gmail.com';
    });
  };
```

<a name="lock-release"></a>
<br>

### lockRelease

This method will lock the state after a function is called, only until the method has finished will it allow the user to make subsequent requests. This method will also update the state, use `isLocked` to check.

The best example to showcase `lockRelease` is to imagine that we have a login screen when the user taps 'Login'. We want to perform an async call to login the user but we don't want the method called multiple times as it could create an undesired experience.

Here's an example below.

```dart
class _LoginPageState extends NyState<LoginPage> {

  _login() async {
    await lockRelease('login_to_app', perform: () async {
      
      await Future.delayed(Duration(seconds: 4), () {
        print('Pretend to login...');
      });

    });
  }

  @override
  Widget view(BuildContext context) {
    return Scaffold(
        body: Column(
          crossAxisAlignment: CrossAxisAlignment.center,
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            if (isLocked('login_to_app'))
              AppLoader(),
            Center(
              child: InkWell(
                onTap: _login,
                child: Text("Login"),
              ),
            )
          ],
        )
    );
  }
```

Once you tap the **_login** method, it will block any subsequent requests until the original request has finished. The `isLocked('login_to_app')` helper is used to check if the button is locked. In the example above, you can see we use that to determine when to display our loading Widget.

<a name="is-locked"></a>
<br>

### isLocked

This method will check if the state is locked using the [`lockRelease`](#lock-release) helper.

Example
```dart
class _HomePageState extends NyState<HomePage> {

  @override
  Widget view(BuildContext context) {
    return Scaffold(
        body: Column(
          crossAxisAlignment: CrossAxisAlignment.center,
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            if (isLocked('login_to_app'))
              AppLoader(),
          ],
        )
    );
  }
```

<a name="view"></a>
<br>

### view

The `view` method is used to display the UI for the page.

Example
```dart
class _HomePageState extends NyState<HomePage> {

  @override
  Widget view(BuildContext context) {
      return Scaffold(
          body: Center(
              child: Text("My Page")
          )
      );
  }
}
```

<a name="confirm-action"></a>
<br>

### confirmAction

The `confirmAction` method will display a dialog to the user to confirm an action.
This method is useful if you want the user to confirm an action before proceeding.

Example

``` dart
_logout() {
 confirmAction(() {
    // logout();
 }, title: "Logout of the app?");   
}
```

<a name="show-toast-success"></a>
<br>

### showToastSuccess

The `showToastSuccess` method will display a success toast notification to the user.

Example
``` dart
_login() {
    ...
    showToastSuccess(
        description: "You have successfully logged in"
    );   
}
```

<a name="show-toast-oops"></a>
<br>

### showToastOops

The `showToastOops` method will display an oops toast notification to the user.

Example
``` dart
_error() {
    ...
    showToastOops(
        description: "Something went wrong"
    );
}
```

<a name="show-toast-danger"></a>
<br>

### showToastDanger

The `showToastDanger` method will display a danger toast notification to the user.

Example
``` dart
_error() {
    ...
    showToastDanger(
        description: "Something went wrong"
    );
}
```

<a name="show-toast-info"></a>
<br>

### showToastInfo

The `showToastInfo` method will display an info toast notification to the user.

Example
``` dart
_info() {
    ...
    showToastInfo(
        description: "Your account has been updated"
    );
}
```

<a name="show-toast-warning"></a>
<br>

### showToastWarning

The `showToastWarning` method will display a warning toast notification to the user.

Example
``` dart
_warning() {
    ...
    showToastWarning(
        description: "Your account is about to expire"
    );
}
```

<a name="show-toast-sorry"></a>
<br>

### showToastSorry

The `showToastSorry` method will display a sorry toast notification to the user.

Example
``` dart
_sorry() {
    ...
    showToastSorry(
        description: "Your account has been suspended"
    );
}
```

<a name="is-loading"></a>
<br>

### isLoading

The `isLoading` method will check if the state is loading.

Example
```dart
class _HomePageState extends NyState<HomePage> {

  @override
  Widget build(BuildContext context) {
    if (isLoading()) {
      return AppLoader();
    }

    return Scaffold(
        body: Text("The page loaded", style: TextStyle(
          color: colors().primaryContent
        )
      )
    );
  }
```

<a name="after-load"></a>
<br>

### afterLoad

The `afterLoad` method can be used to display a loader until the state has finished 'loading'.

You can also check other loading keys using the **loadingKey** parameter `afterLoad(child: () {}, loadingKey: 'home_data')`.

Example
```dart
class _HomePageState extends NyState<HomePage> {

  @override
  get init => () {
    awaitData(perform: () async {
        await sleep(4);
        print('4 seconds after...');
    });
  };

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        body: afterLoad(child: () {
          return Text("Loaded");
        })
    );
  }
```

<a name="after-not-locked"></a>
<br>

### afterNotLocked

The `afterNotLocked` method will check if the state is locked. 

If the state is locked it will display the [loading] widget.

Example
```dart
class _HomePageState extends NyState<HomePage> {  

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        body: Container(
          alignment: Alignment.center,
          child: afterNotLocked('login', child: () {
            return MaterialButton(
              onPressed: () {
                login();
              },
              child: Text("Login"),
            );
          }),
        )
    );
  }

  login() async {
    await lockRelease('login', perform: () async {
      await sleep(4);
      print('4 seconds after...');
    });
  }
}
```

<a name="after-not-null"></a>
<br>

### afterNotNull

You can use `afterNotNull` to show a loading widget until a variable has been set.

Imagine you need to fetch a user's account from a DB using a Future call which might take 1-2 seconds, you can use afterNotNull on that value until you have the data.

Example

```dart
class _HomePageState extends NyState<HomePage> {

  User? _user;

  @override
  get init => () async {
    _user = await api<ApiService>((request) => request.fetchUser()); // example
    setState(() {});
  };

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        body: afterNotNull(_user, child: () {
          return Text(_user!.firstName);
        })
    );
  }
```

<a name="set-loading"></a>
<br>

### setLoading

You can change to a 'loading' state by using `setLoading`. 

The first parameter accepts a `bool` for if it's loading or not, the next parameter allows you to set a name for the loading state, e.g. `setLoading(true, name: 'refreshing_content');`.

Example
```dart
class _HomePageState extends NyState<HomePage> {

  @override
  get init => () async {
    setLoading(true, name: 'refreshing_content');

    await sleep(4);

    setLoading(false, name: 'refreshing_content');
  };

  @override
  Widget build(BuildContext context) {
    if (isLoading(name: 'refreshing_content')) {
      return AppLoader();
    }

    return Scaffold(
        body: Text("The page loaded")
    );
  }
```
