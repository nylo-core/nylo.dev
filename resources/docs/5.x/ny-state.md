# NyState

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [How to use NyState](#how-to-use-nystate "How to use NyState")
- [State Management](#state-management "State Management")
- [Helpers](#helpers "Helpers")


<a name="introduction"></a>
<br>
## Introduction

When you create a <a href="/docs/{{$version}}/metro#make-page" target="_BLANK">page</a> in {{ config('app.name') }}, it will extend the `NyState` class. This class provides useful utilities to make development easier. 

The `NyState` class can help you with the following:

- Networking
- Loading data
- Themes
- Validation
- Navigation
- Managing the state
- Changing Language

<a name="how-to-use-nystate"></a>
<br>

## How to use NyState

You can start using this class by extending it.

E.g. 

``` dart
class _HomePageState extends NyState<HomePage> {

  @override
  init() async {
    super.init();
    
  }
```

Once your page extends `NyState` you can initialize the widget using the `init` method.
This method is called inside `initState` from within your Flutter state and it makes it easier to call async functions.

To create a new page in {{ config('app.name') }}, you can run the below command.

``` bash
dart run nylo_framework:main make:page product_page
```

Or with the alias metro

``` bash
metro make:page product_page
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
  init() async {
    super.init();
    
  }
  
  @override
  void stateUpdated(data) {
    // e.g. to update this state from another class
    // updateState(SettingsTab.state, data: "example payload");
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      child: Cart(),
    );
  }
}
```


<a name="helpers"></a>
<br>

## Helpers

|  |  |
| --- | ----------- |
| [color](#color "color") | [lockRelease](#lock-release "lockRelease") |
| [boot](#boot "boot") | [reboot](#reboot "reboot") |
| [showToast](#showToast "showToast") | [isLoading](#is-loading "isLoading") |
| [validate](#validate "validate") | [afterLoad](#after-load "afterLoad") |
| [afterNotLocked](#afterNotLocked "afterNotLocked") | [afterNotNull](#after-not-null "afterNotNull") |
| [whenEnv](#when-env "whenEnv") | [setLoading](#set-loading "setLoading") |
| [pop](#pop "pop") | [isLocked](#is-locked "isLocked") |
| [changeLanguage](#change-language "changeLanguage") |


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

<a name="boot"></a>
<br>

### Boot

The `boot` method is used in conjunction with `afterLoad` to make async calls easier. You can call **await** on Future methods inside `boot` and while waiting, the afterLoad method will display a loader (from your **config/design.dart** file). After the boot method has finished, it will display the **child** Widget of your choice.

Example

```dart
class _HomePageState extends NyState<HomePage> {

  @override
  boot() async {
    await Future.delayed(Duration(seconds: 4));
    print('After 4 seconds...');
  }

  @override
  Widget build(BuildContext context) {
      return Scaffold(
          body: Center(
              child: afterLoad(child: () {
                return Text("The page loaded");
              })
          )
      );
  }
```

<a name="reboot"></a>
<br>

### Reboot

This method will re-run the `boot` method in your state. It's useful if you want to refresh the data on the page.

Example
```dart
class _HomePageState extends NyState<HomePage> {

  List<User> users = [];

  @override
  boot() async {
    users = await api<ApiService>((request) => request.fetchUsers());
  }

  @override
  Widget build(BuildContext context) {
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
  Widget build(BuildContext context) {
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
  Widget build(BuildContext context) {
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
        "email address": "email"
      }, data: {
        "email address": textEmail
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
  Widget build(BuildContext context) {
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
  init() async {
    super.init();
    whenEnv('developing', perform: () {
      _textEditingController.text = 'test-email@gmail.com';
    });
  }
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
  Widget build(BuildContext context) {
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
  Widget build(BuildContext context) {
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
  init() async {
    super.init();

    awaitData(perform: () async {
        await Future.delayed(Duration(seconds: 4));
        print('4 seconds after...');
    });
  }

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
      await Future.delayed(Duration(seconds: 4));
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
  init() async {
    super.init();
    _user = await api<ApiService>((request) => request.fetchUser()); // example
    setState(() {});
  }

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
  init() async {
    super.init();
    setLoading(true, name: 'refreshing_content');

    await Future.delayed(Duration(seconds: 4));

    setLoading(false, name: 'refreshing_content');
  }

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
