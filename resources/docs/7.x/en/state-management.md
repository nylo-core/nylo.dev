# State Management

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [When to Use State Management](#when-to-use-state-management "When to Use State Management")
- [Lifecycle](#lifecycle "Lifecycle")
- [State Actions](#state-actions "State Actions")
  - [NyState - State Actions](#state-actions-nystate "NyState - State Actions")
  - [NyPage - State Actions](#state-actions-nypage "NyPage - State Actions")
- [Updating a State](#updating-a-state "Updating a State")
- [Building Your First Widget](#building-your-first-widget "Building Your First Widget")

<div id="introduction"></div>

## Introduction

State management lets you update specific parts of your UI without rebuilding entire pages. In {{ config('app.name') }} v7, you can build widgets that communicate and update each other across your app.

{{ config('app.name') }} provides two classes for state management:
- **`NyState`** — For building reusable widgets (like a cart badge, notification counter, or status indicator)
- **`NyPage`** — For building pages in your application (extends `NyState` with page-specific features)

Use state management when you need to:
- Update a widget from a different part of your app
- Keep widgets in sync with shared data
- Avoid rebuilding entire pages when only part of the UI changes


### Let's first understand State Management

Everything in Flutter is a widget, they are just tiny chunks of UI that you can combine to make a complete app.

When you start building complex pages, you will need to manage the state of your widgets. This means when something changes, e.g. data, you can update that widget without having to rebuild the entire page.

There are a lot of reasons why this is important, but the main reason is performance. If you have a widget that is constantly changing, you don't want to rebuild the entire page every time it changes.

This is where State Management comes in, it allows you to manage the state of a widget in your application.


<div id="when-to-use-state-management"></div>

### When to Use State Management

You should use State Management when you have a widget that needs to be updated without rebuilding the entire page.

For example, let's imagine you have created an ecommerce app. You have built a widget to display the total amount of items in the users' cart.
Let's call this widget `Cart()`.

A state managed `Cart` widget in Nylo would look something like this:

**Step 1:** Define the widget extending `NyStateManaged`

``` dart
/// The Cart widget
class Cart extends NyStateManaged {
  Cart({super.key, super.stateName})
      : super(child: () => _CartState(stateName));

  static String state = "cart"; // Unique identifier for this widget's state

  static String _stateFor(String? state) =>
      state == null ? Cart.state : "${Cart.state}_$state";

  static action(String action, {dynamic data, String? stateName}) {
    return stateAction(action, data: data, state: _stateFor(stateName));
  }
}
```

**Step 2:** Create the state class extending `NyState`

``` dart
/// The state class for the Cart widget
class _CartState extends NyState<Cart> {

  String? _cartValue;

  _CartState(String? stateName) {
    this.stateName = Cart._stateFor(stateName);
  }

  @override
  get init => () async {
    _cartValue = await getCartValue(); // Load initial data
  };

  @override
  Map<String, Function> get stateActions => {
    "reload_cart": (data) async {
      _cartValue = await getCartValue();
      setState(() {});
    },
    "clear_cart": () {
      _cartValue = null;
      setState(() {});
    },
  };

  @override
  Widget view(BuildContext context) {
    return Badge(
      child: Icon(Icons.shopping_cart),
      label: Text(_cartValue ?? "1"),
    );
  }
}
```

**Step 3:** Create helper functions to read and update the cart

``` dart
/// Get the cart value from storage
Future<String> getCartValue() async {
  return await storageRead(Keys.cart) ?? "1";
}

/// Set the cart value and notify the widget
Future setCartValue(String value) async {
    await storageSave(Keys.cart, value);
    updateState(Cart.state); // This triggers stateUpdated() on the widget
}
```

Let's break this down.

1. The `Cart` widget extends `NyStateManaged` (not `StatefulWidget` directly).

2. The `stateName` constructor parameter is forwarded via `super(child: () => _CartState(stateName))`, enabling multiple isolated instances of the same widget.

3. The `_stateFor(String? state)` helper produces a namespaced state key like `"cart_sidebar"` for named instances.

4. `_CartState` extends `NyState<Cart>` and receives `stateName` to register the correct isolated state.

5. The `stateActions` map defines named commands you can invoke on the widget from anywhere in your app.

If you want to try this example in your {{ config('app.name') }} project, create a new widget called `Cart`. 

``` bash
metro make:state_managed_widget cart
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

## Lifecycle

The lifecycle of a `NyState` widget is as follows:

1. `init()` - This method is called when the state is initialized.

2. `stateUpdated(data)` - This method is called when the state is updated.

    If you call `updateState(MyStateName.state, data: "The Data")`, it will trigger **stateUpdated(data)** to be called. 

Once the state is first initialized, you will need to implement how you want to manage the state.


<div id="state-actions"></div>

## State Actions

State actions let you trigger specific methods on a widget from anywhere in your app. Think of them as named commands you can send to a widget.

Use state actions when you need to:
- Trigger a specific behavior on a widget (not just refresh it)
- Pass data to a widget and have it respond in a particular way
- Create reusable widget behaviors that can be invoked from multiple places

``` dart
// Sending an action to the widget
stateAction('hello_world_in_widget', state: MyWidget.state);

// Another example with data
stateAction('show_high_score', state: HighScore.state, data: {
  "high_score": 100,
});
```

In your widget, you can define the actions you want to handle.

``` dart
...
@override
get stateActions => {
  "hello_world_in_widget": () {
    print('Hello world');
  },
  "reset_data": (data) async {
    // Example with data
    _textController.clear();
    _myData = null;
    setState(() {});
  },
};
```

Then, you can call `stateAction` method from anywhere in your application.

``` dart
stateAction('hello_world_in_widget', state: MyWidget.state);
// prints 'Hello world'

User user = User(name: "John Doe", age: 30);
stateAction('update_user_info', state: MyWidget.state, data: user);
```

If you already have a `StateActions` instance (e.g. from a widget's `stateActions()` static method), you can call `action()` on it directly instead of using the free function:

``` dart
// Using the free function
stateAction('reset_avatar', state: UserAvatar.state);

// Using the StateActions instance method — equivalent, less repetition
final actions = UserAvatar.stateActions(UserAvatar.state);
actions.action('reset_avatar');
actions.action('update_user_image', data: user);
```

You can also define your state actions using the `whenStateAction` method in your `init` getter.

``` dart
@override
get init => () async {
  ...
  whenStateAction({
    "reset_badge": () {
      // Reset the badge count
      _count = 0;
    }
  });
}
```


<div id="state-actions-nystate"></div>

### NyState - State Actions

First, create a stateful widget.

``` bash
metro make:stateful_widget [widget_name]
```
Example: metro make:stateful_widget user_avatar

This will create a new widget in the `lib/resources/widgets/` directory.

If you open that file, you'll be able to define your state actions.

``` dart
class _UserAvatarState extends NyState<UserAvatar> {
...

@override
get stateActions => {
  "reset_avatar": () {
    // Example
    _avatar = null;
    setState(() {});
  },
  "update_user_image": (User user) {
    // Example
    _avatar = user.image;
    setState(() {});
  },
  "show_toast": (data) {
    showSuccessToast(description: data['message']);
  },
};
```

Finally, you can send the action from anywhere in your application.

``` dart
stateAction('reset_avatar', state: MyWidget.state);
// prints 'Hello from the widget'

stateAction('reset_data', state: MyWidget.state);
// Reset data in widget

stateAction('show_toast', state: MyWidget.state, data: "Hello world");
// shows a success toast with the message
```


<div id="state-actions-nypage"></div>

### NyPage - State Actions

Pages can also receive state actions. This is useful when you want to trigger page-level behaviors from widgets or other pages.

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
bool get stateManaged => false; // set to true to enable state actions on this page

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
  "show_toast": (data) {
    showSuccessToast(description: data['message']);
  },
};
```

Finally, you can send the action from anywhere in your application.

``` dart
stateAction('test_page_action', state: MyPage.path);
// prints 'Hello from the page'

stateAction('reset_data', state: MyPage.path);
// Reset data in page

stateAction('show_toast', state: MyPage.path, data: {
  "message": "Hello from the page"
});
// shows a success toast with the message
```

You can also define your state actions using the `whenStateAction` method.

``` dart
@override
get init => () async {
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
stateAction('reset_badge', state: MyWidget.state);
```


<div id="updating-a-state"></div>

## Updating a State

You can update a state by calling the `updateState()` method.

``` dart
updateState(MyStateName.state);

// or with data
updateState(MyStateName.state, data: "The Data");
```

This can be called anywhere in your application.

**See also:** [NyState](/docs/{{ $version }}/ny-state) for more details on state management helpers and lifecycle methods.


<div id="building-your-first-widget"></div>

## Building Your First Widget

In your Nylo project, run the following command to create a new widget.

``` bash
metro make:stateful_widget todo_list
```

This will create a new `NyState` widget called `TodoList`.

> Note: The new widget will be created in the `lib/resources/widgets/` directory.
