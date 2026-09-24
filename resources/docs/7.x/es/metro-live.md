# Metro Live

<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [Introducción](#introduction "Introducción a Nylo Live")
- El shell de metro live
  - [Opciones compartidas](#shared-options "Opciones que acepta todo comando live")
  - [Autocompletado con Tab e historial](#tab-completion-and-history "Autocompletado con Tab e historial")
  - [Ejecutar un script](#running-a-script "Ejecutar un script .live")
- [live:devices](#live-devices "Listar aplicaciones en ejecución")
- [live:status](#live-status "Mostrar el estado de la app")
- [live:run](#live-run "Ejecutar un comando live integrado")
- Comandos Live
  - [Crear un comando Live](#creating-a-live-command "Crear un comando live")
  - [Ejecutar un comando Live](#running-a-live-command "Ejecutar un comando live")
- Seeders
  - [Crear un Seeder](#creating-a-seeder "Crear un seeder")
  - [Sembrar y revertir](#seeding-and-rolling-back "Sembrar y revertir")
- Instantáneas de almacenamiento
  - [Exportar una instantánea](#exporting-a-snapshot "Exportar una instantánea")
  - [Cargar una instantánea](#loading-a-snapshot "Cargar una instantánea")
- [Acciones de página](#page-actions "Llamar a una página desde fuera de ella")

<div id="introduction"></div>

## Introducción

<x-doc-strip label="Requiere" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

**Nylo Live** permite que Metro acceda a tu app mientras se está ejecutando en modo debug (o profile), a través del Dart VM service. Encuentra la app a través del Dart Tooling Daemon sin necesidad de configuración, y solo se conecta a apps compiladas desde el proyecto actual.

Nylo Live está activo por defecto en builds debug y profile; un build release no registra nada. Los comandos que solo leen la app (`live:status`, `live:run data`, `live:run routes`, ...) también funcionan en builds profile. Los comandos que modifican la app (`route`, `storage`, `auth`, seeders, ...) necesitan un build debug.

Desactívalo tú mismo, antes de que se ejecute cualquier otra cosa, desde un proveedor:

``` dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.useLive(false);
    return nylo;
  }

  @override
  boot(Nylo nylo) async {}
}
```

<div id="metro-live-shell"></div>

## El shell de metro live

<x-doc-strip label="Requiere" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Abre un shell conectado a tu app en ejecución:

``` bash
metro live
```

Nylo Live descubre las apps en ejecución del proyecto y se conecta a una -- te pregunta cuál elegir cuando hay más de una en ejecución. Una vez conectado, el prompt muestra el dispositivo y la ruta que está actualmente en pantalla:

``` plaintext
Nylo Live · my_app
Connected to iPhone 15 Pro (debug)
Type help to see commands, and exit to leave.
iPhone 15 Pro /home ›
```

Los comandos live omiten su prefijo `metro live:` dentro del shell -- `status` en lugar de `metro live:status`, `route /profile` en lugar de `metro live:run route /profile`. Algunos comandos solo se ejecutan dentro del shell: `seed`, `seed:rollback`, `export`, `reload` y `restart`. Escribe `help` para listar todos los comandos, o `help <command>` para ver las opciones de un comando. Escribe `exit` (o Ctrl+D) para salir.

Si la app se reinicia mientras estás conectado, el shell se reconecta automáticamente.

<div id="shared-options"></div>

### Opciones compartidas

Todo comando live -- dentro del shell o como `metro live:*` -- acepta las mismas opciones:

| Opción | Descripción |
|--------|-------------|
| `-d, --device <#\|name>` | Selecciona una app por su número en `metro live:devices` o por el nombre del dispositivo |
| `--all` | Ejecutar en todas las apps en ejecución de este proyecto |
| `--json` | Imprimir JSON legible por máquina |
| `--uri` | Usar esta dirección de VM service en lugar de descubrir apps |
| `--timeout` | Segundos a esperar por cada app al descubrir (predeterminado `3`) |

Un valor de opción escrito como `@path` se lee desde ese archivo -- útil para un payload grande de `--data`:

``` bash
metro live:run route /profile --data @user.json
```

Empieza el valor con `@@` cuando realmente comienza con `@`.

<div id="tab-completion-and-history"></div>

### Autocompletado con Tab e historial

Dentro del shell, Tab autocompleta nombres de comandos, opciones, rutas, claves de almacenamiento y de Backpack, y nombres de seeders según la app conectada. El historial se guarda en `.dart_tool/nylo/live_history` y persiste entre sesiones.

<div id="running-a-script"></div>

### Ejecutar un script

Redirige un archivo hacia `metro live` para ejecutarlo como un script, un comando por línea, deteniéndose en el primer comando que falle:

``` bash
metro live < seeds/demo.live
```

Las líneas en blanco y las que comienzan con `#` se omiten.

<div id="live-devices"></div>

## live:devices

<x-doc-strip label="Requiere" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Lista las apps en ejecución de este proyecto, numeradas para `-d`:

``` bash
metro live:devices
```
``` plaintext
Running apps for my_app:
#  DEVICE          PLATFORM  MODE   ROUTE   APP
1  iPhone 15 Pro   ios       debug  /home   my_app
2  Pixel 8         android   debug  /login  my_app
```

<div id="live-status"></div>

## live:status

<x-doc-strip label="Requiere" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Muestra la app, el dispositivo, el modo de build, la ruta y el stack actuales, el locale, el tema, y si hay un usuario autenticado:

``` bash
metro live:status
```
``` plaintext
App            my_app (development)
Device         iPhone 15 Pro · ios 17.4
Mode           debug
Route          /home
Stack          /home
Locale         en
Theme          default
Authenticated  yes
Live commands  cart:seed
Seeders        demo_user
```

<div id="live-run"></div>

## live:run

<x-doc-strip label="Requiere" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Ejecuta un comando integrado que inspecciona o controla la app:

``` bash
metro live:run <command> [arguments]
```

| Comando | Descripción |
|---------|-------------|
| `data` | Muestra los datos y campos de la página en pantalla |
| `routes` | Lista las rutas registradas |
| `route` | Abre una página, con `--data` opcional |
| `back` | Retrocede una página, o vuelve a una ruta |
| `deeplink` | Abre un deep link, o muestra cómo están configurados |
| `storage` | Lista el almacenamiento local, o muestra, guarda o elimina un valor |
| `storage:clear` | Limpia el almacenamiento local, con `--keep` opcional |
| `backpack` | Lista los valores de Backpack, o muestra, guarda o elimina uno |
| `auth` | Muestra quién está autenticado, o inicia y cierra sesión |
| `locale` | Cambia el idioma de la app |
| `theme` | Cambia el tema de la app |
| `state` | Envía datos a un estado con `updateState` |
| `event` | Dispara uno de tus eventos |
| `toast` | Muestra una notificación toast |

Dentro de `metro live`, omite la parte `live:run` -- `route /profile` en lugar de `metro live:run route /profile`:

``` bash
metro live:run route /profile --data '{"id": 42}'
metro live:run storage SK_COINS 10
metro live:run backpack auth_user
metro live:run auth login --data '{"id": 42}'
metro live:run deeplink myapp://product/42
metro live:run state HomePage --data '{"count": 2}'
metro live:run event LogoutEvent --data '{"reason": "test"}'
metro live:run toast "Item saved" --title Success
```

`data` también lee los campos que declara el estado de tu página, directamente desde la app en ejecución -- no hay reflection en Flutter, así que esto solo funciona para un estado que esté realmente en pantalla:

``` bash
metro live:run data HomePage
```

<div id="live-commands"></div>

## Comandos Live

<x-doc-strip label="Requiere" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Registra tus propios comandos para ejecutarlos dentro de la app, junto a los integrados de arriba.

<div id="creating-a-live-command"></div>

### Crear un comando Live

``` bash
metro make:command seed_cart --live
```

| Opción | Descripción |
|--------|-------------|
| `--category`, `-c` | La categoría del comando (predeterminado: `app`) |
| `--description` | Una descripción de una línea mostrada por el `--help` del comando |
| `--force`, `-f` | Crea el archivo incluso si ya existe |

Esto crea un `LiveCommand` en `lib/app/commands/`, lo marca como `"type": "live"` en `commands.json`, lo registra en `lib/bootstrap/live_commands.dart`, y conecta `liveCommands: liveCommands` en `nylo.configure(...)` dentro de `app_provider.dart`:

``` dart
class SeedCartCommand extends LiveCommand {
  @override
  CommandBuilder builder(CommandBuilder command) {
    command.addOption('count', abbr: 'c', defaultValue: '3');
    command.addFlag('open', help: 'Open the cart afterwards');
    return command;
  }

  @override
  Future<void> handle(CommandResult result) async {
    final int count = result.getInt('count') ?? 3;
    await CartItem.seed(count: count);
    success('Seeded $count cart items');
  }
}
```

`LiveCommand` comparte la forma `builder`/`handle` con los propios comandos de Metro, pero `handle` se ejecuta **dentro de la app**, por lo que puede usar `NyStorage`, `routeTo`, `Auth` y tus modelos. `package:nylo_framework/live.dart` exporta `LiveCommand` junto con `Seeder`, `StorageSnapshot`, `LiveException` y `LiveOutput`.

<div id="running-a-live-command"></div>

### Ejecutar un comando Live

Un comando live nuevo necesita un hot restart antes de que la app en ejecución pueda ejecutarlo. Luego, desde la terminal:

``` bash
metro app:seed_cart --count 5
```

<div id="seeders"></div>

<!-- uncertain: "Seeder" term has no established es translation in the existing locale files; kept as loanword, matching how "Backpack" is treated -->

## Seeders

<x-doc-strip label="Requiere" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Un `Seeder` pone tu app en ejecución en un estado conocido -- autenticado, onboarding completado, registros de ejemplo -- y luego lo revierte.

<div id="creating-a-seeder"></div>

### Crear un Seeder

``` bash
metro make:seeder demo_user
```

| Opción | Descripción |
|--------|-------------|
| `--description` | Una descripción de una línea, mostrada cuando `seed` lista los seeders |
| `--force`, `-f` | Crea el archivo incluso si ya existe |

Esto crea un `Seeder` en `lib/app/seeders/`, lo registra en `lib/bootstrap/seeders.dart`, y conecta `seeders: seeders` en `nylo.configure(...)`:

``` dart
class DemoUserSeeder extends Seeder {
  @override
  String get description => 'Jane Doe, signed in, onboarding done';

  @override
  Future<void> up() async {
    await Auth.authenticate(data: {'name': 'Jane Doe', 'token': 'demo'});
    await saveToStorage({'onboarding_complete': true});
    success('Signed in as Jane Doe');
  }

  @override
  Future<void> down() async {
    await restore();
  }
}
```

Cada valor de almacenamiento y de Backpack que `up()` cambia se registra automáticamente, así que el `down()` predeterminado (`restore()`) devuelve cada uno exactamente a como estaba, en orden inverso -- incluso a través de un hot restart. Llama a `seed(otherSeeders)` desde dentro de un seeder para ejecutar otros como parte de él; sus cambios también se registran y se revierten.

<div id="seeding-and-rolling-back"></div>

<!-- uncertain: "seeding" rendered as "sembrar" -- no precedent for this term in the existing es docs -->

### Sembrar y revertir

Sembrar, revertir y exportar solo se ejecutan dentro del shell de `metro live`:

``` plaintext
› seed demo_user
Seeding demo_user
  + storage    SK_ONBOARDING  added
  ✓ Signed in as Jane Doe
✓ Seeded demo_user in 42ms
Undo with seed:rollback demo_user

› seed:rollback demo_user
Rolling back demo_user
  - storage    SK_ONBOARDING  removed
✓ Rolled back demo_user in 8ms
```

`seed` por sí solo lista los seeders registrados. `--fresh` limpia el almacenamiento y Backpack antes de sembrar, y `--restart` hace un hot restart de la app después, para que arranque ya sembrada:

``` plaintext
› seed demo_user --fresh --restart
```

<div id="storage-snapshots"></div>

## Instantáneas de almacenamiento

<x-doc-strip label="Requiere" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

`export` guarda todo lo que la app tiene actualmente en almacenamiento y Backpack -- como un seeder, o como un archivo JSON portátil -- y `seed <file>` lo vuelve a cargar.

<div id="exporting-a-snapshot"></div>

### Exportar una instantánea

Ejecútalo sin nombre para previsualizar qué se capturaría:

``` plaintext
› export
Storage on iPhone 15 Pro (2 values, 96 B)
KEY          TYPE    VALUE
SK_THEME     string  dark
SK_USER      model   {id: 1, name: Jane Doe}

Backpack (0 values)
Backpack is empty

Save it as a seeder with export <name>, or as a file with export --to <path>.json
```

Dale un nombre para guardarlo como un seeder en `lib/app/seeders/` (igual que `make:seeder`, pero ya completado con los valores capturados):

``` plaintext
› export pro_user
```

O, en su lugar, escríbelo en un archivo:

``` plaintext
› export pro_user --to snapshots/pro_user.json
```

| Opción | Descripción |
|--------|-------------|
| `--to <path>` | Escribe la instantánea en este archivo JSON en lugar de un seeder |
| `--description` | Una descripción de una línea para el seeder |
| `--only <keys>` | Claves separadas por comas a exportar; `*` coincide con cualquier cosa, p. ej. `--only SK_USER,onboarding_*` |
| `--except <keys>` | Claves separadas por comas a excluir, p. ej. `--except 'cache_*'` |
| `--backpack` / `--no-backpack` | Incluir valores de Backpack (predeterminado: incluidos) |
| `--force`, `-f` | Reemplaza un seeder o archivo que ya exista |

Un `Model`, o cualquier clase con un decoder registrado, se etiqueta por su nombre de clase para que vuelva a través de su propio decoder en lugar de como un mapa simple. `export` advierte cuando la instantánea contiene claves que parecen credenciales, y cuando es lo bastante grande como para que probablemente quieras usar `--except`.

<div id="loading-a-snapshot"></div>

### Cargar una instantánea

``` plaintext
› seed snapshots/pro_user.json
```

Cargar un archivo se registra de la misma manera que un seeder, así que `seed:rollback <name>` (el nombre del archivo, o el que le haya dado `--as`) también lo deshace. Desde el código, un seeder puede aplicar una instantánea capturada con `importSnapshot`:

``` dart
class ProUserSeeder extends Seeder {
  @override
  Future<void> up() async {
    await importSnapshot(snapshot);
  }

  static const Map<String, Object?> snapshot = {
    'storage': {
      'SK_USER': {'type': 'model', 'model': 'User', 'value': {'id': 1}},
    },
    'backpack': {},
  };
}
```

`export` escribe exactamente esta forma, así que un archivo escrito con `--to` puede pegarse directamente en el campo `snapshot` de un seeder.

<div id="page-actions"></div>

## Acciones de página

<x-doc-strip label="Requiere" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

`path.actions` en una página te da un `PageStateActions` para llamar a esa página desde fuera de ella, sin construir manualmente una llamada a `stateAction(...)`:

``` dart
class ProductPage extends NyStatefulWidget {
  static RouteView path = ("/product", (_) => ProductPage());
  static final actions = path.actions;

  ProductPage({super.key}) : super(child: () => _ProductPageState());
}

ProductPage.actions.showToast("Added to bag");
ProductPage.actions.refreshPage();
```

Consulta [Llamar a las acciones de una página](/docs/{{ $version }}/state-management#page-actions-shortcut) para ver el conjunto completo de acciones integradas y cómo declarar tus propias acciones tipadas. Nylo Live lee y usa las mismas acciones: `metro live:run data` lista lo que una página en pantalla expone bajo `actions`, y `metro live:run state` envía datos a una página directamente por el nombre de su estado.
