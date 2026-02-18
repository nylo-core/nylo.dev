# App Icons

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie")
- [Generowanie ikon aplikacji](#generating-app-icons "Generowanie ikon aplikacji")
- [Dodawanie ikony aplikacji](#adding-your-app-icon "Dodawanie ikony aplikacji")
- [Wymagania dotyczace ikon](#app-icon-requirements "Wymagania dotyczace ikon aplikacji")
- [Konfiguracja](#configuration "Konfiguracja")
- [Licznik plakietek](#badge-count "Licznik plakietek")

<div id="introduction"></div>

## Wprowadzenie

{{ config('app.name') }} v7 uzywa <a href="https://pub.dev/packages/flutter_launcher_icons" target="_BLANK">flutter_launcher_icons</a> do generowania ikon aplikacji dla iOS i Android z jednego obrazu zrodlowego.

Ikona aplikacji powinna byc umieszczona w katalogu `assets/app_icon/` o rozmiarze **1024x1024 pikseli**.

<div id="generating-app-icons"></div>

## Generowanie ikon aplikacji

Uruchom nastepujace polecenie, aby wygenerowac ikony dla wszystkich platform:

``` bash
dart run flutter_launcher_icons
```

Polecenie odczytuje ikone zrodlowa z `assets/app_icon/` i generuje:
- Ikony iOS w `ios/Runner/Assets.xcassets/AppIcon.appiconset/`
- Ikony Android w `android/app/src/main/res/mipmap-*/`

<div id="adding-your-app-icon"></div>

## Dodawanie ikony aplikacji

1. Utworz ikone jako plik **PNG 1024x1024**
2. Umiesc go w `assets/app_icon/` (np. `assets/app_icon/icon.png`)
3. Zaktualizuj `image_path` w pliku `pubspec.yaml` w razie potrzeby:

``` yaml
flutter_launcher_icons:
  android: true
  ios: true
  image_path: "assets/app_icon/icon.png"
```

4. Uruchom polecenie generowania ikon

<div id="app-icon-requirements"></div>

## Wymagania dotyczace ikon aplikacji

| Atrybut | Wartosc |
|-----------|-------|
| Format | PNG |
| Rozmiar | 1024x1024 pikseli |
| Warstwy | Splaszczone bez przezroczystosci |

### Nazewnictwo plikow

Uzywaj prostych nazw plikow bez znakow specjalnych:
- `app_icon.png`
- `icon.png`

### Wytyczne platformowe

Szczegolowe wymagania znajdziesz w oficjalnych wytycznych platformowych:
- <a href="https://developer.apple.com/design/human-interface-guidelines/app-icons" target="_BLANK">Apple Human Interface Guidelines - App Icons</a>
- <a href="https://developer.android.com/distribute/google-play/resources/icon-design-specifications" target="_BLANK">Google Play Icon Design Specifications</a>

<div id="configuration"></div>

## Konfiguracja

Dostosuj generowanie ikon w pliku `pubspec.yaml`:

``` yaml
flutter_launcher_icons:
  android: true
  ios: true
  image_path: "assets/app_icon/icon.png"

  # Optional: Use different icons per platform
  # image_path_android: "assets/app_icon/android_icon.png"
  # image_path_ios: "assets/app_icon/ios_icon.png"

  # Optional: Adaptive icons for Android
  # adaptive_icon_background: "#ffffff"
  # adaptive_icon_foreground: "assets/app_icon/foreground.png"

  # Optional: Remove alpha channel for iOS
  # remove_alpha_ios: true
```

Wszystkie dostepne opcje znajdziesz w <a href="https://pub.dev/packages/flutter_launcher_icons" target="_BLANK">dokumentacji flutter_launcher_icons</a>.

<div id="badge-count"></div>

## Licznik plakietek

{{ config('app.name') }} udostepnia funkcje pomocnicze do zarzadzania licznikiem plakietek aplikacji (liczba wyswietlana na ikonie aplikacji):

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Set badge count to 5
await setBadgeNumber(5);

// Clear the badge count
await clearBadgeNumber();
```

### Wsparcie platformowe

Liczniki plakietek sa obslugiwane na:
- **iOS**: Natywne wsparcie
- **Android**: Wymaga wsparcia launchera (wiekszosc launcherow to obsluguje)
- **Web**: Nieobslugiwane

### Przypadki uzycia

Typowe scenariusze dla licznikow plakietek:
- Nieprzeczytane powiadomienia
- Oczekujace wiadomosci
- Elementy w koszyku
- Niezakonczone zadania

``` dart
// Example: Update badge when new messages arrive
void onNewMessage() async {
  int unreadCount = await MessageService.getUnreadCount();
  await setBadgeNumber(unreadCount);
}

// Example: Clear badge when user views messages
void onMessagesViewed() async {
  await clearBadgeNumber();
}
```
