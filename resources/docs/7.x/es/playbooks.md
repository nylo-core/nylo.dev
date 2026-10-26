# Playbooks
<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [Introducción](#introduction "Introducción a los playbooks")
- [Crear un playbook](#creating-a-playbook "Crear un playbook")
- [Grabar un playbook](#recording-a-playbook "Grabar un playbook desde la app en ejecución")
- [Pasos](#steps "Los pasos que puede realizar un playbook")
- [Objetivos](#targets "Encontrar elementos en pantalla")
  - [Cadenas simples](#plain-strings "Cómo se reconoce una cadena simple")
  - [Find](#find "Objetivos precisos con Find")
- [Datos y configuración](#data-and-setup "Datos, setUp y tearDown")
- [Reproducir otros playbooks](#playing-other-playbooks "Ejecutar un playbook desde otro playbook")
- [Velocidad](#speed "PlaybookSpeed")
- [Dedo y subtítulo](#finger-and-caption "El dedo y el subtítulo del paso")
- [Capturas de pantalla](#screenshots "Capturar pantallas durante un playbook")
  - [Configuraciones regionales y temas](#screenshots-locales-and-themes "Capturar una pantalla en cada configuración regional y tema")
  - [Recopilar desde el código](#collecting-screenshots "Recopilar capturas con onScreenshot")
- [Registrar playbooks](#registering-playbooks "Registrar playbooks en Nylo")
- [Ejecutar un playbook](#running-a-playbook "Ejecutar un playbook")
  - [Desde Metro](#running-from-metro "Ejecutar desde Metro")
  - [Al arrancar](#running-on-boot "Ejecutar al arrancar")
  - [Desde Dart](#running-from-dart "Ejecutar desde Dart")
- [Reportes](#reports "PlaybookReport")
- [Compilaciones release](#release-builds "Playbooks en compilaciones release")
- [Pruebas](#testing "Probar un playbook")

<div id="introduction"></div>

## Introducción

<x-doc-strip label="Requiere" items="{{ config('app.name') }} 7.3.0+, nylo_support 7.32.0+" />

Un **playbook** es una clase que lista los toques, la escritura, los desplazamientos y los cambios de página de un recorrido de usuario. Tu app en ejecución los realiza por sí misma, y puede mostrar un dedo y un subtítulo en cada paso -- útil para demos, grabaciones de pantalla y comprobar un recorrido de principio a fin.

``` dart
import 'package:nylo_framework/nylo_framework.dart';
import 'package:nylo_framework/playbook.dart';

class SignUpPlaybook extends Playbook {
  @override
  String get description => 'New user signs up and lands on the dashboard';

  @override
  Map<String, dynamic> get defaults => {'email': 'demo@nylo.dev'};

  @override
  List<PlaybookStep> get steps => [
    visit(SignUpPage.path),
    type('Email', data['email']),
    type('Password', 'secret123', secret: true),
    tap('Create account'),
    waitForPage(DashboardPage.path),
    see('Welcome'),
  ];
}
```

Importa `package:nylo_framework/playbook.dart` dondequiera que escribas o registres playbooks.

<div id="creating-a-playbook"></div>

## Crear un playbook

Usa Metro para crear uno:

``` bash
metro make:playbook sign_up
```

Esto crea `lib/app/playbooks/sign_up_playbook.dart` y lo registra en tu app. Consulta [Make Playbook](/docs/{{ $version }}/metro#make-playbook) para ver las opciones del comando.

Para empezar desde cada página que añade tu router, pasa `--from-routes`:

``` bash
metro make:playbook tour --from-routes
```

Metro escribe un `visit()` y un [`screenshot()`](#screenshots) para cada página, así que una sola ejecución fotografía toda tu app.

<div id="recording-a-playbook"></div>

## Grabar un playbook

<x-doc-strip label="Requiere" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.36.0+" />

En lugar de escribir los pasos, grábalos. Con tu app en ejecución en modo debug, abre `metro live` y escribe:

``` plaintext
› record sign_up
```

Luego usa la app. Cada toque, pulsación larga, campo tecleado, desplazamiento y cambio de página se escribe como un paso en el momento en que ocurre, y se imprime sobre la marcha:

``` plaintext
● Recording sign_up on iPhone 17. Use the app; each step is written as you go.
· Enter finishes · a: the next tap becomes see() · d: dontSee() · s <title>: start a scene · u: undo the last step · p: pause or resume · Ctrl+C discards
  1  visit(HomePage.path)
  2  tap('Get started')
  3  waitForPage(SignUpPage.path)
  4  type('Email', data['email'])
  5  type('Password', data['password'], secret: true, submit: true)
  6  tap('Create account')
  7  waitForPage(DashboardPage.path)
  8  see('Welcome')
✓ Recorded 8 steps in 41s
✓ Registered as 'sign_up' in lib/bootstrap/playbooks.dart
Play it back now at fast speed? It takes a hot restart. [Y/n]
```

Pulsa Enter para terminar. El playbook se escribe en `lib/app/playbooks/sign_up_playbook.dart`, con la misma estructura que uno creado por `make:playbook`, y se registra, así que se reproduce en cuanto la app se reinicia. `metro make:playbook sign_up --record` hace lo mismo desde la terminal: abre `metro live` e inicia la grabación de inmediato.

Mientras grabas, escribe una tecla y pulsa Enter:

| Tecla | Descripción |
|-------|-------------|
| `a` | El siguiente toque se escribe como `see(...)` en lugar de un toque |
| `d` | El siguiente toque se escribe como `dontSee(...)` |
| `s <title>` | Inicia una `scene` con ese título; los pasos siguientes se anidan dentro |
| `u` | Elimina el último paso |
| `p` | Pausa la grabación, p. ej. para iniciar sesión con credenciales reales; `p` otra vez la reanuda |
| Enter | Termina y escribe el playbook |
| Ctrl+C | Descarta la grabación |

Los objetivos se nombran como los lee el reproductor, primero el texto visible, y cada nombre se comprueba contra la app en ejecución antes de escribirse, así que un paso grabado se reproduce. Cuando una cadena simple coincide con más de un widget, el paso la acota con `Find`: `tap(Find.text('Add').at(1))`. Un botón de ícono sin texto ni tooltip pasa a ser `tap(Find.icon(Icons.add))`. Un widget que nada nombra se escribe como `tap(Find.type<GestureDetector>().at(3))` bajo un comentario `// TODO` con el archivo y la línea donde se creó, y Metro lo lista cuando termina la grabación. Dale una `Key` y usa la key en su lugar.

- **Teclear** pasa a ser un paso `type` que lee un marcador `data[...]`, con el valor tecleado en `defaults`. Un campo que se deja con la tecla de acción del teclado recibe `submit: true`. Un campo con texto oculto recibe `secret: true`, su valor en `defaults` y una advertencia para cambiarlo antes de hacer commit. Pasa `--no-data` para escribir los valores tal como se tecleron.
- **Los cambios de página** después de un toque pasan a ser `waitForPage(SignUpPage.path)` cuando una de tus páginas declara la ruta, o `waitForPage('/sign-up')` en caso contrario. Volver atrás pasa a ser `back()`.
- **El desplazamiento** se escribe como `scrollTo(...)` sobre lo que tocas a continuación, y solo cuando el desplazamiento lo incorporó al árbol. El reproductor revela todo lo demás por sí solo.
- **Los deslizamientos que no son desplazamientos** -- un `Dismissible`, un `PageView`, un `Slider` -- aún no tienen verbo de playbook, así que se dejan como un comentario `// TODO` con los puntos de inicio y fin.

La grabación necesita una compilación debug y funciona en simuladores, emuladores y dispositivos por igual. Los toques del propio playbook nunca se graban. Consulta [Metro Live](/docs/{{ $version }}/metro-live#recording-a-journey) para ver las opciones del comando.

<div id="steps"></div>

## Pasos

Los `steps` de un playbook se construyen con los verbos que hereda:

| Paso | Descripción |
|------|-------------|
| `tap(target)` | Toca un objetivo |
| `longPress(target)` | Mantiene pulsado un objetivo |
| `type(target, text)` | Escribe en un campo. Lo vacía primero salvo con `clear: false`; `submit: true` pulsa el botón de acción del teclado; `secret: true` oculta el texto en el subtítulo y en el reporte |
| `scrollTo(target)` | Se desplaza hasta que un objetivo esté en pantalla. Pasa `within:` para desplazar un elemento desplazable concreto |
| `see(target)` | Comprueba que un objetivo está en pantalla, esperando a que aparezca |
| `dontSee(target)` | Comprueba que un objetivo no está en pantalla, esperando a que desaparezca |
| `waitFor(target)` | Espera a un objetivo, hasta `timeout` (10 segundos por defecto) |
| `waitForPage(path)` | Espera a la página de una ruta o `RouteView` (p. ej. `HomePage.path`) |
| `visit(path)` | Abre una página por ruta o `RouteView`, con `data:` opcional |
| `back()` | Retrocede una página |
| `pause(seconds: 1)` | Se queda quieto, p. ej. para dejar respirar una demo |
| `step(title, action)` | Ejecuta cualquier código con los mismos verbos, mediante un `PlaybookActor` |
| `scene(title, steps)` | Agrupa pasos bajo un título; sus subtítulos empiezan con él y sus resultados se anidan en el reporte |
| `screenshot(name)` | Captura la pantalla -- consulta [Capturas de pantalla](#screenshots) |
| `play(playbook)` | Ejecuta otro playbook -- consulta [Reproducir otros playbooks](#playing-other-playbooks) |

``` dart
@override
List<PlaybookStep> get steps => [
  scene('Add to bag', [
    scrollTo('Running shoes'),
    tap('Running shoes'),
    tap('Add to bag'),
  ]),
  step('Clear the cache', (actor) async {
    await Backpack.instance.delete('cart_cache');
  }),
  see('1 item'),
];
```

Los pasos se ejecutan en orden y se detienen en el primer fallo. Los pasos restantes se reportan como omitidos.

<div id="targets"></div>

## Objetivos

La mayoría de los pasos reciben un objetivo: qué tocar, dónde escribir o qué buscar.

<div id="plain-strings"></div>

### Cadenas simples

Una cadena simple se reconoce como una persona lee la pantalla. Prueba, en orden:

1. Texto visible
2. El hint o label de un campo
3. Un tooltip
4. Un label de semántica
5. Una `ValueKey`
6. Texto que contiene la cadena

``` dart
tap('Create account'),
type('Email', 'demo@nylo.dev'),
```

También se puede pasar directamente a un paso una `Key`, un `Type` o un `IconData`.

<div id="find"></div>

### Find

Usa `Find` cuando una cadena simple no es lo bastante precisa:

``` dart
tap(Find.icon(Icons.add)),
tap(Find.type<FloatingActionButton>()),
tap(Find.key(const ValueKey('checkout'))),
type(Find.field('email'), 'demo@nylo.dev'),
tap(Find.text('Delete').inside(Find.type<AlertDialog>())),
tap(Find.text('Add').at(1)),
```

| Finder | Encuentra |
|--------|---------|
| `Find.text(text)` | Texto visible |
| `Find.textContaining(text)` | Texto que contiene la cadena |
| `Find.icon(icon, label:)` | Un ícono. `label:` le da nombre en los subtítulos de los pasos |
| `Find.type<T>()` | Un widget de tipo `T` |
| `Find.key(key)` | Un widget con la key |
| `Find.field(name)` | Un campo de `NyForm` por nombre |
| `Find.input(text)` | Un campo de texto por hint, label o placeholder |
| `Find.tooltip(message)` | Un tooltip |
| `Find.semantics(label)` | Un label de semántica |
| `Find.where(test)` | Widgets que superan tu prueba |

Dale un `label` a un ícono y sus subtítulos de paso dicen `Tap the bag icon` en lugar de `Tap icon f37d`:

``` dart
tap(Find.icon(Icons.shopping_bag_outlined, label: 'bag')),
```

Acota cualquier finder con `.inside(ancestor)`, `.at(index)` (contando desde 0), `.first` o `.last`.

Un toque se rechaza cuando algo ajeno, como la barrera de un diálogo, cubre su objetivo.

<div id="data-and-setup"></div>

## Datos y configuración

`defaults` contiene los datos con los que empiezan los pasos. Léelos mediante `data` dentro de `steps`. `setUp` se ejecuta antes de los pasos y `tearDown` después, incluso si un paso falló:

``` dart
class CheckoutPlaybook extends Playbook {
  @override
  Map<String, dynamic> get defaults => {'coupon': 'SAVE10'};

  @override
  Future<void> setUp(PlaybookActor actor) async {
    await Auth.logout();
    await seed(['demo_user']);
  }

  @override
  Future<void> tearDown(PlaybookActor actor) async {
    await Auth.logout();
  }

  @override
  List<PlaybookStep> get steps => [
    tap('Checkout'),
    type('Coupon', data['coupon']),
  ];
}
```

`seed([...])` ejecuta [Seeders](/docs/{{ $version }}/metro-live#seeders) (instancias o nombres registrados) y los registra como `seed` en `metro live`.

<div id="playing-other-playbooks"></div>

## Reproducir otros playbooks

`play()` ejecuta otro playbook completo, con su propio `setUp` y `tearDown`. Pasa una instancia o el nombre con el que está registrado:

``` dart
@override
List<PlaybookStep> get steps => [
  play(SignUpPlaybook()),
  play('add_to_bag', data: {'item': 'Running shoes'}),
  tap('Checkout'),
];
```

- **Los datos** se combinan en este orden: los `defaults` del hijo, luego los `data` del padre y después el `data:` pasado a `play`.
- **La velocidad** se hereda del padre, salvo que pases `speed:`.
- **Los resultados** se anidan bajo el paso en el reporte.
- **Un hijo que falla** hace fallar el paso.
- **Los ciclos** se rechazan, y los playbooks se anidan como máximo 8 niveles.

<div id="speed"></div>

## Velocidad

`PlaybookSpeed` fija el ritmo de cada espera:

| Velocidad | Descripción |
|-------|-------------|
| `PlaybookSpeed.instant` | Sin esperas más allá de lo que necesita el desplazamiento. Pensada para pruebas |
| `PlaybookSpeed.fast` | Rápida, pero aún fácil de seguir |
| `PlaybookSpeed.natural` | El ritmo de una persona usando la app. La predeterminada |
| `PlaybookSpeed.slow` | Lo bastante lenta para narrar encima |

Escala cualquier velocidad con `.times(n)`:

``` dart
@override
PlaybookSpeed get speed => PlaybookSpeed.natural.times(2); // el doble de lento
```

Una velocidad pasada a `run()`, `--speed` desde Metro, o la velocidad de un playbook que reproduce a este, prevalece sobre la del propio playbook.

<div id="finger-and-caption"></div>

## Dedo y subtítulo

El dedo y los subtítulos de los pasos están desactivados por defecto. Sobrescribe `showFinger` y `caption` para activarlos:

``` dart
@override
bool get showFinger => true;

@override
PlaybookCaption get caption => PlaybookCaption.bottom; // o .top
```

`showFinger` es `false` por defecto y `caption` es `PlaybookCaption.hidden`; `PlaybookCaption` es `top`, `bottom` o `hidden`. Los toques y los deslizamientos ocurren en ambos casos. Un playbook reproducido desde este con `play()` usa su propio ajuste, y el de este vuelve cuando termina. Para ocultar ambos en una ejecución, usa `--no-overlay` desde Metro o `run(overlay: false)` desde Dart.

Antes de nylo_support 7.33.0 ({{ config('app.name') }} 7.4.0), el dedo y el subtítulo inferior estaban activados por defecto. Añade las sobrescrituras anteriores para conservar ese aspecto.

<div id="screenshots"></div>

## Capturas de pantalla

<x-doc-strip label="Requiere" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

Añade un paso `screenshot` donde valga la pena conservar la pantalla:

``` dart
@override
List<PlaybookStep> get steps => [
  visit(ShopPage.path),
  screenshot('shop'),
  tap('Running shoes'),
  tap('Add to bag'),
  screenshot('cart', caption: 'Your bag, ready to go'),
];
```

`metro live:run playbook <name>` guarda cada una como `screenshots/<playbook>/<name>.png`, sin necesidad de ningún flag. Una ejecución desde código las recopila con [`onScreenshot`](#collecting-screenshots), y cualquier otra ejecución, como `PLAYBOOK_ON_BOOT`, solo espera a que la pantalla se estabilice. Un nombre empieza con una letra o un dígito y usa letras, dígitos, `-`, `_` y `.`.

| Parámetro | Descripción |
|-----------|-------------|
| `name` | El nombre con el que se guarda la captura |
| `locales` | Captura una vez en cada uno de estos códigos de idioma. `Screenshot.allLocales` es cada idioma que incluye la app |
| `themes` | Captura una vez en cada uno de estos ids de tema. `Screenshot.allThemes` es cada tema registrado |
| `caption` | El texto sobre la pantalla cuando la ejecución enmarca sus capturas, o una clave de traducción |
| `native` | Captura con Metro, en el iOS Simulator y en Android (`true` por defecto) |

En el iOS Simulator y en Android, Metro captura la pantalla completa del dispositivo, así que la barra de estado y el teclado aparecen en ella, y el dedo y el subtítulo se ocultan mientras tanto. Añade `--clean-status-bar` a la ejecución para mostrar 9:41 con señal y batería completas. En otros casos, y en las ejecuciones con `--store` y `--frame`, la app captura su propia pantalla. Pasa `native: false` para usar siempre la captura de la app:

``` dart
screenshot('checkout', native: false),
```

Para guardar la pantalla de cada paso, ejecuta `metro live:run playbook <name> --screenshots`. Consulta [Metro Live](/docs/{{ $version }}/metro-live#playbook-screenshots) para ver las opciones que ajustan el tamaño de las capturas para App Store y Google Play, y [Capturas de pantalla](/docs/{{ $version }}/screenshots) para la clase `Screenshot` que hay detrás.

<div id="screenshots-locales-and-themes"></div>

### Configuraciones regionales y temas

Dale a un paso `screenshot` `locales`, `themes` o ambos para capturar la misma pantalla una vez en cada uno:

``` dart
screenshot('cart', locales: ['en', 'es'], themes: ['light', 'dark']),
screenshot('home', locales: Screenshot.allLocales),
```

La app cambia en el sitio, conservando la página y su estado, y vuelve atrás antes del siguiente paso. Cada captura se guarda en una carpeta con el nombre de su configuración regional y tema:

``` plaintext
screenshots/sign_up/en/cart.png
screenshots/sign_up/en-dark/cart.png
screenshots/sign_up/es/cart.png
screenshots/sign_up/es-dark/cart.png
```

Para reproducir un playbook completo en cada idioma y tema, pasa `--locales` y `--themes` a `metro live:run playbook`.

<div id="collecting-screenshots"></div>

### Recopilar desde el código

`run()` acepta `onScreenshot` para recibir las capturas de una ejecución como `PlaybookShot`, lo que resulta útil para una prueba o para tus propias herramientas:

``` dart
final List<PlaybookShot> shots = [];

final PlaybookReport report = await ShopPlaybook().run(
  onScreenshot: shots.add,
  screenshots: PlaybookScreenshots.marked,
  pixelRatio: 2,
);

for (final PlaybookShot shot in shots) {
  print('${shot.name} ${shot.locale} ${shot.theme}');
}
```

`screenshots` elige qué pantallas se capturan:

| Modo | Captura |
|------|---------|
| `PlaybookScreenshots.off` | Nada |
| `PlaybookScreenshots.failures` | Solo la pantalla de un paso que falla |
| `PlaybookScreenshots.marked` | Los pasos `screenshot()` y los pasos que fallan |
| `PlaybookScreenshots.auto` | Los pasos `screenshot()` de un playbook que los tiene, y cada paso de uno que no los tiene. También los pasos que fallan. El valor por defecto |
| `PlaybookScreenshots.all` | Cada paso, los pasos `screenshot()` y los pasos que fallan |

`pixelRatio` ajusta el tamaño de las capturas relativo al del dispositivo (`1.0` por defecto). Cada `PlaybookShot` tiene un `screenshot` para [guardar](/docs/{{ $version }}/screenshots#saving-a-screenshot) o del que leer `bytes`, un `kind`, y el `name`, `locale` y `theme` con los que se tomó.

<div id="registering-playbooks"></div>

## Registrar playbooks

Registra tus playbooks para que Metro y `play('name')` puedan encontrarlos:

``` dart
nylo.addPlaybooks({
  'sign_up': SignUpPlaybook.new,
});
```

`nylo.getPlaybooks()` devuelve lo que está registrado. También puedes pasarlos al configurar Nylo en tu `AppProvider`:

``` dart
await nylo.configure(
  // ...
  playbooks: playbooks,
);
```

`metro make:playbook` lo hace por ti. Mantiene el registro en `lib/bootstrap/playbooks.dart`, añade cada nuevo playbook y pasa `playbooks` a `nylo.configure(...)`. El registro es un mapa vacío en las compilaciones release:

``` dart
final Map<String, Playbook Function()> playbooks = kReleaseMode ? const {} : {
  'sign_up': SignUpPlaybook.new,
};
```

Un playbook registrado se ejecuta con su clave. Uno sin registrar se ejecuta con su `name`, o con el nombre de su clase en snake case (`SignUpPlaybook` es `sign_up`).

<div id="running-a-playbook"></div>

## Ejecutar un playbook

<div id="running-from-metro"></div>

### Desde Metro

Con tu app en ejecución en modo debug:

``` bash
metro live:run playbook sign_up --speed fast
```

Dentro de `metro live`, escribe `playbook sign_up`. Consulta [Metro Live](/docs/{{ $version }}/metro-live#playbooks) para ver todas las opciones. Añade `--record` para guardar un vídeo de la ejecución; consulta [Grabación](/docs/{{ $version }}/metro-live#recording-playbooks). Si la ejecución deja de esperar tras 20 segundos, probablemente la app se inició con `flutter run --no-dds`; consulta [Metro Live](/docs/{{ $version }}/metro-live#playbooks).

<div id="running-on-boot"></div>

### Al arrancar

Añade estas claves a tu archivo `.env` para reproducir playbooks en cuanto la app esté en pantalla:

``` bash
PLAYBOOK_ON_BOOT="sign_up,add_to_bag"
PLAYBOOK_SPEED="fast"
```

`PLAYBOOK_ON_BOOT` es una lista de nombres de playbooks separados por comas y `PLAYBOOK_SPEED` es uno de `instant`, `fast`, `natural` o `slow`. Ambas se aplican solo en compilaciones debug y profile.

<div id="running-from-dart"></div>

### Desde Dart

``` dart
final PlaybookReport report = await SignUpPlaybook().run(
  speed: PlaybookSpeed.fast,
  data: {'email': 'jane@example.com'},
);

if (!report.passed) {
  print(report);
}
```

`run()` acepta `speed`, `data`, `clock`, `overlay` (`true` por defecto), `onEvent`, y `onScreenshot`, `screenshots` y `pixelRatio` para [recopilar capturas](#collecting-screenshots). Un paso que falla no lanza una excepción -- comprueba `report.passed`. Solo puede ejecutarse un playbook a la vez; usa `play()` para ejecutar uno dentro de otro.

Detén un playbook en ejecución con `Playbook.stop()`. Los pasos restantes se omiten, pero `tearDown` se ejecuta igualmente. `Playbook.isRunning` indica si hay uno en ejecución.

<div id="reports"></div>

## Reportes

`run()` devuelve un `PlaybookReport` con el `name` del playbook, su `speed`, el tiempo `elapsed`, si fue `stopped`, y una lista `steps` de `PlaybookStepResult` (cada uno con `title`, `status`, `elapsed`, `error` y `steps` anidados). `report.passed` es true cuando todos los pasos pasaron, y al imprimir un reporte se lee como la salida de Metro. `report.toJson()` da los mismos datos como JSON.

<div id="release-builds"></div>

## Compilaciones release

Los playbooks nunca se ejecutan en una compilación release, así que un recorrido no puede controlar la app de un usuario:

- `Playbook.run()` lanza un `UnsupportedError`.
- `addPlaybooks` se ignora.
- El registro `lib/bootstrap/playbooks.dart` que genera Metro está vacío.

`Playbook.isAvailable` indica si la compilación actual puede ejecutar playbooks.

<div id="testing"></div>

## Pruebas

Ejecuta un playbook en una prueba de widgets con `NyTesterClock` y luego verifica su reporte con `expectPlaybookPassed`:

``` dart
nyWidgetTest('signs up', (tester) async {
  await tester.visit(SignUpPage.path);
  final report = await SignUpPlaybook().run(clock: NyTesterClock(tester));
  expectPlaybookPassed(report);
});
```

Consulta [Probar playbooks](/docs/{{ $version }}/testing#testing-playbooks) para las pruebas de integración.
