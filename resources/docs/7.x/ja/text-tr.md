# TextTr

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- [基本的な使い方](#basic-usage "基本的な使い方")
- [文字列補間](#string-interpolation "文字列補間")
- [スタイル付きコンストラクタ](#styled-constructors "スタイル付きコンストラクタ")
- [パラメータ](#parameters "パラメータ")


<div id="introduction"></div>

## はじめに

**TextTr** ウィジェットは、Flutter の `Text` ウィジェットをラップした便利なウィジェットで、{{ config('app.name') }} のローカリゼーションシステムを使用してコンテンツを自動的に翻訳します。

以下のように書く代わりに:

``` dart
Text("hello_world".tr())
```

こう書けます:

``` dart
TextTr("hello_world")
```

これにより、多くの翻訳文字列を扱う場合に、コードがよりクリーンで読みやすくなります。

<div id="basic-usage"></div>

## 基本的な使い方

``` dart
@override
Widget build(BuildContext context) {
  return Column(
    children: [
      TextTr("welcome_message"),

      TextTr(
        "app_title",
        style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
        textAlign: TextAlign.center,
      ),
    ],
  );
}
```

ウィジェットは言語ファイル（例: `/lang/en.json`）の翻訳キーを参照します:

``` json
{
  "welcome_message": "Welcome to our app!",
  "app_title": "My Application"
}
```

<div id="string-interpolation"></div>

## 文字列補間

`arguments` パラメータを使用して、翻訳に動的な値を挿入できます:

``` dart
TextTr(
  "greeting",
  arguments: {"name": "John"},
)
```

言語ファイル:

``` json
{
  "greeting": "Hello, @{{name}}!"
}
```

出力: **Hello, John!**

### 複数の引数

``` dart
TextTr(
  "order_summary",
  arguments: {
    "item": "Coffee",
    "quantity": "2",
    "total": "\$8.50",
  },
)
```

``` json
{
  "order_summary": "You ordered @{{quantity}}x @{{item}} for @{{total}}"
}
```

出力: **You ordered 2x Coffee for $8.50**

<div id="styled-constructors"></div>

## スタイル付きコンストラクタ

`TextTr` は、テーマのテキストスタイルを自動的に適用する名前付きコンストラクタを提供します:

### displayLarge

``` dart
TextTr.displayLarge("page_title")
```

`Theme.of(context).textTheme.displayLarge` スタイルを使用します。

### headlineLarge

``` dart
TextTr.headlineLarge("section_heading")
```

`Theme.of(context).textTheme.headlineLarge` スタイルを使用します。

### bodyLarge

``` dart
TextTr.bodyLarge("paragraph_text")
```

`Theme.of(context).textTheme.bodyLarge` スタイルを使用します。

### labelLarge

``` dart
TextTr.labelLarge("button_label")
```

`Theme.of(context).textTheme.labelLarge` スタイルを使用します。

### スタイル付きコンストラクタの使用例

``` dart
@override
Widget build(BuildContext context) {
  return Column(
    crossAxisAlignment: CrossAxisAlignment.start,
    children: [
      TextTr.headlineLarge("welcome_title"),
      SizedBox(height: 16),
      TextTr.bodyLarge(
        "welcome_description",
        arguments: {"app_name": "MyApp"},
      ),
      SizedBox(height: 24),
      TextTr.labelLarge("get_started"),
    ],
  );
}
```

<div id="parameters"></div>

## パラメータ

`TextTr` は標準の `Text` ウィジェットのすべてのパラメータをサポートします:

| パラメータ | 型 | 説明 |
|-----------|------|-------------|
| `data` | `String` | 参照する翻訳キー |
| `arguments` | `Map<String, String>?` | 文字列補間用のキーと値のペア |
| `style` | `TextStyle?` | テキストスタイル |
| `textAlign` | `TextAlign?` | テキストの配置方法 |
| `maxLines` | `int?` | 最大行数 |
| `overflow` | `TextOverflow?` | オーバーフローの処理方法 |
| `softWrap` | `bool?` | ソフトブレークでテキストを折り返すかどうか |
| `textDirection` | `TextDirection?` | テキストの方向 |
| `locale` | `Locale?` | テキストレンダリング用のロケール |
| `semanticsLabel` | `String?` | アクセシビリティラベル |

## 比較

| アプローチ | コード |
|----------|------|
| 従来の方法 | `Text("hello".tr())` |
| TextTr | `TextTr("hello")` |
| 引数付き | `TextTr("hello", arguments: {"name": "John"})` |
| スタイル付き | `TextTr.headlineLarge("title")` |
