# {{ config('app.name') }}에 기여하기

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- [시작하기](#getting-started "시작하기")
- [개발 환경](#development-environment "개발 환경")
- [개발 가이드라인](#development-guidelines "개발 가이드라인")
- [변경 사항 제출](#submitting-changes "변경 사항 제출")
- [이슈 보고](#reporting-issues "이슈 보고")


<div id="introduction"></div>

## 소개

{{ config('app.name') }}에 기여를 고려해주셔서 감사합니다!

이 가이드는 마이크로 프레임워크에 기여하는 방법을 이해하는 데 도움을 줍니다. 버그 수정, 기능 추가, 문서 개선 등 여러분의 기여는 {{ config('app.name') }} 커뮤니티에 소중합니다.

{{ config('app.name') }}은 세 개의 저장소로 나뉘어 있습니다:

| 저장소 | 목적 |
|------------|---------|
| <a href="https://github.com/nylo-core/nylo" target="_BLANK">nylo</a> | 보일러플레이트 애플리케이션 |
| <a href="https://github.com/nylo-core/framework" target="_BLANK">framework</a> | 코어 프레임워크 클래스 (nylo_framework) |
| <a href="https://github.com/nylo-core/support" target="_BLANK">support</a> | 위젯, 헬퍼, 유틸리티가 포함된 지원 라이브러리 (nylo_support) |

<div id="getting-started"></div>

## 시작하기

### 저장소 포크

기여하려는 저장소를 포크합니다:

- <a href="https://github.com/nylo-core/nylo/fork" target="_BLANK">Nylo Boilerplate 포크</a>
- <a href="https://github.com/nylo-core/framework/fork" target="_BLANK">Framework 포크</a>
- <a href="https://github.com/nylo-core/support/fork" target="_BLANK">Support 포크</a>

### 포크 클론

``` bash
git clone https://github.com/YOUR-USERNAME/nylo
git clone https://github.com/YOUR-USERNAME/framework
git clone https://github.com/YOUR-USERNAME/support
```

<div id="development-environment"></div>

## 개발 환경

### 요구사항

다음이 설치되어 있는지 확인합니다:

| 요구사항 | 최소 버전 |
|-------------|-----------------|
| Flutter | 3.24.0 이상 |
| Dart SDK | 3.10.7 이상 |

### 로컬 패키지 연결

에디터에서 Nylo 보일러플레이트를 열고 로컬 프레임워크 및 지원 저장소를 사용하도록 의존성 오버라이드를 추가합니다:

**pubspec.yaml**
``` yaml
dependency_overrides:
  nylo_framework:
    path: ../framework  # 로컬 프레임워크 저장소 경로
  nylo_support:
    path: ../support    # 로컬 지원 저장소 경로
```

`flutter pub get`을 실행하여 의존성을 설치합니다.

이제 프레임워크 또는 지원 저장소에 대한 변경 사항이 Nylo 보일러플레이트에 반영됩니다.

### 변경 사항 테스트

보일러플레이트 앱을 실행하여 변경 사항을 테스트합니다:

``` bash
flutter run
```

위젯이나 헬퍼 변경의 경우 해당 저장소에 테스트를 추가하는 것을 고려하세요.

<div id="development-guidelines"></div>

## 개발 가이드라인

### 코드 스타일

- 공식 <a href="https://dart.dev/guides/language/effective-dart/style" target="_BLANK">Dart 스타일 가이드</a>를 따릅니다
- 의미 있는 변수 및 함수 이름을 사용합니다
- 복잡한 로직에 대해 명확한 주석을 작성합니다
- 공개 API에 대한 문서를 포함합니다
- 코드를 모듈화하고 유지보수 가능하게 유지합니다

### 문서

새 기능을 추가할 때:

- 공개 클래스와 메서드에 dartdoc 주석을 추가합니다
- 필요한 경우 관련 문서 파일을 업데이트합니다
- 문서에 코드 예제를 포함합니다

### 테스트

변경 사항을 제출하기 전에:

- iOS와 Android 기기/시뮬레이터에서 테스트합니다
- 가능한 경우 하위 호환성을 확인합니다
- 호환성을 깨는 변경 사항을 명확하게 문서화합니다
- 기존 테스트를 실행하여 아무것도 깨지지 않았는지 확인합니다

<div id="submitting-changes"></div>

## 변경 사항 제출

### 먼저 논의

새로운 기능의 경우 먼저 커뮤니티와 논의하는 것이 좋습니다:

- <a href="https://github.com/nylo-core/nylo/discussions" target="_BLANK">GitHub Discussions</a>

### 브랜치 생성

``` bash
git checkout -b feature/your-feature-name
```

설명적인 브랜치 이름을 사용합니다:
- `feature/collection-view-pagination`
- `fix/storage-null-handling`
- `docs/update-networking-guide`

### 변경 사항 커밋

``` bash
git add .
git commit -m "Add: Your feature description"
```

명확한 커밋 메시지를 사용합니다:
- `Add: CollectionView grid mode support`
- `Fix: NyStorage returning null on first read`
- `Update: Improve router documentation`

### 푸시 및 Pull Request 생성

``` bash
git push origin feature/your-feature-name
```

그런 다음 GitHub에서 Pull Request를 생성합니다.

### Pull Request 가이드라인

- 변경 사항에 대한 명확한 설명을 제공합니다
- 관련 이슈를 참조합니다
- 해당되는 경우 스크린샷이나 코드 예제를 포함합니다
- PR이 하나의 관심사만 다루도록 합니다
- 변경 사항을 집중적이고 원자적으로 유지합니다

<div id="reporting-issues"></div>

## 이슈 보고

### 보고 전에

1. GitHub에 이미 이슈가 있는지 확인합니다
2. 최신 버전을 사용하고 있는지 확인합니다
3. 새 프로젝트에서 이슈를 재현해봅니다

### 보고 위치

적절한 저장소에 이슈를 보고합니다:

- **보일러플레이트 이슈**: <a href="https://github.com/nylo-core/nylo/issues" target="_BLANK">nylo/issues</a>
- **프레임워크 이슈**: <a href="https://github.com/nylo-core/framework/issues" target="_BLANK">framework/issues</a>
- **지원 라이브러리 이슈**: <a href="https://github.com/nylo-core/support/issues" target="_BLANK">support/issues</a>

### 이슈 템플릿

자세한 정보를 제공합니다:

``` markdown
### Description
Brief description of the issue

### Steps to Reproduce
1. Step one
2. Step two
3. Step three

### Expected Behavior
What should happen

### Actual Behavior
What actually happens

### Environment
- Flutter: 3.24.x
- Dart SDK: 3.10.x
- nylo_framework: ^7.0.0
- OS: macOS/Windows/Linux
- Device: iPhone 15/Pixel 8 (if applicable)

### Code Example
```dart
// Minimal code to reproduce the issue
```
```

### 버전 정보 확인

``` bash
# Flutter 및 Dart 버전
flutter --version

# pubspec.yaml에서 Nylo 버전 확인
# nylo_framework: ^7.0.0
```
