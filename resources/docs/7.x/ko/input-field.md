# InputField

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- [기본 사용법](#basic-usage "기본 사용법")
- [유효성 검사](#validation "유효성 검사")
- 변형
    - [InputField.password](#password "InputField.password")
    - [InputField.emailAddress](#email-address "InputField.emailAddress")
    - [InputField.capitalizeWords](#capitalize-words "InputField.capitalizeWords")
- [입력 마스킹](#masking "입력 마스킹")
- [헤더 및 푸터](#header-footer "헤더 및 푸터")
- [지우기 가능한 입력](#clearable "지우기 가능한 입력")
- [상태 관리](#state-management "상태 관리")
- [매개변수](#parameters "매개변수")


<div id="introduction"></div>

## 소개

**InputField** 위젯은 {{ config('app.name') }}의 향상된 텍스트 필드로 다음을 기본 지원합니다:

- 커스터마이즈 가능한 에러 메시지와 함께하는 유효성 검사
- 비밀번호 표시/숨기기 토글
- 입력 마스킹 (전화번호, 신용카드 등)
- 헤더 및 푸터 위젯
- 지우기 가능한 입력
- 상태 관리 통합
- 개발용 더미 데이터

<div id="basic-usage"></div>

## 기본 사용법

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

## 유효성 검사

`formValidator` 매개변수를 사용하여 유효성 검사 규칙을 추가합니다:

``` dart
InputField(
  controller: _controller,
  labelText: "Email",
  formValidator: FormValidator.rule("email|not_empty"),
  validateOnFocusChange: true,
)
```

사용자가 필드에서 포커스를 이동하면 유효성 검사가 실행됩니다.

### 커스텀 유효성 검사 핸들러

프로그래밍 방식으로 유효성 검사 에러를 처리합니다:

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

사용 가능한 모든 유효성 검사 규칙은 [유효성 검사](/docs/7.x/validation) 문서를 참조하세요.

<div id="password"></div>

## InputField.password

텍스트 숨김과 표시/숨기기 토글이 포함된 미리 구성된 비밀번호 필드입니다:

``` dart
final TextEditingController _passwordController = TextEditingController();

InputField.password(
  controller: _passwordController,
  labelText: "Password",
  formValidator: FormValidator.rule("not_empty|min:8"),
)
```

### 비밀번호 표시 커스터마이징

``` dart
InputField.password(
  controller: _passwordController,
  passwordVisible: true, // Show/hide toggle icon
  passwordViewable: true, // Allow user to toggle visibility
)
```

<div id="email-address"></div>

## InputField.emailAddress

이메일 키보드와 자동 포커스가 포함된 미리 구성된 이메일 필드입니다:

``` dart
final TextEditingController _emailController = TextEditingController();

InputField.emailAddress(
  controller: _emailController,
  formValidator: FormValidator.rule("email|not_empty"),
)
```

<div id="capitalize-words"></div>

## InputField.capitalizeWords

각 단어의 첫 글자를 자동으로 대문자로 변환합니다:

``` dart
final TextEditingController _nameController = TextEditingController();

InputField.capitalizeWords(
  controller: _nameController,
  labelText: "Full Name",
)
```

<div id="masking"></div>

## 입력 마스킹

전화번호나 신용카드와 같은 포맷된 데이터에 입력 마스크를 적용합니다:

``` dart
// Phone number mask
InputField(
  controller: _phoneController,
  labelText: "Phone Number",
  mask: "(###) ###-####",
  maskMatch: r'[0-9]',
  maskedReturnValue: false, // Returns unmasked value: 1234567890
)

// Credit card mask
InputField(
  controller: _cardController,
  labelText: "Card Number",
  mask: "#### #### #### ####",
  maskMatch: r'[0-9]',
  maskedReturnValue: true, // Returns masked value: 1234 5678 9012 3456
)
```

| 매개변수 | 설명 |
|-----------|-------------|
| `mask` | `#`을 플레이스홀더로 사용하는 마스크 패턴 |
| `maskMatch` | 유효한 입력 문자를 위한 정규식 패턴 |
| `maskedReturnValue` | true이면 포맷된 값을 반환; false이면 원시 입력을 반환 |

<div id="header-footer"></div>

## 헤더 및 푸터

입력 필드 위 또는 아래에 위젯을 추가합니다:

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

## 지우기 가능한 입력

필드를 빠르게 비우는 지우기 버튼을 추가합니다:

``` dart
InputField(
  controller: _searchController,
  labelText: "Search",
  clearable: true,
  clearIcon: Icon(Icons.close, size: 20), // Custom clear icon
  onChanged: (value) {
    // Handle search
  },
)
```

<div id="state-management"></div>

## 상태 관리

입력 필드에 상태 이름을 지정하여 프로그래밍 방식으로 제어합니다:

``` dart
InputField(
  controller: _controller,
  labelText: "Username",
  stateName: "username_field",
)
```

### 상태 액션

``` dart
// Clear the field
InputField.stateActions("username_field").clear();

// Set a value
updateState("username_field", data: {
  "action": "setValue",
  "value": "new_value"
});
```

<div id="parameters"></div>

## 매개변수

### 공통 매개변수

| 매개변수 | 타입 | 기본값 | 설명 |
|-----------|------|---------|-------------|
| `controller` | `TextEditingController` | 필수 | 편집 중인 텍스트를 제어 |
| `labelText` | `String?` | - | 필드 위에 표시되는 라벨 |
| `hintText` | `String?` | - | 플레이스홀더 텍스트 |
| `formValidator` | `FormValidator?` | - | 유효성 검사 규칙 |
| `validateOnFocusChange` | `bool` | `true` | 포커스 변경 시 유효성 검사 |
| `obscureText` | `bool` | `false` | 입력 숨기기 (비밀번호용) |
| `keyboardType` | `TextInputType` | `text` | 키보드 유형 |
| `autoFocus` | `bool` | `false` | 빌드 시 자동 포커스 |
| `readOnly` | `bool` | `false` | 필드를 읽기 전용으로 설정 |
| `enabled` | `bool?` | - | 필드 활성화/비활성화 |
| `maxLines` | `int?` | `1` | 최대 줄 수 |
| `maxLength` | `int?` | - | 최대 문자 수 |

### 스타일링 매개변수

| 매개변수 | 타입 | 설명 |
|-----------|------|-------------|
| `backgroundColor` | `Color?` | 필드 배경색 |
| `borderRadius` | `BorderRadius?` | 테두리 반경 |
| `border` | `InputBorder?` | 기본 테두리 |
| `focusedBorder` | `InputBorder?` | 포커스 시 테두리 |
| `enabledBorder` | `InputBorder?` | 활성화 시 테두리 |
| `contentPadding` | `EdgeInsetsGeometry?` | 내부 패딩 |
| `style` | `TextStyle?` | 텍스트 스타일 |
| `labelStyle` | `TextStyle?` | 라벨 텍스트 스타일 |
| `hintStyle` | `TextStyle?` | 힌트 텍스트 스타일 |
| `prefixIcon` | `Widget?` | 입력 앞 아이콘 |

### 마스킹 매개변수

| 매개변수 | 타입 | 설명 |
|-----------|------|-------------|
| `mask` | `String?` | 마스크 패턴 (예: "###-####") |
| `maskMatch` | `String?` | 유효한 문자를 위한 정규식 |
| `maskedReturnValue` | `bool?` | 마스크된 값 또는 원시 값 반환 |

### 기능 매개변수

| 매개변수 | 타입 | 설명 |
|-----------|------|-------------|
| `header` | `Widget?` | 필드 위의 위젯 |
| `footer` | `Widget?` | 필드 아래의 위젯 |
| `clearable` | `bool?` | 지우기 버튼 표시 |
| `clearIcon` | `Widget?` | 커스텀 지우기 아이콘 |
| `passwordVisible` | `bool?` | 비밀번호 토글 표시 |
| `passwordViewable` | `bool?` | 비밀번호 표시/숨기기 토글 허용 |
| `dummyData` | `String?` | 개발용 가짜 데이터 |
| `stateName` | `String?` | 상태 관리를 위한 이름 |
| `onChanged` | `Function(String)?` | 값 변경 시 호출 |
