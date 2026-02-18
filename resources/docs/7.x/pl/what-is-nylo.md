# What is {{ config('app.name') }}?

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie")
- Tworzenie aplikacji
    - [Nowy we Flutter?](#new-to-flutter "Nowy we Flutter?")
    - [Harmonogram utrzymania i wydawania](#maintenance-and-release-schedule "Harmonogram utrzymania i wydawania")
- Podziekowania
    - [Zaleznosci frameworka](#framework-dependencies "Zaleznosci frameworka")
    - [Kontrybutorzy](#contributors "Kontrybutorzy")


<div id="introduction"></div>

## Wprowadzenie

{{ config('app.name') }} to mikro-framework dla Flutter zaprojektowany, aby uproscic tworzenie aplikacji. Zapewnia ustrukturyzowany szablon z wstepnie skonfigurowanymi niezbednymi elementami, dzieki czemu mozesz skupic sie na budowaniu funkcji aplikacji zamiast konfigurowac infrastrukture.

{{ config('app.name') }} zawiera od razu:

- **Routing** - Proste, deklaratywne zarzadzanie trasami ze strazami i glebokimi linkami
- **Siec** - Serwisy API z Dio, przechwytywaczami i morfowaniem odpowiedzi
- **Zarzadzanie stanem** - Reaktywny stan z NyState i globalnymi aktualizacjami stanu
- **Lokalizacja** - Obsluga wielu jezykow z plikami tlumaczen JSON
- **Motywy** - Tryb jasny/ciemny z przelaczaniem motywow
- **Lokalna pamiec** - Bezpieczna pamiec z Backpack i NyStorage
- **Formularze** - Obsluga formularzy z walidacja i typami pol
- **Powiadomienia push** - Obsluga lokalnych i zdalnych powiadomien
- **Narzedzie CLI (Metro)** - Generowanie stron, kontrolerow, modeli i wiecej

<div id="new-to-flutter"></div>

## Nowy we Flutter?

Jesli jestes nowy we Flutter, zacznij od oficjalnych zasobow:

- <a href="https://flutter.dev" target="_BLANK">Dokumentacja Flutter</a> - Kompleksowe przewodniki i referencja API
- <a href="https://www.youtube.com/c/flutterdev" target="_BLANK">Kanal YouTube Flutter</a> - Samouczki i aktualizacje
- <a href="https://docs.flutter.dev/cookbook" target="_BLANK">Flutter Cookbook</a> - Praktyczne przepisy na typowe zadania

Gdy poczujesz sie komfortowo z podstawami Flutter, {{ config('app.name') }} bedzie intuicyjne, poniewaz opiera sie na standardowych wzorcach Flutter.


<div id="maintenance-and-release-schedule"></div>

## Harmonogram utrzymania i wydawania

{{ config('app.name') }} stosuje <a href="https://semver.org" target="_BLANK">wersjonowanie semantyczne</a>:

- **Wydania glowne** (7.x → 8.x) - Raz w roku dla zmian niekompatybilnych wstecz
- **Wydania pomniejsze** (7.0 → 7.1) - Nowe funkcje, kompatybilne wstecz
- **Wydania poprawkowe** (7.0.0 → 7.0.1) - Poprawki bledow i drobne ulepszenia

Poprawki bledow i latki bezpieczenstwa sa obslugiwane niezwlocznie przez repozytoria GitHub.


<div id="framework-dependencies"></div>

## Zaleznosci frameworka

{{ config('app.name') }} v7 jest zbudowany na nastepujacych pakietach open source:

### Glowne zaleznosci

| Pakiet | Przeznaczenie |
|---------|---------|
| <a href="https://pub.dev/packages/dio" target="_BLANK">dio</a> | Klient HTTP do zapytan API |
| <a href="https://pub.dev/packages/flutter_secure_storage" target="_BLANK">flutter_secure_storage</a> | Bezpieczna pamiec lokalna |
| <a href="https://pub.dev/packages/intl" target="_BLANK">intl</a> | Internacjonalizacja i formatowanie |
| <a href="https://pub.dev/packages/rxdart" target="_BLANK">rxdart</a> | Rozszerzenia reaktywne dla strumieni |
| <a href="https://pub.dev/packages/equatable" target="_BLANK">equatable</a> | Rownosc wartosci dla obiektow |

### UI i widgety

| Pakiet | Przeznaczenie |
|---------|---------|
| <a href="https://pub.dev/packages/skeletonizer" target="_BLANK">skeletonizer</a> | Efekty ladowania szkieletowego |
| <a href="https://pub.dev/packages/flutter_styled_toast" target="_BLANK">flutter_styled_toast</a> | Powiadomienia toast |
| <a href="https://pub.dev/packages/pull_to_refresh_flutter3" target="_BLANK">pull_to_refresh_flutter3</a> | Funkcjonalnosc pull-to-refresh |
| <a href="https://pub.dev/packages/flutter_staggered_grid_view" target="_BLANK">flutter_staggered_grid_view</a> | Uklady siatki przestawnej |
| <a href="https://pub.dev/packages/date_field" target="_BLANK">date_field</a> | Pola wyboru daty |

### Powiadomienia i lacznosc

| Pakiet | Przeznaczenie |
|---------|---------|
| <a href="https://pub.dev/packages/flutter_local_notifications" target="_BLANK">flutter_local_notifications</a> | Lokalne powiadomienia push |
| <a href="https://pub.dev/packages/connectivity_plus" target="_BLANK">connectivity_plus</a> | Status polaczenia sieciowego |
| <a href="https://pub.dev/packages/app_badge_plus" target="_BLANK">app_badge_plus</a> | Znaczniki ikon aplikacji |

### Narzedzia

| Pakiet | Przeznaczenie |
|---------|---------|
| <a href="https://pub.dev/packages/url_launcher" target="_BLANK">url_launcher</a> | Otwieranie adresow URL i aplikacji |
| <a href="https://pub.dev/packages/recase" target="_BLANK">recase</a> | Konwersja wielkosci liter ciagow |
| <a href="https://pub.dev/packages/uuid" target="_BLANK">uuid</a> | Generowanie UUID |
| <a href="https://pub.dev/packages/path_provider" target="_BLANK">path_provider</a> | Sciezki systemu plikow |
| <a href="https://pub.dev/packages/mask_text_input_formatter" target="_BLANK">mask_text_input_formatter</a> | Maskowanie danych wejsciowych |


<div id="contributors"></div>

## Kontrybutorzy

Dziekujemy wszystkim, ktorzy przyczynili sie do rozwoju {{ config('app.name') }}! Jesli wnosiles wklad, skontaktuj sie przez <a href="mailto:support@nylo.dev">support@nylo.dev</a>, aby zostac dodanym tutaj.

- <a href="https://github.com/agordn52" target="_BLANK">Anthony Gordon</a> (Tworca)
- <a href="https://github.com/Abdulrasheed1729" target="_BLANK">Abdulrasheed1729</a>
- <a href="https://github.com/Rashid-Khabeer" target="_BLANK">Rashid-Khabeer</a>
- <a href="https://github.com/lpdevit" target="_BLANK">lpdevit</a>
- <a href="https://github.com/youssefKadaouiAbbassi" target="_BLANK">youssefKadaouiAbbassi</a>
- <a href="https://github.com/jeremyhalin" target="_BLANK">jeremyhalin</a>
- <a href="https://github.com/abdulawalarif" target="_BLANK">abdulawalarif</a>
- <a href="https://github.com/lepresk" target="_BLANK">lepresk</a>
- <a href="https://github.com/joshua1996" target="_BLANK">joshua1996</a>
- <a href="https://github.com/stensonb" target="_BLANK">stensonb</a>
- <a href="https://github.com/ruwiss" target="_BLANK">ruwiss</a>
- <a href="https://github.com/rytisder" target="_BLANK">rytisder</a>
- <a href="https://github.com/israelins85" target="_BLANK">israelins85</a>
- <a href="https://github.com/voytech-net" target="_BLANK">voytech-net</a>
- <a href="https://github.com/sadobass" target="_BLANK">sadobass</a>