# 必要条件

---

<a name="section-1"></a>
- [システム要件](#system-requirements "システム要件")
- [Flutter のインストール](#installing-flutter "Flutter のインストール")
- [インストールの確認](#verifying-installation "インストールの確認")
- [エディタの設定](#set-up-an-editor "エディタの設定")


<div id="system-requirements"></div>

## システム要件

{{ config('app.name') }} v7 には以下の最低バージョンが必要です:

| 要件 | 最低バージョン |
|-------------|-----------------|
| **Flutter** | 3.24.0 以上 |
| **Dart SDK** | 3.10.7 以上 |

### プラットフォームサポート

{{ config('app.name') }} は Flutter がサポートするすべてのプラットフォームをサポートしています:

| プラットフォーム | サポート |
|----------|---------|
| iOS | ✓ フルサポート |
| Android | ✓ フルサポート |
| Web | ✓ フルサポート |
| macOS | ✓ フルサポート |
| Windows | ✓ フルサポート |
| Linux | ✓ フルサポート |

<div id="installing-flutter"></div>

## Flutter のインストール

Flutter がインストールされていない場合は、お使いの OS に合わせた公式インストールガイドに従ってください:

- <a href="https://docs.flutter.dev/get-started/install" target="_BLANK">Flutter インストールガイド</a>

<div id="verifying-installation"></div>

## インストールの確認

Flutter をインストールした後、セットアップを確認してください:

### Flutter バージョンの確認

``` bash
flutter --version
```

以下のような出力が表示されるはずです:

```
Flutter 3.24.0 • channel stable
Dart SDK version: 3.10.7
```

### Flutter の更新（必要な場合）

Flutter のバージョンが 3.24.0 未満の場合、最新の安定版にアップグレードしてください:

``` bash
flutter channel stable
flutter upgrade
```

### Flutter Doctor の実行

開発環境が正しく設定されていることを確認します:

``` bash
flutter doctor -v
```

このコマンドは以下を確認します:
- Flutter SDK のインストール
- Android ツールチェーン（Android 開発用）
- Xcode（iOS/macOS 開発用）
- 接続されたデバイス
- IDE プラグイン

{{ config('app.name') }} のインストールに進む前に、報告された問題を修正してください。

<div id="set-up-an-editor"></div>

## エディタの設定

Flutter をサポートする IDE を選択してください:

### Visual Studio Code（推奨）

<a href="https://code.visualstudio.com" target="_BLANK">VS Code</a> は軽量で、優れた Flutter サポートを備えています。

1. <a href="https://code.visualstudio.com" target="_BLANK">VS Code</a> をインストール
2. <a href="https://marketplace.visualstudio.com/items?itemName=Dart-Code.flutter" target="_BLANK">Flutter 拡張機能</a>をインストール
3. <a href="https://marketplace.visualstudio.com/items?itemName=Dart-Code.dart-code" target="_BLANK">Dart 拡張機能</a>をインストール

セットアップガイド: <a href="https://docs.flutter.dev/get-started/editor?tab=vscode" target="_BLANK">VS Code Flutter セットアップ</a>

### Android Studio

<a href="https://developer.android.com/studio" target="_BLANK">Android Studio</a> は、エミュレーターサポートを内蔵したフル機能の IDE を提供します。

1. <a href="https://developer.android.com/studio" target="_BLANK">Android Studio</a> をインストール
2. Flutter プラグインをインストール（設定 → プラグイン → Flutter）
3. Dart プラグインをインストール

セットアップガイド: <a href="https://docs.flutter.dev/get-started/editor?tab=androidstudio" target="_BLANK">Android Studio Flutter セットアップ</a>

### IntelliJ IDEA

<a href="https://www.jetbrains.com/idea/" target="_BLANK">IntelliJ IDEA</a>（Community または Ultimate）も Flutter 開発をサポートしています。

1. IntelliJ IDEA をインストール
2. Flutter プラグインをインストール（設定 → プラグイン → Flutter）
3. Dart プラグインをインストール

エディタの設定が完了したら、[{{ config('app.name') }} のインストール](/docs/7.x/installation)に進む準備が整いました。
