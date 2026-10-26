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
- [Playbooks](#playbooks "Reproducir playbooks desde Metro")
- [Grabar un recorrido](#recording-a-journey "Grabar lo que haces como un playbook")
- [Capturas de pantalla](#screenshots "Guardar capturas de pantalla de la app en ejecución")
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

Los comandos live omiten su prefijo `metro live:` dentro del shell -- `status` en lugar de `metro live:status`, `route /profile` en lugar de `metro live:run route /profile`. Algunos comandos solo se ejecutan dentro del shell: `seed`, `seed:rollback`, `export`, `record`, `reload` y `restart`. Escribe `help` para listar todos los comandos, o `help <command>` para ver las opciones de un comando. Escribe `exit` (o Ctrl+D) para salir.

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

Dentro del shell, Tab autocompleta nombres de comandos, opciones, rutas, claves de almacenamiento y de Backpack, nombres de seeders y nombres de playbooks según la app conectada. El historial se guarda en `.dart_tool/nylo/live_history` y persiste entre sesiones.

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
Playbooks      sign_up
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
| `playbook` | Reproduce tus [playbooks](/docs/{{ $version }}/playbooks), o los lista |
| `screenshot` | Guarda una [captura de pantalla](#screenshots) de la app, o de una `ScreenshotArea` |

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

<div id="playbooks"></div>

## Playbooks

<x-doc-strip label="Requiere" items="{{ config('app.name') }} 7.3.0+, nylo_support 7.32.0+" />

Reproduce uno o varios [playbooks](/docs/{{ $version }}/playbooks) en tu app en ejecución. Cada paso se imprime conforme ocurre:

``` bash
metro live:run playbook sign_up
metro live:run playbook sign_up add_to_bag --speed fast
```

Indica varios playbooks para reproducirlos uno tras otro. Sin nombres, o con `--list`, el comando lista los playbooks de la app.

| Opción | Descripción |
|--------|-------------|
| `--speed instant\|fast\|natural\|slow` | Reproduce a este ritmo en lugar del propio de cada playbook |
| `--data <json>` | Un objeto JSON que todos los playbooks de la ejecución leen en `data`, por encima de sus valores por defecto |
| `--no-overlay` | Oculta la cuenta atrás, y el dedo y los subtítulos de los playbooks que los muestran |
| `--loop` | Reproduce los playbooks una y otra vez hasta pulsar Ctrl+C |
| `--record[=<file>]` | Graba la pantalla del dispositivo durante la ejecución. Se guarda en `build/playbooks/<playbooks>-<device>-<time>.mp4`; `--record=demo.mp4` (o `.gif`) elige el archivo, relativo al proyecto. Requiere ffmpeg |
| `--no-captions` | Omite el archivo de subtítulos `.vtt` y los capítulos de una grabación |
| `--clean-status-bar` | Muestra 9:41 con señal y batería completas mientras se reproducen los playbooks y luego lo restaura. También funciona sin `--record` |
| `--countdown <seconds>` | Cuenta atrás de 0 a 10 segundos en pantalla antes del primer paso |
| `--screenshots[=<mode>]` | Guarda también la pantalla de cada paso. `<mode>` es `all`, `auto`, `marked`, `failures` u `off`; consulta [Capturas de playbooks](#playbook-screenshots) |
| `--scale <n>` | El tamaño de las capturas relativo al del propio dispositivo, hasta 4, p. ej. `0.5` para la mitad |
| `--locales <list>` | Reproduce una vez en cada una de estas configuraciones regionales, p. ej. `en,es`, o `all` las de la app |
| `--themes <list>` | Reproduce una vez en cada uno de estos temas, p. ej. `light,dark`, o `all` los de la app |
| `--store <id>` | Dibuja las capturas con el tamaño de una tienda de apps, p. ej. `ios-6.9` |
| `--frame` | Dibuja cada pantalla dentro del contorno de un teléfono |
| `--background <hex>` | El color alrededor de la pantalla, p. ej. `"#1B2027"` |
| `--list` | Lista los playbooks de la app en lugar de reproducir uno |

Ctrl+C pide a la app que se detenga: los pasos restantes se omiten, pero `tearDown` se ejecuta igualmente. Pulsa Ctrl+C una segunda vez para dejar de esperar.

Si ningún paso llega a Metro en 20 segundos, Metro deja de esperar y la ejecución falla. La app está reproduciendo el playbook, pero sus eventos no están llegando, algo que ocurre cuando la app se inició con `flutter run --no-dds`. Ejecútala con un `flutter run` normal e inténtalo de nuevo.

Dentro del shell `metro live` el comando es `playbook <name>`, y Tab autocompleta los nombres de playbooks:

``` plaintext
› playbook sign_up --speed fast
```

<div id="recording-playbooks"></div>

### Grabación

<x-doc-strip label="Requiere" items="{{ config('app.name') }} 7.4.0+, nylo_support 7.33.0+" />

Añade `--record` para guardar un vídeo de la ejecución:

``` bash
metro live:run playbook sign_up --record
metro live:run playbook sign_up --record=docs/sign-up.gif --speed slow
metro live:run playbook sign_up --record --clean-status-bar --countdown 3
```

Dentro del shell `metro live`, escribe `playbook sign_up --record`.

La grabación requiere ffmpeg (por ejemplo `brew install ffmpeg`). Metro lo comprueba antes de reproducir nada.

- **iOS Simulator** -- se graba con `xcrun simctl`.
- **Emulador o dispositivo Android** -- se graba con `adb screenrecord`, que se detiene tras 3 minutos, o con `scrcpy` si está instalado (sin límite, y graba sonido en Android 11+). Si hay varios dispositivos Android conectados y Metro no puede saber cuál ejecuta la app, define `ANDROID_SERIAL`.
- **iPhone físico** -- no se puede grabar desde la línea de comandos. Graba con QuickTime o el Centro de control y usa `--countdown 3` para tener tiempo de empezar.

El resultado es un `.mp4` (H.264, 60 fps constantes) que dura exactamente lo que la ejecución, o un `.gif` de 480px de ancho a 15 fps. Cada paso se convierte en un subtítulo en un archivo `.vtt` junto al `.mp4` y en un capítulo dentro de él. Se generan a partir de los pasos de la ejecución, tanto si el playbook muestra subtítulos en pantalla como si no. Pasa `--no-captions` para omitirlos.

``` plaintext
· Recording iPhone 17
Playbook "sign_up" (natural)
  ✓   240ms  Tap "Create account"
  ...
✓ Playbook "sign_up" passed in 9.8s
· Saving the recording
✓ Saved build/playbooks/sign_up-iphone-17-20261014-101530.mp4 (11.2s)
· Step captions in build/playbooks/sign_up-iphone-17-20261014-101530.vtt (8 steps)
```

Un playbook que falla o se detiene también se guarda, así que una grabación sirve además como reporte de errores. Ctrl+C detiene el playbook (`tearDown` se ejecuta igualmente) y la grabación se guarda de todos modos.

`--clean-status-bar` conserva una barra de estado que ya hayas definido con `simctl status_bar` en el Simulator. En Android usa el modo demo y lo desactiva después. Con varias apps (`--all`), cada dispositivo obtiene su propio archivo con el nombre del dispositivo.

Un playbook oculta por defecto su dedo y sus subtítulos. Para un vídeo de demo, define `showFinger` y `caption` en el playbook -- consulta [Dedo y subtítulo](/docs/{{ $version }}/playbooks#finger-and-caption).

<div id="playbook-screenshots"></div>

### Capturas de pantalla

<x-doc-strip label="Requiere" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

Los [pasos `screenshot()`](/docs/{{ $version }}/playbooks#screenshots) de un playbook se guardan en `screenshots/<playbook>/` sin necesidad de ningún flag. Añade opciones para capturarlos en todos los idiomas y temas, o para darles el tamaño de una ficha de tienda:

``` bash
metro live:run playbook checkout
metro live:run playbook checkout --locales en,es --themes light,dark
metro live:run playbook checkout --store ios-6.9 --frame --background "#1B2027"
metro live:run playbook checkout --screenshots --scale 0.5
metro live:run playbook checkout --locales all --clean-status-bar
```

`--screenshots` también guarda la pantalla después de cada paso de un playbook que no tiene pasos `screenshot()`, y `--screenshots=all` lo hace para todos los playbooks. La pantalla de un paso que falla se guarda en `build/playbooks/failures`; `--screenshots=off` lo desactiva.

`--locales` y `--themes` reproducen el playbook una vez en cada uno, y después se restauran la configuración regional y el tema propios de la app. `--store` lista los tamaños cuando no tiene valor, y `--frame` dibuja cada pantalla en el contorno de un teléfono con el `caption:` del paso encima. `--clean-status-bar` muestra 9:41 con señal y batería completas en las capturas que Metro toma con el iOS Simulator o `adb`.

<div id="playbook-live-commands"></div>

### Referencia de comandos live

Los comandos que hay detrás son `playbooks.list`, `playbooks.run` y `playbooks.stop`. Una ejecución devuelve su id de inmediato y emite cada paso como un evento `nylo.playbook`, por lo que puede durar más que el timeout de un comando. `live:status` lista los playbooks de la app. Los playbooks llegaron con la versión 9 del protocolo Nylo Live. La compatibilidad con la grabación -- `countdown` en `playbooks.run` (con un evento `countdown` cada segundo) y `simulator` (el UDID del iOS Simulator) en `status` -- llegó con la versión 10 del protocolo.

<div id="recording-a-journey"></div>

## Grabar un recorrido

<x-doc-strip label="Requiere" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.36.0+" />

`record` escribe lo que haces en la app en ejecución como un [playbook](/docs/{{ $version }}/playbooks#recording-a-playbook). Solo se ejecuta dentro del shell `metro live`:

``` plaintext
› record sign_up
```

Usa la app y pulsa Enter cuando termine el recorrido. El playbook se guarda en `lib/app/playbooks/sign_up_playbook.dart`, registrado en `lib/bootstrap/playbooks.dart`, y Metro se ofrece a hacer un hot restart de la app y reproducirlo a velocidad `fast`.

| Opción | Descripción |
|--------|-------------|
| `--description <text>` | Una descripción de una línea, que se muestra cuando Metro lista los playbooks |
| `--no-data` | Escribe los valores tecleados tal como se tecleron, en lugar de marcadores `data[...]` con sus valores por defecto |
| `--no-replay` | Omite la pregunta sobre reproducirlo |
| `--force`, `-f` | Reemplaza un archivo de playbook que ya existe |

Mientras graba, escribe `a` y Enter para que el siguiente toque sea un `see()`, `d` para un `dontSee()`, `s <title>` para iniciar una escena, `u` para deshacer el último paso y `p` para pausar. Ctrl+C descarta la grabación. `metro make:playbook sign_up --record` abre el shell e inicia la grabación de una sola vez.

Los comandos que hay detrás son `record.start`, `record.stop`, `record.undo`, `record.mark` y `record.status`. Una grabación emite cada paso como un evento `nylo.record`. La grabación llegó con la versión 11 del protocolo Nylo Live.

<div id="screenshots"></div>

## Capturas de pantalla

<x-doc-strip label="Requiere" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

Guarda un PNG de tu app en ejecución:

``` bash
metro live:run screenshot
metro live:run screenshot --open
metro live:run screenshot --area receipt --copy
metro live:run screenshot --store ios-6.9 --frame
```

Cada archivo se guarda en `screenshots/` dentro de tu proyecto, con el nombre `<mode>-<device>-<date>-<time>.png`. En el iOS Simulator y en Android, Metro captura la pantalla completa del dispositivo con el Simulator o `adb`, así que la barra de estado y el teclado aparecen en ella. Cualquier otro dispositivo usa la captura propia de la app, que son las páginas de la app tal como las ve el usuario.

| Opción | Descripción |
|--------|-------------|
| `--area <name>` | Captura solo la `ScreenshotArea` con este nombre |
| `--scale <n>` | El tamaño relativo al del propio dispositivo, hasta 4, p. ej. `0.5` para la mitad |
| `--locales <list>` | Captura la pantalla en cada una de estas configuraciones regionales, p. ej. `en,es`, o `all` las de la app |
| `--themes <list>` | Captura la pantalla en cada uno de estos temas, p. ej. `light,dark`, o `all` los de la app |
| `--store <id>` | Dibuja con el tamaño de captura de una tienda de apps, p. ej. `ios-6.9`. Por sí solo, `--store` lista los tamaños |
| `--frame` | Dibuja la pantalla dentro del contorno de un teléfono, sobre un fondo |
| `--caption <text>` | Texto encima del `--frame`, o una clave de traducción |
| `--background <hex>` | El color alrededor de la pantalla, detrás de un `--frame` o junto a un tamaño de `--store` de otra proporción, p. ej. `"#1B2027"` |
| `--out <path>` | Guarda en este `.png`, o en una carpeta que termine en `/` (por defecto: `screenshots/`) |
| `--open` | Abre la captura una vez guardada |
| `--copy` | Copia la captura al portapapeles |
| `--native` | Captura con el iOS Simulator o `adb`. Es el valor por defecto para una captura de pantalla completa; `--no-native` usa la captura propia de la app |
| `--clean-status-bar` | Muestra 9:41 con señal y batería completas en una captura `--native` |

Con `--locales` o `--themes`, Metro guarda un PNG por cada combinación en una carpeta, con nombres como `es.png`, `dark.png` o `es-dark.png`, cambiando la app en el sitio y restaurándola después. `--area`, `--scale`, `--store` y `--frame` usan la captura propia de la app.

Dentro del shell `metro live`, escribe `screenshot --open`. Para tomar capturas desde el código de tu propia app, consulta [Capturas de pantalla](/docs/{{ $version }}/screenshots).

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
