# Navigation Hub

---

<a name="section-1"></a>
- [Giriş](#introduction "Giriş")
- [Temel Kullanım](#basic-usage "Temel kullanım")
  - [Navigation Hub Oluşturma](#creating-a-navigation-hub "Navigation Hub oluşturma")
  - [Navigasyon Sekmeleri Oluşturma](#creating-navigation-tabs "Navigasyon sekmeleri oluşturma")
  - [Alt Navigasyon](#bottom-navigation "Alt navigasyon")
    - [Alt Navigasyon Stilleri](#bottom-nav-styles "Alt navigasyon stilleri")
    - [Özel Nav Bar Builder](#custom-nav-bar-builder "Özel Nav Bar Builder")
  - [Üst Navigasyon](#top-navigation "Üst navigasyon")
  - [Yolculuk Navigasyonu](#journey-navigation "Yolculuk navigasyonu")
    - [İlerleme Stilleri](#journey-progress-styles "İlerleme stilleri")
    - [Düğme Stilleri](#journey-button-styles "Düğme stilleri")
    - [JourneyState](#journey-state "JourneyState")
    - [JourneyState Yardımcı Metotları](#journey-state-helper-methods "JourneyState yardımcı metotları")
- [Sekme İçinde Gezinme](#navigating-within-a-tab "Sekme içinde gezinme")
- [Sekmeler](#tabs "Sekmeler")
  - [Sekmelere Rozet Ekleme](#adding-badges-to-tabs "Sekmelere rozet ekleme")
  - [Sekmelere Uyarı Ekleme](#adding-alerts-to-tabs "Sekmelere uyarı ekleme")
- [Durumu Koruma](#maintaining-state "Durumu koruma")
- [Durum Eylemleri](#state-actions "Durum eylemleri")
- [Yükleme Stili](#loading-style "Yükleme stili")
- [Navigation Hub Oluşturma](#creating-a-navigation-hub "Navigation Hub oluşturma")

<div id="introduction"></div>

## Giriş

Navigation Hub'lar, tüm widget'larınız için navigasyonu **yönetebileceğiniz** merkezi bir yerdir.
Kutudan çıktığı haliyle saniyeler içinde alt, üst ve yolculuk navigasyon düzenleri oluşturabilirsiniz.

Bir uygulamanız olduğunu ve alt navigasyon çubuğu ekleyerek kullanıcıların uygulamanızdaki farklı sekmeler arasında gezinmesini istediğinizi **hayal edelim**.

Bunu oluşturmak için bir Navigation Hub kullanabilirsiniz.

Uygulamanızda Navigation Hub'ı nasıl kullanabileceğinize bakalım.

<div id="basic-usage"></div>

## Temel Kullanım

Aşağıdaki komutu kullanarak bir Navigation Hub oluşturabilirsiniz.

``` bash
metro make:navigation_hub base
```

Bu, `resources/pages/` dizininizde bir **base_navigation_hub.dart** dosyası oluşturacaktır.

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';

class BaseNavigationHub extends NyStatefulWidget with BottomNavPageControls {
  static RouteView path = ("/base", (_) => BaseNavigationHub());

  BaseNavigationHub()
      : super(
            child: () => _BaseNavigationHubState(),
            stateName: path.stateName());

  /// State actions
  static NavigationHubStateActions stateActions = NavigationHubStateActions(path.stateName());
}

class _BaseNavigationHubState extends NavigationHub<BaseNavigationHub> {

  /// Layouts:
  /// - [NavigationHubLayout.bottomNav] Bottom navigation
  /// - [NavigationHubLayout.topNav] Top navigation
  /// - [NavigationHubLayout.journey] Journey navigation
  NavigationHubLayout? layout = NavigationHubLayout.bottomNav(
    // backgroundColor: Colors.white,
  );

  /// Should the state be maintained
  @override
  bool get maintainState => true;

  /// Navigation pages
  _BaseNavigationHubState() : super(() async {
    return {
      0: NavigationTab(
        title: "Home",
        page: HomeTab(),
        icon: Icon(Icons.home),
        activeIcon: Icon(Icons.home),
      ),
      1: NavigationTab(
         title: "Settings",
         page: SettingsTab(),
         icon: Icon(Icons.settings),
         activeIcon: Icon(Icons.settings),
      ),
    };
  });
}
```

Navigation Hub'ın **iki** sekmesi olduğunu görebilirsiniz: Home ve Settings.

Navigation Hub'a NavigationTab'lar ekleyerek daha fazla sekme oluşturabilirsiniz.

Öncelikle, Metro kullanarak yeni bir widget oluşturmanız gerekir.

``` bash
metro make:stateful_widget create_advert_tab
```

Aynı anda birden fazla widget de oluşturabilirsiniz.

``` bash
metro make:stateful_widget news_tab,notifications_tab
```

Ardından, yeni widget'ı Navigation Hub'a ekleyebilirsiniz.

``` dart
  _BaseNavigationHubState() : super(() async {
    return {
      0: NavigationTab(
        title: "Home",
        page: HomeTab(),
        icon: Icon(Icons.home),
        activeIcon: Icon(Icons.home),
      ),
      1: NavigationTab(
         title: "Settings",
         page: SettingsTab(),
         icon: Icon(Icons.settings),
         activeIcon: Icon(Icons.settings),
      ),
      2: NavigationTab(
         title: "News",
         page: NewsTab(),
         icon: Icon(Icons.newspaper),
         activeIcon: Icon(Icons.newspaper),
      ),
    };
  });

import 'package:nylo_framework/nylo_framework.dart';

appRouter() => nyRoutes((router) {
    ...
    router.add(BaseNavigationHub.path).initalRoute();
});

// or navigate to the Navigation Hub from anywhere in your app

routeTo(BaseNavigationHub.path);
```

Navigation Hub ile yapabileceğiniz çok **daha fazla** şey var, bazı özelliklerine bakalım.

<div id="bottom-navigation"></div>

### Alt Navigasyon

**layout**'u `NavigationHubLayout.bottomNav` kullanacak şekilde ayarlayarak düzeni alt navigasyon çubuğuna değiştirebilirsiniz.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav();
```

Alt navigasyon çubuğunu aşağıdaki gibi özellikler ayarlayarak özelleştirebilirsiniz:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav(
        // customize the bottomNav layout properties
    );
```

<div id="bottom-nav-styles"></div>

### Alt Navigasyon Stilleri

`style` parametresini kullanarak alt navigasyon çubuğunuza hazır stiller uygulayabilirsiniz.

Nylo birkaç yerleşik stil sunar:

- `BottomNavStyle.material()` - Varsayılan Flutter material stili
- `BottomNavStyle.glass()` - iOS 26 tarzı bulanık cam efekti
- `BottomNavStyle.floating()` - Yuvarlak köşeli yüzen hap şeklinde navigasyon çubuğu

#### Glass Stili

Glass stili, modern iOS 26 ilhamli tasarımlar için mükemmel olan güzel bir buzlu cam efekti oluşturur.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav(
        style: BottomNavStyle.glass(),
    );
```

Glass efektini özelleştirebilirsiniz:

``` dart
NavigationHubLayout.bottomNav(
    style: BottomNavStyle.glass(
        blur: 15.0,                              // Blur intensity
        opacity: 0.7,                            // Background opacity
        borderRadius: BorderRadius.circular(20), // Rounded corners
        margin: EdgeInsets.all(16),              // Float above the edge
        backgroundColor: Colors.white.withValues(alpha: 0.8),
    ),
)
```

#### Floating Stili

Floating stili, alt kenarın üzerinde yüzen hap şeklinde bir navigasyon çubuğu oluşturur.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav(
        style: BottomNavStyle.floating(),
    );
```

Floating stilini özelleştirebilirsiniz:

``` dart
NavigationHubLayout.bottomNav(
    style: BottomNavStyle.floating(
        borderRadius: BorderRadius.circular(30),
        margin: EdgeInsets.symmetric(horizontal: 24, vertical: 16),
        shadow: BoxShadow(
            color: Colors.black.withValues(alpha: 0.1),
            blurRadius: 10,
        ),
        backgroundColor: Colors.white,
    ),
)
```

<div id="custom-nav-bar-builder"></div>

### Özel Nav Bar Builder

Navigasyon çubuğunuz üzerinde tam kontrol için `navBarBuilder` parametresini kullanabilirsiniz.

Bu, navigasyon verilerini alırken herhangi bir özel widget oluşturmanızı sağlar.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav(
        navBarBuilder: (context, data) {
            return MyCustomNavBar(
                items: data.items,
                currentIndex: data.currentIndex,
                onTap: data.onTap,
            );
        },
    );
```

`NavBarData` nesnesi şunları içerir:

| Özellik | Tür | Açıklama |
| --- | --- | --- |
| `items` | `List<BottomNavigationBarItem>` | Navigasyon çubuğu öğeleri |
| `currentIndex` | `int` | Şu anda seçili olan indeks |
| `onTap` | `ValueChanged<int>` | Bir sekmeye dokunulduğunda çağrılan callback |

İşte tamamen özel bir glass navigasyon çubuğu örneği:

``` dart
NavigationHubLayout.bottomNav(
    navBarBuilder: (context, data) {
        return Padding(
            padding: EdgeInsets.all(16),
            child: ClipRRect(
                borderRadius: BorderRadius.circular(25),
                child: BackdropFilter(
                    filter: ImageFilter.blur(sigmaX: 10, sigmaY: 10),
                    child: Container(
                        decoration: BoxDecoration(
                            color: Colors.white.withValues(alpha: 0.7),
                            borderRadius: BorderRadius.circular(25),
                        ),
                        child: BottomNavigationBar(
                            items: data.items,
                            currentIndex: data.currentIndex,
                            onTap: data.onTap,
                            backgroundColor: Colors.transparent,
                            elevation: 0,
                        ),
                    ),
                ),
            ),
        );
    },
)
```

> **Not:** `navBarBuilder` kullanıldığında `style` parametresi yok sayılır.

<div id="top-navigation"></div>

### Üst Navigasyon

**layout**'u `NavigationHubLayout.topNav` kullanacak şekilde ayarlayarak düzeni üst navigasyon çubuğuna değiştirebilirsiniz.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.topNav();
```

Üst navigasyon çubuğunu aşağıdaki gibi özellikler ayarlayarak özelleştirebilirsiniz:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.topNav(
        // customize the topNav layout properties
    );
```

<div id="journey-navigation"></div>

### Yolculuk Navigasyonu

**layout**'u `NavigationHubLayout.journey` kullanacak şekilde ayarlayarak düzeni yolculuk navigasyonuna değiştirebilirsiniz.

Bu, başlangıç akışları veya çok adımlı formlar için harikadır.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        // customize the journey layout properties
    );
```

Yolculuk navigasyon düzenini kullanmak istiyorsanız, **widget'larınız** `JourneyState` kullanmalıdır çünkü yolculuğu yönetmenize yardımcı olacak birçok yardımcı metot içerir.

Aşağıdaki komutu kullanarak bir JourneyState oluşturabilirsiniz.

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```
Bu, **resources/widgets/** dizininizde `welcome.dart`, `phone_number_step.dart` ve `add_photos_step.dart` dosyalarını oluşturacaktır.

Ardından yeni widget'ları Navigation Hub'a ekleyebilirsiniz.

``` dart
_MyNavigationHubState() : super(() async {
    return {
        0: NavigationTab.journey(
            page: Welcome(),
        ),
        1: NavigationTab.journey(
            page: PhoneNumberStep(),
        ),
        2: NavigationTab.journey(
            page: AddPhotosStep(),
        ),
    };
});
```

Bir `buttonStyle` tanımlarsanız, yolculuk navigasyon düzeni geri ve ileri düğmelerini sizin için otomatik olarak yönetecektir.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        buttonStyle: JourneyButtonStyle.standard(
            // Customize button properties
        ),
    );
```

Widget'larınızdaki mantığı da özelleştirebilirsiniz.

``` dart
import 'package:flutter/material.dart';
import '/resources/pages/onboarding_navigation_hub.dart';
import '/resources/widgets/buttons/buttons.dart';
import 'package:nylo_framework/nylo_framework.dart';

class WelcomeStep extends StatefulWidget {
  const WelcomeStep({super.key});

  @override
  createState() => _WelcomeStepState();
}

class _WelcomeStepState extends JourneyState<WelcomeStep> {
  _WelcomeStepState() : super(
      navigationHubState: OnboardingNavigationHub.path.stateName());

  @override
  get init => () {
    // Your initialization logic here
  };

  @override
  Widget view(BuildContext context) {
    return buildJourneyContent(
      content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text('WelcomeStep', style: Theme.of(context).textTheme.headlineMedium),
          const SizedBox(height: 20),
          Text('This onboarding journey will help you get started.'),
        ],
      ),
      nextButton: Button.primary(
        text: isLastStep ? "Get Started" : "Continue",
        onPressed: onNextPressed,
      ),
      backButton: isFirstStep ? null : Button.textOnly(
        text: "Back",
        textColor: Colors.black87,
        onPressed: onBackPressed,
      ),
    );
  }

  /// Check if the journey can continue to the next step
  /// Override this method to add validation logic
  Future<bool> canContinue() async {
    // Perform your validation logic here
    // Return true if the journey can continue, false otherwise
    return true;
  }

  /// Called when unable to continue (canContinue returns false)
  /// Override this method to handle validation failures
  Future<void> onCannotContinue() async {
    showToastSorry(description: "You cannot continue");
  }

  /// Called before navigating to the next step
  /// Override this method to perform actions before continuing
  Future<void> onBeforeNext() async {
    // E.g. save data here before navigating
  }

  /// Called after navigating to the next step
  /// Override this method to perform actions after continuing
  Future<void> onAfterNext() async {
    // print('Navigated to the next step');
  }

  /// Called when the journey is complete (at the last step)
  /// Override this method to perform completion actions
  Future<void> onComplete() async {}
}
```

`JourneyState` sınıfındaki herhangi bir metodu geçersiz kılabilirsiniz.

<div id="journey-progress-styles"></div>

### Yolculuk İlerleme Stilleri

İlerleme göstergesi stilini `JourneyProgressStyle` sınıfını kullanarak özelleştirebilirsiniz.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle.linear(
            activeColor: Colors.blue,
            inactiveColor: Colors.grey,
            thickness: 4.0,
        ),
    );
```

Aşağıdaki ilerleme stillerini kullanabilirsiniz:

- `JourneyProgressIndicator.none`: Hiçbir şey render etmez — belirli bir sekmede göstergeyi gizlemek için kullanışlıdır.
- `JourneyProgressIndicator.linear`: Doğrusal ilerleme göstergesi.
- `JourneyProgressIndicator.dots`: Nokta tabanlı ilerleme göstergesi.
- `JourneyProgressIndicator.numbered`: Numaralı adım ilerleme göstergesi.
- `JourneyProgressIndicator.segments`: Bölümlü ilerleme çubuğu stili.
- `JourneyProgressIndicator.circular`: Dairesel ilerleme göstergesi.
- `JourneyProgressIndicator.timeline`: Zaman çizelgesi stili ilerleme göstergesi.
- `JourneyProgressIndicator.custom`: Bir oluşturucu fonksiyon kullanan özel ilerleme göstergesi.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle.custom(
            builder: (context, currentStep, totalSteps, percentage) {
                return LinearProgressIndicator(
                    value: percentage,
                    backgroundColor: Colors.grey[200],
                    color: Colors.blue,
                    minHeight: 4.0,
                );
            },
        ),
    );
```

İlerleme göstergesi konumunu ve dolgusunu `JourneyProgressStyle` içinde özelleştirebilirsiniz:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle(
            indicator: JourneyProgressIndicator.dots(),
            position: ProgressIndicatorPosition.bottom,
            padding: EdgeInsets.symmetric(horizontal: 16.0, vertical: 8.0),
        ),
    );
```

Aşağıdaki ilerleme göstergesi konumlarını kullanabilirsiniz:

- `ProgressIndicatorPosition.top`: Ekranın üstünde ilerleme göstergesi.
- `ProgressIndicatorPosition.bottom`: Ekranın altında ilerleme göstergesi.

#### Sekme Bazında İlerleme Stili Geçersiz Kılma

`NavigationTab.journey(progressStyle: ...)` kullanarak layout düzeyindeki `progressStyle`'ı tek tek sekmeler için geçersiz kılabilirsiniz. Kendi `progressStyle`'ı olmayan sekmeler layout varsayılanını devralır. Layout varsayılanı ve sekme bazında stil olmayan sekmeler ilerleme göstergesi göstermez.

``` dart
_MyNavigationHubState() : super(() async {
    return {
        0: NavigationTab.journey(
            page: Welcome(),
        ),
        1: NavigationTab.journey(
            page: PhoneNumberStep(),
            progressStyle: JourneyProgressStyle(
                indicator: JourneyProgressIndicator.numbered(),
            ), // overrides the layout default for this tab only
        ),
        2: NavigationTab.journey(
            page: AddPhotosStep(),
        ),
    };
});
```

<div id="journey-button-styles">
<br>

### Yolculuk Düğme Stilleri

Bir başlangıç akışı oluşturmak istiyorsanız, `NavigationHubLayout.journey` sınıfında `buttonStyle` özelliğini ayarlayabilirsiniz.

Kutudan çıktığı haliyle aşağıdaki düğme stillerini kullanabilirsiniz:

- `JourneyButtonStyle.standard`: Özelleştirilebilir özelliklere sahip standart düğme stili.
- `JourneyButtonStyle.minimal`: Yalnızca simgeli minimal düğme stili.
- `JourneyButtonStyle.outlined`: Çerçeveli düğme stili.
- `JourneyButtonStyle.contained`: Dolu düğme stili.
- `JourneyButtonStyle.custom`: Oluşturucu fonksiyonlar kullanan özel düğme stili.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle.linear(),
        buttonStyle: JourneyButtonStyle.standard(
            // Customize button properties
        ),
    );
```

<div id="journey-state"></div>

### JourneyState

`JourneyState` sınıfı, yolculuğu yönetmenize yardımcı olacak birçok yardımcı metot içerir.

Yeni bir `JourneyState` oluşturmak için aşağıdaki komutu kullanabilirsiniz.

``` bash
metro make:journey_widget onboard_user_dob
```

Veya aynı anda birden fazla widget oluşturmak istiyorsanız, aşağıdaki komutu kullanabilirsiniz.

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

Bu, **resources/widgets/** dizininizde `welcome.dart`, `phone_number_step.dart` ve `add_photos_step.dart` dosyalarını oluşturacaktır.

Ardından yeni widget'ları Navigation Hub'a ekleyebilirsiniz.

``` dart
_MyNavigationHubState() : super(() async {
    return {
        0: NavigationTab.journey(
            page: Welcome(),
        ),
        1: NavigationTab.journey(
            page: PhoneNumberStep(),
        ),
        2: NavigationTab.journey(
            page: AddPhotosStep(),
        ),
    };
});
```

`WelcomeStep` sınıfına bakarsak, `JourneyState` sınıfını genişlettiğini görebiliriz.

``` dart
...
class _WelcomeTabState extends JourneyState<WelcomeTab> {
  _WelcomeTabState() : super(
      navigationHubState: BaseNavigationHub.path.stateName());

  @override
  get init => () {
    // Your initialization logic here
  };

  @override
  Widget view(BuildContext context) {
    return buildJourneyContent(
      content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text('WelcomeTab', style: Theme.of(context).textTheme.headlineMedium),
          const SizedBox(height: 20),
          Text('This onboarding journey will help you get started.'),
        ],
      ),
    );
  }
```

**JourneyState** sınıfının sayfanın içeriğini oluşturmak için `buildJourneyContent` kullanacağını fark edeceksiniz.

İşte `buildJourneyContent` metodunda kullanabileceğiniz özelliklerin listesi.

| Özellik | Tür | Açıklama |
| --- | --- | --- |
| `content` | `Widget` | Sayfanın ana içeriği. |
| `nextButton` | `Widget?` | İleri düğmesi widget'ı. |
| `backButton` | `Widget?` | Geri düğmesi widget'ı. |
| `contentPadding` | `EdgeInsetsGeometry` | İçerik için dolgu. |
| `header` | `Widget?` | Üst bilgi widget'ı. |
| `footer` | `Widget?` | Alt bilgi widget'ı. |
| `crossAxisAlignment` | `CrossAxisAlignment` | İçeriğin çapraz eksen hizalaması. |


<div id="journey-state-helper-methods"></div>

### JourneyState Yardımcı Metotları

`JourneyState` sınıfı, yolculuğunuzun davranışını özelleştirmek için kullanabileceğiniz bazı yardımcı metotlara sahiptir.

| Metot | Açıklama |
| --- | --- |
| [`onNextPressed()`](#on-next-pressed) | İleri düğmesine basıldığında çağrılır. |
| [`onBackPressed()`](#on-back-pressed) | Geri düğmesine basıldığında çağrılır. |
| [`onComplete()`](#on-complete) | Yolculuk tamamlandığında (son adımda) çağrılır. |
| [`onBeforeNext()`](#on-before-next) | Sonraki adıma geçmeden önce çağrılır. |
| [`onAfterNext()`](#on-after-next) | Sonraki adıma geçtikten sonra çağrılır. |
| [`onCannotContinue()`](#on-cannot-continue) | Yolculuk devam edemediğinde (canContinue false döndürdüğünde) çağrılır. |
| [`canContinue()`](#can-continue) | Kullanıcı sonraki adıma geçmeye çalıştığında çağrılır. |
| [`isFirstStep`](#is-first-step) | Yolculuktaki ilk adım ise true döndürür. |
| [`isLastStep`](#is-last-step) | Yolculuktaki son adım ise true döndürür. |
| [`goToStep(int index)`](#go-to-step) | Belirtilen adım indeksine git. |
| [`goToNextStep()`](#go-to-next-step) | Sonraki adıma git. |
| [`goToPreviousStep()`](#go-to-previous-step) | Önceki adıma git. |
| [`goToFirstStep()`](#go-to-first-step) | İlk adıma git. |
| [`goToLastStep()`](#go-to-last-step) | Son adıma git. |


<div id="on-next-pressed"></div>

#### onNextPressed

`onNextPressed` metodu, ileri düğmesine basıldığında çağrılır.

Örneğin, bu metodu yolculuktaki sonraki adımı tetiklemek için kullanabilirsiniz.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        nextButton: Button.primary(
            text: isLastStep ? "Get Started" : "Continue",
            onPressed: onNextPressed, // this will attempt to navigate to the next step
        ),
    );
}
```

<div id="on-back-pressed"></div>

#### onBackPressed

`onBackPressed` metodu, geri düğmesine basıldığında çağrılır.

Örneğin, bu metodu yolculuktaki önceki adımı tetiklemek için kullanabilirsiniz.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        backButton: isFirstStep ? null : Button.textOnly(
            text: "Back",
            textColor: Colors.black87,
            onPressed: onBackPressed, // this will attempt to navigate to the previous step
        ),
    );
}
```

<div id="on-complete"></div>

#### onComplete

`onComplete` metodu, yolculuk tamamlandığında (son adımda) çağrılır.

Örneğin, bu widget yolculuktaki son adım ise bu metot çağrılacaktır.

``` dart
Future<void> onComplete() async {
    print("Journey completed");
}
```

<div id="on-before-next"></div>

#### onBeforeNext

`onBeforeNext` metodu, sonraki adıma geçmeden önce çağrılır.

Örneğin, sonraki adıma geçmeden önce veri kaydetmek istiyorsanız bunu burada yapabilirsiniz.

``` dart
Future<void> onBeforeNext() async {
    // E.g. save data here before navigating
}
```

<div id="is-first-step"></div>

#### isFirstStep

`isFirstStep` metodu, yolculuktaki ilk adım ise true döndürür.

Örneğin, bu ilk adımsa geri düğmesini devre dışı bırakmak için bu metodu kullanabilirsiniz.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        backButton: isFirstStep ? null : Button.textOnly( // Example of disabling the back button
            text: "Back",
            textColor: Colors.black87,
            onPressed: onBackPressed,
        ),
    );
}
```

<div id="is-last-step"></div>

#### isLastStep

`isLastStep` metodu, yolculuktaki son adım ise true döndürür.

Örneğin, bu son adımsa ileri düğmesini devre dışı bırakmak için bu metodu kullanabilirsiniz.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        nextButton: Button.primary(
            text: isLastStep ? "Get Started" : "Continue", // Example updating the next button text
            onPressed: onNextPressed,
        ),
    );
}
```

<div id="go-to-step"></div>

#### goToStep

`goToStep` metodu, yolculukta belirli bir adıma gitmek için kullanılır.

Örneğin, yolculukta belirli bir adıma gitmek için bu metodu kullanabilirsiniz.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        nextButton: Button.primary(
            text: "Add photos"
            onPressed: () {
                goToStep(2); // this will navigate to the step with index 2
                // Note: this will not trigger the onNextPressed method
            },
        ),
    );
}
```

<div id="go-to-next-step"></div>

#### goToNextStep

`goToNextStep` metodu, yolculuktaki sonraki adıma gitmek için kullanılır.

Örneğin, yolculuktaki sonraki adıma gitmek için bu metodu kullanabilirsiniz.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        nextButton: Button.primary(
            text: "Continue",
            onPressed: () {
                goToNextStep(); // this will navigate to the next step
                // Note: this will not trigger the onNextPressed method
            },
        ),
    );
}
```

<div id="go-to-previous-step"></div>

#### goToPreviousStep

`goToPreviousStep` metodu, yolculuktaki önceki adıma gitmek için kullanılır.

Örneğin, yolculuktaki önceki adıma gitmek için bu metodu kullanabilirsiniz.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        backButton: isFirstStep ? null : Button.textOnly(
            text: "Back",
            textColor: Colors.black87,
            onPressed: () {
                goToPreviousStep(); // this will navigate to the previous step
            },
        ),
    );
}
```

<div id="on-after-next"></div>

#### onAfterNext

`onAfterNext` metodu, sonraki adıma geçtikten sonra çağrılır.


Örneğin, sonraki adıma geçtikten sonra bir eylem gerçekleştirmek istiyorsanız bunu burada yapabilirsiniz.

``` dart
Future<void> onAfterNext() async {
    // print('Navigated to the next step');
}
```

<div id="on-cannot-continue"></div>

#### onCannotContinue

`onCannotContinue` metodu, yolculuk devam edemediğinde (canContinue false döndürdüğünde) çağrılır.

Örneğin, kullanıcı gerekli alanları doldurmadan sonraki adıma geçmeye çalıştığında bir hata mesajı göstermek istiyorsanız bunu burada yapabilirsiniz.

``` dart
Future<void> onCannotContinue() async {
    showToastSorry(description: "You cannot continue");
}
```

<div id="can-continue"></div>

#### canContinue

`canContinue` metodu, kullanıcı sonraki adıma geçmeye çalıştığında çağrılır.

Örneğin, sonraki adıma geçmeden önce doğrulama yapmak istiyorsanız bunu burada yapabilirsiniz.

``` dart
Future<bool> canContinue() async {
    // Perform your validation logic here
    // Return true if the journey can continue, false otherwise
    return true;
}
```

<div id="go-to-first-step"></div>

#### goToFirstStep

`goToFirstStep` metodu, yolculuktaki ilk adıma gitmek için kullanılır.


Örneğin, yolculuktaki ilk adıma gitmek için bu metodu kullanabilirsiniz.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        nextButton: Button.primary(
            text: "Continue",
            onPressed: () {
                goToFirstStep(); // this will navigate to the first step
            },
        ),
    );
}
```

<div id="go-to-last-step"></div>

#### goToLastStep

`goToLastStep` metodu, yolculuktaki son adıma gitmek için kullanılır.

Örneğin, yolculuktaki son adıma gitmek için bu metodu kullanabilirsiniz.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        nextButton: Button.primary(
            text: "Continue",
            onPressed: () {
                goToLastStep(); // this will navigate to the last step
            },
        ),
    );
}
```

<div id="navigating-within-a-tab"></div>

## Sekme İçindeki Widget'lara Gezinme

`pushTo` yardımcısını kullanarak bir sekme içindeki widget'lara gezinebilirsiniz.

Sekmenizin içinde, başka bir widget'a gitmek için `pushTo` yardımcısını kullanabilirsiniz.

``` dart
_HomeTabState extends State<HomeTab> {
    ...
    void _navigateToSettings() {
        pushTo(SettingsPage());
    }
    ...
}
```

Gezindiğiniz widget'a veri de geçirebilirsiniz.

``` dart
_HomeTabState extends State<HomeTab> {
    ...
    void _navigateToSettings() {
        pushTo(SettingsPage(), data: {"name": "Anthony"});
    }
    ...
}
```

<div id="tabs"></div>

## Sekmeler

Sekmeler, Navigation Hub'ın ana yapı taşlarıdır.

`NavigationTab` sınıfını kullanarak Navigation Hub'a sekmeler ekleyebilirsiniz.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() async {
        return {
            0: NavigationTab(
                title: "Home",
                page: HomeTab(),
                icon: Icon(Icons.home),
                activeIcon: Icon(Icons.home),
            ),
            1: NavigationTab(
                title: "Settings",
                page: SettingsTab(),
                icon: Icon(Icons.settings),
                activeIcon: Icon(Icons.settings),
            ),
        };
    });
```

Yukarıdaki örnekte, Navigation Hub'a Home ve Settings olmak üzere iki sekme ekledik.

`NavigationTab`, `NavigationTab.badge` ve `NavigationTab.alert` gibi farklı sekme türlerini kullanabilirsiniz.

- `NavigationTab.badge` sınıfı, sekmelere rozet eklemek için kullanılır.
- `NavigationTab.alert` sınıfı, sekmelere uyarı eklemek için kullanılır.
- `NavigationTab` sınıfı, normal bir sekme eklemek için kullanılır.

<div id="adding-badges-to-tabs"></div>

## Sekmelere Rozet Ekleme

Sekmelerinize rozet eklemeyi kolaylaştırdık.

Rozetler, kullanıcılara bir sekmede yeni bir şey olduğunu göstermenin harika bir yoludur.

Örneğin, bir sohbet uygulamanız varsa, sohbet sekmesinde okunmamış mesaj sayısını gösterebilirsiniz.

Bir sekmeye rozet eklemek için `NavigationTab.badge` sınıfını kullanabilirsiniz.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() async {
        return {
            0: NavigationTab.badge(
                title: "Chats",
                page: ChatTab(),
                icon: Icon(Icons.message),
                activeIcon: Icon(Icons.message),
                initialCount: 10,
            ),
            1: NavigationTab(
                title: "Settings",
                page: SettingsTab(),
                icon: Icon(Icons.settings),
                activeIcon: Icon(Icons.settings),
            ),
        };
    });
```

Yukarıdaki örnekte, Chat sekmesine başlangıç sayısı 10 olan bir rozet ekledik.

Rozet sayısını programatik olarak da güncelleyebilirsiniz.

``` dart
/// Increment the badge count
BaseNavigationHub.stateActions.incrementBadgeCount(tab: 0);

/// Update the badge count
BaseNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 5);

/// Clear the badge count
BaseNavigationHub.stateActions.clearBadgeCount(tab: 0);
```

Varsayılan olarak, rozet sayısı hatırlanacaktır. Her oturumda rozet sayısını **temizlemek** istiyorsanız, `rememberCount`'u `false` olarak ayarlayabilirsiniz.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() async {
        return {
            0: NavigationTab.badge(
                title: "Chats",
                page: ChatTab(),
                icon: Icon(Icons.message),
                activeIcon: Icon(Icons.message),
                initialCount: 10,
                rememberCount: false,
            ),
            1: NavigationTab(
                title: "Settings",
                page: SettingsTab(),
                icon: Icon(Icons.settings),
                activeIcon: Icon(Icons.settings),
            ),
        };
    });
```

<div id="adding-alerts-to-tabs"></div>

## Sekmelere Uyarı Ekleme

Sekmelerinize uyarı ekleyebilirsiniz.

Bazen rozet sayısı göstermek istemezsiniz, ancak kullanıcıya bir uyarı göstermek istersiniz.

Bir sekmeye uyarı eklemek için `NavigationTab.alert` sınıfını kullanabilirsiniz.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() async {
        return {
            0: NavigationTab.alert(
                title: "Chats",
                page: ChatTab(),
                icon: Icon(Icons.message),
                activeIcon: Icon(Icons.message),
                alertColor: Colors.red,
                alertEnabled: true,
                rememberAlert: false,
            ),
            1: NavigationTab(
                title: "Settings",
                page: SettingsTab(),
                icon: Icon(Icons.settings),
                activeIcon: Icon(Icons.settings),
            ),
        };
    });
```

Bu, Chat sekmesine kırmızı renkte bir uyarı ekleyecektir.

Uyarıyı programatik olarak da güncelleyebilirsiniz.

``` dart
/// Enable the alert
BaseNavigationHub.stateActions.alertEnableTab(tab: 0);

/// Disable the alert
BaseNavigationHub.stateActions.alertDisableTab(tab: 0);
```

<div id="maintaining-state"></div>

## Durumu Koruma

Varsayılan olarak, Navigation Hub'ın durumu korunur.

Bu, bir sekmeye gittiğinizde sekmenin durumunun saklandığı anlamına gelir.

Her gittiğinizde sekmenin durumunu temizlemek istiyorsanız, `maintainState`'i `false` olarak ayarlayabilirsiniz.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    bool get maintainState => false;
    ...
}
```

<div id="state-actions"></div>

## Durum Eylemleri

Durum eylemleri, uygulamanızın herhangi bir yerinden Navigation Hub ile etkileşim kurmanın bir yoludur.

İşte kullanabileceğiniz bazı durum eylemleri:

``` dart
  /// Reset the tab
  /// E.g. MyNavigationHub.stateActions.resetTabState(tab: 0);
  resetTabState({required tab});

  /// Update the badge count
  /// E.g. MyNavigationHub.updateBadgeCount(tab: 0, count: 2);
  updateBadgeCount({required int tab, required int count});

  /// Increment the badge count
  /// E.g. MyNavigationHub.incrementBadgeCount(tab: 0);
  incrementBadgeCount({required int tab});

  /// Clear the badge count
  /// E.g. MyNavigationHub.clearBadgeCount(tab: 0);
  clearBadgeCount({required int tab});
```

Bir durum eylemi kullanmak için aşağıdakileri yapabilirsiniz:

``` dart
MyNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 2);
// or
MyNavigationHub.stateActions.resetTabState(tab: 0);
```

<div id="loading-style"></div>

## Yükleme Stili

Kutudan çıktığı haliyle, Navigation Hub sekme yüklenirken **varsayılan** yükleme Widget'ınızı (resources/widgets/loader_widget.dart) gösterecektir.

Yükleme stilini güncellemek için `loadingStyle`'ı özelleştirebilirsiniz.

İşte kullanabileceğiniz farklı yükleme stillerinin tablosu:
// normal, skeletonizer, none

| Stil | Açıklama |
| --- | --- |
| normal | Varsayılan yükleme stili |
| skeletonizer | İskelet yükleme stili |
| none | Yükleme stili yok |

Yükleme stilini şu şekilde değiştirebilirsiniz:

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal();
// or
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer();
```

Stillerden birindeki yükleme Widget'ını güncellemek istiyorsanız, `LoadingStyle`'a bir `child` geçirebilirsiniz.

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal(
    child: Center(
        child: Text("Loading..."),
    ),
);
```

Şimdi, sekme yüklenirken "Loading..." metni görüntülenecektir.

Aşağıdaki örnek:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
     _BaseNavigationHubState() : super(() async {

      await sleep(3); // simulate loading for 3 seconds

      return {
        0: NavigationTab(
          title: "Home",
          page: HomeTab(),
          icon: Icon(Icons.home),
          activeIcon: Icon(Icons.home),
        ),
        1: NavigationTab(
          title: "Settings",
          page: SettingsTab(),
          icon: Icon(Icons.settings),
          activeIcon: Icon(Icons.settings),
        ),
      };
    });

    @override
    LoadingStyle get loadingStyle => LoadingStyle.normal(
        child: Center(
            child: Text("Loading..."),
        ),
    );
    ...
}
```

<div id="creating-a-navigation-hub"></div>

## Navigation Hub Oluşturma

Bir Navigation Hub oluşturmak için [Metro](/docs/{{$version}}/metro) kullanabilirsiniz, aşağıdaki komutu kullanın.

``` bash
metro make:navigation_hub base
```

Bu, `resources/pages/` dizininizde bir base_navigation_hub.dart dosyası oluşturacak ve Navigation Hub'ı `routes/router.dart` dosyanıza ekleyecektir.
