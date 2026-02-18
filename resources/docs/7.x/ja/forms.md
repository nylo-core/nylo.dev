# フォーム

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- はじめに
  - [フォームの作成](#creating-forms "フォームの作成")
  - [フォームの表示](#displaying-a-form "フォームの表示")
  - [フォームの送信](#submitting-a-form "フォームの送信")
- フィールドタイプ
  - [テキストフィールド](#text-fields "テキストフィールド")
  - [数値フィールド](#number-fields "数値フィールド")
  - [パスワードフィールド](#password-fields "パスワードフィールド")
  - [メールフィールド](#email-fields "メールフィールド")
  - [URL フィールド](#url-fields "URL フィールド")
  - [テキストエリアフィールド](#text-area-fields "テキストエリアフィールド")
  - [電話番号フィールド](#phone-number-fields "電話番号フィールド")
  - [単語の先頭大文字](#capitalize-words-fields "単語の先頭大文字")
  - [文の先頭大文字](#capitalize-sentences-fields "文の先頭大文字")
  - [日付フィールド](#date-fields "日付フィールド")
  - [日時フィールド](#datetime-fields "日時フィールド")
  - [マスク入力フィールド](#masked-input-fields "マスク入力フィールド")
  - [通貨フィールド](#currency-fields "通貨フィールド")
  - [チェックボックスフィールド](#checkbox-fields "チェックボックスフィールド")
  - [スイッチボックスフィールド](#switch-box-fields "スイッチボックスフィールド")
  - [ピッカーフィールド](#picker-fields "ピッカーフィールド")
  - [ラジオフィールド](#radio-fields "ラジオフィールド")
  - [チップフィールド](#chip-fields "チップフィールド")
  - [スライダーフィールド](#slider-fields "スライダーフィールド")
  - [範囲スライダーフィールド](#range-slider-fields "範囲スライダーフィールド")
  - [カスタムフィールド](#custom-fields "カスタムフィールド")
  - [ウィジェットフィールド](#widget-fields "ウィジェットフィールド")
- [FormCollection](#form-collection "FormCollection")
- [フォームバリデーション](#form-validation "フォームバリデーション")
- [フォームデータの管理](#managing-form-data "フォームデータの管理")
  - [初期データ](#initial-data "初期データ")
  - [フィールド値の設定](#setting-field-values "フィールド値の設定")
  - [フィールドオプションの設定](#setting-field-options "フィールドオプションの設定")
  - [フォームデータの読み取り](#reading-form-data "フォームデータの読み取り")
  - [データのクリア](#clearing-data "データのクリア")
  - [フィールドの更新](#finding-and-updating-fields "フィールドの更新")
- [送信ボタン](#submit-button "送信ボタン")
- [フォームレイアウト](#form-layout "フォームレイアウト")
- [フィールドの表示/非表示](#field-visibility "フィールドの表示/非表示")
- [フィールドスタイリング](#field-styling "フィールドスタイリング")
- [NyFormWidget 静的メソッド](#ny-form-widget-static-methods "NyFormWidget 静的メソッド")
- [NyFormWidget コンストラクタリファレンス](#ny-form-widget-constructor-reference "NyFormWidget コンストラクタリファレンス")
- [NyFormActions](#ny-form-actions "NyFormActions")
- [全フィールドタイプリファレンス](#all-field-types-reference "全フィールドタイプリファレンス")

<div id="introduction"></div>

## はじめに

{{ config('app.name') }} v7 は `NyFormWidget` を中心に構築されたフォームシステムを提供します。フォームクラスは `NyFormWidget` を継承し、それ自体がウィジェット**です** -- 別途ラッパーは不要です。フォームは組み込みバリデーション、多数のフィールドタイプ、スタイリング、データ管理をサポートしています。

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// 1. フォームを定義
class LoginForm extends NyFormWidget {
  LoginForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  fields() => [
    Field.email("Email", validator: FormValidator.email()),
    Field.password("Password", validator: FormValidator.password()),
  ];

  static NyFormActions get actions => const NyFormActions('LoginForm');
}

// 2. 表示して送信
LoginForm(
  submitButton: Button.primary(text: "Login"),
  onSubmit: (data) {
    print(data); // {email: "...", password: "..."}
  },
)
```


<div id="creating-forms"></div>

## フォームの作成

Metro CLI を使用して新しいフォームを作成します:

``` bash
metro make:form LoginForm
```

これにより `lib/app/forms/login_form.dart` が作成されます:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class LoginForm extends NyFormWidget {
  LoginForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  fields() => [
    Field.email("Email", validator: FormValidator.email()),
    Field.password("Password", validator: FormValidator.password()),
  ];

  static NyFormActions get actions => const NyFormActions('LoginForm');
}
```

フォームは `NyFormWidget` を継承し、`fields()` メソッドをオーバーライドしてフォームフィールドを定義します。各フィールドは `Field.text()`、`Field.email()`、`Field.password()` のような名前付きコンストラクタを使用します。`static NyFormActions get actions` getter は、アプリのどこからでもフォームと対話するための便利な方法を提供します。


<div id="displaying-a-form"></div>

## フォームの表示

フォームクラスは `NyFormWidget` を継承しているため、それ自体がウィジェット**です**。ウィジェットツリーで直接使用できます:

``` dart
@override
Widget view(BuildContext context) {
  return Scaffold(
    body: SafeArea(
      child: LoginForm(
        submitButton: Button.primary(text: "Submit"),
        onSubmit: (data) {
          print(data);
        },
      ),
    ),
  );
}
```


<div id="submitting-a-form"></div>

## フォームの送信

フォームを送信する方法は 3 つあります:

### onSubmit と submitButton の使用

フォーム構築時に `onSubmit` と `submitButton` を渡します。{{ config('app.name') }} は送信ボタンとして機能するビルド済みボタンを提供しています:

``` dart
LoginForm(
  submitButton: Button.primary(text: "Submit"),
  onSubmit: (data) {
    print(data); // {email: "...", password: "..."}
  },
  onFailure: (errors) {
    print(errors.first.rule.getMessage());
  },
)
```

利用可能なボタンスタイル: `Button.primary`、`Button.secondary`、`Button.outlined`、`Button.textOnly`、`Button.icon`、`Button.gradient`、`Button.rounded`、`Button.transparency`。

### NyFormActions の使用

`actions` getter を使用してどこからでも送信できます:

``` dart
LoginForm.actions.submit(
  onSuccess: (data) {
    print(data);
  },
  onFailure: (errors) {
    print(errors.first.rule.getMessage());
  },
  showToastError: true,
);
```

### NyFormWidget.submit() 静的メソッドの使用

フォーム名を指定してどこからでもフォームを送信できます:

``` dart
NyFormWidget.submit("LoginForm",
  onSuccess: (data) {
    print(data);
  },
  onFailure: (errors) {
    print(errors.first.rule.getMessage());
  },
  showToastError: true,
);
```

送信時、フォームはすべてのフィールドをバリデーションします。有効な場合、`onSuccess` がフィールドデータの `Map<String, dynamic>` を引数に呼び出されます（キーはフィールド名の snake_case 版）。無効な場合、デフォルトでトーストエラーが表示され、指定されていれば `onFailure` が呼び出されます。


<div id="field-types"></div>

## フィールドタイプ

{{ config('app.name') }} v7 は `Field` クラスの名前付きコンストラクタを通じて 22 のフィールドタイプを提供します。すべてのフィールドコンストラクタは以下の共通パラメータを共有しています:

| パラメータ | 型 | デフォルト | 説明 |
|-----------|------|---------|-------------|
| `key` | `String` | 必須 | フィールド識別子（位置パラメータ） |
| `label` | `String?` | `null` | カスタム表示ラベル（デフォルトはキーのタイトルケース） |
| `value` | `dynamic` | `null` | 初期値 |
| `validator` | `FormValidator?` | `null` | バリデーションルール |
| `autofocus` | `bool` | `false` | 読み込み時に自動フォーカス |
| `dummyData` | `String?` | `null` | テスト/開発用データ |
| `header` | `Widget?` | `null` | フィールド上部に表示されるウィジェット |
| `footer` | `Widget?` | `null` | フィールド下部に表示されるウィジェット |
| `titleStyle` | `TextStyle?` | `null` | カスタムラベルテキストスタイル |
| `hidden` | `bool` | `false` | フィールドを非表示 |
| `readOnly` | `bool?` | `null` | フィールドを読み取り専用に設定 |
| `style` | `FieldStyle?` | 可変 | フィールド固有のスタイル設定 |
| `onChanged` | `Function(dynamic)?` | `null` | 値変更コールバック |

<div id="text-fields"></div>

### テキストフィールド

``` dart
Field.text("Name")

Field.text("Name",
  value: "John",
  validator: FormValidator.notEmpty(),
  autofocus: true,
)
```

スタイルタイプ: `FieldStyleTextField`

<div id="number-fields"></div>

### 数値フィールド

``` dart
Field.number("Age")

// 小数
Field.number("Score", decimal: true)
```

`decimal` パラメータは小数入力を許可するかどうかを制御します。スタイルタイプ: `FieldStyleTextField`

<div id="password-fields"></div>

### パスワードフィールド

``` dart
Field.password("Password")

// 表示/非表示トグル付き
Field.password("Password", viewable: true)
```

`viewable` パラメータは表示/非表示トグルを追加します。スタイルタイプ: `FieldStyleTextField`

<div id="email-fields"></div>

### メールフィールド

``` dart
Field.email("Email", validator: FormValidator.email())
```

メールキーボードタイプを自動設定し、空白をフィルタリングします。スタイルタイプ: `FieldStyleTextField`

<div id="url-fields"></div>

### URL フィールド

``` dart
Field.url("Website", validator: FormValidator.url())
```

URL キーボードタイプを設定します。スタイルタイプ: `FieldStyleTextField`

<div id="text-area-fields"></div>

### テキストエリアフィールド

``` dart
Field.textArea("Description")
```

複数行テキスト入力。スタイルタイプ: `FieldStyleTextField`

<div id="phone-number-fields"></div>

### 電話番号フィールド

``` dart
Field.phoneNumber("Mobile Phone")
```

電話番号入力を自動フォーマットします。スタイルタイプ: `FieldStyleTextField`

<div id="capitalize-words-fields"></div>

### 単語の先頭大文字

``` dart
Field.capitalizeWords("Full Name")
```

各単語の最初の文字を大文字にします。スタイルタイプ: `FieldStyleTextField`

<div id="capitalize-sentences-fields"></div>

### 文の先頭大文字

``` dart
Field.capitalizeSentences("Bio")
```

各文の最初の文字を大文字にします。スタイルタイプ: `FieldStyleTextField`

<div id="date-fields"></div>

### 日付フィールド

``` dart
Field.date("Birthday")

Field.date("Birthday",
  dummyData: "1990-01-01",
  style: FieldStyleDateTimePicker(
    firstDate: DateTime(1900),
    lastDate: DateTime.now(),
  ),
)

// クリアボタンを無効化
Field.date("Birthday",
  style: FieldStyleDateTimePicker(
    canClear: false,
  ),
)

// カスタムクリアアイコン
Field.date("Birthday",
  style: FieldStyleDateTimePicker(
    clearIconData: Icons.close,
  ),
)
```

日付ピッカーを開きます。デフォルトでは、フィールドにはユーザーが値をリセットできるクリアボタンが表示されます。`canClear: false` で非表示にするか、`clearIconData` でアイコンを変更できます。スタイルタイプ: `FieldStyleDateTimePicker`

<div id="datetime-fields"></div>

### 日時フィールド

``` dart
Field.datetime("Check in Date")

Field.datetime("Appointment",
  firstDate: DateTime(2025),
  lastDate: DateTime(2030),
  dateFormat: DateFormat('yyyy-MM-dd HH:mm'),
  initialPickerDateTime: DateTime.now(),
)
```

日付と時刻のピッカーを開きます。`firstDate`、`lastDate`、`dateFormat`、`initialPickerDateTime` をトップレベルパラメータとして直接設定できます。スタイルタイプ: `FieldStyleDateTimePicker`

<div id="masked-input-fields"></div>

### マスク入力フィールド

``` dart
Field.mask("Phone", mask: "(###) ###-####")

Field.mask("Credit Card", mask: "#### #### #### ####")

Field.mask("Custom Code",
  mask: "AA-####",
  match: r'[\w\d]',
  maskReturnValue: true, // フォーマットされた値を返す
)
```

マスク内の `#` 文字はユーザー入力に置き換えられます。`match` を使用して許可される文字を制御します。`maskReturnValue` が `true` の場合、返される値にはマスクフォーマットが含まれます。

<div id="currency-fields"></div>

### 通貨フィールド

``` dart
Field.currency("Price", currency: "usd")
```

`currency` パラメータは必須で、通貨フォーマットを決定します。スタイルタイプ: `FieldStyleTextField`

<div id="checkbox-fields"></div>

### チェックボックスフィールド

``` dart
Field.checkbox("Accept Terms")

Field.checkbox("Agree to terms",
  header: Text("Terms and conditions"),
  footer: Text("You must agree to continue."),
  validator: FormValidator.booleanTrue(message: "You must accept the terms"),
)
```

スタイルタイプ: `FieldStyleCheckbox`

<div id="switch-box-fields"></div>

### スイッチボックスフィールド

``` dart
Field.switchBox("Enable Notifications")
```

スタイルタイプ: `FieldStyleSwitchBox`

<div id="picker-fields"></div>

### ピッカーフィールド

``` dart
Field.picker("Category",
  options: FormCollection.from(["Electronics", "Clothing", "Books"]),
)

// キー・バリューペア
Field.picker("Country",
  options: FormCollection.fromMap({
    "us": "United States",
    "ca": "Canada",
    "uk": "United Kingdom",
  }),
)
```

`options` パラメータには `FormCollection` が必要です（生のリストではありません）。詳細は [FormCollection](#form-collection) を参照してください。スタイルタイプ: `FieldStylePicker`

#### リストタイルスタイル

`PickerListTileStyle` を使用して、ピッカーのボトムシートでのアイテム表示をカスタマイズできます。デフォルトでは、ボトムシートはプレーンテキストタイルを表示します。組み込みプリセットを使用して選択インジケーターを追加するか、完全にカスタムのビルダーを提供できます。

**ラジオスタイル** -- リーディングウィジェットとしてラジオボタンアイコンを表示:

``` dart
Field.picker("Country",
  options: FormCollection.from(["United States", "Canada", "United Kingdom"]),
  style: FieldStylePicker(
    listTileStyle: PickerListTileStyle.radio(),
  ),
)

// カスタムアクティブカラー
FieldStylePicker(
  listTileStyle: PickerListTileStyle.radio(activeColor: Colors.blue),
)
```

**チェックマークスタイル** -- 選択時にトレーリングウィジェットとしてチェックアイコンを表示:

``` dart
Field.picker("Category",
  options: FormCollection.from(["Electronics", "Clothing", "Books"]),
  style: FieldStylePicker(
    listTileStyle: PickerListTileStyle.checkmark(activeColor: Colors.green),
  ),
)
```

**カスタムビルダー** -- 各タイルのウィジェットを完全に制御:

``` dart
Field.picker("Color",
  options: FormCollection.from(["Red", "Green", "Blue"]),
  style: FieldStylePicker(
    listTileStyle: PickerListTileStyle.custom(
      builder: (option, isSelected, onTap) {
        return ListTile(
          title: Text(option.label,
            style: TextStyle(
              fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
            ),
          ),
          trailing: isSelected ? Icon(Icons.check_circle) : null,
          onTap: onTap,
        );
      },
    ),
  ),
)
```

両方のプリセットスタイルは `textStyle`、`selectedTextStyle`、`contentPadding`、`tileColor`、`selectedTileColor` もサポートしています:

``` dart
FieldStylePicker(
  listTileStyle: PickerListTileStyle.radio(
    activeColor: Colors.blue,
    textStyle: TextStyle(fontSize: 16),
    selectedTextStyle: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
    selectedTileColor: Colors.blue.shade50,
  ),
)
```

<div id="radio-fields"></div>

### ラジオフィールド

``` dart
Field.radio("Newsletter",
  options: FormCollection.fromMap({
    "Yes": "Yes, I want to receive newsletters",
    "No": "No, I do not want to receive newsletters",
  }),
)
```

`options` パラメータには `FormCollection` が必要です。スタイルタイプ: `FieldStyleRadio`

<div id="chip-fields"></div>

### チップフィールド

``` dart
Field.chips("Tags",
  options: FormCollection.from(["Featured", "Sale", "New"]),
)

// キー・バリューペア
Field.chips("Engine Size",
  options: FormCollection.fromMap({
    "125": "125cc",
    "250": "250cc",
    "500": "500cc",
  }),
)
```

チップウィジェットによる複数選択が可能です。`options` パラメータには `FormCollection` が必要です。スタイルタイプ: `FieldStyleChip`

<div id="slider-fields"></div>

### スライダーフィールド

``` dart
Field.slider("Rating",
  label: "Rate us",
  validator: FormValidator.minValue(4, message: "Rating must be at least 4"),
  style: FieldStyleSlider(
    min: 0,
    max: 10,
    divisions: 10,
    activeColor: Colors.blue,
    inactiveColor: Colors.grey,
  ),
)
```

スタイルタイプ: `FieldStyleSlider` -- `min`、`max`、`divisions`、色、値表示などを設定できます。

<div id="range-slider-fields"></div>

### 範囲スライダーフィールド

``` dart
Field.rangeSlider("Price Range",
  style: FieldStyleRangeSlider(
    min: 0,
    max: 1000,
    divisions: 20,
    activeColor: Colors.blue,
    inactiveColor: Colors.grey,
  ),
)
```

`RangeValues` オブジェクトを返します。スタイルタイプ: `FieldStyleRangeSlider`

<div id="custom-fields"></div>

### カスタムフィールド

`Field.custom()` を使用して独自のステートフルウィジェットを提供します:

``` dart
Field.custom("My Field",
  child: MyCustomFieldWidget(),
)
```

`child` パラメータには `NyFieldStatefulWidget` を継承するウィジェットが必要です。これにより、フィールドのレンダリングと動作を完全に制御できます。

<div id="widget-fields"></div>

### ウィジェットフィールド

`Field.widget()` を使用して、フォームフィールドではない任意のウィジェットをフォーム内に埋め込みます:

``` dart
Field.widget(child: Divider())

Field.widget(child: Text("Section Header", style: TextStyle(fontSize: 18)))
```

ウィジェットフィールドはバリデーションやデータ収集に参加しません。純粋にレイアウト用です。


<div id="form-collection"></div>

## FormCollection

ピッカー、ラジオ、チップフィールドにはオプションとして `FormCollection` が必要です。`FormCollection` は、異なるオプション形式を処理するための統一インターフェースを提供します。

### FormCollection の作成

``` dart
// 文字列リストから（値とラベルは同じ）
FormCollection.from(["Red", "Green", "Blue"])

// 上記と同じ、明示的
FormCollection.fromArray(["Red", "Green", "Blue"])

// マップから（キー = 値、バリュー = ラベル）
FormCollection.fromMap({
  "us": "United States",
  "ca": "Canada",
})

// 構造化データから（API レスポンスに便利）
FormCollection.fromKeyValue([
  {"value": "en", "label": "English"},
  {"value": "es", "label": "Spanish"},
])
```

`FormCollection.from()` はデータ形式を自動検出し、適切なコンストラクタに委譲します。

### FormOption

`FormCollection` 内の各オプションは `value` と `label` プロパティを持つ `FormOption` です:

``` dart
FormOption option = FormOption(value: "us", label: "United States");
print(option.value); // "us"
print(option.label); // "United States"
```

### オプションのクエリ

``` dart
FormCollection options = FormCollection.fromMap({"us": "United States", "ca": "Canada"});

options.getByValue("us");          // FormOption(value: us, label: United States)
options.getLabelByValue("us");     // "United States"
options.containsValue("ca");      // true
options.searchByLabel("can");      // [FormOption(value: ca, label: Canada)]
options.values;                    // ["us", "ca"]
options.labels;                    // ["United States", "Canada"]
```


<div id="form-validation"></div>

## フォームバリデーション

`validator` パラメータと `FormValidator` を使用して、任意のフィールドにバリデーションを追加します:

``` dart
// 名前付きコンストラクタ
Field.email("Email", validator: FormValidator.email())

// チェーンルール
Field.text("Username",
  validator: FormValidator()
    .notEmpty()
    .minLength(3)
    .maxLength(20)
)

// 強度レベル付きパスワード
Field.password("Password",
  validator: FormValidator.password(strength: 2)
)

// ブール値バリデーション
Field.checkbox("Terms",
  validator: FormValidator.booleanTrue(message: "You must accept the terms")
)

// カスタムインラインバリデーション
Field.number("Age",
  validator: FormValidator.custom(
    message: "Age must be between 18 and 100",
    validate: (data) {
      int? age = int.tryParse(data.toString());
      return age != null && age >= 18 && age <= 100;
    },
  )
)
```

フォーム送信時、すべてのバリデーターがチェックされます。いずれかが失敗すると、最初のエラーメッセージのトーストエラーが表示され、`onFailure` コールバックが呼び出されます。

**参照:** 利用可能なバリデーターの完全なリストは [バリデーション](/docs/7.x/validation#validation-rules) を参照してください。


<div id="managing-form-data"></div>

## フォームデータの管理

<div id="initial-data"></div>

### 初期データ

フォームに初期データを設定する方法は 2 つあります。

**方法 1: フォームクラスで `init` getter をオーバーライド**

``` dart
class EditAccountForm extends NyFormWidget {
  EditAccountForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  Function()? get init => () async {
    final user = await api<ApiService>((request) => request.getUserData());

    return {
      "First Name": user?.firstName,
      "Last Name": user?.lastName,
    };
  };

  @override
  fields() => [
    Field.text("First Name"),
    Field.text("Last Name"),
  ];

  static NyFormActions get actions => const NyFormActions('EditAccountForm');
}
```

`init` getter は同期的な `Map` または非同期の `Future<Map>` のいずれかを返すことができます。キーは snake_case 正規化を使用してフィールド名にマッチングされるため、`"First Name"` はキー `"First Name"` を持つフィールドにマッピングされます。

#### init での `define()` の使用

`init` でフィールドに**オプション**（または値とオプションの両方）を設定する必要がある場合は `define()` ヘルパーを使用します。これは、API やその他の非同期ソースからオプションを取得するピッカー、チップ、ラジオフィールドに便利です。

``` dart
class CreatePostForm extends NyFormWidget {
  CreatePostForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  Function()? get init => () async {
    final categories = await api<ApiService>((request) => request.getCategories());

    return {
      "Title": "My Post",
      "Category": define(options: categories),
    };
  };

  @override
  fields() => [
    Field.text("Title"),
    Field.picker("Category", options: FormCollection.from([])),
  ];

  static NyFormActions get actions => const NyFormActions('CreatePostForm');
}
```

`define()` は 2 つの名前付きパラメータを受け取ります:

| パラメータ | 説明 |
|-----------|-------------|
| `value` | フィールドの初期値 |
| `options` | ピッカー、チップ、ラジオフィールドのオプション |

``` dart
// オプションのみ設定（初期値なし）
"Category": define(options: categories),

// 初期値のみ設定
"Price": define(value: "100"),

// 値とオプションの両方を設定
"Country": define(value: "us", options: countries),

// シンプルなフィールドにはプレーンな値も使用可能
"Name": "John",
```

`define()` に渡すオプションは `List`、`Map`、または `FormCollection` のいずれかです。適用時に自動的に `FormCollection` に変換されます。

**方法 2: フォームウィジェットに `initialData` を渡す**

``` dart
EditAccountForm(
  initialData: {
    "first_name": "John",
    "last_name": "Doe",
  },
)
```

<div id="setting-field-values"></div>

### フィールド値の設定

`NyFormActions` を使用してどこからでもフィールド値を設定します:

``` dart
// 単一フィールドの値を設定
EditAccountForm.actions.updateField("First Name", "Jane");
```

<div id="setting-field-options"></div>

### フィールドオプションの設定

ピッカー、チップ、ラジオフィールドのオプションを動的に更新します:

``` dart
EditAccountForm.actions.setOptions("Category", FormCollection.from(["New Option 1", "New Option 2"]));
```

<div id="reading-form-data"></div>

### フォームデータの読み取り

フォームデータは、フォーム送信時の `onSubmit` コールバック、またはリアルタイム更新用の `onChanged` コールバックを通じてアクセスします:

``` dart
EditAccountForm(
  onSubmit: (data) {
    // data は Map<String, dynamic>
    // {first_name: "Jane", last_name: "Doe", email: "jane@example.com"}
    print(data);
  },
  onChanged: (Field field, dynamic value) {
    print("${field.key} changed to: $value");
  },
)
```

<div id="clearing-data"></div>

### データのクリア

``` dart
// すべてのフィールドをクリア
EditAccountForm.actions.clear();

// 特定のフィールドをクリア
EditAccountForm.actions.clearField("First Name");
```


<div id="finding-and-updating-fields"></div>

### フィールドの更新

``` dart
// フィールド値を更新
EditAccountForm.actions.updateField("First Name", "Jane");

// フォーム UI を更新
EditAccountForm.actions.refresh();

// フォームフィールドを更新（fields() を再呼び出し）
EditAccountForm.actions.refreshForm();
```


<div id="submit-button"></div>

## 送信ボタン

フォーム構築時に `submitButton` と `onSubmit` コールバックを渡します:

``` dart
UserInfoForm(
  submitButton: Button.primary(text: "Submit"),
  onSubmit: (data) {
    print(data);
  },
  onFailure: (errors) {
    print(errors.first.rule.getMessage());
  },
)
```

`submitButton` はフォームフィールドの下に自動的に表示されます。組み込みボタンスタイルまたはカスタムウィジェットのいずれかを使用できます。

また、任意のウィジェットを `footer` として渡すことで送信ボタンとして使用することもできます:

``` dart
UserInfoForm(
  onSubmit: (data) {
    print(data);
  },
  footer: ElevatedButton(
    onPressed: () {
      UserInfoForm.actions.submit(
        onSuccess: (data) {
          print(data);
        },
      );
    },
    child: Text("Submit"),
  ),
)
```


<div id="form-layout"></div>

## フォームレイアウト

フィールドを `List` でラップすることで横並びに配置します:

``` dart
@override
fields() => [
  // 単一フィールド（全幅）
  Field.text("Title"),

  // 2 つのフィールドを 1 行に
  [
    Field.text("First Name"),
    Field.text("Last Name"),
  ],

  // 別の単一フィールド
  Field.textArea("Bio"),

  // スライダーと範囲スライダーを 1 行に
  [
    Field.slider("Rating", style: FieldStyleSlider(min: 0, max: 10)),
    Field.rangeSlider("Budget", style: FieldStyleRangeSlider(min: 0, max: 1000)),
  ],

  // 非フィールドウィジェットを埋め込み
  Field.widget(child: Divider()),

  Field.email("Email"),
];
```

`List` 内のフィールドは等しい `Expanded` 幅の `Row` でレンダリングされます。フィールド間のスペーシングは `NyFormWidget` の `crossAxisSpacing` パラメータで制御されます。


<div id="field-visibility"></div>

## フィールドの表示/非表示

`Field` の `hide()` と `show()` メソッドを使用して、プログラム的にフィールドの表示/非表示を切り替えます。フォームクラス内または `onChanged` コールバックを通じてフィールドにアクセスできます:

``` dart
// NyFormWidget サブクラスまたは onChanged コールバック内
Field nameField = ...;

// フィールドを非表示
nameField.hide();

// フィールドを表示
nameField.show();
```

非表示フィールドは UI にレンダリングされませんが、フォームのフィールドリストには存在し続けます。


<div id="field-styling"></div>

## フィールドスタイリング

各フィールドタイプには対応する `FieldStyle` サブクラスがあります:

| フィールドタイプ | スタイルクラス |
|------------|-------------|
| Text, Email, Password, Number, URL, TextArea, PhoneNumber, Currency, Mask, CapitalizeWords, CapitalizeSentences | `FieldStyleTextField` |
| Date, DateTime | `FieldStyleDateTimePicker` |
| Picker | `FieldStylePicker` |
| Checkbox | `FieldStyleCheckbox` |
| Switch Box | `FieldStyleSwitchBox` |
| Radio | `FieldStyleRadio` |
| Chip | `FieldStyleChip` |
| Slider | `FieldStyleSlider` |
| Range Slider | `FieldStyleRangeSlider` |

任意のフィールドの `style` パラメータにスタイルオブジェクトを渡します:

``` dart
Field.text("Name",
  style: FieldStyleTextField(
    filled: true,
    fillColor: Colors.grey.shade100,
    border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
    contentPadding: EdgeInsets.symmetric(horizontal: 16, vertical: 12),
    prefixIcon: Icon(Icons.person),
  ),
)

Field.slider("Rating",
  style: FieldStyleSlider(
    min: 0,
    max: 10,
    divisions: 10,
    activeColor: Colors.blue,
    showValue: true,
  ),
)

Field.chips("Tags",
  options: FormCollection.from(["Sale", "New", "Featured"]),
  style: FieldStyleChip(
    selectedColor: Colors.blue,
    checkmarkColor: Colors.white,
    spacing: 8.0,
    runSpacing: 8.0,
  ),
)
```


<div id="ny-form-widget-static-methods"></div>

## NyFormWidget 静的メソッド

`NyFormWidget` は、アプリのどこからでもフォーム名でフォームと対話するための静的メソッドを提供します:

| メソッド | 説明 |
|--------|-------------|
| `NyFormWidget.submit(name, onSuccess:, onFailure:, showToastError:)` | フォーム名でフォームを送信 |
| `NyFormWidget.stateRefresh(name)` | フォームの UI ステートを更新 |
| `NyFormWidget.stateSetValue(name, key, value)` | フォーム名でフィールド値を設定 |
| `NyFormWidget.stateSetOptions(name, key, options)` | フォーム名でフィールドオプションを設定 |
| `NyFormWidget.stateClearData(name)` | フォーム名で全フィールドをクリア |
| `NyFormWidget.stateRefreshForm(name)` | フォームフィールドを更新（`fields()` を再呼び出し） |

``` dart
// どこからでも "LoginForm" という名前のフォームを送信
NyFormWidget.submit("LoginForm", onSuccess: (data) {
  print(data);
});

// フィールド値をリモートで更新
NyFormWidget.stateSetValue("LoginForm", "Email", "new@email.com");

// すべてのフォームデータをクリア
NyFormWidget.stateClearData("LoginForm");
```

> **ヒント:** これらの静的メソッドを直接呼び出す代わりに、`NyFormActions`（下記参照）を使用することを推奨します -- より簡潔でエラーが少なくなります。


<div id="ny-form-widget-constructor-reference"></div>

## NyFormWidget コンストラクタリファレンス

`NyFormWidget` を継承する際に渡せるコンストラクタパラメータです:

``` dart
LoginForm(
  Key? key,
  double crossAxisSpacing = 10,  // 行内フィールド間の水平スペーシング
  double mainAxisSpacing = 10,   // フィールド間の垂直スペーシング
  Map<String, dynamic>? initialData, // フィールド初期値
  Function(Field field, dynamic value)? onChanged, // フィールド変更コールバック
  Widget? header,                // フォーム上部のウィジェット
  Widget? submitButton,          // 送信ボタンウィジェット
  Widget? footer,                // フォーム下部のウィジェット
  double headerSpacing = 10,     // ヘッダー後のスペーシング
  double submitButtonSpacing = 10, // 送信ボタン後のスペーシング
  double footerSpacing = 10,     // フッター前のスペーシング
  LoadingStyle? loadingStyle,    // ローディングインジケータースタイル
  bool locked = false,           // フォームを読み取り専用に設定
  Function(dynamic data)? onSubmit,   // バリデーション成功時にフォームデータで呼び出される
  Function(dynamic error)? onFailure, // バリデーション失敗時にエラーで呼び出される
)
```

`onChanged` コールバックは変更された `Field` とその新しい値を受け取ります:

``` dart
LoginForm(
  onChanged: (Field field, dynamic value) {
    print("${field.key} changed to: $value");
  },
)
```


<div id="ny-form-actions"></div>

## NyFormActions

`NyFormActions` は、アプリのどこからでもフォームと対話するための便利な方法を提供します。フォームクラスの静的 getter として定義します:

``` dart
class LoginForm extends NyFormWidget {
  LoginForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  fields() => [
    Field.email("Email", validator: FormValidator.email()),
    Field.password("Password", validator: FormValidator.password()),
  ];

  static NyFormActions get actions => const NyFormActions('LoginForm');
}
```

### 利用可能なアクション

| メソッド | 説明 |
|--------|-------------|
| `actions.updateField(key, value)` | フィールドの値を設定 |
| `actions.clearField(key)` | 特定のフィールドをクリア |
| `actions.clear()` | すべてのフィールドをクリア |
| `actions.refresh()` | フォームの UI ステートを更新 |
| `actions.refreshForm()` | `fields()` を再呼び出しして再構築 |
| `actions.setOptions(key, options)` | ピッカー/チップ/ラジオフィールドのオプションを設定 |
| `actions.submit(onSuccess:, onFailure:, showToastError:)` | バリデーション付きで送信 |

``` dart
// フィールド値を更新
LoginForm.actions.updateField("Email", "new@email.com");

// すべてのフォームデータをクリア
LoginForm.actions.clear();

// フォームを送信
LoginForm.actions.submit(
  onSuccess: (data) {
    print(data);
  },
);
```

### NyFormWidget オーバーライド

`NyFormWidget` サブクラスでオーバーライドできるメソッドです:

| オーバーライド | 説明 |
|----------|-------------|
| `fields()` | フォームフィールドを定義（必須） |
| `init` | 初期データを提供（同期または非同期） |
| `onChange(field, data)` | フィールド変更を内部的に処理 |


<div id="all-field-types-reference"></div>

## 全フィールドタイプリファレンス

| コンストラクタ | 主要パラメータ | 説明 |
|-------------|----------------|-------------|
| `Field.text()` | -- | 標準テキスト入力 |
| `Field.email()` | -- | キーボードタイプ付きメール入力 |
| `Field.password()` | `viewable` | オプションの表示トグル付きパスワード |
| `Field.number()` | `decimal` | 数値入力、オプションの小数 |
| `Field.currency()` | `currency`（必須） | 通貨フォーマット入力 |
| `Field.capitalizeWords()` | -- | タイトルケーステキスト入力 |
| `Field.capitalizeSentences()` | -- | センテンスケーステキスト入力 |
| `Field.textArea()` | -- | 複数行テキスト入力 |
| `Field.phoneNumber()` | -- | 自動フォーマット電話番号 |
| `Field.url()` | -- | キーボードタイプ付き URL 入力 |
| `Field.mask()` | `mask`（必須）, `match`, `maskReturnValue` | マスク付きテキスト入力 |
| `Field.date()` | -- | 日付ピッカー |
| `Field.datetime()` | `firstDate`, `lastDate`, `dateFormat`, `initialPickerDateTime` | 日付と時刻のピッカー |
| `Field.checkbox()` | -- | ブール値チェックボックス |
| `Field.switchBox()` | -- | ブール値トグルスイッチ |
| `Field.picker()` | `options`（必須 `FormCollection`） | リストからの単一選択 |
| `Field.radio()` | `options`（必須 `FormCollection`） | ラジオボタングループ |
| `Field.chips()` | `options`（必須 `FormCollection`） | 複数選択チップ |
| `Field.slider()` | -- | 単一値スライダー |
| `Field.rangeSlider()` | -- | 範囲値スライダー |
| `Field.custom()` | `child`（必須 `NyFieldStatefulWidget`） | カスタムステートフルウィジェット |
| `Field.widget()` | `child`（必須 `Widget`） | 任意のウィジェットを埋め込み（非フィールド） |
