# TextTr

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- [기본 사용법](#basic-usage "기본 사용법")
- [문자열 보간](#string-interpolation "문자열 보간")
- [스타일 생성자](#styled-constructors "스타일 생성자")
- [매개변수](#parameters "매개변수")


<div id="introduction"></div>

## 소개

**TextTr** 위젯은 {{ config('app.name') }}의 현지화 시스템을 사용하여 콘텐츠를 자동으로 번역하는 Flutter의 `Text` 위젯 편의 래퍼입니다.

이렇게 작성하는 대신:

``` dart
Text("hello_world".tr())
```

이렇게 작성할 수 있습니다:

``` dart
TextTr("hello_world")
```

이를 통해 많은 번역된 문자열을 다룰 때 코드가 더 깔끔하고 읽기 쉬워집니다.

<div id="basic-usage"></div>

## 기본 사용법

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

위젯은 언어 파일(예: `/lang/en.json`)에서 번역 키를 찾습니다:

``` json
{
  "welcome_message": "Welcome to our app!",
  "app_title": "My Application"
}
```

<div id="string-interpolation"></div>

## 문자열 보간

`arguments` 매개변수를 사용하여 번역에 동적 값을 삽입합니다:

``` dart
TextTr(
  "greeting",
  arguments: {"name": "John"},
)
```

언어 파일에서:

``` json
{
  "greeting": "Hello, @{{name}}!"
}
```

출력: **Hello, John!**

### 다중 인수

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

출력: **You ordered 2x Coffee for $8.50**

<div id="styled-constructors"></div>

## 스타일 생성자

`TextTr`은 테마의 텍스트 스타일을 자동으로 적용하는 네임드 생성자를 제공합니다:

### displayLarge

``` dart
TextTr.displayLarge("page_title")
```

`Theme.of(context).textTheme.displayLarge` 스타일을 사용합니다.

### headlineLarge

``` dart
TextTr.headlineLarge("section_heading")
```

`Theme.of(context).textTheme.headlineLarge` 스타일을 사용합니다.

### bodyLarge

``` dart
TextTr.bodyLarge("paragraph_text")
```

`Theme.of(context).textTheme.bodyLarge` 스타일을 사용합니다.

### labelLarge

``` dart
TextTr.labelLarge("button_label")
```

`Theme.of(context).textTheme.labelLarge` 스타일을 사용합니다.

### 스타일 생성자 예시

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

## 매개변수

`TextTr`은 표준 `Text` 위젯의 모든 매개변수를 지원합니다:

| 매개변수 | 타입 | 설명 |
|-----------|------|-------------|
| `data` | `String` | 찾을 번역 키 |
| `arguments` | `Map<String, String>?` | 문자열 보간을 위한 키-값 쌍 |
| `style` | `TextStyle?` | 텍스트 스타일링 |
| `textAlign` | `TextAlign?` | 텍스트 정렬 방식 |
| `maxLines` | `int?` | 최대 줄 수 |
| `overflow` | `TextOverflow?` | 오버플로 처리 방식 |
| `softWrap` | `bool?` | 소프트 줄바꿈에서 텍스트 줄바꿈 여부 |
| `textDirection` | `TextDirection?` | 텍스트 방향 |
| `locale` | `Locale?` | 텍스트 렌더링을 위한 로케일 |
| `semanticsLabel` | `String?` | 접근성 레이블 |

## 비교

| 방식 | 코드 |
|----------|------|
| 전통적인 방식 | `Text("hello".tr())` |
| TextTr | `TextTr("hello")` |
| 인수와 함께 | `TextTr("hello", arguments: {"name": "John"})` |
| 스타일 적용 | `TextTr.headlineLarge("title")` |
