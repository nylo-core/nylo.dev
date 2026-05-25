# State Managed Widgets

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Choose Your Pattern](#choose-your-pattern "Choose Your Pattern")
- [State Actions](#state-actions "State Actions")
  - [Defining Handlers](#defining-handlers "Defining Handlers")
  - [Triggering Actions](#triggering-actions "Triggering Actions")
  - [Handlers With and Without Data](#handlers-with-and-without-data "Handlers With and Without Data")
  - [Using a StateActions Instance](#using-a-state-actions-instance "Using a StateActions Instance")
- [Pattern: State Managed Pages (NyPage)](#pattern-ny-page "Pattern: State Managed Pages")
- [Pattern: Stateful Widgets (NyState)](#pattern-ny-state "Pattern: Stateful Widgets")
- [Pattern: State Managed Widgets (NyStateManaged)](#pattern-ny-state-managed "Pattern: State Managed Widgets")
  - [Advanced: Multiple Isolated Instances](#advanced-multiple-isolated-instances "Advanced: Multiple Isolated Instances")
- [Lifecycle Reference](#lifecycle "Lifecycle Reference")

<div id="introduction"></div>

## Introduction

State managed widgets let you update specific parts of your UI from anywhere in your app — without rebuilding entire pages. In {{ config('app.name') }} v7, you trigger named **state actions** on a target widget or page, and the matching handler runs.

The mental model is simple:

- Each state managed widget or page has a unique **state key** (a string)
- It defines a map of **named actions** it knows how to handle
- Anywhere in your app, you can call 
```
stateAction("action_name", state: TargetWidget.state)
``` 

There are three patterns for building state managed UI. They all share the same `stateAction` mechanic — the only difference is what kind of UI you're managing.


<div id="choose-your-pattern"></div>

## Choose Your Pattern

| You want to... | Use | Scaffold command |
|---|---|---|
| State-manage a full page | `NyPage` | `metro make:page my_page` |
| State-manage a single-instance widget | `NyState` | `metro make:stateful_widget my_widget` |
| State-manage a widget with multiple isolated instances | `NyStateManaged` | `metro make:state_managed_widget my_widget` |

Read the [State Actions](#state-actions) section first — it explains the API every pattern uses. Then jump to the pattern that fits your case.


<div id="state-actions"></div>

## State Actions

State actions are named commands a widget or page knows how to handle. You define a map of action names to handler functions, and trigger them by name from anywhere in your app.

Use state actions when you need to:
- Trigger a specific behavior on a widget or page (not just a generic refresh)
- Pass data to a widget and have it respond in a defined way
- Build reusable widget behaviors that can be invoked from multiple call sites

<div id="defining-handlers"></div>

### Defining Handlers

Handlers live in the `stateActions` getter on your `NyState` or `NyPage` class. The map keys are action names; the values are functions to run when that action is triggered.

``` dart
@override
Map<String, Function> get stateActions => {
  "reload_cart": () async {
    _cartValue = await getCartValue();
    setState(() {});
  },
  "clear_cart": () {
    _cartValue = null;
    setState(() {});
  },
  "apply_discount": (code) async {
    _discount = await validateDiscount(code);
    setState(() {});
  },
};
```

Handlers are responsible for calling `setState` themselves if they want the widget to rebuild.

The `apply_discount` handler above takes a `code` argument — declare a single positional parameter when your handler needs the payload passed via 
```
stateAction("reload_cart", state: TargetWidget.state);
stateAction("clear_cart", state: TargetWidget.state);
stateAction("apply_discount", state: TargetWidget.state, data: "promo_code_123");
```

Use the no-arg form `()` when the action carries no payload.


<div id="triggering-actions"></div>

### Triggering Actions

Use the top-level `stateAction` function to fire an action from anywhere — another widget, a controller, an event handler, an API callback, etc.

``` dart
// Fire an action with no data
stateAction("clear_cart", state: Cart.state);

// Fire an action with data
stateAction("show_toast", state: Cart.state, data: {
  "message": "Item added",
});
```

The `state:` argument is the **state key** of the target:
- For widgets — `MyWidget.state` (a string)
- For pages — `MyPage.path` (the route)


<div id="handlers-with-and-without-data"></div>

### Handlers With and Without Data

Handlers can be sync or async, and can be defined with or without a `data` argument:

``` dart
@override
Map<String, Function> get stateActions => {
  // No data — handler runs as-is
  "reset": () {
    _value = null;
    setState(() {});
  },

  // With data — receives whatever was passed via the `data:` arg
  "set_value": (data) {
    _value = data;
    setState(() {});
  },

  // Async is supported — the framework awaits the handler
  "reload": (data) async {
    _items = await fetchItems();
    setState(() {});
  },
};
```

If you pass `data:` to `stateAction` but your handler takes no arguments, the data is simply ignored.


<div id="using-a-state-actions-instance"></div>

### Using a StateActions Instance

If a widget exposes a typed `StateActions` instance (often via a `stateActions(stateName)` static method), you can call `.action(...)` on it directly instead of the free function. This is cleaner when firing several actions at the same target:

``` dart
// Using the free function
stateAction("reset_avatar", state: UserAvatar.state);
stateAction("update_user_image", state: UserAvatar.state, data: user);

// Using a StateActions instance — equivalent, less repetition
final actions = UserAvatar.stateActions(UserAvatar.state);
actions.action("reset_avatar");
actions.action("update_user_image", data: user);
```

Several built-in widgets (`InputField`, `CollectionView`, `LanguageSwitcher`, the `NyForm*` family) ship typed `StateActions` classes with named methods like `.clear()`, `.setValue(...)`, `.refresh()` — check the widget's docs for what's available.


<div id="pattern-ny-page"></div>

## Pattern: State Managed Pages (NyPage)

Use this when you want to trigger behavior on a full page from elsewhere in your app — for example, refreshing a page when an event fires, or clearing form state from a child widget.

**Step 1:** Scaffold a page.

``` bash
metro make:page my_page
```

This generates a `NyPage` in `lib/resources/pages/`:

``` dart
class MyPage extends NyStatefulWidget {

  static RouteView path = ("/my-page", (_) => MyPage());

  MyPage({super.key}) : super(child: () => _MyPageState());
}

class _MyPageState extends NyPage<MyPage> {

  @override
  get init => () {

  };

  @override
  bool get stateManaged => false;

  @override
  Widget view(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text("My Page")
      ),
      body: SafeArea(
         child: Container(),
      ),
    );
  }
}
```

**Step 2:** Flip `stateManaged` to `true`.

By default, pages do **not** subscribe to state events — this avoids unnecessary listeners on pages that don't need them. To enable state actions on a page, change the getter:

``` dart
@override
bool get stateManaged => true;
```

**Step 3:** Add a `stateActions` map.

``` dart
@override
Map<String, Function> get stateActions => {
  "refresh_data": () async {
    _items = await fetchItems();
    setState(() {});
  },
  "show_toast": (data) {
    showToastSuccess(description: data["message"]);
  },
};
```

**Step 4:** Trigger the action from anywhere using the page's `path`.

``` dart
stateAction("refresh_data", state: MyPage.path);

stateAction("show_toast", state: MyPage.path, data: {
  "message": "Welcome back",
});
```

> Pages key off `MyPage.path`, widgets key off `MyWidget.state`. This is the only difference at the call site.


<div id="pattern-ny-state"></div>

## Pattern: Stateful Widgets (NyState)

Use this for reusable widgets that exist as a single instance per page — a profile card, a header bar, a status indicator. If you only render one instance of the widget at a time, this is the right pattern.

**Step 1:** Scaffold a stateful widget.

``` bash
metro make:stateful_widget profile_card
```

This generates the following in `lib/resources/widgets/`:

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';

class ProfileCard extends StatefulWidget {

  const ProfileCard({super.key});

  @override
  createState() => _ProfileCardState();
}

class _ProfileCardState extends NyState<ProfileCard> {

  @override
  get init => () {

  };

  @override
  Widget view(BuildContext context) {
    return Container();
  }
}
```

The scaffold gives you a working `NyState` widget — but it's **not yet state managed**. To make it respond to state actions, you need to add four things:

1. A `state` key on the widget class — the unique string other parts of your app will target
2. A constructor parameter that receives the state key and forwards it to the state class
3. A constructor on the state class that assigns `stateName`
4. A `stateActions` map defining the handlers

**Step 2:** Convert the scaffold into a state managed widget.

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';

class ProfileCard extends StatefulWidget {

  const ProfileCard({super.key});

  static String get state => "profile_card";

  @override
  createState() => _ProfileCardState(state);
}

class _ProfileCardState extends NyState<ProfileCard> {

  _ProfileCardState(String? state) {
    this.stateName = state;
  }

  @override
  get init => () {
    // stateAction("hello_world", state: ProfileCard.state);
    // ^ call this from anywhere in your app to trigger the handler below
  };

  @override
  Map<String, Function> get stateActions => {
    "hello_world": () {
      print("Hello World");
    },
  };

  @override
  Widget view(BuildContext context) {
    return Container();
  }
}
```

What changed:
- `static String get state => "profile_card";` defines the public state key
- `createState() => _ProfileCardState(state)` passes the key into the state class
- `_ProfileCardState(String? state) { this.stateName = state; }` registers this state instance under that key, so the framework knows which widget to deliver the action to
- `stateActions` declares the named handlers

**Step 3:** Trigger from anywhere.

``` dart
stateAction("hello_world", state: ProfileCard.state);
```

You can add as many handlers as you need, with or without data:

``` dart
@override
Map<String, Function> get stateActions => {
  "refresh": () async {
    _user = await loadUser();
    setState(() {});
  },
  "update_avatar": (User user) {
    _avatarUrl = user.avatarUrl;
    setState(() {});
  },
};
```


<div id="pattern-ny-state-managed"></div>

## Pattern: State Managed Widgets (NyStateManaged)

Use this when you need **multiple independent instances of the same widget** on screen at once — for example, a cart badge in the header and another in a sidebar that should both update independently.

`NyStateManaged` adds an `id` parameter so each instance can be addressed separately. If you only ever render one instance of the widget, prefer `NyState` instead — it's simpler.

**Step 1:** Scaffold a state managed widget.

``` bash
metro make:state_managed_widget cart
```

This generates the following in `lib/resources/widgets/`:

``` dart
class Cart extends NyStateManaged {
  Cart({super.key, super.id})
      : super(baseState: state, child: () => _CartState());

  static const String state = "cart";

  static action(String action, {dynamic data, String? id}) =>
      stateAction(action, data: data, state: state, id: id);
}

class _CartState extends NyState<Cart> {
  @override
  get init => () {
    // initialization logic here
  };

  @override
  Map<String, Function> get stateActions => {
    "my_action": (data) {},
    "clear_data": () {
      // Invoke actions from anywhere in your app
      // Cart.action("my_action", data: "hello world");
      // Cart.action("clear_data");
    },
  };

  @override
  Widget view(BuildContext context) {
    return Container(
      child: Text("My Widget").bodyMedium(),
    );
  }
}
```

**Step 2:** Flesh out the state — load data in `init`, define handlers, and render.

``` dart
class _CartState extends NyState<Cart> {
  String? _cartValue;

  @override
  get init => () async {
    _cartValue = await getCartValue();
  };

  @override
  Map<String, Function> get stateActions => {
    "reload_cart": () async {
      _cartValue = await getCartValue();
      setState(() {});
    },
    "clear_cart": () {
      _cartValue = null;
      setState(() {});
    },
    "set_quantity": (quantity) {
      _cartValue = quantity.toString();
      setState(() {});
    },
  };

  @override
  Widget view(BuildContext context) {
    return Badge(
      child: Icon(Icons.shopping_cart),
      label: Text(_cartValue ?? "0"),
    );
  }
}
```

**Step 3:** Trigger actions using the generated static `action()` helper.

``` dart
Cart.action("reload_cart");
Cart.action("clear_cart");
```

This is equivalent to `stateAction("reload_cart", state: Cart.state)` — the static helper just removes the boilerplate.


<div id="advanced-multiple-isolated-instances"></div>

### Advanced: Multiple Isolated Instances

The reason `NyStateManaged` exists is to support multiple independent instances of the same widget. Each instance gets its own `id`, which produces a namespaced state key.

Render two carts with distinct ids:

``` dart
Column(
  children: [
    Cart(id: "header"),
    Cart(id: "sidebar"),
  ],
)
```

Now you can update either one independently:

``` dart
// Reload only the header cart
Cart.action("reload_cart", id: "header");

// Reload only the sidebar cart
Cart.action("reload_cart", id: "sidebar");

// No id — targets the default unnamed instance
Cart.action("reload_cart");
```

`NyStateManaged` handles the namespacing: `Cart(id: "header")` registers under the key `"cart_header"`, and `Cart.action(..., id: "header")` targets that exact key.


<div id="lifecycle"></div>

## Lifecycle Reference

State managed widgets and pages share two key lifecycle hooks:

1. **`init()`** — called once when the state is first created. Use it to load initial data.

2. **`stateUpdated(data)`** — called whenever a state action is fired against this state. The `data` argument is the full payload (including the action name and the action's data). Override it if you need to react to *every* state action — most of the time, defining handlers in `stateActions` is what you want instead.

**See also:** [NyState](/docs/{{ $version }}/ny-state) for the full set of state helpers and lifecycle methods.
