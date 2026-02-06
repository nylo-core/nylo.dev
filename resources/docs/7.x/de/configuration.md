# Konfiguration

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung zur Konfiguration")
- Umgebung
  - [Die .env-Datei](#env-file "Die .env-Datei")
  - [Umgebungskonfiguration generieren](#generating-env "Umgebungskonfiguration generieren")
  - [Werte abrufen](#retrieving-values "Umgebungswerte abrufen")
  - [Konfigurationsklassen erstellen](#creating-config-classes "Konfigurationsklassen erstellen")
  - [Variablentypen](#variable-types "Umgebungsvariablentypen")
- [Umgebungsvarianten](#environment-flavours "Umgebungsvarianten")
- [Build-Time-Injektion](#build-time-injection "Build-Time-Injektion")


<div id="introduction"></div>

## Einleitung

{{ config('app.name') }} v7 verwendet ein sicheres Umgebungskonfigurationssystem. Ihre Umgebungsvariablen werden in einer `.env`-Datei gespeichert und dann in eine generierte Dart-Datei (`env.g.dart`) verschlüsselt, die in Ihrer App verwendet wird.

Dieser Ansatz bietet:
- **Sicherheit**: Umgebungswerte werden XOR-verschlüsselt in der kompilierten App
- **Typsicherheit**: Werte werden automatisch in geeignete Typen umgewandelt
- **Build-Time-Flexibilität**: Verschiedene Konfigurationen für Entwicklung, Staging und Produktion

<div id="env-file"></div>

## Die .env-Datei

Die `.env`-Datei im Stammverzeichnis Ihres Projekts enthält Ihre Konfigurationsvariablen:

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

### Verfügbare Variablen

| Variable | Beschreibung |
|----------|-------------|
| `APP_KEY` | **Erforderlich**. 32-Zeichen-Geheimschlüssel für die Verschlüsselung |
| `APP_NAME` | Ihr Anwendungsname |
| `APP_ENV` | Umgebung: `developing` oder `production` |
| `APP_DEBUG` | Debug-Modus aktivieren (`true`/`false`) |
| `APP_URL` | Die URL Ihrer App |
| `API_BASE_URL` | Basis-URL für API-Anfragen |
| `ASSET_PATH` | Pfad zum Assets-Ordner |
| `DEFAULT_LOCALE` | Standard-Sprachcode |

<div id="generating-env"></div>

## Umgebungskonfiguration generieren

{{ config('app.name') }} v7 erfordert die Generierung einer verschlüsselten Umgebungsdatei, bevor Ihre App auf Umgebungswerte zugreifen kann.

### Schritt 1: APP_KEY generieren

Generieren Sie zunächst einen sicheren APP_KEY:

``` bash
metro make:key
```

Dies fügt einen 32-Zeichen-`APP_KEY` zu Ihrer `.env`-Datei hinzu.

### Schritt 2: env.g.dart generieren

Generieren Sie die verschlüsselte Umgebungsdatei:

``` bash
metro make:env
```

Dies erstellt `lib/bootstrap/env.g.dart` mit Ihren verschlüsselten Umgebungsvariablen.

Ihre Umgebung wird automatisch registriert, wenn Ihre App startet -- `Nylo.init(env: Env.get, ...)` in `main.dart` erledigt dies für Sie. Keine zusätzliche Einrichtung erforderlich.

### Nach Änderungen neu generieren

Wenn Sie Ihre `.env`-Datei ändern, generieren Sie die Konfiguration neu:

``` bash
metro make:env --force
```

Das Flag `--force` überschreibt die vorhandene `env.g.dart`.

<div id="retrieving-values"></div>

## Werte abrufen

Der empfohlene Weg zum Zugriff auf Umgebungswerte ist über **Konfigurationsklassen**. Ihre Datei `lib/config/app.dart` verwendet `getEnv()`, um Umgebungswerte als typisierte statische Felder bereitzustellen:

``` dart
// lib/config/app.dart
final class AppConfig {
  static final String appName = getEnv('APP_NAME', defaultValue: 'Nylo');
  static final String appEnv = getEnv('APP_ENV', defaultValue: 'developing');
  static final bool appDebug = getEnv('APP_DEBUG', defaultValue: false);
  static final String apiBaseUrl = getEnv('API_BASE_URL');
}
```

Dann greifen Sie in Ihrem App-Code über die Konfigurationsklasse auf die Werte zu:

``` dart
// Anywhere in your app
String name = AppConfig.appName;
bool isDebug = AppConfig.appDebug;
String apiUrl = AppConfig.apiBaseUrl;
```

Dieses Muster hält den Umgebungszugriff in Ihren Konfigurationsklassen zentralisiert. Der `getEnv()`-Helfer sollte innerhalb von Konfigurationsklassen verwendet werden, nicht direkt im App-Code.

<div id="creating-config-classes"></div>

## Konfigurationsklassen erstellen

Sie können benutzerdefinierte Konfigurationsklassen für Drittanbieter-Dienste oder funktionsspezifische Konfigurationen mit Metro erstellen:

``` bash
metro make:config RevenueCat
```

Dies erstellt eine neue Konfigurationsdatei unter `lib/config/revenue_cat_config.dart`:

``` dart
final class RevenueCatConfig {
  // Add your config values here
}
```

### Beispiel: RevenueCat-Konfiguration

**Schritt 1:** Fügen Sie die Umgebungsvariablen zu Ihrer `.env`-Datei hinzu:

``` bash
REVENUECAT_API_KEY="appl_xxxxxxxxxxxxx"
REVENUECAT_ENTITLEMENT_ID="premium"
```

**Schritt 2:** Aktualisieren Sie Ihre Konfigurationsklasse, um diese Werte zu referenzieren:

``` dart
// lib/config/revenue_cat_config.dart
import 'package:nylo_framework/nylo_framework.dart';

final class RevenueCatConfig {
  static final String apiKey = getEnv('REVENUECAT_API_KEY');
  static final String entitlementId = getEnv('REVENUECAT_ENTITLEMENT_ID', defaultValue: 'premium');
}
```

**Schritt 3:** Generieren Sie Ihre Umgebungskonfiguration neu:

``` bash
metro make:env --force
```

**Schritt 4:** Verwenden Sie die Konfigurationsklasse in Ihrer App:

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

Dieser Ansatz hält Ihre API-Schlüssel und Konfigurationswerte sicher und zentralisiert, was die Verwaltung verschiedener Werte über Umgebungen hinweg erleichtert.

<div id="variable-types"></div>

## Variablentypen

Werte in Ihrer `.env`-Datei werden automatisch geparst:

| .env-Wert | Dart-Typ | Beispiel |
|-----------|----------|---------|
| `APP_NAME="My App"` | `String` | `"My App"` |
| `DEBUG=true` | `bool` | `true` |
| `DEBUG=false` | `bool` | `false` |
| `VALUE=null` | `null` | `null` |
| `EMPTY=""` | `String` | `""` (leerer String) |


<div id="environment-flavours"></div>

## Umgebungsvarianten

Erstellen Sie verschiedene Konfigurationen für Entwicklung, Staging und Produktion.

### Schritt 1: Umgebungsdateien erstellen

Erstellen Sie separate `.env`-Dateien:

``` bash
.env                  # Development (default)
.env.staging          # Staging
.env.production       # Production
```

Beispiel `.env.production`:

``` bash
APP_KEY=production-secret-key-here
APP_NAME="My App"
APP_ENV="production"
APP_DEBUG=false
APP_URL="https://myapp.com"
API_BASE_URL="https://api.myapp.com"
```

### Schritt 2: Umgebungskonfiguration generieren

Generierung aus einer bestimmten Umgebungsdatei:

``` bash
# For production
metro make:env --file=".env.production" --force

# For staging
metro make:env --file=".env.staging" --force
```

### Schritt 3: App erstellen

Erstellen Sie die App mit der entsprechenden Konfiguration:

``` bash
# Development
flutter run

# Production build
metro make:env --file=.env.production --force
flutter build ios
flutter build appbundle
```

<div id="build-time-injection"></div>

## Build-Time-Injektion

Für erhöhte Sicherheit können Sie den APP_KEY zur Build-Zeit injizieren, anstatt ihn im Quellcode einzubetten.

### Generierung mit --dart-define-Modus

``` bash
metro make:env --dart-define
```

Dies generiert `env.g.dart` ohne den eingebetteten APP_KEY.

### Build mit APP_KEY-Injektion

``` bash
# iOS
flutter build ios --dart-define=APP_KEY=your-secret-key

# Android
flutter build appbundle --dart-define=APP_KEY=your-secret-key

# Run
flutter run --dart-define=APP_KEY=your-secret-key
```

Dieser Ansatz hält den APP_KEY aus Ihrem Quellcode heraus, was nützlich ist für:
- CI/CD-Pipelines, bei denen Geheimnisse injiziert werden
- Open-Source-Projekte
- Erhöhte Sicherheitsanforderungen

### Best Practices

1. **`.env` niemals in die Versionskontrolle committen** - Fügen Sie sie zur `.gitignore` hinzu
2. **`.env-example` verwenden** - Committen Sie eine Vorlage ohne sensible Werte
3. **Nach Änderungen neu generieren** - Führen Sie immer `metro make:env --force` nach Änderung der `.env` aus
4. **Verschiedene Schlüssel pro Umgebung** - Verwenden Sie eindeutige APP_KEYs für Entwicklung, Staging und Produktion
