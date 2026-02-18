# Navigation Hub

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- [Penggunaan Dasar](#basic-usage "Penggunaan Dasar")
  - [Membuat Navigation Hub](#creating-a-navigation-hub "Membuat Navigation Hub")
  - [Membuat Tab Navigasi](#creating-navigation-tabs "Membuat Tab Navigasi")
  - [Navigasi Bawah](#bottom-navigation "Navigasi Bawah")
    - [Nav Bar Builder Kustom](#custom-nav-bar-builder "Nav Bar Builder Kustom")
  - [Navigasi Atas](#top-navigation "Navigasi Atas")
  - [Navigasi Journey](#journey-navigation "Navigasi Journey")
    - [Gaya Progress](#journey-progress-styles "Gaya Progress")
    - [JourneyState](#journey-state "JourneyState")
    - [Method Helper JourneyState](#journey-state-helper-methods "Method Helper JourneyState")
    - [onJourneyComplete](#on-journey-complete "onJourneyComplete")
    - [buildJourneyPage](#build-journey-page "buildJourneyPage")
- [Navigasi di dalam tab](#navigating-within-a-tab "Navigasi di dalam tab")
- [Tab](#tabs "Tab")
  - [Menambahkan Badge ke Tab](#adding-badges-to-tabs "Menambahkan Badge ke Tab")
  - [Menambahkan Alert ke Tab](#adding-alerts-to-tabs "Menambahkan Alert ke Tab")
- [Indeks Awal](#initial-index "Indeks Awal")
- [Mempertahankan State](#maintaining-state "Mempertahankan State")
- [onTap](#on-tap "onTap")
- [Aksi State](#state-actions "Aksi State")
- [Gaya Loading](#loading-style "Gaya Loading")

<div id="introduction"></div>

## Pengantar

Navigation Hub adalah tempat sentral di mana Anda dapat **mengelola** navigasi untuk semua widget Anda.
Secara langsung, Anda dapat membuat tata letak navigasi bawah, atas, dan journey dalam hitungan detik.

Mari **bayangkan** Anda memiliki aplikasi dan ingin menambahkan navigasi bar bawah serta memungkinkan pengguna berpindah antar tab di aplikasi Anda.

Anda dapat menggunakan Navigation Hub untuk membangun ini.

Mari kita lihat bagaimana cara menggunakan Navigation Hub di aplikasi Anda.

<div id="basic-usage"></div>

## Penggunaan Dasar

Anda dapat membuat Navigation Hub menggunakan perintah di bawah ini.

``` bash
metro make:navigation_hub base
```

Perintah ini akan memandu Anda melalui pengaturan interaktif:

1. **Pilih tipe layout** - Pilih antara `navigation_tabs` (navigasi bawah) atau `journey_states` (alur berurutan).
2. **Masukkan nama tab/state** - Berikan nama-nama yang dipisahkan koma untuk tab atau journey state Anda.

Ini akan membuat file di direktori `resources/pages/navigation_hubs/base/` Anda:
- `base_navigation_hub.dart` - Widget hub utama
- `tabs/` atau `states/` - Berisi widget anak untuk setiap tab atau journey state

Berikut tampilan Navigation Hub yang dihasilkan:

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

Anda dapat melihat bahwa Navigation Hub memiliki **dua** tab, Home dan Settings.

Method `layout` mengembalikan tipe layout untuk hub. Method ini menerima `BuildContext` sehingga Anda dapat mengakses data tema dan media query saat mengonfigurasi layout Anda.

Anda dapat membuat lebih banyak tab dengan menambahkan `NavigationTab` ke Navigation Hub.

Pertama, Anda perlu membuat widget baru menggunakan Metro.

``` bash
metro make:stateful_widget news_tab
```

Anda juga dapat membuat beberapa widget sekaligus.

``` bash
metro make:stateful_widget news_tab,notifications_tab
```

Kemudian, Anda dapat menambahkan widget baru ke Navigation Hub.

``` dart
_BaseNavigationHubState() : super(() => {
    0: NavigationTab.tab(title: "Home", page: HomeTab()),
    1: NavigationTab.tab(title: "Settings", page: SettingsTab()),
    2: NavigationTab.tab(title: "News", page: NewsTab()),
});
```

Untuk menggunakan Navigation Hub, tambahkan ke router Anda sebagai rute awal:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

appRouter() => nyRoutes((router) {
    ...
    router.add(BaseNavigationHub.path).initialRoute();
});

// or navigate to the Navigation Hub from anywhere in your app

routeTo(BaseNavigationHub.path);
```

Masih **banyak** lagi yang dapat Anda lakukan dengan Navigation Hub, mari kita bahas beberapa fiturnya.

<div id="bottom-navigation"></div>

### Navigasi Bawah

Anda dapat mengatur layout menjadi navigasi bar bawah dengan mengembalikan `NavigationHubLayout.bottomNav` dari method `layout`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
```

Anda dapat menyesuaikan navigasi bar bawah dengan mengatur properti seperti berikut:

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

Anda dapat menerapkan gaya preset ke navigasi bar bawah Anda menggunakan parameter `style`.

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav(
    style: BottomNavStyle.material(), // Default Flutter material style
);
```

<div id="custom-nav-bar-builder"></div>

### Nav Bar Builder Kustom

Untuk kontrol penuh atas navigasi bar Anda, Anda dapat menggunakan parameter `navBarBuilder`.

Ini memungkinkan Anda membangun widget kustom apa pun sambil tetap menerima data navigasi.

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

Anda dapat mengubah layout menjadi navigasi bar atas dengan mengembalikan `NavigationHubLayout.topNav` dari method `layout`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.topNav();
```

Anda dapat menyesuaikan navigasi bar atas dengan mengatur properti seperti berikut:

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

### Navigasi Journey

Anda dapat mengubah layout menjadi navigasi journey dengan mengembalikan `NavigationHubLayout.journey` dari method `layout`.

Ini sangat cocok untuk alur onboarding atau form multi-langkah.

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

Anda juga dapat mengatur `backgroundGradient` untuk layout journey:

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

> **Catatan:** Ketika `backgroundGradient` diatur, ia akan lebih diprioritaskan daripada `backgroundColor`.

Jika Anda ingin menggunakan layout navigasi journey, **widget** Anda sebaiknya menggunakan `JourneyState` karena berisi banyak method helper untuk membantu Anda mengelola journey.

Anda dapat membuat seluruh journey menggunakan perintah `make:navigation_hub` dengan layout `journey_states`:

``` bash
metro make:navigation_hub onboarding
# Select: journey_states
# Enter: welcome, personal_info, add_photos
```

Ini akan membuat hub dan semua widget journey state di `resources/pages/navigation_hubs/onboarding/states/`.

Atau Anda dapat membuat widget journey individual menggunakan:

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

Anda kemudian dapat menambahkan widget baru ke Navigation Hub.

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

### Gaya Progress Journey

Anda dapat menyesuaikan gaya indikator progress menggunakan kelas `JourneyProgressStyle`.

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

Anda dapat menggunakan indikator progress berikut:

- `JourneyProgressIndicator.none()`: Tidak merender apa pun - berguna untuk menyembunyikan indikator pada tab tertentu.
- `JourneyProgressIndicator.linear()`: Indikator progress linear.
- `JourneyProgressIndicator.dots()`: Indikator progress berbasis titik.
- `JourneyProgressIndicator.numbered()`: Indikator progress langkah bernomor.
- `JourneyProgressIndicator.segments()`: Gaya progress bar tersegmentasi.
- `JourneyProgressIndicator.circular()`: Indikator progress melingkar.
- `JourneyProgressIndicator.timeline()`: Indikator progress gaya timeline.
- `JourneyProgressIndicator.custom()`: Indikator progress kustom menggunakan fungsi builder.

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

Anda dapat menyesuaikan posisi dan padding indikator progress di dalam `JourneyProgressStyle`:

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

Anda dapat menggunakan posisi indikator progress berikut:

- `ProgressIndicatorPosition.top`: Indikator progress di bagian atas layar.
- `ProgressIndicatorPosition.bottom`: Indikator progress di bagian bawah layar.

#### Override Gaya Progress Per-Tab

Anda dapat meng-override `progressStyle` pada level layout untuk tab individual menggunakan `NavigationTab.journey(progressStyle: ...)`. Tab tanpa `progressStyle` sendiri akan mewarisi default layout. Tab tanpa default layout dan tanpa gaya per-tab tidak akan menampilkan indikator progress.

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

Kelas `JourneyState` meng-extend `NyState` dengan fungsionalitas khusus journey untuk mempermudah pembuatan alur onboarding dan journey multi-langkah.

Untuk membuat `JourneyState` baru, Anda dapat menggunakan perintah di bawah ini.

``` bash
metro make:journey_widget onboard_user_dob
```

Atau jika Anda ingin membuat beberapa widget sekaligus, Anda dapat menggunakan perintah berikut.

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

Berikut tampilan widget JourneyState yang dihasilkan:

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

Anda akan melihat bahwa kelas **JourneyState** menggunakan `nextStep` untuk berpindah maju dan `onBackPressed` untuk kembali.

Method `nextStep` menjalankan seluruh siklus validasi: `canContinue()` -> `onBeforeNext()` -> navigasi (atau `onComplete()` jika di langkah terakhir) -> `onAfterNext()`.

Anda juga dapat menggunakan `buildJourneyContent` untuk membangun layout terstruktur dengan tombol navigasi opsional:

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

Berikut properti yang dapat Anda gunakan di method `buildJourneyContent`.

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

Kelas `JourneyState` memiliki method helper dan properti yang dapat Anda gunakan untuk menyesuaikan perilaku journey Anda.

| Method / Properti | Deskripsi |
| --- | --- |
| [`nextStep()`](#next-step) | Berpindah ke langkah berikutnya dengan validasi. Mengembalikan `Future<bool>`. |
| [`previousStep()`](#previous-step) | Berpindah ke langkah sebelumnya. Mengembalikan `Future<bool>`. |
| [`onBackPressed()`](#on-back-pressed) | Helper sederhana untuk berpindah ke langkah sebelumnya. |
| [`onComplete()`](#on-complete) | Dipanggil saat journey selesai (di langkah terakhir). |
| [`onBeforeNext()`](#on-before-next) | Dipanggil sebelum berpindah ke langkah berikutnya. |
| [`onAfterNext()`](#on-after-next) | Dipanggil setelah berpindah ke langkah berikutnya. |
| [`canContinue()`](#can-continue) | Pengecekan validasi sebelum berpindah ke langkah berikutnya. |
| [`isFirstStep`](#is-first-step) | Mengembalikan true jika ini adalah langkah pertama dalam journey. |
| [`isLastStep`](#is-last-step) | Mengembalikan true jika ini adalah langkah terakhir dalam journey. |
| [`currentStep`](#current-step) | Mengembalikan indeks langkah saat ini (berbasis 0). |
| [`totalSteps`](#total-steps) | Mengembalikan jumlah total langkah. |
| [`completionPercentage`](#completion-percentage) | Mengembalikan persentase penyelesaian (0.0 hingga 1.0). |
| [`goToStep(int index)`](#go-to-step) | Langsung menuju langkah tertentu berdasarkan indeks. |
| [`goToNextStep()`](#go-to-next-step) | Langsung menuju langkah berikutnya (tanpa validasi). |
| [`goToPreviousStep()`](#go-to-previous-step) | Langsung menuju langkah sebelumnya (tanpa validasi). |
| [`goToFirstStep()`](#go-to-first-step) | Langsung menuju langkah pertama. |
| [`goToLastStep()`](#go-to-last-step) | Langsung menuju langkah terakhir. |
| [`exitJourney()`](#exit-journey) | Keluar dari journey dengan melakukan pop pada root navigator. |
| [`resetCurrentStep()`](#reset-current-step) | Mereset state langkah saat ini. |
| [`onJourneyComplete`](#on-journey-complete) | Callback saat journey selesai (override di langkah terakhir). |
| [`buildJourneyPage()`](#build-journey-page) | Membangun halaman journey layar penuh dengan Scaffold. |


<div id="next-step"></div>

#### nextStep

Method `nextStep` berpindah ke langkah berikutnya dengan validasi penuh. Method ini menjalankan siklus: `canContinue()` -> `onBeforeNext()` -> navigasi atau `onComplete()` -> `onAfterNext()`.

Anda dapat memasukkan `force: true` untuk melewati validasi dan langsung berpindah.

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

Untuk melewati validasi:

``` dart
onPressed: () => nextStep(force: true),
```

<div id="previous-step"></div>

#### previousStep

Method `previousStep` berpindah ke langkah sebelumnya. Mengembalikan `true` jika berhasil, `false` jika sudah di langkah pertama.

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

Method `onBackPressed` adalah helper sederhana yang memanggil `previousStep()` secara internal.

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

Method `onComplete` dipanggil saat `nextStep()` dipicu di langkah terakhir (setelah validasi berhasil).

``` dart
@override
Future<void> onComplete() async {
    print("Journey completed");
}
```

<div id="on-before-next"></div>

#### onBeforeNext

Method `onBeforeNext` dipanggil sebelum berpindah ke langkah berikutnya.

Contoh: jika Anda ingin menyimpan data sebelum berpindah ke langkah berikutnya, Anda dapat melakukannya di sini.

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

Method `onAfterNext` dipanggil setelah berpindah ke langkah berikutnya.

``` dart
@override
Future<void> onAfterNext() async {
    // print('Navigated to the next step');
}
```

<div id="can-continue"></div>

#### canContinue

Method `canContinue` dipanggil saat `nextStep()` dipicu. Kembalikan `false` untuk mencegah navigasi.

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

Properti `isFirstStep` mengembalikan true jika ini adalah langkah pertama dalam journey.

``` dart
backButton: isFirstStep ? null : Button.textOnly(
    text: "Back",
    textColor: Colors.black87,
    onPressed: onBackPressed,
),
```

<div id="is-last-step"></div>

#### isLastStep

Properti `isLastStep` mengembalikan true jika ini adalah langkah terakhir dalam journey.

``` dart
nextButton: Button.primary(
    text: isLastStep ? "Get Started" : "Continue",
    onPressed: nextStep,
),
```

<div id="current-step"></div>

#### currentStep

Properti `currentStep` mengembalikan indeks langkah saat ini (berbasis 0).

``` dart
Text("Step ${currentStep + 1} of $totalSteps"),
```

<div id="total-steps"></div>

#### totalSteps

Properti `totalSteps` mengembalikan jumlah total langkah dalam journey.

<div id="completion-percentage"></div>

#### completionPercentage

Properti `completionPercentage` mengembalikan persentase penyelesaian sebagai nilai dari 0.0 hingga 1.0.

``` dart
LinearProgressIndicator(value: completionPercentage),
```

<div id="go-to-step"></div>

#### goToStep

Method `goToStep` langsung menuju langkah tertentu berdasarkan indeks. Method ini **tidak** memicu validasi.

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

Method `goToNextStep` langsung menuju langkah berikutnya tanpa validasi. Jika sudah di langkah terakhir, method ini tidak melakukan apa-apa.

``` dart
onPressed: () {
    goToNextStep(); // skip validation and go to next step
},
```

<div id="go-to-previous-step"></div>

#### goToPreviousStep

Method `goToPreviousStep` langsung menuju langkah sebelumnya tanpa validasi. Jika sudah di langkah pertama, method ini tidak melakukan apa-apa.

``` dart
onPressed: () {
    goToPreviousStep();
},
```

<div id="go-to-first-step"></div>

#### goToFirstStep

Method `goToFirstStep` langsung menuju langkah pertama.

``` dart
onPressed: () {
    goToFirstStep();
},
```

<div id="go-to-last-step"></div>

#### goToLastStep

Method `goToLastStep` langsung menuju langkah terakhir.

``` dart
onPressed: () {
    goToLastStep();
},
```

<div id="exit-journey"></div>

#### exitJourney

Method `exitJourney` keluar dari journey dengan melakukan pop pada root navigator.

``` dart
onPressed: () {
    exitJourney(); // pop the root navigator
},
```

<div id="reset-current-step"></div>

#### resetCurrentStep

Method `resetCurrentStep` mereset state langkah saat ini.

``` dart
onPressed: () {
    resetCurrentStep();
},
```

<div id="on-journey-complete"></div>

### onJourneyComplete

Getter `onJourneyComplete` dapat di-override di **langkah terakhir** journey Anda untuk menentukan apa yang terjadi saat pengguna menyelesaikan alur tersebut.

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

Method `buildJourneyPage` membangun halaman journey layar penuh yang dibungkus dalam `Scaffold` dengan `SafeArea`.

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

| Properti | Tipe | Deskripsi |
| --- | --- | --- |
| `content` | `Widget` | Konten utama halaman. |
| `nextButton` | `Widget?` | Widget tombol lanjut. |
| `backButton` | `Widget?` | Widget tombol kembali. |
| `contentPadding` | `EdgeInsetsGeometry` | Padding untuk konten. |
| `header` | `Widget?` | Widget header. |
| `footer` | `Widget?` | Widget footer. |
| `backgroundColor` | `Color?` | Warna latar belakang Scaffold. |
| `appBar` | `Widget?` | Widget AppBar opsional. |
| `crossAxisAlignment` | `CrossAxisAlignment` | Alignment sumbu silang konten. |

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

Anda dapat menambahkan tab ke Navigation Hub menggunakan kelas `NavigationTab` dan named constructor-nya.

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

Pada contoh di atas, kita menambahkan dua tab ke Navigation Hub, Home dan Settings.

Anda dapat menggunakan berbagai jenis tab:

- `NavigationTab.tab()` - Tab navigasi standar.
- `NavigationTab.badge()` - Tab dengan jumlah badge.
- `NavigationTab.alert()` - Tab dengan indikator alert.
- `NavigationTab.journey()` - Tab untuk layout navigasi journey.

<div id="adding-badges-to-tabs"></div>

## Menambahkan Badge ke Tab

Kami telah memudahkan untuk menambahkan badge ke tab Anda.

Badge adalah cara yang bagus untuk menunjukkan kepada pengguna bahwa ada sesuatu yang baru di suatu tab.

Contohnya, jika Anda memiliki aplikasi chat, Anda dapat menampilkan jumlah pesan yang belum dibaca di tab chat.

Untuk menambahkan badge ke tab, Anda dapat menggunakan constructor `NavigationTab.badge`.

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

Pada contoh di atas, kita menambahkan badge ke tab Chat dengan jumlah awal 10.

Anda juga dapat memperbarui jumlah badge secara programatis.

``` dart
/// Increment the badge count
BaseNavigationHub.stateActions.incrementBadgeCount(tab: 0);

/// Update the badge count
BaseNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 5);

/// Clear the badge count
BaseNavigationHub.stateActions.clearBadgeCount(tab: 0);
```

Secara default, jumlah badge akan diingat. Jika Anda ingin **menghapus** jumlah badge setiap sesi, Anda dapat mengatur `rememberCount` ke `false`.

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

## Menambahkan Alert ke Tab

Anda dapat menambahkan alert ke tab Anda.

Terkadang Anda mungkin tidak ingin menampilkan jumlah badge, tetapi ingin menampilkan indikator alert kepada pengguna.

Untuk menambahkan alert ke tab, Anda dapat menggunakan constructor `NavigationTab.alert`.

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

Ini akan menambahkan alert ke tab Chat dengan warna merah.

Anda juga dapat memperbarui alert secara programatis.

``` dart
/// Enable the alert
BaseNavigationHub.stateActions.alertEnableTab(tab: 0);

/// Disable the alert
BaseNavigationHub.stateActions.alertDisableTab(tab: 0);
```

<div id="initial-index"></div>

## Indeks Awal

Secara default, Navigation Hub dimulai dari tab pertama (indeks 0). Anda dapat mengubahnya dengan meng-override getter `initialIndex`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    int get initialIndex => 1; // Start on the second tab
    ...
}
```

<div id="maintaining-state"></div>

## Mempertahankan State

Secara default, state Navigation Hub dipertahankan.

Ini berarti saat Anda berpindah ke tab, state tab tersebut akan dipertahankan.

Jika Anda ingin menghapus state tab setiap kali berpindah ke sana, Anda dapat mengatur `maintainState` ke `false`.

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

Anda dapat meng-override method `onTap` untuk menambahkan logika kustom saat sebuah tab diketuk.

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

## Aksi State

Aksi state adalah cara untuk berinteraksi dengan Navigation Hub dari mana saja di aplikasi Anda.

Berikut aksi state yang dapat Anda gunakan:

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

Untuk menggunakan aksi state, Anda dapat melakukan hal berikut:

``` dart
MyNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 2);

MyNavigationHub.stateActions.resetTabIndex(0);

MyNavigationHub.stateActions.currentTabIndex(2); // Switch to tab 2

await MyNavigationHub.stateActions.nextPage(); // Journey: go to next page
```

<div id="loading-style"></div>

## Gaya Loading

Secara default, Navigation Hub akan menampilkan Widget loading **default** Anda (resources/widgets/loader_widget.dart) saat tab sedang loading.

Anda dapat menyesuaikan `loadingStyle` untuk memperbarui gaya loading.

| Gaya | Deskripsi |
| --- | --- |
| normal | Gaya loading default |
| skeletonizer | Gaya loading skeleton |
| none | Tanpa gaya loading |

Anda dapat mengubah gaya loading seperti ini:

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal();
// or
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

## Membuat Navigation Hub

Untuk membuat Navigation Hub, Anda dapat menggunakan [Metro](/docs/{{$version}}/metro), gunakan perintah di bawah ini.

``` bash
metro make:navigation_hub base
```

Perintah ini akan memandu Anda melalui pengaturan interaktif di mana Anda dapat memilih tipe layout dan menentukan tab atau journey state Anda.

Ini akan membuat file `base_navigation_hub.dart` di direktori `resources/pages/navigation_hubs/base/` Anda dengan widget anak yang terorganisir di subfolder `tabs/` atau `states/`.
