# 状态管理

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- [何时使用状态管理](#when-to-use-state-management "何时使用状态管理")
- [生命周期](#lifecycle "生命周期")
- [状态动作](#state-actions "状态动作")
  - [NyState - 状态动作](#state-actions-nystate "NyState - 状态动作")
  - [NyPage - 状态动作](#state-actions-nypage "NyPage - 状态动作")
- [更新状态](#updating-a-state "更新状态")
- [构建您的第一个组件](#building-your-first-widget "构建您的第一个组件")

<div id="introduction"></div>

## 简介

状态管理让您可以更新 UI 的特定部分，而无需重建整个页面。在 {{ config('app.name') }} v7 中，您可以构建在整个应用中相互通信和更新的组件。

{{ config('app.name') }} 提供两个用于状态管理的类：
- **`NyState`** — 用于构建可重用的组件（如购物车徽章、通知计数器或状态指示器）
- **`NyPage`** — 用于构建应用中的页面（扩展 `NyState` 并添加页面特定功能）

在以下情况下使用状态管理：
- 需要从应用的不同部分更新组件
- 需要保持组件与共享数据同步
- 需要避免在只有部分 UI 变化时重建整个页面


### 首先了解状态管理

Flutter 中的一切都是组件，它们只是可以组合成完整应用的 UI 小块。

当您开始构建复杂页面时，需要管理组件的状态。这意味着当某些东西发生变化时（例如数据），您可以更新该组件而无需重建整个页面。

这很重要的原因有很多，但主要原因是性能。如果您有一个不断变化的组件，您不希望每次变化时都重建整个页面。

这就是状态管理的用武之地，它允许您管理应用中组件的状态。


<div id="when-to-use-state-management"></div>

### 何时使用状态管理

当您有一个需要更新而不重建整个页面的组件时，应使用状态管理。

例如，假设您创建了一个电商应用。您构建了一个组件来显示用户购物车中的商品总数。
我们称这个组件为 `Cart()`。

在 Nylo 中，一个状态管理的 `Cart` 组件如下所示：

**步骤 1：** 定义带有静态状态名称的组件

``` dart
/// The Cart widget
class Cart extends StatefulWidget {

  Cart({Key? key}) : super(key: key);

  static String state = "cart"; // Unique identifier for this widget's state

  @override
  _CartState createState() => _CartState();
}
```

**步骤 2：** 创建扩展 `NyState` 的状态类

``` dart
/// The state class for the Cart widget
class _CartState extends NyState<Cart> {

  String? _cartValue;

  _CartState() {
    stateName = Cart.state; // Register the state name
  }

  @override
  get init => () async {
    _cartValue = await getCartValue(); // Load initial data
  };

  @override
  void stateUpdated(data) {
    reboot(); // Reload the widget when state updates
  }

  @override
  Widget view(BuildContext context) {
    return Badge(
      child: Icon(Icons.shopping_cart),
      label: Text(_cartValue ?? "1"),
    );
  }
}
```

**步骤 3：** 创建用于读取和更新购物车的辅助函数

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

让我们分解一下。

1. `Cart` 组件是一个 `StatefulWidget`。

2. `_CartState` 扩展了 `NyState<Cart>`。

3. 您需要为 `state` 定义一个名称，用于标识状态。

4. `boot()` 方法在组件首次加载时调用。

5. `stateUpdate()` 方法处理状态更新时发生的事情。

如果您想在 {{ config('app.name') }} 项目中尝试此示例，请创建一个名为 `Cart` 的新组件。

``` bash
metro make:state_managed_widget cart
```

然后您可以复制上面的示例并在项目中尝试。

现在，要更新购物车，您可以调用以下方法。

```dart
_updateCart() async {
  String count = await getCartValue();
  String countIncremented = (int.parse(count) + 1).toString();

  await storageSave(Keys.cart, countIncremented);

  updateState(Cart.state);
}
```


<div id="lifecycle"></div>

## 生命周期

`NyState` 组件的生命周期如下：

1. `init()` - 在状态初始化时调用此方法。

2. `stateUpdated(data)` - 在状态更新时调用此方法。

    如果您调用 `updateState(MyStateName.state, data: "The Data")`，它将触发 **stateUpdated(data)** 被调用。

状态首次初始化后，您需要实现如何管理状态。


<div id="state-actions"></div>

## 状态动作

状态动作让您可以从应用的任何位置触发组件上的特定方法。将它们视为可以发送给组件的命名命令。

在以下情况下使用状态动作：
- 需要在组件上触发特定行为（不仅仅是刷新它）
- 需要向组件传递数据并让它以特定方式响应
- 需要创建可从多个位置调用的可重用组件行为

``` dart
// Sending an action to the widget
stateAction('hello_world_in_widget', state: MyWidget.state);

// Another example with data
stateAction('show_high_score', state: HighScore.state, data: {
  "high_score": 100,
});
```

在您的组件中，您可以定义要处理的动作。

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

然后，您可以从应用的任何位置调用 `stateAction` 方法。

``` dart
stateAction('hello_world_in_widget', state: MyWidget.state);
// prints 'Hello world'

User user = User(name: "John Doe", age: 30);
stateAction('update_user_info', state: MyWidget.state, data: user);
```

您也可以在 `init` getter 中使用 `whenStateAction` 方法定义状态动作。

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

### NyState - 状态动作

首先，创建一个有状态组件。

``` bash
metro make:stateful_widget [widget_name]
```
示例：metro make:stateful_widget user_avatar

这将在 `lib/resources/widgets/` 目录中创建一个新组件。

如果您打开该文件，就可以定义状态动作。

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

最后，您可以从应用的任何位置发送动作。

``` dart
stateAction('reset_avatar', state: MyWidget.state);
// prints 'Hello from the widget'

stateAction('reset_data', state: MyWidget.state);
// Reset data in widget

stateAction('show_toast', state: MyWidget.state, data: "Hello world");
// shows a success toast with the message
```


<div id="state-actions-nypage"></div>

### NyPage - 状态动作

页面也可以接收状态动作。当您想要从组件或其他页面触发页面级别的行为时，这非常有用。

首先，创建状态管理页面。

``` bash
metro make:page my_page
```

这将在 `lib/resources/pages/` 目录中创建一个名为 `MyPage` 的新状态管理页面。

如果您打开该文件，就可以定义状态动作。

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
  "show_toast": (data) {
    showSuccessToast(description: data['message']);
  },
};
```

最后，您可以从应用的任何位置发送动作。

``` dart
stateAction('test_page_action', state: MyPage.state);
// prints 'Hello from the page'

stateAction('reset_data', state: MyPage.state);
// Reset data in page

stateAction('show_toast', state: MyPage.state, data: {
  "message": "Hello from the page"
});
// shows a success toast with the message
```

您也可以使用 `whenStateAction` 方法定义状态动作。

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

然后您可以从应用的任何位置发送动作。

``` dart
stateAction('reset_badge', state: MyWidget.state);
```


<div id="updating-a-state"></div>

## 更新状态

您可以通过调用 `updateState()` 方法来更新状态。

``` dart
updateState(MyStateName.state);

// or with data
updateState(MyStateName.state, data: "The Data");
```

这可以在应用的任何位置调用。

**另请参阅：** [NyState](/docs/{{ $version }}/ny-state) 获取有关状态管理辅助方法和生命周期方法的更多详细信息。


<div id="building-your-first-widget"></div>

## 构建您的第一个组件

在您的 Nylo 项目中，运行以下命令创建一个新组件。

``` bash
metro make:stateful_widget todo_list
```

这将创建一个名为 `TodoList` 的新 `NyState` 组件。

> 注意：新组件将在 `lib/resources/widgets/` 目录中创建。
