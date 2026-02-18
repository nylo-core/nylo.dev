# バリデーション

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- 基本
  - [check() によるデータの検証](#validating-data "check() によるデータの検証")
  - [バリデーション結果](#validation-results "バリデーション結果")
- FormValidator
  - [FormValidator の使用](#using-form-validator "FormValidator の使用")
  - [FormValidator の名前付きコンストラクタ](#form-validator-named-constructors "FormValidator の名前付きコンストラクタ")
  - [バリデーションルールのチェーン](#chaining-validation-rules "バリデーションルールのチェーン")
  - [カスタムバリデーション](#custom-validation "カスタムバリデーション")
  - [フィールドでの FormValidator の使用](#form-validator-with-fields "フィールドでの FormValidator の使用")
- [利用可能なバリデーションルール](#validation-rules "利用可能なバリデーションルール")
- [カスタムバリデーションルールの作成](#creating-custom-validation-rules "カスタムバリデーションルールの作成")

<div id="introduction"></div>

## はじめに

{{ config('app.name') }} v7 は `FormValidator` クラスを中心に構築されたバリデーションシステムを提供します。ページ内で `check()` メソッドを使用してデータを検証するか、`FormValidator` を直接使用してスタンドアロンおよびフィールドレベルのバリデーションを行えます。

``` dart
// NyPage/NyState で check() を使用してデータを検証
check((validate) {
  validate.that('user@example.com').email();
  validate.that('Anthony')
              .capitalized()
              .maxLength(50);
}, onSuccess: () {
  print("All validations passed!");
});

// フォームフィールドでの FormValidator
Field.text("Email", validator: FormValidator.email())
```

<div id="validating-data"></div>

## check() によるデータの検証

任意の `NyPage` 内で、`check()` メソッドを使用してデータを検証します。バリデーターのリストを受け取るコールバックを受け付けます。`.that()` を使用してデータを追加し、ルールをチェーンします:

``` dart
class _RegisterPageState extends NyPage<RegisterPage> {

  void handleForm() {
    check((validate) {
      validate.that(_emailController.text, label: "Email").email();
      validate.that(_passwordController.text, label: "Password")
          .notEmpty()
          .password(strength: 2);
    }, onSuccess: () {
      // すべてのバリデーションに合格
      _submitForm();
    }, onValidationError: (FormValidationResponseBag bag) {
      // バリデーション失敗
      print(bag.firstErrorMessage);
    });
  }
}
```

### check() の動作

1. `check()` は空の `List<FormValidator>` を作成します
2. コールバックで `.that(data)` を使用して、データ付きの新しい `FormValidator` をリストに追加します
3. 各 `.that()` はルールをチェーンできる `FormValidator` を返します
4. コールバック後、リスト内のすべてのバリデーターがチェックされます
5. 結果は `FormValidationResponseBag` に収集されます

### 複数フィールドの検証

``` dart
check((validate) {
  validate.that(_nameController.text, label: "Name").notEmpty().capitalized();
  validate.that(_emailController.text, label: "Email").email();
  validate.that(_phoneController.text, label: "Phone").phoneNumberUs();
  validate.that(_ageController.text, label: "Age").numeric().minValue(18);
}, onSuccess: () {
  _submitForm();
});
```

オプションの `label` パラメータは、エラーメッセージで使用される人間が読みやすい名前を設定します（例: "The Email must be a valid email address."）。

<div id="validation-results"></div>

## バリデーション結果

`check()` メソッドは `FormValidationResponseBag`（`List<FormValidationResult>`）を返し、直接検査することもできます:

``` dart
FormValidationResponseBag bag = check((validate) {
  validate.that(_emailController.text, label: "Email").email();
  validate.that(_passwordController.text, label: "Password")
      .password(strength: 1);
});

if (bag.isValid) {
  print("All valid!");
} else {
  // 最初のエラーメッセージを取得
  String? error = bag.firstErrorMessage;
  print(error);

  // 失敗したすべての結果を取得
  List<FormValidationResult> errors = bag.validationErrors;

  // 成功したすべての結果を取得
  List<FormValidationResult> successes = bag.validationSuccess;
}
```

### FormValidationResult

各 `FormValidationResult` は、単一の値の検証結果を表します:

``` dart
FormValidator validator = FormValidator(data: "test@email.com")
    .email()
    .maxLength(50);

FormValidationResult result = validator.check();

if (result.isValid) {
  print("Valid!");
} else {
  // 最初のエラーメッセージ
  String? message = result.getFirstErrorMessage();

  // すべてのエラーメッセージ
  List<String> messages = result.errorMessages();

  // エラーレスポンス
  List<FormValidationError> errors = result.errorResponses;
}
```

<div id="using-form-validator"></div>

## FormValidator の使用

`FormValidator` はスタンドアロンまたはフォームフィールドと共に使用できます。

### スタンドアロンでの使用

``` dart
// 名前付きコンストラクタの使用
FormValidator validator = FormValidator.email();
FormValidationResult result = validator.check("user@example.com");

if (result.isValid) {
  print("Email is valid");
} else {
  String? errorMessage = result.getFirstErrorMessage();
  print("Error: $errorMessage");
}
```

### コンストラクタでデータを指定

``` dart
FormValidator validator = FormValidator(data: "user@example.com", attribute: "Email")
    .email()
    .maxLength(50);

FormValidationResult result = validator.check();
print(result.isValid); // true
```

<div id="form-validator-named-constructors"></div>

## FormValidator の名前付きコンストラクタ

{{ config('app.name') }} v7 は一般的なバリデーション用の名前付きコンストラクタを提供します:

``` dart
// メールバリデーション
FormValidator.email(message: "Please enter a valid email")

// パスワードバリデーション（strength 1 または 2）
FormValidator.password(strength: 1)
FormValidator.password(strength: 2, message: "Password too weak")

// 電話番号
FormValidator.phoneNumberUk()
FormValidator.phoneNumberUs()

// URL バリデーション
FormValidator.url()

// 長さの制約
FormValidator.minLength(5, message: "Too short")
FormValidator.maxLength(100, message: "Too long")

// 値の制約
FormValidator.minValue(18, message: "Must be 18+")
FormValidator.maxValue(100)

// サイズの制約（リスト/文字列用）
FormValidator.minSize(2, message: "Select at least 2")
FormValidator.maxSize(5)

// 空でないこと
FormValidator.notEmpty(message: "This field is required")

// 値を含む
FormValidator.contains(['option1', 'option2'])

// 開始/終了文字列
FormValidator.beginsWith("https://")
FormValidator.endsWith(".com")

// ブーリアンチェック
FormValidator.booleanTrue(message: "Must accept terms")
FormValidator.booleanFalse()

// 数値
FormValidator.numeric()

// 日付バリデーション
FormValidator.date()
FormValidator.dateInPast()
FormValidator.dateInFuture()
FormValidator.dateAgeIsOlder(18, message: "Must be 18+")
FormValidator.dateAgeIsYounger(65)

// テキストケース
FormValidator.uppercase()
FormValidator.lowercase()
FormValidator.capitalized()

// 住所フォーマット
FormValidator.zipcodeUs()
FormValidator.postcodeUk()

// 正規表現パターン
FormValidator.regex(r'^[A-Z]{3}\d{4}$', message: "Invalid format")

// カスタムバリデーション
FormValidator.custom(
  message: "Invalid value",
  validate: (data) => data != null,
)
```

<div id="chaining-validation-rules"></div>

## バリデーションルールのチェーン

インスタンスメソッドを使用して複数のルールを流暢にチェーンします。各メソッドは `FormValidator` を返すため、ルールを積み重ねることができます:

``` dart
FormValidator validator = FormValidator()
    .notEmpty()
    .email()
    .maxLength(50);

FormValidationResult result = validator.check("user@example.com");

if (!result.isValid) {
  List<String> errors = result.errorMessages();
  print(errors);
}
```

すべての名前付きコンストラクタには対応するチェーン可能なインスタンスメソッドがあります:

``` dart
FormValidator()
    .notEmpty(message: "Required")
    .email(message: "Invalid email")
    .minLength(5, message: "Too short")
    .maxLength(100, message: "Too long")
    .beginsWith("user", message: "Must start with 'user'")
    .lowercase(message: "Must be lowercase")
```

<div id="custom-validation"></div>

## カスタムバリデーション

### カスタムルール（インライン）

`.custom()` を使用してインラインバリデーションロジックを追加します:

``` dart
FormValidator.custom(
  message: "Username already taken",
  validate: (data) {
    // 有効な場合は true、無効な場合は false を返す
    return !_takenUsernames.contains(data);
  },
)
```

または他のルールとチェーンします:

``` dart
FormValidator()
    .notEmpty()
    .custom(
      message: "Must start with a letter",
      validate: (data) => RegExp(r'^[a-zA-Z]').hasMatch(data.toString()),
    )
```

### 等値バリデーション

値が別の値と一致するかチェックします:

``` dart
FormValidator()
    .notEmpty()
    .equals(
      _passwordController.text,
      message: "Passwords must match",
    )
```

<div id="form-validator-with-fields"></div>

## フィールドでの FormValidator の使用

`FormValidator` はフォームの `Field` ウィジェットと統合します。`validator` パラメータにバリデーターを渡します:

``` dart
class RegisterForm extends NyFormData {
  RegisterForm({String? name}) : super(name ?? "register");

  @override
  fields() => [
        Field.text(
          "Name",
          autofocus: true,
          validator: FormValidator.notEmpty(),
        ),
        Field.email("Email", validator: FormValidator.email()),
        Field.password(
          "Password",
          validator: FormValidator.password(strength: 1),
        ),
      ];
}
```

フィールドでチェーンされたバリデーターも使用できます:

``` dart
Field.text(
  "Username",
  validator: FormValidator()
      .notEmpty(message: "Username is required")
      .minLength(3, message: "At least 3 characters")
      .maxLength(20, message: "At most 20 characters"),
)

Field.slider(
  "Rating",
  validator: FormValidator.minValue(4, message: "Rating must be at least 4"),
)
```

<div id="validation-rules"></div>

## 利用可能なバリデーションルール

`FormValidator` で利用可能なすべてのルール（名前付きコンストラクタとチェーン可能なメソッドの両方）:

| ルール | 名前付きコンストラクタ | チェーン可能なメソッド | 説明 |
|------|-------------------|------------------|-------------|
| Email | `FormValidator.email()` | `.email()` | メール形式を検証 |
| Password | `FormValidator.password(strength: 1)` | `.password(strength: 1)` | Strength 1: 8文字以上、大文字1つ、数字1つ。Strength 2: + 特殊文字1つ |
| Not Empty | `FormValidator.notEmpty()` | `.notEmpty()` | 空であってはならない |
| Min Length | `FormValidator.minLength(5)` | `.minLength(5)` | 最小文字列長 |
| Max Length | `FormValidator.maxLength(100)` | `.maxLength(100)` | 最大文字列長 |
| Min Value | `FormValidator.minValue(18)` | `.minValue(18)` | 最小数値（文字列長、リスト長、マップ長にも対応） |
| Max Value | `FormValidator.maxValue(100)` | `.maxValue(100)` | 最大数値 |
| Min Size | `FormValidator.minSize(2)` | `.minSize(2)` | リスト/文字列の最小サイズ |
| Max Size | `FormValidator.maxSize(5)` | `.maxSize(5)` | リスト/文字列の最大サイズ |
| Contains | `FormValidator.contains(['a', 'b'])` | `.contains(['a', 'b'])` | 値が指定された値のいずれかを含む必要がある |
| Begins With | `FormValidator.beginsWith("https://")` | `.beginsWith("https://")` | 文字列がプレフィックスで始まる必要がある |
| Ends With | `FormValidator.endsWith(".com")` | `.endsWith(".com")` | 文字列がサフィックスで終わる必要がある |
| URL | `FormValidator.url()` | `.url()` | URL 形式を検証 |
| Numeric | `FormValidator.numeric()` | `.numeric()` | 数値である必要がある |
| Boolean True | `FormValidator.booleanTrue()` | `.booleanTrue()` | `true` である必要がある |
| Boolean False | `FormValidator.booleanFalse()` | `.booleanFalse()` | `false` である必要がある |
| Date | `FormValidator.date()` | `.date()` | 有効な日付である必要がある |
| Date In Past | `FormValidator.dateInPast()` | `.dateInPast()` | 日付が過去である必要がある |
| Date In Future | `FormValidator.dateInFuture()` | `.dateInFuture()` | 日付が未来である必要がある |
| Age Is Older | `FormValidator.dateAgeIsOlder(18)` | `.dateAgeIsOlder(18)` | 年齢が N 歳より上である必要がある |
| Age Is Younger | `FormValidator.dateAgeIsYounger(65)` | `.dateAgeIsYounger(65)` | 年齢が N 歳より下である必要がある |
| Capitalized | `FormValidator.capitalized()` | `.capitalized()` | 最初の文字が大文字である必要がある |
| Lowercase | `FormValidator.lowercase()` | `.lowercase()` | すべての文字が小文字である必要がある |
| Uppercase | `FormValidator.uppercase()` | `.uppercase()` | すべての文字が大文字である必要がある |
| Phone US | `FormValidator.phoneNumberUs()` | `.phoneNumberUs()` | 米国の電話番号形式 |
| Phone UK | `FormValidator.phoneNumberUk()` | `.phoneNumberUk()` | 英国の電話番号形式 |
| Zipcode US | `FormValidator.zipcodeUs()` | `.zipcodeUs()` | 米国の郵便番号形式 |
| Postcode UK | `FormValidator.postcodeUk()` | `.postcodeUk()` | 英国の郵便番号形式 |
| Regex | `FormValidator.regex(r'pattern')` | `.regex(r'pattern')` | 正規表現パターンに一致する必要がある |
| Equals | -- | `.equals(otherValue)` | 別の値と等しい必要がある |
| Custom | `FormValidator.custom(validate: fn)` | `.custom(validate: fn)` | カスタムバリデーション関数 |

すべてのルールは、エラーメッセージをカスタマイズするためのオプションの `message` パラメータを受け付けます。

<div id="creating-custom-validation-rules"></div>

## カスタムバリデーションルールの作成

再利用可能なバリデーションルールを作成するには、`FormRule` クラスを継承します:

``` dart
class FormRuleUsername extends FormRule {
  @override
  String? rule = "username";

  @override
  String? message = "The @{{attribute}} must be a valid username.";

  FormRuleUsername({String? message}) {
    if (message != null) {
      this.message = message;
    }
  }

  @override
  bool validate(data) {
    if (data is! String) return false;
    // ユーザー名: 英数字、アンダースコア、3〜20文字
    return RegExp(r'^[a-zA-Z0-9_]{3,20}$').hasMatch(data);
  }
}
```

`message` 内の `@{{attribute}}` はプレースホルダーとして使用され、実行時にフィールドのラベルに置き換えられます。

### カスタム FormRule の使用

`FormValidator.rule()` を使用してカスタムルールを `FormValidator` に追加します:

``` dart
FormValidator validator = FormValidator.rule([
  FormRuleNotEmpty(),
  FormRuleUsername(),
]);

FormValidationResult result = validator.check("my_username");
```

またはクラスを作成せずに一回限りのルールには `.custom()` メソッドを使用します:

``` dart
FormValidator()
    .notEmpty()
    .custom(
      message: "Username must start with a letter",
      validate: (data) => RegExp(r'^[a-zA-Z]').hasMatch(data.toString()),
    )
```
