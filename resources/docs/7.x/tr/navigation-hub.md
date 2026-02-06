# Navigation Hub

---

<a name="section-1"></a>
- [Giris](#introduction "Giris")
- [Temel Kullanim](#basic-usage "Temel Kullanim")
  - [Navigation Hub Olusturma](#creating-a-navigation-hub "Navigation Hub Olusturma")
  - [Navigasyon Sekmeleri Olusturma](#creating-navigation-tabs "Navigasyon Sekmeleri Olusturma")
  - [Alt Navigasyon](#bottom-navigation "Alt Navigasyon")
    - [Ozel Nav Bar Builder](#custom-nav-bar-builder "Ozel Nav Bar Builder")
  - [Ust Navigasyon](#top-navigation "Ust Navigasyon")
  - [Journey Navigasyonu](#journey-navigation "Journey Navigasyonu")
    - [Ilerleme Stilleri](#journey-progress-styles "Ilerleme Stilleri")
    - [JourneyState](#journey-state "JourneyState")
    - [JourneyState Yardimci Metotlari](#journey-state-helper-methods "JourneyState Yardimci Metotlari")
    - [onJourneyComplete](#on-journey-complete "onJourneyComplete")
    - [buildJourneyPage](#build-journey-page "buildJourneyPage")
- [Sekme Icinde Gezinme](#navigating-within-a-tab "Sekme Icinde Gezinme")
- [Sekmeler](#tabs "Sekmeler")
  - [Sekmelere Rozet Ekleme](#adding-badges-to-tabs "Sekmelere Rozet Ekleme")
  - [Sekmelere Uyari Ekleme](#adding-alerts-to-tabs "Sekmelere Uyari Ekleme")
- [Baslangic Indeksi](#initial-index "Baslangic Indeksi")
- [Durumu Koruma](#maintaining-state "Durumu Koruma")
- [onTap](#on-tap "onTap")
- [Durum Eylemleri](#state-actions "Durum Eylemleri")
- [Yukleme Stili](#loading-style "Yukleme Stili")

<div id="introduction"></div>

## Giris

Navigation Hub'lar, tum widget'lariniz icin navigasyonu **yonetebileceginiz** merkezi bir yerdir.
Kutudan ciktigi haliyle saniyeler icinde alt, ust ve journey navigasyon duzenleri olusturabilirsiniz.

Bir uygulamaniz oldugunu ve alt navigasyon cubugu ekleyerek kullanicilarin uygulamanizdaki farkli sekmeler arasinda gezinmesini istediginizi **hayal edelim**.

Bunu olusturmak icin bir Navigation Hub kullanabilirsiniz.

Uygulamanizda Navigation Hub'i nasil kullanabileceginize bakalim.

<div id="basic-usage"></div>

## Temel Kullanim

Asagidaki komutu kullanarak bir Navigation Hub olusturabilirsiniz.

``` bash
metro make:navigation_hub base
```

Komut sizi interaktif bir kurulum surecinden gecirecektir:

1. **Bir duzen turu secin** - `navigation_tabs` (alt navigasyon) veya `journey_states` (sirali akis) arasinda secim yapin.
2. **Sekme/durum adlarini girin** - Sekmeleriniz veya journey durumlari icin virgullerle ayrilmis adlar girin.

Bu, `resources/pages/navigation_hubs/base/` dizininiz altinda dosyalar olusturacaktir:
- `base_navigation_hub.dart` - Ana hub widget'i
- `tabs/` veya `states/` - Her sekme veya journey durumu icin alt widget'lari icerir

Olusturulan bir Navigation Hub su sekilde gorunur:

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/pages/navigation_hubs/base/tabs/home_tab_widget.dart';
import '/resources/pages/navigation_hubs/base/tabs/settings_tab_widget.dart';

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

  /// Layout builder
  @override
  NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();

  /// Should the state be maintained
  @override
  bool get maintainState => true;

  /// The initial index
  @override
  int get initialIndex => 0;

  /// Navigation pages
  _BaseNavigationHubState() : super(() => {
      0: NavigationTab.tab(title: "Home", page: HomeTab()),
      1: NavigationTab.tab(title: "Settings", page: SettingsTab()),
  });

  /// Handle the tap event
  @override
  onTap(int index) {
    super.onTap(index);
  }
}
```

Navigation Hub'in **iki** sekmesi oldugunu gorebilirsiniz: Home ve Settings.

`layout` metodu hub'in duzen turunu dondurur. Duzeninizi yapilandirirken tema verilerine ve medya sorgularina erisebilmeniz icin bir `BuildContext` alir.

Navigation Hub'a `NavigationTab`'lar ekleyerek daha fazla sekme olusturabilirsiniz.

Oncelikle, Metro kullanarak yeni bir widget olusturmaniz gerekir.

``` bash
metro make:stateful_widget news_tab
```

Ayni anda birden fazla widget de olusturabilirsiniz.

``` bash
metro make:stateful_widget news_tab,notifications_tab
```

Ardindan, yeni widget'i Navigation Hub'a ekleyebilirsiniz.

``` dart
_BaseNavigationHubState() : super(() => {
    0: NavigationTab.tab(title: "Home", page: HomeTab()),
    1: NavigationTab.tab(title: "Settings", page: SettingsTab()),
    2: NavigationTab.tab(title: "News", page: NewsTab()),
});
```

Navigation Hub'i kullanmak icin, router'iniza baslangic rotasi olarak ekleyin:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

appRouter() => nyRoutes((router) {
    ...
    router.add(BaseNavigationHub.path).initialRoute();
});

// or navigate to the Navigation Hub from anywhere in your app

routeTo(BaseNavigationHub.path);
```

Navigation Hub ile yapabileceginiz cok **daha fazla** sey var, bazi ozelliklerine bakalim.

<div id="bottom-navigation"></div>

### Alt Navigasyon

`layout` metodundan `NavigationHubLayout.bottomNav` dondurerek duzeni alt navigasyon cubuguna ayarlayabilirsiniz.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
```

Alt navigasyon cubugunu asagidaki gibi ozellikler ayarlayarak ozellestebilirsiniz:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav(
        backgroundColor: Colors.white,
        selectedItemColor: Colors.blue,
        unselectedItemColor: Colors.grey,
        elevation: 8.0,
        iconSize: 24.0,
        selectedFontSize: 14.0,
        unselectedFontSize: 12.0,
        showSelectedLabels: true,
        showUnselectedLabels: true,
        type: BottomNavigationBarType.fixed,
    );
```

`style` parametresini kullanarak alt navigasyon cubuguna hazir bir stil uygulayabilirsiniz.

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav(
    style: BottomNavStyle.material(), // Default Flutter material style
);
```

<div id="custom-nav-bar-builder"></div>

### Ozel Nav Bar Builder

Navigasyon cubugunuz uzerinde tam kontrol icin `navBarBuilder` parametresini kullanabilirsiniz.

Bu, navigasyon verilerini almaya devam ederken herhangi bir ozel widget olusturmanizi saglar.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav(
        navBarBuilder: (context, data) {
            return MyCustomNavBar(
                items: data.items,
                currentIndex: data.currentIndex,
                onTap: data.onTap,
            );
        },
    );
```

`NavBarData` nesnesi sunlari icerir:

| Ozellik | Tur | Aciklama |
| --- | --- | --- |
| `items` | `List<BottomNavigationBarItem>` | Navigasyon cubugu ogeleri |
| `currentIndex` | `int` | Su anda secili olan indeks |
| `onTap` | `ValueChanged<int>` | Bir sekmeye dokunuldugunda cagirilan callback |

Iste tamamen ozel bir glass navigasyon cubugu ornegi:

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

> **Not:** `navBarBuilder` kullanildiginda `style` parametresi yok sayilir.

<div id="top-navigation"></div>

### Ust Navigasyon

`layout` metodundan `NavigationHubLayout.topNav` dondurerek duzeni ust navigasyon cubuguna degistirebilirsiniz.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.topNav();
```

Ust navigasyon cubugunu asagidaki gibi ozellikler ayarlayarak ozellestebilirsiniz:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.topNav(
        backgroundColor: Colors.white,
        labelColor: Colors.blue,
        unselectedLabelColor: Colors.grey,
        indicatorColor: Colors.blue,
        indicatorWeight: 3.0,
        isScrollable: false,
        hideAppBarTitle: true,
    );
```

<div id="journey-navigation"></div>

### Journey Navigasyonu

`layout` metodundan `NavigationHubLayout.journey` dondurerek duzeni journey navigasyonuna degistirebilirsiniz.

Bu, baslangic akislari veya cok adimli formlar icin harikadir.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle(
          indicator: JourneyProgressIndicator.segments(),
        ),
    );
```

Journey duzeni icin bir `backgroundGradient` de ayarlayabilirsiniz:

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
    backgroundGradient: LinearGradient(
        colors: [Colors.blue, Colors.purple],
        begin: Alignment.topLeft,
        end: Alignment.bottomRight,
    ),
    progressStyle: JourneyProgressStyle(
      indicator: JourneyProgressIndicator.linear(),
    ),
);
```

> **Not:** `backgroundGradient` ayarlandiginda, `backgroundColor`'dan onceliklidir.

Journey navigasyon duzenini kullanmak istiyorsaniz, **widget'lariniz** `JourneyState` kullanmalidir cunku yolculugu yonetmenize yardimci olacak bircok yardimci metot icerir.

`make:navigation_hub` komutunu `journey_states` duzeni ile kullanarak tum yolculugu olusturabilirsiniz:

``` bash
metro make:navigation_hub onboarding
# Select: journey_states
# Enter: welcome, personal_info, add_photos
```

Bu, hub'i ve tum journey state widget'larini `resources/pages/navigation_hubs/onboarding/states/` altinda olusturacaktir.

Veya tek tek journey widget'lari olusturmak icin su komutu kullanabilirsiniz:

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

Ardindan yeni widget'lari Navigation Hub'a ekleyebilirsiniz.

``` dart
_MyNavigationHubState() : super(() => {
    0: NavigationTab.journey(
        page: Welcome(),
    ),
    1: NavigationTab.journey(
        page: PhoneNumberStep(),
    ),
    2: NavigationTab.journey(
        page: AddPhotosStep(),
    ),
});
```

<div id="journey-progress-styles"></div>

### Ilerleme Stilleri

Ilerleme gostergesi stilini `JourneyProgressStyle` sinifini kullanarak ozellestirebilirsiniz.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle(
            indicator: JourneyProgressIndicator.linear(
                activeColor: Colors.blue,
                inactiveColor: Colors.grey,
                thickness: 4.0,
            ),
        ),
    );
```

Asagidaki ilerleme gostergelerini kullanabilirsiniz:

- `JourneyProgressIndicator.none()`: Hicbir sey render etmez - belirli bir sekmede gostergeyi gizlemek icin kullanislidir.
- `JourneyProgressIndicator.linear()`: Dogrusal ilerleme cubugu.
- `JourneyProgressIndicator.dots()`: Nokta tabanli ilerleme gostergesi.
- `JourneyProgressIndicator.numbered()`: Numarali adim ilerleme gostergesi.
- `JourneyProgressIndicator.segments()`: Bolumlu ilerleme cubugu stili.
- `JourneyProgressIndicator.circular()`: Dairesel ilerleme gostergesi.
- `JourneyProgressIndicator.timeline()`: Zaman cizelgesi stili ilerleme gostergesi.
- `JourneyProgressIndicator.custom()`: Bir builder fonksiyonu kullanan ozel ilerleme gostergesi.

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
    progressStyle: JourneyProgressStyle(
        indicator: JourneyProgressIndicator.custom(
            builder: (context, currentStep, totalSteps, percentage) {
                return LinearProgressIndicator(
                    value: percentage,
                    backgroundColor: Colors.grey[200],
                    color: Colors.blue,
                    minHeight: 4.0,
                );
            },
        ),
    ),
);
```

Ilerleme gostergesi konumunu ve dolgusunu `JourneyProgressStyle` icinde ozellestirebilirsiniz:

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
    progressStyle: JourneyProgressStyle(
        indicator: JourneyProgressIndicator.dots(),
        position: ProgressIndicatorPosition.bottom,
        padding: EdgeInsets.symmetric(horizontal: 16.0, vertical: 8.0),
    ),
);
```

Asagidaki ilerleme gostergesi konumlarini kullanabilirsiniz:

- `ProgressIndicatorPosition.top`: Ekranin ustunde ilerleme gostergesi.
- `ProgressIndicatorPosition.bottom`: Ekranin altinda ilerleme gostergesi.

#### Sekme Bazinda Ilerleme Stili Gecersiz Kilma

`NavigationTab.journey(progressStyle: ...)` kullanarak layout duzeyindeki `progressStyle`'i tek tek sekmeler icin gecersiz kilabilirsiniz. Kendi `progressStyle`'i olmayan sekmeler layout varsayilanini devralir. Layout varsayilani ve sekme bazinda stil olmayan sekmeler ilerleme gostergesi gostermez.

``` dart
_MyNavigationHubState() : super(() => {
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
});
```

<div id="journey-state"></div>

### JourneyState

`JourneyState` sinifi, baslangic akislari ve cok adimli yolculuklar olusturmayi kolaylastirmak icin journey'e ozel islevlerle `NyState`'i genisletir.

Yeni bir `JourneyState` olusturmak icin asagidaki komutu kullanabilirsiniz.

``` bash
metro make:journey_widget onboard_user_dob
```

Veya ayni anda birden fazla widget olusturmak istiyorsaniz, asagidaki komutu kullanabilirsiniz.

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

Olusturulan bir JourneyState widget'i su sekilde gorunur:

``` dart
import 'package:flutter/material.dart';
import '/resources/pages/navigation_hubs/onboarding/onboarding_navigation_hub.dart';
import '/resources/widgets/buttons/buttons.dart';
import 'package:nylo_framework/nylo_framework.dart';

class Welcome extends StatefulWidget {
  const Welcome({super.key});

  @override
  createState() => _WelcomeState();
}

class _WelcomeState extends JourneyState<Welcome> {
  _WelcomeState() : super(
      navigationHubState: OnboardingNavigationHub.path.stateName());

  @override
  get init => () {
    // Your initialization logic here
  };

  @override
  Widget view(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(16),
      child: Column(
        children: [
          Expanded(
            child: Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Text('Welcome', style: Theme.of(context).textTheme.headlineMedium),
                  const SizedBox(height: 20),
                  Text('This onboarding journey will help you get started.'),
                ],
              ),
            ),
          ),

          // Navigation buttons
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              if (!isFirstStep)
                Flexible(
                  child: Button.textOnly(
                    text: "Back",
                    textColor: Colors.black87,
                    onPressed: onBackPressed,
                  ),
                )
              else
                const SizedBox.shrink(),
              Flexible(
                child: Button.primary(
                  text: "Continue",
                  onPressed: nextStep,
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  /// Check if the journey can continue to the next step
  @override
  Future<bool> canContinue() async {
    return true;
  }

  /// Called before navigating to the next step
  @override
  Future<void> onBeforeNext() async {
    // E.g. save data to session
  }

  /// Called when the journey is complete (at the last step)
  @override
  Future<void> onComplete() async {}
}
```

**JourneyState** sinifinin ileri gitmek icin `nextStep`, geri gitmek icin `onBackPressed` kullandigini fark edeceksiniz.

`nextStep` metodu tam dogrulama yasam dongusu boyunca calisir: `canContinue()` -> `onBeforeNext()` -> navigasyon (veya son adimda ise `onComplete()`) -> `onAfterNext()`.

Ayrica istege bagli navigasyon dugmeleriyle yapilandirilmis bir duzen olusturmak icin `buildJourneyContent` kullanabilirsiniz:

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
      content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text('Welcome', style: Theme.of(context).textTheme.headlineMedium),
          const SizedBox(height: 20),
          Text('This onboarding journey will help you get started.'),
        ],
      ),
      nextButton: Button.primary(
        text: isLastStep ? "Get Started" : "Continue",
        onPressed: nextStep,
      ),
      backButton: isFirstStep ? null : Button.textOnly(
        text: "Back",
        textColor: Colors.black87,
        onPressed: onBackPressed,
      ),
    );
}
```

`buildJourneyContent` metodunda kullanabileceginiz ozellikler sunlardir.

| Ozellik | Tur | Aciklama |
| --- | --- | --- |
| `content` | `Widget` | Sayfanin ana icerigi. |
| `nextButton` | `Widget?` | Ileri dugmesi widget'i. |
| `backButton` | `Widget?` | Geri dugmesi widget'i. |
| `contentPadding` | `EdgeInsetsGeometry` | Icerik icin dolgu. |
| `header` | `Widget?` | Ust bilgi widget'i. |
| `footer` | `Widget?` | Alt bilgi widget'i. |
| `crossAxisAlignment` | `CrossAxisAlignment` | Icerigin capraz eksen hizalamasi. |

<div id="journey-state-helper-methods"></div>

### JourneyState Yardimci Metotlari

`JourneyState` sinifi, yolculugunuzun davranisini ozellestirmek icin kullanabileceginiz yardimci metotlara ve ozelliklere sahiptir.

| Metot / Ozellik | Aciklama |
| --- | --- |
| [`nextStep()`](#next-step) | Dogrulama ile sonraki adima gider. `Future<bool>` dondurur. |
| [`previousStep()`](#previous-step) | Onceki adima gider. `Future<bool>` dondurur. |
| [`onBackPressed()`](#on-back-pressed) | Onceki adima gitmek icin basit yardimci. |
| [`onComplete()`](#on-complete) | Yolculuk tamamlandiginda (son adimda) cagrilir. |
| [`onBeforeNext()`](#on-before-next) | Sonraki adima gecmeden once cagrilir. |
| [`onAfterNext()`](#on-after-next) | Sonraki adima gectikten sonra cagrilir. |
| [`canContinue()`](#can-continue) | Sonraki adima gecmeden once dogrulama kontrolu. |
| [`isFirstStep`](#is-first-step) | Yolculuktaki ilk adim ise true dondurur. |
| [`isLastStep`](#is-last-step) | Yolculuktaki son adim ise true dondurur. |
| [`currentStep`](#current-step) | Mevcut adim indeksini dondurur (0 tabanli). |
| [`totalSteps`](#total-steps) | Toplam adim sayisini dondurur. |
| [`completionPercentage`](#completion-percentage) | Tamamlanma yuzdesi dondurur (0.0 ile 1.0 arasi). |
| [`goToStep(int index)`](#go-to-step) | Indeksle belirli bir adima atlar. |
| [`goToNextStep()`](#go-to-next-step) | Sonraki adima atlar (dogrulama yok). |
| [`goToPreviousStep()`](#go-to-previous-step) | Onceki adima atlar (dogrulama yok). |
| [`goToFirstStep()`](#go-to-first-step) | Ilk adima atlar. |
| [`goToLastStep()`](#go-to-last-step) | Son adima atlar. |
| [`exitJourney()`](#exit-journey) | Root navigator'i kapatarak yolculuktan cikar. |
| [`resetCurrentStep()`](#reset-current-step) | Mevcut adimin durumunu sifirlar. |
| [`onJourneyComplete`](#on-journey-complete) | Yolculuk tamamlandiginda cagirilan callback (son adimda override edin). |
| [`buildJourneyPage()`](#build-journey-page) | Scaffold ile tam ekran journey sayfasi olusturur. |


<div id="next-step"></div>

#### nextStep

`nextStep` metodu tam dogrulama ile sonraki adima gider. Yasam dongusunu takip eder: `canContinue()` -> `onBeforeNext()` -> navigasyon veya `onComplete()` -> `onAfterNext()`.

Dogrulamayi atlamak icin `force: true` gecebilirsiniz.

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
            onPressed: nextStep, // runs validation then navigates
        ),
    );
}
```

Dogrulamayi atlamak icin:

``` dart
onPressed: () => nextStep(force: true),
```

<div id="previous-step"></div>

#### previousStep

`previousStep` metodu onceki adima gider. Basarili olursa `true`, zaten ilk adimdaysa `false` dondurur.

``` dart
onPressed: () async {
    bool success = await previousStep();
    if (!success) {
      // Already at first step
    }
},
```

<div id="on-back-pressed"></div>

#### onBackPressed

`onBackPressed` metodu dahili olarak `previousStep()` cagiran basit bir yardimcidir.

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
            onPressed: onBackPressed,
        ),
    );
}
```

<div id="on-complete"></div>

#### onComplete

`onComplete` metodu, son adimda `nextStep()` tetiklendiginde (dogrulama gecildikten sonra) cagrilir.

``` dart
@override
Future<void> onComplete() async {
    print("Journey completed");
}
```

<div id="on-before-next"></div>

#### onBeforeNext

`onBeforeNext` metodu, sonraki adima gecmeden once cagrilir.

Ornegin, sonraki adima gecmeden once veri kaydetmek istiyorsaniz burada yapabilirsiniz.

``` dart
@override
Future<void> onBeforeNext() async {
    // E.g. save data to session
    // session('onboarding', {
    //   'name': 'Anthony Gordon',
    //   'occupation': 'Software Engineer',
    // });
}
```

<div id="on-after-next"></div>

#### onAfterNext

`onAfterNext` metodu, sonraki adima gectikten sonra cagrilir.

``` dart
@override
Future<void> onAfterNext() async {
    // print('Navigated to the next step');
}
```

<div id="can-continue"></div>

#### canContinue

`canContinue` metodu, `nextStep()` tetiklendiginde cagrilir. Navigasyonu engellemek icin `false` dondurun.

``` dart
@override
Future<bool> canContinue() async {
    // Perform your validation logic here
    // Return true if the journey can continue, false otherwise
    if (nameController.text.isEmpty) {
        showToastSorry(description: "Please enter your name");
        return false;
    }
    return true;
}
```

<div id="is-first-step"></div>

#### isFirstStep

`isFirstStep` ozelligi, yolculuktaki ilk adim ise true dondurur.

``` dart
backButton: isFirstStep ? null : Button.textOnly(
    text: "Back",
    textColor: Colors.black87,
    onPressed: onBackPressed,
),
```

<div id="is-last-step"></div>

#### isLastStep

`isLastStep` ozelligi, yolculuktaki son adim ise true dondurur.

``` dart
nextButton: Button.primary(
    text: isLastStep ? "Get Started" : "Continue",
    onPressed: nextStep,
),
```

<div id="current-step"></div>

#### currentStep

`currentStep` ozelligi mevcut adim indeksini dondurur (0 tabanli).

``` dart
Text("Step ${currentStep + 1} of $totalSteps"),
```

<div id="total-steps"></div>

#### totalSteps

`totalSteps` ozelligi yolculuktaki toplam adim sayisini dondurur.

<div id="completion-percentage"></div>

#### completionPercentage

`completionPercentage` ozelligi tamamlanma yuzdesi olarak 0.0 ile 1.0 arasinda bir deger dondurur.

``` dart
LinearProgressIndicator(value: completionPercentage),
```

<div id="go-to-step"></div>

#### goToStep

`goToStep` metodu indeksle dogrudan belirli bir adima atlar. Bu islem dogrulama **tetiklemez**.

``` dart
nextButton: Button.primary(
    text: "Skip to photos",
    onPressed: () {
        goToStep(2); // jump to step index 2
    },
),
```

<div id="go-to-next-step"></div>

#### goToNextStep

`goToNextStep` metodu dogrulama olmadan sonraki adima atlar. Zaten son adimdaysa hicbir sey yapmaz.

``` dart
onPressed: () {
    goToNextStep(); // skip validation and go to next step
},
```

<div id="go-to-previous-step"></div>

#### goToPreviousStep

`goToPreviousStep` metodu dogrulama olmadan onceki adima atlar. Zaten ilk adimdaysa hicbir sey yapmaz.

``` dart
onPressed: () {
    goToPreviousStep();
},
```

<div id="go-to-first-step"></div>

#### goToFirstStep

`goToFirstStep` metodu ilk adima atlar.

``` dart
onPressed: () {
    goToFirstStep();
},
```

<div id="go-to-last-step"></div>

#### goToLastStep

`goToLastStep` metodu son adima atlar.

``` dart
onPressed: () {
    goToLastStep();
},
```

<div id="exit-journey"></div>

#### exitJourney

`exitJourney` metodu root navigator'i kapatarak yolculuktan cikar.

``` dart
onPressed: () {
    exitJourney(); // pop the root navigator
},
```

<div id="reset-current-step"></div>

#### resetCurrentStep

`resetCurrentStep` metodu mevcut adimin durumunu sifirlar.

``` dart
onPressed: () {
    resetCurrentStep();
},
```

<div id="on-journey-complete"></div>

### onJourneyComplete

`onJourneyComplete` getter'i, kullanicinin akisi tamamladiginda ne olacagini tanimlamak icin yolculugunuzun **son adiminda** override edilebilir.

``` dart
class _CompleteStepState extends JourneyState<CompleteStep> {
  _CompleteStepState() : super(
      navigationHubState: OnboardingNavigationHub.path.stateName());

  /// Callback when journey completes
  @override
  void Function()? get onJourneyComplete => () {
    // Navigate to your home page or next destination
    routeTo(HomePage.path);
  };

  @override
  Widget view(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(16),
      child: Column(
        children: [
          ...
          Button.primary(
            text: "Get Started",
            onPressed: onJourneyComplete, // triggers the completion callback
          ),
        ],
      ),
    );
  }
}
```

<div id="build-journey-page"></div>

### buildJourneyPage

`buildJourneyPage` metodu `Scaffold` ve `SafeArea` ile sarilmis tam ekran bir journey sayfasi olusturur.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyPage(
      content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text('Welcome', style: Theme.of(context).textTheme.headlineMedium),
        ],
      ),
      nextButton: Button.primary(
        text: "Continue",
        onPressed: nextStep,
      ),
      backgroundColor: Colors.white,
    );
}
```

| Ozellik | Tur | Aciklama |
| --- | --- | --- |
| `content` | `Widget` | Sayfanin ana icerigi. |
| `nextButton` | `Widget?` | Ileri dugmesi widget'i. |
| `backButton` | `Widget?` | Geri dugmesi widget'i. |
| `contentPadding` | `EdgeInsetsGeometry` | Icerik icin dolgu. |
| `header` | `Widget?` | Ust bilgi widget'i. |
| `footer` | `Widget?` | Alt bilgi widget'i. |
| `backgroundColor` | `Color?` | Scaffold'un arka plan rengi. |
| `appBar` | `Widget?` | Istege bagli bir AppBar widget'i. |
| `crossAxisAlignment` | `CrossAxisAlignment` | Icerigin capraz eksen hizalamasi. |

<div id="navigating-within-a-tab"></div>

## Sekme Icindeki Widget'lara Gezinme

`pushTo` yardimcisini kullanarak bir sekme icindeki widget'lara gezinebilirsiniz.

Sekmenizin icinde, baska bir widget'a gitmek icin `pushTo` yardimcisini kullanabilirsiniz.

``` dart
_HomeTabState extends State<HomeTab> {
    ...
    void _navigateToSettings() {
        pushTo(SettingsPage());
    }
    ...
}
```

Gezindiginiz widget'a veri de gecebilirsiniz.

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

Sekmeler, Navigation Hub'in ana yapi taslaridir.

`NavigationTab` sinifini ve adlandirilmis constructor'larini kullanarak Navigation Hub'a sekmeler ekleyebilirsiniz.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() => {
        0: NavigationTab.tab(
            title: "Home",
            page: HomeTab(),
            icon: Icon(Icons.home),
            activeIcon: Icon(Icons.home),
        ),
        1: NavigationTab.tab(
            title: "Settings",
            page: SettingsTab(),
            icon: Icon(Icons.settings),
            activeIcon: Icon(Icons.settings),
        ),
    });
```

Yukaridaki ornekte, Navigation Hub'a Home ve Settings olmak uzere iki sekme ekledik.

Farkli sekme turlerini kullanabilirsiniz:

- `NavigationTab.tab()` - Standart navigasyon sekmesi.
- `NavigationTab.badge()` - Rozet sayili sekme.
- `NavigationTab.alert()` - Uyari gostergeli sekme.
- `NavigationTab.journey()` - Journey navigasyon duzenleri icin sekme.

<div id="adding-badges-to-tabs"></div>

## Sekmelere Rozet Ekleme

Sekmelerinize rozet eklemeyi kolaylastirdik.

Rozetler, kullanicilara bir sekmede yeni bir sey oldugunu gostermenin harika bir yoludur.

Ornegin, bir sohbet uygulamaniz varsa, sohbet sekmesinde okunmamis mesaj sayisini gosterebilirsiniz.

Bir sekmeye rozet eklemek icin `NavigationTab.badge` constructor'ini kullanabilirsiniz.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() => {
        0: NavigationTab.badge(
            title: "Chats",
            page: ChatTab(),
            icon: Icon(Icons.message),
            activeIcon: Icon(Icons.message),
            initialCount: 10,
        ),
        1: NavigationTab.tab(
            title: "Settings",
            page: SettingsTab(),
            icon: Icon(Icons.settings),
            activeIcon: Icon(Icons.settings),
        ),
    });
```

Yukaridaki ornekte, Chat sekmesine baslangic sayisi 10 olan bir rozet ekledik.

Rozet sayisini programatik olarak da guncelleyebilirsiniz.

``` dart
/// Increment the badge count
BaseNavigationHub.stateActions.incrementBadgeCount(tab: 0);

/// Update the badge count
BaseNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 5);

/// Clear the badge count
BaseNavigationHub.stateActions.clearBadgeCount(tab: 0);
```

Varsayilan olarak rozet sayisi hatirlanir. Her oturumda rozet sayisini **temizlemek** istiyorsaniz, `rememberCount`'u `false` olarak ayarlayabilirsiniz.

``` dart
0: NavigationTab.badge(
    title: "Chats",
    page: ChatTab(),
    icon: Icon(Icons.message),
    activeIcon: Icon(Icons.message),
    initialCount: 10,
    rememberCount: false,
),
```

<div id="adding-alerts-to-tabs"></div>

## Sekmelere Uyari Ekleme

Sekmelerinize uyari ekleyebilirsiniz.

Bazen rozet sayisi gostermek istemeyebilirsiniz, ancak kullaniciya bir uyari gostergesi gostermek isteyebilirsiniz.

Bir sekmeye uyari eklemek icin `NavigationTab.alert` constructor'ini kullanabilirsiniz.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() => {
        0: NavigationTab.alert(
            title: "Chats",
            page: ChatTab(),
            icon: Icon(Icons.message),
            activeIcon: Icon(Icons.message),
            alertColor: Colors.red,
            alertEnabled: true,
            rememberAlert: false,
        ),
        1: NavigationTab.tab(
            title: "Settings",
            page: SettingsTab(),
            icon: Icon(Icons.settings),
            activeIcon: Icon(Icons.settings),
        ),
    });
```

Bu, Chat sekmesine kirmizi renkte bir uyari ekleyecektir.

Uyariyi programatik olarak da guncelleyebilirsiniz.

``` dart
/// Enable the alert
BaseNavigationHub.stateActions.alertEnableTab(tab: 0);

/// Disable the alert
BaseNavigationHub.stateActions.alertDisableTab(tab: 0);
```

<div id="initial-index"></div>

## Baslangic Indeksi

Varsayilan olarak, Navigation Hub ilk sekmede (indeks 0) baslar. Bunu `initialIndex` getter'ini override ederek degistirebilirsiniz.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    int get initialIndex => 1; // Start on the second tab
    ...
}
```

<div id="maintaining-state"></div>

## Durumu Koruma

Varsayilan olarak, Navigation Hub'in durumu korunur.

Bu, bir sekmeye gittiginizde sekmenin durumunun saklanacagi anlamina gelir.

Her gittiginizde sekmenin durumunu temizlemek istiyorsaniz, `maintainState`'i `false` olarak ayarlayabilirsiniz.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    bool get maintainState => false;
    ...
}
```

<div id="on-tap"></div>

## onTap

Bir sekmeye dokunuldugunda ozel mantik eklemek icin `onTap` metodunu override edebilirsiniz.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    onTap(int index) {
        // Add custom logic here
        // E.g. track analytics, show confirmation, etc.
        super.onTap(index); // Always call super to handle the tab switch
    }
}
```

<div id="state-actions"></div>

## Durum Eylemleri

Durum eylemleri, uygulamanizin herhangi bir yerinden Navigation Hub ile etkilesim kurmanin bir yoludur.

Kullanabileceginiz durum eylemleri sunlardir:

``` dart
/// Reset the tab at a given index
/// E.g. MyNavigationHub.stateActions.resetTabIndex(0);
resetTabIndex(int tabIndex);

/// Change the current tab programmatically
/// E.g. MyNavigationHub.stateActions.currentTabIndex(2);
currentTabIndex(int tabIndex);

/// Update the badge count
/// E.g. MyNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 2);
updateBadgeCount({required int tab, required int count});

/// Increment the badge count
/// E.g. MyNavigationHub.stateActions.incrementBadgeCount(tab: 0);
incrementBadgeCount({required int tab});

/// Clear the badge count
/// E.g. MyNavigationHub.stateActions.clearBadgeCount(tab: 0);
clearBadgeCount({required int tab});

/// Enable the alert for a tab
/// E.g. MyNavigationHub.stateActions.alertEnableTab(tab: 0);
alertEnableTab({required int tab});

/// Disable the alert for a tab
/// E.g. MyNavigationHub.stateActions.alertDisableTab(tab: 0);
alertDisableTab({required int tab});

/// Navigate to the next page in a journey layout
/// E.g. await MyNavigationHub.stateActions.nextPage();
Future<bool> nextPage();

/// Navigate to the previous page in a journey layout
/// E.g. await MyNavigationHub.stateActions.previousPage();
Future<bool> previousPage();
```

Bir durum eylemi kullanmak icin asagidakileri yapabilirsiniz:

``` dart
MyNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 2);

MyNavigationHub.stateActions.resetTabIndex(0);

MyNavigationHub.stateActions.currentTabIndex(2); // Switch to tab 2

await MyNavigationHub.stateActions.nextPage(); // Journey: go to next page
```

<div id="loading-style"></div>

## Yukleme Stili

Kutudan ciktigi haliyle, Navigation Hub sekme yuklenirken **varsayilan** yukleme Widget'inizi (resources/widgets/loader_widget.dart) gosterecektir.

Yukleme stilini guncellemek icin `loadingStyle`'i ozellestirebilirsiniz.

| Stil | Aciklama |
| --- | --- |
| normal | Varsayilan yukleme stili |
| skeletonizer | Iskelet yukleme stili |
| none | Yukleme stili yok |

Yukleme stilini su sekilde degistirebilirsiniz:

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal();
// or
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer();
```

Stillerden birindeki yukleme Widget'ini guncellemek istiyorsaniz, `LoadingStyle`'a bir `child` gecebilirsiniz.

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal(
    child: Center(
        child: Text("Loading..."),
    ),
);
```

Simdi, sekme yuklenirken "Loading..." metni goruntulecektir.

Asagida ornek:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    _MyNavigationHubState() : super(() async {

      await sleep(3); // simulate loading for 3 seconds

      return {
        0: NavigationTab.tab(
          title: "Home",
          page: HomeTab(),
        ),
        1: NavigationTab.tab(
          title: "Settings",
          page: SettingsTab(),
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

## Navigation Hub Olusturma

Bir Navigation Hub olusturmak icin [Metro](/docs/{{$version}}/metro) kullanabilirsiniz, asagidaki komutu calistirin.

``` bash
metro make:navigation_hub base
```

Komut, duzen turunu secebileceginiz ve sekmelerinizi veya journey durumlarinizi tanimlayabileceginiz interaktif bir kurulum surecinde size rehberlik edecektir.

Bu, `resources/pages/navigation_hubs/base/` dizininizde alt widget'larin `tabs/` veya `states/` alt klasorlerinde duzenlendirilmis bir `base_navigation_hub.dart` dosyasi olusturacaktir.
