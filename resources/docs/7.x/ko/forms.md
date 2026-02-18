# Forms

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- 시작하기
  - [폼 생성](#creating-forms "폼 생성")
  - [폼 표시](#displaying-a-form "폼 표시")
  - [폼 제출](#submitting-a-form "폼 제출")
- 필드 유형
  - [텍스트 필드](#text-fields "텍스트 필드")
  - [숫자 필드](#number-fields "숫자 필드")
  - [비밀번호 필드](#password-fields "비밀번호 필드")
  - [이메일 필드](#email-fields "이메일 필드")
  - [URL 필드](#url-fields "URL 필드")
  - [텍스트 영역 필드](#text-area-fields "텍스트 영역 필드")
  - [전화번호 필드](#phone-number-fields "전화번호 필드")
  - [단어 대문자화](#capitalize-words-fields "단어 대문자화")
  - [문장 대문자화](#capitalize-sentences-fields "문장 대문자화")
  - [날짜 필드](#date-fields "날짜 필드")
  - [날짜시간 필드](#datetime-fields "날짜시간 필드")
  - [마스크 입력 필드](#masked-input-fields "마스크 입력 필드")
  - [통화 필드](#currency-fields "통화 필드")
  - [체크박스 필드](#checkbox-fields "체크박스 필드")
  - [스위치 박스 필드](#switch-box-fields "스위치 박스 필드")
  - [Picker 필드](#picker-fields "Picker 필드")
  - [라디오 필드](#radio-fields "라디오 필드")
  - [Chip 필드](#chip-fields "Chip 필드")
  - [슬라이더 필드](#slider-fields "슬라이더 필드")
  - [범위 슬라이더 필드](#range-slider-fields "범위 슬라이더 필드")
  - [커스텀 필드](#custom-fields "커스텀 필드")
  - [위젯 필드](#widget-fields "위젯 필드")
- [FormCollection](#form-collection "FormCollection")
- [폼 유효성 검사](#form-validation "폼 유효성 검사")
- [폼 데이터 관리](#managing-form-data "폼 데이터 관리")
  - [초기 데이터](#initial-data "초기 데이터")
  - [필드 값 설정](#setting-field-values "필드 값 설정")
  - [필드 옵션 설정](#setting-field-options "필드 옵션 설정")
  - [폼 데이터 읽기](#reading-form-data "폼 데이터 읽기")
  - [데이터 지우기](#clearing-data "데이터 지우기")
  - [필드 업데이트](#finding-and-updating-fields "필드 업데이트")
- [제출 버튼](#submit-button "제출 버튼")
- [폼 레이아웃](#form-layout "폼 레이아웃")
- [필드 가시성](#field-visibility "필드 가시성")
- [필드 스타일링](#field-styling "필드 스타일링")
- [NyFormWidget 정적 메서드](#ny-form-widget-static-methods "NyFormWidget 정적 메서드")
- [NyFormWidget 생성자 참조](#ny-form-widget-constructor-reference "NyFormWidget 생성자 참조")
- [NyFormActions](#ny-form-actions "NyFormActions")
- [전체 필드 유형 참조](#all-field-types-reference "전체 필드 유형 참조")

<div id="introduction"></div>

## 소개

{{ config('app.name') }} v7은 `NyFormWidget`을 중심으로 구축된 폼 시스템을 제공합니다. 폼 클래스가 `NyFormWidget`을 확장하며 그 자체가 위젯이므로 별도의 래퍼가 필요 없습니다. 폼은 내장 유효성 검사, 다양한 필드 유형, 스타일링, 데이터 관리를 지원합니다.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// 1. Define a form
class LoginForm extends NyFormWidget {
  LoginForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  fields() => [
    Field.email("Email", validator: FormValidator.email()),
    Field.password("Password", validator: FormValidator.password()),
  ];

  static NyFormActions get actions => const NyFormActions('LoginForm');
}

// 2. Display and submit it
LoginForm(
  submitButton: Button.primary(text: "Login"),
  onSubmit: (data) {
    print(data); // {email: "...", password: "..."}
  },
)
```


<div id="creating-forms"></div>

## 폼 생성

Metro CLI를 사용하여 새 폼을 생성합니다:

``` bash
metro make:form LoginForm
```

`lib/app/forms/login_form.dart`가 생성됩니다:

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

폼은 `NyFormWidget`을 확장하고 `fields()` 메서드를 오버라이드하여 폼 필드를 정의합니다. 각 필드는 `Field.text()`, `Field.email()`, `Field.password()`와 같은 명명된 생성자를 사용합니다. `static NyFormActions get actions` getter는 앱 어디에서나 폼과 상호작용하는 편리한 방법을 제공합니다.


<div id="displaying-a-form"></div>

## 폼 표시

폼 클래스가 `NyFormWidget`을 확장하므로 그 자체가 위젯입니다. 위젯 트리에서 직접 사용합니다:

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

## 폼 제출

폼을 제출하는 세 가지 방법이 있습니다:

### onSubmit과 submitButton 사용

폼을 생성할 때 `onSubmit`과 `submitButton`을 전달합니다. {{ config('app.name') }}은 제출 버튼으로 작동하는 미리 만들어진 버튼을 제공합니다:

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

사용 가능한 버튼 스타일: `Button.primary`, `Button.secondary`, `Button.outlined`, `Button.textOnly`, `Button.icon`, `Button.gradient`, `Button.rounded`, `Button.transparency`.

### NyFormActions 사용

`actions` getter를 사용하여 어디에서나 제출합니다:

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

### NyFormWidget.submit() 정적 메서드 사용

이름으로 폼을 어디에서나 제출합니다:

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

제출 시 폼은 모든 필드를 유효성 검사합니다. 유효하면 필드 데이터의 `Map<String, dynamic>`과 함께 `onSuccess`가 호출됩니다 (키는 필드 이름의 snake_case 버전). 유효하지 않으면 기본적으로 Toast 에러가 표시되고 제공된 경우 `onFailure`가 호출됩니다.


<div id="field-types"></div>

## 필드 유형

{{ config('app.name') }} v7은 `Field` 클래스의 명명된 생성자를 통해 22개의 필드 유형을 제공합니다. 모든 필드 생성자는 다음과 같은 공통 매개변수를 공유합니다:

| 매개변수 | 타입 | 기본값 | 설명 |
|-----------|------|---------|-------------|
| `key` | `String` | 필수 | 필드 식별자 (위치 매개변수) |
| `label` | `String?` | `null` | 커스텀 표시 라벨 (기본값: 키를 타이틀 케이스로 변환) |
| `value` | `dynamic` | `null` | 초기값 |
| `validator` | `FormValidator?` | `null` | 유효성 검사 규칙 |
| `autofocus` | `bool` | `false` | 로드 시 자동 포커스 |
| `dummyData` | `String?` | `null` | 테스트/개발 데이터 |
| `header` | `Widget?` | `null` | 필드 위에 표시되는 위젯 |
| `footer` | `Widget?` | `null` | 필드 아래에 표시되는 위젯 |
| `titleStyle` | `TextStyle?` | `null` | 커스텀 라벨 텍스트 스타일 |
| `hidden` | `bool` | `false` | 필드 숨기기 |
| `readOnly` | `bool?` | `null` | 필드를 읽기 전용으로 설정 |
| `style` | `FieldStyle?` | 다양 | 필드별 스타일 구성 |
| `onChanged` | `Function(dynamic)?` | `null` | 값 변경 콜백 |

<div id="text-fields"></div>

### 텍스트 필드

``` dart
Field.text("Name")

Field.text("Name",
  value: "John",
  validator: FormValidator.notEmpty(),
  autofocus: true,
)
```

스타일 타입: `FieldStyleTextField`

<div id="number-fields"></div>

### 숫자 필드

``` dart
Field.number("Age")

// Decimal numbers
Field.number("Score", decimal: true)
```

`decimal` 매개변수는 소수점 입력 허용 여부를 제어합니다. 스타일 타입: `FieldStyleTextField`

<div id="password-fields"></div>

### 비밀번호 필드

``` dart
Field.password("Password")

// With visibility toggle
Field.password("Password", viewable: true)
```

`viewable` 매개변수는 표시/숨기기 토글을 추가합니다. 스타일 타입: `FieldStyleTextField`

<div id="email-fields"></div>

### 이메일 필드

``` dart
Field.email("Email", validator: FormValidator.email())
```

자동으로 이메일 키보드 유형을 설정하고 공백을 필터링합니다. 스타일 타입: `FieldStyleTextField`

<div id="url-fields"></div>

### URL 필드

``` dart
Field.url("Website", validator: FormValidator.url())
```

URL 키보드 유형을 설정합니다. 스타일 타입: `FieldStyleTextField`

<div id="text-area-fields"></div>

### 텍스트 영역 필드

``` dart
Field.textArea("Description")
```

여러 줄 텍스트 입력입니다. 스타일 타입: `FieldStyleTextField`

<div id="phone-number-fields"></div>

### 전화번호 필드

``` dart
Field.phoneNumber("Mobile Phone")
```

전화번호 입력을 자동으로 포맷합니다. 스타일 타입: `FieldStyleTextField`

<div id="capitalize-words-fields"></div>

### 단어 대문자화

``` dart
Field.capitalizeWords("Full Name")
```

각 단어의 첫 글자를 대문자로 변환합니다. 스타일 타입: `FieldStyleTextField`

<div id="capitalize-sentences-fields"></div>

### 문장 대문자화

``` dart
Field.capitalizeSentences("Bio")
```

각 문장의 첫 글자를 대문자로 변환합니다. 스타일 타입: `FieldStyleTextField`

<div id="date-fields"></div>

### 날짜 필드

``` dart
Field.date("Birthday")

Field.date("Birthday",
  dummyData: "1990-01-01",
  style: FieldStyleDateTimePicker(
    firstDate: DateTime(1900),
    lastDate: DateTime.now(),
  ),
)

// Disable the clear button
Field.date("Birthday",
  style: FieldStyleDateTimePicker(
    canClear: false,
  ),
)

// Custom clear icon
Field.date("Birthday",
  style: FieldStyleDateTimePicker(
    clearIconData: Icons.close,
  ),
)
```

날짜 선택기를 엽니다. 기본적으로 필드에는 사용자가 값을 리셋할 수 있는 지우기 버튼이 표시됩니다. `canClear: false`로 숨기거나 `clearIconData`로 아이콘을 변경할 수 있습니다. 스타일 타입: `FieldStyleDateTimePicker`

<div id="datetime-fields"></div>

### 날짜시간 필드

``` dart
Field.datetime("Check in Date")

Field.datetime("Appointment",
  firstDate: DateTime(2025),
  lastDate: DateTime(2030),
  dateFormat: DateFormat('yyyy-MM-dd HH:mm'),
  initialPickerDateTime: DateTime.now(),
)
```

날짜 및 시간 선택기를 엽니다. `firstDate`, `lastDate`, `dateFormat`, `initialPickerDateTime`을 최상위 매개변수로 직접 설정할 수 있습니다. 스타일 타입: `FieldStyleDateTimePicker`

<div id="masked-input-fields"></div>

### 마스크 입력 필드

``` dart
Field.mask("Phone", mask: "(###) ###-####")

Field.mask("Credit Card", mask: "#### #### #### ####")

Field.mask("Custom Code",
  mask: "AA-####",
  match: r'[\w\d]',
  maskReturnValue: true, // Returns the formatted value
)
```

마스크의 `#` 문자는 사용자 입력으로 대체됩니다. `match`를 사용하여 허용되는 문자를 제어합니다. `maskReturnValue`가 `true`이면 반환 값에 마스크 포맷이 포함됩니다.

<div id="currency-fields"></div>

### 통화 필드

``` dart
Field.currency("Price", currency: "usd")
```

`currency` 매개변수는 필수이며 통화 형식을 결정합니다. 스타일 타입: `FieldStyleTextField`

<div id="checkbox-fields"></div>

### 체크박스 필드

``` dart
Field.checkbox("Accept Terms")

Field.checkbox("Agree to terms",
  header: Text("Terms and conditions"),
  footer: Text("You must agree to continue."),
  validator: FormValidator.booleanTrue(message: "You must accept the terms"),
)
```

스타일 타입: `FieldStyleCheckbox`

<div id="switch-box-fields"></div>

### 스위치 박스 필드

``` dart
Field.switchBox("Enable Notifications")
```

스타일 타입: `FieldStyleSwitchBox`

<div id="picker-fields"></div>

### Picker 필드

``` dart
Field.picker("Category",
  options: FormCollection.from(["Electronics", "Clothing", "Books"]),
)

// With key-value pairs
Field.picker("Country",
  options: FormCollection.fromMap({
    "us": "United States",
    "ca": "Canada",
    "uk": "United Kingdom",
  }),
)
```

`options` 매개변수에는 `FormCollection`이 필요합니다 (원시 리스트가 아님). 자세한 내용은 [FormCollection](#form-collection)을 참조하세요. 스타일 타입: `FieldStylePicker`

#### 리스트 타일 스타일

`PickerListTileStyle`을 사용하여 Picker의 바텀 시트에서 항목이 표시되는 방식을 커스터마이징할 수 있습니다. 기본적으로 바텀 시트는 일반 텍스트 타일을 표시합니다. 내장 프리셋을 사용하여 선택 표시기를 추가하거나 완전히 커스텀 빌더를 제공할 수 있습니다.

**라디오 스타일** -- 선행 위젯으로 라디오 버튼 아이콘을 표시합니다:

``` dart
Field.picker("Country",
  options: FormCollection.from(["United States", "Canada", "United Kingdom"]),
  style: FieldStylePicker(
    listTileStyle: PickerListTileStyle.radio(),
  ),
)

// With a custom active color
FieldStylePicker(
  listTileStyle: PickerListTileStyle.radio(activeColor: Colors.blue),
)
```

**체크마크 스타일** -- 선택 시 후행 위젯으로 체크 아이콘을 표시합니다:

``` dart
Field.picker("Category",
  options: FormCollection.from(["Electronics", "Clothing", "Books"]),
  style: FieldStylePicker(
    listTileStyle: PickerListTileStyle.checkmark(activeColor: Colors.green),
  ),
)
```

**커스텀 빌더** -- 각 타일의 위젯을 완전히 제어합니다:

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

두 프리셋 스타일 모두 `textStyle`, `selectedTextStyle`, `contentPadding`, `tileColor`, `selectedTileColor`를 지원합니다:

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

### 라디오 필드

``` dart
Field.radio("Newsletter",
  options: FormCollection.fromMap({
    "Yes": "Yes, I want to receive newsletters",
    "No": "No, I do not want to receive newsletters",
  }),
)
```

`options` 매개변수에는 `FormCollection`이 필요합니다. 스타일 타입: `FieldStyleRadio`

<div id="chip-fields"></div>

### Chip 필드

``` dart
Field.chips("Tags",
  options: FormCollection.from(["Featured", "Sale", "New"]),
)

// With key-value pairs
Field.chips("Engine Size",
  options: FormCollection.fromMap({
    "125": "125cc",
    "250": "250cc",
    "500": "500cc",
  }),
)
```

Chip 위젯을 통한 다중 선택을 허용합니다. `options` 매개변수에는 `FormCollection`이 필요합니다. 스타일 타입: `FieldStyleChip`

<div id="slider-fields"></div>

### 슬라이더 필드

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

스타일 타입: `FieldStyleSlider` -- `min`, `max`, `divisions`, 색상, 값 표시 등을 구성합니다.

<div id="range-slider-fields"></div>

### 범위 슬라이더 필드

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

`RangeValues` 객체를 반환합니다. 스타일 타입: `FieldStyleRangeSlider`

<div id="custom-fields"></div>

### 커스텀 필드

`Field.custom()`을 사용하여 자체 상태가 있는 위젯을 제공합니다:

``` dart
Field.custom("My Field",
  child: MyCustomFieldWidget(),
)
```

`child` 매개변수에는 `NyFieldStatefulWidget`을 확장하는 위젯이 필요합니다. 필드의 렌더링과 동작을 완전히 제어할 수 있습니다.

<div id="widget-fields"></div>

### 위젯 필드

`Field.widget()`을 사용하여 폼 필드가 아닌 위젯을 폼 내에 삽입합니다:

``` dart
Field.widget(child: Divider())

Field.widget(child: Text("Section Header", style: TextStyle(fontSize: 18)))
```

위젯 필드는 유효성 검사나 데이터 수집에 참여하지 않습니다. 순수하게 레이아웃 용도입니다.


<div id="form-collection"></div>

## FormCollection

Picker, 라디오, Chip 필드의 옵션에는 `FormCollection`이 필요합니다. `FormCollection`은 다양한 옵션 형식을 처리하기 위한 통합 인터페이스를 제공합니다.

### FormCollection 생성

``` dart
// From a list of strings (value and label are the same)
FormCollection.from(["Red", "Green", "Blue"])

// Same as above, explicit
FormCollection.fromArray(["Red", "Green", "Blue"])

// From a map (key = value, value = label)
FormCollection.fromMap({
  "us": "United States",
  "ca": "Canada",
})

// From structured data (useful for API responses)
FormCollection.fromKeyValue([
  {"value": "en", "label": "English"},
  {"value": "es", "label": "Spanish"},
])
```

`FormCollection.from()`은 데이터 형식을 자동 감지하고 적절한 생성자에 위임합니다.

### FormOption

`FormCollection`의 각 옵션은 `value`와 `label` 속성을 가진 `FormOption`입니다:

``` dart
FormOption option = FormOption(value: "us", label: "United States");
print(option.value); // "us"
print(option.label); // "United States"
```

### 옵션 조회

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

## 폼 유효성 검사

`FormValidator`와 함께 `validator` 매개변수를 사용하여 모든 필드에 유효성 검사를 추가합니다:

``` dart
// Named constructor
Field.email("Email", validator: FormValidator.email())

// Chained rules
Field.text("Username",
  validator: FormValidator()
    .notEmpty()
    .minLength(3)
    .maxLength(20)
)

// Password with strength level
Field.password("Password",
  validator: FormValidator.password(strength: 2)
)

// Boolean validation
Field.checkbox("Terms",
  validator: FormValidator.booleanTrue(message: "You must accept the terms")
)

// Custom inline validation
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

폼이 제출되면 모든 유효성 검사기가 확인됩니다. 실패하면 첫 번째 에러 메시지가 포함된 Toast 에러가 표시되고 `onFailure` 콜백이 호출됩니다.

**참고:** 사용 가능한 유효성 검사기의 전체 목록은 [유효성 검사](/docs/7.x/validation#validation-rules)를 참조하세요.


<div id="managing-form-data"></div>

## 폼 데이터 관리

<div id="initial-data"></div>

### 초기 데이터

폼에 초기 데이터를 설정하는 두 가지 방법이 있습니다.

**옵션 1: 폼 클래스에서 `init` getter를 오버라이드**

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

`init` getter는 동기 `Map` 또는 비동기 `Future<Map>`을 반환할 수 있습니다. 키는 snake_case 정규화를 사용하여 필드 이름과 매칭되므로 `"First Name"`은 키가 `"First Name"`인 필드에 매핑됩니다.

#### init에서 `define()` 사용

`init`에서 필드에 **옵션** (또는 값과 옵션 모두)을 설정해야 할 때 `define()` 헬퍼를 사용합니다. 옵션이 API나 다른 비동기 소스에서 오는 Picker, Chip, 라디오 필드에 유용합니다.

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

`define()`은 두 개의 명명된 매개변수를 받습니다:

| 매개변수 | 설명 |
|-----------|-------------|
| `value` | 필드의 초기값 |
| `options` | Picker, Chip 또는 라디오 필드의 옵션 |

``` dart
// Set only options (no initial value)
"Category": define(options: categories),

// Set only an initial value
"Price": define(value: "100"),

// Set both a value and options
"Country": define(value: "us", options: countries),

// Plain values still work for simple fields
"Name": "John",
```

`define()`에 전달되는 옵션은 `List`, `Map` 또는 `FormCollection`일 수 있습니다. 적용 시 자동으로 `FormCollection`으로 변환됩니다.

**옵션 2: 폼 위젯에 `initialData` 전달**

``` dart
EditAccountForm(
  initialData: {
    "first_name": "John",
    "last_name": "Doe",
  },
)
```

<div id="setting-field-values"></div>

### 필드 값 설정

`NyFormActions`를 사용하여 어디에서나 필드 값을 설정합니다:

``` dart
// Set a single field value
EditAccountForm.actions.updateField("First Name", "Jane");
```

<div id="setting-field-options"></div>

### 필드 옵션 설정

Picker, Chip 또는 라디오 필드의 옵션을 동적으로 업데이트합니다:

``` dart
EditAccountForm.actions.setOptions("Category", FormCollection.from(["New Option 1", "New Option 2"]));
```

<div id="reading-form-data"></div>

### 폼 데이터 읽기

폼 데이터는 폼 제출 시 `onSubmit` 콜백을 통해 접근하거나, 실시간 업데이트를 위해 `onChanged` 콜백을 통해 접근합니다:

``` dart
EditAccountForm(
  onSubmit: (data) {
    // data is a Map<String, dynamic>
    // {first_name: "Jane", last_name: "Doe", email: "jane@example.com"}
    print(data);
  },
  onChanged: (Field field, dynamic value) {
    print("${field.key} changed to: $value");
  },
)
```

<div id="clearing-data"></div>

### 데이터 지우기

``` dart
// Clear all fields
EditAccountForm.actions.clear();

// Clear a specific field
EditAccountForm.actions.clearField("First Name");
```


<div id="finding-and-updating-fields"></div>

### 필드 업데이트

``` dart
// Update a field value
EditAccountForm.actions.updateField("First Name", "Jane");

// Refresh the form UI
EditAccountForm.actions.refresh();

// Refresh form fields (re-calls fields())
EditAccountForm.actions.refreshForm();
```


<div id="submit-button"></div>

## 제출 버튼

폼을 생성할 때 `submitButton`과 `onSubmit` 콜백을 전달합니다:

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

`submitButton`은 폼 필드 아래에 자동으로 표시됩니다. 내장 버튼 스타일이나 커스텀 위젯을 사용할 수 있습니다.

`footer`로 전달하여 모든 위젯을 제출 버튼으로 사용할 수도 있습니다:

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

## 폼 레이아웃

필드를 `List`로 감싸서 나란히 배치합니다:

``` dart
@override
fields() => [
  // Single field (full width)
  Field.text("Title"),

  // Two fields in a row
  [
    Field.text("First Name"),
    Field.text("Last Name"),
  ],

  // Another single field
  Field.textArea("Bio"),

  // Slider and range slider in a row
  [
    Field.slider("Rating", style: FieldStyleSlider(min: 0, max: 10)),
    Field.rangeSlider("Budget", style: FieldStyleRangeSlider(min: 0, max: 1000)),
  ],

  // Embed a non-field widget
  Field.widget(child: Divider()),

  Field.email("Email"),
];
```

`List`에 있는 필드는 동일한 `Expanded` 너비로 `Row`에 렌더링됩니다. 필드 간 간격은 `NyFormWidget`의 `crossAxisSpacing` 매개변수로 제어됩니다.


<div id="field-visibility"></div>

## 필드 가시성

`Field`의 `hide()` 및 `show()` 메서드를 사용하여 프로그래밍 방식으로 필드를 표시하거나 숨깁니다. 폼 클래스 내부 또는 `onChanged` 콜백을 통해 필드에 접근할 수 있습니다:

``` dart
// Inside your NyFormWidget subclass or onChanged callback
Field nameField = ...;

// Hide the field
nameField.hide();

// Show the field
nameField.show();
```

숨겨진 필드는 UI에 렌더링되지 않지만 폼의 필드 목록에는 여전히 존재합니다.


<div id="field-styling"></div>

## 필드 스타일링

각 필드 유형에는 스타일링을 위한 해당 `FieldStyle` 서브클래스가 있습니다:

| 필드 유형 | 스타일 클래스 |
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

모든 필드의 `style` 매개변수에 스타일 객체를 전달합니다:

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

## NyFormWidget 정적 메서드

`NyFormWidget`은 앱 어디에서나 이름으로 폼과 상호작용하는 정적 메서드를 제공합니다:

| 메서드 | 설명 |
|--------|-------------|
| `NyFormWidget.submit(name, onSuccess:, onFailure:, showToastError:)` | 이름으로 폼 제출 |
| `NyFormWidget.stateRefresh(name)` | 폼의 UI 상태 새로고침 |
| `NyFormWidget.stateSetValue(name, key, value)` | 폼 이름으로 필드 값 설정 |
| `NyFormWidget.stateSetOptions(name, key, options)` | 폼 이름으로 필드 옵션 설정 |
| `NyFormWidget.stateClearData(name)` | 폼 이름으로 모든 필드 지우기 |
| `NyFormWidget.stateRefreshForm(name)` | 폼 필드 새로고침 (`fields()` 재호출) |

``` dart
// Submit a form named "LoginForm" from anywhere
NyFormWidget.submit("LoginForm", onSuccess: (data) {
  print(data);
});

// Update a field value remotely
NyFormWidget.stateSetValue("LoginForm", "Email", "new@email.com");

// Clear all form data
NyFormWidget.stateClearData("LoginForm");
```

> **팁:** 이 정적 메서드를 직접 호출하는 대신 `NyFormActions`(아래 참조)를 사용하는 것이 더 간결하고 에러가 적습니다.


<div id="ny-form-widget-constructor-reference"></div>

## NyFormWidget 생성자 참조

`NyFormWidget`을 확장할 때 전달할 수 있는 생성자 매개변수입니다:

``` dart
LoginForm(
  Key? key,
  double crossAxisSpacing = 10,  // Horizontal spacing between row fields
  double mainAxisSpacing = 10,   // Vertical spacing between fields
  Map<String, dynamic>? initialData, // Initial field values
  Function(Field field, dynamic value)? onChanged, // Field change callback
  Widget? header,                // Widget above the form
  Widget? submitButton,          // Submit button widget
  Widget? footer,                // Widget below the form
  double headerSpacing = 10,     // Spacing after header
  double submitButtonSpacing = 10, // Spacing after submit button
  double footerSpacing = 10,     // Spacing before footer
  LoadingStyle? loadingStyle,    // Loading indicator style
  bool locked = false,           // Makes form read-only
  Function(dynamic data)? onSubmit,   // Called with form data on successful validation
  Function(dynamic error)? onFailure, // Called with errors on failed validation
)
```

`onChanged` 콜백은 변경된 `Field`와 새 값을 받습니다:

``` dart
LoginForm(
  onChanged: (Field field, dynamic value) {
    print("${field.key} changed to: $value");
  },
)
```


<div id="ny-form-actions"></div>

## NyFormActions

`NyFormActions`는 앱 어디에서나 폼과 상호작용하는 편리한 방법을 제공합니다. 폼 클래스에 정적 getter로 정의합니다:

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

### 사용 가능한 액션

| 메서드 | 설명 |
|--------|-------------|
| `actions.updateField(key, value)` | 필드 값 설정 |
| `actions.clearField(key)` | 특정 필드 지우기 |
| `actions.clear()` | 모든 필드 지우기 |
| `actions.refresh()` | 폼의 UI 상태 새로고침 |
| `actions.refreshForm()` | `fields()` 재호출 및 재빌드 |
| `actions.setOptions(key, options)` | Picker/Chip/라디오 필드의 옵션 설정 |
| `actions.submit(onSuccess:, onFailure:, showToastError:)` | 유효성 검사 후 제출 |

``` dart
// Update a field value
LoginForm.actions.updateField("Email", "new@email.com");

// Clear all form data
LoginForm.actions.clear();

// Submit the form
LoginForm.actions.submit(
  onSuccess: (data) {
    print(data);
  },
);
```

### NyFormWidget 오버라이드

`NyFormWidget` 서브클래스에서 오버라이드할 수 있는 메서드:

| 오버라이드 | 설명 |
|----------|-------------|
| `fields()` | 폼 필드 정의 (필수) |
| `init` | 초기 데이터 제공 (동기 또는 비동기) |
| `onChange(field, data)` | 내부적으로 필드 변경 처리 |


<div id="all-field-types-reference"></div>

## 전체 필드 유형 참조

| 생성자 | 주요 매개변수 | 설명 |
|-------------|----------------|-------------|
| `Field.text()` | -- | 표준 텍스트 입력 |
| `Field.email()` | -- | 키보드 유형이 포함된 이메일 입력 |
| `Field.password()` | `viewable` | 선택적 표시/숨기기 토글이 있는 비밀번호 |
| `Field.number()` | `decimal` | 숫자 입력, 선택적 소수점 |
| `Field.currency()` | `currency` (필수) | 통화 형식 입력 |
| `Field.capitalizeWords()` | -- | 타이틀 케이스 텍스트 입력 |
| `Field.capitalizeSentences()` | -- | 문장 케이스 텍스트 입력 |
| `Field.textArea()` | -- | 여러 줄 텍스트 입력 |
| `Field.phoneNumber()` | -- | 자동 포맷 전화번호 |
| `Field.url()` | -- | 키보드 유형이 포함된 URL 입력 |
| `Field.mask()` | `mask` (필수), `match`, `maskReturnValue` | 마스크 텍스트 입력 |
| `Field.date()` | -- | 날짜 선택기 |
| `Field.datetime()` | `firstDate`, `lastDate`, `dateFormat`, `initialPickerDateTime` | 날짜 및 시간 선택기 |
| `Field.checkbox()` | -- | Boolean 체크박스 |
| `Field.switchBox()` | -- | Boolean 토글 스위치 |
| `Field.picker()` | `options` (필수 `FormCollection`) | 리스트에서 단일 선택 |
| `Field.radio()` | `options` (필수 `FormCollection`) | 라디오 버튼 그룹 |
| `Field.chips()` | `options` (필수 `FormCollection`) | 다중 선택 Chip |
| `Field.slider()` | -- | 단일 값 슬라이더 |
| `Field.rangeSlider()` | -- | 범위 값 슬라이더 |
| `Field.custom()` | `child` (필수 `NyFieldStatefulWidget`) | 커스텀 상태가 있는 위젯 |
| `Field.widget()` | `child` (필수 `Widget`) | 모든 위젯 삽입 (비필드) |
