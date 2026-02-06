# स्टोरेज

---

<a name="section-1"></a>
- [परिचय](#introduction "स्टोरेज का परिचय")
- NyStorage
  - [वैल्यू सेव करना](#save-values "वैल्यू सेव करना")
  - [वैल्यू पढ़ना](#read-values "वैल्यू प्राप्त करना")
  - [वैल्यू डिलीट करना](#delete-values "वैल्यू डिलीट करना")
  - [स्टोरेज कीज़](#storage-keys "स्टोरेज कीज़")
  - [JSON सेव/पढ़ना](#save-json "JSON सेव और पढ़ना")
  - [TTL (टाइम-टू-लिव)](#ttl-storage "TTL स्टोरेज")
  - [बैच ऑपरेशन्स](#batch-operations "बैच ऑपरेशन्स")
- कलेक्शन्स
  - [परिचय](#introduction-to-collections "कलेक्शन्स का परिचय")
  - [कलेक्शन में जोड़ना](#add-to-a-collection "कलेक्शन में जोड़ना")
  - [कलेक्शन पढ़ना](#read-a-collection "कलेक्शन पढ़ना")
  - [कलेक्शन अपडेट करना](#update-collection "कलेक्शन अपडेट करना")
  - [कलेक्शन से डिलीट करना](#delete-from-collection "कलेक्शन से डिलीट करना")
- Backpack
  - [परिचय](#backpack-storage "Backpack स्टोरेज")
  - [Backpack के साथ पर्सिस्ट करना](#persist-data-with-backpack "Backpack के साथ डेटा पर्सिस्ट करना")
- [सेशन्स](#introduction-to-sessions "सेशन्स")
- मॉडल स्टोरेज
  - [मॉडल सेव](#model-save "मॉडल सेव")
  - [मॉडल कलेक्शन्स](#model-collections "मॉडल कलेक्शन्स")
- [StorageKey एक्सटेंशन रेफ़रेंस](#storage-key-extension-reference "StorageKey एक्सटेंशन रेफ़रेंस")
- [स्टोरेज एक्सेप्शन्स](#storage-exceptions "स्टोरेज एक्सेप्शन्स")
- [लीगेसी माइग्रेशन](#legacy-migration "लीगेसी माइग्रेशन")

<div id="introduction"></div>

## परिचय

{{ config('app.name') }} v7 `NyStorage` क्लास के माध्यम से एक शक्तिशाली स्टोरेज सिस्टम प्रदान करता है। यह यूज़र के डिवाइस पर डेटा को सुरक्षित रूप से स्टोर करने के लिए अंदरूनी तौर पर <a href="https://pub.dev/packages/flutter_secure_storage" target="_BLANK">flutter_secure_storage</a> का उपयोग करता है।

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Save a value
await NyStorage.save("coins", 100);

// Read a value
int? coins = await NyStorage.read<int>('coins'); // 100

// Delete a value
await NyStorage.delete('coins');
```

<div id="save-values"></div>

## वैल्यू सेव करना

`NyStorage.save()` या `storageSave()` हेल्पर का उपयोग करके वैल्यू सेव करें:

``` dart
// Using NyStorage class
await NyStorage.save("username", "Anthony");
await NyStorage.save("score", 1500);
await NyStorage.save("isPremium", true);

// Using helper function
await storageSave("username", "Anthony");
```

### Backpack में एक साथ सेव करें

पर्सिस्टेंट स्टोरेज और इन-मेमोरी Backpack दोनों में स्टोर करें:

``` dart
await NyStorage.save('auth_token', 'abc123', inBackpack: true);

// Now accessible synchronously via Backpack
String? token = Backpack.instance.read('auth_token');
```

<div id="read-values"></div>

## वैल्यू पढ़ना

ऑटोमैटिक टाइप कास्टिंग के साथ वैल्यू पढ़ें:

``` dart
// Read as String (default)
String? username = await NyStorage.read('username');

// Read with type casting
int? score = await NyStorage.read<int>('score');
double? rating = await NyStorage.read<double>('rating');
bool? isPremium = await NyStorage.read<bool>('isPremium');

// With default value
String name = await NyStorage.read('name', defaultValue: 'Guest') ?? 'Guest';

// Using helper function
String? username = await storageRead('username');
int? score = await storageRead<int>('score');
```

<div id="delete-values"></div>

## वैल्यू डिलीट करना

``` dart
// Delete a single key
await NyStorage.delete('username');

// Delete and remove from Backpack too
await NyStorage.delete('auth_token', andFromBackpack: true);

// Delete multiple keys
await NyStorage.deleteMultiple(['key1', 'key2', 'key3']);

// Delete all (with optional exclusions)
await NyStorage.deleteAll();
await NyStorage.deleteAll(excludeKeys: ['auth_token']);
```

<div id="storage-keys"></div>

## स्टोरेज कीज़

अपनी स्टोरेज कीज़ को `lib/config/storage_keys.dart` में व्यवस्थित करें:

``` dart
final class StorageKeysConfig {
  // Auth key for user authentication
  static StorageKey auth = 'SK_AUTH';

  // Keys synced on app boot
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

### StorageKey एक्सटेंशन का उपयोग

`StorageKey` `String` के लिए एक typedef है, और इसमें शक्तिशाली एक्सटेंशन मेथड्स का एक सेट है:

``` dart
// Save
await StorageKeysConfig.coins.save(100);

// Save with Backpack
await StorageKeysConfig.coins.save(100, inBackpack: true);

// Read
int? coins = await StorageKeysConfig.coins.read<int>();

// Read with default value
int? coins = await StorageKeysConfig.coins.fromStorage<int>(defaultValue: 0);

// Save/Read JSON
await StorageKeysConfig.coins.saveJson({"gold": 50, "silver": 200});
Map? data = await StorageKeysConfig.coins.readJson<Map>();

// Delete
await StorageKeysConfig.coins.deleteFromStorage();

// Delete (alias)
await StorageKeysConfig.coins.flush();

// Read from Backpack (synchronous)
int? coins = StorageKeysConfig.coins.fromBackpack<int>();

// Collection operations
await StorageKeysConfig.coins.addToCollection<int>(100);
List<int> allCoins = await StorageKeysConfig.coins.readCollection<int>();
```

<div id="save-json"></div>

## JSON सेव/पढ़ना

JSON डेटा स्टोर और प्राप्त करें:

``` dart
// Save JSON
Map<String, dynamic> user = {
  "name": "Anthony",
  "email": "anthony@example.com",
  "preferences": {"theme": "dark"}
};
await NyStorage.saveJson("user_data", user);

// Read JSON
Map<String, dynamic>? userData = await NyStorage.readJson("user_data");
print(userData?['name']); // "Anthony"
```

<div id="ttl-storage"></div>

## TTL (टाइम-टू-लिव)

{{ config('app.name') }} v7 ऑटोमैटिक एक्सपायरेशन के साथ वैल्यू स्टोर करने का समर्थन करता है:

``` dart
// Save with 1 hour expiration
await NyStorage.saveWithExpiry(
  'session_token',
  'abc123',
  ttl: Duration(hours: 1),
);

// Read (returns null if expired)
String? token = await NyStorage.readWithExpiry<String>('session_token');

// Check remaining time
Duration? remaining = await NyStorage.getTimeToLive('session_token');
if (remaining != null) {
  print('Expires in ${remaining.inMinutes} minutes');
}

// Clean up all expired keys
int removed = await NyStorage.removeExpired();
print('Removed $removed expired keys');
```

<div id="batch-operations"></div>

## बैच ऑपरेशन्स

एकाधिक स्टोरेज ऑपरेशन्स को कुशलतापूर्वक हैंडल करें:

``` dart
// Save multiple values
await NyStorage.saveAll({
  'username': 'Anthony',
  'score': 1500,
  'level': 10,
});

// Read multiple values
Map<String, dynamic?> values = await NyStorage.readMultiple<dynamic>([
  'username',
  'score',
  'level',
]);

// Delete multiple keys
await NyStorage.deleteMultiple(['temp_1', 'temp_2', 'temp_3']);
```

<div id="introduction-to-collections"></div>

## कलेक्शन्स का परिचय

कलेक्शन्स आपको एक कुंजी के तहत आइटम्स की सूची स्टोर करने की अनुमति देते हैं:

``` dart
// Add items to a collection
await NyStorage.addToCollection("favorites", item: "Product A");
await NyStorage.addToCollection("favorites", item: "Product B");

// Read the collection
List<String> favorites = await NyStorage.readCollection<String>("favorites");
// ["Product A", "Product B"]
```

<div id="add-to-a-collection"></div>

## कलेक्शन में जोड़ना

``` dart
// Add item (allows duplicates by default)
await NyStorage.addToCollection("cart_ids", item: 123);

// Prevent duplicates
await NyStorage.addToCollection(
  "cart_ids",
  item: 123,
  allowDuplicates: false,
);

// Save entire collection at once
await NyStorage.saveCollection<int>("cart_ids", [1, 2, 3, 4, 5]);
```

<div id="read-a-collection"></div>

## कलेक्शन पढ़ना

``` dart
// Read collection with type
List<int> cartIds = await NyStorage.readCollection<int>("cart_ids");

// Check if collection is empty
bool isEmpty = await NyStorage.isCollectionEmpty("cart_ids");
```

<div id="update-collection"></div>

## कलेक्शन अपडेट करना

``` dart
// Update item by index
await NyStorage.updateCollectionByIndex<int>(
  0, // index
  (item) => item + 10, // transform function
  key: "scores",
);

// Update items matching a condition
await NyStorage.updateCollectionWhere<Map<String, dynamic>>(
  (item) => item['id'] == 5, // where condition
  key: "products",
  update: (item) {
    item['quantity'] = item['quantity'] + 1;
    return item;
  },
);
```

<div id="delete-from-collection"></div>

## कलेक्शन से डिलीट करना

``` dart
// Delete by index
await NyStorage.deleteFromCollection<String>(0, key: "favorites");

// Delete by value
await NyStorage.deleteValueFromCollection<int>(
  "cart_ids",
  value: 123,
);

// Delete items matching a condition
await NyStorage.deleteFromCollectionWhere<Map<String, dynamic>>(
  (item) => item['expired'] == true,
  key: "coupons",
);

// Delete entire collection
await NyStorage.delete("favorites");
```

<div id="backpack-storage"></div>

## Backpack स्टोरेज

`Backpack` यूज़र के सेशन के दौरान त्वरित सिंक्रोनस एक्सेस के लिए एक हल्का इन-मेमोरी स्टोरेज क्लास है। ऐप बंद होने पर डेटा **पर्सिस्ट नहीं** होता।

### Backpack में सेव करें

``` dart
// Using helper
backpackSave('user_token', 'abc123');
backpackSave('user', userObject);

// Using Backpack directly
Backpack.instance.save('settings', {'darkMode': true});
```

### Backpack से पढ़ें

``` dart
// Using helper
String? token = backpackRead('user_token');
User? user = backpackRead<User>('user');

// Using Backpack directly
var settings = Backpack.instance.read('settings');
```

### Backpack से डिलीट करें

``` dart
backpackDelete('user_token');

// Delete all
backpackDeleteAll();
```

### व्यावहारिक उदाहरण

``` dart
// After login, store token in both persistent and session storage
Future<void> handleLogin(String token) async {
  // Persist for app restarts
  await NyStorage.save('auth_token', token);

  // Also store in Backpack for quick access
  backpackSave('auth_token', token);
}

// In API service, access synchronously
class ApiService extends NyApiService {
  Future<User?> getProfile() async {
    return await network<User>(
      request: (request) => request.get("/profile"),
      bearerToken: backpackRead('auth_token'), // No await needed
    );
  }
}
```

<div id="persist-data-with-backpack"></div>

## Backpack के साथ पर्सिस्ट करना

पर्सिस्टेंट स्टोरेज और Backpack दोनों में एक कॉल में स्टोर करें:

``` dart
// Save to both
await NyStorage.save('user_token', 'abc123', inBackpack: true);

// Now accessible via Backpack (sync) and NyStorage (async)
String? tokenSync = Backpack.instance.read('user_token');
String? tokenAsync = await NyStorage.read('user_token');
```

### स्टोरेज को Backpack में सिंक करना

ऐप बूट पर सभी पर्सिस्टेंट स्टोरेज को Backpack में लोड करें:

``` dart
// In your app provider
await NyStorage.syncToBackpack();

// With overwrite option
await NyStorage.syncToBackpack(overwrite: true);
```

<div id="introduction-to-sessions"></div>

## सेशन्स

सेशन्स संबंधित डेटा को ग्रुप करने के लिए नेम्ड, इन-मेमोरी स्टोरेज प्रदान करते हैं (पर्सिस्ट नहीं होता):

``` dart
// Create/access a session and add data
session('checkout')
    .add('items', ['Product A', 'Product B'])
    .add('total', 99.99)
    .add('coupon', 'SAVE10');

// Or initialize with data
session('checkout', {
  'items': ['Product A', 'Product B'],
  'total': 99.99,
});

// Read session data
List<String>? items = session('checkout').get<List<String>>('items');
double? total = session('checkout').get<double>('total');

// Get all session data
Map<String, dynamic>? checkoutData = session('checkout').data();

// Delete a single value
session('checkout').delete('coupon');

// Clear entire session
session('checkout').clear();
// or
session('checkout').flush();
```

### सेशन्स पर्सिस्ट करना

सेशन्स को पर्सिस्टेंट स्टोरेज में सिंक किया जा सकता है:

``` dart
// Save session to storage
await session('checkout').syncToStorage();

// Restore session from storage
await session('checkout').syncFromStorage();
```

### सेशन उपयोग के मामले

सेशन्स इनके लिए आदर्श हैं:
- मल्टी-स्टेप फ़ॉर्म्स (ऑनबोर्डिंग, चेकआउट)
- अस्थायी यूज़र प्रेफ़रेंसेस
- विज़ार्ड/जर्नी फ़्लोज़
- शॉपिंग कार्ट डेटा

<div id="model-save"></div>

## मॉडल सेव

`Model` बेस क्लास बिल्ट-इन स्टोरेज मेथड्स प्रदान करती है। जब आप कंस्ट्रक्टर में `key` परिभाषित करते हैं, तो मॉडल खुद को सेव कर सकता है:

``` dart
class User extends Model {
  String? name;
  String? email;

  // Define a storage key
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

### मॉडल सेव करना

``` dart
User user = User();
user.name = "Anthony";
user.email = "anthony@example.com";

// Save to persistent storage
await user.save();

// Save to both storage and Backpack
await user.save(inBackpack: true);
```

### मॉडल वापस पढ़ना

``` dart
User? user = await NyStorage.read<User>(User.key);
```

### Backpack में सिंक करना

सिंक्रोनस एक्सेस के लिए स्टोरेज से मॉडल को Backpack में लोड करें:

``` dart
bool found = await User().syncToBackpack();
if (found) {
  User? user = Backpack.instance.read<User>(User.key);
}
```

<div id="model-collections"></div>

## मॉडल कलेक्शन्स

मॉडल्स को कलेक्शन में सेव करें:

``` dart
User userAnthony = User();
userAnthony.name = 'Anthony';
await userAnthony.saveToCollection();

User userKyle = User();
userKyle.name = 'Kyle';
await userKyle.saveToCollection();

// Read back
List<User> users = await NyStorage.readCollection<User>(User.key);
```

<div id="storage-key-extension-reference"></div>

## StorageKey एक्सटेंशन रेफ़रेंस

`StorageKey` `String` के लिए एक typedef है। `NyStorageKeyExt` एक्सटेंशन ये मेथड्स प्रदान करता है:

| मेथड | रिटर्न | विवरण |
|--------|---------|-------------|
| `save(value, {inBackpack})` | `Future` | स्टोरेज में वैल्यू सेव करें |
| `saveJson(value, {inBackpack})` | `Future` | स्टोरेज में JSON वैल्यू सेव करें |
| `read<T>({defaultValue})` | `Future<T?>` | स्टोरेज से वैल्यू पढ़ें |
| `readJson<T>({defaultValue})` | `Future<T?>` | स्टोरेज से JSON वैल्यू पढ़ें |
| `fromStorage<T>({defaultValue})` | `Future<T?>` | read का एलियास |
| `fromBackpack<T>({defaultValue})` | `T?` | Backpack से पढ़ें (सिंक) |
| `toModel<T>()` | `T` | JSON स्ट्रिंग को मॉडल में कन्वर्ट करें |
| `addToCollection<T>(value, {allowDuplicates})` | `Future` | कलेक्शन में आइटम जोड़ें |
| `readCollection<T>()` | `Future<List<T>>` | कलेक्शन पढ़ें |
| `deleteFromStorage({andFromBackpack})` | `Future` | स्टोरेज से डिलीट करें |
| `flush({andFromBackpack})` | `Future` | deleteFromStorage का एलियास |
| `defaultValue<T>(value)` | `Future Function(bool)?` | कुंजी खाली होने पर डिफ़ॉल्ट सेट करें (syncedOnBoot में उपयोग) |

<div id="storage-exceptions"></div>

## स्टोरेज एक्सेप्शन्स

{{ config('app.name') }} v7 टाइप्ड स्टोरेज एक्सेप्शन्स प्रदान करता है:

| एक्सेप्शन | विवरण |
|-----------|-------------|
| `StorageException` | मैसेज और वैकल्पिक कुंजी के साथ बेस एक्सेप्शन |
| `StorageSerializationException` | स्टोरेज के लिए ऑब्जेक्ट सीरियलाइज़ करने में विफल |
| `StorageDeserializationException` | स्टोर किए गए डेटा को डीसीरियलाइज़ करने में विफल |
| `StorageKeyNotFoundException` | स्टोरेज कुंजी नहीं मिली |
| `StorageTimeoutException` | स्टोरेज ऑपरेशन टाइमआउट हो गया |

``` dart
try {
  await NyStorage.read<User>('user');
} on StorageDeserializationException catch (e) {
  print('Failed to load user: ${e.message}');
  print('Expected type: ${e.expectedType}');
}
```

<div id="legacy-migration"></div>

## लीगेसी माइग्रेशन

{{ config('app.name') }} v7 एक नया एन्वेलप स्टोरेज फ़ॉर्मेट उपयोग करता है। यदि आप v6 से अपग्रेड कर रहे हैं, तो आप पुराने डेटा को माइग्रेट कर सकते हैं:

``` dart
// Call during app initialization
int migrated = await NyStorage.migrateToEnvelopeFormat();
print('Migrated $migrated keys to new format');
```

यह लीगेसी फ़ॉर्मेट (अलग `_runtime_type` कुंजियाँ) को नए एन्वेलप फ़ॉर्मेट में कन्वर्ट करता है। माइग्रेशन को कई बार चलाना सुरक्षित है - पहले से माइग्रेट की गई कुंजियाँ छोड़ दी जाती हैं।
