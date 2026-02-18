# Storage

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie do pamieci")
- NyStorage
  - [Zapisywanie wartosci](#save-values "Zapisywanie wartosci")
  - [Odczytywanie wartosci](#read-values "Odczytywanie wartosci")
  - [Usuwanie wartosci](#delete-values "Usuwanie wartosci")
  - [Klucze pamieci](#storage-keys "Klucze pamieci")
  - [Zapis/odczyt JSON](#save-json "Zapis i odczyt JSON")
  - [TTL (czas zycia)](#ttl-storage "Pamiec TTL")
  - [Operacje wsadowe](#batch-operations "Operacje wsadowe")
- Kolekcje
  - [Wprowadzenie](#introduction-to-collections "Wprowadzenie do kolekcji")
  - [Dodawanie do kolekcji](#add-to-a-collection "Dodawanie do kolekcji")
  - [Odczytywanie kolekcji](#read-a-collection "Odczytywanie kolekcji")
  - [Aktualizacja kolekcji](#update-collection "Aktualizacja kolekcji")
  - [Usuwanie z kolekcji](#delete-from-collection "Usuwanie z kolekcji")
- Backpack
  - [Wprowadzenie](#backpack-storage "Pamiec Backpack")
  - [Utrwalanie z Backpack](#persist-data-with-backpack "Utrwalanie danych z Backpack")
- [Sesje](#introduction-to-sessions "Sesje")
- Pamiec modeli
  - [Zapis modelu](#model-save "Zapis modelu")
  - [Kolekcje modeli](#model-collections "Kolekcje modeli")
- [Referencja rozszerzenia StorageKey](#storage-key-extension-reference "Referencja rozszerzenia StorageKey")
- [Wyjatki pamieci](#storage-exceptions "Wyjatki pamieci")
- [Migracja starszych wersji](#legacy-migration "Migracja starszych wersji")

<div id="introduction"></div>

## Wprowadzenie

{{ config('app.name') }} v7 zapewnia potezny system pamieci poprzez klase `NyStorage`. Wykorzystuje <a href="https://pub.dev/packages/flutter_secure_storage" target="_BLANK">flutter_secure_storage</a> pod spodem do bezpiecznego przechowywania danych na urzadzeniu uzytkownika.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Zapisz wartosc
await NyStorage.save("coins", 100);

// Odczytaj wartosc
int? coins = await NyStorage.read<int>('coins'); // 100

// Usun wartosc
await NyStorage.delete('coins');
```

<div id="save-values"></div>

## Zapisywanie wartosci

Zapisuj wartosci za pomoca `NyStorage.save()` lub helpera `storageSave()`:

``` dart
// Uzywajac klasy NyStorage
await NyStorage.save("username", "Anthony");
await NyStorage.save("score", 1500);
await NyStorage.save("isPremium", true);

// Uzywajac funkcji pomocniczej
await storageSave("username", "Anthony");
```

### Jednoczesny zapis do Backpack

Przechowuj zarowno w pamieci trwalej, jak i w pamieci podrecznej Backpack:

``` dart
await NyStorage.save('auth_token', 'abc123', inBackpack: true);

// Teraz dostepne synchronicznie przez Backpack
String? token = Backpack.instance.read('auth_token');
```

<div id="read-values"></div>

## Odczytywanie wartosci

Odczytuj wartosci z automatycznym rzutowaniem typow:

``` dart
// Odczytaj jako String (domyslnie)
String? username = await NyStorage.read('username');

// Odczytaj z rzutowaniem typow
int? score = await NyStorage.read<int>('score');
double? rating = await NyStorage.read<double>('rating');
bool? isPremium = await NyStorage.read<bool>('isPremium');

// Z wartoscia domyslna
String name = await NyStorage.read('name', defaultValue: 'Guest') ?? 'Guest';

// Uzywajac funkcji pomocniczej
String? username = await storageRead('username');
int? score = await storageRead<int>('score');
```

<div id="delete-values"></div>

## Usuwanie wartosci

``` dart
// Usun pojedynczy klucz
await NyStorage.delete('username');

// Usun i usun tez z Backpack
await NyStorage.delete('auth_token', andFromBackpack: true);

// Usun wiele kluczy
await NyStorage.deleteMultiple(['key1', 'key2', 'key3']);

// Usun wszystko (z opcjonalnymi wylaczeniami)
await NyStorage.deleteAll();
await NyStorage.deleteAll(excludeKeys: ['auth_token']);
```

<div id="storage-keys"></div>

## Klucze pamieci

Organizuj swoje klucze pamieci w `lib/config/storage_keys.dart`:

``` dart
final class StorageKeysConfig {
  // Klucz uwierzytelnienia
  static StorageKey auth = 'SK_AUTH';

  // Klucze synchronizowane podczas uruchamiania
  static syncedOnBoot() => () async {
    return [
      coins.defaultValue(0),
      themePreference.defaultValue('light'),
    ];
  };

  static StorageKey coins = 'SK_COINS';
  static StorageKey themePreference = 'SK_THEME_PREFERENCE';
  static StorageKey onboardingComplete = 'SK_ONBOARDING_COMPLETE';
}
```

### Uzycie rozszerzen StorageKey

`StorageKey` jest aliasem typu dla `String` i posiada potezny zestaw metod rozszerzenia:

``` dart
// Zapisz
await StorageKeysConfig.coins.save(100);

// Zapisz z Backpack
await StorageKeysConfig.coins.save(100, inBackpack: true);

// Odczytaj
int? coins = await StorageKeysConfig.coins.read<int>();

// Odczytaj z wartoscia domyslna
int? coins = await StorageKeysConfig.coins.fromStorage<int>(defaultValue: 0);

// Zapisz/odczytaj JSON
await StorageKeysConfig.coins.saveJson({"gold": 50, "silver": 200});
Map? data = await StorageKeysConfig.coins.readJson<Map>();

// Usun
await StorageKeysConfig.coins.deleteFromStorage();

// Usun (alias)
await StorageKeysConfig.coins.flush();

// Odczytaj z Backpack (synchronicznie)
int? coins = StorageKeysConfig.coins.fromBackpack<int>();

// Operacje na kolekcjach
await StorageKeysConfig.coins.addToCollection<int>(100);
List<int> allCoins = await StorageKeysConfig.coins.readCollection<int>();
```

<div id="save-json"></div>

## Zapis/odczyt JSON

Przechowuj i pobieraj dane JSON:

``` dart
// Zapisz JSON
Map<String, dynamic> user = {
  "name": "Anthony",
  "email": "anthony@example.com",
  "preferences": {"theme": "dark"}
};
await NyStorage.saveJson("user_data", user);

// Odczytaj JSON
Map<String, dynamic>? userData = await NyStorage.readJson("user_data");
print(userData?['name']); // "Anthony"
```

<div id="ttl-storage"></div>

## TTL (czas zycia)

{{ config('app.name') }} v7 obsluguje przechowywanie wartosci z automatycznym wygasaniem:

``` dart
// Zapisz z 1-godzinnym wygasaniem
await NyStorage.saveWithExpiry(
  'session_token',
  'abc123',
  ttl: Duration(hours: 1),
);

// Odczytaj (zwraca null jesli wygasl)
String? token = await NyStorage.readWithExpiry<String>('session_token');

// Sprawdz pozostaly czas
Duration? remaining = await NyStorage.getTimeToLive('session_token');
if (remaining != null) {
  print('Wygasa za ${remaining.inMinutes} minut');
}

// Wyczysc wszystkie wygasle klucze
int removed = await NyStorage.removeExpired();
print('Usunieto $removed wygaslych kluczy');
```

<div id="batch-operations"></div>

## Operacje wsadowe

Efektywna obsluga wielu operacji na pamieci:

``` dart
// Zapisz wiele wartosci
await NyStorage.saveAll({
  'username': 'Anthony',
  'score': 1500,
  'level': 10,
});

// Odczytaj wiele wartosci
Map<String, dynamic?> values = await NyStorage.readMultiple<dynamic>([
  'username',
  'score',
  'level',
]);

// Usun wiele kluczy
await NyStorage.deleteMultiple(['temp_1', 'temp_2', 'temp_3']);
```

<div id="introduction-to-collections"></div>

## Wprowadzenie do kolekcji

Kolekcje pozwalaja przechowywac listy elementow pod jednym kluczem:

``` dart
// Dodaj elementy do kolekcji
await NyStorage.addToCollection("favorites", item: "Product A");
await NyStorage.addToCollection("favorites", item: "Product B");

// Odczytaj kolekcje
List<String> favorites = await NyStorage.readCollection<String>("favorites");
// ["Product A", "Product B"]
```

<div id="add-to-a-collection"></div>

## Dodawanie do kolekcji

``` dart
// Dodaj element (domyslnie zezwala na duplikaty)
await NyStorage.addToCollection("cart_ids", item: 123);

// Zapobiegaj duplikatom
await NyStorage.addToCollection(
  "cart_ids",
  item: 123,
  allowDuplicates: false,
);

// Zapisz cala kolekcje naraz
await NyStorage.saveCollection<int>("cart_ids", [1, 2, 3, 4, 5]);
```

<div id="read-a-collection"></div>

## Odczytywanie kolekcji

``` dart
// Odczytaj kolekcje z typem
List<int> cartIds = await NyStorage.readCollection<int>("cart_ids");

// Sprawdz czy kolekcja jest pusta
bool isEmpty = await NyStorage.isCollectionEmpty("cart_ids");
```

<div id="update-collection"></div>

## Aktualizacja kolekcji

``` dart
// Aktualizuj element po indeksie
await NyStorage.updateCollectionByIndex<int>(
  0, // indeks
  (item) => item + 10, // funkcja transformacji
  key: "scores",
);

// Aktualizuj elementy spelniajace warunek
await NyStorage.updateCollectionWhere<Map<String, dynamic>>(
  (item) => item['id'] == 5, // warunek where
  key: "products",
  update: (item) {
    item['quantity'] = item['quantity'] + 1;
    return item;
  },
);
```

<div id="delete-from-collection"></div>

## Usuwanie z kolekcji

``` dart
// Usun po indeksie
await NyStorage.deleteFromCollection<String>(0, key: "favorites");

// Usun po wartosci
await NyStorage.deleteValueFromCollection<int>(
  "cart_ids",
  value: 123,
);

// Usun elementy spelniajace warunek
await NyStorage.deleteFromCollectionWhere<Map<String, dynamic>>(
  (item) => item['expired'] == true,
  key: "coupons",
);

// Usun cala kolekcje
await NyStorage.delete("favorites");
```

<div id="backpack-storage"></div>

## Pamiec Backpack

`Backpack` to lekka klasa pamieci w pamieci operacyjnej do szybkiego synchronicznego dostepu podczas sesji uzytkownika. Dane **nie sa utrwalane** po zamknieciu aplikacji.

### Zapis do Backpack

``` dart
// Uzywajac helpera
backpackSave('user_token', 'abc123');
backpackSave('user', userObject);

// Uzywajac Backpack bezposrednio
Backpack.instance.save('settings', {'darkMode': true});
```

### Odczyt z Backpack

``` dart
// Uzywajac helpera
String? token = backpackRead('user_token');
User? user = backpackRead<User>('user');

// Uzywajac Backpack bezposrednio
var settings = Backpack.instance.read('settings');
```

### Usuwanie z Backpack

``` dart
backpackDelete('user_token');

// Usun wszystko
backpackDeleteAll();
```

### Przyklad praktyczny

``` dart
// Po logowaniu, zapisz token zarowno w trwalej pamieci, jak i w pamieci sesji
Future<void> handleLogin(String token) async {
  // Utrwal na wypadek restartu aplikacji
  await NyStorage.save('auth_token', token);

  // Zapisz tez w Backpack dla szybkiego dostepu
  backpackSave('auth_token', token);
}

// W serwisie API, uzyskaj dostep synchronicznie
class ApiService extends NyApiService {
  Future<User?> getProfile() async {
    return await network<User>(
      request: (request) => request.get("/profile"),
      bearerToken: backpackRead('auth_token'), // Bez await
    );
  }
}
```

<div id="persist-data-with-backpack"></div>

## Utrwalanie z Backpack

Zapisz zarowno w trwalej pamieci, jak i w Backpack jednym wywolaniem:

``` dart
// Zapisz do obu
await NyStorage.save('user_token', 'abc123', inBackpack: true);

// Teraz dostepne przez Backpack (sync) i NyStorage (async)
String? tokenSync = Backpack.instance.read('user_token');
String? tokenAsync = await NyStorage.read('user_token');
```

### Synchronizacja pamieci do Backpack

Zaladuj cala trwala pamiec do Backpack przy uruchamianiu aplikacji:

``` dart
// W providerze aplikacji
await NyStorage.syncToBackpack();

// Z opcja nadpisywania
await NyStorage.syncToBackpack(overwrite: true);
```

<div id="introduction-to-sessions"></div>

## Sesje

Sesje zapewniaja nazwaną pamiec w pamieci operacyjnej do grupowania powiazanych danych (nieutrwalana):

``` dart
// Utworz/uzyskaj dostep do sesji i dodaj dane
session('checkout')
    .add('items', ['Product A', 'Product B'])
    .add('total', 99.99)
    .add('coupon', 'SAVE10');

// Lub zainicjalizuj z danymi
session('checkout', {
  'items': ['Product A', 'Product B'],
  'total': 99.99,
});

// Odczytaj dane sesji
List<String>? items = session('checkout').get<List<String>>('items');
double? total = session('checkout').get<double>('total');

// Pobierz wszystkie dane sesji
Map<String, dynamic>? checkoutData = session('checkout').data();

// Usun pojedyncza wartosc
session('checkout').delete('coupon');

// Wyczysc cala sesje
session('checkout').clear();
// lub
session('checkout').flush();
```

### Utrwalanie sesji

Sesje moga byc synchronizowane z trwala pamiecia:

``` dart
// Zapisz sesje do pamieci
await session('checkout').syncToStorage();

// Przywroc sesje z pamieci
await session('checkout').syncFromStorage();
```

### Przypadki uzycia sesji

Sesje sa idealne do:
- Formularzy wieloetapowych (onboarding, finalizacja zakupu)
- Tymczasowych preferencji uzytkownika
- Przeplywow kreatorow/podrozy
- Danych koszyka zakupowego

<div id="model-save"></div>

## Zapis modelu

Klasa bazowa `Model` udostepnia wbudowane metody pamieci. Gdy zdefiniujesz `key` w konstruktorze, model moze zapisac sam siebie:

``` dart
class User extends Model {
  String? name;
  String? email;

  // Zdefiniuj klucz pamieci
  static StorageKey key = 'user';
  User() : super(key: key);

  User.fromJson(dynamic data) : super(key: key) {
    name = data['name'];
    email = data['email'];
  }

  @override
  Map<String, dynamic> toJson() => {
    "name": name,
    "email": email,
  };
}
```

### Zapisywanie modelu

``` dart
User user = User();
user.name = "Anthony";
user.email = "anthony@example.com";

// Zapisz w trwalej pamieci
await user.save();

// Zapisz zarowno w pamieci, jak i w Backpack
await user.save(inBackpack: true);
```

### Odczytywanie modelu

``` dart
User? user = await NyStorage.read<User>(User.key);
```

### Synchronizacja do Backpack

Zaladuj model z pamieci do Backpack dla synchronicznego dostepu:

``` dart
bool found = await User().syncToBackpack();
if (found) {
  User? user = Backpack.instance.read<User>(User.key);
}
```

<div id="model-collections"></div>

## Kolekcje modeli

Zapisuj modele do kolekcji:

``` dart
User userAnthony = User();
userAnthony.name = 'Anthony';
await userAnthony.saveToCollection();

User userKyle = User();
userKyle.name = 'Kyle';
await userKyle.saveToCollection();

// Odczytaj
List<User> users = await NyStorage.readCollection<User>(User.key);
```

<div id="storage-key-extension-reference"></div>

## Referencja rozszerzenia StorageKey

`StorageKey` jest aliasem typu dla `String`. Rozszerzenie `NyStorageKeyExt` udostepnia nastepujace metody:

| Metoda | Zwraca | Opis |
|--------|---------|-------------|
| `save(value, {inBackpack})` | `Future` | Zapisz wartosc w pamieci |
| `saveJson(value, {inBackpack})` | `Future` | Zapisz wartosc JSON w pamieci |
| `read<T>({defaultValue})` | `Future<T?>` | Odczytaj wartosc z pamieci |
| `readJson<T>({defaultValue})` | `Future<T?>` | Odczytaj wartosc JSON z pamieci |
| `fromStorage<T>({defaultValue})` | `Future<T?>` | Alias dla read |
| `fromBackpack<T>({defaultValue})` | `T?` | Odczytaj z Backpack (sync) |
| `toModel<T>()` | `T` | Konwertuj ciag JSON na model |
| `addToCollection<T>(value, {allowDuplicates})` | `Future` | Dodaj element do kolekcji |
| `readCollection<T>()` | `Future<List<T>>` | Odczytaj kolekcje |
| `deleteFromStorage({andFromBackpack})` | `Future` | Usun z pamieci |
| `flush({andFromBackpack})` | `Future` | Alias dla deleteFromStorage |
| `defaultValue<T>(value)` | `Future Function(bool)?` | Ustaw domyslna wartosc jesli klucz jest pusty (uzywane w syncedOnBoot) |

<div id="storage-exceptions"></div>

## Wyjatki pamieci

{{ config('app.name') }} v7 udostepnia typowane wyjatki pamieci:

| Wyjatek | Opis |
|-----------|-------------|
| `StorageException` | Bazowy wyjatek z wiadomoscia i opcjonalnym kluczem |
| `StorageSerializationException` | Nie udalo sie serializowac obiektu do pamieci |
| `StorageDeserializationException` | Nie udalo sie deserializowac przechowywanych danych |
| `StorageKeyNotFoundException` | Nie znaleziono klucza pamieci |
| `StorageTimeoutException` | Operacja na pamieci przekroczyla czas oczekiwania |

``` dart
try {
  await NyStorage.read<User>('user');
} on StorageDeserializationException catch (e) {
  print('Nie udalo sie zaladowac uzytkownika: ${e.message}');
  print('Oczekiwany typ: ${e.expectedType}');
}
```

<div id="legacy-migration"></div>

## Migracja starszych wersji

{{ config('app.name') }} v7 uzywa nowego formatu pamieci z opakowaniem (envelope). Jesli aktualizujesz z v6, mozesz zmigrowac stare dane:

``` dart
// Wywolaj podczas inicjalizacji aplikacji
int migrated = await NyStorage.migrateToEnvelopeFormat();
print('Zmigrowano $migrated kluczy do nowego formatu');
```

To konwertuje starszy format (oddzielne klucze `_runtime_type`) do nowego formatu z opakowaniem. Migracja jest bezpieczna do wielokrotnego uruchomienia -- juz zmigrowane klucze sa pomijane.