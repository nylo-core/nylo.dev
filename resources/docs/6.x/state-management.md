# State Management

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [When to Use State Management](#when-to-use-state-management "When to Use State Management")
- [Lifecycle](#lifecycle "Lifecycle")
- [Updating a State](#updating-a-state "Updating a State")
- [Sending actions](#sending-state-actions "Sending actions")
- [State Actions](#state-actions "State Actions")
- [Building Your First Widget](#building-your-first-widget "Building Your First Widget")

<div id="introduction"></div>
<br>

## Introduction

In Nylo {{$version}}, you can build Widgets that use State Management.

Nylo provides two classes for State Management:
- `NyState` - This is used for building reusable widgets.
- `NyPage` - This is used for building pages in your application.

In this section you'll learn how to use State Management in your Nylo project.


### Let's first understand State Management

Everything in Flutter is a widget, they are just tiny chunks of UI that you can combine to make a complete app.

When you start building complex pages, you will need to manage the state of your widgets. This means when something changes, e.g. data, you can update that widget without having to rebuild the entire page.

There are a lot of reasons why this is important, but the main reason is performance. If you have a widget that is constantly changing, you don't want to rebuild the entire page every time it changes.

This is where State Management comes in, it allows you to manage the state of a widget in your application.


<div id="when-to-use-state-management"></div>
<br>

### When to Use State Management

You should use State Management when you have a widget that needs to be updated without rebuilding the entire page.

For example, let's imagine you have created an ecommerce app. You have built a widget to display the total amount of items in the users' cart.
Let's call this widget `Cart()`.

A state managed `Cart` widget in Nylo would look something like this.

``` dart
/// The Cart widget
class Cart extends StatefulWidget {
  
  Cart({Key? key}) : super(key: key);
  
  static String state = "cart";

  @override
  _CartState createState() => _CartState();
}

/// The state class for the Cart widget
class _CartState extends NyState<Cart> {

  String? _cartValue;

  _CartState() {
    stateName = Cart.state;
  }

  @override
  get init => () async {
    _cartValue = await getCartValue();
  };
  
  @override
  void stateUpdated(data) {
    reboot(); // Reboot the widget
  }

  @override
  Widget view(BuildContext context) {
    return Badge(
      child: Icon(Icons.shopping_cart),
      label: Text(_cartValue ?? "1"),
    );
  }
}

/// Get the cart value from storage
Future<String> getCartValue() async {
  return await storageRead(Keys.cart) ?? "1";
}

/// Set the cart value
Future setCartValue(String value) async {
    await storageSave(Keys.cart, value);
    updateState(Cart.state);
}
```

Let's break this down.

1. The `Cart` widget is a `StatefulWidget`.

2. `_CartState` extends `NyState<Cart>`.

3. You need to define a name for the `state`, this is used to identify the state.

4. The `boot()` method is called when the widget is first loaded.

5. The `stateUpdate()` methods handle what happens when the state is updated.

If you want to try this example in your {{ config('app.name') }} project, create a new widget called `Cart`. 

``` dart
dart run nylo_framework:main make:state_managed_widget cart
```

Then you can copy the example above and try it in your project.

Now, to update the cart, you can call the following.

```dart 
_updateCart() async {
  String count = await getCartValue();
  String countIncremented = (int.parse(count) + 1).toString();

  await storageSave(Keys.cart, countIncremented);

  updateState(Cart.state);
}
```

<div id="lifecycle"></div>
<br>

## Lifecycle

The lifecycle of a `NyState` widget is as follows:

1. `init()` - This method is called when the state is initialized.

2. `stateUpdated(data)` - This method is called when the state is updated.

    If you call `updateState(MyStateName.state, data: "The Data")`, it will trigger **stateUpdated(data)** to be called. 

Once the state is first initialized, you will need to implement how you want to manage the state.

<div id="updating-a-state"></div>
<br>

## Updating a State

You can update a state by calling the `updateState()` method.

``` dart
updateState(MyStateName.state);

// or with data
updateState(MyStateName.state, data: "The Data");
```

This can be called anywhere in your application.

<div id="state-actions"></div>
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

<div id="sending-state-actions"></div>
<br>

## Sending State Actions

In Nylo, you can send action events to your Widgets. 

```
// Send an action to the widget
stateAction('hello_world_in_widget', state: MyWidget.state);

// Another example
stateAction('reset_data', state: HighScore.state);
```

In your widget, you need the following code to handle the action.

``` dart
...
@override
get stateActions => {
  "hello_world_in_widget": () {
    print('Hello world');
  },
  "reset_data": () async {
    // Example
    _textController.clear();
    _myData = null;
    setState(() {});
  },
};
```

This is useful when you want to update the state of a widget from another widget or class.

### NyState - State Actions

First, create your state managed widget.

``` bash
metro make:state_managed_widget my_widget
```

This will create a new state managed widget called `MyWidget` in the `lib/resources/widgets/` directory.

If you open that file, you'll be able to define your state actions.

``` dart
class _MyWidgetState extends NyState<MyWidget> {
...

@override
get stateActions => {
  "print_hello_world": () {
    print('Hello from the widget');
  },
  "reset_data": () {
    // Example
    _textController.clear();
    _myData = null;
    setState(() {});
  },
};
```

Finally, you can send the action from anywhere in your application.

``` dart
stateAction('print_hello_world', state: MyWidget.state);

// prints 'Hello from the widget'

stateAction('reset_data', state: MyWidget.state);

// Reset data in widget
```

### NyPage - State Actions

First, create your state managed page.

``` bash
metro make:page my_page
```

This will create a new state managed page called `MyPage` in the `lib/resources/pages/` directory.

If you open that file, you'll be able to define your state actions.

``` dart
class _MyPageState extends NyPage<MyPage> {
...

@override
bool get stateManaged => true;

@override
get stateActions => {
  "test_page_action": () {
    print('Hello from the page');
  },
  "reset_data": () {
    // Example
    _textController.clear();
    _myData = null;
    setState(() {});
  },
};
```

Finally, you can send the action from anywhere in your application.

``` dart
stateAction('test_page_action', state: MyPage.state);

// prints 'Hello from the page'
```

You can also define your state actions using the `whenStateAction` method.

``` dart
@override
stateUpdated(dynamic data) async {
  super.stateUpdated(data);
  ...
  whenStateAction({
    "reset_badge": () {
      // Reset the badge count
      _count = 0;
    }
  });
}
```

Then you can send the action from anywhere in your application.

``` dart
stateAction('reset_badge', state: MyPage.state);
```

<div id="building-your-first-widget"></div>
<br>

## Building Your First Widget

In your Nylo project, run the following command to create a new widget.

``` dart
dart run nylo_framework:main make:stateful_widget todo_list
```

This will create a new `NyState` widget called `TodoList`.

> Note: The new widget will be created in the `lib/resources/widgets/` directory.
