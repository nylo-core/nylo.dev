# スクリーンショット
<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [はじめに](#introduction "スクリーンショットの概要")
- [画面のキャプチャ](#capturing-the-screen "画面全体をキャプチャする")
- [領域のキャプチャ](#capturing-an-area "ScreenshotArea で画面の一部をキャプチャする")
- [スクリーンショットの保存](#saving-a-screenshot "スクリーンショットをディスクに保存する")
- [ストア掲載用](#store-listings "App Store と Google Play 向けにスクリーンショットのサイズを調整する")
  - [ストアのサイズ](#store-sizes "ScreenshotStore のサイズ")
- [コンテンツの非表示](#hiding-content "スクリーンショットからコンテンツを隠す")
- [ウィジェットのキャプチャ](#capturing-a-widget "表示せずにウィジェットをキャプチャする")
- [ロケールとテーマ](#locales-and-themes "別のロケールやテーマでキャプチャする")
- [Metro から](#from-metro "Metro からスクリーンショットを撮る")

<div id="introduction"></div>

## はじめに

<x-doc-strip label="必須" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`Screenshot` は、アプリの画面、またはその一部の PNG を撮影します:

``` dart
// 画面全体（ダイアログとシートを含む）
final Screenshot shot = await Screenshot.capture();
await shot.store('reports/checkout.png');

// 画面の一部
final Screenshot receipt = await Screenshot.area('receipt');
await receipt.store('order-1042.png', disk: 'public');
```

キャプチャはすべてのビルドモードで動作するため、レシートの共有やバグ報告への画面の添付など、アプリ内の機能に利用できます。[Metro](#from-metro) と[プレイブック](/docs/{{ $version }}/playbooks#screenshots)も同じコードを使用しています。

`Screenshot` は PNG を `bytes` として保持し、物理ピクセル単位の `width` と `height` を持ちます。

<div id="capturing-the-screen"></div>

## 画面のキャプチャ

`Screenshot.capture()` は、ユーザーが見ているとおりにアプリの画面全体を読み取ります:

``` dart
final Screenshot shot = await Screenshot.capture();
```

| パラメータ | 説明 |
|-----------|-------------|
| `pixelRatio` | 論理ピクセルに対するサイズ。デフォルトはデバイスの値で、PNG は画面のネイティブ解像度になります |
| `settle` | キャプチャの前に次のフレームを待ちます（デフォルトは `true`）。フレームが描画された直後は `false` を渡します |
| `locale` | キャプチャの間、この言語で画面を表示します。例: `'es'` |
| `theme` | キャプチャの間、このテーマ ID で画面を表示します。例: `'dark'` |

`NyApp.materialApp` でビルドされたアプリは、ページだけを正確にキャプチャします。独自の `MaterialApp` をビルドしている場合は、その `builder` に `ScreenshotRoot` を追加してください:

``` dart
MaterialApp(
  builder: (context, child) => ScreenshotRoot(child: child!),
)
```

<div id="capturing-an-area"></div>

## 領域のキャプチャ

画面の一部を `ScreenshotArea` で囲み、名前を付けます:

``` dart
ScreenshotArea(
  name: 'receipt',
  child: ReceiptCard(order: order),
)
```

その名前でキャプチャします:

``` dart
final Screenshot receipt = await Screenshot.area('receipt');
```

`Screenshot.area` は、`capture` と同じ `pixelRatio`、`settle`、`locale`、`theme` パラメータを取ります。`Screenshot.areas` は、画面上にある領域の名前を一覧表示します。

<div id="saving-a-screenshot"></div>

## スクリーンショットの保存

`store` は PNG をファイルストレージのディスクに保存し、パスを返します:

``` dart
final Screenshot shot = await Screenshot.capture();

String path = await shot.store('reports/checkout.png');
await shot.store('order-1042.png', disk: 'public');
```

`disk` を指定しない場合は、デフォルトのディスクに保存されます。画像を共有またはアップロードするには、`shot.bytes` を読み取ります。

<div id="store-listings"></div>

## ストア掲載用

`forStore` は、アプリストアが求める正確なサイズの新しいスクリーンショットを返します:

``` dart
final Screenshot listing = await shot.forStore(
  ScreenshotStore.ios69,
  frame: true,
  caption: 'Checkout in one tap',
);
await listing.store('store/ios-6.9/checkout.png');
```

ほぼ同じ形の画面はそのサイズいっぱいに表示され、それ以外はその内側に収まるように調整されます。`frame: true` を指定すると、画面は `caption` の下にスマートフォンの枠の中に描画されます。

| パラメータ | 説明 |
|-----------|-------------|
| `frame` | 画面をスマートフォンの枠の中に描画する |
| `caption` | スマートフォンの上に表示するテキスト。`frame` と併用します |
| `background` | 画面の周囲の `Color` |

スクリーンショット自身のサイズで枠を描画するには、`framed()` を使用します:

``` dart
final Screenshot framed = await shot.framed(caption: 'Checkout in one tap');
```

<div id="store-sizes"></div>

### ストアのサイズ

| サイズ | ID | ピクセル |
|------|----|--------|
| `ScreenshotStore.ios69` | `ios-6.9` | 1320 x 2868 |
| `ScreenshotStore.ios65` | `ios-6.5` | 1284 x 2778 |
| `ScreenshotStore.ipad13` | `ipad-13` | 2064 x 2752 |
| `ScreenshotStore.playPhone` | `play-phone` | 1080 x 1920 |
| `ScreenshotStore.playTablet` | `play-tablet` | 1600 x 2560 |

`ScreenshotStore.all` はすべてのサイズを一覧表示し、`ScreenshotStore.named('ios-6.9')` は ID でサイズを検索します。

<div id="hiding-content"></div>

## コンテンツの非表示

プライベートなものは `ScreenshotHidden` で囲みます。画面上では通常どおり表示され、すべてのスクリーンショットではグレーのブロックで覆われます:

``` dart
ScreenshotHidden(
  child: Text('Account 4021 8890 1177'),
)
```

グレーのブロックの代わりに独自のプレースホルダーを描画するには、`replacement` を渡します。

<div id="capturing-a-widget"></div>

## ウィジェットのキャプチャ

`Screenshot.widget` は、画像のためだけに作られたシェアカードのように、ウィジェットを表示せずにキャプチャします:

``` dart
final Screenshot card = await Screenshot.widget(
  SizedBox(
    width: 400,
    height: 400,
    child: ShareCard(order: order),
  ),
  context: context,
);
```

PNG はウィジェットと同じ大きさになるため、正確なサイズにするには `SizedBox` で囲みます。そのコンテキストのテーマとローカライゼーションをウィジェットに適用するには `context` を、レイアウトされる空間を設定するには `size` を、画像が読み込まれるまでの時間を確保するには `delay` を渡します。

<div id="locales-and-themes"></div>

## ロケールとテーマ

`locale` または `theme` を渡すと、1 回のキャプチャのあいだ画面を別の状態で表示します:

``` dart
final Screenshot spanish = await Screenshot.capture(locale: 'es');
final Screenshot dark = await Screenshot.area('receipt', theme: 'dark');
```

アプリはページ、その状態、ナビゲーションスタックを維持したままその場で切り替わり、直後に元に戻ります。何も保存されないため、ユーザー自身の選択は維持されます。ロケールにはアセット内の言語ファイルが、テーマには登録済みのテーマ ID が必要です。

<div id="from-metro"></div>

## Metro から

ターミナルから、実行中のアプリのスクリーンショットを保存します:

``` bash
metro live:run screenshot --open
metro live:run screenshot --area receipt
metro live:run screenshot --store ios-6.9 --frame
```

すべてのオプションについては [Metro Live](/docs/{{ $version }}/metro-live#screenshots) を参照してください。[プレイブック](/docs/{{ $version }}/playbooks#screenshots)は、すべての言語とテーマで、ジャーニー全体のスクリーンショットを撮れます。
