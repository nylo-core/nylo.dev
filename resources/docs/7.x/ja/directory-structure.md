# ディレクトリ構造

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- [ルートディレクトリ](#root-directory "ルートディレクトリ")
- [lib ディレクトリ](#lib-directory "lib ディレクトリ")
  - [app](#app-directory "app")
  - [bootstrap](#bootstrap-directory "bootstrap")
  - [config](#config-directory "config")
  - [resources](#resources-directory "resources")
  - [routes](#routes-directory "routes")
- [assets ディレクトリ](#assets-directory "assets ディレクトリ")
- [アセットヘルパー](#asset-helpers "アセットヘルパー")


<div id="introduction"></div>

## はじめに

{{ config('app.name') }} は <a href="https://github.com/laravel/laravel" target="_BLANK">Laravel</a> にインスパイアされた、クリーンで整理されたディレクトリ構造を使用します。この構造はプロジェクト全体で一貫性を保ち、ファイルを見つけやすくします。

<div id="root-directory"></div>

## ルートディレクトリ

```
nylo_app/
├── android/          # Android プラットフォームファイル
├── assets/           # 画像、フォント、その他のアセット
├── ios/              # iOS プラットフォームファイル
├── lang/             # 言語/翻訳 JSON ファイル
├── lib/              # Dart アプリケーションコード
├── test/             # テストファイル
├── .env              # 環境変数
├── pubspec.yaml      # 依存関係とプロジェクト設定
└── ...
```

<div id="lib-directory"></div>

## lib ディレクトリ

`lib/` フォルダにはすべての Dart アプリケーションコードが含まれます:

```
lib/
├── app/              # アプリケーションロジック
├── bootstrap/        # ブート設定
├── config/           # 設定ファイル
├── resources/        # UI コンポーネント
├── routes/           # ルート定義
└── main.dart         # アプリケーションエントリーポイント
```

<div id="app-directory"></div>

### app/

`app/` ディレクトリにはアプリケーションのコアロジックが含まれます:

| ディレクトリ | 目的 |
|-----------|---------|
| `commands/` | カスタム Metro CLI コマンド |
| `controllers/` | ビジネスロジック用のページ Controller |
| `events/` | イベントシステム用のイベントクラス |
| `forms/` | バリデーション付きフォームクラス |
| `models/` | データモデルクラス |
| `networking/` | API サービスとネットワーク設定 |
| `networking/dio/interceptors/` | Dio HTTP インターセプター |
| `providers/` | アプリ起動時にブートされるサービス Provider |
| `services/` | 汎用サービスクラス |

<div id="bootstrap-directory"></div>

### bootstrap/

`bootstrap/` ディレクトリにはアプリのブート方法を設定するファイルが含まれます:

| ファイル | 目的 |
|------|---------|
| `boot.dart` | メインブートシーケンスの設定 |
| `decoders.dart` | Model と API デコーダーの登録 |
| `env.g.dart` | 生成された暗号化環境設定 |
| `events.dart` | イベント登録 |
| `extensions.dart` | カスタムエクステンション |
| `helpers.dart` | カスタムヘルパー関数 |
| `providers.dart` | Provider 登録 |
| `theme.dart` | テーマ設定 |

<div id="config-directory"></div>

### config/

`config/` ディレクトリにはアプリケーション設定が含まれます:

| ファイル | 目的 |
|------|---------|
| `app.dart` | コアアプリ設定 |
| `design.dart` | アプリデザイン（フォント、ロゴ、ローダー） |
| `localization.dart` | 言語とロケール設定 |
| `storage_keys.dart` | ローカルストレージキーの定義 |
| `toast_notification.dart` | トースト通知スタイル |

<div id="resources-directory"></div>

### resources/

`resources/` ディレクトリには UI コンポーネントが含まれます:

| ディレクトリ | 目的 |
|-----------|---------|
| `pages/` | ページウィジェット（画面） |
| `themes/` | テーマ定義 |
| `themes/light/` | ライトテーマカラー |
| `themes/dark/` | ダークテーマカラー |
| `widgets/` | 再利用可能なウィジェットコンポーネント |
| `widgets/buttons/` | カスタムボタンウィジェット |
| `widgets/bottom_sheet_modals/` | ボトムシートモーダルウィジェット |

<div id="routes-directory"></div>

### routes/

`routes/` ディレクトリにはルーティング設定が含まれます:

| ファイル/ディレクトリ | 目的 |
|----------------|---------|
| `router.dart` | ルート定義 |
| `guards/` | ルートガードクラス |

<div id="assets-directory"></div>

## assets ディレクトリ

`assets/` ディレクトリには静的ファイルが保存されます:

```
assets/
├── app_icon/         # アプリアイコンソース
├── fonts/            # カスタムフォント
└── images/           # 画像アセット
```

### アセットの登録

アセットは `pubspec.yaml` に登録します:

``` yaml
flutter:
  assets:
    - assets/fonts/
    - assets/images/
    - assets/app_icon/
    - lang/
```

<div id="asset-helpers"></div>

## アセットヘルパー

{{ config('app.name') }} はアセットを操作するためのヘルパーを提供します。

### 画像アセット

``` dart
// 標準的な Flutter の方法
Image.asset(
  'assets/images/logo.png',
  height: 50,
  width: 50,
)

// LocalAsset ウィジェットを使用
LocalAsset.image(
  "logo.png",
  height: 50,
  width: 50,
)
```

### 汎用アセット

``` dart
// 任意のアセットパスを取得
String fontPath = getAsset('fonts/custom.ttf');

// 動画の例
VideoPlayerController.asset(
  getAsset('videos/intro.mp4')
)
```

### 言語ファイル

言語ファイルはプロジェクトルートの `lang/` に保存されます:

```
lang/
├── en.json           # 英語
├── es.json           # スペイン語
├── fr.json           # フランス語
└── ...
```

詳細については[ローカリゼーション](/docs/7.x/localization)を参照してください。
