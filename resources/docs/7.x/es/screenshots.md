# Capturas de pantalla
<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [Introducción](#introduction "Introducción a las capturas de pantalla")
- [Capturar la pantalla](#capturing-the-screen "Capturar la pantalla completa")
- [Capturar un área](#capturing-an-area "Capturar una parte de la pantalla con ScreenshotArea")
- [Guardar una captura](#saving-a-screenshot "Guardar una captura en un disco")
- [Fichas de tienda](#store-listings "Ajustar el tamaño de las capturas para App Store y Google Play")
  - [Tamaños de tienda](#store-sizes "Tamaños de ScreenshotStore")
- [Ocultar contenido](#hiding-content "Ocultar contenido de las capturas")
- [Capturar un widget](#capturing-a-widget "Capturar un widget sin mostrarlo")
- [Configuraciones regionales y temas](#locales-and-themes "Capturar en otra configuración regional o tema")
- [Desde Metro](#from-metro "Tomar capturas desde Metro")

<div id="introduction"></div>

## Introducción

<x-doc-strip label="Requiere" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`Screenshot` toma un PNG de la pantalla de tu app, o de una parte de ella:

``` dart
// La pantalla completa, con diálogos y hojas incluidos
final Screenshot shot = await Screenshot.capture();
await shot.store('reports/checkout.png');

// Una parte de la pantalla
final Screenshot receipt = await Screenshot.area('receipt');
await receipt.store('order-1042.png', disk: 'public');
```

Las capturas funcionan en todos los modos de compilación, así que pueden impulsar funciones de tu app, como compartir un recibo o adjuntar la pantalla a un reporte de errores. [Metro](#from-metro) y los [playbooks](/docs/{{ $version }}/playbooks#screenshots) usan el mismo código.

Un `Screenshot` contiene el PNG como `bytes`, con su `width` y `height` en píxeles físicos.

<div id="capturing-the-screen"></div>

## Capturar la pantalla

`Screenshot.capture()` lee la pantalla completa de la app tal como la ve el usuario:

``` dart
final Screenshot shot = await Screenshot.capture();
```

| Parámetro | Descripción |
|-----------|-------------|
| `pixelRatio` | El tamaño relativo a los píxeles lógicos. Por defecto el del dispositivo, así que el PNG tiene la resolución nativa de la pantalla |
| `settle` | Espera al siguiente fotograma antes de capturar (`true` por defecto). Pasa `false` cuando acaba de dibujarse un fotograma |
| `locale` | Muestra la pantalla en este idioma durante la captura, p. ej. `'es'` |
| `theme` | Muestra la pantalla con este id de tema durante la captura, p. ej. `'dark'` |

Una app construida con `NyApp.materialApp` captura exactamente sus páginas. Si construyes tu propio `MaterialApp`, añade un `ScreenshotRoot` a su `builder`:

``` dart
MaterialApp(
  builder: (context, child) => ScreenshotRoot(child: child!),
)
```

<div id="capturing-an-area"></div>

## Capturar un área

Envuelve una parte de una pantalla en un `ScreenshotArea` y ponle un nombre:

``` dart
ScreenshotArea(
  name: 'receipt',
  child: ReceiptCard(order: order),
)
```

Luego captúrala por ese nombre:

``` dart
final Screenshot receipt = await Screenshot.area('receipt');
```

`Screenshot.area` acepta los mismos parámetros `pixelRatio`, `settle`, `locale` y `theme` que `capture`. `Screenshot.areas` lista los nombres de las áreas en pantalla.

<div id="saving-a-screenshot"></div>

## Guardar una captura

`store` guarda el PNG en un disco de almacenamiento de archivos y devuelve la ruta:

``` dart
final Screenshot shot = await Screenshot.capture();

String path = await shot.store('reports/checkout.png');
await shot.store('order-1042.png', disk: 'public');
```

Sin `disk`, se guarda en el disco predeterminado. Para compartir la imagen o subirla, lee `shot.bytes`.

<div id="store-listings"></div>

## Fichas de tienda

`forStore` devuelve una nueva captura con el tamaño exacto que pide una tienda de apps:

``` dart
final Screenshot listing = await shot.forStore(
  ScreenshotStore.ios69,
  frame: true,
  caption: 'Checkout in one tap',
);
await listing.store('store/ios-6.9/checkout.png');
```

Una pantalla de forma casi igual llena el tamaño, y cualquier otra se ajusta dentro de él. Con `frame: true`, la pantalla se dibuja en el contorno de un teléfono bajo el `caption`.

| Parámetro | Descripción |
|-----------|-------------|
| `frame` | Dibuja la pantalla dentro del contorno de un teléfono |
| `caption` | Texto sobre el teléfono, se usa con `frame` |
| `background` | El `Color` alrededor de la pantalla |

Usa `framed()` para dibujar el contorno con el tamaño propio de la captura:

``` dart
final Screenshot framed = await shot.framed(caption: 'Checkout in one tap');
```

<div id="store-sizes"></div>

### Tamaños de tienda

| Tamaño | Id | Píxeles |
|--------|----|---------|
| `ScreenshotStore.ios69` | `ios-6.9` | 1320 x 2868 |
| `ScreenshotStore.ios65` | `ios-6.5` | 1284 x 2778 |
| `ScreenshotStore.ipad13` | `ipad-13` | 2064 x 2752 |
| `ScreenshotStore.playPhone` | `play-phone` | 1080 x 1920 |
| `ScreenshotStore.playTablet` | `play-tablet` | 1600 x 2560 |

`ScreenshotStore.all` lista todos los tamaños, y `ScreenshotStore.named('ios-6.9')` encuentra uno por su id.

<div id="hiding-content"></div>

## Ocultar contenido

Envuelve cualquier cosa privada en `ScreenshotHidden`. Se muestra con normalidad en pantalla, y un bloque gris la cubre en todas las capturas:

``` dart
ScreenshotHidden(
  child: Text('Account 4021 8890 1177'),
)
```

Pasa `replacement` para dibujar tu propio marcador en lugar del bloque gris.

<div id="capturing-a-widget"></div>

## Capturar un widget

`Screenshot.widget` captura un widget sin mostrarlo, como una tarjeta para compartir creada solo para la imagen:

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

El PNG es tan grande como el widget, así que envuélvelo en un `SizedBox` para un tamaño exacto. Pasa `context` para darle al widget el tema y las localizaciones de ese contexto, `size` para fijar el espacio en el que se dispone, y `delay` para dar tiempo a que carguen las imágenes.

<div id="locales-and-themes"></div>

## Configuraciones regionales y temas

Pasa `locale` o `theme` para mostrar la pantalla de otra forma en una captura:

``` dart
final Screenshot spanish = await Screenshot.capture(locale: 'es');
final Screenshot dark = await Screenshot.area('receipt', theme: 'dark');
```

La app cambia en el sitio, conservando la página, su estado y la pila de navegación, y vuelve atrás de inmediato. No se guarda nada, así que se respetan las elecciones del propio usuario. Una configuración regional necesita un archivo de idioma en tus assets, y un tema necesita un id de tema registrado.

<div id="from-metro"></div>

## Desde Metro

Guarda una captura de tu app en ejecución desde la terminal:

``` bash
metro live:run screenshot --open
metro live:run screenshot --area receipt
metro live:run screenshot --store ios-6.9 --frame
```

Consulta [Metro Live](/docs/{{ $version }}/metro-live#screenshots) para ver todas las opciones. Un [playbook](/docs/{{ $version }}/playbooks#screenshots) puede tomar capturas de un recorrido completo, en cada idioma y tema.
