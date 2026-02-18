# Styled Text

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- [基本的な使い方](#basic-usage "基本的な使い方")
- [Children モード](#children-mode "Children モード")
- [テンプレートモード](#template-mode "テンプレートモード")
  - [プレースホルダーのスタイリング](#styling-placeholders "プレースホルダーのスタイリング")
  - [タップコールバック](#tap-callbacks "タップコールバック")
  - [パイプ区切りキー](#pipe-keys "パイプ区切りキー")
  - [ローカリゼーションキー](#localization-keys "ローカリゼーションキー")
- [パラメータ](#parameters "パラメータ")
- [テキストエクステンション](#text-extensions "テキストエクステンション")
  - [タイポグラフィスタイル](#typography-styles "タイポグラフィスタイル")
  - [ユーティリティメソッド](#utility-methods "ユーティリティメソッド")
- [使用例](#examples "使用例")

<div id="introduction"></div>

## はじめに

**StyledText** は、混合スタイル、タップコールバック、ポインターイベントを持つリッチテキストを表示するためのウィジェットです。複数の `TextSpan` 子要素を持つ `RichText` ウィジェットとしてレンダリングされ、テキストの各セグメントをきめ細かく制御できます。

StyledText は 2 つのモードをサポートしています:

1. **Children モード** -- それぞれ独自のスタイルを持つ `Text` ウィジェットのリストを渡す
2. **テンプレートモード** -- 文字列内で `@{{placeholder}}` 構文を使用し、プレースホルダーをスタイルとアクションにマッピング

<div id="basic-usage"></div>

## 基本的な使い方

``` dart
// Children モード - Text ウィジェットのリスト
StyledText(
  children: [
    Text("Hello ", style: TextStyle(color: Colors.black)),
    Text("World", style: TextStyle(color: Colors.blue, fontWeight: FontWeight.bold)),
  ],
)

// テンプレートモード - プレースホルダー構文
StyledText.template(
  "Welcome to @{{Nylo}}!",
  styles: {
    "Nylo": TextStyle(fontWeight: FontWeight.bold, color: Colors.blue),
  },
)
```

<div id="children-mode"></div>

## Children モード

`Text` ウィジェットのリストを渡してスタイル付きテキストを構成します:

``` dart
StyledText(
  style: TextStyle(fontSize: 16, color: Colors.black),
  children: [
    Text("Your order "),
    Text("#1234", style: TextStyle(fontWeight: FontWeight.bold)),
    Text(" has been "),
    Text("confirmed", style: TextStyle(color: Colors.green, fontWeight: FontWeight.bold)),
    Text("."),
  ],
)
```

ベースの `style` は、独自のスタイルを持たない子要素に適用されます。

### ポインターイベント

ポインターがテキストセグメントに入った時と出た時を検出します:

``` dart
StyledText(
  onEnter: (Text text, PointerEnterEvent event) {
    print("Hovering over: ${text.data}");
  },
  onExit: (Text text, PointerExitEvent event) {
    print("Left: ${text.data}");
  },
  children: [
    Text("Hover me", style: TextStyle(color: Colors.blue)),
    Text(" or "),
    Text("me", style: TextStyle(color: Colors.red)),
  ],
)
```

<div id="template-mode"></div>

## テンプレートモード

`StyledText.template()` で `@{{placeholder}}` 構文を使用します:

``` dart
StyledText.template(
  "By continuing, you agree to our @{{Terms of Service}} and @{{Privacy Policy}}.",
  style: TextStyle(color: Colors.grey),
  styles: {
    "Terms of Service": TextStyle(color: Colors.blue, decoration: TextDecoration.underline),
    "Privacy Policy": TextStyle(color: Colors.blue, decoration: TextDecoration.underline),
  },
  onTap: {
    "Terms of Service": () => routeTo("/terms"),
    "Privacy Policy": () => routeTo("/privacy"),
  },
)
```

`@{{ }}` 間のテキストは、**表示テキスト** であると同時に、スタイルとタップコールバックを参照するための **キー** です。

<div id="styling-placeholders"></div>

### プレースホルダーのスタイリング

プレースホルダー名を `TextStyle` オブジェクトにマッピングします:

``` dart
StyledText.template(
  "Framework Version: @{{$version}}",
  style: textTheme.bodyMedium,
  styles: {
    version: TextStyle(fontWeight: FontWeight.bold),
  },
)
```

<div id="tap-callbacks"></div>

### タップコールバック

プレースホルダー名をタップハンドラーにマッピングします:

``` dart
StyledText.template(
  "@{{Sign up}} or @{{Log in}} to continue.",
  onTap: {
    "Sign up": () => routeTo(SignUpPage.path),
    "Log in": () => routeTo(LoginPage.path),
  },
  styles: {
    "Sign up": TextStyle(color: Colors.blue, fontWeight: FontWeight.bold),
    "Log in": TextStyle(color: Colors.blue, fontWeight: FontWeight.bold),
  },
)
```

<div id="pipe-keys"></div>

### パイプ区切りキー

パイプ `|` 区切りのキーを使用して、同じスタイルまたはコールバックを複数のプレースホルダーに適用します:

``` dart
StyledText.template(
  "Available in @{{English}}, @{{French}}, and @{{German}}.",
  styles: {
    "English|French|German": TextStyle(
      fontWeight: FontWeight.bold,
      color: Colors.blue,
    ),
  },
  onTap: {
    "English|French|German": () => showLanguagePicker(),
  },
)
```

これにより、3 つすべてのプレースホルダーに同じスタイルとコールバックがマッピングされます。

<div id="localization-keys"></div>

### ローカリゼーションキー

`@{{key:text}}` 構文を使用して、**参照キー** と **表示テキスト** を分離します。これはローカリゼーションに便利です。キーはすべてのロケールで同じままで、表示テキストは言語ごとに変わります。

``` dart
// ロケールファイル:
// en.json → "learn_skills": "Learn @{{lang:Languages}}, @{{read:Reading}} and @{{speak:Speaking}} in @{{app:AppName}}"
// es.json → "learn_skills": "Aprende @{{lang:Idiomas}}, @{{read:Lectura}} y @{{speak:Habla}} en @{{app:AppName}}"

StyledText.template(
  "learn_skills".tr(),
  styles: {
    "lang|read|speak": TextStyle(
      color: Colors.blue,
      fontWeight: FontWeight.bold,
    ),
    "app": TextStyle(color: Colors.green),
  },
  onTap: {
    "app": () => routeTo("/about"),
  },
)
// EN の表示: "Learn Languages, Reading and Speaking in AppName"
// ES の表示: "Aprende Idiomas, Lectura y Habla en AppName"
```

`:` の前の部分はスタイルとタップコールバックを参照するための **キー** です。`:` の後の部分は画面にレンダリングされる **表示テキスト** です。`:` がない場合、プレースホルダーは以前と同じ動作をします -- 完全な後方互換性があります。

これは[パイプ区切りキー](#pipe-keys)や[タップコールバック](#tap-callbacks)を含むすべての既存機能と連携します。

<div id="parameters"></div>

## パラメータ

### StyledText（Children モード）

| パラメータ | 型 | デフォルト | 説明 |
|-----------|------|---------|-------------|
| `children` | `List<Text>` | 必須 | Text ウィジェットのリスト |
| `style` | `TextStyle?` | null | すべての子要素のベーススタイル |
| `onEnter` | `Function(Text, PointerEnterEvent)?` | null | ポインター進入コールバック |
| `onExit` | `Function(Text, PointerExitEvent)?` | null | ポインター退出コールバック |
| `spellOut` | `bool?` | null | テキストを一文字ずつ綴る |
| `softWrap` | `bool` | `true` | ソフトラッピングを有効化 |
| `textAlign` | `TextAlign` | `TextAlign.start` | テキストの配置 |
| `textDirection` | `TextDirection?` | null | テキストの方向 |
| `maxLines` | `int?` | null | 最大行数 |
| `overflow` | `TextOverflow` | `TextOverflow.clip` | オーバーフロー動作 |
| `locale` | `Locale?` | null | テキストのロケール |
| `strutStyle` | `StrutStyle?` | null | ストラットスタイル |
| `textScaler` | `TextScaler?` | null | テキストスケーラー |
| `selectionColor` | `Color?` | null | 選択ハイライト色 |

### StyledText.template（テンプレートモード）

| パラメータ | 型 | デフォルト | 説明 |
|-----------|------|---------|-------------|
| `text` | `String` | 必須 | `@{{placeholder}}` 構文を含むテンプレートテキスト |
| `styles` | `Map<String, TextStyle>?` | null | プレースホルダー名からスタイルへのマップ |
| `onTap` | `Map<String, VoidCallback>?` | null | プレースホルダー名からタップコールバックへのマップ |
| `style` | `TextStyle?` | null | プレースホルダー以外のテキストのベーススタイル |

その他のパラメータ（`softWrap`、`textAlign`、`maxLines` など）は Children コンストラクタと同じです。

<div id="text-extensions"></div>

## テキストエクステンション

{{ config('app.name') }} は Flutter の `Text` ウィジェットをタイポグラフィとユーティリティメソッドで拡張しています。

<div id="typography-styles"></div>

### タイポグラフィスタイル

任意の `Text` ウィジェットに Material Design タイポグラフィスタイルを適用します:

``` dart
Text("Hello").displayLarge()
Text("Hello").displayMedium()
Text("Hello").displaySmall()
Text("Hello").headingLarge()
Text("Hello").headingMedium()
Text("Hello").headingSmall()
Text("Hello").titleLarge()
Text("Hello").titleMedium()
Text("Hello").titleSmall()
Text("Hello").bodyLarge()
Text("Hello").bodyMedium()
Text("Hello").bodySmall()
Text("Hello").labelLarge()
Text("Hello").labelMedium()
Text("Hello").labelSmall()
```

それぞれオプションのオーバーライドを受け付けます:

``` dart
Text("Welcome").headingLarge(
  color: Colors.blue,
  fontWeight: FontWeight.w700,
  letterSpacing: 1.2,
)
```

**利用可能なオーバーライド**（すべてのタイポグラフィメソッドで共通）:

| パラメータ | 型 | 説明 |
|-----------|------|-------------|
| `color` | `Color?` | テキスト色 |
| `fontSize` | `double?` | フォントサイズ |
| `fontWeight` | `FontWeight?` | フォントウェイト |
| `fontStyle` | `FontStyle?` | イタリック/通常 |
| `letterSpacing` | `double?` | 文字間隔 |
| `wordSpacing` | `double?` | 単語間隔 |
| `height` | `double?` | 行の高さ |
| `decoration` | `TextDecoration?` | テキスト装飾 |
| `decorationColor` | `Color?` | 装飾の色 |
| `decorationStyle` | `TextDecorationStyle?` | 装飾スタイル |
| `decorationThickness` | `double?` | 装飾の太さ |
| `fontFamily` | `String?` | フォントファミリー |
| `shadows` | `List<Shadow>?` | テキストシャドウ |
| `overflow` | `TextOverflow?` | オーバーフロー動作 |

<div id="utility-methods"></div>

### ユーティリティメソッド

``` dart
// フォントウェイト
Text("Bold text").fontWeightBold()
Text("Light text").fontWeightLight()

// 配置
Text("Left aligned").alignLeft()
Text("Center aligned").alignCenter()
Text("Right aligned").alignRight()

// 最大行数
Text("Long text...").setMaxLines(2)

// フォントファミリー
Text("Custom font").setFontFamily("Roboto")

// フォントサイズ
Text("Big text").setFontSize(24)

// カスタムスタイル
Text("Styled").setStyle(TextStyle(color: Colors.red))

// パディング
Text("Padded").paddingOnly(left: 8, top: 4, right: 8, bottom: 4)

// 変更を加えたコピー
Text("Original").copyWith(
  textAlign: TextAlign.center,
  maxLines: 2,
  style: TextStyle(fontSize: 18),
)
```

<div id="examples"></div>

## 使用例

### 利用規約リンク

``` dart
StyledText.template(
  "By signing up, you agree to our @{{Terms}} and @{{Privacy Policy}}.",
  style: TextStyle(color: Colors.grey.shade600, fontSize: 14),
  styles: {
    "Terms|Privacy Policy": TextStyle(
      color: Colors.blue,
      decoration: TextDecoration.underline,
    ),
  },
  onTap: {
    "Terms": () => routeTo("/terms"),
    "Privacy Policy": () => routeTo("/privacy"),
  },
)
```

### バージョン表示

``` dart
StyledText.template(
  "Framework Version: @{{$nyloVersion}}",
  style: textTheme.bodyMedium!.copyWith(
    color: NyColor(
      light: Colors.grey.shade800,
      dark: Colors.grey.shade200,
    ).toColor(context),
  ),
  styles: {
    nyloVersion: TextStyle(fontWeight: FontWeight.bold),
  },
)
```

### 混合スタイルの段落

``` dart
StyledText(
  style: TextStyle(fontSize: 16, height: 1.5),
  children: [
    Text("Flutter "),
    Text("makes it easy", style: TextStyle(fontWeight: FontWeight.bold)),
    Text(" to build beautiful apps. With "),
    Text("Nylo", style: TextStyle(color: Colors.blue, fontWeight: FontWeight.bold)),
    Text(", it's even faster."),
  ],
)
```

### タイポグラフィチェーン

``` dart
Text("Welcome Back")
    .headingLarge(color: Colors.black)
    .alignCenter()
```
