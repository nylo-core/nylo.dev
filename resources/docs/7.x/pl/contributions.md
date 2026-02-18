# Contributing to {{ config('app.name') }}

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie do wspoltworzenia")
- [Pierwsze kroki](#getting-started "Pierwsze kroki we wspoltworzeniu")
- [Srodowisko deweloperskie](#development-environment "Konfiguracja srodowiska deweloperskiego")
- [Wytyczne dla deweloperow](#development-guidelines "Wytyczne dla deweloperow")
- [Przesylanie zmian](#submitting-changes "Jak przesylac zmiany")
- [Zglaszanie problemow](#reporting-issues "Jak zglaszac problemy")


<div id="introduction"></div>

## Wprowadzenie

Dziekujemy za rozważenie wspoltworzenia {{ config('app.name') }}!

Ten przewodnik pomoze Ci zrozumiec, jak wspoltworzycmikro-framework. Niezaleznie od tego, czy naprawiasz bledy, dodajesz funkcje, czy ulepszasz dokumentacje, Twoj wklad jest cenny dla spolecznosci {{ config('app.name') }}.

{{ config('app.name') }} jest podzielony na trzy repozytoria:

| Repozytorium | Cel |
|--------------|-----|
| <a href="https://github.com/nylo-core/nylo" target="_BLANK">nylo</a> | Aplikacja szablonowa |
| <a href="https://github.com/nylo-core/framework" target="_BLANK">framework</a> | Glowne klasy frameworka (nylo_framework) |
| <a href="https://github.com/nylo-core/support" target="_BLANK">support</a> | Biblioteka wspierajaca z widgetami, helperami, narzedziami (nylo_support) |

<div id="getting-started"></div>

## Pierwsze kroki

### Forkowanie repozytoriow

Sforkuj repozytoria, do ktorych chcesz sie przyczynic:

- <a href="https://github.com/nylo-core/nylo/fork" target="_BLANK">Fork Nylo Boilerplate</a>
- <a href="https://github.com/nylo-core/framework/fork" target="_BLANK">Fork Framework</a>
- <a href="https://github.com/nylo-core/support/fork" target="_BLANK">Fork Support</a>

### Klonowanie forkow

``` bash
git clone https://github.com/YOUR-USERNAME/nylo
git clone https://github.com/YOUR-USERNAME/framework
git clone https://github.com/YOUR-USERNAME/support
```

<div id="development-environment"></div>

## Srodowisko deweloperskie

### Wymagania

Upewnij sie, ze masz zainstalowane:

| Wymaganie | Minimalna wersja |
|-----------|-----------------|
| Flutter | 3.24.0 lub wyzsza |
| Dart SDK | 3.10.7 lub wyzsza |

### Linkowanie lokalnych pakietow

Otworz szablon Nylo w edytorze i dodaj nadpisania zaleznosci, aby uzywac lokalnych repozytoriow framework i support:

**pubspec.yaml**
``` yaml
dependency_overrides:
  nylo_framework:
    path: ../framework  # Path to your local framework repository
  nylo_support:
    path: ../support    # Path to your local support repository
```

Uruchom `flutter pub get`, aby zainstalowac zaleznosci.

Teraz zmiany wprowadzone w repozytoriach framework lub support beda odzwierciedlone w szablonie Nylo.

### Testowanie zmian

Uruchom aplikacje szablonowa, aby przetestowac zmiany:

``` bash
flutter run
```

W przypadku zmian w widgetach lub helperach rozważ dodanie testow w odpowiednim repozytorium.

<div id="development-guidelines"></div>

## Wytyczne dla deweloperow

### Styl kodu

- Postepuj zgodnie z oficjalnym <a href="https://dart.dev/guides/language/effective-dart/style" target="_BLANK">przewodnikiem stylu Dart</a>
- Uzywaj znaczacych nazw zmiennych i funkcji
- Pisz jasne komentarze dla zlozonej logiki
- Dodawaj dokumentacje dla publicznych API
- Utrzymuj kod modularnym i latwym w utrzymaniu

### Dokumentacja

Podczas dodawania nowych funkcji:

- Dodawaj komentarze dartdoc do publicznych klas i metod
- Aktualizuj odpowiednie pliki dokumentacji w razie potrzeby
- Dolaczaj przyklady kodu w dokumentacji

### Testowanie

Przed przeslaniem zmian:

- Testuj na urzadzeniach/symulatorach iOS i Android
- Weryfikuj wsteczna kompatybilnosc tam, gdzie to mozliwe
- Jasno dokumentuj wszelkie przelomowe zmiany
- Uruchamiaj istniejace testy, aby upewnic sie, ze nic nie jest zepsute

<div id="submitting-changes"></div>

## Przesylanie zmian

### Najpierw omow

W przypadku nowych funkcji najlepiej najpierw omowic ze spolecznoscia:

- <a href="https://github.com/nylo-core/nylo/discussions" target="_BLANK">GitHub Discussions</a>

### Utworz galaz

``` bash
git checkout -b feature/your-feature-name
```

Uzywaj opisowych nazw galezi:
- `feature/collection-view-pagination`
- `fix/storage-null-handling`
- `docs/update-networking-guide`

### Zatwierdz zmiany

``` bash
git add .
git commit -m "Add: Your feature description"
```

Uzywaj jasnych wiadomosci commitow:
- `Add: CollectionView grid mode support`
- `Fix: NyStorage returning null on first read`
- `Update: Improve router documentation`

### Wypchnij i utworz Pull Request

``` bash
git push origin feature/your-feature-name
```

Nastepnie utworz pull request na GitHubie.

### Wytyczne dla Pull Requestow

- Podaj jasny opis zmian
- Odwoluj sie do powiazanych problemow
- Dolaczaj zrzuty ekranu lub przyklady kodu, jesli dotyczy
- Upewnij sie, ze PR dotyczy tylko jednego zagadnienia
- Utrzymuj zmiany skoncentrowane i atomowe

<div id="reporting-issues"></div>

## Zglaszanie problemow

### Przed zgloszeniem

1. Sprawdz, czy problem juz istnieje na GitHubie
2. Upewnij sie, ze uzywasz najnowszej wersji
3. Sprobuj odtworzyc problem w czystym projekcie

### Gdzie zglaszac

Zglaszaj problemy w odpowiednim repozytorium:

- **Problemy z szablonem**: <a href="https://github.com/nylo-core/nylo/issues" target="_BLANK">nylo/issues</a>
- **Problemy z frameworkiem**: <a href="https://github.com/nylo-core/framework/issues" target="_BLANK">framework/issues</a>
- **Problemy z biblioteka support**: <a href="https://github.com/nylo-core/support/issues" target="_BLANK">support/issues</a>

### Szablon zgloszenia

Podaj szczegolowe informacje:

``` markdown
### Description
Brief description of the issue

### Steps to Reproduce
1. Step one
2. Step two
3. Step three

### Expected Behavior
What should happen

### Actual Behavior
What actually happens

### Environment
- Flutter: 3.24.x
- Dart SDK: 3.10.x
- nylo_framework: ^7.0.0
- OS: macOS/Windows/Linux
- Device: iPhone 15/Pixel 8 (if applicable)

### Code Example
```dart
// Minimal code to reproduce the issue
```
```

### Informacje o wersji

``` bash
# Flutter and Dart versions
flutter --version

# Check your pubspec.yaml for Nylo version
# nylo_framework: ^7.0.0
```
