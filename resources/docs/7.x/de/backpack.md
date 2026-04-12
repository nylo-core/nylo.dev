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
- [Beispiele](#examples "Beispiele")

<div id="introduction"></div>

## Einleitung

**Backpack** ist ein In-Memory-Singleton-Speichersystem in {{ config('app.name') }}. Es bietet schnellen, synchronen Zugriff auf Daten waehrend der Laufzeit Ihrer App. Im Gegensatz zu `NyStorage`, das Daten persistent auf dem Geraet speichert, haelt Backpack Daten im Arbeitsspeicher und wird beim Schliessen der App geleert.

Backpack wird intern vom Framework verwendet, um wichtige Instanzen wie das `Nylo`-App-Objekt, den `EventBus` und Authentifizierungsdaten zu speichern. Sie koennen es auch verwenden, um eigene Daten zu speichern, auf die schnell und ohne asynchrone Aufrufe zugegriffen werden soll.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Einen Wert speichern
Backpack.instance.save("user_name", "Anthony");

// Einen Wert lesen (synchron)
String? name = Backpack.instance.read("user_name");

// Einen Wert loeschen
Backpack.instance.delete("user_name");
```

<div id="basic-usage"></div>

## Grundlegende Verwendung

Backpack verwendet das **Singleton-Muster** -- greifen Sie ueber `Backpack.instance` darauf zu:

``` dart
// Daten speichern
Backpack.instance.save("theme", "dark");

// Daten lesen
String? theme = Backpack.instance.read("theme"); // "dark"

// Pruefen, ob Daten vorhanden sind
bool hasTheme = Backpack.instance.contains("theme"); // true
```

<div id="reading-data"></div>

## Daten lesen

Lesen Sie Werte aus Backpack mit der Methode `read<T>()`. Sie unterstuetzt generische Typen und einen optionalen Standardwert:

``` dart
// Einen String lesen
String? name = Backpack.instance.read<String>("name");

// Mit einem Standardwert lesen
String name = Backpack.instance.read<String>("name", defaultValue: "Guest") ?? "Guest";

// Einen int lesen
int? score = Backpack.instance.read<int>("score");
```

Backpack deserialisiert gespeicherte Werte automatisch zu Model-Objekten, wenn ein Typ angegeben wird. Dies funktioniert sowohl fuer JSON-Strings als auch fuer rohe `Map<String, dynamic>`-Werte:

``` dart
// Wenn ein User-Model als JSON-String gespeichert ist, wird es deserialisiert
User? user = Backpack.instance.read<User>("current_user");

// Wenn eine rohe Map gespeichert wurde (z. B. ueber syncKeys von NyStorage), wird sie
// ebenfalls automatisch beim Lesen in das typisierte Model deserialisiert
Backpack.instance.save("current_user", {"name": "Alice", "age": 30});
User? user = Backpack.instance.read<User>("current_user"); // gibt ein User-Objekt zurueck
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
// Zu einer Liste hinzufuegen
Backpack.instance.append("recent_searches", "Flutter");
Backpack.instance.append("recent_searches", "Dart");

// Mit einem Limit hinzufuegen (behaelt nur die letzten N Eintraege)
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
// Hilfsfunktionen verwenden
backpackSave("locale", "en");

String? locale = backpackRead<String>("locale"); // "en"

backpackDelete("locale");

// Auf die Nylo-Instanz zugreifen
Nylo nylo = backpackNylo();
```

<div id="integration-with-nystorage"></div>

## Integration mit NyStorage

Backpack laesst sich mit `NyStorage` fuer kombinierte persistente und In-Memory-Speicherung integrieren:

``` dart
// In NyStorage (persistent) und Backpack (In-Memory) speichern
await NyStorage.save("auth_token", "abc123", inBackpack: true);

// Jetzt synchron ueber Backpack zugaenglich
String? token = Backpack.instance.read("auth_token");

// Beim Loeschen aus NyStorage auch aus Backpack entfernen
await NyStorage.deleteAll(andFromBackpack: true);
```

Dieses Muster ist nuetzlich fuer Daten wie Authentifizierungstoken, die sowohl Persistenz als auch schnellen synchronen Zugriff benoetigen (z. B. in HTTP-Interceptoren).

<div id="examples"></div>

## Beispiele

### Auth-Token fuer API-Anfragen speichern

``` dart
// In Ihrem Auth-Interceptor
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
// Artikel zu einer Warenkorb-Sitzung hinzufuegen
Backpack.instance.sessionUpdate("cart", "items", ["item_1", "item_2"]);
Backpack.instance.sessionUpdate("cart", "total", 49.99);

// Warenkorb-Daten lesen
Map<String, dynamic>? cart = Backpack.instance.sessionData("cart");

// Warenkorb leeren
Backpack.instance.sessionFlush("cart");
```

### Schnelle Feature-Flags

``` dart
// Feature-Flags fuer schnellen Zugriff in Backpack speichern
backpackSave("feature_dark_mode", true);
backpackSave("feature_notifications", false);

// Einen Feature-Flag pruefen
bool darkMode = backpackRead<bool>("feature_dark_mode") ?? false;
```
