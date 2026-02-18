# FutureWidget

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- [기본 사용법](#basic-usage "기본 사용법")
- [로딩 상태 커스터마이징](#customizing-loading "로딩 상태 커스터마이징")
    - [일반 로딩 스타일](#normal-loading "일반 로딩 스타일")
    - [Skeletonizer 로딩 스타일](#skeletonizer-loading "Skeletonizer 로딩 스타일")
    - [로딩 없음 스타일](#no-loading "로딩 없음 스타일")
- [에러 처리](#error-handling "에러 처리")


<div id="introduction"></div>

## 소개

**FutureWidget**은 {{ config('app.name') }} 프로젝트에서 `Future`를 렌더링하는 간단한 방법입니다. Flutter의 `FutureBuilder`를 래핑하고 내장 로딩 상태와 함께 더 깔끔한 API를 제공합니다.

Future가 진행 중일 때 로더가 표시됩니다. Future가 완료되면 `child` 콜백을 통해 데이터가 반환됩니다.

<div id="basic-usage"></div>

## 기본 사용법

`FutureWidget` 사용의 간단한 예제입니다:

``` dart
// A Future that takes 3 seconds to complete
Future<String> _findUserName() async {
  await sleep(3); // wait for 3 seconds
  return "John Doe";
}

// Use the FutureWidget
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: SafeArea(
       child: FutureWidget<String>(
         future: _findUserName(),
         child: (context, data) {
           // data = "John Doe"
           return Text(data!);
         },
       ),
    ),
  );
}
```

위젯은 Future가 완료될 때까지 사용자를 위해 자동으로 로딩 상태를 처리합니다.

<div id="customizing-loading"></div>

## 로딩 상태 커스터마이징

`loadingStyle` 매개변수를 사용하여 로딩 상태가 표시되는 방식을 커스터마이징할 수 있습니다.

<div id="normal-loading"></div>

### 일반 로딩 스타일

`LoadingStyle.normal()`을 사용하여 표준 로딩 위젯을 표시합니다. 선택적으로 커스텀 자식 위젯을 제공할 수 있습니다:

``` dart
FutureWidget<String>(
  future: _fetchData(),
  child: (context, data) {
    return Text(data!);
  },
  loadingStyle: LoadingStyle.normal(
    child: Text("Loading..."), // custom loading widget
  ),
)
```

자식이 제공되지 않으면 기본 {{ config('app.name') }} 앱 로더가 표시됩니다.

<div id="skeletonizer-loading"></div>

### Skeletonizer 로딩 스타일

`LoadingStyle.skeletonizer()`를 사용하여 스켈레톤 로딩 효과를 표시합니다. 콘텐츠 레이아웃과 일치하는 플레이스홀더 UI를 보여주는 데 적합합니다:

``` dart
FutureWidget<User>(
  future: _fetchUser(),
  child: (context, user) {
    return UserCard(user: user!);
  },
  loadingStyle: LoadingStyle.skeletonizer(
    child: UserCard(user: User.placeholder()), // skeleton placeholder
    effect: SkeletonizerEffect.shimmer, // shimmer, pulse, or solid
  ),
)
```

사용 가능한 스켈레톤 효과:
- `SkeletonizerEffect.shimmer` - 애니메이션 시머 효과 (기본값)
- `SkeletonizerEffect.pulse` - 펄스 애니메이션 효과
- `SkeletonizerEffect.solid` - 단색 효과

<div id="no-loading"></div>

### 로딩 없음 스타일

`LoadingStyle.none()`을 사용하여 로딩 인디케이터를 완전히 숨깁니다:

``` dart
FutureWidget<String>(
  future: _fetchData(),
  child: (context, data) {
    return Text(data!);
  },
  loadingStyle: LoadingStyle.none(),
)
```

<div id="error-handling"></div>

## 에러 처리

`onError` 콜백을 사용하여 Future의 에러를 처리할 수 있습니다:

``` dart
FutureWidget<String>(
  future: _fetchData(),
  child: (context, data) {
    return Text(data!);
  },
  onError: (AsyncSnapshot snapshot) {
    print(snapshot.error.toString());
    return Text("Something went wrong");
  },
)
```

`onError` 콜백이 제공되지 않고 에러가 발생하면 빈 `SizedBox.shrink()`가 표시됩니다.

### 매개변수

| 매개변수 | 타입 | 설명 |
|-----------|------|-------------|
| `future` | `Future<T>?` | 대기할 Future |
| `child` | `Widget Function(BuildContext, T?)` | Future 완료 시 호출되는 빌더 함수 |
| `loadingStyle` | `LoadingStyle?` | 로딩 인디케이터 커스터마이즈 |
| `onError` | `Widget Function(AsyncSnapshot)?` | Future에 에러가 있을 때 호출되는 빌더 함수 |
