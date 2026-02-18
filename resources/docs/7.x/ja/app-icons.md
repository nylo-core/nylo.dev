# アプリアイコン

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- [アプリアイコンの生成](#generating-app-icons "アプリアイコンの生成")
- [アプリアイコンの追加](#adding-your-app-icon "アプリアイコンの追加")
- [アプリアイコンの要件](#app-icon-requirements "アプリアイコンの要件")
- [設定](#configuration "設定")
- [バッジカウント](#badge-count "バッジカウント")

<div id="introduction"></div>

## はじめに

{{ config('app.name') }} v7 は <a href="https://pub.dev/packages/flutter_launcher_icons" target="_BLANK">flutter_launcher_icons</a> を使用して、単一のソース画像から iOS と Android のアプリアイコンを生成します。

アプリアイコンは `assets/app_icon/` ディレクトリに **1024x1024 ピクセル** のサイズで配置する必要があります。

<div id="generating-app-icons"></div>

## アプリアイコンの生成

以下のコマンドを実行して、すべてのプラットフォーム用のアイコンを生成します:

``` bash
dart run flutter_launcher_icons
```

これにより、`assets/app_icon/` のソースアイコンを読み取り、以下を生成します:
- iOS アイコン: `ios/Runner/Assets.xcassets/AppIcon.appiconset/`
- Android アイコン: `android/app/src/main/res/mipmap-*/`

<div id="adding-your-app-icon"></div>

## アプリアイコンの追加

1. **1024x1024 PNG** ファイルとしてアイコンを作成します
2. `assets/app_icon/` に配置します（例: `assets/app_icon/icon.png`）
3. 必要に応じて `pubspec.yaml` の `image_path` を更新します:

``` yaml
flutter_launcher_icons:
  android: true
  ios: true
  image_path: "assets/app_icon/icon.png"
```

4. アイコン生成コマンドを実行します

<div id="app-icon-requirements"></div>

## アプリアイコンの要件

| 属性 | 値 |
|-----------|-------|
| フォーマット | PNG |
| サイズ | 1024x1024 ピクセル |
| レイヤー | 透過なしのフラット化 |

### ファイル名

特殊文字を含まないシンプルなファイル名を使用してください:
- `app_icon.png`
- `icon.png`

### プラットフォームガイドライン

詳細な要件については、公式プラットフォームガイドラインを参照してください:
- <a href="https://developer.apple.com/design/human-interface-guidelines/app-icons" target="_BLANK">Apple Human Interface Guidelines - App Icons</a>
- <a href="https://developer.android.com/distribute/google-play/resources/icon-design-specifications" target="_BLANK">Google Play Icon Design Specifications</a>

<div id="configuration"></div>

## 設定

`pubspec.yaml` でアイコン生成をカスタマイズします:

``` yaml
flutter_launcher_icons:
  android: true
  ios: true
  image_path: "assets/app_icon/icon.png"

  # オプション: プラットフォームごとに異なるアイコンを使用
  # image_path_android: "assets/app_icon/android_icon.png"
  # image_path_ios: "assets/app_icon/ios_icon.png"

  # オプション: Android 用アダプティブアイコン
  # adaptive_icon_background: "#ffffff"
  # adaptive_icon_foreground: "assets/app_icon/foreground.png"

  # オプション: iOS のアルファチャンネルを削除
  # remove_alpha_ios: true
```

利用可能なすべてのオプションについては、<a href="https://pub.dev/packages/flutter_launcher_icons" target="_BLANK">flutter_launcher_icons ドキュメント</a>を参照してください。

<div id="badge-count"></div>

## バッジカウント

{{ config('app.name') }} は、アプリバッジカウント（アプリアイコンに表示される数字）を管理するためのヘルパー関数を提供します:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// バッジカウントを 5 に設定
await setBadgeNumber(5);

// バッジカウントをクリア
await clearBadgeNumber();
```

### プラットフォームサポート

バッジカウントは以下のプラットフォームでサポートされています:
- **iOS**: ネイティブサポート
- **Android**: ランチャーのサポートが必要（ほとんどのランチャーで対応）
- **Web**: 非サポート

### ユースケース

バッジカウントの一般的な使用例:
- 未読通知
- 保留中のメッセージ
- カート内のアイテム
- 未完了のタスク

``` dart
// 例: 新しいメッセージが届いたときにバッジを更新
void onNewMessage() async {
  int unreadCount = await MessageService.getUnreadCount();
  await setBadgeNumber(unreadCount);
}

// 例: ユーザーがメッセージを閲覧したときにバッジをクリア
void onMessagesViewed() async {
  await clearBadgeNumber();
}
```
