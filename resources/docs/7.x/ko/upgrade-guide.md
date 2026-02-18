# 업그레이드 가이드

---

<a name="section-1"></a>
- [v7의 새로운 기능](#whats-new "v7의 새로운 기능")
- [주요 변경 사항 개요](#breaking-changes "주요 변경 사항 개요")
- [권장 마이그레이션 방법](#recommended-migration "권장 마이그레이션 방법")
- [빠른 마이그레이션 체크리스트](#checklist "빠른 마이그레이션 체크리스트")
- [단계별 마이그레이션 가이드](#migration-guide "단계별 마이그레이션 가이드")
  - [1단계: 의존성 업데이트](#step-1-dependencies "1단계: 의존성 업데이트")
  - [2단계: 환경 설정](#step-2-environment "2단계: 환경 설정")
  - [3단계: main.dart 업데이트](#step-3-main "3단계: main.dart 업데이트")
  - [4단계: boot.dart 업데이트](#step-4-boot "4단계: boot.dart 업데이트")
  - [5단계: 설정 파일 재구성](#step-5-config "5단계: 설정 파일 재구성")
  - [6단계: AppProvider 업데이트](#step-6-provider "6단계: AppProvider 업데이트")
  - [7단계: 테마 설정 업데이트](#step-7-theme "7단계: 테마 설정 업데이트")
  - [10단계: Widget 마이그레이션](#step-10-widgets "10단계: Widget 마이그레이션")
  - [11단계: Asset 경로 업데이트](#step-11-assets "11단계: Asset 경로 업데이트")
- [제거된 기능 및 대안](#removed-features "제거된 기능 및 대안")
- [삭제된 클래스 참조](#deleted-classes "삭제된 클래스 참조")
- [Widget 마이그레이션 참조](#widget-reference "Widget 마이그레이션 참조")
- [문제 해결](#troubleshooting "문제 해결")


<div id="whats-new"></div>

## v7의 새로운 기능

{{ config('app.name') }} v7은 개발자 경험을 크게 개선한 메이저 릴리스입니다:

### 암호화된 환경 설정
- 환경 변수가 빌드 시 보안을 위해 XOR 암호화됩니다
- 새로운 `metro make:key` 명령어로 APP_KEY 생성
- 새로운 `metro make:env` 명령어로 암호화된 `env.g.dart` 생성
- CI/CD 파이프라인을 위한 `--dart-define` APP_KEY 주입 지원

### 간소화된 부트 프로세스
- 새로운 `BootConfig` 패턴이 별도의 setup/finished 콜백을 대체
- 암호화된 환경을 위한 `env` 매개변수를 사용하는 더 깔끔한 `Nylo.init()`
- main.dart에서 직접 앱 라이프사이클 훅 사용

### 새로운 nylo.configure() API
- 단일 메서드로 모든 앱 설정 통합
- 개별 `nylo.add*()` 호출을 대체하는 더 깔끔한 구문
- Provider에서 별도의 `setup()` 및 `boot()` 라이프사이클 메서드

### 페이지용 NyPage
- `NyPage`가 페이지 Widget에서 `NyState`를 대체 (더 깔끔한 구문)
- `view()`가 `build()` 메서드를 대체
- `get init =>` getter가 `init()` 및 `boot()` 메서드를 대체
- `NyState`는 페이지가 아닌 Stateful Widget에서 계속 사용 가능

### LoadingStyle 시스템
- 일관된 로딩 상태를 위한 새로운 `LoadingStyle` 열거형
- 옵션: `LoadingStyle.normal()`, `LoadingStyle.skeletonizer()`, `LoadingStyle.none()`
- `LoadingStyle.normal(child: ...)`을 통한 커스텀 로딩 Widget

### RouteView 타입 안전 라우팅
- `static RouteView path`가 `static const path`를 대체
- Widget 팩토리를 사용한 타입 안전한 라우트 정의

### 멀티 테마 지원
- 여러 개의 다크 및 라이트 테마 등록 가능
- `.env` 파일 대신 코드에서 테마 ID 정의
- 테마 분류를 위한 새로운 `NyThemeType.dark` / `NyThemeType.light`
- 선호 테마 API: `NyTheme.setPreferredDark()`, `NyTheme.setPreferredLight()`
- 테마 열거: `NyTheme.lightThemes()`, `NyTheme.darkThemes()`, `NyTheme.all()`

### 새로운 Metro 명령어
- `make:key` - 암호화를 위한 APP_KEY 생성
- `make:env` - 암호화된 환경 파일 생성
- `make:bottom_sheet_modal` - Bottom Sheet Modal 생성
- `make:button` - 커스텀 버튼 생성

<a href="https://github.com/nylo-core/nylo/compare/6.x...7.x" target="_BLANK">GitHub에서 모든 변경 사항 보기</a>

<div id="breaking-changes"></div>

## 주요 변경 사항 개요

| 변경 사항 | v6 | v7 |
|--------|-----|-----|
| 앱 루트 Widget | `LocalizedApp(child: Main(nylo))` | `Main(nylo)` (`NyApp.materialApp()` 사용) |
| 페이지 State 클래스 | `NyState` | 페이지에 `NyPage` 사용 |
| View 메서드 | `build()` | `view()` |
| Init 메서드 | `init() async {}` / `boot() async {}` | `get init => () async {}` |
| 라우트 경로 | `static const path = '/home'` | `static RouteView path = ('/home', (_) => HomePage())` |
| Provider 부트 | `boot(Nylo nylo)` | `setup(Nylo nylo)` + `boot(Nylo nylo)` |
| 설정 | 개별 `nylo.add*()` 호출 | 단일 `nylo.configure()` 호출 |
| 테마 ID | `.env` 파일 (`LIGHT_THEME_ID`, `DARK_THEME_ID`) | 코드 (`type: NyThemeType.dark`) |
| 로딩 Widget | `useSkeletonizer` + `loading()` | `LoadingStyle` getter |
| 설정 위치 | `lib/config/` | `lib/bootstrap/` (decoders, events, providers, theme) |
| Asset 위치 | `public/` | `assets/` |

<div id="recommended-migration"></div>

## 권장 마이그레이션 방법

대규모 프로젝트의 경우, 새로운 v7 프로젝트를 생성하고 파일을 마이그레이션하는 것을 권장합니다:

1. 새 v7 프로젝트 생성: `git clone https://github.com/nylo-core/nylo.git my_app_v7 -b 7.x`
2. 페이지, Controller, Model, 서비스 복사
3. 위에 표시된 대로 구문 업데이트
4. 철저한 테스트 수행

이렇게 하면 최신 보일러플레이트 구조와 설정을 모두 갖출 수 있습니다.

v6과 v7 사이의 변경 사항 비교를 보려면, GitHub에서 비교를 확인할 수 있습니다: <a href="https://github.com/nylo-core/nylo/compare/6.x...7.x" target="_BLANK">https://github.com/nylo-core/nylo/compare/6.x...7.x</a>

<div id="checklist"></div>

## 빠른 마이그레이션 체크리스트

이 체크리스트를 사용하여 마이그레이션 진행 상황을 추적하세요:

- [ ] `pubspec.yaml` 업데이트 (Dart >=3.10.7, Flutter >=3.24.0, nylo_framework: ^7.0.0)
- [ ] `flutter pub get` 실행
- [ ] `metro make:key` 실행하여 APP_KEY 생성
- [ ] `metro make:env` 실행하여 암호화된 환경 생성
- [ ] `main.dart`에 env 매개변수 및 BootConfig 업데이트
- [ ] `Boot` 클래스를 `BootConfig` 패턴으로 변환
- [ ] 설정 파일을 `lib/config/`에서 `lib/bootstrap/`로 이동
- [ ] 새 설정 파일 생성 (`lib/config/app.dart`, `lib/config/storage_keys.dart`, `lib/config/toast_notification.dart`)
- [ ] `AppProvider`를 `nylo.configure()` 사용하도록 업데이트
- [ ] `.env`에서 `LIGHT_THEME_ID` 및 `DARK_THEME_ID` 제거
- [ ] 다크 테마 설정에 `type: NyThemeType.dark` 추가
- [ ] 모든 페이지 Widget에서 `NyState`를 `NyPage`로 이름 변경
- [ ] 모든 페이지에서 `build()`를 `view()`로 변경
- [ ] 모든 페이지에서 `init()/boot()`를 `get init =>`으로 변경
- [ ] `static const path`를 `static RouteView path`로 업데이트
- [ ] 라우트에서 `router.route()`를 `router.add()`로 변경
- [ ] Widget 이름 변경 (NyListView → CollectionView 등)
- [ ] Asset을 `public/`에서 `assets/`로 이동
- [ ] `pubspec.yaml` Asset 경로 업데이트
- [ ] Firebase import 제거 (사용 중인 경우 - 패키지를 직접 추가)
- [ ] NyDevPanel 사용 제거 (Flutter DevTools 사용)
- [ ] `flutter pub get` 실행 및 테스트

<div id="migration-guide"></div>

## 단계별 마이그레이션 가이드

<div id="step-1-dependencies"></div>

### 1단계: 의존성 업데이트

`pubspec.yaml`을 업데이트합니다:

``` yaml
environment:
  sdk: '>=3.10.7 <4.0.0'
  flutter: ">=3.24.0"

dependencies:
  nylo_framework: ^7.0.0
  # ... 기타 의존성
```

`flutter pub get`을 실행하여 패키지를 업데이트합니다.

<div id="step-2-environment"></div>

### 2단계: 환경 설정

v7은 보안 강화를 위해 암호화된 환경 변수가 필요합니다.

**1. APP_KEY 생성:**

``` bash
metro make:key
```

`.env` 파일에 `APP_KEY`가 추가됩니다.

**2. 암호화된 env.g.dart 생성:**

``` bash
metro make:env
```

암호화된 환경 변수가 포함된 `lib/bootstrap/env.g.dart`가 생성됩니다.

**3. .env에서 사용 중단된 테마 변수 제거:**

``` bash
# .env 파일에서 다음 줄을 제거하세요:
LIGHT_THEME_ID=...
DARK_THEME_ID=...
```

<div id="step-3-main"></div>

### 3단계: main.dart 업데이트

**v6:**
``` dart
import 'package:nylo_framework/nylo_framework.dart';
import 'bootstrap/boot.dart';

void main() async {
  await Nylo.init(
    setup: Boot.nylo,
    setupFinished: Boot.finished,
  );
}
```

**v7:**
``` dart
import '/bootstrap/env.g.dart';
import 'package:nylo_framework/nylo_framework.dart';
import 'bootstrap/boot.dart';

void main() async {
  await Nylo.init(
    env: Env.get,
    setup: Boot.nylo(),
    appLifecycle: {
      // 선택사항: 앱 라이프사이클 훅 추가
      // AppLifecycleState.resumed: () => print("App resumed"),
      // AppLifecycleState.paused: () => print("App paused"),
    },
  );
}
```

**주요 변경 사항:**
- 생성된 `env.g.dart`를 import
- `env` 매개변수에 `Env.get` 전달
- `Boot.nylo`가 이제 `Boot.nylo()` (`BootConfig` 반환)
- `setupFinished` 제거 (`BootConfig` 내에서 처리)
- 앱 상태 변경을 위한 선택적 `appLifecycle` 훅

<div id="step-4-boot"></div>

### 4단계: boot.dart 업데이트

**v6:**
``` dart
class Boot {
  static Future<Nylo> nylo() async {
    WidgetsFlutterBinding.ensureInitialized();

    if (getEnv('SHOW_SPLASH_SCREEN', defaultValue: false)) {
      runApp(SplashScreen.app());
    }

    await _setup();
    return await bootApplication(providers);
  }

  static Future<void> finished(Nylo nylo) async {
    await bootFinished(nylo, providers);
    runApp(Main(nylo));
  }
}
```

**v7:**
``` dart
class Boot {
  static BootConfig nylo() => BootConfig(
        setup: () async {
          WidgetsFlutterBinding.ensureInitialized();

          if (AppConfig.showSplashScreen) {
            runApp(SplashScreen.app());
          }

          await _init();
          return await setupApplication(providers);
        },
        boot: (Nylo nylo) async {
          await bootFinished(nylo, providers);
          runApp(Main(nylo));
        },
      );
}
```

**주요 변경 사항:**
- `Future<Nylo>` 대신 `BootConfig` 반환
- `setup`과 `finished`가 단일 `BootConfig` 객체로 결합
- `getEnv('SHOW_SPLASH_SCREEN')` → `AppConfig.showSplashScreen`
- `bootApplication` → `setupApplication`

<div id="step-5-config"></div>

### 5단계: 설정 파일 재구성

v7은 더 나은 구조를 위해 설정 파일을 재구성합니다:

| v6 위치 | v7 위치 | 작업 |
|-------------|-------------|--------|
| `lib/config/decoders.dart` | `lib/bootstrap/decoders.dart` | 이동 |
| `lib/config/events.dart` | `lib/bootstrap/events.dart` | 이동 |
| `lib/config/providers.dart` | `lib/bootstrap/providers.dart` | 이동 |
| `lib/config/theme.dart` | `lib/bootstrap/theme.dart` | 이동 |
| `lib/config/keys.dart` | `lib/config/storage_keys.dart` | 이름 변경 및 리팩터링 |
| (신규) | `lib/config/app.dart` | 생성 |
| (신규) | `lib/config/toast_notification.dart` | 생성 |

**lib/config/app.dart 생성:**

참조: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/app.dart" target="_BLANK">App Config</a>

``` dart
class AppConfig {
  // 애플리케이션 이름
  static final String appName = getEnv('APP_NAME', defaultValue: 'Nylo');

  // 애플리케이션 버전
  static final String version = getEnv('APP_VERSION', defaultValue: '1.0.0');

  // 여기에 기타 앱 설정을 추가하세요
}
```

**lib/config/storage_keys.dart 생성:**

참조: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/storage_keys.dart" target="_BLANK">Storage Keys</a>

``` dart
final class StorageKeysConfig {
  // 부팅 시 동기화할 키 정의
  static syncedOnBoot() => () async {
        return [
          auth,
          bearerToken,
          // coins.defaultValue(10), // 사용자에게 기본 10코인 지급
        ];
      };

  static StorageKey auth = 'SK_USER';

  static StorageKey bearerToken = 'SK_BEARER_TOKEN';

  // static StorageKey coins = 'SK_COINS';

  /// 여기에 스토리지 키를 추가하세요...
}
```

**lib/config/toast_notification.dart 생성:**

참조: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/toast_notification.dart" target="_BLANK">Toast Notification Config</a>

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class ToastNotificationConfig {
  static Map<ToastNotificationStyleMetaHelper, ToastMeta> styles = {
    // 여기에서 토스트 스타일을 커스터마이즈하세요
  };
}
```

<div id="step-6-provider"></div>

### 6단계: AppProvider 업데이트

**v6:**
``` dart
class AppProvider implements NyProvider {
  @override
  boot(Nylo nylo) async {
    await NyLocalization.instance.init(
      localeType: localeType,
      languageCode: languageCode,
      assetsDirectory: assetsDirectory,
    );

    nylo.addLoader(loader);
    nylo.addLogo(logo);
    nylo.addThemes(appThemes);
    nylo.addToastNotification(getToastNotificationWidget);
    nylo.addValidationRules(validationRules);
    nylo.addModelDecoders(modelDecoders);
    nylo.addControllers(controllers);
    nylo.addApiDecoders(apiDecoders);
    nylo.useErrorStack();
    nylo.addAuthKey(Keys.auth);
    await nylo.syncKeys(Keys.syncedOnBoot);

    return nylo;
  }

  @override
  afterBoot(Nylo nylo) async {}
}
```

**v7:**
``` dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    await nylo.configure(
      localization: NyLocalizationConfig(
        languageCode: LocalizationConfig.languageCode,
        localeType: LocalizationConfig.localeType,
        assetsDirectory: LocalizationConfig.assetsDirectory,
      ),
      loader: DesignConfig.loader,
      logo: DesignConfig.logo,
      themes: appThemes,
      initialThemeId: 'light_theme',
      toastNotifications: ToastNotificationConfig.styles,
      modelDecoders: modelDecoders,
      controllers: controllers,
      apiDecoders: apiDecoders,
      authKey: StorageKeysConfig.auth,
      syncKeys: StorageKeysConfig.syncedOnBoot,
      useErrorStack: true,
    );

    return nylo;
  }

  @override
  boot(Nylo nylo) async {}
}
```

**주요 변경 사항:**
- `boot()`가 이제 초기 설정을 위한 `setup()`으로 변경
- `boot()`는 이제 설정 후 로직에 사용 (이전의 `afterBoot`)
- 모든 `nylo.add*()` 호출이 단일 `nylo.configure()`로 통합
- 다국어 설정에 `NyLocalizationConfig` 객체 사용

<div id="step-7-theme"></div>

### 7단계: 테마 설정 업데이트

**v6 (.env 파일):**
``` bash
LIGHT_THEME_ID=default_light_theme
DARK_THEME_ID=default_dark_theme
```

**v6 (theme.dart):**
``` dart
final List<BaseThemeConfig> appThemes = [
  BaseThemeConfig(
    id: getEnv('LIGHT_THEME_ID'),
    description: "Light Theme",
    theme: lightTheme(),
    colors: LightThemeColors(),
  ),
  BaseThemeConfig(
    id: getEnv('DARK_THEME_ID'),
    description: "Dark Theme",
    theme: darkTheme(),
    colors: DarkThemeColors(),
  ),
];
```

**v7 (theme.dart):**
``` dart
final List<BaseThemeConfig<ColorStyles>> appThemes = [
  BaseThemeConfig<ColorStyles>(
    id: 'light_theme',
    theme: lightTheme,
    colors: LightThemeColors(),
    type: NyThemeType.light,
  ),
  BaseThemeConfig<ColorStyles>(
    id: 'dark_theme',
    theme: darkTheme,
    colors: DarkThemeColors(),
    type: NyThemeType.dark,
  ),
];
```

**주요 변경 사항:**
- `.env`에서 `LIGHT_THEME_ID` 및 `DARK_THEME_ID` 제거
- 코드에서 직접 테마 ID 정의
- 모든 다크 테마 설정에 `type: NyThemeType.dark` 추가
- 라이트 테마는 기본적으로 `NyThemeType.light`

**새로운 테마 API 메서드 (v7):**
``` dart
// 선호 테마 설정 및 기억
NyTheme.set(context, id: 'dark_theme', remember: true);

// 시스템 설정 따르기를 위한 선호 테마 설정
NyTheme.setPreferredDark('dark_theme');
NyTheme.setPreferredLight('light_theme');

// 선호 테마 ID 가져오기
String? darkId = NyTheme.preferredDarkId();
String? lightId = NyTheme.preferredLightId();

// 테마 열거
List<BaseThemeConfig> lights = NyTheme.lightThemes();
List<BaseThemeConfig> darks = NyTheme.darkThemes();
List<BaseThemeConfig> all = NyTheme.all();
BaseThemeConfig? theme = NyTheme.getById('dark_theme');
List<BaseThemeConfig> byType = NyTheme.getByType(NyThemeType.dark);

// 저장된 환경설정 초기화
NyTheme.clearSavedTheme();
```

<div id="step-10-widgets"></div>

### 10단계: Widget 마이그레이션

#### NyListView → CollectionView

**v6:**
``` dart
NyListView(
  child: (context, data) {
    return ListTile(title: Text(data.name));
  },
  data: () async => await api.getUsers(),
  loading: CircularProgressIndicator(),
)
```

**v7:**
``` dart
CollectionView<User>(
  data: () async => await api.getUsers(),
  builder: (context, item) => ListTile(
    title: Text(item.data.name),
  ),
  loadingStyle: LoadingStyle.normal(),
)

// 페이지네이션 사용 (당겨서 새로고침):
CollectionView<User>.pullable(
  data: (page) async => await api.getUsers(page: page),
  builder: (context, item) => ListTile(
    title: Text(item.data.name),
  ),
)
```

#### NyFutureBuilder → FutureWidget

**v6:**
``` dart
NyFutureBuilder(
  future: fetchData(),
  child: (context, data) => Text(data),
  loading: CircularProgressIndicator(),
)
```

**v7:**
``` dart
FutureWidget<String>(
  future: fetchData(),
  child: (context, data) => Text(data ?? ''),
  loadingStyle: LoadingStyle.normal(),
)
```

#### NyTextField → InputField

**v6:**
``` dart
NyTextField(
  controller: _controller,
  validationRules: "not_empty|email",
)
```

**v7:**
``` dart
InputField(
  controller: _controller,
  formValidator: FormValidator
                  .notEmpty()
                  .email(),
),
```

#### NyRichText → StyledText

**v6:**
``` dart
NyRichText(children: [
	Text("Hello", style: TextStyle(color: Colors.yellow)),
	Text(" WORLD ", style: TextStyle(color: Colors.blue)),
	Text("!", style: TextStyle(color: Colors.red)),
]),
```

**v7:**
``` dart
StyledText.template(
  "@{{Hello}} @{{WORLD}}@{{!}}",
  styles: {
    "Hello": TextStyle(color: Colors.yellow),
    "WORLD": TextStyle(color: Colors.blue),
    "!": TextStyle(color: Colors.red),
  },
)
```

#### NyLanguageSwitcher → LanguageSwitcher

**v6:**
``` dart
NyLanguageSwitcher(
  onLanguageChange: (locale) => print(locale),
)
```

**v7:**
``` dart
LanguageSwitcher(
  onLanguageChange: (locale) => print(locale),
)
```

<div id="step-11-assets"></div>

### 11단계: Asset 경로 업데이트

v7은 Asset 디렉토리를 `public/`에서 `assets/`로 변경합니다:

**1. Asset 폴더 이동:**
``` bash
# 디렉토리 이동
mv public/fonts assets/fonts
mv public/images assets/images
mv public/app_icon assets/app_icon
```

**2. pubspec.yaml 업데이트:**

**v6:**
``` yaml
flutter:
  assets:
    - public/fonts/
    - public/images/
    - public/app_icon/
```

**v7:**
``` yaml
flutter:
  assets:
    - assets/fonts/
    - assets/images/
    - assets/app_icon/
```

**3. 코드의 Asset 참조 업데이트:**

**v6:**
``` dart
Image.asset('public/images/logo.png')
```

**v7:**
``` dart
Image.asset('assets/images/logo.png')
```

<div id="removed-features"></div>

### LocalizedApp Widget - 제거됨

참조: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/resources/widgets/main_widget.dart" target="_BLANK">Main Widget</a>

**마이그레이션:** `Main(nylo)`을 직접 사용하세요. `NyApp.materialApp()`이 내부적으로 다국어를 처리합니다.

**v6:**
``` dart
runApp(LocalizedApp(child: Main(nylo)));
```

**v7:**
``` dart
runApp(Main(nylo));
```

<div id="deleted-classes"></div>

## 삭제된 클래스 참조

| 삭제된 클래스 | 대안 |
|---------------|-------------|
| `NyTextStyle` | Flutter의 `TextStyle`을 직접 사용 |
| `NyBaseApiService` | `DioApiService` 사용 |
| `BaseColorStyles` | `ThemeColor` 사용 |
| `LocalizedApp` | `Main(nylo)`을 직접 사용 |
| `NyException` | 표준 Dart 예외 사용 |
| `PushNotification` | `flutter_local_notifications`를 직접 사용 |
| `PushNotificationAttachments` | `flutter_local_notifications`를 직접 사용 |

<div id="widget-reference"></div>

## Widget 마이그레이션 참조

### 이름이 변경된 Widget

| v6 Widget | v7 Widget | 참고 |
|-----------|-----------|-------|
| `NyListView` | `CollectionView` | `child` 대신 `builder`를 사용하는 새 API |
| `NyFutureBuilder` | `FutureWidget` | 간소화된 비동기 Widget |
| `NyTextField` | `InputField` | `FormValidator` 사용 |
| `NyLanguageSwitcher` | `LanguageSwitcher` | 동일 API |
| `NyRichText` | `StyledText` | 동일 API |
| `NyFader` | `FadeOverlay` | 동일 API |

### 삭제된 Widget (직접 대체 없음)

| 삭제된 Widget | 대안 |
|----------------|-------------|
| `NyPullToRefresh` | `CollectionView.pullable()` 사용 |

### Widget 마이그레이션 예시

**NyPullToRefresh → CollectionView.pullable():**

**v6:**
``` dart
NyPullToRefresh(
  child: (context, data) => ListTile(title: Text(data.name)),
  data: (page) async => await fetchData(page),
)
```

**v7:**
``` dart
CollectionView<MyModel>.pullable(
  data: (page) async => await fetchData(page),
  builder: (context, item) => ListTile(title: Text(item.data.name)),
)
```

**NyFader → AnimatedOpacity:**

**v6:**
``` dart
NyFader(
  child: MyWidget(),
)
```

**v7:**
``` dart
FadeOverlay.bottom(
  child: MyWidget(),
);
```

<div id="troubleshooting"></div>

## 문제 해결

### "Env.get not found" 또는 "Env is not defined"

**해결:** 환경 생성 명령어를 실행하세요:
``` bash
metro make:key
metro make:env
```
그런 다음 `main.dart`에서 생성된 파일을 import하세요:
``` dart
import '/bootstrap/env.g.dart';
```

### "Theme not applying" 또는 "Dark theme not working"

**해결:** 다크 테마에 `type: NyThemeType.dark`가 있는지 확인하세요:
``` dart
BaseThemeConfig(
  id: 'dark_theme',
  description: "Dark Theme",
  theme: darkTheme(),
  colors: DarkThemeColors(),
  type: NyThemeType.dark, // 이 줄을 추가하세요
),
```

### "LocalizedApp not found"

참조: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/resources/widgets/main_widget.dart" target="_BLANK">Main Widget</a>

**해결:** `LocalizedApp`이 제거되었습니다. 다음과 같이 변경하세요:
``` dart
// 변경 전:
runApp(LocalizedApp(child: Main(nylo)));

// 변경 후:
runApp(Main(nylo));
```

### "router.route is not defined"

**해결:** `router.add()`를 대신 사용하세요:
``` dart
// 변경 전:
router.route(HomePage.path, (context) => HomePage());

// 변경 후:
router.add(HomePage.path);
```

### "NyListView not found"

**해결:** `NyListView`는 이제 `CollectionView`입니다:
``` dart
// 변경 전:
NyListView(...)

// 변경 후:
CollectionView<MyModel>(...)
```

### Asset이 로드되지 않음 (이미지, 폰트)

**해결:** Asset 경로를 `public/`에서 `assets/`로 업데이트하세요:
1. 파일 이동: `mv public/* assets/`
2. `pubspec.yaml` 경로 업데이트
3. 코드 참조 업데이트

### "init() must return a value of type Future"

**해결:** getter 구문으로 변경하세요:
``` dart
// 변경 전:
@override
init() async { ... }

// 변경 후:
@override
get init => () async { ... };
```

---

**도움이 필요하신가요?** [Nylo 문서](https://nylo.dev/docs/7.x)를 확인하거나 [GitHub](https://github.com/nylo-core/nylo/issues)에서 이슈를 열어주세요.
