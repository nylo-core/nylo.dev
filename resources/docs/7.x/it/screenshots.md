# Screenshot
<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione agli Screenshot")
- [Catturare lo Schermo](#capturing-the-screen "Catturare l'intero schermo")
- [Catturare un'Area](#capturing-an-area "Catturare una parte dello schermo con ScreenshotArea")
- [Salvare uno Screenshot](#saving-a-screenshot "Salvare uno screenshot su un disco")
- [Schede degli Store](#store-listings "Dimensionare gli screenshot per App Store e Google Play")
  - [Dimensioni degli Store](#store-sizes "Dimensioni di ScreenshotStore")
- [Nascondere Contenuti](#hiding-content "Nascondere contenuti dagli screenshot")
- [Catturare un Widget](#capturing-a-widget "Catturare un widget senza mostrarlo")
- [Lingue e Temi](#locales-and-themes "Catturare in un'altra lingua o in un altro tema")
- [Da Metro](#from-metro "Fare screenshot da Metro")

<div id="introduction"></div>

## Introduzione

<x-doc-strip label="Richiede" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`Screenshot` cattura un PNG dello schermo della tua app, o di una sua parte:

``` dart
// L'intero schermo, inclusi dialog e sheet
final Screenshot shot = await Screenshot.capture();
await shot.store('reports/checkout.png');

// Una parte dello schermo
final Screenshot receipt = await Screenshot.area('receipt');
await receipt.store('order-1042.png', disk: 'public');
```

Le catture funzionano in ogni modalita' di build, quindi possono alimentare funzionalita' della tua app, come condividere una ricevuta o allegare lo schermo a una segnalazione di bug. [Metro](#from-metro) e i [playbook](/docs/{{ $version }}/playbooks#screenshots) usano lo stesso codice.

Uno `Screenshot` contiene il PNG come `bytes`, con `width` e `height` in pixel fisici.

<div id="capturing-the-screen"></div>

## Catturare lo Schermo

`Screenshot.capture()` legge l'intero schermo dell'app come lo vede l'utente:

``` dart
final Screenshot shot = await Screenshot.capture();
```

| Parametro | Descrizione |
|-----------|-------------|
| `pixelRatio` | La dimensione rispetto ai pixel logici. Per impostazione predefinita e' quella del dispositivo, quindi il PNG ha la risoluzione nativa dello schermo |
| `settle` | Attende il frame successivo prima di catturare (predefinito `true`). Passa `false` quando un frame e' appena stato disegnato |
| `locale` | Mostra lo schermo in questa lingua per la cattura, ad es. `'es'` |
| `theme` | Mostra lo schermo in questo id di tema per la cattura, ad es. `'dark'` |

Un'app costruita con `NyApp.materialApp` cattura esattamente le proprie pagine. Se costruisci il tuo `MaterialApp`, aggiungi un `ScreenshotRoot` al suo `builder`:

``` dart
MaterialApp(
  builder: (context, child) => ScreenshotRoot(child: child!),
)
```

<div id="capturing-an-area"></div>

## Catturare un'Area

Avvolgi una parte di una schermata in una `ScreenshotArea` e dalle un nome:

``` dart
ScreenshotArea(
  name: 'receipt',
  child: ReceiptCard(order: order),
)
```

Poi catturala con quel nome:

``` dart
final Screenshot receipt = await Screenshot.area('receipt');
```

`Screenshot.area` accetta gli stessi parametri `pixelRatio`, `settle`, `locale` e `theme` di `capture`. `Screenshot.areas` elenca i nomi delle aree presenti sullo schermo.

<div id="saving-a-screenshot"></div>

## Salvare uno Screenshot

`store` salva il PNG su un disco di archiviazione file e restituisce il percorso:

``` dart
final Screenshot shot = await Screenshot.capture();

String path = await shot.store('reports/checkout.png');
await shot.store('order-1042.png', disk: 'public');
```

Senza `disk`, salva sul disco predefinito. Per condividere l'immagine o caricarla, leggi `shot.bytes`.

<div id="store-listings"></div>

## Schede degli Store

`forStore` restituisce un nuovo screenshot esattamente alla dimensione richiesta da un app store:

``` dart
final Screenshot listing = await shot.forStore(
  ScreenshotStore.ios69,
  frame: true,
  caption: 'Checkout in one tap',
);
await listing.store('store/ios-6.9/checkout.png');
```

Uno schermo di forma quasi identica riempie la dimensione, e qualsiasi altro viene adattato al suo interno. Con `frame: true`, lo schermo viene disegnato in un contorno di telefono sotto la `caption`.

| Parametro | Descrizione |
|-----------|-------------|
| `frame` | Disegna lo schermo dentro il contorno di un telefono |
| `caption` | Testo sopra il telefono, usato con `frame` |
| `background` | Il `Color` intorno allo schermo |

Usa `framed()` per disegnare il contorno alla dimensione propria dello screenshot:

``` dart
final Screenshot framed = await shot.framed(caption: 'Checkout in one tap');
```

<div id="store-sizes"></div>

### Dimensioni degli Store

| Dimensione | Id | Pixel |
|------|----|--------|
| `ScreenshotStore.ios69` | `ios-6.9` | 1320 x 2868 |
| `ScreenshotStore.ios65` | `ios-6.5` | 1284 x 2778 |
| `ScreenshotStore.ipad13` | `ipad-13` | 2064 x 2752 |
| `ScreenshotStore.playPhone` | `play-phone` | 1080 x 1920 |
| `ScreenshotStore.playTablet` | `play-tablet` | 1600 x 2560 |

`ScreenshotStore.all` elenca ogni dimensione, e `ScreenshotStore.named('ios-6.9')` ne trova una tramite il suo id.

<div id="hiding-content"></div>

## Nascondere Contenuti

Avvolgi qualsiasi contenuto privato in `ScreenshotHidden`. Sullo schermo appare come al solito, e un blocco grigio lo copre in ogni screenshot:

``` dart
ScreenshotHidden(
  child: Text('Account 4021 8890 1177'),
)
```

Passa `replacement` per disegnare un tuo segnaposto al posto del blocco grigio.

<div id="capturing-a-widget"></div>

## Catturare un Widget

`Screenshot.widget` cattura un widget senza mostrarlo, ad esempio una card di condivisione costruita apposta per l'immagine:

``` dart
final Screenshot card = await Screenshot.widget(
  SizedBox(
    width: 400,
    height: 400,
    child: ShareCard(order: order),
  ),
  context: context,
);
```

Il PNG e' grande quanto il widget, quindi avvolgilo in un `SizedBox` per una dimensione esatta. Passa `context` per dare al widget il tema e le localizzazioni di quel contesto, `size` per impostare lo spazio in cui viene disposto e `delay` per dare alle immagini il tempo di caricarsi.

<div id="locales-and-themes"></div>

## Lingue e Temi

Passa `locale` o `theme` per mostrare lo schermo in un altro modo per una sola cattura:

``` dart
final Screenshot spanish = await Screenshot.capture(locale: 'es');
final Screenshot dark = await Screenshot.area('receipt', theme: 'dark');
```

L'app cambia sul posto, mantenendo la pagina, il suo stato e lo stack di navigazione, e torna indietro subito dopo. Non viene salvato nulla, quindi le scelte dell'utente restano invariate. Una lingua richiede un file di lingua nei tuoi asset, e un tema richiede un id di tema registrato.

<div id="from-metro"></div>

## Da Metro

Salva uno screenshot della tua app in esecuzione dal terminale:

``` bash
metro live:run screenshot --open
metro live:run screenshot --area receipt
metro live:run screenshot --store ios-6.9 --frame
```

Vedi [Metro Live](/docs/{{ $version }}/metro-live#screenshots) per ogni opzione. Un [playbook](/docs/{{ $version }}/playbooks#screenshots) puo' fare screenshot di un intero percorso, in ogni lingua e tema.
