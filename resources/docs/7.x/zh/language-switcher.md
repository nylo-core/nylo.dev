# LanguageSwitcher

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- 使用方法
    - [下拉组件](#usage-dropdown "下拉组件")
    - [底部弹窗](#usage-bottom-modal "底部弹窗")
- [自定义下拉构建器](#custom-builder "自定义下拉构建器")
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
        LanguageSwitcher(), // Add to the app bar
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

### 自定义弹窗高度

``` dart
LanguageSwitcher.showBottomModal(
  context,
  height: 300, // Custom height
);
```

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
        Text(language['name']), // e.g., "English"
        // language['locale'] contains the locale code, e.g., "en"
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
    // Perform additional actions when language changes
  },
)
```

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
// Returns: {"en": "English"} or null if not set
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
// Returns: {"en": "English"}

Map<String, String>? langData = LanguageSwitcher.getLanguageData("fr_CA");
// Returns: {"fr_CA": "French (Canada)"}
```

### 获取语言列表

获取 `/lang` 目录中所有可用的语言：

``` dart
List<Map<String, String>> languages = await LanguageSwitcher.getLanguageList();
// Returns: [{"en": "English"}, {"es": "Spanish"}, ...]
```

### 显示底部弹窗

显示语言选择弹窗：

``` dart
await LanguageSwitcher.showBottomModal(context);

// With custom height
await LanguageSwitcher.showBottomModal(context, height: 400);
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
