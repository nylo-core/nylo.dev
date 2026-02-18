# 模态框

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- [创建模态框](#creating-a-modal "创建模态框")
- [基本用法](#basic-usage "基本用法")
- [创建模态框](#creating-a-modal "创建模态框")
- [BottomSheetModal](#bottom-sheet-modal "BottomSheetModal")
  - [参数](#parameters "参数")
  - [操作按钮](#actions "操作按钮")
  - [头部](#header "头部")
  - [关闭按钮](#close-button "关闭按钮")
  - [自定义装饰](#custom-decoration "自定义装饰")
- [BottomModalSheetStyle](#bottom-modal-sheet-style "BottomModalSheetStyle")
- [示例](#examples "示例")

<div id="introduction"></div>

## 简介

{{ config('app.name') }} 提供了一个基于**底部弹出模态框**构建的模态框系统。

`BottomSheetModal` 类提供了灵活的 API，用于显示带有操作按钮、头部、关闭按钮和自定义样式的内容覆盖层。

模态框适用于：
- 确认对话框（例如登出、删除）
- 快速输入表单
- 带有多个选项的操作列表
- 信息展示覆盖层

<div id="creating-a-modal"></div>

## 创建模态框

您可以使用 Metro CLI 创建新的模态框：

``` bash
metro make:bottom_sheet_modal payment_options
```

这会生成两样东西：

1. **模态框内容组件**，位于 `lib/resources/widgets/bottom_sheet_modals/modals/payment_options_modal.dart`：

``` dart
import 'package:flutter/material.dart';

/// Payment Options Modal
///
/// Used in BottomSheetModal.showPaymentOptions()
class PaymentOptionsModal extends StatelessWidget {
  const PaymentOptionsModal({super.key});

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Text('PaymentOptionsModal').headingLarge(),
      ],
    );
  }
}
```

2. **一个静态方法**，添加到 `lib/resources/widgets/bottom_sheet_modals/bottom_sheet_modals.dart` 中的 `BottomSheetModal` 类：

``` dart
/// Show Payment Options modal
static Future<void> showPaymentOptions(BuildContext context) {
  return displayModal(
    context,
    isScrollControlled: false,
    child: const PaymentOptionsModal(),
  );
}
```

然后你可以从任何地方显示模态框：

``` dart
BottomSheetModal.showPaymentOptions(context);
```

如果同名的模态框已存在，使用 `--force` 标志覆盖它：

``` bash
metro make:bottom_sheet_modal payment_options --force
```

<div id="basic-usage"></div>

## 基本用法

使用 `BottomSheetModal` 显示模态框：

``` dart
BottomSheetModal.showLogout(context);
```

<div id="creating-a-modal"></div>

## 创建模态框

推荐的模式是创建一个 `BottomSheetModal` 类，为每种模态框类型提供静态方法。项目模板提供了这种结构：

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';

class BottomSheetModal extends NyBaseModal {
  static ModalShowFunction get displayModal => displayModal;

  /// Show Logout modal
  static Future<void> showLogout(
    BuildContext context, {
    Function()? onLogoutPressed,
    Function()? onCancelPressed,
  }) {
    return displayModal(
      context,
      isScrollControlled: false,
      child: const LogoutModal(),
      actionsRow: [
        Button.secondary(
          text: "Logout",
          onPressed: onLogoutPressed ?? () => routeToInitial(),
        ),
        Button(
          text: "Cancel",
          onPressed: onCancelPressed ?? () => Navigator.pop(context),
        ),
      ],
    );
  }
}
```

从任何地方调用它：

``` dart
BottomSheetModal.showLogout(context);

// With custom callbacks
BottomSheetModal.showLogout(
  context,
  onLogoutPressed: () {
    // Custom logout logic
  },
  onCancelPressed: () {
    Navigator.pop(context);
  },
);
```

<div id="bottom-sheet-modal"></div>

## BottomSheetModal

`displayModal<T>()` 是显示模态框的核心方法。

<div id="parameters"></div>

### 参数

| 参数 | 类型 | 默认值 | 描述 |
|-----------|------|---------|-------------|
| `context` | `BuildContext` | 必填 | 模态框的构建上下文 |
| `child` | `Widget` | 必填 | 主内容组件 |
| `actionsRow` | `List<Widget>` | `[]` | 水平排列的操作组件 |
| `actionsColumn` | `List<Widget>` | `[]` | 垂直排列的操作组件 |
| `height` | `double?` | null | 模态框的固定高度 |
| `header` | `Widget?` | null | 顶部的头部组件 |
| `useSafeArea` | `bool` | `true` | 使用 SafeArea 包裹内容 |
| `isScrollControlled` | `bool` | `false` | 允许模态框可滚动 |
| `showCloseButton` | `bool` | `false` | 显示 X 关闭按钮 |
| `headerPadding` | `EdgeInsets?` | null | 头部存在时的内边距 |
| `backgroundColor` | `Color?` | null | 模态框背景颜色 |
| `showHandle` | `bool` | `true` | 显示顶部拖拽手柄 |
| `closeButtonColor` | `Color?` | null | 关闭按钮背景颜色 |
| `closeButtonIconColor` | `Color?` | null | 关闭按钮图标颜色 |
| `modalDecoration` | `BoxDecoration?` | null | 模态框容器的自定义装饰 |
| `handleColor` | `Color?` | null | 拖拽手柄的颜色 |

<div id="actions"></div>

### 操作按钮

操作按钮显示在模态框底部。

**行操作**并排放置，每个按钮占据相等的空间：

``` dart
displayModal(
  context,
  child: Text("Are you sure?"),
  actionsRow: [
    ElevatedButton(
      onPressed: () => Navigator.pop(context, true),
      child: Text("Yes"),
    ),
    TextButton(
      onPressed: () => Navigator.pop(context, false),
      child: Text("No"),
    ),
  ],
);
```

**列操作**垂直堆叠：

``` dart
displayModal(
  context,
  child: Text("Choose an option"),
  actionsColumn: [
    ElevatedButton(
      onPressed: () => Navigator.pop(context, true),
      child: Text("Yes"),
    ),
    TextButton(
      onPressed: () => Navigator.pop(context, false),
      child: Text("No"),
    ),
  ],
);
```

<div id="header"></div>

### 头部

添加位于主内容上方的头部：

``` dart
displayModal(
  context,
  header: Container(
    padding: EdgeInsets.all(16),
    color: Colors.blue,
    child: Text(
      "Modal Title",
      style: TextStyle(color: Colors.white, fontSize: 18),
    ),
  ),
  child: Text("Modal content goes here"),
);
```

<div id="close-button"></div>

### 关闭按钮

在右上角显示关闭按钮：

``` dart
displayModal(
  context,
  showCloseButton: true,
  closeButtonColor: Colors.grey.shade200,
  closeButtonIconColor: Colors.black,
  child: Padding(
    padding: EdgeInsets.all(24),
    child: Text("Content with close button"),
  ),
);
```

<div id="custom-decoration"></div>

### 自定义装饰

自定义模态框容器的外观：

``` dart
displayModal(
  context,
  backgroundColor: Colors.transparent,
  modalDecoration: BoxDecoration(
    color: Colors.white,
    borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
    boxShadow: [
      BoxShadow(color: Colors.black26, blurRadius: 10),
    ],
  ),
  handleColor: Colors.grey.shade400,
  child: Text("Custom styled modal"),
);
```

<div id="bottom-modal-sheet-style"></div>

## BottomModalSheetStyle

`BottomModalSheetStyle` 配置表单选择器和其他组件使用的底部弹出模态框外观：

``` dart
BottomModalSheetStyle(
  backgroundColor: NyColor(light: Colors.white, dark: Colors.grey.shade900),
  barrierColor: NyColor(light: Colors.black54, dark: Colors.black87),
  useRootNavigator: false,
  titleStyle: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
  itemStyle: TextStyle(fontSize: 16),
  clearButtonStyle: TextStyle(color: Colors.red),
)
```

| 属性 | 类型 | 描述 |
|----------|------|-------------|
| `backgroundColor` | `NyColor?` | 模态框的背景颜色 |
| `barrierColor` | `NyColor?` | 模态框后方遮罩的颜色 |
| `useRootNavigator` | `bool` | 使用根导航器（默认：`false`） |
| `routeSettings` | `RouteSettings?` | 模态框的路由设置 |
| `titleStyle` | `TextStyle?` | 标题文本样式 |
| `itemStyle` | `TextStyle?` | 列表项文本样式 |
| `clearButtonStyle` | `TextStyle?` | 清除按钮文本样式 |

<div id="examples"></div>

## 示例

### 确认模态框

``` dart
static Future<bool?> showConfirm(
  BuildContext context, {
  required String message,
}) {
  return displayModal<bool>(
    context,
    child: Padding(
      padding: EdgeInsets.symmetric(vertical: 16),
      child: Text(message, textAlign: TextAlign.center),
    ),
    actionsRow: [
      ElevatedButton(
        onPressed: () => Navigator.pop(context, true),
        child: Text("Confirm"),
      ),
      TextButton(
        onPressed: () => Navigator.pop(context, false),
        child: Text("Cancel"),
      ),
    ],
  );
}

// Usage
bool? confirmed = await BottomSheetModal.showConfirm(
  context,
  message: "Delete this item?",
);
if (confirmed == true) {
  // delete the item
}
```

### 可滚动内容模态框

``` dart
displayModal(
  context,
  isScrollControlled: true,
  height: MediaQuery.of(context).size.height * 0.8,
  showCloseButton: true,
  header: Padding(
    padding: EdgeInsets.all(16),
    child: Text("Terms of Service", style: TextStyle(fontSize: 20)),
  ),
  child: SingleChildScrollView(
    child: Text(longTermsText),
  ),
);
```

### 操作列表

``` dart
displayModal(
  context,
  showHandle: true,
  child: Text("Share via", style: TextStyle(fontSize: 18)),
  actionsColumn: [
    ListTile(
      leading: Icon(Icons.email),
      title: Text("Email"),
      onTap: () {
        Navigator.pop(context);
        shareViaEmail();
      },
    ),
    ListTile(
      leading: Icon(Icons.message),
      title: Text("Message"),
      onTap: () {
        Navigator.pop(context);
        shareViaMessage();
      },
    ),
    ListTile(
      leading: Icon(Icons.copy),
      title: Text("Copy Link"),
      onTap: () {
        Navigator.pop(context);
        copyLink();
      },
    ),
  ],
);
```
