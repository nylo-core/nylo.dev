# Playbooks
<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [Introducción](#introduction "Introducción a los playbooks")
- [Crear un playbook](#creating-a-playbook "Crear un playbook")
- [Pasos](#steps "Los pasos que puede realizar un playbook")
- [Objetivos](#targets "Encontrar elementos en pantalla")
  - [Cadenas simples](#plain-strings "Cómo se reconoce una cadena simple")
  - [Find](#find "Objetivos precisos con Find")
- [Datos y configuración](#data-and-setup "Datos, setUp y tearDown")
- [Reproducir otros playbooks](#playing-other-playbooks "Ejecutar un playbook desde otro playbook")
- [Velocidad](#speed "PlaybookSpeed")
- [Dedo y subtítulo](#finger-and-caption "El dedo y el subtítulo del paso")
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

Un **playbook** es una clase que lista los toques, la escritura, los desplazamientos y los cambios de página de un recorrido de usuario. Tu app en ejecución los realiza por sí misma, con un dedo visible y un subtítulo en cada paso -- útil para demos, grabaciones de pantalla y comprobar un recorrido de principio a fin.

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
| `Find.icon(icon)` | Un ícono |
| `Find.type<T>()` | Un widget de tipo `T` |
| `Find.key(key)` | Un widget con la key |
| `Find.field(name)` | Un campo de `NyForm` por nombre |
| `Find.input(text)` | Un campo de texto por hint, label o placeholder |
| `Find.tooltip(message)` | Un tooltip |
| `Find.semantics(label)` | Un label de semántica |
| `Find.where(test)` | Widgets que superan tu prueba |

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

Sobrescribe `showFinger` y `caption` para cambiar lo que dibuja la superposición:

``` dart
@override
bool get showFinger => false;

@override
PlaybookCaption get caption => PlaybookCaption.top;
```

`PlaybookCaption` es `top`, `bottom` (la predeterminada) o `hidden`. Los toques y los deslizamientos siguen ocurriendo sin el dedo. Para desactivar toda la superposición en una ejecución, usa `--no-overlay` desde Metro o `run(overlay: false)` desde Dart.

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

Dentro de `metro live`, escribe `playbook sign_up`. Consulta [Metro Live](/docs/{{ $version }}/metro-live#playbooks) para ver todas las opciones.

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

`run()` acepta `speed`, `data`, `clock`, `overlay` (`true` por defecto) y `onEvent`. Un paso que falla no lanza una excepción -- comprueba `report.passed`. Solo puede ejecutarse un playbook a la vez; usa `play()` para ejecutar uno dentro de otro.

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
testWidgets('signs up', (tester) async {
  await tester.pumpWidget(const MyApp());
  final report = await SignUpPlaybook().run(clock: NyTesterClock(tester));
  expectPlaybookPassed(report);
});
```

Consulta [Probar playbooks](/docs/{{ $version }}/testing#testing-playbooks) para las pruebas de integración.
