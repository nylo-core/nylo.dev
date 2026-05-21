# 딥 링크

---

<a name="section-1"></a>

- [소개](#introduction "소개")
- [시작하기](#getting-started "시작하기")
  - [딥 링크 활성화](#enable-deep-links "딥 링크 활성화")
  - [Provider 생성](#generate-a-provider "Provider 생성")
- [플랫폼 설정](#platform-configuration "플랫폼 설정")
  - [Android 설정](#android-configuration "Android 설정")
  - [iOS 설정](#ios-configuration "iOS 설정")
- [링크 인터셉트](#intercepting-links "링크 인터셉트")
- [폴백 라우트](#fallback-route "폴백 라우트")
- [테스트](#testing "테스트")
- [API 참조](#api-reference "API 참조")

<div id="introduction"></div>

## 소개

{{ config('app.name') }}는 [`app_links`](https://pub.dev/packages/app_links)를 기반으로 한 최고 수준의 딥 링크 지원을 제공합니다. Android App Links, iOS Universal Links, 커스텀 URL 스킴, Web URL로부터 들어오는 URI를 자동으로 캡처하여 등록된 라우트를 통해 라우팅합니다.

단 한 줄로 전체 파이프라인을 활성화할 수 있습니다:

```dart
nylo.useDeepLinks();
```

URI가 도착하면 {{ config('app.name') }}는 경로와 쿼리 매개변수를 추출하여 `routeTo()`를 호출합니다. 콜드 스타트(링크에 의해 앱이 열린 경우)는 네비게이터가 준비된 후 재실행되며, 웜 스타트 링크(앱 실행 중 수신)도 동일한 방식으로 처리됩니다.

<div id="getting-started"></div>

## 시작하기

<div id="enable-deep-links"></div>

### 딥 링크 활성화

임의의 provider의 `setup` 메서드 내에서 `useDeepLinks()`를 호출합니다:

```dart
import 'package:nylo_framework/nylo_framework.dart';

class DeepLinkProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.useDeepLinks();
    return nylo;
  }

  @override
  boot(Nylo nylo) async {}
}
```

앱 부팅 중에 실행되도록 `config/providers.dart`에 provider를 등록하세요.

<div id="generate-a-provider"></div>

### Provider 생성

Metro를 사용하여 provider를 자동으로 생성할 수 있습니다:

```bash
dart run nylo_framework:main make:deep_link_provider
```

이 명령은 `lib/app/providers/deep_link_provider.dart`를 생성하며, `useDeepLinks()`가 미리 연결되어 있고 `onIncomingLink` 예시가 주석으로 포함됩니다. 또한 provider가 `config/providers.dart`에 자동으로 추가됩니다.

<div id="platform-configuration"></div>

## 플랫폼 설정

<div id="android-configuration"></div>

### Android 설정

`android/app/src/main/AndroidManifest.xml`의 메인 `<activity>`에 `<intent-filter>`를 추가합니다. 아래 예시는 Android App Links(`https://example.com/...`)와 커스텀 스킴(`myapp://...`) 모두를 허용합니다:

```xml
<activity
    android:name=".MainActivity"
    android:exported="true"
    android:launchMode="singleTop">

    <!-- Standard Flutter intent-filter -->
    <intent-filter>
        <action android:name="android.intent.action.MAIN" />
        <category android:name="android.intent.category.LAUNCHER" />
    </intent-filter>

    <!-- Android App Links -->
    <intent-filter android:autoVerify="true">
        <action android:name="android.intent.action.VIEW" />
        <category android:name="android.intent.category.DEFAULT" />
        <category android:name="android.intent.category.BROWSABLE" />
        <data android:scheme="https"
              android:host="example.com" />
    </intent-filter>

    <!-- Custom URL scheme -->
    <intent-filter>
        <action android:name="android.intent.action.VIEW" />
        <category android:name="android.intent.category.DEFAULT" />
        <category android:name="android.intent.category.BROWSABLE" />
        <data android:scheme="myapp" />
    </intent-filter>
</activity>
```

검증된 App Links의 경우 `https://example.com/.well-known/assetlinks.json`에 `assetlinks.json` 파일을 호스팅하세요. 자세한 내용은 <a href="https://docs.flutter.dev/cookbook/navigation/set-up-app-links" target="_BLANK">Flutter App Links 가이드</a>를 참조하세요.

<div id="ios-configuration"></div>

### iOS 설정

**커스텀 URL 스킴**의 경우 `ios/Runner/Info.plist`에 `CFBundleURLTypes` 항목을 추가합니다:

```xml
<key>CFBundleURLTypes</key>
<array>
    <dict>
        <key>CFBundleURLName</key>
        <string>com.example.myapp</string>
        <key>CFBundleURLSchemes</key>
        <array>
            <string>myapp</string>
        </array>
    </dict>
</array>
```

**Universal Links**의 경우 Xcode에서 Associated Domains 기능을 활성화하고 도메인에 `apple-app-site-association` 파일을 호스팅하세요. 전체 설명은 <a href="https://docs.flutter.dev/cookbook/navigation/set-up-universal-links" target="_BLANK">Flutter Universal Links 가이드</a>를 참조하세요.

<div id="intercepting-links"></div>

## 링크 인터셉트

`onIncomingLink`를 사용하면 {{ config('app.name') }}가 URI를 라우팅하기 전에 로직을 실행할 수 있습니다. {{ config('app.name') }}가 자동으로 라우팅하도록 하려면 `true`를, URI를 직접 처리하려면 `false`를 반환합니다:

```dart
class DeepLinkProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.useDeepLinks();

    nylo.onIncomingLink((Uri uri) async {
      // Require auth on /account/* routes
      if (uri.path.startsWith('/account') && !(await Auth.isAuthenticated())) {
        routeTo(LoginPage.path);
        return false;
      }

      // Track analytics for every incoming link
      Analytics.track('deep_link_opened', {'path': uri.path});

      return true;
    });

    return nylo;
  }

  @override
  boot(Nylo nylo) async {}
}
```

콜백은 스킴, 호스트, 경로, 쿼리 매개변수를 포함한 전체 `Uri`를 수신합니다.

<div id="fallback-route"></div>

## 폴백 라우트

들어오는 URI의 경로가 등록되어 있지 않을 때 적절한 곳으로 이동하려면 `useDeepLinks()`에 `fallbackRoute`를 전달합니다:

```dart
nylo.useDeepLinks(fallbackRoute: HomePage.path);
```

URI의 경로가 등록된 라우트와 일치하면 {{ config('app.name') }}가 해당 라우트로 이동합니다. 일치하지 않으면 사용자는 폴백 라우트로 이동합니다.

<div id="testing"></div>

## 테스트

커맨드라인에서 들어오는 딥 링크를 시뮬레이션합니다:

```bash
# Android — custom scheme
adb shell am start -W -a android.intent.action.VIEW \
    -d "myapp://user/42?ref=test" com.example.myapp

# Android — App Link
adb shell am start -W -a android.intent.action.VIEW \
    -d "https://example.com/user/42?ref=test" com.example.myapp

# iOS Simulator
xcrun simctl openurl booted "myapp://user/42?ref=test"
```

콜드 스타트 동작을 확인하려면 테스트 사이에 앱을 종료하고, 웜 스타트를 확인하려면 앱이 포그라운드 상태일 때 URL을 트리거하세요.

<div id="api-reference"></div>

## API 참조

### useDeepLinks

```dart
void useDeepLinks({String? fallbackRoute})
```

플랫폼 딥 링크 캡처를 활성화합니다. provider의 `setup` 메서드 내에서 호출하세요.

| 매개변수 | 타입 | 설명 |
|-----------|------|-------------|
| `fallbackRoute` | `String?` | 들어오는 URI의 경로가 등록되어 있지 않을 때 이동할 라우트. |

### onIncomingLink

```dart
void onIncomingLink(Future<bool> Function(Uri uri) callback)
```

모든 들어오는 URI에 대해 호출되는 콜백을 등록합니다. {{ config('app.name') }}가 자동으로 라우팅하도록 하려면 `true`를, 자동 라우팅을 억제하려면 `false`를 반환합니다.

| 매개변수 | 타입 | 설명 |
|-----------|------|-------------|
| `callback` | `Future<bool> Function(Uri uri)` | 들어오는 URI를 수신합니다. |
