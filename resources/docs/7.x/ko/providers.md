# Providers

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- [Provider 생성](#create-a-provider "Provider 생성")
- [Provider 객체](#provider-object "Provider 객체")


<div id="introduction"></div>

## Provider 소개

{{ config('app.name') }}에서 Provider는 애플리케이션이 실행될 때 <b>main.dart</b> 파일에서 초기에 부팅됩니다. 모든 Provider는 `/lib/app/providers/*`에 위치하며, 이 파일들을 수정하거나 <a href="/docs/7.x/metro#make-provider" target="_BLANK">Metro</a>를 사용하여 새 Provider를 생성할 수 있습니다.

Provider는 앱이 초기에 로드되기 전에 클래스, 패키지를 초기화하거나 무언가를 생성해야 할 때 사용할 수 있습니다. 예를 들어 `route_provider.dart` 클래스는 {{ config('app.name') }}에 모든 라우트를 추가하는 역할을 합니다.

### 자세히 살펴보기

```dart
import 'package:nylo_framework/nylo_framework.dart';
import 'bootstrap/boot.dart';

/// Nylo - Framework for Flutter Developers
/// Docs: https://nylo.dev/docs/7.x

/// Main entry point for the application.
void main() async {
  await Nylo.init(
    setup: Boot.nylo,
    setupFinished: Boot.finished,

    // showSplashScreen: true,
    // Uncomment showSplashScreen to show the splash screen
    // File: lib/resources/widgets/splash_screen.dart
  );
}
```

### 생명주기

- `Boot.{{ config('app.name') }}`은 <b>config/providers.dart</b> 파일에 등록된 Provider를 순회하며 부팅합니다.

- `Boot.Finished`는 **"Boot.{{ config('app.name') }}"**이 완료된 직후에 호출되며, 이 메서드는 {{ config('app.name') }} 인스턴스를 'nylo' 값으로 `Backpack`에 바인딩합니다.

예: Backpack.instance.read('nylo'); // {{ config('app.name') }} 인스턴스


<div id="create-a-provider"></div>

## 새 Provider 생성

터미널에서 아래 명령어를 실행하여 새 Provider를 생성할 수 있습니다.

```bash
metro make:provider cache_provider
```

<div id="provider-object"></div>

## Provider 객체

Provider에는 `setup(Nylo nylo)`과 `boot(Nylo nylo)` 두 가지 메서드가 있습니다.

앱이 처음 실행될 때 **setup** 메서드 내부의 코드가 먼저 실행됩니다. 아래 예시처럼 `Nylo` 객체를 조작할 수도 있습니다.

예시: `lib/app/providers/app_provider.dart`

```dart
class AppProvider extends NyProvider {

  @override
  Future<Nylo?> setup(Nylo nylo) async {
    await NyLocalization.instance.init(
        localeType: localeType,
        languageCode: languageCode,
        languagesList: languagesList,
        assetsDirectory: assetsDirectory,
        valuesAsMap: valuesAsMap);

    return nylo;
  }

  @override
  Future<void> boot(Nylo nylo) async {
    User user = await Auth.user();
    if (!user.isSubscribed) {
      await Auth.remove();
    }
  }
}
```

### 생명주기

1. `setup(Nylo nylo)` - Provider를 초기화합니다. `Nylo` 인스턴스 또는 `null`을 반환합니다.
2. `boot(Nylo nylo)` - 모든 Provider의 setup이 완료된 후 호출됩니다. 다른 Provider가 준비된 후에 의존하는 초기화에 사용합니다.

> `setup` 메서드 내부에서는 위 예시처럼 `Nylo` 인스턴스 또는 `null`을 **반환**해야 합니다.
