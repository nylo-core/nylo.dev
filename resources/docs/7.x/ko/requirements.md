# 요구 사항

---

<a name="section-1"></a>
- [시스템 요구 사항](#system-requirements "시스템 요구 사항")
- [Flutter 설치](#installing-flutter "Flutter 설치")
- [설치 확인](#verifying-installation "설치 확인")
- [에디터 설정](#set-up-an-editor "에디터 설정")


<div id="system-requirements"></div>

## 시스템 요구 사항

{{ config('app.name') }} v7은 다음 최소 버전이 필요합니다:

| 요구 사항 | 최소 버전 |
|-------------|-----------------|
| **Flutter** | 3.24.0 이상 |
| **Dart SDK** | 3.10.7 이상 |

### 플랫폼 지원

{{ config('app.name') }}는 Flutter가 지원하는 모든 플랫폼을 지원합니다:

| 플랫폼 | 지원 |
|----------|---------|
| iOS | ✓ 완전 지원 |
| Android | ✓ 완전 지원 |
| Web | ✓ 완전 지원 |
| macOS | ✓ 완전 지원 |
| Windows | ✓ 완전 지원 |
| Linux | ✓ 완전 지원 |

<div id="installing-flutter"></div>

## Flutter 설치

Flutter가 설치되어 있지 않다면, 운영체제에 맞는 공식 설치 가이드를 따르세요:

- <a href="https://docs.flutter.dev/get-started/install" target="_BLANK">Flutter 설치 가이드</a>

<div id="verifying-installation"></div>

## 설치 확인

Flutter 설치 후, 설정을 확인하세요:

### Flutter 버전 확인

``` bash
flutter --version
```

다음과 유사한 출력이 표시되어야 합니다:

```
Flutter 3.24.0 • channel stable
Dart SDK version: 3.10.7
```

### Flutter 업데이트 (필요한 경우)

Flutter 버전이 3.24.0 미만인 경우, 최신 안정 버전으로 업그레이드하세요:

``` bash
flutter channel stable
flutter upgrade
```

### Flutter Doctor 실행

개발 환경이 올바르게 구성되었는지 확인하세요:

``` bash
flutter doctor -v
```

이 명령어는 다음을 확인합니다:
- Flutter SDK 설치
- Android 툴체인 (Android 개발용)
- Xcode (iOS/macOS 개발용)
- 연결된 디바이스
- IDE 플러그인

{{ config('app.name') }} 설치를 진행하기 전에 보고된 문제를 모두 해결하세요.

<div id="set-up-an-editor"></div>

## 에디터 설정

Flutter를 지원하는 IDE를 선택하세요:

### Visual Studio Code (권장)

<a href="https://code.visualstudio.com" target="_BLANK">VS Code</a>는 가볍고 뛰어난 Flutter 지원을 제공합니다.

1. <a href="https://code.visualstudio.com" target="_BLANK">VS Code</a> 설치
2. <a href="https://marketplace.visualstudio.com/items?itemName=Dart-Code.flutter" target="_BLANK">Flutter 확장 프로그램</a> 설치
3. <a href="https://marketplace.visualstudio.com/items?itemName=Dart-Code.dart-code" target="_BLANK">Dart 확장 프로그램</a> 설치

설정 가이드: <a href="https://docs.flutter.dev/get-started/editor?tab=vscode" target="_BLANK">VS Code Flutter 설정</a>

### Android Studio

<a href="https://developer.android.com/studio" target="_BLANK">Android Studio</a>는 내장 에뮬레이터를 지원하는 완전한 기능의 IDE를 제공합니다.

1. <a href="https://developer.android.com/studio" target="_BLANK">Android Studio</a> 설치
2. Flutter 플러그인 설치 (Preferences → Plugins → Flutter)
3. Dart 플러그인 설치

설정 가이드: <a href="https://docs.flutter.dev/get-started/editor?tab=androidstudio" target="_BLANK">Android Studio Flutter 설정</a>

### IntelliJ IDEA

<a href="https://www.jetbrains.com/idea/" target="_BLANK">IntelliJ IDEA</a> (Community 또는 Ultimate)도 Flutter 개발을 지원합니다.

1. IntelliJ IDEA 설치
2. Flutter 플러그인 설치 (Preferences → Plugins → Flutter)
3. Dart 플러그인 설치

에디터가 구성되면, [{{ config('app.name') }} 설치](/docs/7.x/installation)를 진행할 준비가 됩니다.
