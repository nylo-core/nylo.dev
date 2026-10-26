# Screenshots
<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einführung in Screenshots")
- [Den Bildschirm aufnehmen](#capturing-the-screen "Den gesamten Bildschirm aufnehmen")
- [Einen Bereich aufnehmen](#capturing-an-area "Einen Teil des Bildschirms mit ScreenshotArea aufnehmen")
- [Einen Screenshot speichern](#saving-a-screenshot "Einen Screenshot auf einer Disk speichern")
- [Store-Einträge](#store-listings "Screenshots für App Store und Google Play dimensionieren")
  - [Store-Größen](#store-sizes "ScreenshotStore-Größen")
- [Inhalte ausblenden](#hiding-content "Inhalte in Screenshots ausblenden")
- [Ein Widget aufnehmen](#capturing-a-widget "Ein Widget aufnehmen, ohne es anzuzeigen")
- [Sprachen und Themes](#locales-and-themes "In einer anderen Sprache oder einem anderen Theme aufnehmen")
- [Über Metro](#from-metro "Screenshots über Metro aufnehmen")

<div id="introduction"></div>

## Einleitung

<x-doc-strip label="Voraussetzung" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`Screenshot` erstellt ein PNG vom Bildschirm Ihrer App oder von einem Teil davon:

``` dart
// Der gesamte Bildschirm, einschließlich Dialogen und Sheets
final Screenshot shot = await Screenshot.capture();
await shot.store('reports/checkout.png');

// Ein Teil des Bildschirms
final Screenshot receipt = await Screenshot.area('receipt');
await receipt.store('order-1042.png', disk: 'public');
```

Aufnahmen funktionieren in jedem Build-Modus, sodass sie Funktionen in Ihrer App antreiben können, etwa das Teilen eines Belegs oder das Anhängen des Bildschirms an einen Fehlerbericht. [Metro](#from-metro) und [Playbooks](/docs/{{ $version }}/playbooks#screenshots) verwenden denselben Code.

Ein `Screenshot` hält das PNG als `bytes`, mit seiner `width` und `height` in physischen Pixeln.

<div id="capturing-the-screen"></div>

## Den Bildschirm aufnehmen

`Screenshot.capture()` liest den gesamten Bildschirm der App so, wie der Benutzer ihn sieht:

``` dart
final Screenshot shot = await Screenshot.capture();
```

| Parameter | Beschreibung |
|-----------|--------------|
| `pixelRatio` | Die Größe relativ zu logischen Pixeln. Standardmäßig die des Geräts, sodass das PNG die native Auflösung des Bildschirms hat |
| `settle` | Vor der Aufnahme auf den nächsten Frame warten (Standard `true`). Übergeben Sie `false`, wenn gerade ein Frame gezeichnet wurde |
| `locale` | Den Bildschirm für die Aufnahme in dieser Sprache anzeigen, z. B. `'es'` |
| `theme` | Den Bildschirm für die Aufnahme in dieser Theme-ID anzeigen, z. B. `'dark'` |

Eine mit `NyApp.materialApp` gebaute App nimmt genau ihre Seiten auf. Wenn Sie Ihre eigene `MaterialApp` bauen, fügen Sie ihrem `builder` einen `ScreenshotRoot` hinzu:

``` dart
MaterialApp(
  builder: (context, child) => ScreenshotRoot(child: child!),
)
```

<div id="capturing-an-area"></div>

## Einen Bereich aufnehmen

Umschließen Sie einen Teil eines Bildschirms mit einer `ScreenshotArea` und geben Sie ihr einen Namen:

``` dart
ScreenshotArea(
  name: 'receipt',
  child: ReceiptCard(order: order),
)
```

Nehmen Sie ihn dann unter diesem Namen auf:

``` dart
final Screenshot receipt = await Screenshot.area('receipt');
```

`Screenshot.area` akzeptiert dieselben Parameter `pixelRatio`, `settle`, `locale` und `theme` wie `capture`. `Screenshot.areas` listet die Namen der Bereiche auf dem Bildschirm auf.

<div id="saving-a-screenshot"></div>

## Einen Screenshot speichern

`store` speichert das PNG auf einer Dateispeicher-Disk und gibt den Pfad zurück:

``` dart
final Screenshot shot = await Screenshot.capture();

String path = await shot.store('reports/checkout.png');
await shot.store('order-1042.png', disk: 'public');
```

Ohne `disk` wird auf der Standard-Disk gespeichert. Um das Bild zu teilen oder hochzuladen, lesen Sie `shot.bytes`.

<div id="store-listings"></div>

## Store-Einträge

`forStore` gibt einen neuen Screenshot in exakt der Größe zurück, die ein App Store verlangt:

``` dart
final Screenshot listing = await shot.forStore(
  ScreenshotStore.ios69,
  frame: true,
  caption: 'Checkout in one tap',
);
await listing.store('store/ios-6.9/checkout.png');
```

Ein Bildschirm mit nahezu gleichem Seitenverhältnis füllt die Größe aus, jeder andere wird hineingepasst. Mit `frame: true` wird der Bildschirm unter der `caption` in einem Handy-Umriss gezeichnet.

| Parameter | Beschreibung |
|-----------|--------------|
| `frame` | Den Bildschirm in einem Handy-Umriss zeichnen |
| `caption` | Text über dem Handy, zusammen mit `frame` verwendet |
| `background` | Die `Color` um den Bildschirm |

Verwenden Sie `framed()`, um den Umriss in der eigenen Größe des Screenshots zu zeichnen:

``` dart
final Screenshot framed = await shot.framed(caption: 'Checkout in one tap');
```

<div id="store-sizes"></div>

### Store-Größen

| Größe | ID | Pixel |
|-------|----|-------|
| `ScreenshotStore.ios69` | `ios-6.9` | 1320 x 2868 |
| `ScreenshotStore.ios65` | `ios-6.5` | 1284 x 2778 |
| `ScreenshotStore.ipad13` | `ipad-13` | 2064 x 2752 |
| `ScreenshotStore.playPhone` | `play-phone` | 1080 x 1920 |
| `ScreenshotStore.playTablet` | `play-tablet` | 1600 x 2560 |

`ScreenshotStore.all` listet jede Größe auf, und `ScreenshotStore.named('ios-6.9')` findet eine anhand ihrer ID.

<div id="hiding-content"></div>

## Inhalte ausblenden

Umschließen Sie alles Private mit `ScreenshotHidden`. Es wird auf dem Bildschirm wie gewohnt angezeigt, und in jedem Screenshot verdeckt es ein grauer Block:

``` dart
ScreenshotHidden(
  child: Text('Account 4021 8890 1177'),
)
```

Übergeben Sie `replacement`, um statt des grauen Blocks Ihren eigenen Platzhalter zu zeichnen.

<div id="capturing-a-widget"></div>

## Ein Widget aufnehmen

`Screenshot.widget` nimmt ein Widget auf, ohne es anzuzeigen, etwa eine Share-Karte, die nur für das Bild gebaut wurde:

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

Das PNG ist so groß wie das Widget, umschließen Sie es also mit einer `SizedBox` für eine exakte Größe. Übergeben Sie `context`, um dem Widget das Theme und die Lokalisierungen dieses Kontexts zu geben, `size`, um den Raum festzulegen, in dem es angeordnet wird, und `delay`, um Bildern Zeit zum Laden zu geben.

<div id="locales-and-themes"></div>

## Sprachen und Themes

Übergeben Sie `locale` oder `theme`, um den Bildschirm für eine Aufnahme anders anzuzeigen:

``` dart
final Screenshot spanish = await Screenshot.capture(locale: 'es');
final Screenshot dark = await Screenshot.area('receipt', theme: 'dark');
```

Die App wechselt an Ort und Stelle, behält Seite, Zustand und Navigationsstack und wechselt gleich danach zurück. Es wird nichts gespeichert, sodass die eigenen Einstellungen des Benutzers erhalten bleiben. Eine Sprache braucht eine Sprachdatei in Ihren Assets, und ein Theme braucht eine registrierte Theme-ID.

<div id="from-metro"></div>

## Über Metro

Speichern Sie einen Screenshot Ihrer laufenden App vom Terminal aus:

``` bash
metro live:run screenshot --open
metro live:run screenshot --area receipt
metro live:run screenshot --store ios-6.9 --frame
```

Alle Optionen finden Sie unter [Metro Live](/docs/{{ $version }}/metro-live#screenshots). Ein [Playbook](/docs/{{ $version }}/playbooks#screenshots) kann Screenshots einer ganzen Journey aufnehmen, in jeder Sprache und jedem Theme.
