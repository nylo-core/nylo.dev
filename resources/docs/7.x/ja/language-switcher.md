# LanguageSwitcher

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- 使い方
    - [ドロップダウンウィジェット](#usage-dropdown "ドロップダウンウィジェット")
    - [ボトムシートモーダル](#usage-bottom-modal "ボトムシートモーダル")
- [カスタムドロップダウンビルダー](#custom-builder "カスタムドロップダウンビルダー")
- [パラメータ](#parameters "パラメータ")
- [静的メソッド](#methods "静的メソッド")


<div id="introduction"></div>

## はじめに

**LanguageSwitcher** ウィジェットは、{{ config('app.name') }} プロジェクトで言語切り替えを簡単に処理する方法を提供します。`/lang` ディレクトリで利用可能な言語を自動的に検出し、ユーザーに表示します。

**LanguageSwitcher の機能:**

- `/lang` ディレクトリから利用可能な言語を表示
- ユーザーが選択するとアプリの言語を切り替え
- 選択した言語をアプリの再起動後も保持
- 言語が変更されると UI を自動的に更新

> **注意**: アプリがまだローカライズされていない場合は、このウィジェットを使用する前に[ローカリゼーション](/docs/7.x/localization)のドキュメントを参照してください。

<div id="usage-dropdown"></div>

## ドロップダウンウィジェット

`LanguageSwitcher` を使用する最もシンプルな方法は、アプリバーにドロップダウンとして配置することです:

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    appBar: AppBar(
      title: Text("Settings"),
      actions: [
        LanguageSwitcher(), // アプリバーに追加
      ],
    ),
    body: Center(
      child: Text("Hello World".tr()),
    ),
  );
}
```

ユーザーがドロップダウンをタップすると、利用可能な言語のリストが表示されます。言語を選択すると、アプリは自動的に切り替わり、UI が更新されます。

<div id="usage-bottom-modal"></div>

## ボトムシートモーダル

言語をボトムシートモーダルで表示することもできます:

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: Center(
      child: MaterialButton(
        child: Text("Change Language"),
        onPressed: () {
          LanguageSwitcher.showBottomModal(context);
        },
      ),
    ),
  );
}
```

ボトムモーダルは、現在選択されている言語の横にチェックマークを付けた言語リストを表示します。

### モーダルの高さのカスタマイズ

``` dart
LanguageSwitcher.showBottomModal(
  context,
  height: 300, // カスタムの高さ
);
```

<div id="custom-builder"></div>

## カスタムドロップダウンビルダー

ドロップダウンの各言語オプションの表示方法をカスタマイズします:

``` dart
LanguageSwitcher(
  dropdownBuilder: (Map<String, dynamic> language) {
    return Row(
      children: [
        Icon(Icons.language),
        SizedBox(width: 8),
        Text(language['name']), // 例: "English"
        // language['locale'] にはロケールコードが含まれます（例: "en"）
      ],
    );
  },
)
```

### 言語変更の処理

``` dart
LanguageSwitcher(
  onLanguageChange: (Map<String, dynamic> language) {
    print('Language changed to: ${language['name']}');
    // 言語変更時に追加のアクションを実行
  },
)
```

<div id="parameters"></div>

## パラメータ

| パラメータ | 型 | デフォルト | 説明 |
|-----------|------|---------|-------------|
| `icon` | `Widget?` | - | ドロップダウンボタンのカスタムアイコン |
| `iconEnabledColor` | `Color?` | - | ドロップダウンアイコンの色 |
| `iconSize` | `double` | `24` | ドロップダウンアイコンのサイズ |
| `dropdownBgColor` | `Color?` | - | ドロップダウンメニューの背景色 |
| `hint` | `Widget?` | - | 言語未選択時のヒントウィジェット |
| `itemHeight` | `double` | `kMinInteractiveDimension` | 各ドロップダウンアイテムの高さ |
| `elevation` | `int` | `8` | ドロップダウンメニューのエレベーション |
| `padding` | `EdgeInsetsGeometry?` | - | ドロップダウン周囲のパディング |
| `borderRadius` | `BorderRadius?` | - | ドロップダウンメニューの角丸 |
| `textStyle` | `TextStyle?` | - | ドロップダウンアイテムのテキストスタイル |
| `langPath` | `String` | `'lang'` | アセット内の言語ファイルのパス |
| `dropdownBuilder` | `Function(Map<String, dynamic>)?` | - | ドロップダウンアイテムのカスタムビルダー |
| `dropdownAlignment` | `AlignmentGeometry` | `AlignmentDirectional.centerStart` | ドロップダウンアイテムの配置 |
| `dropdownOnTap` | `Function()?` | - | ドロップダウンアイテムタップ時のコールバック |
| `onTap` | `Function()?` | - | ドロップダウンボタンタップ時のコールバック |
| `onLanguageChange` | `Function(Map<String, dynamic>)?` | - | 言語変更時のコールバック |

<div id="methods"></div>

## 静的メソッド

### 現在の言語を取得

現在選択されている言語を取得します:

``` dart
Map<String, dynamic>? lang = await LanguageSwitcher.currentLanguage();
// 戻り値: {"en": "English"} または未設定の場合は null
```

### 言語を保存

言語設定を手動で保存します:

``` dart
await LanguageSwitcher.storeLanguage(
  object: {"fr": "French"},
);
```

### 言語をクリア

保存された言語設定を削除します:

``` dart
await LanguageSwitcher.clearLanguage();
```

### 言語データを取得

ロケールコードから言語情報を取得します:

``` dart
Map<String, String>? langData = LanguageSwitcher.getLanguageData("en");
// 戻り値: {"en": "English"}

Map<String, String>? langData = LanguageSwitcher.getLanguageData("fr_CA");
// 戻り値: {"fr_CA": "French (Canada)"}
```

### 言語リストを取得

`/lang` ディレクトリから利用可能なすべての言語を取得します:

``` dart
List<Map<String, String>> languages = await LanguageSwitcher.getLanguageList();
// 戻り値: [{"en": "English"}, {"es": "Spanish"}, ...]
```

### ボトムモーダルを表示

言語選択モーダルを表示します:

``` dart
await LanguageSwitcher.showBottomModal(context);

// カスタムの高さ指定
await LanguageSwitcher.showBottomModal(context, height: 400);
```

## サポートされているロケール

`LanguageSwitcher` ウィジェットは、人間が読める名前を持つ数百のロケールコードをサポートしています。いくつかの例:

| ロケールコード | 言語名 |
|-------------|---------------|
| `en` | English |
| `en_US` | English (United States) |
| `en_GB` | English (United Kingdom) |
| `es` | Spanish |
| `fr` | French |
| `de` | German |
| `zh` | Chinese |
| `zh_Hans` | Chinese (Simplified) |
| `ja` | Japanese |
| `ko` | Korean |
| `ar` | Arabic |
| `hi` | Hindi |
| `pt` | Portuguese |
| `ru` | Russian |

完全なリストには、ほとんどの言語の地域バリアントが含まれています。
