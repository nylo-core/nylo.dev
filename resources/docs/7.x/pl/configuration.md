# Configuration

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie do konfiguracji")
- Środowisko
  - [Plik .env](#env-file "Plik .env")
  - [Generowanie konfiguracji środowiska](#generating-env "Generowanie konfiguracji środowiska")
  - [Pobieranie wartości](#retrieving-values "Pobieranie wartości środowiskowych")
  - [Tworzenie klas konfiguracyjnych](#creating-config-classes "Tworzenie klas konfiguracyjnych")
  - [Typy zmiennych](#variable-types "Typy zmiennych środowiskowych")
- [Warianty środowisk](#environment-flavours "Warianty środowisk")
- [Wstrzykiwanie w czasie kompilacji](#build-time-injection "Wstrzykiwanie w czasie kompilacji")


<div id="introduction"></div>

## Wprowadzenie

{{ config('app.name') }} v7 używa bezpiecznego systemu konfiguracji środowiskowej. Zmienne środowiskowe są przechowywane w pliku `.env`, a następnie szyfrowane do wygenerowanego pliku Dart (`env.g.dart`) do użycia w aplikacji.

To podejście zapewnia:
- **Bezpieczeństwo**: Wartości środowiskowe są szyfrowane XOR w skompilowanej aplikacji
- **Bezpieczeństwo typów**: Wartości są automatycznie parsowane do odpowiednich typów
- **Elastyczność w czasie kompilacji**: Różne konfiguracje dla środowisk development, staging i production

<div id="env-file"></div>

## Plik .env

Plik `.env` w katalogu głównym projektu zawiera zmienne konfiguracyjne:

``` bash
# Environment configuration
APP_KEY=your-32-character-secret-key
APP_NAME="My App"
APP_ENV="developing"
APP_DEBUG=true
APP_URL="https://myapp.com"
API_BASE_URL="https://api.myapp.com"

ASSET_PATH="assets"

DEFAULT_LOCALE="en"
```

### Dostępne zmienne

| Zmienna | Opis |
|---------|------|
| `APP_KEY` | **Wymagany**. 32-znakowy klucz tajny do szyfrowania |
| `APP_NAME` | Nazwa aplikacji |
| `APP_ENV` | Środowisko: `developing` lub `production` |
| `APP_DEBUG` | Włącz tryb debugowania (`true`/`false`) |
| `APP_URL` | URL aplikacji |
| `API_BASE_URL` | Bazowy URL dla żądań API |
| `ASSET_PATH` | Ścieżka do folderu zasobów |
| `DEFAULT_LOCALE` | Domyślny kod języka |

<div id="generating-env"></div>

## Generowanie konfiguracji środowiska

{{ config('app.name') }} v7 wymaga wygenerowania zaszyfrowanego pliku środowiskowego, zanim aplikacja będzie mogła uzyskać dostęp do wartości env.

### Krok 1: Wygeneruj APP_KEY

Najpierw wygeneruj bezpieczny APP_KEY:

``` bash
metro make:key
```

To dodaje 32-znakowy `APP_KEY` do pliku `.env`.

### Krok 2: Wygeneruj env.g.dart

Wygeneruj zaszyfrowany plik środowiskowy:

``` bash
metro make:env
```

To tworzy `lib/bootstrap/env.g.dart` z zaszyfrowanymi zmiennymi środowiskowymi.

Środowisko jest automatycznie rejestrowane przy uruchomieniu aplikacji — `Nylo.init(env: Env.get, ...)` w `main.dart` obsługuje to za Ciebie. Nie jest wymagana dodatkowa konfiguracja.

### Ponowne generowanie po zmianach

Gdy zmodyfikujesz plik `.env`, ponownie wygeneruj konfigurację:

``` bash
metro make:env --force
```

Flaga `--force` nadpisuje istniejący `env.g.dart`.

<div id="retrieving-values"></div>

## Pobieranie wartości

Zalecanym sposobem dostępu do wartości środowiskowych jest użycie **klas konfiguracyjnych**. Plik `lib/config/app.dart` używa `getEnv()` do eksponowania wartości env jako typowanych pól statycznych:

``` dart
// lib/config/app.dart
final class AppConfig {
  static final String appName = getEnv('APP_NAME', defaultValue: 'Nylo');
  static final String appEnv = getEnv('APP_ENV', defaultValue: 'developing');
  static final bool appDebug = getEnv('APP_DEBUG', defaultValue: false);
  static final String apiBaseUrl = getEnv('API_BASE_URL');
}
```

Następnie w kodzie aplikacji uzyskaj dostęp do wartości przez klasę konfiguracyjną:

``` dart
// Anywhere in your app
String name = AppConfig.appName;
bool isDebug = AppConfig.appDebug;
String apiUrl = AppConfig.apiBaseUrl;
```

Ten wzorzec centralizuje dostęp do env w klasach konfiguracyjnych. Helper `getEnv()` powinien być używany w klasach konfiguracyjnych, a nie bezpośrednio w kodzie aplikacji.

<div id="creating-config-classes"></div>

## Tworzenie klas konfiguracyjnych

Możesz tworzyć niestandardowe klasy konfiguracyjne dla usług zewnętrznych lub konfiguracji specyficznych dla funkcji za pomocą Metro:

``` bash
metro make:config RevenueCat
```

To tworzy nowy plik konfiguracyjny w `lib/config/revenue_cat_config.dart`:

``` dart
final class RevenueCatConfig {
  // Add your config values here
}
```

### Przykład: Konfiguracja RevenueCat

**Krok 1:** Dodaj zmienne środowiskowe do pliku `.env`:

``` bash
REVENUECAT_API_KEY="appl_xxxxxxxxxxxxx"
REVENUECAT_ENTITLEMENT_ID="premium"
```

**Krok 2:** Zaktualizuj klasę konfiguracyjną, aby odwoływała się do tych wartości:

``` dart
// lib/config/revenue_cat_config.dart
import 'package:nylo_framework/nylo_framework.dart';

final class RevenueCatConfig {
  static final String apiKey = getEnv('REVENUECAT_API_KEY');
  static final String entitlementId = getEnv('REVENUECAT_ENTITLEMENT_ID', defaultValue: 'premium');
}
```

**Krok 3:** Ponownie wygeneruj konfigurację środowiska:

``` bash
metro make:env --force
```

**Krok 4:** Użyj klasy konfiguracyjnej w aplikacji:

``` dart
import '/config/revenue_cat_config.dart';

// Initialize RevenueCat
await Purchases.configure(
  PurchasesConfiguration(RevenueCatConfig.apiKey),
);

// Check entitlements
if (entitlement.identifier == RevenueCatConfig.entitlementId) {
  // Grant premium access
}
```

To podejście zapewnia bezpieczeństwo i centralizację kluczy API i wartości konfiguracyjnych, ułatwiając zarządzanie różnymi wartościami w różnych środowiskach.

<div id="variable-types"></div>

## Typy zmiennych

Wartości w pliku `.env` są automatycznie parsowane:

| Wartość .env | Typ Dart | Przykład |
|--------------|----------|----------|
| `APP_NAME="My App"` | `String` | `"My App"` |
| `DEBUG=true` | `bool` | `true` |
| `DEBUG=false` | `bool` | `false` |
| `VALUE=null` | `null` | `null` |
| `EMPTY=""` | `String` | `""` (pusty string) |


<div id="environment-flavours"></div>

## Warianty środowisk

Twórz różne konfiguracje dla środowisk development, staging i production.

### Krok 1: Utwórz pliki środowiskowe

Utwórz oddzielne pliki `.env`:

``` bash
.env                  # Development (default)
.env.staging          # Staging
.env.production       # Production
```

Przykład `.env.production`:

``` bash
APP_KEY=production-secret-key-here
APP_NAME="My App"
APP_ENV="production"
APP_DEBUG=false
APP_URL="https://myapp.com"
API_BASE_URL="https://api.myapp.com"
```

### Krok 2: Wygeneruj konfigurację środowiska

Wygeneruj z konkretnego pliku env:

``` bash
# For production
metro make:env --file=".env.production" --force

# For staging
metro make:env --file=".env.staging" --force
```

### Krok 3: Zbuduj aplikację

Zbuduj z odpowiednią konfiguracją:

``` bash
# Development
flutter run

# Production build
metro make:env --file=.env.production --force
flutter build ios
flutter build appbundle
```

<div id="build-time-injection"></div>

## Wstrzykiwanie w czasie kompilacji

Dla zwiększonego bezpieczeństwa możesz wstrzyknąć APP_KEY w czasie kompilacji zamiast osadzać go w kodzie źródłowym.

### Generowanie z trybem --dart-define

``` bash
metro make:env --dart-define
```

To generuje `env.g.dart` bez osadzania APP_KEY.

### Budowanie z wstrzykiwaniem APP_KEY

``` bash
# iOS
flutter build ios --dart-define=APP_KEY=your-secret-key

# Android
flutter build appbundle --dart-define=APP_KEY=your-secret-key

# Run
flutter run --dart-define=APP_KEY=your-secret-key
```

To podejście utrzymuje APP_KEY poza kodem źródłowym, co jest przydatne dla:
- Pipeline CI/CD, gdzie sekrety są wstrzykiwane
- Projektów open source
- Zwiększonych wymagań bezpieczeństwa

### Najlepsze praktyki

1. **Nigdy nie commituj `.env` do kontroli wersji** - Dodaj go do `.gitignore`
2. **Używaj `.env-example`** - Commituj szablon bez wrażliwych wartości
3. **Ponownie generuj po zmianach** - Zawsze uruchamiaj `metro make:env --force` po modyfikacji `.env`
4. **Różne klucze per środowisko** - Używaj unikalnych APP_KEY dla development, staging i production
