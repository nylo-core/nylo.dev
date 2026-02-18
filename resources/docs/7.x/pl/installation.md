# Installation

---

<a name="section-1"></a>
- [Instalacja](#install "Instalacja")
- [Uruchamianie projektu](#running-the-project "Uruchamianie projektu")
- [Metro CLI](#metro-cli "Metro CLI")


<div id="install"></div>

## Instalacja

### 1. Zainstaluj nylo_installer globalnie

``` bash
dart pub global activate nylo_installer
```

To instaluje narzędzie CLI {{ config('app.name') }} globalnie w Twoim systemie.

### 2. Utwórz nowy projekt

``` bash
nylo new my_app
```

To polecenie klonuje szablon {{ config('app.name') }}, konfiguruje projekt z nazwą Twojej aplikacji i automatycznie instaluje wszystkie zależności.

### 3. Skonfiguruj alias Metro CLI

``` bash
cd my_app
nylo init
```

To konfiguruje polecenie `metro` dla Twojego projektu, pozwalając na używanie poleceń Metro CLI bez pełnej składni `dart run`.

Po instalacji otrzymasz kompletną strukturę projektu Flutter z:
- Wstępnie skonfigurowanym routingiem i nawigacją
- Gotowym szkieletem serwisu API
- Konfiguracją motywów i lokalizacji
- Metro CLI do generowania kodu


<div id="running-the-project"></div>

## Uruchamianie projektu

Projekty {{ config('app.name') }} działają jak każda standardowa aplikacja Flutter.

### Za pomocą terminala

``` bash
flutter run
```

### Za pomocą IDE

- **Android Studio**: <a href="https://docs.flutter.dev/tools/android-studio#running-and-debugging" target="_BLANK">Uruchamianie i debugowanie</a>
- **VS Code**: <a href="https://docs.flutter.dev/tools/vs-code#run-app-without-breakpoints" target="_BLANK">Uruchom aplikację bez punktów przerwania</a>

Jeśli budowanie zakończy się sukcesem, aplikacja wyświetli domyślny ekran powitalny {{ config('app.name') }}.


<div id="metro-cli"></div>

## Metro CLI

{{ config('app.name') }} zawiera narzędzie CLI o nazwie **Metro** do generowania plików projektu.

### Uruchamianie Metro

``` bash
metro
```

To wyświetla menu Metro:

``` bash
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
  make:theme
  make:route_guard
  make:config
  make:interceptor
  make:command
  make:env
```

### Referencja poleceń Metro

| Polecenie | Opis |
|-----------|------|
| `make:page` | Utwórz nową stronę |
| `make:stateful_widget` | Utwórz widget stanowy |
| `make:stateless_widget` | Utwórz widget bezstanowy |
| `make:state_managed_widget` | Utwórz widget z zarządzaniem stanem |
| `make:navigation_hub` | Utwórz hub nawigacji (dolna nawigacja) |
| `make:journey_widget` | Utwórz widget podróży dla huba nawigacji |
| `make:bottom_sheet_modal` | Utwórz modal dolnego arkusza |
| `make:button` | Utwórz niestandardowy widget przycisku |
| `make:form` | Utwórz formularz z walidacją |
| `make:model` | Utwórz klasę modelu |
| `make:provider` | Utwórz providera |
| `make:api_service` | Utwórz serwis API |
| `make:controller` | Utwórz kontroler |
| `make:event` | Utwórz zdarzenie |
| `make:theme` | Utwórz motyw |
| `make:route_guard` | Utwórz strażnika trasy |
| `make:config` | Utwórz plik konfiguracyjny |
| `make:interceptor` | Utwórz interceptor sieciowy |
| `make:command` | Utwórz niestandardowe polecenie Metro |
| `make:env` | Wygeneruj konfigurację środowiska z .env |

### Przykłady użycia

``` bash
# Create a new page
metro make:page settings_page

# Create a model
metro make:model User

# Create an API service
metro make:api_service user_api_service
```
