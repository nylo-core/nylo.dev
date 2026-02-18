# Logging

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- [로그 레벨](#log-levels "로그 레벨")
- [로그 메서드](#log-methods "로그 메서드")
- [JSON 로깅](#json-logging "JSON 로깅")
- [색상 출력](#colored-output "색상 출력")
- [로그 리스너](#log-listeners "로그 리스너")
- [헬퍼 확장](#helper-extensions "헬퍼 확장")
- [설정](#configuration "설정")

<div id="introduction"></div>

## 소개

{{ config('app.name') }} v7은 종합적인 로깅 시스템을 제공합니다.

로그는 `.env` 파일에서 `APP_DEBUG=true`일 때만 출력되어 프로덕션 앱을 깔끔하게 유지합니다.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// 기본 로깅
printInfo("Hello World");
printDebug("Debug message");
printError("Error occurred");
```

<div id="log-levels"></div>

## 로그 레벨

{{ config('app.name') }} v7은 색상 출력과 함께 여러 로그 레벨을 지원합니다:

| 레벨 | 메서드 | 색상 | 사용 사례 |
|-------|--------|-------|----------|
| Debug | `printDebug()` | 시안 | 상세한 디버깅 정보 |
| Info | `printInfo()` | 파랑 | 일반 정보 |
| Error | `printError()` | 빨강 | 오류 및 예외 |

``` dart
printDebug("Fetching user ID: 123");
printInfo("App initialized");
printError("Network request failed");
```

출력 예시:
```
[2025-01-27 10:30:45] [debug] Fetching user ID: 123
[2025-01-27 10:30:45] [info] App initialized
[2025-01-27 10:30:46] [error] Network request failed
```

<div id="log-methods"></div>

## 로그 메서드

### 기본 로깅

``` dart
// 클래스 메서드
printInfo("Information message");
printDebug("Debug message");
printError("Error message");
printJson({"key": "value"});
```

### 스택 트레이스를 포함한 오류

더 나은 디버깅을 위해 스택 트레이스와 함께 오류를 기록합니다:

``` dart
try {
  await someOperation();
} catch (e, stackTrace) {
  printError(e, stackTrace: stackTrace);
}
```

### 디버그 모드와 관계없이 강제 출력

`APP_DEBUG=false`일 때도 출력하려면 `alwaysPrint: true`를 사용합니다:

``` dart
printInfo("Critical info", alwaysPrint: true);
printError("Critical error", alwaysPrint: true);
```

### 다음 로그 표시 (일회성 오버라이드)

`APP_DEBUG=false`일 때 단일 로그를 출력합니다:

``` dart
// .env: APP_DEBUG=false

printInfo("This won't print");

showNextLog();
printInfo("This will print"); // 한 번 출력

printInfo("This won't print again");
```

<div id="json-logging"></div>

## JSON 로깅

{{ config('app.name') }} v7에는 전용 JSON 로깅 메서드가 포함되어 있습니다:

``` dart
Map<String, dynamic> userData = {
  "id": 123,
  "name": "Anthony",
  "email": "anthony@example.com"
};

// 압축 JSON
printJson(userData);
// {"id":123,"name":"Anthony","email":"anthony@example.com"}

// 보기 좋게 출력된 JSON
printJson(userData, prettyPrint: true);
// {
//   "id": 123,
//   "name": "Anthony",
//   "email": "anthony@example.com"
// }
```

<div id="colored-output"></div>

## 색상 출력

{{ config('app.name') }} v7은 디버그 모드에서 로그 출력에 ANSI 색상을 사용합니다. 각 로그 레벨은 쉽게 식별할 수 있도록 고유한 색상을 갖습니다.

### 색상 비활성화

``` dart
// 전역으로 색상 출력 비활성화
NyLogger.useColors = false;
```

색상은 다음 경우에 자동으로 비활성화됩니다:
- 릴리스 모드
- 터미널이 ANSI 이스케이프 코드를 지원하지 않는 경우

<div id="log-listeners"></div>

## 로그 리스너

{{ config('app.name') }} v7에서는 모든 로그 항목을 실시간으로 수신할 수 있습니다:

``` dart
// 로그 리스너 설정
NyLogger.onLog = (NyLogEntry entry) {
  print("Log: [${entry.type}] ${entry.message}");

  // 크래시 리포팅 서비스로 전송
  if (entry.type == 'error') {
    CrashReporter.log(entry.message, stackTrace: entry.stackTrace);
  }
};
```

### NyLogEntry 속성

``` dart
NyLogger.onLog = (NyLogEntry entry) {
  entry.message;    // 로그 메시지
  entry.type;       // 로그 레벨 (debug, info, warning, error, success, verbose)
  entry.dateTime;   // 로그 생성 시간
  entry.stackTrace; // 스택 트레이스 (오류의 경우)
};
```

### 사용 사례

- 크래시 리포팅 서비스로 오류 전송 (Sentry, Firebase Crashlytics)
- 커스텀 로그 뷰어 구축
- 디버깅을 위한 로그 저장
- 실시간 앱 동작 모니터링

``` dart
// 예시: Sentry로 오류 전송
NyLogger.onLog = (entry) {
  if (entry.type == 'error') {
    Sentry.captureMessage(
      entry.message,
      level: SentryLevel.error,
    );
  }
};
```

<div id="helper-extensions"></div>

## 헬퍼 확장

{{ config('app.name') }}은 로깅을 위한 편리한 확장 메서드를 제공합니다:

### dump()

콘솔에 모든 값을 출력합니다:

``` dart
String project = 'Nylo';
project.dump(); // 'Nylo'

List<String> seasons = ['Spring', 'Summer', 'Fall', 'Winter'];
seasons.dump(); // ['Spring', 'Summer', 'Fall', 'Winter']

int age = 25;
age.dump(); // 25

// 함수 구문
dump("Hello World");
```

### dd() - Dump and Die

값을 출력하고 즉시 종료합니다 (디버깅에 유용):

``` dart
String code = 'Dart';
code.dd(); // 'Dart'를 출력하고 실행 중지

// 함수 구문
dd("Debug point reached");
```

<div id="configuration"></div>

## 설정

### 환경 변수

`.env` 파일에서 로깅 동작을 제어합니다:

``` bash
# 모든 로깅 활성화/비활성화
APP_DEBUG=true
```

### 로그의 DateTime

{{ config('app.name') }}은 로그 출력에 타임스탬프를 포함할 수 있습니다. Nylo 설정에서 이를 구성합니다:

``` dart
// boot provider에서
Nylo.instance.showDateTimeInLogs(true);
```

타임스탬프가 있는 출력:
```
[2025-01-27 10:30:45] [info] User logged in
```

타임스탬프가 없는 출력:
```
[info] User logged in
```

### 모범 사례

1. **적절한 로그 레벨 사용** - 모든 것을 오류로 기록하지 마세요
2. **프로덕션에서 상세 로그 제거** - 프로덕션에서는 `APP_DEBUG=false`를 유지하세요
3. **컨텍스트 포함** - 디버깅에 관련된 데이터를 기록하세요
4. **구조화된 로깅 사용** - 복잡한 데이터에는 `NyLogger.json()`을 사용하세요
5. **오류 모니터링 설정** - 오류를 잡기 위해 `NyLogger.onLog`를 사용하세요

``` dart
// 좋은 로깅 관행
NyLogger.info("User ${user.id} logged in from ${device.platform}");
NyLogger.error("API request failed", stackTrace: stackTrace, alwaysPrint: true);
NyLogger.json(response.data, prettyPrint: true);
```
