# 深度链接

---

<a name="section-1"></a>

- [简介](#introduction "简介")
- [入门](#getting-started "入门")
  - [启用深度链接](#enable-deep-links "启用深度链接")
  - [生成 Provider](#generate-a-provider "生成 Provider")
- [平台配置](#platform-configuration "平台配置")
  - [Android 配置](#android-configuration "Android 配置")
  - [iOS 配置](#ios-configuration "iOS 配置")
- [拦截链接](#intercepting-links "拦截链接")
- [回退路由](#fallback-route "回退路由")
- [测试](#testing "测试")
- [API 参考](#api-reference "API 参考")

<div id="introduction"></div>

## 简介

{{ config('app.name') }} 内置了由 [`app_links`](https://pub.dev/packages/app_links) 提供支持的一流深度链接支持。来自 Android App Links、iOS Universal Links、自定义 URL 方案和 Web URL 的传入 URI 会被自动捕获并通过已注册的路由进行路由。

只需一行代码即可启用完整的处理流程：

```dart
nylo.useDeepLinks();
```

当 URI 到达时，{{ config('app.name') }} 会提取路径和查询参数，并以此调用 `routeTo()`。冷启动（应用通过链接打开）会在导航器就绪后重新执行，热启动链接（应用运行时收到）也以同样的方式处理。

<div id="getting-started"></div>

## 入门

<div id="enable-deep-links"></div>

### 启用深度链接

在任意 provider 的 `setup` 方法中调用 `useDeepLinks()`：

```dart
import 'package:nylo_framework/nylo_framework.dart';

class DeepLinkProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.useDeepLinks();
    return nylo;
  }

  @override
  boot(Nylo nylo) async {}
}
```

在 `config/providers.dart` 中注册该 provider，使其在应用启动时运行。

<div id="generate-a-provider"></div>

### 生成 Provider

使用 Metro 为您脚手架生成 provider：

```bash
dart run nylo_framework:main make:deep_link_provider
```

这将创建 `lib/app/providers/deep_link_provider.dart`，其中已预先配置 `useDeepLinks()` 并包含注释形式的 `onIncomingLink` 示例。同时，该 provider 也会自动添加到 `config/providers.dart`。

<div id="platform-configuration"></div>

## 平台配置

<div id="android-configuration"></div>

### Android 配置

在 `android/app/src/main/AndroidManifest.xml` 的主 `<activity>` 中添加 `<intent-filter>`。以下示例同时接受 Android App Links（`https://example.com/...`）和自定义方案（`myapp://...`）：

```xml
<activity
    android:name=".MainActivity"
    android:exported="true"
    android:launchMode="singleTop">

    <!-- Standard Flutter intent-filter -->
    <intent-filter>
        <action android:name="android.intent.action.MAIN" />
        <category android:name="android.intent.category.LAUNCHER" />
    </intent-filter>

    <!-- Android App Links -->
    <intent-filter android:autoVerify="true">
        <action android:name="android.intent.action.VIEW" />
        <category android:name="android.intent.category.DEFAULT" />
        <category android:name="android.intent.category.BROWSABLE" />
        <data android:scheme="https"
              android:host="example.com" />
    </intent-filter>

    <!-- Custom URL scheme -->
    <intent-filter>
        <action android:name="android.intent.action.VIEW" />
        <category android:name="android.intent.category.DEFAULT" />
        <category android:name="android.intent.category.BROWSABLE" />
        <data android:scheme="myapp" />
    </intent-filter>
</activity>
```

对于已验证的 App Links，请在 `https://example.com/.well-known/assetlinks.json` 托管一个 `assetlinks.json` 文件。完整详情请参阅 <a href="https://docs.flutter.dev/cookbook/navigation/set-up-app-links" target="_BLANK">Flutter App Links 指南</a>。

<div id="ios-configuration"></div>

### iOS 配置

对于**自定义 URL 方案**，请在 `ios/Runner/Info.plist` 中添加 `CFBundleURLTypes` 条目：

```xml
<key>CFBundleURLTypes</key>
<array>
    <dict>
        <key>CFBundleURLName</key>
        <string>com.example.myapp</string>
        <key>CFBundleURLSchemes</key>
        <array>
            <string>myapp</string>
        </array>
    </dict>
</array>
```

对于 **Universal Links**，请在 Xcode 中启用 Associated Domains 功能，并在您的域名上托管 `apple-app-site-association` 文件。完整操作步骤请参阅 <a href="https://docs.flutter.dev/cookbook/navigation/set-up-universal-links" target="_BLANK">Flutter Universal Links 指南</a>。

<div id="intercepting-links"></div>

## 拦截链接

使用 `onIncomingLink` 可在 {{ config('app.name') }} 路由 URI 之前执行逻辑。返回 `true` 让 {{ config('app.name') }} 自动路由，返回 `false` 则由您自行处理该 URI：

```dart
class DeepLinkProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.useDeepLinks();

    nylo.onIncomingLink((Uri uri) async {
      // Require auth on /account/* routes
      if (uri.path.startsWith('/account') && !(await Auth.isAuthenticated())) {
        routeTo(LoginPage.path);
        return false;
      }

      // Track analytics for every incoming link
      Analytics.track('deep_link_opened', {'path': uri.path});

      return true;
    });

    return nylo;
  }

  @override
  boot(Nylo nylo) async {}
}
```

回调接收完整的 `Uri`，包括方案、主机、路径和查询参数。

<div id="fallback-route"></div>

## 回退路由

当传入 URI 的路径未注册时，将 `fallbackRoute` 传递给 `useDeepLinks()` 以跳转到合适的位置：

```dart
nylo.useDeepLinks(fallbackRoute: HomePage.path);
```

当 URI 的路径匹配已注册的路由时，{{ config('app.name') }} 将路由到该路由。否则，用户将落到回退路由。

<div id="testing"></div>

## 测试

从命令行模拟传入的深度链接：

```bash
# Android — custom scheme
adb shell am start -W -a android.intent.action.VIEW \
    -d "myapp://user/42?ref=test" com.example.myapp

# Android — App Link
adb shell am start -W -a android.intent.action.VIEW \
    -d "https://example.com/user/42?ref=test" com.example.myapp

# iOS Simulator
xcrun simctl openurl booted "myapp://user/42?ref=test"
```

在测试之间关闭应用以验证冷启动行为，在应用处于前台时触发 URL 以验证热启动行为。

<div id="api-reference"></div>

## API 参考

### useDeepLinks

```dart
void useDeepLinks({String? fallbackRoute})
```

启用平台深度链接捕获。在 provider 的 `setup` 方法中调用此方法。

| 参数 | 类型 | 说明 |
|-----------|------|-------------|
| `fallbackRoute` | `String?` | 当传入 URI 的路径未注册时要导航到的路由。 |

### onIncomingLink

```dart
void onIncomingLink(Future<bool> Function(Uri uri) callback)
```

注册一个针对每个传入 URI 调用的回调。返回 `true` 让 {{ config('app.name') }} 自动路由，返回 `false` 则抑制自动路由。

| 参数 | 类型 | 说明 |
|-----------|------|-------------|
| `callback` | `Future<bool> Function(Uri uri)` | 接收传入的 URI。 |
