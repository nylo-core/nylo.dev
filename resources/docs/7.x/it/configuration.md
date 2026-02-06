# Configurazione

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione alla configurazione")
- Ambiente
  - [Il File .env](#env-file "Il file .env")
  - [Generazione della Configurazione Ambiente](#generating-env "Generazione della configurazione ambiente")
  - [Recupero dei Valori](#retrieving-values "Recupero dei valori dell'ambiente")
  - [Creazione di Classi di Configurazione](#creating-config-classes "Creazione di classi di configurazione")
  - [Tipi di Variabili](#variable-types "Tipi di variabili d'ambiente")
- [Varianti d'Ambiente](#environment-flavours "Varianti d'ambiente")
- [Iniezione al Build-Time](#build-time-injection "Iniezione al build-time")


<div id="introduction"></div>

## Introduzione

{{ config('app.name') }} v7 utilizza un sistema di configurazione d'ambiente sicuro. Le tue variabili d'ambiente sono memorizzate in un file `.env` e poi crittografate in un file Dart generato (`env.g.dart`) per l'uso nella tua app.

Questo approccio fornisce:
- **Sicurezza**: I valori dell'ambiente sono crittografati con XOR nell'app compilata
- **Type safety**: I valori vengono automaticamente convertiti nei tipi appropriati
- **Flessibilità al build-time**: Configurazioni diverse per sviluppo, staging e produzione

<div id="env-file"></div>

## Il File .env

Il file `.env` nella radice del tuo progetto contiene le variabili di configurazione:

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

### Variabili Disponibili

| Variabile | Descrizione |
|-----------|-------------|
| `APP_KEY` | **Obbligatorio**. Chiave segreta di 32 caratteri per la crittografia |
| `APP_NAME` | Il nome della tua applicazione |
| `APP_ENV` | Ambiente: `developing` o `production` |
| `APP_DEBUG` | Abilita modalità debug (`true`/`false`) |
| `APP_URL` | L'URL della tua app |
| `API_BASE_URL` | URL base per le richieste API |
| `ASSET_PATH` | Percorso alla cartella assets |
| `DEFAULT_LOCALE` | Codice lingua predefinito |

<div id="generating-env"></div>

## Generazione della Configurazione Ambiente

{{ config('app.name') }} v7 richiede la generazione di un file d'ambiente crittografato prima che la tua app possa accedere ai valori env.

### Passo 1: Genera un APP_KEY

Prima, genera un APP_KEY sicuro:

``` bash
metro make:key
```

Questo aggiunge un `APP_KEY` di 32 caratteri al tuo file `.env`.

### Passo 2: Genera env.g.dart

Genera il file d'ambiente crittografato:

``` bash
metro make:env
```

Questo crea `lib/bootstrap/env.g.dart` con le tue variabili d'ambiente crittografate.

Il tuo env viene registrato automaticamente all'avvio dell'app — `Nylo.init(env: Env.get, ...)` in `main.dart` gestisce questo per te. Non è necessaria alcuna configurazione aggiuntiva.

### Rigenerazione Dopo le Modifiche

Quando modifichi il tuo file `.env`, rigenera la configurazione:

``` bash
metro make:env --force
```

Il flag `--force` sovrascrive il file `env.g.dart` esistente.

<div id="retrieving-values"></div>

## Recupero dei Valori

Il modo consigliato per accedere ai valori dell'ambiente è attraverso le **classi di configurazione**. Il tuo file `lib/config/app.dart` utilizza `getEnv()` per esporre i valori env come campi statici tipizzati:

``` dart
// lib/config/app.dart
final class AppConfig {
  static final String appName = getEnv('APP_NAME', defaultValue: 'Nylo');
  static final String appEnv = getEnv('APP_ENV', defaultValue: 'developing');
  static final bool appDebug = getEnv('APP_DEBUG', defaultValue: false);
  static final String apiBaseUrl = getEnv('API_BASE_URL');
}
```

Poi nel codice della tua app, accedi ai valori attraverso la classe di configurazione:

``` dart
// Anywhere in your app
String name = AppConfig.appName;
bool isDebug = AppConfig.appDebug;
String apiUrl = AppConfig.apiBaseUrl;
```

Questo pattern mantiene l'accesso all'env centralizzato nelle tue classi di configurazione. L'helper `getEnv()` dovrebbe essere usato all'interno delle classi di configurazione piuttosto che direttamente nel codice dell'app.

<div id="creating-config-classes"></div>

## Creazione di Classi di Configurazione

Puoi creare classi di configurazione personalizzate per servizi di terze parti o configurazioni specifiche per funzionalita usando Metro:

``` bash
metro make:config RevenueCat
```

Questo crea un nuovo file di configurazione in `lib/config/revenue_cat_config.dart`:

``` dart
final class RevenueCatConfig {
  // Add your config values here
}
```

### Esempio: Configurazione RevenueCat

**Passo 1:** Aggiungi le variabili d'ambiente al tuo file `.env`:

``` bash
REVENUECAT_API_KEY="appl_xxxxxxxxxxxxx"
REVENUECAT_ENTITLEMENT_ID="premium"
```

**Passo 2:** Aggiorna la tua classe di configurazione per referenziare questi valori:

``` dart
// lib/config/revenue_cat_config.dart
import 'package:nylo_framework/nylo_framework.dart';

final class RevenueCatConfig {
  static final String apiKey = getEnv('REVENUECAT_API_KEY');
  static final String entitlementId = getEnv('REVENUECAT_ENTITLEMENT_ID', defaultValue: 'premium');
}
```

**Passo 3:** Rigenera la tua configurazione d'ambiente:

``` bash
metro make:env --force
```

**Passo 4:** Usa la classe di configurazione nella tua app:

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

Questo approccio mantiene le tue chiavi API e i valori di configurazione sicuri e centralizzati, rendendo facile gestire valori diversi tra gli ambienti.

<div id="variable-types"></div>

## Tipi di Variabili

I valori nel tuo file `.env` vengono automaticamente convertiti:

| Valore .env | Tipo Dart | Esempio |
|-------------|-----------|---------|
| `APP_NAME="My App"` | `String` | `"My App"` |
| `DEBUG=true` | `bool` | `true` |
| `DEBUG=false` | `bool` | `false` |
| `VALUE=null` | `null` | `null` |
| `EMPTY=""` | `String` | `""` (stringa vuota) |


<div id="environment-flavours"></div>

## Varianti d'Ambiente

Crea configurazioni diverse per sviluppo, staging e produzione.

### Passo 1: Crea i File d'Ambiente

Crea file `.env` separati:

``` bash
.env                  # Development (default)
.env.staging          # Staging
.env.production       # Production
```

Esempio `.env.production`:

``` bash
APP_KEY=production-secret-key-here
APP_NAME="My App"
APP_ENV="production"
APP_DEBUG=false
APP_URL="https://myapp.com"
API_BASE_URL="https://api.myapp.com"
```

### Passo 2: Genera la Configurazione Ambiente

Genera da un file env specifico:

``` bash
# For production
metro make:env --file=".env.production" --force

# For staging
metro make:env --file=".env.staging" --force
```

### Passo 3: Compila la Tua App

Compila con la configurazione appropriata:

``` bash
# Development
flutter run

# Production build
metro make:env --file=.env.production --force
flutter build ios
flutter build appbundle
```

<div id="build-time-injection"></div>

## Iniezione al Build-Time

Per una sicurezza migliorata, puoi iniettare l'APP_KEY al build-time invece di incorporarlo nel codice sorgente.

### Genera con Modalità --dart-define

``` bash
metro make:env --dart-define
```

Questo genera `env.g.dart` senza incorporare l'APP_KEY.

### Compila con Iniezione APP_KEY

``` bash
# iOS
flutter build ios --dart-define=APP_KEY=your-secret-key

# Android
flutter build appbundle --dart-define=APP_KEY=your-secret-key

# Run
flutter run --dart-define=APP_KEY=your-secret-key
```

Questo approccio mantiene l'APP_KEY fuori dal codice sorgente, il che è utile per:
- Pipeline CI/CD dove i segreti vengono iniettati
- Progetti open source
- Requisiti di sicurezza avanzati

### Buone Pratiche

1. **Non fare mai il commit di `.env` nel controllo versione** - Aggiungilo al `.gitignore`
2. **Usa `.env-example`** - Fai il commit di un template senza valori sensibili
3. **Rigenera dopo le modifiche** - Esegui sempre `metro make:env --force` dopo aver modificato `.env`
4. **Chiavi diverse per ambiente** - Usa APP_KEY unici per sviluppo, staging e produzione
