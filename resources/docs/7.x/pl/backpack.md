# Backpack

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie")
- [Podstawowe uzycie](#basic-usage "Podstawowe uzycie")
- [Odczytywanie danych](#reading-data "Odczytywanie danych")
- [Zapisywanie danych](#saving-data "Zapisywanie danych")
- [Usuwanie danych](#deleting-data "Usuwanie danych")
- [Sesje](#sessions "Sesje")
- [Dostep do instancji Nylo](#nylo-instance "Dostep do instancji Nylo")
- [Funkcje pomocnicze](#helper-functions "Funkcje pomocnicze")
- [Integracja z NyStorage](#integration-with-nystorage "Integracja z NyStorage")
- [Przyklady](#examples "Praktyczne przyklady")

<div id="introduction"></div>

## Wprowadzenie

**Backpack** to singletonowy system przechowywania w pamieci w {{ config('app.name') }}. Zapewnia szybki, synchroniczny dostep do danych podczas dzialania aplikacji. W odroznieniu od `NyStorage`, ktory utrwala dane na urzadzeniu, Backpack przechowuje dane w pamieci i sa one czyszczone po zamknieciu aplikacji.

Backpack jest uzywany wewnetrznie przez framework do przechowywania krytycznych instancji, takich jak obiekt aplikacji `Nylo`, `EventBus` i dane uwierzytelniania. Mozesz go rowniez uzywac do przechowywania wlasnych danych, ktore musza byc szybko dostepne bez wywolan asynchronicznych.

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

## Podstawowe uzycie

Backpack uzywa **wzorca singleton** -- uzyskaj do niego dostep poprzez `Backpack.instance`:

``` dart
// Save data
Backpack.instance.save("theme", "dark");

// Read data
String? theme = Backpack.instance.read("theme"); // "dark"

// Check if data exists
bool hasTheme = Backpack.instance.contains("theme"); // true
```

<div id="reading-data"></div>

## Odczytywanie danych

Odczytuj wartosci z Backpack za pomoca metody `read<T>()`. Obsluguje typy generyczne i opcjonalna wartosc domyslna:

``` dart
// Read a String
String? name = Backpack.instance.read<String>("name");

// Read with a default value
String name = Backpack.instance.read<String>("name", defaultValue: "Guest") ?? "Guest";

// Read an int
int? score = Backpack.instance.read<int>("score");
```

Backpack automatycznie deserializuje ciagi JSON do obiektow modeli, gdy podany jest typ:

``` dart
// If a User model is stored as JSON, it will be deserialized
User? user = Backpack.instance.read<User>("current_user");
```

<div id="saving-data"></div>

## Zapisywanie danych

Zapisuj wartosci za pomoca metody `save()`:

``` dart
Backpack.instance.save("api_token", "abc123");
Backpack.instance.save("is_premium", true);
Backpack.instance.save("cart_count", 3);
```

### Dopisywanie danych

Uzyj `append()` do dodawania wartosci do listy przechowywanej pod kluczem:

``` dart
// Append to a list
Backpack.instance.append("recent_searches", "Flutter");
Backpack.instance.append("recent_searches", "Dart");

// Append with a limit (keeps only the last N items)
Backpack.instance.append("recent_searches", "Nylo", limit: 10);
```

<div id="deleting-data"></div>

## Usuwanie danych

### Usuwanie pojedynczego klucza

``` dart
Backpack.instance.delete("api_token");
```

### Usuwanie wszystkich danych

Metoda `deleteAll()` usuwa wszystkie wartosci **z wyjatkiem** zarezerwowanych kluczy frameworka (`nylo` i `event_bus`):

``` dart
Backpack.instance.deleteAll();
```

<div id="sessions"></div>

## Sesje

Backpack udostepnia zarzadzanie sesjami do organizowania danych w nazwane grupy. Jest to przydatne do przechowywania powiazanych danych razem.

### Aktualizacja wartosci sesji

``` dart
Backpack.instance.sessionUpdate("cart", "item_count", 3);
Backpack.instance.sessionUpdate("cart", "total", 29.99);
```

### Pobieranie wartosci sesji

``` dart
int? itemCount = Backpack.instance.sessionGet<int>("cart", "item_count"); // 3
double? total = Backpack.instance.sessionGet<double>("cart", "total"); // 29.99
```

### Usuwanie klucza sesji

``` dart
Backpack.instance.sessionRemove("cart", "item_count");
```

### Czyszczenie calej sesji

``` dart
Backpack.instance.sessionFlush("cart");
```

### Pobieranie wszystkich danych sesji

``` dart
Map<String, dynamic>? cartData = Backpack.instance.sessionData("cart");
// {"item_count": 3, "total": 29.99}
```

<div id="nylo-instance"></div>

## Dostep do instancji Nylo

Backpack przechowuje instancje aplikacji `Nylo`. Mozesz ja pobrac za pomoca:

``` dart
Nylo nylo = Backpack.instance.nylo();
```

Sprawdz, czy instancja Nylo zostala zainicjalizowana:

``` dart
bool isReady = Backpack.instance.isNyloInitialized(); // true
```

<div id="helper-functions"></div>

## Funkcje pomocnicze

{{ config('app.name') }} udostepnia globalne funkcje pomocnicze dla typowych operacji Backpack:

| Funkcja | Opis |
|----------|-------------|
| `backpackRead<T>(key)` | Odczytaj wartosc z Backpack |
| `backpackSave(key, value)` | Zapisz wartosc w Backpack |
| `backpackDelete(key)` | Usun wartosc z Backpack |
| `backpackDeleteAll()` | Usun wszystkie wartosci (zachowuje klucze frameworka) |
| `backpackNylo()` | Pobierz instancje Nylo z Backpack |

### Przyklad

``` dart
// Using helper functions
backpackSave("locale", "en");

String? locale = backpackRead<String>("locale"); // "en"

backpackDelete("locale");

// Access the Nylo instance
Nylo nylo = backpackNylo();
```

<div id="integration-with-nystorage"></div>

## Integracja z NyStorage

Backpack integruje sie z `NyStorage` dla polaczonego trwalego + pamieciowego przechowywania:

``` dart
// Save to both NyStorage (persistent) and Backpack (in-memory)
await NyStorage.save("auth_token", "abc123", inBackpack: true);

// Now accessible synchronously via Backpack
String? token = Backpack.instance.read("auth_token");

// When deleting from NyStorage, also clear from Backpack
await NyStorage.deleteAll(andFromBackpack: true);
```

Ten wzorzec jest przydatny dla danych takich jak tokeny uwierzytelniania, ktore potrzebuja zarowno trwalosci, jak i szybkiego synchronicznego dostepu (np. w interceptorach HTTP).

<div id="examples"></div>

## Przyklady

### Przechowywanie tokenow uwierzytelniania dla zapytan API

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

### Zarzadzanie koszykiem oparte na sesjach

``` dart
// Add items to a cart session
Backpack.instance.sessionUpdate("cart", "items", ["item_1", "item_2"]);
Backpack.instance.sessionUpdate("cart", "total", 49.99);

// Read cart data
Map<String, dynamic>? cart = Backpack.instance.sessionData("cart");

// Clear the cart
Backpack.instance.sessionFlush("cart");
```

### Szybkie flagi funkcjonalnosci

``` dart
// Store feature flags in Backpack for fast access
backpackSave("feature_dark_mode", true);
backpackSave("feature_notifications", false);

// Check a feature flag
bool darkMode = backpackRead<bool>("feature_dark_mode") ?? false;
```
