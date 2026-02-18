# App Icons

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- [앱 아이콘 생성하기](#generating-app-icons "앱 아이콘 생성하기")
- [앱 아이콘 추가하기](#adding-your-app-icon "앱 아이콘 추가하기")
- [앱 아이콘 요구사항](#app-icon-requirements "앱 아이콘 요구사항")
- [설정](#configuration "설정")
- [배지 카운트](#badge-count "배지 카운트")

<div id="introduction"></div>

## 소개

{{ config('app.name') }} v7은 하나의 소스 이미지로 iOS와 Android용 앱 아이콘을 생성하기 위해 <a href="https://pub.dev/packages/flutter_launcher_icons" target="_BLANK">flutter_launcher_icons</a>를 사용합니다.

앱 아이콘은 `assets/app_icon/` 디렉토리에 **1024x1024 픽셀** 크기로 배치해야 합니다.

<div id="generating-app-icons"></div>

## 앱 아이콘 생성하기

다음 명령어를 실행하여 모든 플랫폼의 아이콘을 생성합니다:

``` bash
dart run flutter_launcher_icons
```

이 명령어는 `assets/app_icon/`에서 소스 아이콘을 읽어 다음을 생성합니다:
- `ios/Runner/Assets.xcassets/AppIcon.appiconset/`에 iOS 아이콘
- `android/app/src/main/res/mipmap-*/`에 Android 아이콘

<div id="adding-your-app-icon"></div>

## 앱 아이콘 추가하기

1. **1024x1024 PNG** 파일로 아이콘을 생성합니다
2. `assets/app_icon/`에 배치합니다 (예: `assets/app_icon/icon.png`)
3. 필요한 경우 `pubspec.yaml`에서 `image_path`를 업데이트합니다:

``` yaml
flutter_launcher_icons:
  android: true
  ios: true
  image_path: "assets/app_icon/icon.png"
```

4. 아이콘 생성 명령어를 실행합니다

<div id="app-icon-requirements"></div>

## 앱 아이콘 요구사항

| 속성 | 값 |
|-----------|-------|
| 형식 | PNG |
| 크기 | 1024x1024 픽셀 |
| 레이어 | 투명도 없이 평탄화 |

### 파일 이름 지정

특수 문자 없이 간단한 파일 이름을 사용합니다:
- `app_icon.png`
- `icon.png`

### 플랫폼 가이드라인

자세한 요구사항은 공식 플랫폼 가이드라인을 참조하세요:
- <a href="https://developer.apple.com/design/human-interface-guidelines/app-icons" target="_BLANK">Apple Human Interface Guidelines - App Icons</a>
- <a href="https://developer.android.com/distribute/google-play/resources/icon-design-specifications" target="_BLANK">Google Play Icon Design Specifications</a>

<div id="configuration"></div>

## 설정

`pubspec.yaml`에서 아이콘 생성을 커스터마이즈합니다:

``` yaml
flutter_launcher_icons:
  android: true
  ios: true
  image_path: "assets/app_icon/icon.png"

  # 선택사항: 플랫폼별 다른 아이콘 사용
  # image_path_android: "assets/app_icon/android_icon.png"
  # image_path_ios: "assets/app_icon/ios_icon.png"

  # 선택사항: Android 적응형 아이콘
  # adaptive_icon_background: "#ffffff"
  # adaptive_icon_foreground: "assets/app_icon/foreground.png"

  # 선택사항: iOS 알파 채널 제거
  # remove_alpha_ios: true
```

사용 가능한 모든 옵션은 <a href="https://pub.dev/packages/flutter_launcher_icons" target="_BLANK">flutter_launcher_icons 문서</a>를 참조하세요.

<div id="badge-count"></div>

## 배지 카운트

{{ config('app.name') }}은 앱 배지 카운트(앱 아이콘에 표시되는 숫자)를 관리하기 위한 헬퍼 함수를 제공합니다:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// 배지 카운트를 5로 설정
await setBadgeNumber(5);

// 배지 카운트 초기화
await clearBadgeNumber();
```

### 플랫폼 지원

배지 카운트는 다음 플랫폼에서 지원됩니다:
- **iOS**: 네이티브 지원
- **Android**: 런처 지원 필요 (대부분의 런처가 지원)
- **Web**: 지원되지 않음

### 사용 사례

배지 카운트의 일반적인 시나리오:
- 읽지 않은 알림
- 대기 중인 메시지
- 장바구니 항목
- 미완료 작업

``` dart
// 예제: 새 메시지 도착 시 배지 업데이트
void onNewMessage() async {
  int unreadCount = await MessageService.getUnreadCount();
  await setBadgeNumber(unreadCount);
}

// 예제: 사용자가 메시지를 확인하면 배지 초기화
void onMessagesViewed() async {
  await clearBadgeNumber();
}
```
