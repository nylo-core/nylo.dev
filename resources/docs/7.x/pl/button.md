# Button

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie")
- [Podstawowe użycie](#basic-usage "Podstawowe użycie")
- [Dostępne typy przycisków](#button-types "Dostępne typy przycisków")
    - [Primary](#primary "Primary")
    - [Secondary](#secondary "Secondary")
    - [Outlined](#outlined "Outlined")
    - [Text Only](#text-only "Text Only")
    - [Icon](#icon "Icon")
    - [Gradient](#gradient "Gradient")
    - [Rounded](#rounded "Rounded")
    - [Transparency](#transparency "Transparency")
- [Asynchroniczny stan ładowania](#async-loading "Asynchroniczny stan ładowania")
- [Style animacji](#animation-styles "Style animacji")
    - [Clickable](#anim-clickable "Clickable")
    - [Bounce](#anim-bounce "Bounce")
    - [Pulse](#anim-pulse "Pulse")
    - [Squeeze](#anim-squeeze "Squeeze")
    - [Jelly](#anim-jelly "Jelly")
    - [Shine](#anim-shine "Shine")
    - [Ripple](#anim-ripple "Ripple")
    - [Morph](#anim-morph "Morph")
    - [Shake](#anim-shake "Shake")
- [Style splash](#splash-styles "Style splash")
- [Style ładowania](#loading-styles "Style ładowania")
- [Wysyłanie formularzy](#form-submission "Wysyłanie formularzy")
- [Dostosowywanie przycisków](#customizing-buttons "Dostosowywanie przycisków")
- [Referencja parametrów](#parameters "Referencja parametrów")


<div id="introduction"></div>

## Wprowadzenie

{{ config('app.name') }} udostępnia klasę `Button` z ośmioma gotowymi stylami przycisków. Każdy przycisk posiada wbudowaną obsługę:

- **Asynchroniczne stany ładowania** — zwróć `Future` z `onPressed`, a przycisk automatycznie wyświetli wskaźnik ładowania
- **Style animacji** — wybierz spośród efektów clickable, bounce, pulse, squeeze, jelly, shine, ripple, morph i shake
- **Style splash** — dodaj reakcje dotykowe ripple, highlight, glow lub ink
- **Wysyłanie formularzy** — podłącz przycisk bezpośrednio do instancji `NyFormData`

Definicje przycisków aplikacji znajdziesz w `lib/resources/widgets/buttons/buttons.dart`. Ten plik zawiera klasę `Button` z metodami statycznymi dla każdego typu przycisku, co ułatwia dostosowanie domyślnych ustawień projektu.

<div id="basic-usage"></div>

## Podstawowe użycie

Używaj klasy `Button` w dowolnym miejscu widgetów. Oto prosty przykład na stronie:

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: SafeArea(
      child: Padding(
        padding: EdgeInsets.all(16),
        child: Column(
          children: [
            Button.primary(
              text: "Sign Up",
              onPressed: () {
                routeTo(SignUpPage.path);
              },
            ),

            SizedBox(height: 12),

            Button.secondary(
              text: "Learn More",
              onPressed: () {
                routeTo(AboutPage.path);
              },
            ),

            SizedBox(height: 12),

            Button.outlined(
              text: "Cancel",
              onPressed: () {
                Navigator.pop(context);
              },
            ),
          ],
        ),
      ),
    ),
  );
}
```

Każdy typ przycisku działa według tego samego wzorca — przekaż etykietę `text` i callback `onPressed`.

<div id="button-types"></div>

## Dostępne typy przycisków

Wszystkie przyciski są dostępne przez klasę `Button` za pomocą metod statycznych.

<div id="primary"></div>

### Primary

Wypełniony przycisk z cieniem, używający głównego koloru motywu. Najlepszy dla głównych elementów wezwania do działania.

``` dart
Button.primary(
  text: "Sign Up",
  onPressed: () {
    // Handle press
  },
)
```

<div id="secondary"></div>

### Secondary

Wypełniony przycisk z łagodniejszym kolorem powierzchni i subtelnym cieniem. Dobry dla akcji drugorzędnych obok przycisku głównego.

``` dart
Button.secondary(
  text: "Learn More",
  onPressed: () {
    // Handle press
  },
)
```

<div id="outlined"></div>

### Outlined

Przezroczysty przycisk z obramowaniem. Przydatny dla mniej widocznych akcji lub przycisków anulowania.

``` dart
Button.outlined(
  text: "Cancel",
  onPressed: () {
    // Handle press
  },
)
```

Możesz dostosować kolory obramowania i tekstu:

``` dart
Button.outlined(
  text: "Custom Outline",
  borderColor: Colors.red,
  textColor: Colors.red,
  onPressed: () {},
)
```

<div id="text-only"></div>

### Text Only

Minimalny przycisk bez tła i obramowania. Idealny dla akcji inline lub linków.

``` dart
Button.textOnly(
  text: "Skip",
  onPressed: () {
    // Handle press
  },
)
```

Możesz dostosować kolor tekstu:

``` dart
Button.textOnly(
  text: "View Details",
  textColor: Colors.blue,
  onPressed: () {},
)
```

<div id="icon"></div>

### Icon

Wypełniony przycisk wyświetlający ikonę obok tekstu. Ikona domyślnie pojawia się przed tekstem.

``` dart
Button.icon(
  text: "Add to Cart",
  icon: Icon(Icons.shopping_cart),
  onPressed: () {
    // Handle press
  },
)
```

Możesz dostosować kolor tła:

``` dart
Button.icon(
  text: "Download",
  icon: Icon(Icons.download),
  color: Colors.green,
  onPressed: () {},
)
```

<div id="gradient"></div>

### Gradient

Przycisk z liniowym gradientem tła. Domyślnie używa kolorów primary i tertiary motywu.

``` dart
Button.gradient(
  text: "Get Started",
  onPressed: () {
    // Handle press
  },
)
```

Możesz podać niestandardowe kolory gradientu:

``` dart
Button.gradient(
  text: "Premium",
  gradientColors: [Colors.purple, Colors.pink],
  onPressed: () {},
)
```

<div id="rounded"></div>

### Rounded

Przycisk w kształcie pigułki z pełnymi zaokrąglonymi rogami. Promień obramowania domyślnie wynosi połowę wysokości przycisku.

``` dart
Button.rounded(
  text: "Continue",
  onPressed: () {
    // Handle press
  },
)
```

Możesz dostosować kolor tła i promień obramowania:

``` dart
Button.rounded(
  text: "Apply",
  backgroundColor: Colors.teal,
  borderRadius: BorderRadius.circular(20),
  onPressed: () {},
)
```

<div id="transparency"></div>

### Transparency

Przycisk w stylu matowego szkła z efektem rozmycia tła. Dobrze wygląda umieszczony na obrazach lub kolorowych tłach.

``` dart
Button.transparency(
  text: "Explore",
  onPressed: () {
    // Handle press
  },
)
```

Możesz dostosować kolor tekstu:

``` dart
Button.transparency(
  text: "View More",
  color: Colors.white,
  onPressed: () {},
)
```

<div id="async-loading"></div>

## Asynchroniczny stan ładowania

Jedną z najpotężniejszych funkcji przycisków {{ config('app.name') }} jest **automatyczne zarządzanie stanem ładowania**. Gdy callback `onPressed` zwraca `Future`, przycisk automatycznie wyświetli wskaźnik ładowania i wyłączy interakcję do momentu zakończenia operacji.

``` dart
Button.primary(
  text: "Submit",
  onPressed: () async {
    await sleep(3); // Simulates a 3 second async task
  },
)
```

Podczas trwania operacji asynchronicznej przycisk wyświetli efekt skeleton loading (domyślnie). Po zakończeniu `Future` przycisk wraca do normalnego stanu.

Działa to z każdą operacją asynchroniczną — wywołaniami API, zapisami do bazy danych, przesyłaniem plików lub czymkolwiek, co zwraca `Future`:

``` dart
Button.primary(
  text: "Save Profile",
  onPressed: () async {
    await api<ApiService>((request) =>
      request.updateProfile(name: "John", email: "john@example.com")
    );
    showToastSuccess(description: "Profile saved!");
  },
)
```

``` dart
Button.secondary(
  text: "Sync Data",
  onPressed: () async {
    await fetchAndStoreData();
    await clearOldCache();
  },
)
```

Nie musisz zarządzać zmiennymi stanu `isLoading`, wywoływać `setState` ani opakowywać czegokolwiek w `StatefulWidget` — {{ config('app.name') }} obsługuje to wszystko za Ciebie.

### Jak to działa

Gdy przycisk wykryje, że `onPressed` zwraca `Future`, używa mechanizmu `lockRelease` do:

1. Wyświetlenia wskaźnika ładowania (kontrolowanego przez `LoadingStyle`)
2. Wyłączenia przycisku, aby zapobiec wielokrotnemu tapnięciu
3. Oczekiwania na zakończenie `Future`
4. Przywrócenia przycisku do normalnego stanu

<div id="animation-styles"></div>

## Style animacji

Przyciski obsługują animacje naciśnięcia przez `ButtonAnimationStyle`. Te animacje zapewniają wizualną informację zwrotną, gdy użytkownik wchodzi w interakcję z przyciskiem. Możesz ustawić styl animacji podczas dostosowywania przycisków w `lib/resources/widgets/buttons/buttons.dart`.

<div id="anim-clickable"></div>

### Clickable

Efekt naciśnięcia 3D w stylu Duolingo. Przycisk przesuwa się w dół po naciśnięciu i wraca na miejsce po zwolnieniu. Najlepszy dla głównych akcji i interfejsów w stylu gier.

``` dart
animationStyle: ButtonAnimationStyle.clickable()
```

Dostosuj efekt:

``` dart
ButtonAnimationStyle.clickable(
  translateY: 6.0,        // How far the button moves down (default: 4.0)
  shadowOffset: 6.0,      // Shadow depth (default: 4.0)
  duration: Duration(milliseconds: 100),
  enableHapticFeedback: true,
)
```

<div id="anim-bounce"></div>

### Bounce

Skaluje przycisk w dół po naciśnięciu i wraca sprężyście po zwolnieniu. Najlepszy dla przycisków dodawania do koszyka, polubienia i ulubionych.

``` dart
animationStyle: ButtonAnimationStyle.bounce()
```

Dostosuj efekt:

``` dart
ButtonAnimationStyle.bounce(
  scaleMin: 0.90,         // Minimum scale on press (default: 0.92)
  duration: Duration(milliseconds: 150),
  curve: Curves.easeOutBack,
  enableHapticFeedback: true,
)
```

<div id="anim-pulse"></div>

### Pulse

Subtelna ciągła pulsacja skali, gdy przycisk jest przytrzymany. Najlepsza dla akcji długiego naciśnięcia lub przyciągania uwagi.

``` dart
animationStyle: ButtonAnimationStyle.pulse()
```

Dostosuj efekt:

``` dart
ButtonAnimationStyle.pulse(
  pulseScale: 1.08,       // Max scale during pulse (default: 1.05)
  duration: Duration(milliseconds: 800),
  curve: Curves.easeInOut,
)
```

<div id="anim-squeeze"></div>

### Squeeze

Kompresuje przycisk poziomo i rozszerza pionowo po naciśnięciu. Najlepszy dla zabawnych i interaktywnych interfejsów.

``` dart
animationStyle: ButtonAnimationStyle.squeeze()
```

Dostosuj efekt:

``` dart
ButtonAnimationStyle.squeeze(
  squeezeX: 0.93,         // Horizontal scale (default: 0.95)
  squeezeY: 1.07,         // Vertical scale (default: 1.05)
  duration: Duration(milliseconds: 120),
  enableHapticFeedback: true,
)
```

<div id="anim-jelly"></div>

### Jelly

Chwiejny, elastyczny efekt deformacji. Najlepszy dla zabawnych, casualowych aplikacji lub aplikacji rozrywkowych.

``` dart
animationStyle: ButtonAnimationStyle.jelly()
```

Dostosuj efekt:

``` dart
ButtonAnimationStyle.jelly(
  jellyStrength: 0.2,     // Wobble intensity (default: 0.15)
  duration: Duration(milliseconds: 300),
  curve: Curves.elasticOut,
  enableHapticFeedback: true,
)
```

<div id="anim-shine"></div>

### Shine

Błyszczące podświetlenie przesuwające się po przycisku po naciśnięciu. Najlepsze dla funkcji premium lub CTA, na które chcesz zwrócić uwagę.

``` dart
animationStyle: ButtonAnimationStyle.shine()
```

Dostosuj efekt:

``` dart
ButtonAnimationStyle.shine(
  shineColor: Colors.white,  // Color of the shine streak (default: white)
  shineWidth: 0.4,           // Width of the shine band (default: 0.3)
  duration: Duration(milliseconds: 600),
)
```

<div id="anim-ripple"></div>

### Ripple

Ulepszony efekt ripple rozszerzający się od punktu dotyku. Najlepszy dla podkreślenia stylu Material Design.

``` dart
animationStyle: ButtonAnimationStyle.ripple()
```

Dostosuj efekt:

``` dart
ButtonAnimationStyle.ripple(
  rippleScale: 2.5,       // How far the ripple expands (default: 2.0)
  duration: Duration(milliseconds: 400),
  curve: Curves.easeOut,
  enableHapticFeedback: true,
)
```

<div id="anim-morph"></div>

### Morph

Promień obramowania przycisku zwiększa się po naciśnięciu, tworząc efekt zmiany kształtu. Najlepszy dla subtelnej, eleganckiej informacji zwrotnej.

``` dart
animationStyle: ButtonAnimationStyle.morph()
```

Dostosuj efekt:

``` dart
ButtonAnimationStyle.morph(
  morphRadius: 30.0,      // Target border radius on press (default: 24.0)
  duration: Duration(milliseconds: 150),
  curve: Curves.easeInOut,
)
```

<div id="anim-shake"></div>

### Shake

Animacja poziomego potrząsania. Najlepsza dla stanów błędu lub nieprawidłowych akcji — potrząśnij przyciskiem, aby zasygnalizować, że coś poszło nie tak.

``` dart
animationStyle: ButtonAnimationStyle.shake()
```

Dostosuj efekt:

``` dart
ButtonAnimationStyle.shake(
  shakeOffset: 10.0,      // Horizontal displacement (default: 8.0)
  shakeCount: 4,          // Number of shakes (default: 3)
  duration: Duration(milliseconds: 400),
  enableHapticFeedback: true,
)
```

### Wyłączanie animacji

Aby użyć przycisku bez animacji:

``` dart
animationStyle: ButtonAnimationStyle.none()
```

### Zmiana domyślnej animacji

Aby zmienić domyślną animację dla typu przycisku, zmodyfikuj plik `lib/resources/widgets/buttons/buttons.dart`:

``` dart
class Button {
  static Widget primary({
    required String text,
    VoidCallback? onPressed,
    ...
  }) {
    return PrimaryButton(
      text: text,
      onPressed: onPressed,
      animationStyle: ButtonAnimationStyle.bounce(), // Change the default
    );
  }
}
```

<div id="splash-styles"></div>

## Style splash

Efekty splash zapewniają wizualną informację zwrotną o dotyku na przyciskach. Konfiguruj je przez `ButtonSplashStyle`. Style splash można łączyć ze stylami animacji dla wielowarstwowej informacji zwrotnej.

### Dostępne style splash

| Splash | Fabryka | Opis |
|--------|---------|------|
| Ripple | `ButtonSplashStyle.ripple()` | Standardowy ripple Material od punktu dotyku |
| Highlight | `ButtonSplashStyle.highlight()` | Subtelne podświetlenie bez animacji ripple |
| Glow | `ButtonSplashStyle.glow()` | Miękka poświata wychodząca od punktu dotyku |
| Ink | `ButtonSplashStyle.ink()` | Szybki splash ink, szybszy i bardziej responsywny |
| None | `ButtonSplashStyle.none()` | Brak efektu splash |
| Custom | `ButtonSplashStyle.custom()` | Pełna kontrola nad fabryką splash |

### Przykład

``` dart
class Button {
  static Widget outlined({
    required String text,
    VoidCallback? onPressed,
    ...
  }) {
    return OutlinedButton(
      text: text,
      onPressed: onPressed,
      splashStyle: ButtonSplashStyle.ripple(),
      animationStyle: ButtonAnimationStyle.clickable(),
    );
  }
}
```

Możesz dostosować kolory i przezroczystość splash:

``` dart
ButtonSplashStyle.ripple(
  splashColor: Colors.blue,
  highlightColor: Colors.blue,
  splashOpacity: 0.2,
  highlightOpacity: 0.1,
)
```

<div id="loading-styles"></div>

## Style ładowania

Wskaźnik ładowania wyświetlany podczas operacji asynchronicznych jest kontrolowany przez `LoadingStyle`. Możesz go ustawić dla każdego typu przycisku w pliku przycisków.

### Skeletonizer (domyślny)

Wyświetla efekt shimmer skeleton na przycisku:

``` dart
loadingStyle: LoadingStyle.skeletonizer()
```

### Normal

Wyświetla widget ładowania (domyślnie loader aplikacji):

``` dart
loadingStyle: LoadingStyle.normal(
  child: Text("Please wait..."),
)
```

### None

Utrzymuje przycisk widocznym, ale wyłącza interakcję podczas ładowania:

``` dart
loadingStyle: LoadingStyle.none()
```

<div id="form-submission"></div>

## Wysyłanie formularzy

Wszystkie przyciski obsługują parametr `submitForm`, który łączy przycisk z `NyForm`. Po tapnięciu przycisk zwaliduje formularz i wywoła handler sukcesu z danymi formularza.

``` dart
Button.primary(
  text: "Submit",
  submitForm: (LoginForm(), (data) {
    // data contains the validated form fields
    print(data);
  }),
  onFailure: (error) {
    // Handle validation errors
    print(error);
  },
)
```

Parametr `submitForm` przyjmuje rekord z dwiema wartościami:
1. Instancja `NyFormData` (lub nazwa formularza jako `String`)
2. Callback, który otrzymuje zwalidowane dane

Domyślnie `showToastError` ma wartość `true`, co wyświetla powiadomienie toast przy błędzie walidacji formularza. Ustaw na `false`, aby obsługiwać błędy po cichu:

``` dart
Button.primary(
  text: "Login",
  submitForm: (LoginForm(), (data) async {
    await api<AuthApiService>((request) => request.login(data));
  }),
  showToastError: false,
  onFailure: (error) {
    // Custom error handling
  },
)
```

Gdy callback `submitForm` zwraca `Future`, przycisk automatycznie wyświetli stan ładowania do momentu zakończenia operacji asynchronicznej.

<div id="customizing-buttons"></div>

## Dostosowywanie przycisków

Wszystkie domyślne ustawienia przycisków są zdefiniowane w projekcie w `lib/resources/widgets/buttons/buttons.dart`. Każdy typ przycisku ma odpowiadającą klasę widgetu w `lib/resources/widgets/buttons/partials/`.

### Zmiana domyślnych stylów

Aby zmodyfikować domyślny wygląd przycisku, edytuj klasę `Button`:

``` dart
class Button {
  static Widget primary({
    required String text,
    VoidCallback? onPressed,
    Function(dynamic error)? onFailure,
    bool showToastError = true,
    double? width,
  }) {
    return PrimaryButton(
      text: text,
      onPressed: onPressed,
      submitForm: submitForm,
      onFailure: onFailure,
      showToastError: showToastError,
      loadingStyle: LoadingStyle.skeletonizer(),
      width: width,
      height: 52.0,
      animationStyle: ButtonAnimationStyle.bounce(),
      splashStyle: ButtonSplashStyle.glow(),
    );
  }
}
```

### Dostosowywanie widgetu przycisku

Aby zmienić wygląd wizualny typu przycisku, edytuj odpowiadający widget w `lib/resources/widgets/buttons/partials/`. Na przykład, aby zmienić promień obramowania lub cień przycisku primary:

``` dart
// lib/resources/widgets/buttons/partials/primary_button_widget.dart

class PrimaryButton extends StatefulAppButton {
  ...

  @override
  Widget buildButton(BuildContext context) {
    final theme = Theme.of(context);
    final bgColor = backgroundColor ?? theme.colorScheme.primary;
    final fgColor = contentColor ?? theme.colorScheme.onPrimary;

    return Container(
      width: width ?? double.infinity,
      height: height,
      decoration: BoxDecoration(
        color: bgColor,
        borderRadius: BorderRadius.circular(8), // Change the radius
      ),
      child: Center(
        child: Text(
          text,
          style: TextStyle(
            color: fgColor,
            fontSize: 16,
            fontWeight: FontWeight.w600,
          ),
        ),
      ),
    );
  }
}
```

### Tworzenie nowego typu przycisku

Aby dodać nowy typ przycisku:

1. Utwórz nowy plik widgetu w `lib/resources/widgets/buttons/partials/` rozszerzający `StatefulAppButton`.
2. Zaimplementuj metodę `buildButton`.
3. Dodaj metodę statyczną w klasie `Button`.

``` dart
// lib/resources/widgets/buttons/partials/danger_button_widget.dart

class DangerButton extends StatefulAppButton {
  DangerButton({
    required super.text,
    super.onPressed,
    super.submitForm,
    super.onFailure,
    super.showToastError,
    super.loadingStyle,
    super.width,
    super.height,
    super.animationStyle,
    super.splashStyle,
  });

  @override
  Widget buildButton(BuildContext context) {
    return Container(
      width: width ?? double.infinity,
      height: height,
      decoration: BoxDecoration(
        color: Colors.red,
        borderRadius: BorderRadius.circular(14),
      ),
      child: Center(
        child: Text(
          text,
          style: TextStyle(
            color: Colors.white,
            fontSize: 16,
            fontWeight: FontWeight.w600,
          ),
        ),
      ),
    );
  }
}
```

Następnie zarejestruj go w klasie `Button`:

``` dart
class Button {
  ...

  static Widget danger({
    required String text,
    VoidCallback? onPressed,
    Function(dynamic error)? onFailure,
    bool showToastError = true,
    double? width,
  }) {
    return DangerButton(
      text: text,
      onPressed: onPressed,
      submitForm: submitForm,
      onFailure: onFailure,
      showToastError: showToastError,
      loadingStyle: LoadingStyle.skeletonizer(),
      width: width,
      height: 52.0,
      animationStyle: ButtonAnimationStyle.shake(),
    );
  }
}
```

<div id="parameters"></div>

## Referencja parametrów

### Wspólne parametry (wszystkie typy przycisków)

| Parametr | Typ | Domyślnie | Opis |
|----------|-----|-----------|------|
| `text` | `String` | wymagany | Tekst etykiety przycisku |
| `onPressed` | `VoidCallback?` | `null` | Callback po tapnięciu przycisku. Zwróć `Future` dla automatycznego stanu ładowania |
| `submitForm` | `(dynamic, Function(dynamic))?` | `null` | Rekord wysyłania formularza (instancja formularza, callback sukcesu) |
| `onFailure` | `Function(dynamic)?` | `null` | Wywoływany przy błędzie walidacji formularza |
| `showToastError` | `bool` | `true` | Wyświetl powiadomienie toast przy błędzie walidacji formularza |
| `width` | `double?` | `null` | Szerokość przycisku (domyślnie pełna szerokość) |

### Parametry specyficzne dla typu

#### Button.outlined

| Parametr | Typ | Domyślnie | Opis |
|----------|-----|-----------|------|
| `borderColor` | `Color?` | Kolor obramowania motywu | Kolor obramowania |
| `textColor` | `Color?` | Kolor primary motywu | Kolor tekstu |

#### Button.textOnly

| Parametr | Typ | Domyślnie | Opis |
|----------|-----|-----------|------|
| `textColor` | `Color?` | Kolor primary motywu | Kolor tekstu |

#### Button.icon

| Parametr | Typ | Domyślnie | Opis |
|----------|-----|-----------|------|
| `icon` | `Widget` | wymagany | Widget ikony do wyświetlenia |
| `color` | `Color?` | Kolor primary motywu | Kolor tła |

#### Button.gradient

| Parametr | Typ | Domyślnie | Opis |
|----------|-----|-----------|------|
| `gradientColors` | `List<Color>?` | Kolory primary i tertiary | Punkty kolorów gradientu |

#### Button.rounded

| Parametr | Typ | Domyślnie | Opis |
|----------|-----|-----------|------|
| `backgroundColor` | `Color?` | Kolor primary container motywu | Kolor tła |
| `borderRadius` | `BorderRadius?` | Kształt pigułki (wysokość / 2) | Promień zaokrąglenia |

#### Button.transparency

| Parametr | Typ | Domyślnie | Opis |
|----------|-----|-----------|------|
| `color` | `Color?` | Adaptacyjny do motywu | Kolor tekstu |

### Parametry ButtonAnimationStyle

| Parametr | Typ | Domyślnie | Opis |
|----------|-----|-----------|------|
| `duration` | `Duration` | Zależy od typu | Czas trwania animacji |
| `curve` | `Curve` | Zależy od typu | Krzywa animacji |
| `enableHapticFeedback` | `bool` | Zależy od typu | Wyzwól informację zwrotną haptyczną po naciśnięciu |
| `translateY` | `double` | `4.0` | Clickable: pionowa odległość naciśnięcia |
| `shadowOffset` | `double` | `4.0` | Clickable: głębokość cienia |
| `scaleMin` | `double` | `0.92` | Bounce: minimalna skala po naciśnięciu |
| `pulseScale` | `double` | `1.05` | Pulse: maksymalna skala podczas pulsacji |
| `squeezeX` | `double` | `0.95` | Squeeze: kompresja pozioma |
| `squeezeY` | `double` | `1.05` | Squeeze: rozszerzenie pionowe |
| `jellyStrength` | `double` | `0.15` | Jelly: intensywność drgań |
| `shineColor` | `Color` | `Colors.white` | Shine: kolor podświetlenia |
| `shineWidth` | `double` | `0.3` | Shine: szerokość paska blasku |
| `rippleScale` | `double` | `2.0` | Ripple: skala rozszerzenia |
| `morphRadius` | `double` | `24.0` | Morph: docelowy promień obramowania |
| `shakeOffset` | `double` | `8.0` | Shake: przesunięcie poziome |
| `shakeCount` | `int` | `3` | Shake: liczba oscylacji |

### Parametry ButtonSplashStyle

| Parametr | Typ | Domyślnie | Opis |
|----------|-----|-----------|------|
| `splashColor` | `Color?` | Kolor powierzchni motywu | Kolor efektu splash |
| `highlightColor` | `Color?` | Kolor powierzchni motywu | Kolor efektu podświetlenia |
| `splashOpacity` | `double` | `0.12` | Przezroczystość splash |
| `highlightOpacity` | `double` | `0.06` | Przezroczystość podświetlenia |
| `borderRadius` | `BorderRadius?` | `null` | Promień obcinania splash |
