# State Management

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [When to Use State Management](#when-to-use-state-management "When to Use State Management")
- [Lifecycle](#lifecycle "Lifecycle")
- [Updating a State](#updating-a-state "Updating a State")
- [State Actions](#state-actions "State Actions")
- [Building Your First Widget](#building-your-first-widget "Building Your First Widget")

<a name="introduction"></a>
<br>

## Introduction

In Nylo {{$version}}, you can build Widgets that use State Management.

In this section, we will learn about the `NyState`, and `NyPage` class. We'll also dive into some examples.


### Let's first understand State Management

Everything in Flutter is a widget, they are just tiny chunks of UI that you can combine to make a complete app.

When you start building complex pages, you will need to manage the state of your widgets. This means when something changes, e.g. data, you can update that widget without having to rebuild the entire page.

There are a lot of reasons why this is important, but the main reason is performance. If you have a widget that is constantly changing, you don't want to rebuild the entire page every time it changes.

This is where State Management comes in, it allows you to manage the state of a widget in your application.


<a name="when-to-use-state-management"></a>
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
dart run nylo_framework:main make:state_managed cart
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

<a name="lifecycle"></a>
<br>

## Lifecycle

The lifecycle of a `NyState` widget is as follows:

1. `init()` - This method is called when the state is initialized.

2. `stateUpdated(data)` - This method is called when the state is updated.

    If you call `updateState(MyStateName.state, data: "The Data")`, it will trigger **stateUpdated(data)** to be called. 

Once the state is first initialized, you will need to implement how you want to manage the state.

<a name="updating-a-state"></a>
<br>

## Updating a State

You can update a state by calling the `updateState()` method.

``` dart
updateState(MyStateName.state);

// or with data
updateState(MyStateName.state, data: "The Data");
```

This can be called anywhere in your application.

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

<a name="building-your-first-widget"></a>
<br>

## Building Your First Widget

In your Nylo project, run the following command to create a new widget.

``` dart
dart run nylo_framework:main make:stateful_widget todo_list
```

This will create a new `NyState` widget called `TodoList`.

> Note: The new widget will be created in the `lib/resources/widgets/` directory.
