# 커맨드

---

<a name="section-1"></a>
- [소개](#introduction)
- [커맨드 생성](#creating-commands)
- [커맨드 구조](#command-structure)
- [커맨드 실행](#running-commands)
- [커맨드 레지스트리](#command-registry)
- [옵션과 플래그](#options-and-flags)
  - [옵션 추가](#adding-options)
  - [플래그 추가](#adding-flags)
- [커맨드 결과](#command-result)
- [대화형 입력](#interactive-input)
  - [텍스트 입력](#text-input)
  - [확인](#confirmation)
  - [단일 선택](#single-selection)
  - [다중 선택](#multiple-selection)
  - [비밀 입력](#secret-input)
- [출력 포맷팅](#output-formatting)
- [스피너와 진행 상황](#spinner-and-progress)
  - [withSpinner 사용](#using-withspinner)
  - [수동 스피너 제어](#manual-spinner-control)
  - [프로그레스 바](#progress-bar)
  - [진행 상황과 함께 항목 처리](#processing-items-with-progress)
- [API 헬퍼](#api-helper)
- [파일 시스템 헬퍼](#file-system-helpers)
- [JSON 및 YAML 헬퍼](#json-and-yaml-helpers)
- [Dart 파일 조작](#dart-file-manipulation)
- [디렉토리 헬퍼](#directory-helpers)
- [대소문자 변환 헬퍼](#case-conversion-helpers)
- [프로젝트 경로 헬퍼](#project-path-helpers)
- [플랫폼 헬퍼](#platform-helpers)
- [Dart 및 Flutter 커맨드](#dart-and-flutter-commands)
- [유효성 검사 헬퍼](#validation-helpers)
- [파일 스캐폴딩](#file-scaffolding)
- [작업 러너](#task-runner)
- [테이블 출력](#table-output)
- [예시](#examples)
  - [현재 시간 커맨드](#current-time-command)
  - [폰트 다운로드 커맨드](#download-fonts-command)
  - [배포 파이프라인 커맨드](#deployment-pipeline-command)

<div id="introduction"></div>

## 소개

커맨드를 사용하면 {{ config('app.name') }}의 CLI를 커스텀 프로젝트별 도구로 확장할 수 있습니다. `NyCustomCommand`를 상속하여 반복적인 작업을 자동화하고, 배포 워크플로우를 구축하고, 코드를 생성하거나, 터미널에서 직접 필요한 기능을 추가할 수 있습니다.

모든 커스텀 커맨드는 파일 I/O, JSON/YAML, 대화형 프롬프트, 스피너, 프로그레스 바, API 요청 등을 위한 풍부한 내장 헬퍼 세트에 접근할 수 있습니다 -- 추가 패키지를 가져올 필요가 없습니다.

> **참고:** 커스텀 커맨드는 Flutter 런타임 외부에서 실행됩니다. 커맨드에서 `nylo_framework.dart`를 가져올 수 없습니다. 대신 `ny_cli.dart`를 사용하세요.

<div id="creating-commands"></div>

## 커맨드 생성

Metro 또는 Dart CLI를 사용하여 새 커맨드를 생성합니다:

```bash
metro make:command current_time
```

`--category` 옵션을 사용하여 커맨드의 카테고리를 지정할 수 있습니다:

```bash
metro make:command current_time --category="project"
```

이렇게 하면 `lib/app/commands/current_time.dart`에 새 파일이 생성되고 커맨드 레지스트리에 등록됩니다.

<div id="command-structure"></div>

## 커맨드 구조

모든 커맨드는 `NyCustomCommand`를 확장하고 두 가지 핵심 메서드를 구현합니다:

- **`builder()`** -- 옵션과 플래그 설정
- **`handle()`** -- 커맨드 로직 실행

```dart
import 'package:nylo_framework/metro/ny_cli.dart';

void main(arguments) => _CurrentTimeCommand(arguments).run();

/// Current Time Command
///
/// Usage:
///   metro app:current_time
class _CurrentTimeCommand extends NyCustomCommand {
  _CurrentTimeCommand(super.arguments);

  @override
  CommandBuilder builder(CommandBuilder command) {
    command.addOption('format', defaultValue: 'HH:mm:ss');
    return command;
  }

  @override
  Future<void> handle(CommandResult result) async {
    final format = result.getString("format");
    info("Current time format: $format");
  }
}
```

<div id="running-commands"></div>

## 커맨드 실행

Metro를 사용하여 커맨드를 실행합니다:

```bash
metro app:current_time
```

커맨드 이름은 `category:name` 패턴을 따릅니다. 인수 없이 `metro`를 실행하면 커스텀 커맨드가 **Custom Commands** 섹션에 나타납니다:

```
[Custom Commands]
  app:current_time
  project:install_firebase
  project:deploy
```

커맨드의 도움말을 표시하려면:

```bash
metro app:current_time --help
```

<div id="command-registry"></div>

## 커맨드 레지스트리

모든 커스텀 커맨드는 `lib/app/commands/commands.json`에 등록됩니다. 이 파일은 `make:command`를 사용할 때 자동으로 업데이트됩니다:

```json
[
  {
    "name": "install_firebase",
    "category": "project",
    "script": "install_firebase.dart"
  },
  {
    "name": "current_time",
    "category": "app",
    "script": "current_time.dart"
  }
]
```

각 항목은 다음과 같습니다:

| 필드 | 설명 |
|-------|-------------|
| `name` | 커맨드 이름 (카테고리 접두사 뒤에 사용) |
| `category` | 커맨드 카테고리 (예: `app`, `project`) |
| `script` | `lib/app/commands/`의 Dart 파일 |

<div id="options-and-flags"></div>

## 옵션과 플래그

`builder()` 메서드에서 `CommandBuilder`를 사용하여 커맨드의 옵션과 플래그를 설정합니다.

<div id="adding-options"></div>

### 옵션 추가

옵션은 사용자로부터 값을 받습니다:

```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addOption(
    'environment',
    abbr: 'e',
    help: '대상 배포 환경',
    defaultValue: 'development',
    allowed: ['development', 'staging', 'production'],
  );
  return command;
}
```

사용법:

```bash
metro project:deploy --environment=production
# 또는 약어 사용
metro project:deploy -e production
```

| 파라미터 | 타입 | 설명 |
|-----------|------|-------------|
| `name` | `String` | 옵션 이름 |
| `abbr` | `String?` | 단일 문자 약어 |
| `help` | `String?` | `--help`와 함께 표시되는 도움말 텍스트 |
| `allowed` | `List<String>?` | 허용되는 값으로 제한 |
| `defaultValue` | `String?` | 제공되지 않을 때의 기본값 |

<div id="adding-flags"></div>

### 플래그 추가

플래그는 부울 토글입니다:

```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addFlag(
    'verbose',
    abbr: 'v',
    help: '상세 출력 활성화',
    defaultValue: false,
  );
  return command;
}
```

사용법:

```bash
metro project:deploy --verbose
metro project:deploy -v
```

| 파라미터 | 타입 | 설명 |
|-----------|------|-------------|
| `name` | `String` | 플래그 이름 |
| `abbr` | `String?` | 단일 문자 약어 |
| `help` | `String?` | `--help`와 함께 표시되는 도움말 텍스트 |
| `defaultValue` | `bool` | 기본값 (기본: `false`) |

<div id="command-result"></div>

## 커맨드 결과

`handle()` 메서드는 파싱된 옵션, 플래그, 인수를 읽기 위한 타입 접근자가 있는 `CommandResult` 객체를 받습니다.

```dart
@override
Future<void> handle(CommandResult result) async {
  // 문자열 옵션 가져오기
  final name = result.getString('name');

  // 부울 플래그 가져오기
  final verbose = result.getBool('verbose');

  // 정수 옵션 가져오기
  final count = result.getInt('count');

  // 제네릭 타입 접근
  final value = result.get<String>('key');

  // 내장 플래그 확인
  if (result.hasForceFlag) { /* --force가 전달됨 */ }
  if (result.hasHelpFlag) { /* --help가 전달됨 */ }

  // 원시 인수
  List<String> allArgs = result.arguments;
  List<String> unparsed = result.rest;
}
```

| 메서드 / 속성 | 반환 타입 | 설명 |
|-------------------|---------|-------------|
| `getString(String name, {String? defaultValue})` | `String?` | 문자열 값 가져오기 |
| `getBool(String name, {bool? defaultValue})` | `bool?` | 부울 값 가져오기 |
| `getInt(String name, {int? defaultValue})` | `int?` | 정수 값 가져오기 |
| `get<T>(String name)` | `T?` | 타입이 지정된 값 가져오기 |
| `hasForceFlag` | `bool` | `--force`가 전달되었는지 여부 |
| `hasHelpFlag` | `bool` | `--help`가 전달되었는지 여부 |
| `arguments` | `List<String>` | 모든 커맨드라인 인수 |
| `rest` | `List<String>` | 파싱되지 않은 나머지 인수 |

<div id="interactive-input"></div>

## 대화형 입력

`NyCustomCommand`는 터미널에서 사용자 입력을 수집하기 위한 메서드를 제공합니다.

<div id="text-input"></div>

### 텍스트 입력

```dart
// 선택적 기본값으로 질문하기
final name = prompt('프로젝트 이름은 무엇입니까?', defaultValue: 'my_app');

// ask()는 prompt()의 별칭입니다
final description = ask('설명을 입력하세요:');
```

| 파라미터 | 타입 | 설명 |
|-----------|------|-------------|
| `question` | `String` | 표시할 질문 |
| `defaultValue` | `String` | 사용자가 Enter를 누를 때의 기본값 (기본: `''`) |

<div id="confirmation"></div>

### 확인

```dart
if (confirm('계속하시겠습니까?', defaultValue: true)) {
  await runProcess('flutter pub get');
} else {
  info('작업이 취소되었습니다');
}
```

| 파라미터 | 타입 | 설명 |
|-----------|------|-------------|
| `question` | `String` | 예/아니오 질문 |
| `defaultValue` | `bool` | 기본 답변 (기본: `false`) |

<div id="single-selection"></div>

### 단일 선택

```dart
final environment = select(
  '배포 환경을 선택하세요:',
  ['development', 'staging', 'production'],
  defaultOption: 'development',
);
```

| 파라미터 | 타입 | 설명 |
|-----------|------|-------------|
| `question` | `String` | 프롬프트 텍스트 |
| `options` | `List<String>` | 사용 가능한 선택지 |
| `defaultOption` | `String?` | 미리 선택된 옵션 |

<div id="multiple-selection"></div>

### 다중 선택

```dart
final packages = multiSelect(
  '설치할 패키지를 선택하세요:',
  ['firebase_auth', 'dio', 'provider', 'shared_preferences'],
);

if (packages.isNotEmpty) {
  addPackages(packages);
  await runProcess('flutter pub get');
}
```

사용자는 쉼표로 구분된 번호 또는 `"all"`을 입력합니다.

<div id="secret-input"></div>

### 비밀 입력

```dart
final apiKey = promptSecret('API 키를 입력하세요:');
```

입력이 터미널 표시에서 숨겨집니다. 에코 모드가 지원되지 않는 경우 보이는 입력으로 폴백합니다.

<div id="output-formatting"></div>

## 출력 포맷팅

콘솔에 스타일이 적용된 출력을 인쇄하는 메서드입니다:

```dart
@override
Future<void> handle(CommandResult result) async {
  info('파일 처리 중...');       // 파란색 텍스트
  error('작업 실패');         // 빨간색 텍스트
  success('배포 완료');    // 녹색 텍스트
  warning('오래된 패키지');       // 노란색 텍스트
  line('일반 텍스트 출력');         // 색상 없음
  comment('배경 메모');        // 회색 텍스트
  alert('중요 공지');         // 테두리가 있는 알림 박스
  newLine();                         // 한 줄 빈 줄
  newLine(3);                        // 세 줄 빈 줄

  // 오류와 함께 커맨드 종료
  abort('치명적 오류 발생');     // 빨간색으로 인쇄, 코드 1로 종료
}
```

| 메서드 | 설명 |
|--------|-------------|
| `info(String message)` | 파란색 텍스트 인쇄 |
| `error(String message)` | 빨간색 텍스트 인쇄 |
| `success(String message)` | 녹색 텍스트 인쇄 |
| `warning(String message)` | 노란색 텍스트 인쇄 |
| `line(String message)` | 일반 텍스트 인쇄 (색상 없음) |
| `newLine([int count = 1])` | 빈 줄 인쇄 |
| `comment(String message)` | 회색/음소거 텍스트 인쇄 |
| `alert(String message)` | 테두리가 있는 알림 박스 인쇄 |
| `abort([String? message, int exitCode = 1])` | 오류와 함께 커맨드 종료 |

<div id="spinner-and-progress"></div>

## 스피너와 진행 상황

스피너와 프로그레스 바는 오래 실행되는 작업 중에 시각적 피드백을 제공합니다.

<div id="using-withspinner"></div>

### withSpinner 사용

자동 스피너로 비동기 작업을 래핑합니다:

```dart
final projectFiles = await withSpinner(
  task: () async {
    await sleep(2);
    return ['pubspec.yaml', 'lib/main.dart', 'README.md'];
  },
  message: '프로젝트 구조 분석 중',
  successMessage: '프로젝트 분석 완료',
  errorMessage: '프로젝트 분석 실패',
);

info('${projectFiles.length}개의 주요 파일 발견');
```

| 파라미터 | 타입 | 설명 |
|-----------|------|-------------|
| `task` | `Future<T> Function()` | 실행할 비동기 함수 |
| `message` | `String` | 스피너 실행 중 표시되는 텍스트 |
| `successMessage` | `String?` | 성공 시 표시 |
| `errorMessage` | `String?` | 실패 시 표시 |

<div id="manual-spinner-control"></div>

### 수동 스피너 제어

다단계 워크플로우의 경우 스피너를 생성하고 수동으로 제어합니다:

```dart
final spinner = createSpinner('프로덕션에 배포 중');
spinner.start();

try {
  await runProcess('flutter clean', silent: true);
  spinner.update('릴리스 버전 빌드 중');

  await runProcess('flutter build web --release', silent: true);
  spinner.update('서버에 업로드 중');

  await runProcess('./deploy.sh', silent: true);
  spinner.stop(completionMessage: '배포 완료', success: true);
} catch (e) {
  spinner.stop(completionMessage: '배포 실패: $e', success: false);
}
```

**ConsoleSpinner 메서드:**

| 메서드 | 설명 |
|--------|-------------|
| `start([String? message])` | 스피너 애니메이션 시작 |
| `update(String message)` | 표시되는 메시지 변경 |
| `stop({String? completionMessage, bool success = true})` | 스피너 중지 |

<div id="progress-bar"></div>

### 프로그레스 바

프로그레스 바를 수동으로 생성하고 관리합니다:

```dart
final progress = progressBar(100, message: '파일 처리 중');
progress.start();

for (int i = 0; i < 100; i++) {
  await Future.delayed(Duration(milliseconds: 50));
  progress.tick();
}

progress.complete('모든 파일 처리 완료');
```

**ConsoleProgressBar 메서드:**

| 메서드 / 속성 | 설명 |
|-------------------|-------------|
| `start()` | 프로그레스 바 시작 |
| `tick([int amount = 1])` | 진행 상황 증가 |
| `update(int value)` | 진행 상황을 특정 값으로 설정 |
| `updateMessage(String newMessage)` | 표시되는 메시지 변경 |
| `complete([String? completionMessage])` | 선택적 메시지와 함께 완료 |
| `stop()` | 완료 없이 중지 |
| `current` | 현재 진행 값 (getter) |
| `percentage` | 백분율 0-100으로의 진행 상황 (getter) |

<div id="processing-items-with-progress"></div>

### 진행 상황과 함께 항목 처리

자동 진행 상황 추적으로 항목 목록을 처리합니다:

```dart
// 비동기 처리
final results = await withProgress<File, String>(
  items: findFiles('lib/', extension: '.dart'),
  process: (file, index) async {
    return file.path;
  },
  message: 'Dart 파일 분석 중',
  completionMessage: '분석 완료',
);

// 동기 처리
final upperItems = withProgressSync<String, String>(
  items: ['a', 'b', 'c', 'd', 'e'],
  process: (item, index) => item.toUpperCase(),
  message: '항목 변환 중',
);
```

<div id="api-helper"></div>

## API 헬퍼

`api` 헬퍼는 HTTP 요청을 위한 Dio의 간소화된 래퍼를 제공합니다:

```dart
// GET 요청
final userData = await api((request) =>
  request.get('https://api.example.com/users/1')
);

// POST 요청
final result = await api((request) =>
  request.post(
    'https://api.example.com/items',
    data: {'name': 'New Item', 'price': 19.99},
  )
);

// PUT 요청
final updateResult = await api((request) =>
  request.put(
    'https://api.example.com/items/42',
    data: {'name': 'Updated Item', 'price': 29.99},
  )
);

// DELETE 요청
final deleteResult = await api((request) =>
  request.delete('https://api.example.com/items/42')
);

// PATCH 요청
final patchResult = await api((request) =>
  request.patch(
    'https://api.example.com/items/42',
    data: {'price': 24.99},
  )
);

// 쿼리 파라미터 사용
final searchResults = await api((request) =>
  request.get(
    'https://api.example.com/search',
    queryParameters: {'q': 'keyword', 'limit': 10},
  )
);
```

더 나은 사용자 경험을 위해 `withSpinner`와 결합합니다:

```dart
final data = await withSpinner(
  task: () => api((request) =>
    request.get('https://api.example.com/config')
  ),
  message: '설정 로딩 중',
);
```

`ApiService`는 `get`, `post`, `put`, `delete`, `patch` 메서드를 지원하며, 각각 선택적 `queryParameters`, `data`, `options`, `cancelToken`을 받습니다.

<div id="file-system-helpers"></div>

## 파일 시스템 헬퍼

`dart:io`를 가져올 필요가 없는 내장 파일 시스템 헬퍼입니다:

```dart
// 존재 여부 확인
if (fileExists('lib/config/app.dart')) { /* ... */ }
if (directoryExists('lib/app/models')) { /* ... */ }

// 파일 읽기
String content = await readFile('pubspec.yaml');
String contentSync = readFileSync('pubspec.yaml');

// 파일 쓰기
await writeFile('lib/generated/output.dart', 'class Output {}');
writeFileSync('lib/generated/output.dart', 'class Output {}');

// 파일에 추가
await appendFile('log.txt', 'New log entry\n');

// 디렉토리 존재 확인
await ensureDirectory('lib/generated');

// 파일 삭제 및 복사
await deleteFile('lib/generated/output.dart');
await copyFile('lib/config/app.dart', 'lib/config/app.bak.dart');
```

| 메서드 | 설명 |
|--------|-------------|
| `fileExists(String path)` | 파일이 존재하면 `true` 반환 |
| `directoryExists(String path)` | 디렉토리가 존재하면 `true` 반환 |
| `readFile(String path)` | 파일을 문자열로 읽기 (비동기) |
| `readFileSync(String path)` | 파일을 문자열로 읽기 (동기) |
| `writeFile(String path, String content)` | 파일에 내용 쓰기 (비동기) |
| `writeFileSync(String path, String content)` | 파일에 내용 쓰기 (동기) |
| `appendFile(String path, String content)` | 파일에 내용 추가 |
| `ensureDirectory(String path)` | 디렉토리가 없으면 생성 |
| `deleteFile(String path)` | 파일 삭제 |
| `copyFile(String source, String destination)` | 파일 복사 |

<div id="json-and-yaml-helpers"></div>

## JSON 및 YAML 헬퍼

내장 헬퍼로 JSON 및 YAML 파일을 읽고 씁니다:

```dart
// JSON을 Map으로 읽기
Map<String, dynamic> config = await readJson('config.json');

// JSON을 List로 읽기
List<dynamic> items = await readJsonArray('lib/app/commands/commands.json');

// JSON 쓰기 (기본적으로 보기 좋게 인쇄)
await writeJson('output.json', {'name': 'MyApp', 'version': '1.0.0'});

// 압축 JSON 쓰기
await writeJson('output.json', data, pretty: false);

// JSON 배열 파일에 추가 (중복 방지 포함)
await appendToJsonArray(
  'lib/app/commands/commands.json',
  {'name': 'my_command', 'category': 'app', 'script': 'my_command.dart'},
  uniqueKey: 'name',
);

// YAML을 Map으로 읽기
Map<String, dynamic> pubspec = await readYaml('pubspec.yaml');
info('프로젝트: ${pubspec['name']}');
```

| 메서드 | 설명 |
|--------|-------------|
| `readJson(String path)` | JSON 파일을 `Map<String, dynamic>`으로 읽기 |
| `readJsonArray(String path)` | JSON 파일을 `List<dynamic>`으로 읽기 |
| `writeJson(String path, dynamic data, {bool pretty = true})` | 데이터를 JSON으로 쓰기 |
| `appendToJsonArray(String path, Map item, {String? uniqueKey})` | JSON 배열 파일에 추가 |
| `readYaml(String path)` | YAML 파일을 `Map<String, dynamic>`으로 읽기 |

<div id="dart-file-manipulation"></div>

## Dart 파일 조작

Dart 소스 파일을 프로그래밍 방식으로 편집하기 위한 헬퍼 -- 스캐폴딩 도구를 구축할 때 유용합니다:

```dart
// import 추가 (이미 있으면 건너뜀)
await addImport(
  'lib/bootstrap/providers.dart',
  "import '/app/providers/firebase_provider.dart';",
);

// 마지막 닫는 중괄호 앞에 코드 삽입
await insertBeforeClosingBrace(
  'lib/bootstrap/providers.dart',
  '  FirebaseProvider(),',
);

// 파일에 문자열이 포함되어 있는지 확인
bool hasImport = await fileContains(
  'lib/bootstrap/providers.dart',
  'firebase_provider',
);

// 파일이 정규식 패턴과 일치하는지 확인
bool hasClass = await fileContainsPattern(
  'lib/app/models/user.dart',
  RegExp(r'class User'),
);
```

| 메서드 | 설명 |
|--------|-------------|
| `addImport(String filePath, String importStatement)` | Dart 파일에 import 추가 (이미 있으면 건너뜀) |
| `insertBeforeClosingBrace(String filePath, String code)` | 파일의 마지막 `}` 앞에 코드 삽입 |
| `fileContains(String filePath, String identifier)` | 파일에 문자열이 포함되어 있는지 확인 |
| `fileContainsPattern(String filePath, Pattern pattern)` | 파일이 패턴과 일치하는지 확인 |

<div id="directory-helpers"></div>

## 디렉토리 헬퍼

디렉토리 작업 및 파일 찾기를 위한 헬퍼입니다:

```dart
// 디렉토리 내용 나열
var entities = listDirectory('lib/app/models');
var allEntities = listDirectory('lib/', recursive: true);

// 확장자로 파일 찾기
List<File> dartFiles = findFiles(
  'lib/app/models',
  extension: '.dart',
  recursive: true,
);

// 이름 패턴으로 파일 찾기
List<File> testFiles = findFiles(
  'test/',
  namePattern: RegExp(r'_test\.dart$'),
);

// 디렉토리 재귀적으로 삭제
await deleteDirectory('build/');

// 디렉토리 재귀적으로 복사
await copyDirectory('lib/templates', 'lib/generated');
```

| 메서드 | 설명 |
|--------|-------------|
| `listDirectory(String path, {bool recursive = false})` | 디렉토리 내용 나열 |
| `findFiles(String directory, {String? extension, Pattern? namePattern, bool recursive = true})` | 조건에 맞는 파일 찾기 |
| `deleteDirectory(String path)` | 디렉토리 재귀적으로 삭제 |
| `copyDirectory(String source, String destination)` | 디렉토리 재귀적으로 복사 |

<div id="case-conversion-helpers"></div>

## 대소문자 변환 헬퍼

`recase` 패키지를 가져오지 않고 네이밍 규칙 간에 문자열을 변환합니다:

```dart
String input = 'user profile page';

snakeCase(input);    // user_profile_page
camelCase(input);    // userProfilePage
pascalCase(input);   // UserProfilePage
titleCase(input);    // User Profile Page
kebabCase(input);    // user-profile-page
constantCase(input); // USER_PROFILE_PAGE
```

| 메서드 | 출력 형식 | 예시 |
|--------|--------------|---------|
| `snakeCase(String input)` | `snake_case` | `user_profile` |
| `camelCase(String input)` | `camelCase` | `userProfile` |
| `pascalCase(String input)` | `PascalCase` | `UserProfile` |
| `titleCase(String input)` | `Title Case` | `User Profile` |
| `kebabCase(String input)` | `kebab-case` | `user-profile` |
| `constantCase(String input)` | `CONSTANT_CASE` | `USER_PROFILE` |

<div id="project-path-helpers"></div>

## 프로젝트 경로 헬퍼

표준 {{ config('app.name') }} 프로젝트 디렉토리에 대한 getter로, 프로젝트 루트 기준 상대 경로를 반환합니다:

```dart
info(modelsPath);       // lib/app/models
info(controllersPath);  // lib/app/controllers
info(widgetsPath);      // lib/resources/widgets
info(pagesPath);        // lib/resources/pages
info(commandsPath);     // lib/app/commands
info(configPath);       // lib/config
info(providersPath);    // lib/app/providers
info(eventsPath);       // lib/app/events
info(networkingPath);   // lib/app/networking
info(themesPath);       // lib/resources/themes

// 커스텀 경로 빌드
String customPath = projectPath('app/services/auth_service.dart');
// 반환: lib/app/services/auth_service.dart
```

| 속성 | 경로 |
|----------|------|
| `modelsPath` | `lib/app/models` |
| `controllersPath` | `lib/app/controllers` |
| `widgetsPath` | `lib/resources/widgets` |
| `pagesPath` | `lib/resources/pages` |
| `commandsPath` | `lib/app/commands` |
| `configPath` | `lib/config` |
| `providersPath` | `lib/app/providers` |
| `eventsPath` | `lib/app/events` |
| `networkingPath` | `lib/app/networking` |
| `themesPath` | `lib/resources/themes` |
| `projectPath(String relativePath)` | 프로젝트 내 상대 경로 해석 |

<div id="platform-helpers"></div>

## 플랫폼 헬퍼

플랫폼을 확인하고 환경 변수에 접근합니다:

```dart
if (isWindows) {
  info('Windows에서 실행 중');
} else if (isMacOS) {
  info('macOS에서 실행 중');
} else if (isLinux) {
  info('Linux에서 실행 중');
}

info('작업 디렉토리: $workingDirectory');

String home = env('HOME', '/default/path');
```

| 속성 / 메서드 | 설명 |
|-------------------|-------------|
| `isWindows` | Windows에서 실행 중이면 `true` |
| `isMacOS` | macOS에서 실행 중이면 `true` |
| `isLinux` | Linux에서 실행 중이면 `true` |
| `workingDirectory` | 현재 작업 디렉토리 경로 |
| `env(String key, [String defaultValue = ''])` | 시스템 환경 변수 읽기 |

<div id="dart-and-flutter-commands"></div>

## Dart 및 Flutter 커맨드

일반적인 Dart 및 Flutter CLI 커맨드를 헬퍼 메서드로 실행합니다. 각각 프로세스 종료 코드를 반환합니다:

```dart
// Dart 파일 또는 디렉토리 포맷
await dartFormat('lib/app/models/user.dart');

// dart analyze 실행
int analyzeResult = await dartAnalyze('lib/');

// flutter pub get 실행
await flutterPubGet();

// flutter clean 실행
await flutterClean();

// 추가 인수로 대상 빌드
await flutterBuild('apk', args: ['--release', '--split-per-abi']);
await flutterBuild('web', args: ['--release']);

// flutter test 실행
await flutterTest();
await flutterTest('test/unit/');
```

| 메서드 | 설명 |
|--------|-------------|
| `dartFormat(String path)` | 파일 또는 디렉토리에 `dart format` 실행 |
| `dartAnalyze([String? path])` | `dart analyze` 실행 |
| `flutterPubGet()` | `flutter pub get` 실행 |
| `flutterClean()` | `flutter clean` 실행 |
| `flutterBuild(String target, {List<String> args})` | `flutter build <target>` 실행 |
| `flutterTest([String? path])` | `flutter test` 실행 |

<div id="validation-helpers"></div>

## 유효성 검사 헬퍼

코드 생성을 위한 사용자 입력 검증 및 정리 헬퍼입니다:

```dart
// Dart 식별자 검증
if (!isValidDartIdentifier('MyClass')) {
  error('유효하지 않은 Dart 식별자');
}

// 비어 있지 않은 첫 번째 인수 요구 (누락 시 중단)
String name = requireArgument(result, message: '이름을 입력해 주세요');

// 클래스 이름 정리 (PascalCase, 접미사 제거)
String className = cleanClassName('user_model', removeSuffixes: ['_model']);
// 반환: 'User'

// 파일 이름 정리 (snake_case + 확장자)
String fileName = cleanFileName('UserModel', extension: '.dart');
// 반환: 'user_model.dart'
```

| 메서드 | 설명 |
|--------|-------------|
| `isValidDartIdentifier(String name)` | Dart 식별자 이름 검증 |
| `requireArgument(CommandResult result, {String? message})` | 비어 있지 않은 첫 번째 인수 요구 또는 중단 |
| `cleanClassName(String name, {List<String> removeSuffixes})` | 클래스 이름 정리 및 PascalCase 변환 |
| `cleanFileName(String name, {String extension = '.dart'})` | 파일 이름 정리 및 snake_case 변환 |

<div id="file-scaffolding"></div>

## 파일 스캐폴딩

스캐폴딩 시스템을 사용하여 하나 또는 여러 파일을 내용과 함께 생성합니다.

### 단일 파일

```dart
await scaffold(
  path: 'lib/app/services/auth_service.dart',
  content: '''
class AuthService {
  Future<bool> login(String email, String password) async {
    return false;
  }
}
''',
  force: false,
  successMessage: 'AuthService 생성됨',
);
```

| 파라미터 | 타입 | 설명 |
|-----------|------|-------------|
| `path` | `String` | 생성할 파일 경로 |
| `content` | `String` | 파일 내용 |
| `force` | `bool` | 존재하면 덮어쓰기 (기본: `false`) |
| `successMessage` | `String?` | 성공 시 표시되는 메시지 |

### 여러 파일

```dart
await scaffoldMany([
  ScaffoldFile(
    path: 'lib/app/models/product.dart',
    content: 'class Product {}',
    successMessage: 'Product Model 생성됨',
  ),
  ScaffoldFile(
    path: 'lib/app/networking/product_api_service.dart',
    content: 'class ProductApiService {}',
    successMessage: 'Product API Service 생성됨',
  ),
], force: false);
```

`ScaffoldFile` 클래스:

| 속성 | 타입 | 설명 |
|----------|------|-------------|
| `path` | `String` | 생성할 파일 경로 |
| `content` | `String` | 파일 내용 |
| `successMessage` | `String?` | 성공 시 표시되는 메시지 |

<div id="task-runner"></div>

## 작업 러너

자동 상태 출력과 함께 일련의 명명된 작업을 실행합니다.

### 기본 작업 러너

```dart
await runTasks([
  CommandTask(
    'Clean project',
    () => runProcess('flutter clean', silent: true),
  ),
  CommandTask(
    'Fetch dependencies',
    () => runProcess('flutter pub get', silent: true),
  ),
  CommandTask(
    'Run tests',
    () => runProcess('flutter test', silent: true),
    stopOnError: true,
  ),
]);
```

### 스피너가 있는 작업 러너

```dart
await runTasksWithSpinner([
  CommandTask(
    'Preparing release',
    () async {
      await flutterClean();
      await flutterPubGet();
    },
  ),
  CommandTask(
    'Building APK',
    () => flutterBuild('apk', args: ['--release']),
  ),
]);
```

`CommandTask` 클래스:

| 속성 | 타입 | 기본값 | 설명 |
|----------|------|---------|-------------|
| `name` | `String` | 필수 | 출력에 표시되는 작업 이름 |
| `action` | `Future<void> Function()` | 필수 | 실행할 비동기 함수 |
| `stopOnError` | `bool` | `true` | 실패 시 나머지 작업을 중지할지 여부 |

<div id="table-output"></div>

## 테이블 출력

콘솔에 포맷된 ASCII 테이블을 표시합니다:

```dart
table(
  ['Name', 'Version', 'Status'],
  [
    ['nylo_framework', '7.0.0', 'installed'],
    ['nylo_support', '7.0.0', 'installed'],
    ['dio', '5.4.0', 'installed'],
  ],
);
```

출력:

```
┌─────────────────┬─────────┬───────────┐
│ Name            │ Version │ Status    │
├─────────────────┼─────────┼───────────┤
│ nylo_framework  │ 7.0.0   │ installed │
│ nylo_support    │ 7.0.0   │ installed │
│ dio             │ 5.4.0   │ installed │
└─────────────────┴─────────┴───────────┘
```

<div id="examples"></div>

## 예시

<div id="current-time-command"></div>

### 현재 시간 커맨드

현재 시간을 표시하는 간단한 커맨드입니다:

```dart
import 'package:nylo_framework/metro/ny_cli.dart';

void main(arguments) => _CurrentTimeCommand(arguments).run();

class _CurrentTimeCommand extends NyCustomCommand {
  _CurrentTimeCommand(super.arguments);

  @override
  CommandBuilder builder(CommandBuilder command) {
    command.addOption('format', defaultValue: 'HH:mm:ss');
    return command;
  }

  @override
  Future<void> handle(CommandResult result) async {
    final format = result.getString("format");
    final now = DateTime.now();
    info("현재 시간: ${now.toIso8601String()}");
    info("요청된 형식: $format");
  }
}
```

<div id="download-fonts-command"></div>

### 폰트 다운로드 커맨드

Google Fonts를 다운로드하여 프로젝트에 설치하는 커맨드입니다:

```dart
import 'package:nylo_framework/metro/ny_cli.dart';

void main(arguments) => _DownloadFontsCommand(arguments).run();

class _DownloadFontsCommand extends NyCustomCommand {
  _DownloadFontsCommand(super.arguments);

  @override
  CommandBuilder builder(CommandBuilder command) {
    command.addOption('font', abbr: 'f', help: '폰트 패밀리 이름');
    command.addFlag('verbose', abbr: 'v', defaultValue: false);
    return command;
  }

  @override
  Future<void> handle(CommandResult result) async {
    final fontName = result.getString('font') ??
        prompt('폰트 패밀리 이름을 입력하세요:', defaultValue: 'Roboto');

    final verbose = result.getBool('verbose') ?? false;

    await withSpinner(
      task: () async {
        await ensureDirectory('assets/fonts');
        final fontData = await api((request) =>
          request.get('https://fonts.google.com/download?family=$fontName')
        );
        if (fontData != null) {
          await writeFile('assets/fonts/$fontName.ttf', fontData.toString());
        }
      },
      message: '$fontName 폰트 다운로드 중',
      successMessage: '$fontName 폰트 설치됨',
      errorMessage: '$fontName 다운로드 실패',
    );

    if (verbose) {
      info('폰트 저장 위치: assets/fonts/$fontName.ttf');
    }
  }
}
```

<div id="deployment-pipeline-command"></div>

### 배포 파이프라인 커맨드

작업 러너를 사용하여 전체 배포 파이프라인을 실행하는 커맨드입니다:

```dart
import 'package:nylo_framework/metro/ny_cli.dart';

void main(arguments) => _DeployCommand(arguments).run();

class _DeployCommand extends NyCustomCommand {
  _DeployCommand(super.arguments);

  @override
  CommandBuilder builder(CommandBuilder command) {
    command.addOption(
      'environment',
      abbr: 'e',
      defaultValue: 'development',
      allowed: ['development', 'staging', 'production'],
    );
    command.addFlag('skip-tests', defaultValue: false);
    return command;
  }

  @override
  Future<void> handle(CommandResult result) async {
    final env = result.getString('environment') ?? 'development';
    final skipTests = result.getBool('skip-tests') ?? false;

    alert('$env에 배포');
    newLine();

    if (env == 'production') {
      if (!confirm('프로덕션에 배포하시겠습니까?')) {
        abort('배포 취소됨');
      }
    }

    final tasks = <CommandTask>[
      CommandTask('Clean project', () => flutterClean()),
      CommandTask('Fetch dependencies', () => flutterPubGet()),
    ];

    if (!skipTests) {
      tasks.add(CommandTask(
        'Run tests',
        () => flutterTest(),
        stopOnError: true,
      ));
    }

    tasks.add(CommandTask(
      'Build for web',
      () => flutterBuild('web', args: ['--release']),
    ));

    await runTasksWithSpinner(tasks);

    newLine();
    success('$env 배포 완료');

    table(
      ['Step', 'Status'],
      tasks.map((t) => [t.name, 'Done']).toList(),
    );
  }
}
```
