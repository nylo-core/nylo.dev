# LanguageSwitcher

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione")
- Utilizzo
    - [Widget Dropdown](#usage-dropdown "Widget Dropdown")
    - [Modale Bottom Sheet](#usage-bottom-modal "Modale Bottom Sheet")
- [Stile di Animazione](#animation-style "Stile di Animazione")
- [Builder Dropdown Personalizzato](#custom-builder "Builder Dropdown Personalizzato")
- [Azioni sullo Stato](#state-actions "Azioni sullo Stato")
- [Parametri](#parameters "Parametri")
- [Metodi Statici](#methods "Metodi Statici")


<div id="introduction"></div>

## Introduzione

Il widget **LanguageSwitcher** fornisce un modo semplice per gestire il cambio di lingua nei tuoi progetti {{ config('app.name') }}. Rileva automaticamente le lingue disponibili nella tua directory `/lang` e le mostra all'utente.

**Cosa fa LanguageSwitcher?**

- Mostra le lingue disponibili dalla tua directory `/lang`
- Cambia la lingua dell'app quando l'utente ne seleziona una
- Mantiene la lingua selezionata tra i riavvii dell'app
- Aggiorna automaticamente l'interfaccia quando la lingua cambia

> **Nota**: Se la tua app non e' ancora localizzata, scopri come farlo nella documentazione [Localizzazione](/docs/7.x/localization) prima di usare questo widget.

<div id="usage-dropdown"></div>

## Widget Dropdown

Il modo piu' semplice per usare `LanguageSwitcher` e' come dropdown nella barra dell'app:

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    appBar: AppBar(
      title: Text("Settings"),
      actions: [
        LanguageSwitcher(), // Aggiungi alla barra dell'app
      ],
    ),
    body: Center(
      child: Text("Hello World".tr()),
    ),
  );
}
```

Quando l'utente tocca il dropdown, vedra' un elenco delle lingue disponibili. Dopo aver selezionato una lingua, l'app cambiera' automaticamente e aggiornera' l'interfaccia.

<div id="usage-bottom-modal"></div>

## Modale Bottom Sheet

Puoi anche mostrare le lingue in un modale bottom sheet:

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

Il modale bottom sheet mostra un elenco di lingue con un segno di spunta accanto alla lingua attualmente selezionata.

### Personalizzazione del Modale

``` dart
LanguageSwitcher.showBottomModal(
  context,
  height: 300,
  useRootNavigator: true, // Mostra il modale sopra tutte le route, incluse le tab bar
  onLanguageChange: (String languageKey) {
    print('Language changed to: $languageKey');
  },
);
```

<div id="animation-style"></div>

## Stile di Animazione

Il parametro `animationStyle` controlla le animazioni di transizione sia per il trigger del dropdown che per gli elementi della lista nel modale bottom. Sono disponibili quattro preset:

``` dart
// Nessuna animazione
LanguageSwitcher(
  animationStyle: LanguageSwitcherAnimationStyle.none(),
)

// Animazioni sottili e raffinate (consigliate per la maggior parte delle app)
LanguageSwitcher(
  animationStyle: LanguageSwitcherAnimationStyle.subtle(),
)

// Animazioni vivaci e rimbalzanti
LanguageSwitcher(
  animationStyle: LanguageSwitcherAnimationStyle.bouncy(),
)

// Dissolvenza fluida con scala delicata
LanguageSwitcher(
  animationStyle: LanguageSwitcherAnimationStyle.fadeIn(),
)
```

Puoi anche passare un `LanguageSwitcherAnimationStyle()` personalizzato con parametri individuali, o usare `copyWith` per modificare un preset.

Lo stesso parametro `animationStyle` e' accettato da `LanguageSwitcher.showBottomModal`.

<div id="custom-builder"></div>

## Builder Dropdown Personalizzato

Personalizza come appare ogni opzione della lingua nel dropdown:

``` dart
LanguageSwitcher(
  dropdownBuilder: (Map<String, dynamic> language) {
    return Row(
      children: [
        Icon(Icons.language),
        SizedBox(width: 8),
        Text(language['name']), // es., "English"
        // language['locale'] contiene il codice locale, es., "en"
      ],
    );
  },
)
```

### Gestione dei Cambiamenti di Lingua

``` dart
LanguageSwitcher(
  onLanguageChange: (Map<String, dynamic> language) {
    print('Language changed to: ${language['name']}');
    // Esegui azioni aggiuntive al cambio di lingua
  },
)
```

<div id="state-actions"></div>

## Azioni sullo Stato

Controlla il `LanguageSwitcher` in modo programmatico usando `stateActions()`:

``` dart
// Aggiorna l'elenco delle lingue (rilegge le lingue disponibili)
LanguageSwitcher.stateActions().refresh();

// Passa a una lingua tramite codice locale
LanguageSwitcher.stateActions().setLanguage("es");
LanguageSwitcher.stateActions().setLanguage("fr");
```

Utile quando vuoi cambiare la lingua dell'app senza interazione utente, ad esempio dopo il login con una preferenza utente.

<div id="parameters"></div>

## Parametri

| Parametro | Tipo | Predefinito | Descrizione |
|-----------|------|---------|-------------|
| `icon` | `Widget?` | - | Icona personalizzata per il pulsante dropdown |
| `iconEnabledColor` | `Color?` | - | Colore dell'icona del dropdown |
| `iconSize` | `double` | `24` | Dimensione dell'icona del dropdown |
| `dropdownBgColor` | `Color?` | - | Colore di sfondo del menu dropdown |
| `hint` | `Widget?` | - | Widget suggerimento quando nessuna lingua e' selezionata |
| `itemHeight` | `double` | `kMinInteractiveDimension` | Altezza di ogni elemento del dropdown |
| `elevation` | `int` | `8` | Elevazione del menu dropdown |
| `padding` | `EdgeInsetsGeometry?` | - | Padding attorno al dropdown |
| `borderRadius` | `BorderRadius?` | - | Raggio del bordo del menu dropdown |
| `textStyle` | `TextStyle?` | - | Stile del testo per gli elementi del dropdown |
| `langPath` | `String` | `'lang'` | Percorso ai file di lingua negli asset |
| `dropdownBuilder` | `Function(Map<String, dynamic>)?` | - | Builder personalizzato per gli elementi del dropdown |
| `dropdownAlignment` | `AlignmentGeometry` | `AlignmentDirectional.centerStart` | Allineamento degli elementi del dropdown |
| `dropdownOnTap` | `Function()?` | - | Callback quando un elemento del dropdown viene toccato |
| `onTap` | `Function()?` | - | Callback quando il pulsante dropdown viene toccato |
| `onLanguageChange` | `Function(Map<String, dynamic>)?` | - | Callback quando la lingua viene cambiata |

<div id="methods"></div>

## Metodi Statici

### Ottenere la Lingua Corrente

Recupera la lingua attualmente selezionata:

``` dart
Map<String, dynamic>? lang = await LanguageSwitcher.currentLanguage();
// Returns: {"en": "English"} or null if not set
```

### Salvare la Lingua

Salva manualmente una preferenza di lingua:

``` dart
await LanguageSwitcher.storeLanguage(
  object: {"fr": "French"},
);
```

### Cancellare la Lingua

Rimuovi la preferenza di lingua salvata:

``` dart
await LanguageSwitcher.clearLanguage();
```

### Ottenere i Dati della Lingua

Ottieni le informazioni sulla lingua da un codice locale:

``` dart
Map<String, String>? langData = LanguageSwitcher.getLanguageData("en");
// Returns: {"en": "English"}

Map<String, String>? langData = LanguageSwitcher.getLanguageData("fr_CA");
// Returns: {"fr_CA": "French (Canada)"}
```

### Ottenere l'Elenco delle Lingue

Ottieni tutte le lingue disponibili dalla directory `/lang`:

``` dart
List<Map<String, String>> languages = await LanguageSwitcher.getLanguageList();
// Returns: [{"en": "English"}, {"es": "Spanish"}, ...]
```

### Mostrare il Modale Bottom Sheet

Mostra il modale di selezione della lingua:

``` dart
await LanguageSwitcher.showBottomModal(context);

// Con opzioni
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

## Locali Supportati

Il widget `LanguageSwitcher` supporta centinaia di codici locale con nomi leggibili. Alcuni esempi:

| Codice Locale | Nome della Lingua |
|-------------|---------------|
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

L'elenco completo include varianti regionali per la maggior parte delle lingue.
