# LanguageSwitcher

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie")
- Użycie
    - [Widget listy rozwijanej](#usage-dropdown "Widget listy rozwijanej")
    - [Modal dolnego arkusza](#usage-bottom-modal "Modal dolnego arkusza")
- [Niestandardowy builder listy rozwijanej](#custom-builder "Niestandardowy builder listy rozwijanej")
- [Parametry](#parameters "Parametry")
- [Metody statyczne](#methods "Metody statyczne")


<div id="introduction"></div>

## Wprowadzenie

Widget **LanguageSwitcher** zapewnia łatwy sposób obsługi przełączania języków w projektach {{ config('app.name') }}. Automatycznie wykrywa języki dostępne w katalogu `/lang` i wyświetla je użytkownikowi.

**Co robi LanguageSwitcher?**

- Wyświetla dostępne języki z katalogu `/lang`
- Zmienia język aplikacji po wyborze przez użytkownika
- Zapamiętuje wybrany język między uruchomieniami aplikacji
- Automatycznie aktualizuje interfejs po zmianie języka

> **Uwaga**: Jeśli Twoja aplikacja nie jest jeszcze zlokalizowana, dowiedz się, jak to zrobić, w dokumentacji [Lokalizacja](/docs/7.x/localization), zanim użyjesz tego widgetu.

<div id="usage-dropdown"></div>

## Widget listy rozwijanej

Najprostszy sposób użycia `LanguageSwitcher` to lista rozwijana na pasku aplikacji:

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    appBar: AppBar(
      title: Text("Settings"),
      actions: [
        LanguageSwitcher(), // Add to the app bar
      ],
    ),
    body: Center(
      child: Text("Hello World".tr()),
    ),
  );
}
```

Gdy użytkownik dotknie listy rozwijanej, zobaczy listę dostępnych języków. Po wyborze języka aplikacja automatycznie się przełączy i zaktualizuje interfejs.

<div id="usage-bottom-modal"></div>

## Modal dolnego arkusza

Możesz również wyświetlać języki w modalu dolnego arkusza:

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: Center(
      child: MaterialButton(
        child: Text("Change Language"),
        onPressed: () {
          LanguageSwitcher.showBottomModal(context);
        },
      ),
    ),
  );
}
```

Modal dolny wyświetla listę języków ze znacznikiem obok aktualnie wybranego języka.

### Personalizacja wysokości modalu

``` dart
LanguageSwitcher.showBottomModal(
  context,
  height: 300, // Custom height
);
```

<div id="custom-builder"></div>

## Niestandardowy builder listy rozwijanej

Dostosuj wygląd każdej opcji języka w liście rozwijanej:

``` dart
LanguageSwitcher(
  dropdownBuilder: (Map<String, dynamic> language) {
    return Row(
      children: [
        Icon(Icons.language),
        SizedBox(width: 8),
        Text(language['name']), // e.g., "English"
        // language['locale'] contains the locale code, e.g., "en"
      ],
    );
  },
)
```

### Obsługa zmian języka

``` dart
LanguageSwitcher(
  onLanguageChange: (Map<String, dynamic> language) {
    print('Language changed to: ${language['name']}');
    // Perform additional actions when language changes
  },
)
```

<div id="parameters"></div>

## Parametry

| Parametr | Typ | Domyślna wartość | Opis |
|----------|-----|------------------|------|
| `icon` | `Widget?` | - | Niestandardowa ikona przycisku listy rozwijanej |
| `iconEnabledColor` | `Color?` | - | Kolor ikony listy rozwijanej |
| `iconSize` | `double` | `24` | Rozmiar ikony listy rozwijanej |
| `dropdownBgColor` | `Color?` | - | Kolor tła menu listy rozwijanej |
| `hint` | `Widget?` | - | Widget wskazówki, gdy nie wybrano języka |
| `itemHeight` | `double` | `kMinInteractiveDimension` | Wysokość każdego elementu listy rozwijanej |
| `elevation` | `int` | `8` | Elewacja menu listy rozwijanej |
| `padding` | `EdgeInsetsGeometry?` | - | Wypełnienie wokół listy rozwijanej |
| `borderRadius` | `BorderRadius?` | - | Zaokrąglenie krawędzi menu listy rozwijanej |
| `textStyle` | `TextStyle?` | - | Styl tekstu elementów listy rozwijanej |
| `langPath` | `String` | `'lang'` | Ścieżka do plików językowych w zasobach |
| `dropdownBuilder` | `Function(Map<String, dynamic>)?` | - | Niestandardowy builder elementów listy rozwijanej |
| `dropdownAlignment` | `AlignmentGeometry` | `AlignmentDirectional.centerStart` | Wyrównanie elementów listy rozwijanej |
| `dropdownOnTap` | `Function()?` | - | Callback po dotknięciu elementu listy rozwijanej |
| `onTap` | `Function()?` | - | Callback po dotknięciu przycisku listy rozwijanej |
| `onLanguageChange` | `Function(Map<String, dynamic>)?` | - | Callback po zmianie języka |

<div id="methods"></div>

## Metody statyczne

### Pobieranie aktualnego języka

Pobierz aktualnie wybrany język:

``` dart
Map<String, dynamic>? lang = await LanguageSwitcher.currentLanguage();
// Returns: {"en": "English"} or null if not set
```

### Zapisywanie języka

Ręcznie zapisz preferencję językową:

``` dart
await LanguageSwitcher.storeLanguage(
  object: {"fr": "French"},
);
```

### Czyszczenie języka

Usuń zapisaną preferencję językową:

``` dart
await LanguageSwitcher.clearLanguage();
```

### Pobieranie danych językowych

Pobierz informacje o języku na podstawie kodu lokalizacji:

``` dart
Map<String, String>? langData = LanguageSwitcher.getLanguageData("en");
// Returns: {"en": "English"}

Map<String, String>? langData = LanguageSwitcher.getLanguageData("fr_CA");
// Returns: {"fr_CA": "French (Canada)"}
```

### Pobieranie listy języków

Pobierz wszystkie dostępne języki z katalogu `/lang`:

``` dart
List<Map<String, String>> languages = await LanguageSwitcher.getLanguageList();
// Returns: [{"en": "English"}, {"es": "Spanish"}, ...]
```

### Wyświetlanie modalu dolnego

Wyświetl modal wyboru języka:

``` dart
await LanguageSwitcher.showBottomModal(context);

// With custom height
await LanguageSwitcher.showBottomModal(context, height: 400);
```

## Obsługiwane lokalizacje

Widget `LanguageSwitcher` obsługuje setki kodów lokalizacji z czytelnymi nazwami. Kilka przykładów:

| Kod lokalizacji | Nazwa języka |
|-----------------|--------------|
| `en` | English |
| `en_US` | English (United States) |
| `en_GB` | English (United Kingdom) |
| `es` | Spanish |
| `fr` | French |
| `de` | German |
| `zh` | Chinese |
| `zh_Hans` | Chinese (Simplified) |
| `ja` | Japanese |
| `ko` | Korean |
| `ar` | Arabic |
| `hi` | Hindi |
| `pt` | Portuguese |
| `ru` | Russian |

Pełna lista zawiera regionalne warianty dla większości języków.
