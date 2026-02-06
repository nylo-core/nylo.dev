# Navigation Hub

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- [Penggunaan Dasar](#basic-usage "Penggunaan Dasar")
  - [Membuat Navigation Hub](#creating-a-navigation-hub "Membuat Navigation Hub")
  - [Membuat Tab Navigasi](#creating-navigation-tabs "Membuat Tab Navigasi")
  - [Navigasi Bawah](#bottom-navigation "Navigasi Bawah")
    - [Gaya Navigasi Bawah](#bottom-nav-styles "Gaya Navigasi Bawah")
    - [Nav Bar Builder Kustom](#custom-nav-bar-builder "Nav Bar Builder Kustom")
  - [Navigasi Atas](#top-navigation "Navigasi Atas")
  - [Navigasi Journey](#journey-navigation "Navigasi Journey")
    - [Gaya Progress](#journey-progress-styles "Gaya Progress Journey")
    - [Gaya Tombol](#journey-button-styles "Gaya Tombol")
    - [JourneyState](#journey-state "JourneyState")
    - [Method Helper JourneyState](#journey-state-helper-methods "Method Helper JourneyState")
- [Navigasi di dalam tab](#navigating-within-a-tab "Navigasi di dalam tab")
- [Tab](#tabs "Tab")
  - [Menambahkan Badge ke Tab](#adding-badges-to-tabs "Menambahkan Badge ke Tab")
  - [Menambahkan Alert ke Tab](#adding-alerts-to-tabs "Menambahkan Alert ke Tab")
- [Mempertahankan state](#maintaining-state "Mempertahankan state")
- [Aksi State](#state-actions "Aksi State")
- [Gaya Loading](#loading-style "Gaya Loading")
- [Membuat Navigation Hub](#creating-a-navigation-hub "Membuat Navigation Hub")

<div id="introduction"></div>

## Pengantar

Navigation Hub adalah tempat sentral di mana Anda dapat **mengelola** navigasi untuk semua widget Anda.
Secara langsung Anda dapat membuat tata letak navigasi bawah, atas, dan journey dalam hitungan detik.

Mari **bayangkan** Anda memiliki aplikasi dan ingin menambahkan navigasi bar bawah serta memungkinkan pengguna berpindah antar tab di aplikasi Anda.

Anda dapat menggunakan Navigation Hub untuk membangun ini.

Mari kita lihat bagaimana Anda dapat menggunakan Navigation Hub di aplikasi Anda.

<div id="basic-usage"></div>

## Penggunaan Dasar

Anda dapat membuat Navigation Hub menggunakan perintah di bawah ini.

``` bash
metro make:navigation_hub base
```

Ini akan membuat file **base_navigation_hub.dart** di direktori `resources/pages/` Anda.

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

Anda dapat melihat bahwa Navigation Hub memiliki **dua** tab, Home dan Settings.

Anda dapat membuat lebih banyak tab dengan menambahkan NavigationTab ke Navigation Hub.

Pertama, Anda perlu membuat widget baru menggunakan Metro.

``` bash
metro make:stateful_widget create_advert_tab
```

Anda juga dapat membuat beberapa widget sekaligus.

``` bash
metro make:stateful_widget news_tab,notifications_tab
```

Kemudian, Anda dapat menambahkan widget baru ke Navigation Hub.

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

Masih **banyak** lagi yang dapat Anda lakukan dengan Navigation Hub, mari kita bahas beberapa fiturnya.

<div id="bottom-navigation"></div>

### Navigasi Bawah

Anda dapat mengubah tata letak menjadi navigasi bar bawah dengan mengatur **layout** menggunakan `NavigationHubLayout.bottomNav`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav();
```

Anda dapat menyesuaikan navigasi bar bawah dengan mengatur properti seperti berikut:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav(
        // sesuaikan properti tata letak bottomNav
    );
```

<div id="bottom-nav-styles"></div>

### Gaya Navigasi Bawah

Anda dapat menerapkan gaya preset ke navigasi bar bawah Anda menggunakan parameter `style`.

Nylo menyediakan beberapa gaya bawaan:

- `BottomNavStyle.material()` - Gaya material Flutter default
- `BottomNavStyle.glass()` - Efek kaca buram bergaya iOS 26 dengan blur
- `BottomNavStyle.floating()` - Navigasi bar mengambang berbentuk pil dengan sudut membulat

#### Gaya Glass

Gaya glass menciptakan efek kaca buram yang indah, sempurna untuk desain modern terinspirasi iOS 26.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav(
        style: BottomNavStyle.glass(),
    );
```

Anda dapat menyesuaikan efek glass:

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

#### Gaya Floating

Gaya floating menciptakan navigasi bar berbentuk pil yang mengambang di atas tepi bawah.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav(
        style: BottomNavStyle.floating(),
    );
```

Anda dapat menyesuaikan gaya floating:

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

### Nav Bar Builder Kustom

Untuk kontrol penuh atas navigasi bar Anda, Anda dapat menggunakan parameter `navBarBuilder`.

Ini memungkinkan Anda membangun widget kustom apa pun sambil tetap menerima data navigasi.

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

Objek `NavBarData` berisi:

| Properti | Tipe | Deskripsi |
| --- | --- | --- |
| `items` | `List<BottomNavigationBarItem>` | Item navigasi bar |
| `currentIndex` | `int` | Indeks yang sedang dipilih |
| `onTap` | `ValueChanged<int>` | Callback saat tab diketuk |

Berikut contoh navigasi bar glass kustom sepenuhnya:

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

> **Catatan:** Saat menggunakan `navBarBuilder`, parameter `style` akan diabaikan.

<div id="top-navigation"></div>

### Navigasi Atas

Anda dapat mengubah tata letak menjadi navigasi bar atas dengan mengatur **layout** menggunakan `NavigationHubLayout.topNav`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.topNav();
```

Anda dapat menyesuaikan navigasi bar atas dengan mengatur properti seperti berikut:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.topNav(
        // sesuaikan properti tata letak topNav
    );
```

<div id="journey-navigation"></div>

### Navigasi Journey

Anda dapat mengubah tata letak menjadi navigasi journey dengan mengatur **layout** menggunakan `NavigationHubLayout.journey`.

Ini sangat cocok untuk alur onboarding atau form multi-langkah.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        // sesuaikan properti tata letak journey
    );
```

Jika Anda ingin menggunakan tata letak navigasi journey, **widget** Anda harus menggunakan `JourneyState` karena berisi banyak method helper untuk membantu Anda mengelola journey.

Anda dapat membuat JourneyState menggunakan perintah di bawah ini.

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```
Ini akan membuat file berikut di direktori **resources/widgets/** Anda: `welcome.dart`, `phone_number_step.dart` dan `add_photos_step.dart`.

Anda kemudian dapat menambahkan widget baru ke Navigation Hub.

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

Tata letak navigasi journey akan secara otomatis menangani tombol kembali dan lanjut untuk Anda jika Anda mendefinisikan `buttonStyle`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        buttonStyle: JourneyButtonStyle.standard(
            // Sesuaikan properti tombol
        ),
    );
```

Anda juga dapat menyesuaikan logika di widget Anda.

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
    // Logika inisialisasi Anda di sini
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

  /// Periksa apakah journey dapat melanjutkan ke langkah berikutnya
  /// Override method ini untuk menambahkan logika validasi
  Future<bool> canContinue() async {
    // Lakukan logika validasi Anda di sini
    // Kembalikan true jika journey dapat melanjutkan, false jika tidak
    return true;
  }

  /// Dipanggil saat tidak dapat melanjutkan (canContinue mengembalikan false)
  /// Override method ini untuk menangani kegagalan validasi
  Future<void> onCannotContinue() async {
    showToastSorry(description: "You cannot continue");
  }

  /// Dipanggil sebelum berpindah ke langkah berikutnya
  /// Override method ini untuk melakukan aksi sebelum melanjutkan
  Future<void> onBeforeNext() async {
    // Contoh: simpan data di sini sebelum berpindah
  }

  /// Dipanggil setelah berpindah ke langkah berikutnya
  /// Override method ini untuk melakukan aksi setelah melanjutkan
  Future<void> onAfterNext() async {
    // print('Navigated to the next step');
  }

  /// Dipanggil saat journey selesai (di langkah terakhir)
  /// Override method ini untuk melakukan aksi penyelesaian
  Future<void> onComplete() async {}
}
```

Anda dapat meng-override method apa pun di kelas `JourneyState`.

<div id="journey-progress-styles"></div>

### Gaya Progress Journey

Anda dapat menyesuaikan gaya indikator progress menggunakan kelas `JourneyProgressStyle`.

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

Anda dapat menggunakan gaya progress berikut:

- `JourneyProgressIndicator.none`: Tidak merender apa pun — berguna untuk menyembunyikan indikator pada tab tertentu.
- `JourneyProgressIndicator.linear`: Indikator progress linear.
- `JourneyProgressIndicator.dots`: Indikator progress berbasis titik.
- `JourneyProgressIndicator.numbered`: Indikator progress langkah bernomor.
- `JourneyProgressIndicator.segments`: Gaya progress bar tersegmentasi.
- `JourneyProgressIndicator.circular`: Indikator progress melingkar.
- `JourneyProgressIndicator.timeline`: Indikator progress gaya timeline.
- `JourneyProgressIndicator.custom`: Indikator progress kustom menggunakan fungsi builder.

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

Anda dapat menyesuaikan posisi dan padding indikator progress di dalam `JourneyProgressStyle`:

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

Anda dapat menggunakan posisi indikator progress berikut:

- `ProgressIndicatorPosition.top`: Indikator progress di bagian atas layar.
- `ProgressIndicatorPosition.bottom`: Indikator progress di bagian bawah layar.

#### Override Gaya Progress Per-Tab

Anda dapat meng-override `progressStyle` pada level layout untuk tab individual menggunakan `NavigationTab.journey(progressStyle: ...)`. Tab tanpa `progressStyle` sendiri akan mewarisi default layout. Tab tanpa default layout dan tanpa gaya per-tab tidak akan menampilkan indikator progress.

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

### Gaya Tombol Journey

Jika Anda ingin membangun alur onboarding, Anda dapat mengatur properti `buttonStyle` di kelas `NavigationHubLayout.journey`.

Secara langsung, Anda dapat menggunakan gaya tombol berikut:

- `JourneyButtonStyle.standard`: Gaya tombol standar dengan properti yang dapat disesuaikan.
- `JourneyButtonStyle.minimal`: Gaya tombol minimal hanya dengan ikon.
- `JourneyButtonStyle.outlined`: Gaya tombol outlined.
- `JourneyButtonStyle.contained`: Gaya tombol contained.
- `JourneyButtonStyle.custom`: Gaya tombol kustom menggunakan fungsi builder.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle.linear(),
        buttonStyle: JourneyButtonStyle.standard(
            // Sesuaikan properti tombol
        ),
    );
```

<div id="journey-state"></div>

### JourneyState

Kelas `JourneyState` berisi banyak method helper untuk membantu Anda mengelola journey.

Untuk membuat `JourneyState` baru, Anda dapat menggunakan perintah di bawah ini.

``` bash
metro make:journey_widget onboard_user_dob
```

Atau jika Anda ingin membuat beberapa widget sekaligus, Anda dapat menggunakan perintah berikut.

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

Ini akan membuat file berikut di direktori **resources/widgets/** Anda: `welcome.dart`, `phone_number_step.dart` dan `add_photos_step.dart`.

Anda kemudian dapat menambahkan widget baru ke Navigation Hub.

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

Jika kita melihat kelas `WelcomeStep`, kita dapat melihat bahwa ia meng-extend kelas `JourneyState`.

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

Anda akan melihat bahwa kelas **JourneyState** menggunakan `buildJourneyContent` untuk membangun konten halaman.

Berikut daftar properti yang dapat Anda gunakan di method `buildJourneyContent`.

| Properti | Tipe | Deskripsi |
| --- | --- | --- |
| `content` | `Widget` | Konten utama halaman. |
| `nextButton` | `Widget?` | Widget tombol lanjut. |
| `backButton` | `Widget?` | Widget tombol kembali. |
| `contentPadding` | `EdgeInsetsGeometry` | Padding untuk konten. |
| `header` | `Widget?` | Widget header. |
| `footer` | `Widget?` | Widget footer. |
| `crossAxisAlignment` | `CrossAxisAlignment` | Alignment sumbu silang konten. |


<div id="journey-state-helper-methods"></div>

### Method Helper JourneyState

Kelas `JourneyState` memiliki beberapa method helper yang dapat Anda gunakan untuk menyesuaikan perilaku journey Anda.

| Method | Deskripsi |
| --- | --- |
| [`onNextPressed()`](#on-next-pressed) | Dipanggil saat tombol lanjut ditekan. |
| [`onBackPressed()`](#on-back-pressed) | Dipanggil saat tombol kembali ditekan. |
| [`onComplete()`](#on-complete) | Dipanggil saat journey selesai (di langkah terakhir). |
| [`onBeforeNext()`](#on-before-next) | Dipanggil sebelum berpindah ke langkah berikutnya. |
| [`onAfterNext()`](#on-after-next) | Dipanggil setelah berpindah ke langkah berikutnya. |
| [`onCannotContinue()`](#on-cannot-continue) | Dipanggil saat journey tidak dapat melanjutkan (canContinue mengembalikan false). |
| [`canContinue()`](#can-continue) | Dipanggil saat pengguna mencoba berpindah ke langkah berikutnya. |
| [`isFirstStep`](#is-first-step) | Mengembalikan true jika ini adalah langkah pertama dalam journey. |
| [`isLastStep`](#is-last-step) | Mengembalikan true jika ini adalah langkah terakhir dalam journey. |
| [`goToStep(int index)`](#go-to-step) | Navigasi ke indeks langkah berikutnya. |
| [`goToNextStep()`](#go-to-next-step) | Navigasi ke langkah berikutnya. |
| [`goToPreviousStep()`](#go-to-previous-step) | Navigasi ke langkah sebelumnya. |
| [`goToFirstStep()`](#go-to-first-step) | Navigasi ke langkah pertama. |
| [`goToLastStep()`](#go-to-last-step) | Navigasi ke langkah terakhir. |


<div id="on-next-pressed"></div>

#### onNextPressed

Method `onNextPressed` dipanggil saat tombol lanjut ditekan.

Contoh: Anda dapat menggunakan method ini untuk memicu langkah berikutnya dalam journey.

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
            onPressed: onNextPressed, // ini akan mencoba berpindah ke langkah berikutnya
        ),
    );
}
```

<div id="on-back-pressed"></div>

#### onBackPressed

Method `onBackPressed` dipanggil saat tombol kembali ditekan.

Contoh: Anda dapat menggunakan method ini untuk memicu langkah sebelumnya dalam journey.

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
            onPressed: onBackPressed, // ini akan mencoba berpindah ke langkah sebelumnya
        ),
    );
}
```

<div id="on-complete"></div>

#### onComplete

Method `onComplete` dipanggil saat journey selesai (di langkah terakhir).

Contoh: jika widget ini adalah langkah terakhir dalam journey, method ini akan dipanggil.

``` dart
Future<void> onComplete() async {
    print("Journey completed");
}
```

<div id="on-before-next"></div>

#### onBeforeNext

Method `onBeforeNext` dipanggil sebelum berpindah ke langkah berikutnya.

Contoh: jika Anda ingin menyimpan data sebelum berpindah ke langkah berikutnya, Anda dapat melakukannya di sini.

``` dart
Future<void> onBeforeNext() async {
    // Contoh: simpan data di sini sebelum berpindah
}
```

<div id="is-first-step"></div>

#### isFirstStep

Method `isFirstStep` mengembalikan true jika ini adalah langkah pertama dalam journey.

Contoh: Anda dapat menggunakan method ini untuk menonaktifkan tombol kembali jika ini adalah langkah pertama.

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
        backButton: isFirstStep ? null : Button.textOnly( // Contoh menonaktifkan tombol kembali
            text: "Back",
            textColor: Colors.black87,
            onPressed: onBackPressed,
        ),
    );
}
```

<div id="is-last-step"></div>

#### isLastStep

Method `isLastStep` mengembalikan true jika ini adalah langkah terakhir dalam journey.

Contoh: Anda dapat menggunakan method ini untuk menonaktifkan tombol lanjut jika ini adalah langkah terakhir.

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
            text: isLastStep ? "Get Started" : "Continue", // Contoh memperbarui teks tombol lanjut
            onPressed: onNextPressed,
        ),
    );
}
```

<div id="go-to-step"></div>

#### goToStep

Method `goToStep` digunakan untuk berpindah ke langkah tertentu dalam journey.

Contoh: Anda dapat menggunakan method ini untuk berpindah ke langkah tertentu dalam journey.

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
                goToStep(2); // ini akan berpindah ke langkah dengan indeks 2
                // Catatan: ini tidak akan memicu method onNextPressed
            },
        ),
    );
}
```

<div id="go-to-next-step"></div>

#### goToNextStep

Method `goToNextStep` digunakan untuk berpindah ke langkah berikutnya dalam journey.

Contoh: Anda dapat menggunakan method ini untuk berpindah ke langkah berikutnya dalam journey.

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
                goToNextStep(); // ini akan berpindah ke langkah berikutnya
                // Catatan: ini tidak akan memicu method onNextPressed
            },
        ),
    );
}
```

<div id="go-to-previous-step"></div>

#### goToPreviousStep

Method `goToPreviousStep` digunakan untuk berpindah ke langkah sebelumnya dalam journey.

Contoh: Anda dapat menggunakan method ini untuk berpindah ke langkah sebelumnya dalam journey.

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
                goToPreviousStep(); // ini akan berpindah ke langkah sebelumnya
            },
        ),
    );
}
```

<div id="on-after-next"></div>

#### onAfterNext

Method `onAfterNext` dipanggil setelah berpindah ke langkah berikutnya.


Contoh: jika Anda ingin melakukan aksi setelah berpindah ke langkah berikutnya, Anda dapat melakukannya di sini.

``` dart
Future<void> onAfterNext() async {
    // print('Navigated to the next step');
}
```

<div id="on-cannot-continue"></div>

#### onCannotContinue

Method `onCannotContinue` dipanggil saat journey tidak dapat melanjutkan (canContinue mengembalikan false).

Contoh: jika Anda ingin menampilkan pesan error saat pengguna mencoba berpindah ke langkah berikutnya tanpa mengisi field yang diperlukan, Anda dapat melakukannya di sini.

``` dart
Future<void> onCannotContinue() async {
    showToastSorry(description: "You cannot continue");
}
```

<div id="can-continue"></div>

#### canContinue

Method `canContinue` dipanggil saat pengguna mencoba berpindah ke langkah berikutnya.

Contoh: jika Anda ingin melakukan validasi sebelum berpindah ke langkah berikutnya, Anda dapat melakukannya di sini.

``` dart
Future<bool> canContinue() async {
    // Lakukan logika validasi Anda di sini
    // Kembalikan true jika journey dapat melanjutkan, false jika tidak
    return true;
}
```

<div id="go-to-first-step"></div>

#### goToFirstStep

Method `goToFirstStep` digunakan untuk berpindah ke langkah pertama dalam journey.


Contoh: Anda dapat menggunakan method ini untuk berpindah ke langkah pertama dalam journey.

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
                goToFirstStep(); // ini akan berpindah ke langkah pertama
            },
        ),
    );
}
```

<div id="go-to-last-step"></div>

#### goToLastStep

Method `goToLastStep` digunakan untuk berpindah ke langkah terakhir dalam journey.

Contoh: Anda dapat menggunakan method ini untuk berpindah ke langkah terakhir dalam journey.

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
                goToLastStep(); // ini akan berpindah ke langkah terakhir
            },
        ),
    );
}
```

<div id="navigating-within-a-tab"></div>

## Navigasi ke widget di dalam tab

Anda dapat berpindah ke widget di dalam tab menggunakan helper `pushTo`.

Di dalam tab Anda, Anda dapat menggunakan helper `pushTo` untuk berpindah ke widget lain.

``` dart
_HomeTabState extends State<HomeTab> {
    ...
    void _navigateToSettings() {
        pushTo(SettingsPage());
    }
    ...
}
```

Anda juga dapat mengirim data ke widget yang Anda tuju.

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

## Tab

Tab adalah blok bangunan utama dari Navigation Hub.

Anda dapat menambahkan tab ke Navigation Hub menggunakan kelas `NavigationTab`.

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

Pada contoh di atas, kami telah menambahkan dua tab ke Navigation Hub, Home dan Settings.

Anda dapat menggunakan berbagai jenis tab seperti `NavigationTab`, `NavigationTab.badge`, dan `NavigationTab.alert`.

- Kelas `NavigationTab.badge` digunakan untuk menambahkan badge ke tab.
- Kelas `NavigationTab.alert` digunakan untuk menambahkan alert ke tab.
- Kelas `NavigationTab` digunakan untuk menambahkan tab normal.

<div id="adding-badges-to-tabs"></div>

## Menambahkan Badge ke Tab

Kami telah memudahkan untuk menambahkan badge ke tab Anda.

Badge adalah cara yang bagus untuk menunjukkan kepada pengguna bahwa ada sesuatu yang baru di tab.

Contoh, jika Anda memiliki aplikasi chat, Anda dapat menampilkan jumlah pesan yang belum dibaca di tab chat.

Untuk menambahkan badge ke tab, Anda dapat menggunakan kelas `NavigationTab.badge`.

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

Pada contoh di atas, kami telah menambahkan badge ke tab Chat dengan jumlah awal 10.

Anda juga dapat memperbarui jumlah badge secara programatis.

``` dart
/// Tambahkan jumlah badge
BaseNavigationHub.stateActions.incrementBadgeCount(tab: 0);

/// Perbarui jumlah badge
BaseNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 5);

/// Hapus jumlah badge
BaseNavigationHub.stateActions.clearBadgeCount(tab: 0);
```

Secara default, jumlah badge akan diingat. Jika Anda ingin **menghapus** jumlah badge setiap sesi, Anda dapat mengatur `rememberCount` ke `false`.

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

## Menambahkan Alert ke Tab

Anda dapat menambahkan alert ke tab Anda.

Terkadang Anda mungkin tidak ingin menampilkan jumlah badge, tetapi ingin menampilkan alert kepada pengguna.

Untuk menambahkan alert ke tab, Anda dapat menggunakan kelas `NavigationTab.alert`.

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

Ini akan menambahkan alert ke tab Chat dengan warna merah.

Anda juga dapat memperbarui alert secara programatis.

``` dart
/// Aktifkan alert
BaseNavigationHub.stateActions.alertEnableTab(tab: 0);

/// Nonaktifkan alert
BaseNavigationHub.stateActions.alertDisableTab(tab: 0);
```

<div id="maintaining-state"></div>

## Mempertahankan state

Secara default, state Navigation Hub dipertahankan.

Ini berarti saat Anda berpindah ke tab, state tab tersebut dipertahankan.

Jika Anda ingin menghapus state tab setiap kali Anda berpindah ke sana, Anda dapat mengatur `maintainState` ke `false`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    bool get maintainState => false;
    ...
}
```

<div id="state-actions"></div>

## Aksi State

Aksi state adalah cara untuk berinteraksi dengan Navigation Hub dari mana saja di aplikasi Anda.

Berikut beberapa aksi state yang dapat Anda gunakan:

``` dart
  /// Reset tab
  /// Contoh: MyNavigationHub.stateActions.resetTabState(tab: 0);
  resetTabState({required tab});

  /// Perbarui jumlah badge
  /// Contoh: MyNavigationHub.updateBadgeCount(tab: 0, count: 2);
  updateBadgeCount({required int tab, required int count});

  /// Tambahkan jumlah badge
  /// Contoh: MyNavigationHub.incrementBadgeCount(tab: 0);
  incrementBadgeCount({required int tab});

  /// Hapus jumlah badge
  /// Contoh: MyNavigationHub.clearBadgeCount(tab: 0);
  clearBadgeCount({required int tab});
```

Untuk menggunakan aksi state, Anda dapat melakukan hal berikut:

``` dart
MyNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 2);
// atau
MyNavigationHub.stateActions.resetTabState(tab: 0);
```

<div id="loading-style"></div>

## Gaya Loading

Secara langsung, Navigation Hub akan menampilkan Widget loading **default** Anda (resources/widgets/loader_widget.dart) saat tab sedang loading.

Anda dapat menyesuaikan `loadingStyle` untuk memperbarui gaya loading.

Berikut tabel untuk berbagai gaya loading yang dapat Anda gunakan:
// normal, skeletonizer, none

| Gaya | Deskripsi |
| --- | --- |
| normal | Gaya loading default |
| skeletonizer | Gaya loading skeleton |
| none | Tanpa gaya loading |

Anda dapat mengubah gaya loading seperti ini:

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal();
// atau
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer();
```

Jika Anda ingin memperbarui Widget loading di salah satu gaya, Anda dapat memasukkan `child` ke `LoadingStyle`.

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal(
    child: Center(
        child: Text("Loading..."),
    ),
);
```

Sekarang, saat tab sedang loading, teks "Loading..." akan ditampilkan.

Contoh di bawah ini:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
     _BaseNavigationHubState() : super(() async {

      await sleep(3); // simulasi loading selama 3 detik

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

## Membuat Navigation Hub

Untuk membuat Navigation Hub, Anda dapat menggunakan [Metro](/docs/{{$version}}/metro), gunakan perintah di bawah ini.

``` bash
metro make:navigation_hub base
```

Ini akan membuat file base_navigation_hub.dart di direktori `resources/pages/` Anda dan menambahkan Navigation Hub ke file `routes/router.dart` Anda.
