# Captures d'ecran
<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction aux captures d'ecran")
- [Capturer l'ecran](#capturing-the-screen "Capturer tout l'ecran")
- [Capturer une zone](#capturing-an-area "Capturer une partie de l'ecran avec ScreenshotArea")
- [Enregistrer une capture](#saving-a-screenshot "Enregistrer une capture sur un disque")
- [Fiches de store](#store-listings "Dimensionner les captures pour l'App Store et Google Play")
  - [Tailles de store](#store-sizes "Tailles ScreenshotStore")
- [Masquer du contenu](#hiding-content "Masquer du contenu dans les captures")
- [Capturer un widget](#capturing-a-widget "Capturer un widget sans l'afficher")
- [Locales et themes](#locales-and-themes "Capturer dans une autre locale ou un autre theme")
- [Depuis Metro](#from-metro "Prendre des captures depuis Metro")

<div id="introduction"></div>

## Introduction

<x-doc-strip label="Nécessite" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`Screenshot` prend un PNG de l'ecran de votre application, ou d'une partie de celui-ci :

``` dart
// Tout l'ecran, boites de dialogue et feuilles comprises
final Screenshot shot = await Screenshot.capture();
await shot.store('reports/checkout.png');

// Une partie de l'ecran
final Screenshot receipt = await Screenshot.area('receipt');
await receipt.store('order-1042.png', disk: 'public');
```

Les captures fonctionnent dans tous les modes de build, elles peuvent donc alimenter des fonctionnalites de votre application, comme partager un recu ou joindre l'ecran a un rapport de bug. [Metro](#from-metro) et les [playbooks](/docs/{{ $version }}/playbooks#screenshots) utilisent le meme code.

Un `Screenshot` contient le PNG sous forme de `bytes`, avec sa `width` et sa `height` en pixels physiques.

<div id="capturing-the-screen"></div>

## Capturer l'ecran

`Screenshot.capture()` lit tout l'ecran de l'application tel que l'utilisateur le voit :

``` dart
final Screenshot shot = await Screenshot.capture();
```

| Parametre | Description |
|-----------|-------------|
| `pixelRatio` | La taille par rapport aux pixels logiques. Par defaut celle de l'appareil, de sorte que le PNG a la resolution native de l'ecran |
| `settle` | Attendre la prochaine frame avant de capturer (`true` par defaut). Passez `false` lorsqu'une frame vient d'etre dessinee |
| `locale` | Afficher l'ecran dans cette langue pour la capture, par ex. `'es'` |
| `theme` | Afficher l'ecran avec cet identifiant de theme pour la capture, par ex. `'dark'` |

Une application construite avec `NyApp.materialApp` capture exactement ses pages. Si vous construisez votre propre `MaterialApp`, ajoutez un `ScreenshotRoot` a son `builder` :

``` dart
MaterialApp(
  builder: (context, child) => ScreenshotRoot(child: child!),
)
```

<div id="capturing-an-area"></div>

## Capturer une zone

Enveloppez une partie d'un ecran dans un `ScreenshotArea` et donnez-lui un nom :

``` dart
ScreenshotArea(
  name: 'receipt',
  child: ReceiptCard(order: order),
)
```

Capturez-la ensuite par ce nom :

``` dart
final Screenshot receipt = await Screenshot.area('receipt');
```

`Screenshot.area` accepte les memes parametres `pixelRatio`, `settle`, `locale` et `theme` que `capture`. `Screenshot.areas` liste les noms des zones a l'ecran.

<div id="saving-a-screenshot"></div>

## Enregistrer une capture

`store` enregistre le PNG sur un disque de stockage de fichiers et retourne le chemin :

``` dart
final Screenshot shot = await Screenshot.capture();

String path = await shot.store('reports/checkout.png');
await shot.store('order-1042.png', disk: 'public');
```

Sans `disk`, il enregistre sur le disque par defaut. Pour partager l'image ou la televerser, lisez `shot.bytes`.

<div id="store-listings"></div>

## Fiches de store

`forStore` retourne une nouvelle capture a la taille exacte qu'un app store demande :

``` dart
final Screenshot listing = await shot.forStore(
  ScreenshotStore.ios69,
  frame: true,
  caption: 'Checkout in one tap',
);
await listing.store('store/ios-6.9/checkout.png');
```

Un ecran de forme presque identique remplit la taille, et tout autre est ajuste a l'interieur. Avec `frame: true`, l'ecran est dessine dans un contour de telephone sous la `caption`.

| Parametre | Description |
|-----------|-------------|
| `frame` | Dessiner l'ecran dans un contour de telephone |
| `caption` | Texte au-dessus du telephone, utilise avec `frame` |
| `background` | La `Color` autour de l'ecran |

Utilisez `framed()` pour dessiner le contour a la taille propre de la capture :

``` dart
final Screenshot framed = await shot.framed(caption: 'Checkout in one tap');
```

<div id="store-sizes"></div>

### Tailles de store

| Taille | Id | Pixels |
|------|----|--------|
| `ScreenshotStore.ios69` | `ios-6.9` | 1320 x 2868 |
| `ScreenshotStore.ios65` | `ios-6.5` | 1284 x 2778 |
| `ScreenshotStore.ipad13` | `ipad-13` | 2064 x 2752 |
| `ScreenshotStore.playPhone` | `play-phone` | 1080 x 1920 |
| `ScreenshotStore.playTablet` | `play-tablet` | 1600 x 2560 |

`ScreenshotStore.all` liste toutes les tailles, et `ScreenshotStore.named('ios-6.9')` en trouve une par son id.

<div id="hiding-content"></div>

## Masquer du contenu

Enveloppez tout ce qui est prive dans `ScreenshotHidden`. Il s'affiche normalement a l'ecran, et un bloc gris le recouvre dans chaque capture :

``` dart
ScreenshotHidden(
  child: Text('Account 4021 8890 1177'),
)
```

Passez `replacement` pour dessiner votre propre placeholder a la place du bloc gris.

<div id="capturing-a-widget"></div>

## Capturer un widget

`Screenshot.widget` capture un widget sans l'afficher, comme une carte de partage construite uniquement pour l'image :

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

Le PNG a la taille du widget, enveloppez-le donc dans un `SizedBox` pour obtenir une taille exacte. Passez `context` pour donner au widget le theme et les localisations de ce contexte, `size` pour definir l'espace dans lequel il est dispose, et `delay` pour laisser aux images le temps de se charger.

<div id="locales-and-themes"></div>

## Locales et themes

Passez `locale` ou `theme` pour afficher l'ecran autrement le temps d'une capture :

``` dart
final Screenshot spanish = await Screenshot.capture(locale: 'es');
final Screenshot dark = await Screenshot.area('receipt', theme: 'dark');
```

L'application bascule sur place, en conservant la page, son etat et la pile de navigation, et revient aussitot apres. Rien n'est enregistre, les choix propres a l'utilisateur sont donc conserves. Une locale necessite un fichier de langue dans vos assets, et un theme necessite un identifiant de theme enregistre.

<div id="from-metro"></div>

## Depuis Metro

Enregistrez une capture de votre application en cours d'execution depuis le terminal :

``` bash
metro live:run screenshot --open
metro live:run screenshot --area receipt
metro live:run screenshot --store ios-6.9 --frame
```

Consultez [Metro Live](/docs/{{ $version }}/metro-live#screenshots) pour toutes les options. Un [playbook](/docs/{{ $version }}/playbooks#screenshots) peut prendre des captures d'un parcours entier, dans chaque langue et chaque theme.
