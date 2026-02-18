# Metro CLI tool

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie")
- [Instalacja](#install "Instalacja aliasu Metro dla {{ config('app.name') }}")
- Komendy Make
  - [Tworzenie kontrolera](#make-controller "Tworzenie nowego kontrolera")
  - [Tworzenie modelu](#make-model "Tworzenie nowego modelu")
  - [Tworzenie strony](#make-page "Tworzenie nowej strony")
  - [Tworzenie stateless widget](#make-stateless-widget "Tworzenie nowego stateless widget")
  - [Tworzenie stateful widget](#make-stateful-widget "Tworzenie nowego stateful widget")
  - [Tworzenie journey widget](#make-journey-widget "Tworzenie nowego journey widget")
  - [Tworzenie API Service](#make-api-service "Tworzenie nowego API Service")
  - [Tworzenie zdarzenia](#make-event "Tworzenie nowego zdarzenia")
  - [Tworzenie providera](#make-provider "Tworzenie nowego providera")
  - [Tworzenie motywu](#make-theme "Tworzenie nowego motywu")
  - [Tworzenie formularza](#make-forms "Tworzenie nowego formularza")
  - [Tworzenie strażnika trasy](#make-route-guard "Tworzenie nowego strażnika trasy")
  - [Tworzenie pliku konfiguracji](#make-config-file "Tworzenie nowego pliku konfiguracji")
  - [Tworzenie komendy](#make-command "Tworzenie nowej komendy")
  - [Tworzenie widgetu zarządzanego stanem](#make-state-managed-widget "Tworzenie nowego widgetu zarządzanego stanem")
  - [Tworzenie Navigation Hub](#make-navigation-hub "Tworzenie nowego Navigation Hub")
  - [Tworzenie Bottom Sheet Modal](#make-bottom-sheet-modal "Tworzenie nowego bottom sheet modal")
  - [Tworzenie przycisku](#make-button "Tworzenie nowego przycisku")
  - [Tworzenie interceptora](#make-interceptor "Tworzenie nowego interceptora")
  - [Tworzenie pliku env](#make-env-file "Tworzenie nowego pliku env")
  - [Generowanie klucza](#make-key "Generowanie APP_KEY")
- Ikony aplikacji
  - [Budowanie ikon aplikacji](#build-app-icons "Budowanie ikon aplikacji z Metro")
- Komendy niestandardowe
  - [Tworzenie komend niestandardowych](#creating-custom-commands "Tworzenie komend niestandardowych")
  - [Uruchamianie komend niestandardowych](#running-custom-commands "Uruchamianie komend niestandardowych")
  - [Dodawanie opcji do komend](#adding-options-to-custom-commands "Dodawanie opcji do komend niestandardowych")
  - [Dodawanie flag do komend](#adding-flags-to-custom-commands "Dodawanie flag do komend niestandardowych")
  - [Metody pomocnicze](#custom-command-helper-methods "Metody pomocnicze komend niestandardowych")
  - [Interaktywne metody wprowadzania danych](#interactive-input-methods "Interaktywne metody wprowadzania danych")
  - [Formatowanie wyjścia](#output-formatting "Formatowanie wyjścia")
  - [Pomocniki systemu plików](#file-system-helpers "Pomocniki systemu plików")
  - [Pomocniki JSON i YAML](#json-yaml-helpers "Pomocniki JSON i YAML")
  - [Pomocniki konwersji wielkości liter](#case-conversion-helpers "Pomocniki konwersji wielkości liter")
  - [Pomocniki ścieżek projektu](#project-path-helpers "Pomocniki ścieżek projektu")
  - [Pomocniki platformy](#platform-helpers "Pomocniki platformy")
  - [Komendy Dart i Flutter](#dart-flutter-commands "Komendy Dart i Flutter")
  - [Manipulacja plikami Dart](#dart-file-manipulation "Manipulacja plikami Dart")
  - [Pomocniki katalogów](#directory-helpers "Pomocniki katalogów")
  - [Pomocniki walidacji](#validation-helpers "Pomocniki walidacji")
  - [Szkieletowanie plików](#file-scaffolding "Szkieletowanie plików")
  - [Runner zadań](#task-runner "Runner zadań")
  - [Wyjście tabelaryczne](#table-output "Wyjście tabelaryczne")
  - [Pasek postępu](#progress-bar "Pasek postępu")


<div id="introduction"></div>

## Wprowadzenie

Metro to narzędzie CLI, które działa pod maską frameworka {{ config('app.name') }}.
Dostarcza wiele przydatnych narzędzi przyspieszających rozwój aplikacji.

<div id="install"></div>

## Instalacja

Kiedy tworzysz nowy projekt Nylo za pomocą `nylo init`, komenda `metro` jest automatycznie konfigurowana dla Twojego terminala. Możesz od razu zacząć jej używać w dowolnym projekcie Nylo.

Uruchom `metro` z katalogu projektu, aby zobaczyć wszystkie dostępne komendy:

``` bash
metro
```

Powinieneś zobaczyć wynik podobny do poniższego.

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
  make:key
```

<div id="make-controller"></div>

## Tworzenie kontrolera

- [Tworzenie nowego kontrolera](#making-a-new-controller "Tworzenie nowego kontrolera z Metro")
- [Wymuszenie tworzenia kontrolera](#forcefully-make-a-controller "Wymuszenie tworzenia nowego kontrolera z Metro")
<div id="making-a-new-controller"></div>

### Tworzenie nowego kontrolera

Możesz utworzyć nowy kontroler, uruchamiając poniższe polecenie w terminalu.

``` bash
metro make:controller profile_controller
```

Spowoduje to utworzenie nowego kontrolera, jeśli nie istnieje, w katalogu `lib/app/controllers/`.

<div id="forcefully-make-a-controller"></div>

### Wymuszenie tworzenia kontrolera

**Argumenty:**

Użycie flagi `--force` lub `-f` nadpisze istniejący kontroler, jeśli już istnieje.

``` bash
metro make:controller profile_controller --force
```

<div id="make-model"></div>

## Tworzenie modelu

- [Tworzenie nowego modelu](#making-a-new-model "Tworzenie nowego modelu z Metro")
- [Tworzenie modelu z JSON](#make-model-from-json "Tworzenie nowego modelu z JSON z Metro")
- [Wymuszenie tworzenia modelu](#forcefully-make-a-model "Wymuszenie tworzenia nowego modelu z Metro")
<div id="making-a-new-model"></div>

### Tworzenie nowego modelu

Możesz utworzyć nowy model, uruchamiając poniższe polecenie w terminalu.

``` bash
metro make:model product
```

Nowo utworzony model zostanie umieszczony w `lib/app/models/`.

<div id="make-model-from-json"></div>

### Tworzenie modelu z JSON

**Argumenty:**

Użycie flagi `--json` lub `-j` utworzy nowy model z payloadu JSON.

``` bash
metro make:model product --json
```

Następnie możesz wkleić swój JSON do terminala, a model zostanie wygenerowany automatycznie.

<div id="forcefully-make-a-model"></div>

### Wymuszenie tworzenia modelu

**Argumenty:**

Użycie flagi `--force` lub `-f` nadpisze istniejący model, jeśli już istnieje.

``` bash
metro make:model product --force
```

<div id="make-page"></div>

## Tworzenie strony

- [Tworzenie nowej strony](#making-a-new-page "Tworzenie nowej strony z Metro")
- [Tworzenie strony z kontrolerem](#create-a-page-with-a-controller "Tworzenie nowej strony z kontrolerem z Metro")
- [Tworzenie strony autoryzacji](#create-an-auth-page "Tworzenie nowej strony autoryzacji z Metro")
- [Tworzenie strony początkowej](#create-an-initial-page "Tworzenie nowej strony początkowej z Metro")
- [Wymuszenie tworzenia strony](#forcefully-make-a-page "Wymuszenie tworzenia nowej strony z Metro")

<div id="making-a-new-page"></div>

### Tworzenie nowej strony

Możesz utworzyć nową stronę, uruchamiając poniższe polecenie w terminalu.

``` bash
metro make:page product_page
```

Spowoduje to utworzenie nowej strony, jeśli nie istnieje, w katalogu `lib/resources/pages/`.

<div id="create-a-page-with-a-controller"></div>

### Tworzenie strony z kontrolerem

Możesz utworzyć nową stronę z kontrolerem, uruchamiając poniższe polecenie w terminalu.

**Argumenty:**

Użycie flagi `--controller` lub `-c` utworzy nową stronę z kontrolerem.

``` bash
metro make:page product_page -c
```

<div id="create-an-auth-page"></div>

### Tworzenie strony autoryzacji

Możesz utworzyć nową stronę autoryzacji, uruchamiając poniższe polecenie w terminalu.

**Argumenty:**

Użycie flagi `--auth` lub `-a` utworzy nową stronę autoryzacji.

``` bash
metro make:page login_page -a
```

<div id="create-an-initial-page"></div>

### Tworzenie strony początkowej

Możesz utworzyć nową stronę początkową, uruchamiając poniższe polecenie w terminalu.

**Argumenty:**

Użycie flagi `--initial` lub `-i` utworzy nową stronę początkową.

``` bash
metro make:page home_page -i
```

<div id="forcefully-make-a-page"></div>

### Wymuszenie tworzenia strony

**Argumenty:**

Użycie flagi `--force` lub `-f` nadpisze istniejącą stronę, jeśli już istnieje.

``` bash
metro make:page product_page --force
```

<div id="make-stateless-widget"></div>

## Tworzenie stateless widget

- [Tworzenie nowego stateless widget](#making-a-new-stateless-widget "Tworzenie nowego stateless widget z Metro")
- [Wymuszenie tworzenia stateless widget](#forcefully-make-a-stateless-widget "Wymuszenie tworzenia nowego stateless widget z Metro")
<div id="making-a-new-stateless-widget"></div>

### Tworzenie nowego stateless widget

Możesz utworzyć nowy stateless widget, uruchamiając poniższe polecenie w terminalu.

``` bash
metro make:stateless_widget product_rating_widget
```

Powyższe polecenie utworzy nowy widget, jeśli nie istnieje, w katalogu `lib/resources/widgets/`.

<div id="forcefully-make-a-stateless-widget"></div>

### Wymuszenie tworzenia stateless widget

**Argumenty:**

Użycie flagi `--force` lub `-f` nadpisze istniejący widget, jeśli już istnieje.

``` bash
metro make:stateless_widget product_rating_widget --force
```

<div id="make-stateful-widget"></div>

## Tworzenie stateful widget

- [Tworzenie nowego stateful widget](#making-a-new-stateful-widget "Tworzenie nowego stateful widget z Metro")
- [Wymuszenie tworzenia stateful widget](#forcefully-make-a-stateful-widget "Wymuszenie tworzenia nowego stateful widget z Metro")

<div id="making-a-new-stateful-widget"></div>

### Tworzenie nowego stateful widget

Możesz utworzyć nowy stateful widget, uruchamiając poniższe polecenie w terminalu.

``` bash
metro make:stateful_widget product_rating_widget
```

Powyższe polecenie utworzy nowy widget, jeśli nie istnieje, w katalogu `lib/resources/widgets/`.

<div id="forcefully-make-a-stateful-widget"></div>

### Wymuszenie tworzenia stateful widget

**Argumenty:**

Użycie flagi `--force` lub `-f` nadpisze istniejący widget, jeśli już istnieje.

``` bash
metro make:stateful_widget product_rating_widget --force
```

<div id="make-journey-widget"></div>

## Tworzenie journey widget

- [Tworzenie nowego journey widget](#making-a-new-journey-widget "Tworzenie nowego journey widget z Metro")
- [Wymuszenie tworzenia journey widget](#forcefully-make-a-journey-widget "Wymuszenie tworzenia nowego journey widget z Metro")

<div id="making-a-new-journey-widget"></div>

### Tworzenie nowego journey widget

Możesz utworzyć nowy journey widget, uruchamiając poniższe polecenie w terminalu.

``` bash
metro make:journey_widget product_journey --parent="[NAVIGATION_HUB]"

# Pełny przykład, jeśli masz BaseNavigationHub
metro make:journey_widget welcome,user_dob,user_photos --parent="Base"
```

Powyższe polecenie utworzy nowy widget, jeśli nie istnieje, w katalogu `lib/resources/widgets/`.

Argument `--parent` służy do określenia widgetu nadrzędnego, do którego zostanie dodany nowy journey widget.

Przykład

``` bash
metro make:navigation_hub onboarding
```

Następnie dodaj nowe journey widgety.
``` bash
metro make:journey_widget welcome,user_dob,user_photos --parent="onboarding"
```

<div id="forcefully-make-a-journey-widget"></div>

### Wymuszenie tworzenia journey widget
**Argumenty:**
Użycie flagi `--force` lub `-f` nadpisze istniejący widget, jeśli już istnieje.

``` bash
metro make:journey_widget product_journey --force --parent="[YOUR_NAVIGATION_HUB]"
```

<div id="make-api-service"></div>

## Tworzenie API Service

- [Tworzenie nowego API Service](#making-a-new-api-service "Tworzenie nowego API Service z Metro")
- [Tworzenie nowego API Service z modelem](#making-a-new-api-service-with-a-model "Tworzenie nowego API Service z modelem z Metro")
- [Tworzenie API Service z Postman](#make-api-service-using-postman "Tworzenie API services z Postman")
- [Wymuszenie tworzenia API Service](#forcefully-make-an-api-service "Wymuszenie tworzenia nowego API Service z Metro")

<div id="making-a-new-api-service"></div>

### Tworzenie nowego API Service

Możesz utworzyć nowy API service, uruchamiając poniższe polecenie w terminalu.

``` bash
metro make:api_service user_api_service
```

Nowo utworzony API service zostanie umieszczony w `lib/app/networking/`.

<div id="making-a-new-api-service-with-a-model"></div>

### Tworzenie nowego API Service z modelem

Możesz utworzyć nowy API service z modelem, uruchamiając poniższe polecenie w terminalu.

**Argumenty:**

Użycie opcji `--model` lub `-m` utworzy nowy API service z modelem.

``` bash
metro make:api_service user --model="User"
```

Nowo utworzony API service zostanie umieszczony w `lib/app/networking/`.

### Wymuszenie tworzenia API Service

**Argumenty:**

Użycie flagi `--force` lub `-f` nadpisze istniejący API Service, jeśli już istnieje.

``` bash
metro make:api_service user --force
```

<div id="make-event"></div>

## Tworzenie zdarzenia

- [Tworzenie nowego zdarzenia](#making-a-new-event "Tworzenie nowego zdarzenia z Metro")
- [Wymuszenie tworzenia zdarzenia](#forcefully-make-an-event "Wymuszenie tworzenia nowego zdarzenia z Metro")

<div id="making-a-new-event"></div>

### Tworzenie nowego zdarzenia

Możesz utworzyć nowe zdarzenie, uruchamiając poniższe polecenie w terminalu.

``` bash
metro make:event login_event
```

Spowoduje to utworzenie nowego zdarzenia w `lib/app/events`.

<div id="forcefully-make-an-event"></div>

### Wymuszenie tworzenia zdarzenia

**Argumenty:**

Użycie flagi `--force` lub `-f` nadpisze istniejące zdarzenie, jeśli już istnieje.

``` bash
metro make:event login_event --force
```

<div id="make-provider"></div>

## Tworzenie providera

- [Tworzenie nowego providera](#making-a-new-provider "Tworzenie nowego providera z Metro")
- [Wymuszenie tworzenia providera](#forcefully-make-a-provider "Wymuszenie tworzenia nowego providera z Metro")

<div id="making-a-new-provider"></div>

### Tworzenie nowego providera

Utwórz nowych providerów w swojej aplikacji za pomocą poniższej komendy.

``` bash
metro make:provider firebase_provider
```

Nowo utworzony provider zostanie umieszczony w `lib/app/providers/`.

<div id="forcefully-make-a-provider"></div>

### Wymuszenie tworzenia providera

**Argumenty:**

Użycie flagi `--force` lub `-f` nadpisze istniejącego providera, jeśli już istnieje.

``` bash
metro make:provider firebase_provider --force
```

<div id="make-theme"></div>

## Tworzenie motywu

- [Tworzenie nowego motywu](#making-a-new-theme "Tworzenie nowego motywu z Metro")
- [Wymuszenie tworzenia motywu](#forcefully-make-a-theme "Wymuszenie tworzenia nowego motywu z Metro")

<div id="making-a-new-theme"></div>

### Tworzenie nowego motywu

Możesz tworzyć motywy, uruchamiając poniższe polecenie w terminalu.

``` bash
metro make:theme bright_theme
```

Spowoduje to utworzenie nowego motywu w `lib/resources/themes/`.

<div id="forcefully-make-a-theme"></div>

### Wymuszenie tworzenia motywu

**Argumenty:**

Użycie flagi `--force` lub `-f` nadpisze istniejący motyw, jeśli już istnieje.

``` bash
metro make:theme bright_theme --force
```

<div id="make-forms"></div>

## Tworzenie formularzy

- [Tworzenie nowego formularza](#making-a-new-form "Tworzenie nowego formularza z Metro")
- [Wymuszenie tworzenia formularza](#forcefully-make-a-form "Wymuszenie tworzenia nowego formularza z Metro")

<div id="making-a-new-form"></div>

### Tworzenie nowego formularza

Możesz utworzyć nowy formularz, uruchamiając poniższe polecenie w terminalu.

``` bash
metro make:form car_advert_form
```

Spowoduje to utworzenie nowego formularza w `lib/app/forms`.

<div id="forcefully-make-a-form"></div>

### Wymuszenie tworzenia formularza

**Argumenty:**

Użycie flagi `--force` lub `-f` nadpisze istniejący formularz, jeśli już istnieje.

``` bash
metro make:form login_form --force
```

<div id="make-route-guard"></div>

## Tworzenie strażnika trasy

- [Tworzenie nowego strażnika trasy](#making-a-new-route-guard "Tworzenie nowego strażnika trasy z Metro")
- [Wymuszenie tworzenia strażnika trasy](#forcefully-make-a-route-guard "Wymuszenie tworzenia nowego strażnika trasy z Metro")

<div id="making-a-new-route-guard"></div>

### Tworzenie nowego strażnika trasy

Możesz utworzyć strażnika trasy, uruchamiając poniższe polecenie w terminalu.

``` bash
metro make:route_guard premium_content
```

Spowoduje to utworzenie nowego strażnika trasy w `lib/app/route_guards`.

<div id="forcefully-make-a-route-guard"></div>

### Wymuszenie tworzenia strażnika trasy

**Argumenty:**

Użycie flagi `--force` lub `-f` nadpisze istniejącego strażnika trasy, jeśli już istnieje.

``` bash
metro make:route_guard premium_content --force
```

<div id="make-config-file"></div>

## Tworzenie pliku konfiguracji

- [Tworzenie nowego pliku konfiguracji](#making-a-new-config-file "Tworzenie nowego pliku konfiguracji z Metro")
- [Wymuszenie tworzenia pliku konfiguracji](#forcefully-make-a-config-file "Wymuszenie tworzenia nowego pliku konfiguracji z Metro")

<div id="making-a-new-config-file"></div>

### Tworzenie nowego pliku konfiguracji

Możesz utworzyć nowy plik konfiguracji, uruchamiając poniższe polecenie w terminalu.

``` bash
metro make:config shopping_settings
```

Spowoduje to utworzenie nowego pliku konfiguracji w `lib/app/config`.

<div id="forcefully-make-a-config-file"></div>

### Wymuszenie tworzenia pliku konfiguracji

**Argumenty:**

Użycie flagi `--force` lub `-f` nadpisze istniejący plik konfiguracji, jeśli już istnieje.

``` bash
metro make:config app_config --force
```


<div id="make-command"></div>

## Tworzenie komendy

- [Tworzenie nowej komendy](#making-a-new-command "Tworzenie nowej komendy z Metro")
- [Wymuszenie tworzenia komendy](#forcefully-make-a-command "Wymuszenie tworzenia nowej komendy z Metro")

<div id="making-a-new-command"></div>

### Tworzenie nowej komendy

Możesz utworzyć nową komendę, uruchamiając poniższe polecenie w terminalu.

``` bash
metro make:command my_command
```

Spowoduje to utworzenie nowej komendy w `lib/app/commands`.

<div id="forcefully-make-a-command"></div>

### Wymuszenie tworzenia komendy

**Argumenty:**
Użycie flagi `--force` lub `-f` nadpisze istniejącą komendę, jeśli już istnieje.

``` bash
metro make:command my_command --force
```


<div id="make-state-managed-widget"></div>

## Tworzenie widgetu zarządzanego stanem

Możesz utworzyć nowy widget zarządzany stanem, uruchamiając poniższe polecenie w terminalu.

``` bash
metro make:state_managed_widget product_rating_widget
```

Powyższe polecenie utworzy nowy widget w `lib/resources/widgets/`.

Użycie flagi `--force` lub `-f` nadpisze istniejący widget, jeśli już istnieje.

``` bash
metro make:state_managed_widget product_rating_widget --force
```

<div id="make-navigation-hub"></div>

## Tworzenie Navigation Hub

Możesz utworzyć nowy Navigation Hub, uruchamiając poniższe polecenie w terminalu.

``` bash
metro make:navigation_hub dashboard
```

Spowoduje to utworzenie nowego Navigation Hub w `lib/resources/pages/` i automatyczne dodanie trasy.

**Argumenty:**

| Flaga | Skrót | Opis |
|------|-------|-------------|
| `--auth` | `-a` | Utwórz jako stronę autoryzacji |
| `--initial` | `-i` | Utwórz jako stronę początkową |
| `--force` | `-f` | Nadpisz, jeśli istnieje |

``` bash
# Utwórz jako stronę początkową
metro make:navigation_hub dashboard --initial
```

<div id="make-bottom-sheet-modal"></div>

## Tworzenie Bottom Sheet Modal

Możesz utworzyć nowy bottom sheet modal, uruchamiając poniższe polecenie w terminalu.

``` bash
metro make:bottom_sheet_modal payment_options
```

Spowoduje to utworzenie nowego bottom sheet modal w `lib/resources/widgets/`.

Użycie flagi `--force` lub `-f` nadpisze istniejący modal, jeśli już istnieje.

``` bash
metro make:bottom_sheet_modal payment_options --force
```

<div id="make-button"></div>

## Tworzenie przycisku

Możesz utworzyć nowy widget przycisku, uruchamiając poniższe polecenie w terminalu.

``` bash
metro make:button checkout_button
```

Spowoduje to utworzenie nowego widgetu przycisku w `lib/resources/widgets/`.

Użycie flagi `--force` lub `-f` nadpisze istniejący przycisk, jeśli już istnieje.

``` bash
metro make:button checkout_button --force
```

<div id="make-interceptor"></div>

## Tworzenie interceptora

Możesz utworzyć nowy interceptor sieciowy, uruchamiając poniższe polecenie w terminalu.

``` bash
metro make:interceptor auth_interceptor
```

Spowoduje to utworzenie nowego interceptora w `lib/app/networking/dio/interceptors/`.

Użycie flagi `--force` lub `-f` nadpisze istniejący interceptor, jeśli już istnieje.

``` bash
metro make:interceptor auth_interceptor --force
```

<div id="make-env-file"></div>

## Tworzenie pliku Env

Możesz utworzyć nowy plik środowiskowy, uruchamiając poniższe polecenie w terminalu.

``` bash
metro make:env .env.staging
```

Spowoduje to utworzenie nowego pliku `.env` w głównym katalogu projektu.

<div id="make-key"></div>

## Generowanie klucza

Wygeneruj bezpieczny `APP_KEY` do szyfrowania środowiska. Jest on używany do szyfrowanych plików `.env` w v7.

``` bash
metro make:key
```

**Argumenty:**

| Flaga / Opcja | Skrót | Opis |
|---------------|-------|-------------|
| `--force` | `-f` | Nadpisz istniejący APP_KEY |
| `--file` | `-e` | Docelowy plik .env (domyślnie: `.env`) |

``` bash
# Wygeneruj klucz i nadpisz istniejący
metro make:key --force

# Wygeneruj klucz dla konkretnego pliku env
metro make:key --file=.env.production
```

<div id="build-app-icons"></div>

## Budowanie ikon aplikacji

Możesz wygenerować wszystkie ikony aplikacji dla IOS i Android, uruchamiając poniższą komendę.

``` bash
dart run flutter_launcher_icons:main
```

Wykorzystuje konfigurację <b>flutter_icons</b> z pliku `pubspec.yaml`.

<div id="custom-commands"></div>

## Komendy niestandardowe

Komendy niestandardowe pozwalają rozszerzyć CLI Nylo o własne komendy specyficzne dla projektu. Ta funkcja umożliwia automatyzację powtarzalnych zadań, implementację przepływów wdrożeniowych lub dodanie dowolnej niestandardowej funkcjonalności bezpośrednio do narzędzi wiersza poleceń projektu.

- [Tworzenie komend niestandardowych](#creating-custom-commands)
- [Uruchamianie komend niestandardowych](#running-custom-commands)
- [Dodawanie opcji do komend](#adding-options-to-custom-commands)
- [Dodawanie flag do komend](#adding-flags-to-custom-commands)
- [Metody pomocnicze](#custom-command-helper-methods)

> **Uwaga:** Obecnie nie można importować nylo_framework.dart w komendach niestandardowych, proszę używać zamiast tego ny_cli.dart.

<div id="creating-custom-commands"></div>

## Tworzenie komend niestandardowych

Aby utworzyć nową komendę niestandardową, możesz użyć funkcji `make:command`:

```bash
metro make:command current_time
```

Możesz określić kategorię komendy za pomocą opcji `--category`:

```bash
# Określ kategorię
metro make:command current_time --category="project"
```

Spowoduje to utworzenie nowego pliku komendy w `lib/app/commands/current_time.dart` o następującej strukturze:

``` dart
import 'package:nylo_framework/metro/ny_cli.dart';

void main(arguments) => _CurrentTimeCommand(arguments).run();

/// Current Time Command
///
/// Usage:
///   metro app:current_time
class _CurrentTimeCommand extends NyCustomCommand {
  _CurrentTimeCommand(super.arguments);

  @override
  CommandBuilder builder(CommandBuilder command) {
    command.addOption('format', defaultValue: 'HH:mm:ss');
    return command;
  }

  @override
  Future<void> handle(CommandResult result) async {
      final format = result.getString("format");

      // Get the current time
      final now = DateTime.now();
      final DateFormat dateFormat = DateFormat(format);

      // Format the current time
      final formattedTime = dateFormat.format(now);
      info("The current time is " + formattedTime);
  }
}
```

Komenda zostanie automatycznie zarejestrowana w pliku `lib/app/commands/commands.json`, który zawiera listę wszystkich zarejestrowanych komend:

```json
[
  {
    "name": "install_firebase",
    "category": "project",
    "script": "install_firebase.dart"
  },
  {
    "name": "current_time",
    "category": "app",
    "script": "current_time.dart"
  }
]
```

<div id="running-custom-commands"></div>

## Uruchamianie komend niestandardowych

Po utworzeniu możesz uruchomić swoją komendę niestandardową za pomocą skrótu Metro lub pełnej komendy Dart:

```bash
metro app:current_time
```

Gdy uruchomisz `metro` bez argumentów, zobaczysz swoje komendy niestandardowe wymienione w menu w sekcji "Custom Commands":

```
[Custom Commands]
  app:app_icon
  app:clear_pub
  project:install_firebase
  project:deploy
```

Aby wyświetlić informacje pomocy dla komendy, użyj flagi `--help` lub `-h`:

```bash
metro project:install_firebase --help
```

<div id="adding-options-to-custom-commands"></div>

## Dodawanie opcji do komend

Opcje pozwalają komendzie przyjmować dodatkowe dane wejściowe od użytkowników. Możesz dodać opcje do komendy w metodzie `builder`:

```dart
@override
CommandBuilder builder(CommandBuilder command) {

  // Dodaj opcję z wartością domyślną
  command.addOption(
    'environment',     // nazwa opcji
    abbr: 'e',         // skrót
    help: 'Target deployment environment', // tekst pomocy
    defaultValue: 'development',  // wartość domyślna
    allowed: ['development', 'staging', 'production'] // dozwolone wartości
  );

  return command;
}
```

Następnie uzyskaj dostęp do wartości opcji w metodzie `handle` komendy:

```dart
@override
Future<void> handle(CommandResult result) async {
  final environment = result.getString('environment');
  info('Deploying to $environment environment...');

  // Implementacja komendy...
}
```

Przykład użycia:

```bash
metro project:deploy --environment=production
# lub używając skrótu
metro project:deploy -e production
```

<div id="adding-flags-to-custom-commands"></div>

## Dodawanie flag do komend

Flagi to opcje logiczne, które można włączać lub wyłączać. Dodaj flagi do komendy za pomocą metody `addFlag`:

```dart
@override
CommandBuilder builder(CommandBuilder command) {

  command.addFlag(
    'verbose',       // nazwa flagi
    abbr: 'v',       // skrót
    help: 'Enable verbose output', // tekst pomocy
    defaultValue: false  // domyślnie wyłączone
  );

  return command;
}
```

Następnie sprawdź stan flagi w metodzie `handle` komendy:

```dart
@override
Future<void> handle(CommandResult result) async {
  final verbose = result.getBool('verbose');

  if (verbose) {
    info('Verbose mode enabled');
    // Dodatkowe logowanie...
  }

  // Implementacja komendy...
}
```

Przykład użycia:

```bash
metro project:deploy --verbose
# lub używając skrótu
metro project:deploy -v
```

<div id="custom-command-helper-methods"></div>

## Metody pomocnicze

Klasa bazowa `NyCustomCommand` dostarcza kilka metod pomocniczych do wspomagania typowych zadań:

#### Wyświetlanie komunikatów

Oto kilka metod do wyświetlania komunikatów w różnych kolorach:

| |  |
|-------------|-------------|
| [`info`](#custom-command-helper-formatting)      | Wyświetl komunikat informacyjny w niebieskim tekście |
| [`error`](#custom-command-helper-formatting)     | Wyświetl komunikat błędu w czerwonym tekście |
| [`success`](#custom-command-helper-formatting)   | Wyświetl komunikat sukcesu w zielonym tekście |
| [`warning`](#custom-command-helper-formatting)   | Wyświetl komunikat ostrzeżenia w żółtym tekście |

#### Uruchamianie procesów

Uruchamiaj procesy i wyświetlaj ich wyniki w konsoli:

| |  |
|-------------|-------------|
| [`addPackage`](#custom-command-helper-add-package) | Dodaj pakiet do `pubspec.yaml` |
| [`addPackages`](#custom-command-helper-add-packages) | Dodaj wiele pakietów do `pubspec.yaml` |
| [`runProcess`](#custom-command-helper-run-process) | Uruchom zewnętrzny proces i wyświetl wyniki w konsoli |
| [`prompt`](#custom-command-helper-prompt)    | Zbierz dane wejściowe od użytkownika jako tekst |
| [`confirm`](#custom-command-helper-confirm)   | Zadaj pytanie tak/nie i zwróć wynik logiczny |
| [`select`](#custom-command-helper-select)    | Przedstaw listę opcji i pozwól użytkownikowi wybrać jedną |
| [`multiSelect`](#custom-command-helper-multi-select) | Pozwól użytkownikowi wybrać wiele opcji z listy |

#### Żądania sieciowe

Wykonywanie żądań sieciowych przez konsolę:

| |  |
|-------------|-------------|
| [`api`](#custom-command-helper-multi-select) | Wykonaj wywołanie API za pomocą klienta API Nylo |


#### Spinner ładowania

Wyświetl spinner ładowania podczas wykonywania funkcji:

| |  |
|-------------|-------------|
| [`withSpinner`](#using-with-spinner) | Pokaż spinner ładowania podczas wykonywania funkcji |
| [`createSpinner`](#manual-spinner-control) | Utwórz instancję spinnera do ręcznej kontroli |

#### Pomocniki komend niestandardowych

Możesz również użyć następujących metod pomocniczych do zarządzania argumentami komendy:

| |  |
|-------------|-------------|
| [`getString`](#custom-command-helper-get-string) | Pobierz wartość tekstową z argumentów komendy |
| [`getBool`](#custom-command-helper-get-bool)   | Pobierz wartość logiczną z argumentów komendy |
| [`getInt`](#custom-command-helper-get-int)    | Pobierz wartość całkowitą z argumentów komendy |
| [`sleep`](#custom-command-helper-sleep) | Wstrzymaj wykonanie na określony czas |


### Uruchamianie zewnętrznych procesów

```dart
// Uruchom proces z wyświetlaniem wyników w konsoli
await runProcess('flutter build web --release');

// Uruchom proces cicho
await runProcess('flutter pub get', silent: true);

// Uruchom proces w określonym katalogu
await runProcess('git pull', workingDirectory: './my-project');
```

### Zarządzanie pakietami

<div id="custom-command-helper-add-package"></div>
<div id="custom-command-helper-add-packages"></div>

```dart
// Dodaj pakiet do pubspec.yaml
addPackage('firebase_core', version: '^2.4.0');

// Dodaj pakiet deweloperski do pubspec.yaml
addPackage('build_runner', dev: true);

// Dodaj wiele pakietów naraz
addPackages(['firebase_auth', 'firebase_storage', 'quickalert']);
```

<div id="custom-command-helper-formatting"></div>

### Formatowanie wyjścia

```dart
// Wyświetl komunikaty statusu z kolorami
info('Processing files...');    // Niebieski tekst
error('Operation failed');      // Czerwony tekst
success('Deployment complete'); // Zielony tekst
warning('Outdated package');    // Żółty tekst
```

<div id="interactive-input-methods"></div>

## Interaktywne metody wprowadzania danych

Klasa bazowa `NyCustomCommand` dostarcza kilka metod do zbierania danych wejściowych od użytkownika w terminalu. Te metody ułatwiają tworzenie interaktywnych interfejsów wiersza poleceń dla komend niestandardowych.

<div id="custom-command-helper-prompt"></div>

### Wprowadzanie tekstu

```dart
String prompt(String question, {String defaultValue = ''})
```

Wyświetla pytanie użytkownikowi i zbiera jego odpowiedź tekstową.

**Parametry:**
- `question`: Pytanie lub monit do wyświetlenia
- `defaultValue`: Opcjonalna wartość domyślna, gdy użytkownik naciśnie Enter

**Zwraca:** Dane wejściowe użytkownika jako ciąg znaków lub wartość domyślną, jeśli nie podano danych

**Przykład:**
```dart
final name = prompt('What is your project name?', defaultValue: 'my_app');
final description = prompt('Enter a project description:');
```

<div id="custom-command-helper-confirm"></div>

### Potwierdzenie

```dart
bool confirm(String question, {bool defaultValue = false})
```

Zadaje użytkownikowi pytanie tak/nie i zwraca wynik logiczny.

**Parametry:**
- `question`: Pytanie tak/nie do zadania
- `defaultValue`: Domyślna odpowiedź (true dla tak, false dla nie)

**Zwraca:** `true` jeśli użytkownik odpowiedział tak, `false` jeśli odpowiedział nie

**Przykład:**
```dart
if (confirm('Would you like to continue?', defaultValue: true)) {
  // Użytkownik potwierdził lub nacisnął Enter (akceptując wartość domyślną)
  await runProcess('flutter pub get');
} else {
  // Użytkownik odmówił
  info('Operation canceled');
}
```

<div id="custom-command-helper-select"></div>

### Wybór pojedynczy

```dart
String select(String question, List<String> options, {String? defaultOption})
```

Przedstawia listę opcji i pozwala użytkownikowi wybrać jedną.

**Parametry:**
- `question`: Monit wyboru
- `options`: Lista dostępnych opcji
- `defaultOption`: Opcjonalny domyślny wybór

**Zwraca:** Wybraną opcję jako ciąg znaków

**Przykład:**
```dart
final environment = select(
  'Select deployment environment:',
  ['development', 'staging', 'production'],
  defaultOption: 'development'
);

info('Deploying to $environment environment...');
```

<div id="custom-command-helper-multi-select"></div>

### Wybór wielokrotny

```dart
List<String> multiSelect(String question, List<String> options)
```

Pozwala użytkownikowi wybrać wiele opcji z listy.

**Parametry:**
- `question`: Monit wyboru
- `options`: Lista dostępnych opcji

**Zwraca:** Listę wybranych opcji

**Przykład:**
```dart
final packages = multiSelect(
  'Select packages to install:',
  ['firebase_auth', 'dio', 'provider', 'shared_preferences', 'path_provider']
);

if (packages.isNotEmpty) {
  info('Installing ${packages.length} packages...');
  addPackages(packages);
  await runProcess('flutter pub get');
}
```

<div id="custom-command-helper-api"></div>

## Metoda pomocnicza API

Metoda pomocnicza `api` upraszcza wykonywanie żądań sieciowych z komend niestandardowych.

```dart
Future<T?> api<T>(Future<T?> Function(ApiService) request) async
```

## Podstawowe przykłady użycia

### Żądanie GET

```dart
// Pobierz dane
final userData = await api((request) =>
  request.get('https://api.example.com/users/1')
);
```

### Żądanie POST

```dart
// Utwórz zasób
final result = await api((request) =>
  request.post(
    'https://api.example.com/items',
    data: {'name': 'New Item', 'price': 19.99}
  )
);
```

### Żądanie PUT

```dart
// Zaktualizuj zasób
final updateResult = await api((request) =>
  request.put(
    'https://api.example.com/items/42',
    data: {'name': 'Updated Item', 'price': 29.99}
  )
);
```

### Żądanie DELETE

```dart
// Usuń zasób
final deleteResult = await api((request) => request.delete('https://api.example.com/items/42'));
```

### Żądanie PATCH

```dart
// Częściowo zaktualizuj zasób
final patchResult = await api((request) => request.patch(
    'https://api.example.com/items/42',
    data: {'price': 24.99}
  )
);
```

### Z parametrami zapytania

```dart
// Dodaj parametry zapytania
final searchResults = await api((request) => request.get(
    'https://api.example.com/search',
    queryParameters: {'q': 'keyword', 'limit': 10}
  )
);
```

### Ze spinnerem

```dart
// Użycie ze spinnerem dla lepszego UX
final data = await withSpinner(
  task: () async {
    final data = await api((request) => request.get('https://api.example.com/config'));
    // Przetwórz dane
  },
  message: 'Loading configuration',
);
```


<div id="using-spinners"></div>

## Funkcjonalność spinnera

Spinnery zapewniają wizualną informację zwrotną podczas długotrwałych operacji w komendach niestandardowych. Wyświetlają animowany wskaźnik wraz z komunikatem podczas wykonywania zadań asynchronicznych, poprawiając doświadczenie użytkownika poprzez pokazywanie postępu i statusu.

- [Użycie ze spinnerem](#using-with-spinner)
- [Ręczna kontrola spinnera](#manual-spinner-control)
- [Przykłady](#spinner-examples)

<div id="using-with-spinner"></div>

## Użycie ze spinnerem

Metoda `withSpinner` pozwala opakować zadanie asynchroniczne animacją spinnera, która automatycznie uruchamia się na początku zadania i zatrzymuje po jego zakończeniu lub niepowodzeniu:

```dart
Future<T> withSpinner<T>({
  required Future<T> Function() task,
  required String message,
  String? successMessage,
  String? errorMessage,
}) async
```

**Parametry:**
- `task`: Funkcja asynchroniczna do wykonania
- `message`: Tekst wyświetlany podczas działania spinnera
- `successMessage`: Opcjonalny komunikat wyświetlany po pomyślnym zakończeniu
- `errorMessage`: Opcjonalny komunikat wyświetlany w przypadku niepowodzenia zadania

**Zwraca:** Wynik funkcji zadania

**Przykład:**
```dart
@override
Future<void> handle(CommandResult result) async {
  // Uruchom zadanie ze spinnerem
  final projectFiles = await withSpinner(
    task: () async {
      // Długotrwałe zadanie (np. analiza plików projektu)
      await sleep(2);
      return ['pubspec.yaml', 'lib/main.dart', 'README.md'];
    },
    message: 'Analyzing project structure',
    successMessage: 'Project analysis complete',
    errorMessage: 'Failed to analyze project',
  );

  // Kontynuuj z wynikami
  info('Found ${projectFiles.length} key files');
}
```

<div id="manual-spinner-control"></div>

## Ręczna kontrola spinnera

W bardziej złożonych scenariuszach, gdy potrzebujesz ręcznie kontrolować stan spinnera, możesz utworzyć instancję spinnera:

```dart
ConsoleSpinner createSpinner(String message)
```

**Parametry:**
- `message`: Tekst wyświetlany podczas działania spinnera

**Zwraca:** Instancję `ConsoleSpinner`, którą możesz ręcznie kontrolować

**Przykład z ręczną kontrolą:**
```dart
@override
Future<void> handle(CommandResult result) async {
  // Utwórz instancję spinnera
  final spinner = createSpinner('Deploying to production');
  spinner.start();

  try {
    // Pierwsze zadanie
    await runProcess('flutter clean', silent: true);
    spinner.update('Building release version');

    // Drugie zadanie
    await runProcess('flutter build web --release', silent: true);
    spinner.update('Uploading to server');

    // Trzecie zadanie
    await runProcess('./deploy.sh', silent: true);

    // Zakończ pomyślnie
    spinner.stop(completionMessage: 'Deployment completed successfully', success: true);
  } catch (e) {
    // Obsłuż niepowodzenie
    spinner.stop(completionMessage: 'Deployment failed: $e', success: false);
    rethrow;
  }
}
```

<div id="spinner-examples"></div>

## Przykłady

### Proste zadanie ze spinnerem

```dart
@override
Future<void> handle(CommandResult result) async {
  await withSpinner(
    task: () async {
      // Zainstaluj zależności
      await runProcess('flutter pub get', silent: true);
      return true;
    },
    message: 'Installing dependencies',
    successMessage: 'Dependencies installed successfully',
  );
}
```

### Wiele kolejnych operacji

```dart
@override
Future<void> handle(CommandResult result) async {
  // Pierwsza operacja ze spinnerem
  await withSpinner(
    task: () => runProcess('flutter clean', silent: true),
    message: 'Cleaning project',
  );

  // Druga operacja ze spinnerem
  await withSpinner(
    task: () => runProcess('flutter pub get', silent: true),
    message: 'Updating dependencies',
  );

  // Trzecia operacja ze spinnerem
  final buildSuccess = await withSpinner(
    task: () async {
      await runProcess('flutter build apk --release', silent: true);
      return true;
    },
    message: 'Building release APK',
    successMessage: 'Release APK built successfully',
  );

  if (buildSuccess) {
    success('Build process completed');
  }
}
```

### Złożony przepływ pracy z ręczną kontrolą

```dart
@override
Future<void> handle(CommandResult result) async {
  final spinner = createSpinner('Starting deployment process');
  spinner.start();

  try {
    // Wykonaj wiele kroków z aktualizacjami statusu
    spinner.update('Step 1: Cleaning project');
    await runProcess('flutter clean', silent: true);

    spinner.update('Step 2: Fetching dependencies');
    await runProcess('flutter pub get', silent: true);

    spinner.update('Step 3: Building release');
    await runProcess('flutter build web --release', silent: true);

    // Zakończ proces
    spinner.stop(completionMessage: 'Deployment completed successfully', success: true);

  } catch (e) {
    spinner.stop(completionMessage: 'Deployment failed: $e', success: false);
  }
}
```

Używanie spinnerów w komendach niestandardowych zapewnia wyraźną wizualną informację zwrotną dla użytkowników podczas długotrwałych operacji, tworząc bardziej dopracowane i profesjonalne doświadczenie wiersza poleceń.

<div id="custom-command-helper-get-string"></div>

### Pobieranie wartości tekstowej z opcji

```dart
String getString(String name, {String defaultValue = ''})
```

**Parametry:**

- `name`: Nazwa opcji do pobrania
- `defaultValue`: Opcjonalna wartość domyślna, jeśli opcja nie została podana

**Zwraca:** Wartość opcji jako ciąg znaków

**Przykład:**
```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addOption("name", defaultValue: "Anthony");
  return command;
}

Future<void> handle(CommandResult result) async {
  final name = result.getString('name');
  info('Hello, $name!');
}
```

<div id="custom-command-helper-get-bool"></div>

### Pobieranie wartości logicznej z opcji

```dart
bool getBool(String name, {bool defaultValue = false})
```

**Parametry:**
- `name`: Nazwa opcji do pobrania
- `defaultValue`: Opcjonalna wartość domyślna, jeśli opcja nie została podana

**Zwraca:** Wartość opcji jako wartość logiczną


**Przykład:**
```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addFlag("verbose", defaultValue: false);
  return command;
}

Future<void> handle(CommandResult result) async {
  final verbose = result.getBool('verbose');
  if (verbose) {
    info('Verbose mode enabled');
  } else {
    info('Verbose mode disabled');
  }
}
```

<div id="custom-command-helper-get-int"></div>

### Pobieranie wartości całkowitej z opcji

```dart
int getInt(String name, {int defaultValue = 0})
```

**Parametry:**
- `name`: Nazwa opcji do pobrania
- `defaultValue`: Opcjonalna wartość domyślna, jeśli opcja nie została podana

**Zwraca:** Wartość opcji jako liczbę całkowitą

**Przykład:**
```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addOption("count", defaultValue: 5);
  return command;
}

Future<void> handle(CommandResult result) async {
  final count = result.getInt('count');
  info('Count is set to $count');
}
```

<div id="custom-command-helper-sleep"></div>

### Wstrzymanie na określony czas

```dart
void sleep(int seconds)
```

**Parametry:**
- `seconds`: Liczba sekund wstrzymania

**Zwraca:** Nic

**Przykład:**
```dart
@override
Future<void> handle(CommandResult result) async {
  info('Sleeping for 5 seconds...');
  await sleep(5);
  info('Awake now!');
}
```

<div id="output-formatting"></div>

## Formatowanie wyjścia

Oprócz podstawowych metod `info`, `error`, `success` i `warning`, `NyCustomCommand` dostarcza dodatkowe pomocniki wyjścia:

```dart
@override
Future<void> handle(CommandResult result) async {
  // Wyświetl zwykły tekst (bez koloru)
  line('Processing your request...');

  // Wyświetl puste linie
  newLine();       // jedna pusta linia
  newLine(3);      // trzy puste linie

  // Wyświetl wyciszony komentarz (szary tekst)
  comment('This is a background note');

  // Wyświetl wyróżnione pole alertu
  alert('Important: Please read carefully');

  // Ask jest aliasem dla prompt
  final name = ask('What is your name?');

  // Ukryte dane wejściowe dla wrażliwych danych (np. hasła, klucze API)
  final apiKey = promptSecret('Enter your API key:');

  // Przerwij komendę z komunikatem błędu i kodem wyjścia
  if (name.isEmpty) {
    abort('Name is required');  // kończy z kodem 1
  }
}
```

| Metoda | Opis |
|--------|-------------|
| `line(String message)` | Wyświetl zwykły tekst bez koloru |
| `newLine([int count = 1])` | Wyświetl puste linie |
| `comment(String message)` | Wyświetl wyciszony/szary tekst |
| `alert(String message)` | Wyświetl wyróżnione pole alertu |
| `ask(String question, {String defaultValue})` | Alias dla `prompt` |
| `promptSecret(String question)` | Ukryte dane wejściowe dla wrażliwych danych |
| `abort([String? message, int exitCode = 1])` | Zakończ komendę z błędem |

<div id="file-system-helpers"></div>

## Pomocniki systemu plików

`NyCustomCommand` zawiera wbudowane pomocniki systemu plików, więc nie musisz ręcznie importować `dart:io` do typowych operacji.

### Odczyt i zapis plików

```dart
@override
Future<void> handle(CommandResult result) async {
  // Sprawdź, czy plik istnieje
  if (fileExists('lib/config/app.dart')) {
    info('Config file found');
  }

  // Sprawdź, czy katalog istnieje
  if (directoryExists('lib/app/models')) {
    info('Models directory found');
  }

  // Odczytaj plik (asynchronicznie)
  String content = await readFile('pubspec.yaml');

  // Odczytaj plik (synchronicznie)
  String contentSync = readFileSync('pubspec.yaml');

  // Zapisz do pliku (asynchronicznie)
  await writeFile('lib/generated/output.dart', 'class Output {}');

  // Zapisz do pliku (synchronicznie)
  writeFileSync('lib/generated/output.dart', 'class Output {}');

  // Dopisz zawartość do pliku
  await appendFile('log.txt', 'New log entry\n');

  // Upewnij się, że katalog istnieje (utwórz, jeśli brakuje)
  await ensureDirectory('lib/generated');

  // Usuń plik
  await deleteFile('lib/generated/output.dart');

  // Skopiuj plik
  await copyFile('lib/config/app.dart', 'lib/config/app.bak.dart');
}
```

| Metoda | Opis |
|--------|-------------|
| `fileExists(String path)` | Zwraca `true`, jeśli plik istnieje |
| `directoryExists(String path)` | Zwraca `true`, jeśli katalog istnieje |
| `readFile(String path)` | Odczytaj plik jako ciąg znaków (asynchronicznie) |
| `readFileSync(String path)` | Odczytaj plik jako ciąg znaków (synchronicznie) |
| `writeFile(String path, String content)` | Zapisz zawartość do pliku (asynchronicznie) |
| `writeFileSync(String path, String content)` | Zapisz zawartość do pliku (synchronicznie) |
| `appendFile(String path, String content)` | Dopisz zawartość do pliku |
| `ensureDirectory(String path)` | Utwórz katalog, jeśli nie istnieje |
| `deleteFile(String path)` | Usuń plik |
| `copyFile(String source, String destination)` | Skopiuj plik |

<div id="json-yaml-helpers"></div>

## Pomocniki JSON i YAML

Odczytuj i zapisuj pliki JSON i YAML za pomocą wbudowanych pomocników.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Odczytaj plik JSON jako Map
  Map<String, dynamic> config = await readJson('config.json');

  // Odczytaj plik JSON jako List
  List<dynamic> items = await readJsonArray('lib/app/commands/commands.json');

  // Zapisz dane do pliku JSON (domyślnie sformatowane)
  await writeJson('output.json', {'name': 'MyApp', 'version': '1.0.0'});

  // Zapisz kompaktowy JSON
  await writeJson('output.json', data, pretty: false);

  // Dodaj element do pliku tablicy JSON
  // Jeśli plik zawiera [{"name": "a"}], to dodaje do tej tablicy
  await appendToJsonArray(
    'lib/app/commands/commands.json',
    {'name': 'my_command', 'category': 'app', 'script': 'my_command.dart'},
    uniqueKey: 'name',  // zapobiega duplikatom według tego klucza
  );

  // Odczytaj plik YAML jako Map
  Map<String, dynamic> pubspec = await readYaml('pubspec.yaml');
  info('Project: ${pubspec['name']}');
}
```

| Metoda | Opis |
|--------|-------------|
| `readJson(String path)` | Odczytaj plik JSON jako `Map<String, dynamic>` |
| `readJsonArray(String path)` | Odczytaj plik JSON jako `List<dynamic>` |
| `writeJson(String path, dynamic data, {bool pretty = true})` | Zapisz dane jako JSON |
| `appendToJsonArray(String path, Map item, {String? uniqueKey})` | Dodaj do pliku tablicy JSON |
| `readYaml(String path)` | Odczytaj plik YAML jako `Map<String, dynamic>` |

<div id="case-conversion-helpers"></div>

## Pomocniki konwersji wielkości liter

Konwertuj ciągi znaków między konwencjami nazewnictwa bez importowania pakietu `recase`.

```dart
@override
Future<void> handle(CommandResult result) async {
  String input = 'user profile page';

  info(snakeCase(input));    // user_profile_page
  info(camelCase(input));    // userProfilePage
  info(pascalCase(input));   // UserProfilePage
  info(titleCase(input));    // User Profile Page
  info(kebabCase(input));    // user-profile-page
  info(constantCase(input)); // USER_PROFILE_PAGE
}
```

| Metoda | Format wyjścia | Przykład |
|--------|--------------|---------|
| `snakeCase(String input)` | `snake_case` | `user_profile` |
| `camelCase(String input)` | `camelCase` | `userProfile` |
| `pascalCase(String input)` | `PascalCase` | `UserProfile` |
| `titleCase(String input)` | `Title Case` | `User Profile` |
| `kebabCase(String input)` | `kebab-case` | `user-profile` |
| `constantCase(String input)` | `CONSTANT_CASE` | `USER_PROFILE` |

<div id="project-path-helpers"></div>

## Pomocniki ścieżek projektu

Gettery dla standardowych katalogów projektów {{ config('app.name') }}. Zwracają ścieżki względne do głównego katalogu projektu.

```dart
@override
Future<void> handle(CommandResult result) async {
  info(modelsPath);       // lib/app/models
  info(controllersPath);  // lib/app/controllers
  info(widgetsPath);      // lib/resources/widgets
  info(pagesPath);        // lib/resources/pages
  info(commandsPath);     // lib/app/commands
  info(configPath);       // lib/config
  info(providersPath);    // lib/app/providers
  info(eventsPath);       // lib/app/events
  info(networkingPath);   // lib/app/networking
  info(themesPath);       // lib/resources/themes

  // Zbuduj niestandardową ścieżkę względną do głównego katalogu projektu
  String customPath = projectPath('lib/app/services/auth_service.dart');
}
```

| Właściwość | Ścieżka |
|----------|------|
| `modelsPath` | `lib/app/models` |
| `controllersPath` | `lib/app/controllers` |
| `widgetsPath` | `lib/resources/widgets` |
| `pagesPath` | `lib/resources/pages` |
| `commandsPath` | `lib/app/commands` |
| `configPath` | `lib/config` |
| `providersPath` | `lib/app/providers` |
| `eventsPath` | `lib/app/events` |
| `networkingPath` | `lib/app/networking` |
| `themesPath` | `lib/resources/themes` |
| `projectPath(String relativePath)` | Rozwiązuje ścieżkę względną w projekcie |

<div id="platform-helpers"></div>

## Pomocniki platformy

Sprawdzaj platformę i uzyskuj dostęp do zmiennych środowiskowych.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Sprawdzenia platformy
  if (isWindows) {
    info('Running on Windows');
  } else if (isMacOS) {
    info('Running on macOS');
  } else if (isLinux) {
    info('Running on Linux');
  }

  // Bieżący katalog roboczy
  info('Working in: $workingDirectory');

  // Odczytaj zmienne środowiskowe systemu
  String home = env('HOME', '/default/path');
}
```

| Właściwość / Metoda | Opis |
|-------------------|-------------|
| `isWindows` | `true` jeśli uruchomione na Windows |
| `isMacOS` | `true` jeśli uruchomione na macOS |
| `isLinux` | `true` jeśli uruchomione na Linux |
| `workingDirectory` | Ścieżka bieżącego katalogu roboczego |
| `env(String key, [String defaultValue = ''])` | Odczytaj zmienną środowiskową systemu |

<div id="dart-flutter-commands"></div>

## Komendy Dart i Flutter

Uruchamiaj typowe komendy CLI Dart i Flutter jako metody pomocnicze. Każda zwraca kod wyjścia procesu.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Formatuj plik lub katalog Dart
  await dartFormat('lib/app/models/user.dart');

  // Uruchom dart analyze
  int analyzeResult = await dartAnalyze('lib/');

  // Uruchom flutter pub get
  await flutterPubGet();

  // Uruchom flutter clean
  await flutterClean();

  // Buduj dla celu z dodatkowymi argumentami
  await flutterBuild('apk', args: ['--release', '--split-per-abi']);
  await flutterBuild('web', args: ['--release']);

  // Uruchom flutter test
  await flutterTest();
  await flutterTest('test/unit/');  // konkretny katalog
}
```

| Metoda | Opis |
|--------|-------------|
| `dartFormat(String path)` | Uruchom `dart format` na pliku lub katalogu |
| `dartAnalyze([String? path])` | Uruchom `dart analyze` |
| `flutterPubGet()` | Uruchom `flutter pub get` |
| `flutterClean()` | Uruchom `flutter clean` |
| `flutterBuild(String target, {List<String> args})` | Uruchom `flutter build <target>` |
| `flutterTest([String? path])` | Uruchom `flutter test` |

<div id="dart-file-manipulation"></div>

## Manipulacja plikami Dart

Pomocniki do programistycznej edycji plików Dart, przydatne przy budowaniu narzędzi szkieletowania.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Dodaj instrukcję import do pliku Dart (unika duplikatów)
  await addImport(
    'lib/bootstrap/providers.dart',
    "import '/app/providers/firebase_provider.dart';",
  );

  // Wstaw kod przed ostatnim nawiasem zamykającym w pliku
  // Przydatne do dodawania wpisów do map rejestracji
  await insertBeforeClosingBrace(
    'lib/bootstrap/providers.dart',
    '  FirebaseProvider(),',
  );

  // Sprawdź, czy plik zawiera określony ciąg znaków
  bool hasImport = await fileContains(
    'lib/bootstrap/providers.dart',
    'firebase_provider',
  );

  // Sprawdź, czy plik pasuje do wzorca regex
  bool hasClass = await fileContainsPattern(
    'lib/app/models/user.dart',
    RegExp(r'class User'),
  );
}
```

| Metoda | Opis |
|--------|-------------|
| `addImport(String filePath, String importStatement)` | Dodaj import do pliku Dart (pomija, jeśli już istnieje) |
| `insertBeforeClosingBrace(String filePath, String code)` | Wstaw kod przed ostatnim `}` w pliku |
| `fileContains(String filePath, String identifier)` | Sprawdź, czy plik zawiera ciąg znaków |
| `fileContainsPattern(String filePath, Pattern pattern)` | Sprawdź, czy plik pasuje do wzorca |

<div id="directory-helpers"></div>

## Pomocniki katalogów

Pomocniki do pracy z katalogami i wyszukiwania plików.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Wylistuj zawartość katalogu
  var entities = listDirectory('lib/app/models');
  for (var entity in entities) {
    info(entity.path);
  }

  // Wylistuj rekurencyjnie
  var allEntities = listDirectory('lib/', recursive: true);

  // Znajdź pliki pasujące do kryteriów
  List<File> dartFiles = findFiles(
    'lib/app/models',
    extension: '.dart',
    recursive: true,
  );

  // Znajdź pliki po wzorcu nazwy
  List<File> testFiles = findFiles(
    'test/',
    namePattern: RegExp(r'_test\.dart$'),
  );

  // Usuń katalog rekurencyjnie
  await deleteDirectory('build/');

  // Skopiuj katalog (rekurencyjnie)
  await copyDirectory('lib/templates', 'lib/generated');
}
```

| Metoda | Opis |
|--------|-------------|
| `listDirectory(String path, {bool recursive = false})` | Wylistuj zawartość katalogu |
| `findFiles(String directory, {String? extension, Pattern? namePattern, bool recursive = true})` | Znajdź pliki pasujące do kryteriów |
| `deleteDirectory(String path)` | Usuń katalog rekurencyjnie |
| `copyDirectory(String source, String destination)` | Skopiuj katalog rekurencyjnie |

<div id="validation-helpers"></div>

## Pomocniki walidacji

Pomocniki do walidacji i czyszczenia danych wejściowych użytkownika do generowania kodu.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Zwaliduj identyfikator Dart
  if (!isValidDartIdentifier('MyClass')) {
    error('Invalid Dart identifier');
  }

  // Wymagaj niepustego pierwszego argumentu
  String name = requireArgument(result, message: 'Please provide a name');

  // Wyczyść nazwę klasy (PascalCase, usuń sufiksy)
  String className = cleanClassName('user_model', removeSuffixes: ['_model']);
  // Zwraca: 'User'

  // Wyczyść nazwę pliku (snake_case z rozszerzeniem)
  String fileName = cleanFileName('UserModel', extension: '.dart');
  // Zwraca: 'user_model.dart'
}
```

| Metoda | Opis |
|--------|-------------|
| `isValidDartIdentifier(String name)` | Zwaliduj nazwę identyfikatora Dart |
| `requireArgument(CommandResult result, {String? message})` | Wymagaj niepustego pierwszego argumentu lub przerwij |
| `cleanClassName(String name, {List<String> removeSuffixes})` | Wyczyść i skonwertuj nazwę klasy na PascalCase |
| `cleanFileName(String name, {String extension = '.dart'})` | Wyczyść i skonwertuj nazwę pliku na snake_case |

<div id="file-scaffolding"></div>

## Szkieletowanie plików

Twórz jeden lub wiele plików z zawartością za pomocą systemu szkieletowania.

### Pojedynczy plik

```dart
@override
Future<void> handle(CommandResult result) async {
  await scaffold(
    path: 'lib/app/services/auth_service.dart',
    content: '''
class AuthService {
  Future<bool> login(String email, String password) async {
    // TODO: implement login
    return false;
  }
}
''',
    force: false,  // nie nadpisuj, jeśli istnieje
    successMessage: 'AuthService created',
  );
}
```

### Wiele plików

```dart
@override
Future<void> handle(CommandResult result) async {
  await scaffoldMany([
    ScaffoldFile(
      path: 'lib/app/models/product.dart',
      content: 'class Product {}',
      successMessage: 'Product model created',
    ),
    ScaffoldFile(
      path: 'lib/app/networking/product_api_service.dart',
      content: 'class ProductApiService {}',
      successMessage: 'Product API service created',
    ),
  ], force: false);
}
```

Klasa `ScaffoldFile` przyjmuje:

| Właściwość | Typ | Opis |
|----------|------|-------------|
| `path` | `String` | Ścieżka pliku do utworzenia |
| `content` | `String` | Zawartość pliku |
| `successMessage` | `String?` | Komunikat wyświetlany po sukcesie |

<div id="task-runner"></div>

## Runner zadań

Uruchamiaj serię nazwanych zadań z automatycznym wyświetlaniem statusu.

### Podstawowy runner zadań

```dart
@override
Future<void> handle(CommandResult result) async {
  await runTasks([
    CommandTask(
      'Clean project',
      () => runProcess('flutter clean', silent: true),
    ),
    CommandTask(
      'Fetch dependencies',
      () => runProcess('flutter pub get', silent: true),
    ),
    CommandTask(
      'Run tests',
      () => runProcess('flutter test', silent: true),
      stopOnError: true,  // zatrzymaj potok, jeśli to się nie powiedzie (domyślnie)
    ),
  ]);
}
```

### Runner zadań ze spinnerem

```dart
@override
Future<void> handle(CommandResult result) async {
  await runTasksWithSpinner([
    CommandTask(
      name: 'Preparing release',
      action: () async {
        await flutterClean();
        await flutterPubGet();
      },
    ),
    CommandTask(
      name: 'Building APK',
      action: () => flutterBuild('apk', args: ['--release']),
    ),
  ]);
}
```

Klasa `CommandTask` przyjmuje:

| Właściwość | Typ | Domyślnie | Opis |
|----------|------|---------|-------------|
| `name` | `String` | wymagane | Nazwa zadania wyświetlana w wynikach |
| `action` | `Future<void> Function()` | wymagane | Asynchroniczna funkcja do wykonania |
| `stopOnError` | `bool` | `true` | Czy zatrzymać pozostałe zadania, jeśli to się nie powiedzie |

<div id="table-output"></div>

## Wyjście tabelaryczne

Wyświetlaj sformatowane tabele ASCII w konsoli.

```dart
@override
Future<void> handle(CommandResult result) async {
  table(
    ['Name', 'Version', 'Status'],
    [
      ['nylo_framework', '7.0.0', 'installed'],
      ['nylo_support', '7.0.0', 'installed'],
      ['dio', '5.4.0', 'installed'],
    ],
  );
}
```

Wynik:

```
┌─────────────────┬─────────┬───────────┐
│ Name            │ Version │ Status    │
├─────────────────┼─────────┼───────────┤
│ nylo_framework  │ 7.0.0   │ installed │
│ nylo_support    │ 7.0.0   │ installed │
│ dio             │ 5.4.0   │ installed │
└─────────────────┴─────────┴───────────┘
```

<div id="progress-bar"></div>

## Pasek postępu

Wyświetlaj pasek postępu dla operacji ze znaną liczbą elementów.

### Ręczny pasek postępu

```dart
@override
Future<void> handle(CommandResult result) async {
  // Utwórz pasek postępu dla 100 elementów
  final progress = progressBar(100, message: 'Processing files');
  progress.start();

  for (int i = 0; i < 100; i++) {
    await Future.delayed(Duration(milliseconds: 50));
    progress.tick();  // zwiększ o 1
  }

  progress.complete('All files processed');
}
```

### Przetwarzanie elementów z postępem

```dart
@override
Future<void> handle(CommandResult result) async {
  final files = findFiles('lib/', extension: '.dart');

  // Przetwarzaj elementy z automatycznym śledzeniem postępu
  final results = await withProgress<File, String>(
    items: files,
    process: (file, index) async {
      // przetwórz każdy plik
      return file.path;
    },
    message: 'Analyzing Dart files',
    completionMessage: 'Analysis complete',
  );

  info('Processed ${results.length} files');
}
```

### Synchroniczny postęp

```dart
@override
Future<void> handle(CommandResult result) async {
  final items = ['a', 'b', 'c', 'd', 'e'];

  final results = withProgressSync<String, String>(
    items: items,
    process: (item, index) {
      // przetwarzanie synchroniczne
      return item.toUpperCase();
    },
    message: 'Converting items',
  );

  info('Results: $results');
}
```

Klasa `ConsoleProgressBar` dostarcza:

| Metoda | Opis |
|--------|-------------|
| `start()` | Rozpocznij pasek postępu |
| `tick([int amount = 1])` | Zwiększ postęp |
| `update(int value)` | Ustaw postęp na konkretną wartość |
| `updateMessage(String newMessage)` | Zmień wyświetlany komunikat |
| `complete([String? completionMessage])` | Zakończ z opcjonalnym komunikatem |
| `stop()` | Zatrzymaj bez kończenia |
| `current` | Bieżąca wartość postępu (getter) |
| `percentage` | Postęp jako procent (getter) |
