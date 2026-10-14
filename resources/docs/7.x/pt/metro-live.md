# Metro Live

<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [Introdução](#introduction "Introdução ao Nylo Live")
- O Shell metro live
  - [Opções Compartilhadas](#shared-options "Opções que todo comando live aceita")
  - [Autocompletar e Histórico](#tab-completion-and-history "Autocompletar com Tab e histórico")
  - [Executando um Script](#running-a-script "Executando um script .live")
- [live:devices](#live-devices "Listar apps em execução")
- [live:status](#live-status "Mostrar status do app")
- [live:run](#live-run "Executar um comando live embutido")
- [Playbooks](#playbooks "Executando playbooks pelo Metro")
- Comandos Live
  - [Criando um Live Command](#creating-a-live-command "Criando um live command")
  - [Executando um Live Command](#running-a-live-command "Executando um live command")
- Seeders
  - [Criando um Seeder](#creating-a-seeder "Criando um seeder")
  - [Seeding e Rollback](#seeding-and-rolling-back "Seeding e rollback")
- Snapshots de Armazenamento
  - [Exportando um Snapshot](#exporting-a-snapshot "Exportando um snapshot")
  - [Carregando um Snapshot](#loading-a-snapshot "Carregando um snapshot")
- [Ações de Página](#page-actions "Chamando uma página de fora dela")

<div id="introduction"></div>

## Introdução

<x-doc-strip label="Requer" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

O **Nylo Live** permite que o Metro alcance seu app enquanto ele está rodando em modo debug (ou profile), através do Dart VM service. Ele encontra o app através do Dart Tooling Daemon sem nenhuma configuração, e só se conecta a apps construídos a partir do projeto atual.

O Nylo Live vem ativado por padrão em builds debug e profile; um build release não registra nada. Comandos que apenas leem o app (`live:status`, `live:run data`, `live:run routes`, ...) funcionam em builds profile também. Comandos que alteram o app (`route`, `storage`, `auth`, seeders, ...) precisam de um build debug.

Desative-o você mesmo, antes de qualquer outra coisa rodar, a partir de um provider:

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

## O Shell metro live

<x-doc-strip label="Requer" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Abra um shell conectado ao seu app em execução:

``` bash
metro live
```

O Nylo Live descobre os apps em execução do projeto e se conecta a um -- ele pede para você escolher quando há mais de um em execução. Uma vez conectado, o prompt mostra o dispositivo e a rota atualmente em tela:

``` plaintext
Nylo Live · my_app
Connected to iPhone 15 Pro (debug)
Type help to see commands, and exit to leave.
iPhone 15 Pro /home ›
```

Comandos live derrubam o prefixo `metro live:` dentro do shell -- `status` em vez de `metro live:status`, `route /profile` em vez de `metro live:run route /profile`. Alguns comandos só rodam dentro do shell: `seed`, `seed:rollback`, `export`, `reload` e `restart`. Digite `help` para listar todos os comandos, ou `help <command>` para ver as opções de um comando. Digite `exit` (ou Ctrl+D) para sair.

Se o app reiniciar enquanto você estiver conectado, o shell se reconecta sozinho.

<div id="shared-options"></div>

### Opções Compartilhadas

Todo comando live -- dentro do shell ou como `metro live:*` -- aceita as mesmas opções:

| Opção | Descrição |
|--------|-------------|
| `-d, --device <#\|name>` | Direcionar um app pelo seu número em `metro live:devices` ou pelo nome do dispositivo |
| `--all` | Rodar em todos os apps em execução deste projeto |
| `--json` | Imprimir JSON legível por máquina |
| `--uri` | Usar este endereço do VM service em vez de descobrir apps |
| `--timeout` | Segundos para aguardar cada app durante a descoberta (padrão `3`) |

Um valor de opção escrito `@path` é lido a partir desse arquivo -- útil para um payload `--data` grande:

``` bash
metro live:run route /profile --data @user.json
```

Comece o valor com `@@` quando ele realmente começar com `@`.

<div id="tab-completion-and-history"></div>

### Autocompletar e Histórico

Dentro do shell, Tab autocompleta nomes de comandos, opções, rotas, chaves de storage e Backpack, nomes de seeders e nomes de playbooks contra o app conectado. O histórico é mantido em `.dart_tool/nylo/live_history` e sobrevive entre sessões.

<div id="running-a-script"></div>

### Executando um Script

Direcione um arquivo para dentro do `metro live` para executá-lo como um script, um comando por linha, parando no primeiro comando que falhar:

``` bash
metro live < seeds/demo.live
```

Linhas em branco e linhas começando com `#` são ignoradas.

<div id="live-devices"></div>

## live:devices

<x-doc-strip label="Requer" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Lista os apps em execução deste projeto, numerados para `-d`:

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

<x-doc-strip label="Requer" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Mostra o app, dispositivo, modo de build, rota e pilha atuais, locale, tema, e se um usuário está autenticado:

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

<x-doc-strip label="Requer" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Executa um comando embutido que inspeciona ou controla o app:

``` bash
metro live:run <command> [arguments]
```

| Comando | Descrição |
|---------|-------------|
| `data` | Mostrar os dados e campos da página em tela |
| `routes` | Listar as rotas registradas |
| `route` | Abrir uma página, com `--data` opcional |
| `back` | Voltar uma página, ou voltar para uma rota |
| `deeplink` | Abrir um deep link, ou mostrar como eles são configurados |
| `storage` | Listar o storage local, ou mostrar, salvar ou deletar um valor |
| `storage:clear` | Limpar o storage local, com `--keep` opcional |
| `backpack` | Listar valores do Backpack, ou mostrar, salvar ou deletar um |
| `auth` | Mostrar quem está autenticado, ou autenticar e desautenticar |
| `locale` | Trocar o idioma do app |
| `theme` | Trocar o tema do app |
| `state` | Enviar dados para um estado com `updateState` |
| `event` | Disparar um dos seus eventos |
| `toast` | Mostrar uma notificação toast |
| `playbook` | Executa seus [playbooks](/docs/{{ $version }}/playbooks), ou os lista |

Dentro do `metro live`, remova a parte `live:run` -- `route /profile` em vez de `metro live:run route /profile`:

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

`data` também lê os campos que o estado da sua página declara, direto do app em execução -- não há reflection no Flutter, então isso só funciona para um estado que está de fato em tela:

``` bash
metro live:run data HomePage
```

<div id="playbooks"></div>

## Playbooks

<x-doc-strip label="Requer" items="{{ config('app.name') }} 7.3.0+, nylo_support 7.32.0+" />

Execute um ou mais [playbooks](/docs/{{ $version }}/playbooks) no seu app em execução. Cada passo é impresso conforme acontece:

``` bash
metro live:run playbook sign_up
metro live:run playbook sign_up add_to_bag --speed fast
```

Informe vários playbooks para executá-los em sequência. Sem nomes, ou com `--list`, o comando lista os playbooks do app.

| Opção | Descrição |
|--------|-------------|
| `--speed instant\|fast\|natural\|slow` | Executa neste ritmo em vez do ritmo próprio de cada playbook |
| `--data <json>` | Um objeto JSON que todo playbook da execução lê em `data`, sobrepondo seus padrões |
| `--no-overlay` | Oculta a contagem regressiva, e o dedo e as legendas dos playbooks que os exibem |
| `--loop` | Executa os playbooks repetidamente até pressionar Ctrl+C |
| `--record[=<file>]` | Grava a tela do dispositivo durante a execução. Salvo em `build/playbooks/<playbooks>-<device>-<time>.mp4`; `--record=demo.mp4` (ou `.gif`) escolhe o arquivo, relativo ao projeto. Requer ffmpeg |
| `--no-captions` | Omite o arquivo de legendas `.vtt` e os capítulos de uma gravação |
| `--clean-status-bar` | Mostra 9:41 com sinal e bateria cheios enquanto os playbooks são executados, e depois restaura. Funciona também sem `--record` |
| `--countdown <seconds>` | Faz uma contagem regressiva de 0-10 segundos na tela antes do primeiro passo |
| `--list` | Lista os playbooks do app em vez de executar um |

Ctrl+C pede ao app para parar: os passos restantes são ignorados, mas `tearDown` ainda é executado. Pressione Ctrl+C uma segunda vez para parar de esperar.

Dentro do shell `metro live`, o comando é `playbook <name>`, e Tab autocompleta os nomes dos playbooks:

``` plaintext
› playbook sign_up --speed fast
```

<div id="recording-playbooks"></div>

<!-- uncertain: newly added Recording section; no prior locale context for register or terminology (e.g. chapters, status bar) -->

### Gravação

<x-doc-strip label="Requer" items="{{ config('app.name') }} 7.4.0+, nylo_support 7.33.0+" />

Adicione `--record` para salvar um vídeo da execução:

``` bash
metro live:run playbook sign_up --record
metro live:run playbook sign_up --record=docs/sign-up.gif --speed slow
metro live:run playbook sign_up --record --clean-status-bar --countdown 3
```

Dentro do shell `metro live`, digite `playbook sign_up --record`.

A gravação requer ffmpeg (por exemplo `brew install ffmpeg`). O Metro verifica isso antes de qualquer coisa ser executada.

- **iOS Simulator** -- gravado com `xcrun simctl`.
- **Emulador ou dispositivo Android** -- gravado com `adb screenrecord`, que para após 3 minutos, ou com `scrcpy` quando instalado (sem limite, e grava som no Android 11+). Se vários dispositivos Android estiverem conectados e o Metro não conseguir saber qual executa o app, defina `ANDROID_SERIAL`.
- **iPhone físico** -- não pode ser gravado pela linha de comando. Grave com o QuickTime ou a Central de Controle e use `--countdown 3` para ter tempo de começar.

O resultado é um `.mp4` (H.264, 60 fps constantes) com exatamente a duração da execução, ou um `.gif` de 480px de largura a 15 fps. Cada passo vira uma legenda em um arquivo `.vtt` ao lado do `.mp4` e um capítulo dentro dele. Eles são gerados a partir dos passos da execução, quer o playbook mostre ou não legendas na tela. Passe `--no-captions` para ignorá-los.

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

Um playbook que falha ou é interrompido ainda assim é salvo, então uma gravação serve também como relatório de bug. Ctrl+C interrompe o playbook (`tearDown` ainda é executado) e a gravação continua sendo salva.

`--clean-status-bar` mantém uma barra de status que você já definiu com `simctl status_bar` no Simulator. No Android ele usa o modo demo e o desativa depois. Com vários apps (`--all`), cada dispositivo recebe seu próprio arquivo com o nome do dispositivo.

Um playbook oculta o dedo e as legendas por padrão. Para um vídeo de demonstração, defina `showFinger` e `caption` no playbook -- veja [Dedo e Legenda](/docs/{{ $version }}/playbooks#finger-and-caption).

<div id="playbook-live-commands"></div>

### Referência de Live Commands

Os comandos por trás disso são `playbooks.list`, `playbooks.run` e `playbooks.stop`. Uma execução retorna seu id imediatamente e transmite cada passo como um evento `nylo.playbook`, então pode durar mais que o timeout de um comando. `live:status` lista os playbooks do app. Os playbooks chegaram na versão 9 do protocolo Nylo Live. O suporte à gravação -- `countdown` em `playbooks.run` (com um evento `countdown` a cada segundo) e `simulator` (o UDID do iOS Simulator) em `status` -- chegou na versão 10 do protocolo.

<div id="live-commands"></div>

## Comandos Live

<x-doc-strip label="Requer" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Registre seus próprios comandos para rodar dentro do app, ao lado dos comandos embutidos acima.

<div id="creating-a-live-command"></div>

### Criando um Live Command

``` bash
metro make:command seed_cart --live
```

| Opção | Descrição |
|--------|-------------|
| `--category`, `-c` | A categoria do comando (padrão: `app`) |
| `--description` | Uma descrição de uma linha exibida pelo `--help` do comando |
| `--force`, `-f` | Cria o arquivo mesmo que já exista |

Isso cria um `LiveCommand` em `lib/app/commands/`, marca-o como `"type": "live"` em `commands.json`, o registra em `lib/bootstrap/live_commands.dart`, e conecta `liveCommands: liveCommands` em `nylo.configure(...)` em `app_provider.dart`:

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

`LiveCommand` compartilha o formato `builder`/`handle` com os próprios comandos do Metro, mas `handle` roda **dentro do app**, então pode usar `NyStorage`, `routeTo`, `Auth` e seus models. `package:nylo_framework/live.dart` exporta `LiveCommand` junto com `Seeder`, `StorageSnapshot`, `LiveException` e `LiveOutput`.

<div id="running-a-live-command"></div>

### Executando um Live Command

Um novo live command precisa de um hot restart antes que o app em execução possa rodá-lo. Depois, a partir do terminal:

``` bash
metro app:seed_cart --count 5
```

<div id="seeders"></div>

## Seeders

<x-doc-strip label="Requer" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Um `Seeder` coloca seu app em execução em um estado conhecido -- autenticado, onboarding concluído, registros de exemplo -- e o traz de volta ao estado original.

<div id="creating-a-seeder"></div>

### Criando um Seeder

``` bash
metro make:seeder demo_user
```

| Opção | Descrição |
|--------|-------------|
| `--description` | Uma descrição de uma linha, exibida quando `seed` lista os seeders |
| `--force`, `-f` | Cria o arquivo mesmo que já exista |

Isso cria um `Seeder` em `lib/app/seeders/`, o registra em `lib/bootstrap/seeders.dart`, e conecta `seeders: seeders` em `nylo.configure(...)`:

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

Todo valor de storage e Backpack que `up()` altera é registrado automaticamente, então o `down()` padrão (`restore()`) devolve cada um exatamente como estava, na ordem inversa -- mesmo depois de um hot restart. Chame `seed(otherSeeders)` de dentro de um seeder para rodar outros como parte dele; as alterações deles também são registradas e revertidas.

<div id="seeding-and-rolling-back"></div>

### Seeding e Rollback

Seeding, rollback e exportação só rodam dentro do shell `metro live`:

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

`seed` sozinho lista os seeders registrados. `--fresh` limpa o storage e o Backpack antes de fazer o seed, e `--restart` reinicia o app com hot restart em seguida, para que ele já suba com os dados semeados:

``` plaintext
› seed demo_user --fresh --restart
```

<div id="storage-snapshots"></div>

## Snapshots de Armazenamento

<x-doc-strip label="Requer" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

`export` salva tudo o que o app atualmente mantém em storage e Backpack -- como um seeder, ou como um arquivo JSON portátil -- e `seed <file>` carrega de volta.

<div id="exporting-a-snapshot"></div>

### Exportando um Snapshot

Execute sem nome para pré-visualizar o que seria capturado:

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

Dê um nome para salvá-lo como um seeder em `lib/app/seeders/` (o mesmo que `make:seeder`, mas já preenchido com os valores capturados):

``` plaintext
› export pro_user
```

Ou grave em um arquivo:

``` plaintext
› export pro_user --to snapshots/pro_user.json
```

| Opção | Descrição |
|--------|-------------|
| `--to <path>` | Grava o snapshot neste arquivo JSON em vez de um seeder |
| `--description` | Uma descrição de uma linha para o seeder |
| `--only <keys>` | Chaves separadas por vírgula para exportar; `*` corresponde a qualquer coisa, ex: `--only SK_USER,onboarding_*` |
| `--except <keys>` | Chaves separadas por vírgula para deixar de fora, ex: `--except 'cache_*'` |
| `--backpack` / `--no-backpack` | Incluir valores do Backpack (padrão: incluído) |
| `--force`, `-f` | Substitui um seeder ou arquivo que já existe |

Um `Model`, ou qualquer classe com um decoder registrado, é marcado pelo nome da classe para que volte através do seu próprio decoder em vez de como um mapa simples. `export` avisa quando o snapshot contém chaves que parecem credenciais, e quando ele é grande o suficiente para que você provavelmente queira usar `--except`.

<div id="loading-a-snapshot"></div>

### Carregando um Snapshot

``` plaintext
› seed snapshots/pro_user.json
```

Carregar um arquivo é registrado da mesma forma que um seeder, então `seed:rollback <name>` (o nome do arquivo, ou o que `--as` tiver definido) também desfaz isso. A partir do código, um seeder pode aplicar um snapshot capturado com `importSnapshot`:

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

`export` grava exatamente neste formato, então um arquivo gravado com `--to` pode ser colado diretamente no campo `snapshot` de um seeder.

<div id="page-actions"></div>

## Ações de Página

<x-doc-strip label="Requer" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

`path.actions` em uma página te dá um `PageStateActions` para chamar essa página de fora dela, sem construir uma chamada `stateAction(...)` manualmente:

``` dart
class ProductPage extends NyStatefulWidget {
  static RouteView path = ("/product", (_) => ProductPage());
  static final actions = path.actions;

  ProductPage({super.key}) : super(child: () => _ProductPageState());
}

ProductPage.actions.showToast("Added to bag");
ProductPage.actions.refreshPage();
```

Veja [Chamando as Ações de uma Página](/docs/{{ $version }}/state-management#page-actions-shortcut) para o conjunto completo de ações integradas e como declarar suas próprias ações tipadas. O Nylo Live lê e usa as mesmas ações: `metro live:run data` lista o que uma página em tela expõe em `actions`, e `metro live:run state` envia dados para uma página diretamente pelo nome do seu estado.
