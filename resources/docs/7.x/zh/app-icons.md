# 应用图标

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- [生成应用图标](#generating-app-icons "生成应用图标")
- [添加您的应用图标](#adding-your-app-icon "添加您的应用图标")
- [应用图标要求](#app-icon-requirements "应用图标要求")
- [配置](#configuration "配置")
- [角标计数](#badge-count "角标计数")

<div id="introduction"></div>

## 简介

{{ config('app.name') }} v7 使用 <a href="https://pub.dev/packages/flutter_launcher_icons" target="_BLANK">flutter_launcher_icons</a> 从单个源图像为 iOS 和 Android 生成应用图标。

您的应用图标应放置在 `assets/app_icon/` 目录中，尺寸为 **1024x1024 像素**。

<div id="generating-app-icons"></div>

## 生成应用图标

运行以下命令为所有平台生成图标：

``` bash
dart run flutter_launcher_icons
```

此命令会从 `assets/app_icon/` 读取您的源图标并生成：
- iOS 图标位于 `ios/Runner/Assets.xcassets/AppIcon.appiconset/`
- Android 图标位于 `android/app/src/main/res/mipmap-*/`

<div id="adding-your-app-icon"></div>

## 添加您的应用图标

1. 创建一个 **1024x1024 PNG** 格式的图标文件
2. 将其放置在 `assets/app_icon/` 中（例如 `assets/app_icon/icon.png`）
3. 如需要，更新 `pubspec.yaml` 中的 `image_path`：

``` yaml
flutter_launcher_icons:
  android: true
  ios: true
  image_path: "assets/app_icon/icon.png"
```

4. 运行图标生成命令

<div id="app-icon-requirements"></div>

## 应用图标要求

| 属性 | 值 |
|-----------|-------|
| 格式 | PNG |
| 尺寸 | 1024x1024 像素 |
| 图层 | 无透明度的扁平化图层 |

### 文件命名

保持文件名简单，不使用特殊字符：
- `app_icon.png`
- `icon.png`

### 平台指南

有关详细要求，请参阅官方平台指南：
- <a href="https://developer.apple.com/design/human-interface-guidelines/app-icons" target="_BLANK">Apple 人机界面指南 - 应用图标</a>
- <a href="https://developer.android.com/distribute/google-play/resources/icon-design-specifications" target="_BLANK">Google Play 图标设计规范</a>

<div id="configuration"></div>

## 配置

在您的 `pubspec.yaml` 中自定义图标生成：

``` yaml
flutter_launcher_icons:
  android: true
  ios: true
  image_path: "assets/app_icon/icon.png"

  # Optional: Use different icons per platform
  # image_path_android: "assets/app_icon/android_icon.png"
  # image_path_ios: "assets/app_icon/ios_icon.png"

  # Optional: Adaptive icons for Android
  # adaptive_icon_background: "#ffffff"
  # adaptive_icon_foreground: "assets/app_icon/foreground.png"

  # Optional: Remove alpha channel for iOS
  # remove_alpha_ios: true
```

查看 <a href="https://pub.dev/packages/flutter_launcher_icons" target="_BLANK">flutter_launcher_icons 文档</a> 了解所有可用选项。

<div id="badge-count"></div>

## 角标计数

{{ config('app.name') }} 提供辅助函数来管理应用角标计数（显示在应用图标上的数字）：

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Set badge count to 5
await setBadgeNumber(5);

// Clear the badge count
await clearBadgeNumber();
```

### 平台支持

角标计数在以下平台上受支持：
- **iOS**：原生支持
- **Android**：需要启动器支持（大多数启动器支持此功能）
- **Web**：不支持

### 使用场景

角标计数的常见场景：
- 未读通知
- 待处理消息
- 购物车中的商品
- 未完成的任务

``` dart
// Example: Update badge when new messages arrive
void onNewMessage() async {
  int unreadCount = await MessageService.getUnreadCount();
  await setBadgeNumber(unreadCount);
}

// Example: Clear badge when user views messages
void onMessagesViewed() async {
  await clearBadgeNumber();
}
```

