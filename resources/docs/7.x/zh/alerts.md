# 提示通知

---

<a name="section-1"></a>
- [简介](#introduction "Introduction")
- [基本用法](#basic-usage "Basic Usage")
- [内置样式](#built-in-styles "Built-in Styles")
- [从页面显示提示](#from-pages "Showing Alerts from Pages")
- [从控制器显示提示](#from-controllers "Showing Alerts from Controllers")
- [showToastNotification](#show-toast-notification "showToastNotification")
- [ToastMeta](#toast-meta "ToastMeta")
- [定位](#positioning "Positioning")
- [自定义 Toast 样式](#custom-styles "Custom Toast Styles")
  - [注册样式](#registering-styles "Registering Styles")
  - [创建样式工厂](#creating-a-style-factory "Creating a Style Factory")
- [AlertTab](#alert-tab "AlertTab")
- [示例](#examples "Practical Examples")

<div id="introduction"></div>

## 简介

{{ config('app.name') }} 提供了一个 toast 通知系统，用于向用户显示提示信息。它内置了四种样式 -- **success（成功）**、**warning（警告）**、**info（信息）** 和 **danger（危险）** -- 并通过注册模式支持自定义样式。

提示可以从页面、控制器或任何有 `BuildContext` 的地方触发。

<div id="basic-usage"></div>

## 基本用法

在任何 `NyState` 页面中使用便捷方法显示 toast 通知：

``` dart
// Success toast
showToastSuccess(description: "Item saved successfully");

// Warning toast
showToastWarning(description: "Your session is about to expire");

// Info toast
showToastInfo(description: "New version available");

// Danger toast
showToastDanger(description: "Failed to save item");
```

或使用全局函数并指定样式 ID：

``` dart
showToastNotification(context, id: "success", description: "Item saved!");
```

<div id="built-in-styles"></div>

## 内置样式

{{ config('app.name') }} 注册了四种默认 toast 样式：

| 样式 ID | 图标 | 颜色 | 默认标题 |
|----------|------|-------|---------------|
| `success` | 勾选标记 | 绿色 | Success |
| `warning` | 感叹号 | 橙色 | Warning |
| `info` | 信息图标 | 青色 | Info |
| `danger` | 警告图标 | 红色 | Error |

这些在 `lib/config/toast_notification.dart` 中配置：

``` dart
class ToastNotificationConfig {
  static final Map<String, ToastStyleFactory> styles = {
    'success': ToastNotification.style(
      icon: Icon(Icons.check_circle, color: Colors.green, size: 20),
      color: Colors.green.shade50,
      defaultTitle: 'Success',
    ),
    'warning': ToastNotification.style(
      icon: Icon(Icons.warning_amber_rounded, color: Colors.orange, size: 20),
      color: Colors.orange.shade50,
      defaultTitle: 'Warning',
    ),
    'info': ToastNotification.style(
      icon: Icon(Icons.info_outline, color: Colors.teal, size: 20),
      color: Colors.teal.shade50,
      defaultTitle: 'Info',
    ),
    'danger': ToastNotification.style(
      icon: Icon(Icons.warning_rounded, color: Colors.red, size: 20),
      color: Colors.red.shade50,
      defaultTitle: 'Error',
    ),
  };
}
```

<div id="from-pages"></div>

## 从页面显示提示

在任何继承 `NyState` 或 `NyBaseState` 的页面中，使用以下便捷方法：

``` dart
class _MyPageState extends NyState<MyPage> {

  void onSave() {
    // Success
    showToastSuccess(description: "Saved!");

    // With custom title
    showToastSuccess(title: "Done", description: "Your profile was updated.");

    // Warning
    showToastWarning(description: "Check your input");

    // Info
    showToastInfo(description: "Tip: Swipe left to delete");

    // Danger
    showToastDanger(description: "Something went wrong");

    // Oops (uses danger style)
    showToastOops(description: "That didn't work");

    // Sorry (uses danger style)
    showToastSorry(description: "We couldn't process your request");

    // Custom style by ID
    showToastCustom(id: "custom", description: "Custom alert!");
  }
}
```

### 通用 Toast 方法

``` dart
showToast(
  id: 'success',
  title: 'Hello',
  description: 'Welcome back!',
  duration: Duration(seconds: 3),
);
```

<div id="from-controllers"></div>

## 从控制器显示提示

继承 `NyController` 的控制器具有相同的便捷方法：

``` dart
class ProfileController extends NyController {
  void updateProfile() async {
    try {
      await api.updateProfile();
      showToastSuccess(description: "Profile updated");
    } catch (e) {
      showToastDanger(description: "Failed to update profile");
    }
  }
}
```

可用方法：`showToastSuccess`、`showToastWarning`、`showToastInfo`、`showToastDanger`、`showToastOops`、`showToastSorry`、`showToastCustom`。

<div id="show-toast-notification"></div>

## showToastNotification

全局函数 `showToastNotification()` 可以在任何有 `BuildContext` 的地方显示 toast：

``` dart
showToastNotification(
  context,
  id: 'success',
  title: 'Saved',
  description: 'Your changes have been saved.',
  duration: Duration(seconds: 3),
  position: ToastNotificationPosition.top,
  action: () {
    // Called when the toast is tapped
    routeTo("/details");
  },
  onDismiss: () {
    // Called when the toast is dismissed
  },
  onShow: () {
    // Called when the toast becomes visible
  },
);
```

### 参数

| 参数 | 类型 | 默认值 | 描述 |
|-----------|------|---------|-------------|
| `context` | `BuildContext` | 必填 | 构建上下文 |
| `id` | `String` | `'success'` | Toast 样式 ID |
| `title` | `String?` | null | 覆盖默认标题 |
| `description` | `String?` | null | 描述文本 |
| `duration` | `Duration?` | null | Toast 显示时长 |
| `position` | `ToastNotificationPosition?` | null | 屏幕位置 |
| `action` | `VoidCallback?` | null | 点击回调 |
| `onDismiss` | `VoidCallback?` | null | 关闭回调 |
| `onShow` | `VoidCallback?` | null | 显示回调 |

<div id="toast-meta"></div>

## ToastMeta

`ToastMeta` 封装了 toast 通知的所有数据：

``` dart
ToastMeta(
  title: 'Custom Alert',
  description: 'Something happened.',
  icon: Icon(Icons.star, color: Colors.purple),
  color: Colors.purple.shade50,
  style: 'custom',
  duration: Duration(seconds: 5),
  position: ToastNotificationPosition.top,
  action: () => print("Tapped!"),
  dismiss: () => print("Dismiss pressed"),
  onDismiss: () => print("Toast dismissed"),
  onShow: () => print("Toast shown"),
  metaData: {"key": "value"},
)
```

### 属性

| 属性 | 类型 | 默认值 | 描述 |
|----------|------|---------|-------------|
| `icon` | `Widget?` | null | 图标组件 |
| `title` | `String` | `''` | 标题文本 |
| `style` | `String` | `''` | 样式标识符 |
| `description` | `String` | `''` | 描述文本 |
| `color` | `Color?` | null | 图标区域的背景颜色 |
| `action` | `VoidCallback?` | null | 点击回调 |
| `dismiss` | `VoidCallback?` | null | 关闭按钮回调 |
| `onDismiss` | `VoidCallback?` | null | 自动/手动关闭回调 |
| `onShow` | `VoidCallback?` | null | 可见时回调 |
| `duration` | `Duration` | 5 秒 | 显示时长 |
| `position` | `ToastNotificationPosition` | `top` | 屏幕位置 |
| `metaData` | `Map<String, dynamic>?` | null | 自定义元数据 |

### copyWith

创建 `ToastMeta` 的修改副本：

``` dart
ToastMeta updated = originalMeta.copyWith(
  title: "New Title",
  duration: Duration(seconds: 10),
);
```

<div id="positioning"></div>

## 定位

控制 toast 在屏幕上的显示位置：

``` dart
// Top of screen (default)
showToastNotification(context,
  id: "success",
  description: "Top alert",
  position: ToastNotificationPosition.top,
);

// Bottom of screen
showToastNotification(context,
  id: "info",
  description: "Bottom alert",
  position: ToastNotificationPosition.bottom,
);

// Center of screen
showToastNotification(context,
  id: "warning",
  description: "Center alert",
  position: ToastNotificationPosition.center,
);
```

<div id="custom-styles"></div>

## 自定义 Toast 样式

<div id="registering-styles"></div>

### 注册样式

在 `AppProvider` 中注册自定义样式：

``` dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    await nylo.configure(
      toastNotifications: {
        ...ToastNotificationConfig.styles,
        'custom': ToastNotification.style(
          icon: Icon(Icons.star, color: Colors.purple, size: 20),
          color: Colors.purple.shade50,
          defaultTitle: 'Custom!',
        ),
      },
    );
    return nylo;
  }
}
```

或在任何时候添加样式：

``` dart
nylo.addToastNotifications({
  'promo': ToastNotification.style(
    icon: Icon(Icons.local_offer, color: Colors.pink, size: 20),
    color: Colors.pink.shade50,
    defaultTitle: 'Special Offer',
    position: ToastNotificationPosition.bottom,
    duration: Duration(seconds: 8),
  ),
});
```

然后使用它：

``` dart
showToastNotification(context, id: "promo", description: "50% off today!");
```

<div id="creating-a-style-factory"></div>

### 创建样式工厂

`ToastNotification.style()` 创建一个 `ToastStyleFactory`：

``` dart
static ToastStyleFactory style({
  required Widget icon,
  required Color color,
  required String defaultTitle,
  ToastNotificationPosition? position,
  Duration? duration,
  Widget Function(ToastMeta toastMeta)? builder,
})
```

| 参数 | 类型 | 描述 |
|-----------|------|-------------|
| `icon` | `Widget` | Toast 的图标组件 |
| `color` | `Color` | 图标区域的背景颜色 |
| `defaultTitle` | `String` | 未提供标题时显示的标题 |
| `position` | `ToastNotificationPosition?` | 默认位置 |
| `duration` | `Duration?` | 默认时长 |
| `builder` | `Widget Function(ToastMeta)?` | 自定义组件构建器，用于完全控制 |

### 完全自定义构建器

用于完全控制 toast 组件：

``` dart
'banner': (ToastMeta meta, void Function(ToastMeta) updateMeta) {
  return Container(
    margin: EdgeInsets.symmetric(horizontal: 16, vertical: 8),
    padding: EdgeInsets.all(16),
    decoration: BoxDecoration(
      color: Colors.indigo,
      borderRadius: BorderRadius.circular(12),
    ),
    child: Row(
      children: [
        Icon(Icons.campaign, color: Colors.white),
        SizedBox(width: 12),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            mainAxisSize: MainAxisSize.min,
            children: [
              Text(meta.title, style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
              Text(meta.description, style: TextStyle(color: Colors.white70)),
            ],
          ),
        ),
      ],
    ),
  );
}
```

<div id="alert-tab"></div>

## AlertTab

`AlertTab` 是一个徽章组件，用于为导航标签添加通知指示器。它显示一个可切换的徽章，并可选择将状态持久化到存储中。

``` dart
AlertTab(
  state: "notifications_tab",
  alertEnabled: true,
  icon: Icon(Icons.notifications),
  alertColor: Colors.red,
)
```

### 参数

| 参数 | 类型 | 默认值 | 描述 |
|-----------|------|---------|-------------|
| `state` | `String` | 必填 | 用于追踪的状态名称 |
| `alertEnabled` | `bool?` | null | 是否显示徽章 |
| `rememberAlert` | `bool?` | `true` | 将徽章状态持久化到存储 |
| `icon` | `Widget?` | null | 标签图标 |
| `backgroundColor` | `Color?` | null | 标签背景颜色 |
| `textColor` | `Color?` | null | 徽章文本颜色 |
| `alertColor` | `Color?` | null | 徽章背景颜色 |
| `smallSize` | `double?` | null | 小徽章尺寸 |
| `largeSize` | `double?` | null | 大徽章尺寸 |
| `textStyle` | `TextStyle?` | null | 徽章文本样式 |
| `padding` | `EdgeInsetsGeometry?` | null | 徽章内边距 |
| `alignment` | `Alignment?` | null | 徽章对齐方式 |
| `offset` | `Offset?` | null | 徽章偏移量 |
| `isLabelVisible` | `bool?` | `true` | 显示徽章标签 |

### 工厂构造函数

从 `NavigationTab` 创建：

``` dart
AlertTab.fromNavigationTab(
  myNavigationTab,
  index: 0,
  icon: Icon(Icons.home),
  stateName: "home_alert",
)
```

<div id="examples"></div>

## 示例

### 保存后的成功提示

``` dart
void saveItem() async {
  try {
    await api<ItemApiService>((request) => request.saveItem(item));
    showToastSuccess(description: "Item saved successfully");
  } catch (e) {
    showToastDanger(description: "Could not save item. Please try again.");
  }
}
```

### 带操作的交互式 Toast

``` dart
showToastNotification(
  context,
  id: "info",
  title: "New Message",
  description: "You have a new message from Anthony",
  action: () {
    routeTo(ChatPage.path, data: {"userId": "123"});
  },
  duration: Duration(seconds: 8),
);
```

### 底部定位的警告

``` dart
showToastNotification(
  context,
  id: "warning",
  title: "No Internet",
  description: "You appear to be offline. Some features may not work.",
  position: ToastNotificationPosition.bottom,
  duration: Duration(seconds: 10),
);
```
