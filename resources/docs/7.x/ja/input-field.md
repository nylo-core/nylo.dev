# InputField

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- [基本的な使い方](#basic-usage "基本的な使い方")
- [バリデーション](#validation "バリデーション")
- バリアント
    - [InputField.password](#password "InputField.password")
    - [InputField.emailAddress](#email-address "InputField.emailAddress")
    - [InputField.capitalizeWords](#capitalize-words "InputField.capitalizeWords")
- [入力マスキング](#masking "入力マスキング")
- [ヘッダーとフッター](#header-footer "ヘッダーとフッター")
- [クリア可能な入力](#clearable "クリア可能な入力")
- [状態管理](#state-management "状態管理")
- [パラメータ](#parameters "パラメータ")


<div id="introduction"></div>

## はじめに

**InputField** ウィジェットは {{ config('app.name') }} の拡張テキストフィールドで、以下の機能を組み込んでいます:

- カスタマイズ可能なエラーメッセージ付きバリデーション
- パスワード表示切り替え
- 入力マスキング（電話番号、クレジットカードなど）
- ヘッダーとフッターウィジェット
- クリア可能な入力
- 状態管理との統合
- 開発用ダミーデータ

<div id="basic-usage"></div>

## 基本的な使い方

``` dart
final TextEditingController _controller = TextEditingController();

@override
Widget build(BuildContext context) {
  return Scaffold(
    body: SafeArea(
      child: Padding(
        padding: EdgeInsets.all(16),
        child: InputField(
          controller: _controller,
          labelText: "Username",
          hintText: "Enter your username",
        ),
      ),
    ),
  );
}
```

<div id="validation"></div>

## バリデーション

`formValidator` パラメータを使用してバリデーションルールを追加します:

``` dart
InputField(
  controller: _controller,
  labelText: "Email",
  formValidator: FormValidator.rule("email|not_empty"),
  validateOnFocusChange: true,
)
```

ユーザーがフィールドからフォーカスを外すとバリデーションが実行されます。

### カスタムバリデーションハンドラー

バリデーションエラーをプログラムで処理します:

``` dart
InputField(
  controller: _controller,
  labelText: "Username",
  formValidator: FormValidator.rule("not_empty|min:3"),
  handleValidationError: (FormValidationResult result) {
    if (!result.isValid) {
      print("Error: ${result.getFirstErrorMessage()}");
    }
  },
)
```

利用可能なすべてのバリデーションルールについては、[バリデーション](/docs/7.x/validation)のドキュメントを参照してください。

<div id="password"></div>

## InputField.password

隠しテキストと表示切り替え付きの設定済みパスワードフィールド:

``` dart
final TextEditingController _passwordController = TextEditingController();

InputField.password(
  controller: _passwordController,
  labelText: "Password",
  formValidator: FormValidator.rule("not_empty|min:8"),
)
```

### パスワード表示のカスタマイズ

``` dart
InputField.password(
  controller: _passwordController,
  passwordVisible: true, // 表示/非表示切り替えアイコン
  passwordViewable: true, // ユーザーが表示切り替え可能に
)
```

<div id="email-address"></div>

## InputField.emailAddress

メールキーボードとオートフォーカス付きの設定済みメールフィールド:

``` dart
final TextEditingController _emailController = TextEditingController();

InputField.emailAddress(
  controller: _emailController,
  formValidator: FormValidator.rule("email|not_empty"),
)
```

<div id="capitalize-words"></div>

## InputField.capitalizeWords

各単語の最初の文字を自動的に大文字にします:

``` dart
final TextEditingController _nameController = TextEditingController();

InputField.capitalizeWords(
  controller: _nameController,
  labelText: "Full Name",
)
```

<div id="masking"></div>

## 入力マスキング

電話番号やクレジットカードなどのフォーマットされたデータに入力マスクを適用します:

``` dart
// 電話番号マスク
InputField(
  controller: _phoneController,
  labelText: "Phone Number",
  mask: "(###) ###-####",
  maskMatch: r'[0-9]',
  maskedReturnValue: false, // マスクなしの値を返す: 1234567890
)

// クレジットカードマスク
InputField(
  controller: _cardController,
  labelText: "Card Number",
  mask: "#### #### #### ####",
  maskMatch: r'[0-9]',
  maskedReturnValue: true, // マスク付きの値を返す: 1234 5678 9012 3456
)
```

| パラメータ | 説明 |
|-----------|-------------|
| `mask` | `#` をプレースホルダーとして使用するマスクパターン |
| `maskMatch` | 有効な入力文字の正規表現パターン |
| `maskedReturnValue` | true の場合フォーマット済みの値を返し、false の場合生の入力を返す |

<div id="header-footer"></div>

## ヘッダーとフッター

入力フィールドの上または下にウィジェットを追加します:

``` dart
InputField(
  controller: _controller,
  labelText: "Bio",
  header: Text(
    "Tell us about yourself",
    style: TextStyle(fontWeight: FontWeight.bold),
  ),
  footer: Text(
    "Max 200 characters",
    style: TextStyle(color: Colors.grey, fontSize: 12),
  ),
  maxLength: 200,
)
```

<div id="clearable"></div>

## クリア可能な入力

フィールドを素早く空にするクリアボタンを追加します:

``` dart
InputField(
  controller: _searchController,
  labelText: "Search",
  clearable: true,
  clearIcon: Icon(Icons.close, size: 20), // カスタムクリアアイコン
  onChanged: (value) {
    // 検索を処理
  },
)
```

<div id="state-management"></div>

## 状態管理

プログラムで制御するために入力フィールドに状態名を付けます:

``` dart
InputField(
  controller: _controller,
  labelText: "Username",
  stateName: "username_field",
)
```

### 状態アクション

``` dart
// フィールドをクリア
InputField.stateActions("username_field").clear();

// 値を設定
updateState("username_field", data: {
  "action": "setValue",
  "value": "new_value"
});
```

<div id="parameters"></div>

## パラメータ

### 共通パラメータ

| パラメータ | 型 | デフォルト | 説明 |
|-----------|------|---------|-------------|
| `controller` | `TextEditingController` | 必須 | 編集中のテキストを制御 |
| `labelText` | `String?` | - | フィールド上部に表示されるラベル |
| `hintText` | `String?` | - | プレースホルダーテキスト |
| `formValidator` | `FormValidator?` | - | バリデーションルール |
| `validateOnFocusChange` | `bool` | `true` | フォーカス変更時にバリデーション |
| `obscureText` | `bool` | `false` | 入力を非表示（パスワード用） |
| `keyboardType` | `TextInputType` | `text` | キーボードタイプ |
| `autoFocus` | `bool` | `false` | ビルド時に自動フォーカス |
| `readOnly` | `bool` | `false` | フィールドを読み取り専用に |
| `enabled` | `bool?` | - | フィールドの有効/無効 |
| `maxLines` | `int?` | `1` | 最大行数 |
| `maxLength` | `int?` | - | 最大文字数 |

### スタイリングパラメータ

| パラメータ | 型 | 説明 |
|-----------|------|-------------|
| `backgroundColor` | `Color?` | フィールドの背景色 |
| `borderRadius` | `BorderRadius?` | 角丸 |
| `border` | `InputBorder?` | デフォルトのボーダー |
| `focusedBorder` | `InputBorder?` | フォーカス時のボーダー |
| `enabledBorder` | `InputBorder?` | 有効時のボーダー |
| `contentPadding` | `EdgeInsetsGeometry?` | 内部パディング |
| `style` | `TextStyle?` | テキストスタイル |
| `labelStyle` | `TextStyle?` | ラベルテキストスタイル |
| `hintStyle` | `TextStyle?` | ヒントテキストスタイル |
| `prefixIcon` | `Widget?` | 入力前のアイコン |

### マスキングパラメータ

| パラメータ | 型 | 説明 |
|-----------|------|-------------|
| `mask` | `String?` | マスクパターン（例: "###-####"） |
| `maskMatch` | `String?` | 有効な文字の正規表現 |
| `maskedReturnValue` | `bool?` | マスク付きまたは生の値を返す |

### 機能パラメータ

| パラメータ | 型 | 説明 |
|-----------|------|-------------|
| `header` | `Widget?` | フィールド上部のウィジェット |
| `footer` | `Widget?` | フィールド下部のウィジェット |
| `clearable` | `bool?` | クリアボタンを表示 |
| `clearIcon` | `Widget?` | カスタムクリアアイコン |
| `passwordVisible` | `bool?` | パスワード切り替えを表示 |
| `passwordViewable` | `bool?` | パスワード表示切り替えを許可 |
| `dummyData` | `String?` | 開発用のフェイクデータ |
| `stateName` | `String?` | 状態管理用の名前 |
| `onChanged` | `Function(String)?` | 値変更時に呼び出される |
