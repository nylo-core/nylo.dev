# ディープリンク

---

<a name="section-1"></a>

- [はじめに](#introduction "はじめに")
- [はじめ方](#getting-started "はじめ方")
  - [ディープリンクの有効化](#enable-deep-links "ディープリンクの有効化")
  - [Provider の生成](#generate-a-provider "Provider の生成")
- [プラットフォーム設定](#platform-configuration "プラットフォーム設定")
  - [Android 設定](#android-configuration "Android 設定")
  - [iOS 設定](#ios-configuration "iOS 設定")
- [リンクのインターセプト](#intercepting-links "リンクのインターセプト")
- [フォールバックルート](#fallback-route "フォールバックルート")
- [テスト](#testing "テスト")
- [API リファレンス](#api-reference "API リファレンス")

<div id="introduction"></div>

## はじめに

{{ config('app.name') }} は [`app_links`](https://pub.dev/packages/app_links) を使用したファーストクラスのディープリンクサポートを搭載しています。Android App Links、iOS Universal Links、カスタム URL スキーム、および Web URL からの受信 URI を自動的にキャプチャし、登録済みルートを通じてルーティングします。

1 行で全パイプラインを有効化できます:

```dart
nylo.useDeepLinks();
```

URI が届くと、{{ config('app.name') }} はパスとクエリパラメータを抽出し、それらを使って `routeTo()` を呼び出します。コールドスタート起動（リンクによってアプリが開かれた場合）はナビゲーターの準備が整ってから再実行され、ウォームスタートリンク（アプリ実行中に受信した場合）も同様に処理されます。

<div id="getting-started"></div>

## はじめ方

<div id="enable-deep-links"></div>

### ディープリンクの有効化

任意の Provider の `setup` メソッド内で `useDeepLinks()` を呼び出します:

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

アプリ起動時に実行されるよう、`config/providers.dart` に Provider を登録してください。

<div id="generate-a-provider"></div>

### Provider の生成

Metro を使って Provider を自動生成できます:

```bash
dart run nylo_framework:main make:deep_link_provider
```

これにより `lib/app/providers/deep_link_provider.dart` が作成され、`useDeepLinks()` があらかじめ設定され、`onIncomingLink` の使用例がコメントとして含まれます。また、`config/providers.dart` への Provider の追加も自動的に行われます。

<div id="platform-configuration"></div>

## プラットフォーム設定

<div id="android-configuration"></div>

### Android 設定

`android/app/src/main/AndroidManifest.xml` のメイン `<activity>` に `<intent-filter>` を追加します。以下の例は Android App Links（`https://example.com/...`）とカスタムスキーム（`myapp://...`）の両方を受け付けます:

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

検証済み App Links の場合は、`https://example.com/.well-known/assetlinks.json` に `assetlinks.json` ファイルをホストしてください。詳細については <a href="https://docs.flutter.dev/cookbook/navigation/set-up-app-links" target="_BLANK">Flutter App Links ガイド</a> を参照してください。

<div id="ios-configuration"></div>

### iOS 設定

**カスタム URL スキーム**を使用する場合は、`ios/Runner/Info.plist` に `CFBundleURLTypes` エントリを追加します:

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

**Universal Links** を使用する場合は、Xcode で Associated Domains 機能を有効にし、ドメインに `apple-app-site-association` ファイルをホストしてください。完全な手順については <a href="https://docs.flutter.dev/cookbook/navigation/set-up-universal-links" target="_BLANK">Flutter Universal Links ガイド</a> を参照してください。

<div id="intercepting-links"></div>

## リンクのインターセプト

`onIncomingLink` を使用すると、{{ config('app.name') }} が URI をルーティングする前にロジックを実行できます。{{ config('app.name') }} に自動ルーティングさせる場合は `true` を返し、URI を自分で処理する場合は `false` を返します:

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

コールバックはスキーム、ホスト、パス、クエリパラメータを含む完全な `Uri` を受け取ります。

<div id="fallback-route"></div>

## フォールバックルート

受信した URI のパスが登録されていない場合に適切な場所に遷移するには、`useDeepLinks()` に `fallbackRoute` を渡します:

```dart
nylo.useDeepLinks(fallbackRoute: HomePage.path);
```

URI のパスが登録済みルートと一致する場合、{{ config('app.name') }} はそのルートにナビゲーションします。一致しない場合は、フォールバック先に遷移します。

<div id="testing"></div>

## テスト

コマンドラインから受信ディープリンクをシミュレートします:

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

コールドスタートの動作を確認するにはテスト間にアプリを終了させ、ウォームスタートを確認するにはアプリをフォアグラウンドにした状態で URL をトリガーします。

<div id="api-reference"></div>

## API リファレンス

### useDeepLinks

```dart
void useDeepLinks({String? fallbackRoute})
```

プラットフォームのディープリンクキャプチャを有効にします。Provider の `setup` メソッド内で呼び出してください。

| パラメータ | 型 | 説明 |
|-----------|------|-------------|
| `fallbackRoute` | `String?` | 受信した URI のパスが登録されていない場合にナビゲーションするルート。 |

### onIncomingLink

```dart
void onIncomingLink(Future<bool> Function(Uri uri) callback)
```

受信するすべての URI に対して呼び出されるコールバックを登録します。{{ config('app.name') }} に自動ルーティングさせる場合は `true` を返し、自動ルーティングを抑制する場合は `false` を返します。

| パラメータ | 型 | 説明 |
|-----------|------|-------------|
| `callback` | `Future<bool> Function(Uri uri)` | 受信した URI を受け取ります。 |
