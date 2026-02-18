# アセット

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- ファイル
  - [画像の表示](#displaying-images "画像の表示")
  - [カスタムアセットパス](#custom-asset-paths "カスタムアセットパス")
  - [アセットパスの取得](#returning-asset-paths "アセットパスの取得")
- アセット管理
  - [新しいファイルの追加](#adding-new-files "新しいファイルの追加")
  - [アセット設定](#asset-configuration "アセット設定")

<div id="introduction"></div>

## はじめに

{{ config('app.name') }} v7 は、Flutter アプリでアセットを管理するためのヘルパーメソッドを提供します。アセットは `assets/` ディレクトリに保存され、画像、動画、フォント、その他のファイルを含みます。

デフォルトのアセット構造:

```
assets/
├── images/
│   ├── nylo_logo.png
│   └── icons/
├── fonts/
└── videos/
```

<div id="displaying-images"></div>

## 画像の表示

`LocalAsset()` ウィジェットを使用してアセットから画像を表示します:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// 基本的な使い方
LocalAsset.image("nylo_logo.png")

// `getImageAsset` ヘルパーの使用
Image.asset(getImageAsset("nylo_logo.png"))
```

どちらのメソッドも、設定されたアセットディレクトリを含む完全なアセットパスを返します。

<div id="custom-asset-paths"></div>

## カスタムアセットパス

異なるアセットサブディレクトリをサポートするには、`LocalAsset` ウィジェットにカスタムコンストラクタを追加できます。

``` dart
// /resources/widgets/local_asset_widget.dart
class LocalAsset extends StatelessWidget {
  // images
  const LocalAsset.image(String assetName,
      {super.key, this.width, this.height, this.fit, this.opacity, this.borderRadius})
      : assetName = "images/$assetName";

  // icons <- icons フォルダ用の新しいコンストラクタ
  const LocalAsset.icons(String assetName,
      {super.key, this.width, this.height, this.fit, this.opacity, this.borderRadius})
      : assetName = "images/icons/$assetName";
}

// 使用例
LocalAsset.icons("icon_name.png", width: 32, height: 32)
LocalAsset.image("logo.png", width: 100, height: 100)
```

<div id="returning-asset-paths"></div>

## アセットパスの取得

`assets/` ディレクトリ内の任意のファイルタイプには `getAsset()` を使用します:

``` dart
// 動画ファイル
String videoPath = getAsset("videos/welcome.mp4");

// JSON データファイル
String jsonPath = getAsset("data/config.json");

// フォントファイル
String fontPath = getAsset("fonts/custom_font.ttf");
```

### さまざまなウィジェットでの使用

``` dart
// ビデオプレーヤー
VideoPlayerController.asset(getAsset("videos/intro.mp4"))

// JSON の読み込み
final String jsonString = await rootBundle.loadString(getAsset("data/settings.json"));
```

<div id="adding-new-files"></div>

## 新しいファイルの追加

1. ファイルを `assets/` の適切なサブディレクトリに配置します:
   - 画像: `assets/images/`
   - 動画: `assets/videos/`
   - フォント: `assets/fonts/`
   - その他: `assets/data/` またはカスタムフォルダ

2. フォルダが `pubspec.yaml` に記載されていることを確認します:

``` yaml
flutter:
  assets:
    - assets/images/
    - assets/videos/
    - assets/data/
```

<div id="asset-configuration"></div>

## アセット設定

{{ config('app.name') }} v7 は `.env` ファイルの `ASSET_PATH` 環境変数でアセットパスを設定します:

``` bash
ASSET_PATH="assets"
```

ヘルパー関数はこのパスを自動的に前に付けるため、呼び出し時に `assets/` を含める必要はありません:

``` dart
// これらは同等です:
getAsset("videos/intro.mp4")
// 戻り値: "assets/videos/intro.mp4"

getImageAsset("logo.png")
// 戻り値: "assets/images/logo.png"
```

### ベースパスの変更

異なるアセット構造が必要な場合は、`.env` の `ASSET_PATH` を更新します:

``` bash
# 別のベースフォルダを使用
ASSET_PATH="res"
```

変更後、環境設定を再生成します:

``` bash
metro make:env --force
```
