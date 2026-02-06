# Backpack

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- [Grundlegende Verwendung](#basic-usage "Grundlegende Verwendung")
- [Daten lesen](#reading-data "Daten lesen")
- [Daten speichern](#saving-data "Daten speichern")
- [Daten loeschen](#deleting-data "Daten loeschen")
- [Sitzungen](#sessions "Sitzungen")
- [Zugriff auf die Nylo-Instanz](#nylo-instance "Zugriff auf die Nylo-Instanz")
- [Hilfsfunktionen](#helper-functions "Hilfsfunktionen")
- [Integration mit NyStorage](#integration-with-nystorage "Integration mit NyStorage")
- [Beispiele](#examples "Praktische Beispiele")

<div id="introduction"></div>

## Einleitung

**Backpack** ist ein In-Memory-Singleton-Speichersystem in {{ config('app.name') }}. Es bietet schnellen, synchronen Zugriff auf Daten waehrend der Laufzeit Ihrer App. Im Gegensatz zu `NyStorage`, das Daten persistent auf dem Geraet speichert, haelt Backpack Daten im Arbeitsspeicher und wird beim Schliessen der App geleert.

Backpack wird intern vom Framework verwendet, um wichtige Instanzen wie das `Nylo`-App-Objekt, den `EventBus` und Authentifizierungsdaten zu speichern. Sie koennen es auch verwenden, um eigene Daten zu speichern, auf die schnell und ohne asynchrone Aufrufe zugegriffen werden soll.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Save a value
Backpack.instance.save("user_name", "Anthony");

// Read a value (synchronous)
String? name = Backpack.instance.read("user_name");

// Delete a value
Backpack.instance.delete("user_name");
```

<div id="basic-usage"></div>

## Grundlegende Verwendung

Backpack verwendet das **Singleton-Muster** -- greifen Sie ueber `Backpack.instance` darauf zu:

``` dart
// Save data
Backpack.instance.save("theme", "dark");

// Read data
String? theme = Backpack.instance.read("theme"); // "dark"

// Check if data exists
bool hasTheme = Backpack.instance.contains("theme"); // true
```

<div id="reading-data"></div>

## Daten lesen

Lesen Sie Werte aus Backpack mit der Methode `read<T>()`. Sie unterstuetzt generische Typen und einen optionalen Standardwert:

``` dart
// Read a String
String? name = Backpack.instance.read<String>("name");

// Read with a default value
String name = Backpack.instance.read<String>("name", defaultValue: "Guest") ?? "Guest";

// Read an int
int? score = Backpack.instance.read<int>("score");
```

Backpack deserialisiert JSON-Strings automatisch zu Model-Objekten, wenn ein Typ angegeben wird:

``` dart
// If a User model is stored as JSON, it will be deserialized
User? user = Backpack.instance.read<User>("current_user");
```

<div id="saving-data"></div>

## Daten speichern

Speichern Sie Werte mit der Methode `save()`:

``` dart
Backpack.instance.save("api_token", "abc123");
Backpack.instance.save("is_premium", true);
Backpack.instance.save("cart_count", 3);
```

### Daten anfuegen

Verwenden Sie `append()`, um Werte zu einer Liste unter einem Schluessel hinzuzufuegen:

``` dart
// Append to a list
Backpack.instance.append("recent_searches", "Flutter");
Backpack.instance.append("recent_searches", "Dart");

// Append with a limit (keeps only the last N items)
Backpack.instance.append("recent_searches", "Nylo", limit: 10);
```

<div id="deleting-data"></div>

## Daten loeschen

### Einzelnen Schluessel loeschen

``` dart
Backpack.instance.delete("api_token");
```

### Alle Daten loeschen

Die Methode `deleteAll()` entfernt alle Werte **ausser** den reservierten Framework-Schluesseln (`nylo` und `event_bus`):

``` dart
Backpack.instance.deleteAll();
```

<div id="sessions"></div>

## Sitzungen

Backpack bietet Sitzungsverwaltung zur Organisation von Daten in benannten Gruppen. Dies ist nuetzlich, um zusammengehoerige Daten gemeinsam zu speichern.

### Sitzungswert aktualisieren

``` dart
Backpack.instance.sessionUpdate("cart", "item_count", 3);
Backpack.instance.sessionUpdate("cart", "total", 29.99);
```

### Sitzungswert abrufen

``` dart
int? itemCount = Backpack.instance.sessionGet<int>("cart", "item_count"); // 3
double? total = Backpack.instance.sessionGet<double>("cart", "total"); // 29.99
```

### Sitzungsschluessel entfernen

``` dart
Backpack.instance.sessionRemove("cart", "item_count");
```

### Gesamte Sitzung leeren

``` dart
Backpack.instance.sessionFlush("cart");
```

### Alle Sitzungsdaten abrufen

``` dart
Map<String, dynamic>? cartData = Backpack.instance.sessionData("cart");
// {"item_count": 3, "total": 29.99}
```

<div id="nylo-instance"></div>

## Zugriff auf die Nylo-Instanz

Backpack speichert die `Nylo`-Anwendungsinstanz. Sie koennen sie wie folgt abrufen:

``` dart
Nylo nylo = Backpack.instance.nylo();
```

Pruefen Sie, ob die Nylo-Instanz initialisiert wurde:

``` dart
bool isReady = Backpack.instance.isNyloInitialized(); // true
```

<div id="helper-functions"></div>

## Hilfsfunktionen

{{ config('app.name') }} stellt globale Hilfsfunktionen fuer gaengige Backpack-Operationen bereit:

| Funktion | Beschreibung |
|----------|-------------|
| `backpackRead<T>(key)` | Einen Wert aus Backpack lesen |
| `backpackSave(key, value)` | Einen Wert in Backpack speichern |
| `backpackDelete(key)` | Einen Wert aus Backpack loeschen |
| `backpackDeleteAll()` | Alle Werte loeschen (Framework-Schluessel bleiben erhalten) |
| `backpackNylo()` | Die Nylo-Instanz aus Backpack abrufen |

### Beispiel

``` dart
// Using helper functions
backpackSave("locale", "en");

String? locale = backpackRead<String>("locale"); // "en"

backpackDelete("locale");

// Access the Nylo instance
Nylo nylo = backpackNylo();
```

<div id="integration-with-nystorage"></div>

## Integration mit NyStorage

Backpack laesst sich mit `NyStorage` fuer kombinierte persistente und In-Memory-Speicherung integrieren:

``` dart
// Save to both NyStorage (persistent) and Backpack (in-memory)
await NyStorage.save("auth_token", "abc123", inBackpack: true);

// Now accessible synchronously via Backpack
String? token = Backpack.instance.read("auth_token");

// When deleting from NyStorage, also clear from Backpack
await NyStorage.deleteAll(andFromBackpack: true);
```

Dieses Muster ist nuetzlich fuer Daten wie Authentifizierungstoken, die sowohl Persistenz als auch schnellen synchronen Zugriff benoetigen (z. B. in HTTP-Interceptoren).

<div id="examples"></div>

## Beispiele

### Auth-Token fuer API-Anfragen speichern

``` dart
// In your auth interceptor
class BearerAuthInterceptor extends Interceptor {
  @override
  void onRequest(RequestOptions options, RequestInterceptorHandler handler) {
    String? userToken = Backpack.instance.read(StorageKeysConfig.auth);

    if (userToken != null) {
      options.headers.addAll({"Authorization": "Bearer $userToken"});
    }

    return super.onRequest(options, handler);
  }
}
```

### Sitzungsbasierte Warenkorbverwaltung

``` dart
// Add items to a cart session
Backpack.instance.sessionUpdate("cart", "items", ["item_1", "item_2"]);
Backpack.instance.sessionUpdate("cart", "total", 49.99);

// Read cart data
Map<String, dynamic>? cart = Backpack.instance.sessionData("cart");

// Clear the cart
Backpack.instance.sessionFlush("cart");
```

### Schnelle Feature-Flags

``` dart
// Store feature flags in Backpack for fast access
backpackSave("feature_dark_mode", true);
backpackSave("feature_notifications", false);

// Check a feature flag
bool darkMode = backpackRead<bool>("feature_dark_mode") ?? false;
```
