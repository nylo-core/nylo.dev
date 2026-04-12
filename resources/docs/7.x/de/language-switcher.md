# LanguageSwitcher

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- Verwendung
    - [Dropdown-Widget](#usage-dropdown "Dropdown-Widget")
    - [Bottom Sheet Modal](#usage-bottom-modal "Bottom Sheet Modal")
- [Animationsstil](#animation-style "Animationsstil")
- [Benutzerdefinierter Dropdown-Builder](#custom-builder "Benutzerdefinierter Dropdown-Builder")
- [State-Aktionen](#state-actions "State-Aktionen")
- [Parameter](#parameters "Parameter")
- [Statische Methoden](#methods "Statische Methoden")


<div id="introduction"></div>

## Einleitung

Das **LanguageSwitcher**-Widget bietet eine einfache Möglichkeit, den Sprachwechsel in Ihren {{ config('app.name') }}-Projekten zu handhaben. Es erkennt automatisch die verfügbaren Sprachen in Ihrem `/lang`-Verzeichnis und zeigt sie dem Benutzer an.

**Was macht LanguageSwitcher?**

- Zeigt verfügbare Sprachen aus Ihrem `/lang`-Verzeichnis an
- Wechselt die App-Sprache, wenn der Benutzer eine auswählt
- Speichert die ausgewählte Sprache über App-Neustarts hinweg
- Aktualisiert automatisch die UI, wenn die Sprache gewechselt wird

> **Hinweis**: Wenn Ihre App noch nicht lokalisiert ist, erfahren Sie in der [Lokalisierung](/docs/7.x/localization)-Dokumentation, wie Sie dies tun können, bevor Sie dieses Widget verwenden.

<div id="usage-dropdown"></div>

## Dropdown-Widget

Die einfachste Art, `LanguageSwitcher` zu verwenden, ist als Dropdown in Ihrer App-Leiste:

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    appBar: AppBar(
      title: Text("Settings"),
      actions: [
        LanguageSwitcher(), // Zur App-Leiste hinzufuegen
      ],
    ),
    body: Center(
      child: Text("Hello World".tr()),
    ),
  );
}
```

Wenn der Benutzer auf das Dropdown tippt, sieht er eine Liste der verfügbaren Sprachen. Nach Auswahl einer Sprache wechselt die App automatisch und aktualisiert die UI.

<div id="usage-bottom-modal"></div>

## Bottom Sheet Modal

Sie können Sprachen auch in einem Bottom Sheet Modal anzeigen:

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

Das Bottom Modal zeigt eine Liste von Sprachen mit einem Häkchen neben der aktuell ausgewählten Sprache an.

### Modal anpassen

``` dart
LanguageSwitcher.showBottomModal(
  context,
  height: 300,
  useRootNavigator: true, // Modal ueber allen Routen anzeigen, auch Tabbars
  onLanguageChange: (String languageKey) {
    print('Language changed to: $languageKey');
  },
);
```

<div id="animation-style"></div>

## Animationsstil

Der Parameter `animationStyle` steuert die Uebergangsanimationen fuer den Dropdown-Trigger und die Modal-Listenelemente. Vier Voreinstellungen sind verfuegbar:

``` dart
// Keine Animationen
LanguageSwitcher(
  animationStyle: LanguageSwitcherAnimationStyle.none(),
)

// Dezente, verfeinerte Animationen (empfohlen fuer die meisten Apps)
LanguageSwitcher(
  animationStyle: LanguageSwitcherAnimationStyle.subtle(),
)

// Verspielt, federnde Animationen
LanguageSwitcher(
  animationStyle: LanguageSwitcherAnimationStyle.bouncy(),
)

// Sanftes Einblenden mit leichter Skalierung
LanguageSwitcher(
  animationStyle: LanguageSwitcherAnimationStyle.fadeIn(),
)
```

Sie koennen auch einen benutzerdefinierten `LanguageSwitcherAnimationStyle()` mit einzelnen Parametern uebergeben oder `copyWith` verwenden, um eine Voreinstellung anzupassen.

Der gleiche Parameter `animationStyle` wird von `LanguageSwitcher.showBottomModal` akzeptiert.

<div id="custom-builder"></div>

## Benutzerdefinierter Dropdown-Builder

Passen Sie an, wie jede Sprachoption im Dropdown angezeigt wird:

``` dart
LanguageSwitcher(
  dropdownBuilder: (Map<String, dynamic> language) {
    return Row(
      children: [
        Icon(Icons.language),
        SizedBox(width: 8),
        Text(language['name']), // z.B. "English"
        // language['locale'] enthaelt den Locale-Code, z.B. "en"
      ],
    );
  },
)
```

### Sprachwechsel behandeln

``` dart
LanguageSwitcher(
  onLanguageChange: (Map<String, dynamic> language) {
    print('Language changed to: ${language['name']}');
    // Weitere Aktionen ausfuehren, wenn die Sprache wechselt
  },
)
```

<div id="state-actions"></div>

## State-Aktionen

Steuern Sie den `LanguageSwitcher` programmatisch mit `stateActions()`:

``` dart
// Sprachliste aktualisieren (verfuegbare Sprachen neu laden)
LanguageSwitcher.stateActions().refresh();

// Sprache per Locale-Code wechseln
LanguageSwitcher.stateActions().setLanguage("es");
LanguageSwitcher.stateActions().setLanguage("fr");
```

Dies ist nuetzlich, wenn Sie die App-Sprache ohne Benutzerinteraktion aendern moechten, beispielsweise nach dem Einloggen mit einer gespeicherten Benutzereinstellung.

<div id="parameters"></div>

## Parameter

| Parameter | Typ | Standard | Beschreibung |
|-----------|-----|---------|-------------|
| `icon` | `Widget?` | - | Benutzerdefiniertes Symbol für den Dropdown-Button |
| `iconEnabledColor` | `Color?` | - | Farbe des Dropdown-Symbols |
| `iconSize` | `double` | `24` | Größe des Dropdown-Symbols |
| `dropdownBgColor` | `Color?` | - | Hintergrundfarbe des Dropdown-Menüs |
| `hint` | `Widget?` | - | Hinweis-Widget, wenn keine Sprache ausgewählt ist |
| `itemHeight` | `double` | `kMinInteractiveDimension` | Höhe jedes Dropdown-Elements |
| `elevation` | `int` | `8` | Erhebung des Dropdown-Menüs |
| `padding` | `EdgeInsetsGeometry?` | - | Abstand um das Dropdown |
| `borderRadius` | `BorderRadius?` | - | Rahmenradius des Dropdown-Menüs |
| `textStyle` | `TextStyle?` | - | Textstil für Dropdown-Elemente |
| `langPath` | `String` | `'lang'` | Pfad zu Sprachdateien in Assets |
| `dropdownBuilder` | `Function(Map<String, dynamic>)?` | - | Benutzerdefinierter Builder für Dropdown-Elemente |
| `dropdownAlignment` | `AlignmentGeometry` | `AlignmentDirectional.centerStart` | Ausrichtung der Dropdown-Elemente |
| `dropdownOnTap` | `Function()?` | - | Callback beim Tippen auf ein Dropdown-Element |
| `onTap` | `Function()?` | - | Callback beim Tippen auf den Dropdown-Button |
| `onLanguageChange` | `Function(Map<String, dynamic>)?` | - | Callback bei Sprachwechsel |

<div id="methods"></div>

## Statische Methoden

### Aktuelle Sprache abrufen

Rufen Sie die aktuell ausgewählte Sprache ab:

``` dart
Map<String, dynamic>? lang = await LanguageSwitcher.currentLanguage();
// Gibt zurueck: {"en": "English"} oder null, wenn nicht gesetzt
```

### Sprache speichern

Spracheinstellung manuell speichern:

``` dart
await LanguageSwitcher.storeLanguage(
  object: {"fr": "French"},
);
```

### Sprache löschen

Gespeicherte Spracheinstellung entfernen:

``` dart
await LanguageSwitcher.clearLanguage();
```

### Sprachdaten abrufen

Sprachinformationen aus einem Locale-Code abrufen:

``` dart
Map<String, String>? langData = LanguageSwitcher.getLanguageData("en");
// Gibt zurueck: {"en": "English"}

Map<String, String>? langData = LanguageSwitcher.getLanguageData("fr_CA");
// Gibt zurueck: {"fr_CA": "French (Canada)"}
```

### Sprachliste abrufen

Alle verfügbaren Sprachen aus dem `/lang`-Verzeichnis abrufen:

``` dart
List<Map<String, String>> languages = await LanguageSwitcher.getLanguageList();
// Gibt zurueck: [{"en": "English"}, {"es": "Spanish"}, ...]
```

### Bottom Modal anzeigen

Das Sprachauswahl-Modal anzeigen:

``` dart
await LanguageSwitcher.showBottomModal(context);

// Mit Optionen
await LanguageSwitcher.showBottomModal(
  context,
  height: 400,
  useRootNavigator: true,
  onLanguageChange: (String languageKey) {
    print('Language changed to: $languageKey');
  },
  animationStyle: LanguageSwitcherAnimationStyle.subtle(),
);
```

## Unterstützte Locales

Das `LanguageSwitcher`-Widget unterstützt Hunderte von Locale-Codes mit lesbaren Namen. Einige Beispiele:

| Locale-Code | Sprachname |
|-------------|------------|
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

Die vollständige Liste umfasst regionale Varianten für die meisten Sprachen.
