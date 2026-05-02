# 状态管理组件

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- [选择您的模式](#choose-your-pattern "选择您的模式")
- [状态动作](#state-actions "状态动作")
  - [定义处理器](#defining-handlers "定义处理器")
  - [触发动作](#triggering-actions "触发动作")
  - [有无数据的处理器](#handlers-with-and-without-data "有无数据的处理器")
  - [使用 StateActions 实例](#using-a-state-actions-instance "使用 StateActions 实例")
- [模式：状态管理页面 (NyPage)](#pattern-ny-page "模式：状态管理页面")
- [模式：有状态组件 (NyState)](#pattern-ny-state "模式：有状态组件")
- [模式：状态管理组件 (NyStateManaged)](#pattern-ny-state-managed "模式：状态管理组件")
  - [高级：多个隔离实例](#advanced-multiple-isolated-instances "高级：多个隔离实例")
- [生命周期参考](#lifecycle "生命周期参考")

<div id="introduction"></div>

## 简介

状态管理组件让您无需重建整个页面，即可从应用的任何位置更新 UI 的特定部分。在 {{ config('app.name') }} v7 中，您可以对目标组件或页面触发命名的**状态动作**，相应的处理器随即执行。

其核心模型很简单：

- 每个状态管理组件或页面都有一个唯一的**状态键**（字符串）
- 它定义了一个已知如何处理的**命名动作**映射
- 在应用的任何位置，您可以调用
```
stateAction("action_name", state: TargetWidget.state)
``` 

构建状态管理 UI 有三种模式。它们都共享相同的 `stateAction` 机制——唯一的区别是您要管理的 UI 类型。


<div id="choose-your-pattern"></div>

## 选择您的模式

| 您想要... | 使用 | 脚手架命令 |
|---|---|---|
| 对整个页面进行状态管理 | `NyPage` | `metro make:page my_page` |
| 对单实例组件进行状态管理 | `NyState` | `metro make:stateful_widget my_widget` |
| 对具有多个隔离实例的组件进行状态管理 | `NyStateManaged` | `metro make:state_managed_widget my_widget` |

请先阅读 [状态动作](#state-actions) 章节——它解释了每种模式使用的 API。然后跳转到适合您场景的模式。


<div id="state-actions"></div>

## 状态动作

状态动作是组件或页面知道如何处理的命名命令。您定义动作名称到处理器函数的映射，并在应用的任何位置按名称触发它们。

在以下情况下使用状态动作：
- 需要在组件或页面上触发特定行为（不仅仅是通用刷新）
- 需要向组件传递数据并让它以定义的方式响应
- 需要构建可从多个调用位置调用的可复用组件行为

<div id="defining-handlers"></div>

### 定义处理器

处理器存在于 `NyState` 或 `NyPage` 类的 `stateActions` getter 中。映射的键是动作名称；值是该动作被触发时运行的函数。

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

如果处理器希望组件重建，则由处理器自身负责调用 `setState`。

上面的 `apply_discount` 处理器接受一个 `code` 参数——当您的处理器需要通过以下方式传递的载荷时，声明单个位置参数
```
stateAction("reload_cart", state: TargetWidget.state);
stateAction("clear_cart", state: TargetWidget.state);
stateAction("apply_discount", state: TargetWidget.state, data: "promo_code_123");
```

当动作不携带载荷时，使用无参数形式 `()`。


<div id="triggering-actions"></div>

### 触发动作

使用顶层 `stateAction` 函数从任何位置触发动作——另一个组件、控制器、事件处理器、API 回调等。

``` dart
// 不带数据触发动作
stateAction("clear_cart", state: Cart.state);

// 带数据触发动作
stateAction("show_toast", state: Cart.state, data: {
  "message": "Item added",
});
```

`state:` 参数是目标的**状态键**：
- 对于组件 — `MyWidget.state`（字符串）
- 对于页面 — `MyPage.path`（路由）


<div id="handlers-with-and-without-data"></div>

### 有无数据的处理器

处理器可以是同步或异步的，可以带或不带 `data` 参数定义：

``` dart
@override
Map<String, Function> get stateActions => {
  // 无数据 — 处理器按原样运行
  "reset": () {
    _value = null;
    setState(() {});
  },

  // 带数据 — 接收通过 `data:` 参数传递的内容
  "set_value": (data) {
    _value = data;
    setState(() {});
  },

  // 支持异步 — 框架会 await 处理器
  "reload": (data) async {
    _items = await fetchItems();
    setState(() {});
  },
};
```

如果您向 `stateAction` 传递了 `data:`，但您的处理器不接受参数，则数据会被简单忽略。


<div id="using-a-state-actions-instance"></div>

### 使用 StateActions 实例

如果组件公开了一个类型化的 `StateActions` 实例（通常通过 `stateActions(stateName)` 静态方法），您可以直接在其上调用 `.action(...)`，而不是使用自由函数。当向同一目标触发多个动作时，这种方式更简洁：

``` dart
// 使用自由函数
stateAction("reset_avatar", state: UserAvatar.state);
stateAction("update_user_image", state: UserAvatar.state, data: user);

// 使用 StateActions 实例 — 等价，减少重复
final actions = UserAvatar.stateActions(UserAvatar.state);
actions.action("reset_avatar");
actions.action("update_user_image", data: user);
```

几个内置组件（`InputField`、`CollectionView`、`LanguageSwitcher`、`NyForm*` 系列）附带类型化的 `StateActions` 类，其中包含 `.clear()`、`.setValue(...)`、`.refresh()` 等命名方法——请查阅各组件文档了解可用内容。


<div id="pattern-ny-page"></div>

## 模式：状态管理页面 (NyPage)

当您希望从应用的其他位置对整个页面触发行为时使用——例如，在事件触发时刷新页面，或从子组件清除表单状态。

**第 1 步：** 生成一个页面脚手架。

``` bash
metro make:page my_page
```

这会在 `lib/resources/pages/` 中生成一个 `NyPage`：

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

**第 2 步：** 将 `stateManaged` 切换为 `true`。

默认情况下，页面**不**订阅状态事件——这避免了在不需要的页面上产生不必要的监听器。要在页面上启用状态动作，请更改 getter：

``` dart
@override
bool get stateManaged => true;
```

**第 3 步：** 添加 `stateActions` 映射。

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

**第 4 步：** 使用页面的 `path` 从任何位置触发动作。

``` dart
stateAction("refresh_data", state: MyPage.path);

stateAction("show_toast", state: MyPage.path, data: {
  "message": "Welcome back",
});
```

> 页面以 `MyPage.path` 为键，组件以 `MyWidget.state` 为键。这是调用端的唯一区别。


<div id="pattern-ny-state"></div>

## 模式：有状态组件 (NyState)

用于每个页面以单个实例存在的可复用组件——个人资料卡、标题栏、状态指示器。如果您一次只渲染一个组件实例，这是正确的模式。

**第 1 步：** 生成一个有状态组件脚手架。

``` bash
metro make:stateful_widget profile_card
```

这会在 `lib/resources/widgets/` 中生成以下内容：

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

脚手架为您提供了一个可工作的 `NyState` 组件——但它**尚未进行状态管理**。要让它响应状态动作，您需要添加四件事：

1. 组件类上的 `state` 键——应用其他部分将以此为目标的唯一字符串
2. 接收状态键并将其转发给状态类的构造函数参数
3. 分配 `stateName` 的状态类构造函数
4. 定义处理器的 `stateActions` 映射

**第 2 步：** 将脚手架转换为状态管理组件。

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
    // ^ 从应用的任何位置调用此方法以触发下面的处理器
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

变更说明：
- `static String get state => "profile_card";` 定义公共状态键
- `createState() => _ProfileCardState(state)` 将键传入状态类
- `_ProfileCardState(String? state) { this.stateName = state; }` 在该键下注册此状态实例，使框架知道将动作投递给哪个组件
- `stateActions` 声明命名处理器

**第 3 步：** 从任何位置触发。

``` dart
stateAction("hello_world", state: ProfileCard.state);
```

您可以根据需要添加任意数量的处理器，带或不带数据：

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

## 模式：状态管理组件 (NyStateManaged)

当您需要同时在屏幕上显示**同一组件的多个独立实例**时使用——例如，标题中的购物车徽章和侧边栏中的另一个徽章，它们应当独立更新。

`NyStateManaged` 添加了一个 `stateName` 参数，使每个实例可以单独寻址。如果您只渲染组件的一个实例，请优先使用更简单的 `NyState`。

**第 1 步：** 生成一个状态管理组件脚手架。

``` bash
metro make:state_managed_widget cart
```

这会在 `lib/resources/widgets/` 中生成以下内容：

``` dart
class Cart extends NyStateManaged {
  Cart({super.key, super.stateName})
      : super(child: () => _CartState(stateName));

  static String state = "cart";

  static String _stateFor(String? state) =>
      state == null ? Cart.state : "${Cart.state}_$state";

  static action(String action, {dynamic data, String? stateName}) {
    return stateAction(action, data: data, state: _stateFor(stateName));
  }
}

class _CartState extends NyState<Cart> {
  _CartState(String? stateName) {
    this.stateName = Cart._stateFor(stateName);
  }

  @override
  get init => () {
    // 初始化逻辑在此处
  };

  @override
  Map<String, Function> get stateActions => {
    "my_action": (data) {},
    "clear_data": () {
      // 从应用的任何位置调用动作
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

**第 2 步：** 充实状态——在 `init` 中加载数据，定义处理器，并进行渲染。

``` dart
class _CartState extends NyState<Cart> {
  String? _cartValue;

  _CartState(String? stateName) {
    this.stateName = Cart._stateFor(stateName);
  }

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

**第 3 步：** 使用生成的静态 `action()` 辅助方法触发动作。

``` dart
Cart.action("reload_cart");
Cart.action("clear_cart");
```

这等价于 `stateAction("reload_cart", state: Cart.state)`——静态辅助方法只是去除了样板代码。


<div id="advanced-multiple-isolated-instances"></div>

### 高级：多个隔离实例

`NyStateManaged` 存在的原因是支持同一组件的多个独立实例。每个实例获得自己的 `stateName`，从而生成命名空间化的状态键。

渲染两个具有不同名称的购物车：

``` dart
Column(
  children: [
    Cart(stateName: "header"),
    Cart(stateName: "sidebar"),
  ],
)
```

现在您可以独立更新其中任意一个：

``` dart
// 仅重新加载标题购物车
Cart.action("reload_cart", stateName: "header");

// 仅重新加载侧边栏购物车
Cart.action("reload_cart", stateName: "sidebar");

// 无 stateName — 以默认无名实例为目标
Cart.action("reload_cart");
```

`_stateFor` 辅助方法处理命名空间：`Cart(stateName: "header")` 在键 `"cart_header"` 下注册，而 `Cart.action(..., stateName: "header")` 以该确切键为目标。


<div id="lifecycle"></div>

## 生命周期参考

状态管理组件和页面共享两个关键生命周期钩子：

1. **`init()`** — 在状态首次创建时调用一次。用于加载初始数据。

2. **`stateUpdated(data)`** — 每当针对此状态触发状态动作时调用。`data` 参数是完整载荷（包括动作名称和动作数据）。如果您需要响应*每一个*状态动作，请覆盖它——但大多数情况下，在 `stateActions` 中定义处理器才是您真正需要的。

**另请参阅：** [NyState](/docs/{{ $version }}/ny-state) 获取状态辅助方法和生命周期方法的完整集合。
