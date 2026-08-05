# Installation

---

<a name="section-1"></a>
- [Instalacja](#install "Instalacja")
- [Uruchamianie projektu](#running-the-project "Uruchamianie projektu")
- [Metro CLI](#metro-cli "Metro CLI")

<div id="install"></div>

Trzy polecenia wystarczą, aby przejść od pustego folderu do działającej aplikacji Flutter z gotowym routingiem, siecią, motywami i generowaniem kodu.

<x-doc-strip label="Zanim zaczniesz" items="Zainstalowany Flutter SDK, Dart 3" linkText="Pełne wymagania" linkHref="/pl/docs/{{ $version }}/requirements" />

## Instalacja

Uruchom te polecenia po kolei. Każde z nich można bezpiecznie uruchomić ponownie.

<x-doc-steps>
<x-doc-step number="1" title="Zainstaluj Nylo CLI globalnie">
To instaluje narzędzie CLI {{ config('app.name') }} globalnie w Twoim systemie.

``` bash
dart pub global activate nylo_installer
```
</x-doc-step>

<x-doc-step number="2" title="Utwórz nowy projekt">
To polecenie klonuje szablon {{ config('app.name') }}, konfiguruje projekt z nazwą Twojej aplikacji i automatycznie instaluje wszystkie zależności.

``` bash
nylo new my_app
```
</x-doc-step>

<x-doc-step number="3" title="Skonfiguruj alias Metro">
To konfiguruje polecenie `metro` dla Twojego projektu, pozwalając na używanie poleceń Metro CLI bez pełnej składni `dart run`.

``` bash
cd my_app
nylo init
```
</x-doc-step>
</x-doc-steps>

<x-doc-panel title="Co otrzymujesz" items="Wstępnie skonfigurowany routing i nawigacja
Gotowy szkielet serwisu API
Konfiguracja motywów i lokalizacji
Metro CLI do generowania kodu" />


<div id="running-the-project"></div>

## Uruchamianie projektu

Projekty {{ config('app.name') }} działają jak każda standardowa aplikacja Flutter.

<x-doc-tabs tabs="Terminal, Android Studio, VS Code">
<x-doc-tab label="Terminal">

``` bash
flutter run
```

Jeśli budowanie zakończy się sukcesem, aplikacja wyświetli domyślny ekran powitalny {{ config('app.name') }}.
</x-doc-tab>

<x-doc-tab label="Android Studio">
Otwórz folder projektu, wybierz urządzenie z selektora celu, a następnie naciśnij **Run**.

<a href="https://docs.flutter.dev/tools/android-studio#running-and-debugging" target="_BLANK">Dokumentacja Flutter: uruchamianie i debugowanie ↗</a>
</x-doc-tab>

<x-doc-tab label="VS Code">
Otwórz folder projektu, a następnie uruchom **Debug: Start Without Debugging** z palety poleceń.

<a href="https://docs.flutter.dev/tools/vs-code#run-app-without-breakpoints" target="_BLANK">Dokumentacja Flutter: uruchom bez punktów przerwania ↗</a>
</x-doc-tab>
</x-doc-tabs>


<div id="metro-cli"></div>

## Metro CLI

Metro generuje pliki projektu. Uruchom je bez argumentów, aby wyświetlić menu, lub wywołaj polecenie bezpośrednio.

``` plaintext
$ metro

Metro - Nylo's Companion to Build Flutter apps by Anthony Gordon

Usage:
    command [options] [arguments]

Options
    -h

All commands:

[Widget Commands]
  make:page
  make:stateful_widget
  make:stateless_widget
  make:state_managed_widget
  make:navigation_hub
  make:journey_widget
  make:bottom_sheet_modal
  make:button
  make:form

[Helper Commands]
  make:model
  make:provider
  make:api_service
  make:controller
  make:event
  make:route_guard
  make:config
  make:interceptor
  make:command
  make:env
```

### Referencja poleceń

Każde polecenie przyjmuje nazwę, np. `metro make:page settings_page`

<x-doc-commands title="Polecenia widgetów" rows="
metro make:page | Utwórz nową stronę
metro make:stateful_widget | Utwórz widget stanowy
metro make:stateless_widget | Utwórz widget bezstanowy
metro make:state_managed_widget | Utwórz widget z zarządzaniem stanem
metro make:navigation_hub | Utwórz hub nawigacji (dolna nawigacja)
metro make:journey_widget | Utwórz widget podróży dla huba nawigacji
metro make:bottom_sheet_modal | Utwórz modal dolnego arkusza
metro make:button | Utwórz niestandardowy widget przycisku
metro make:form | Utwórz formularz z walidacją
" />

<x-doc-commands title="Polecenia pomocnicze" rows="
metro make:model | Utwórz klasę modelu
metro make:provider | Utwórz providera
metro make:api_service | Utwórz serwis API
metro make:controller | Utwórz kontroler
metro make:event | Utwórz zdarzenie
metro make:route_guard | Utwórz strażnika trasy
metro make:config | Utwórz plik konfiguracyjny
metro make:interceptor | Utwórz interceptor sieciowy
metro make:command | Utwórz niestandardowe polecenie Metro
metro make:env | Wygeneruj konfigurację środowiska z .env
" />

### Przykłady użycia

``` bash
# Create a new page
metro make:page settings_page

# Create a model
metro make:model User

# Create an API service
metro make:api_service user_api_service
```
