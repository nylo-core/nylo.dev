# LanguageSwitcher

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- 使用方法
    - [下拉组件](#usage-dropdown "下拉组件")
    - [底部弹窗](#usage-bottom-modal "底部弹窗")
- [动画样式](#animation-style "动画样式")
- [自定义下拉构建器](#custom-builder "自定义下拉构建器")
- [状态操作](#state-actions "状态操作")
- [参数](#parameters "参数")
- [静态方法](#methods "静态方法")


<div id="introduction"></div>

## 简介

**LanguageSwitcher** 组件提供了在您的 {{ config('app.name') }} 项目中处理语言切换的简便方式。它自动检测 `/lang` 目录中可用的语言并将其显示给用户。

**LanguageSwitcher 的功能：**

- 显示 `/lang` 目录中的可用语言
- 用户选择时切换应用语言
- 在应用重启之间持久化选定的语言
- 语言更改时自动更新 UI

> **注意**：如果您的应用尚未本地化，请在使用此组件之前先查看[本地化](/docs/7.x/localization)文档了解如何操作。

<div id="usage-dropdown"></div>

## 下拉组件

使用 `LanguageSwitcher` 最简单的方式是作为应用栏中的下拉菜单：

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    appBar: AppBar(
      title: Text("Settings"),
      actions: [
        LanguageSwitcher(), // 添加到应用栏
      ],
    ),
    body: Center(
      child: Text("Hello World".tr()),
    ),
  );
}
```

当用户点击下拉菜单时，他们将看到可用语言列表。选择语言后，应用将自动切换并更新 UI。

<div id="usage-bottom-modal"></div>

## 底部弹窗

您还可以在底部弹窗中显示语言：

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: Center(
      child: MaterialButton(
        child: Text("Change Language"),
        onPressed: () {
          LanguageSwitcher.showBottomModal(context);
        },
      ),
    ),
  );
}
```

底部弹窗显示语言列表，当前选定的语言旁边有一个勾选标记。

### 自定义弹窗

``` dart
LanguageSwitcher.showBottomModal(
  context,
  height: 300,
  useRootNavigator: true, // 在所有路由上方显示弹窗，包括标签栏
  onLanguageChange: (String languageKey) {
    print('Language changed to: $languageKey');
  },
);
```

<div id="animation-style"></div>

## 动画样式

`animationStyle` 参数控制下拉触发器和底部弹窗列表项的过渡动画。提供四种预设：

``` dart
// 无动画
LanguageSwitcher(
  animationStyle: LanguageSwitcherAnimationStyle.none(),
)

// 精致细腻的动画（推荐用于大多数应用）
LanguageSwitcher(
  animationStyle: LanguageSwitcherAnimationStyle.subtle(),
)

// 活泼弹性的动画
LanguageSwitcher(
  animationStyle: LanguageSwitcherAnimationStyle.bouncy(),
)

// 带有轻缓缩放的平滑淡入
LanguageSwitcher(
  animationStyle: LanguageSwitcherAnimationStyle.fadeIn(),
)
```

您也可以传入带有独立参数的自定义 `LanguageSwitcherAnimationStyle()`，或使用 `copyWith` 微调预设。

`LanguageSwitcher.showBottomModal` 也接受相同的 `animationStyle` 参数。

<div id="custom-builder"></div>

## 自定义下拉构建器

自定义下拉菜单中每个语言选项的显示方式：

``` dart
LanguageSwitcher(
  dropdownBuilder: (Map<String, dynamic> language) {
    return Row(
      children: [
        Icon(Icons.language),
        SizedBox(width: 8),
        Text(language['name']), // 例如: "English"
        // language['locale'] 包含区域代码，例如 "en"
      ],
    );
  },
)
```

### 处理语言更改

``` dart
LanguageSwitcher(
  onLanguageChange: (Map<String, dynamic> language) {
    print('Language changed to: ${language['name']}');
    // 语言更改时执行其他操作
  },
)
```

<div id="state-actions"></div>

## 状态操作

使用 `stateActions()` 以编程方式控制 `LanguageSwitcher`：

``` dart
// 刷新语言列表（重新读取可用语言）
LanguageSwitcher.stateActions().refresh();

// 通过区域代码切换语言
LanguageSwitcher.stateActions().setLanguage("es");
LanguageSwitcher.stateActions().setLanguage("fr");
```

当您希望不经用户交互就更改应用语言时（例如使用用户偏好设置登录后），此功能非常有用。

<div id="parameters"></div>

## 参数

| 参数 | 类型 | 默认值 | 描述 |
|-----------|------|---------|-------------|
| `icon` | `Widget?` | - | 下拉按钮的自定义图标 |
| `iconEnabledColor` | `Color?` | - | 下拉图标的颜色 |
| `iconSize` | `double` | `24` | 下拉图标的大小 |
| `dropdownBgColor` | `Color?` | - | 下拉菜单的背景颜色 |
| `hint` | `Widget?` | - | 未选择语言时的提示组件 |
| `itemHeight` | `double` | `kMinInteractiveDimension` | 每个下拉项的高度 |
| `elevation` | `int` | `8` | 下拉菜单的阴影 |
| `padding` | `EdgeInsetsGeometry?` | - | 下拉菜单的内边距 |
| `borderRadius` | `BorderRadius?` | - | 下拉菜单的边框圆角 |
| `textStyle` | `TextStyle?` | - | 下拉项的文本样式 |
| `langPath` | `String` | `'lang'` | 资源中语言文件的路径 |
| `dropdownBuilder` | `Function(Map<String, dynamic>)?` | - | 下拉项的自定义构建器 |
| `dropdownAlignment` | `AlignmentGeometry` | `AlignmentDirectional.centerStart` | 下拉项的对齐方式 |
| `dropdownOnTap` | `Function()?` | - | 下拉项被点击时的回调 |
| `onTap` | `Function()?` | - | 下拉按钮被点击时的回调 |
| `onLanguageChange` | `Function(Map<String, dynamic>)?` | - | 语言更改时的回调 |

<div id="methods"></div>

## 静态方法

### 获取当前语言

获取当前选定的语言：

``` dart
Map<String, dynamic>? lang = await LanguageSwitcher.currentLanguage();
// 返回：{"en": "English"} 或未设置时返回 null
```

### 存储语言

手动存储语言偏好：

``` dart
await LanguageSwitcher.storeLanguage(
  object: {"fr": "French"},
);
```

### 清除语言

移除已存储的语言偏好：

``` dart
await LanguageSwitcher.clearLanguage();
```

### 获取语言数据

从区域代码获取语言信息：

``` dart
Map<String, String>? langData = LanguageSwitcher.getLanguageData("en");
// 返回：{"en": "English"}

Map<String, String>? langData = LanguageSwitcher.getLanguageData("fr_CA");
// 返回：{"fr_CA": "French (Canada)"}
```

### 获取语言列表

获取 `/lang` 目录中所有可用的语言：

``` dart
List<Map<String, String>> languages = await LanguageSwitcher.getLanguageList();
// 返回：[{"en": "English"}, {"es": "Spanish"}, ...]
```

### 显示底部弹窗

显示语言选择弹窗：

``` dart
await LanguageSwitcher.showBottomModal(context);

// 带选项
await LanguageSwitcher.showBottomModal(
  context,
  height: 400,
  useRootNavigator: true,
  onLanguageChange: (String languageKey) {
    print('Language changed to: $languageKey');
  },
  animationStyle: LanguageSwitcherAnimationStyle.subtle(),
);
```

## 支持的区域设置

`LanguageSwitcher` 组件支持数百个带有人类可读名称的区域代码。部分示例：

| 区域代码 | 语言名称 |
|-------------|---------------|
| `en` | English |
| `en_US` | English (United States) |
| `en_GB` | English (United Kingdom) |
| `es` | Spanish |
| `fr` | French |
| `de` | German |
| `zh` | Chinese |
| `zh_Hans` | Chinese (Simplified) |
| `ja` | Japanese |
| `ko` | Korean |
| `ar` | Arabic |
| `hi` | Hindi |
| `pt` | Portuguese |
| `ru` | Russian |

完整列表包含大多数语言的区域变体。
