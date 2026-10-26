# Playbooks
<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [Introdução](#introduction "Introdução aos Playbooks")
- [Criando um Playbook](#creating-a-playbook "Criando um playbook")
- [Gravando um Playbook](#recording-a-playbook "Gravando um playbook a partir do app em execução")
- [Passos](#steps "Os passos que um playbook pode executar")
- [Alvos](#targets "Encontrando elementos na tela")
  - [Strings Simples](#plain-strings "Como uma string simples é associada")
  - [Find](#find "Alvos precisos com Find")
- [Dados e Configuração](#data-and-setup "Dados, setUp e tearDown")
- [Executando Outros Playbooks](#playing-other-playbooks "Executando um playbook a partir de outro playbook")
- [Velocidade](#speed "PlaybookSpeed")
- [Dedo e Legenda](#finger-and-caption "O dedo e a legenda do passo")
- [Screenshots](#screenshots "Capturando screenshots durante um playbook")
  - [Idiomas e Temas](#screenshots-locales-and-themes "Capturando uma tela em todos os idiomas e temas")
  - [Coletando pelo Código](#collecting-screenshots "Coletando screenshots com onScreenshot")
- [Registrando Playbooks](#registering-playbooks "Registrando playbooks no Nylo")
- [Executando um Playbook](#running-a-playbook "Executando um playbook")
  - [Pelo Metro](#running-from-metro "Executando pelo Metro")
  - [Na Inicialização](#running-on-boot "Executando na inicialização")
  - [Pelo Dart](#running-from-dart "Executando pelo Dart")
- [Relatórios](#reports "PlaybookReport")
- [Builds de Release](#release-builds "Playbooks em builds de release")
- [Testes](#testing "Testando um playbook")

<div id="introduction"></div>

## Introdução

<x-doc-strip label="Requer" items="{{ config('app.name') }} 7.3.0+, nylo_support 7.32.0+" />

Um **playbook** é uma classe que lista os toques, a digitação, a rolagem e as mudanças de página de uma jornada de usuário. Seu app em execução as realiza por conta própria, e pode mostrar um dedo e uma legenda para cada passo -- útil para demos, gravações de tela e para verificar uma jornada de ponta a ponta.

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

Importe `package:nylo_framework/playbook.dart` sempre que escrever ou registrar playbooks.

<div id="creating-a-playbook"></div>

## Criando um Playbook

Use o Metro para criar um:

``` bash
metro make:playbook sign_up
```

Isso cria `lib/app/playbooks/sign_up_playbook.dart` e o registra no seu app. Veja [Make Playbook](/docs/{{ $version }}/metro#make-playbook) para as opções do comando.

Para começar a partir de todas as páginas que o seu router adiciona, passe `--from-routes`:

``` bash
metro make:playbook tour --from-routes
```

O Metro escreve um `visit()` e um [`screenshot()`](#screenshots) para cada página, então uma única execução fotografa o seu app inteiro.

<div id="recording-a-playbook"></div>

## Gravando um Playbook

<x-doc-strip label="Requer" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.36.0+" />

Em vez de escrever os passos, grave-os. Com o seu app rodando em modo debug, abra o `metro live` e digite:

``` plaintext
› record sign_up
```

Depois use o app. Cada toque, toque longo, campo digitado, rolagem e mudança de página é escrito como um passo no momento em que acontece, e impresso conforme você avança:

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

Pressione Enter para terminar. O playbook é escrito em `lib/app/playbooks/sign_up_playbook.dart`, com o mesmo layout de um criado por `make:playbook`, e registrado, então ele roda assim que o app reinicia. `metro make:playbook sign_up --record` faz o mesmo pelo terminal: abre o `metro live` e começa a gravar imediatamente.

Durante a gravação, digite uma tecla e pressione Enter:

| Tecla | Descrição |
|-----|-------------|
| `a` | O próximo toque é escrito como `see(...)` em vez de um toque |
| `d` | O próximo toque é escrito como `dontSee(...)` |
| `s <title>` | Inicia uma `scene` com esse título; os passos seguintes ficam aninhados nela |
| `u` | Remove o último passo |
| `p` | Pausa a gravação, por exemplo para entrar com credenciais reais; `p` novamente retoma |
| Enter | Termina e escreve o playbook |
| Ctrl+C | Descarta a gravação |

Os alvos são nomeados do jeito que o player os lê, texto visível primeiro, e cada nome é verificado no app em execução antes de ser escrito, então um passo gravado é reproduzido. Quando uma string simples corresponde a mais de um widget, o passo a restringe com `Find`: `tap(Find.text('Add').at(1))`. Um botão de ícone sem texto nem tooltip vira `tap(Find.icon(Icons.add))`. Um widget que nada nomeia é escrito como `tap(Find.type<GestureDetector>().at(3))` sob um comentário `// TODO` com o arquivo e a linha em que foi criado, e o Metro o lista quando a gravação termina. Dê a ele uma `Key` e use a key no lugar.

- **Digitação** vira um passo `type` que lê um placeholder `data[...]`, com o valor digitado em `defaults`. Um campo deixado com a tecla de ação do teclado recebe `submit: true`. Um campo ofuscado recebe `secret: true`, seu valor em `defaults` e um aviso para alterá-lo antes de fazer commit. Passe `--no-data` para escrever os valores como foram digitados.
- **Mudanças de página** após um toque viram `waitForPage(SignUpPage.path)` quando uma das suas páginas declara a rota, ou `waitForPage('/sign-up')` caso contrário. Voltar vira `back()`.
- **Rolagem** é escrita como `scrollTo(...)` no item em que você toca a seguir, e somente quando a rolagem o trouxe para a árvore. O player revela todo o resto por conta própria.
- **Gestos de deslizar que não são rolagens** -- um `Dismissible`, um `PageView`, um `Slider` -- ainda não têm um verbo de playbook, então ficam como um comentário `// TODO` com os pontos de início e fim.

A gravação precisa de um build de debug e funciona em simuladores, emuladores e dispositivos. Os toques do próprio playbook nunca são gravados. Veja [Metro Live](/docs/{{ $version }}/metro-live#recording-a-journey) para as opções do comando.

<div id="steps"></div>

## Passos

Os `steps` de um playbook são construídos com os verbos que ele herda:

| Passo | Descrição |
|------|-------------|
| `tap(target)` | Toca em um alvo |
| `longPress(target)` | Pressiona e segura um alvo |
| `type(target, text)` | Digita em um campo. Limpa-o antes, a menos que `clear: false`; `submit: true` pressiona o botão de ação do teclado; `secret: true` oculta o texto na legenda e no relatório |
| `scrollTo(target)` | Rola até que um alvo esteja na tela. Passe `within:` para rolar um scrollable específico |
| `see(target)` | Verifica se um alvo está na tela, esperando que ele apareça |
| `dontSee(target)` | Verifica se um alvo não está na tela, esperando que ele desapareça |
| `waitFor(target)` | Espera por um alvo, até `timeout` (10 segundos por padrão) |
| `waitForPage(path)` | Espera pela página em um route path ou `RouteView` (ex.: `HomePage.path`) |
| `visit(path)` | Abre uma página por route path ou `RouteView`, com `data:` opcional |
| `back()` | Volta uma página |
| `pause(seconds: 1)` | Fica parado, por exemplo para deixar uma demo respirar |
| `step(title, action)` | Executa qualquer código com os mesmos verbos, por meio de um `PlaybookActor` |
| `scene(title, steps)` | Agrupa passos sob um título; as legendas deles começam com ele e os resultados ficam aninhados no relatório |
| `screenshot(name)` | Captura a tela -- veja [Screenshots](#screenshots) |
| `play(playbook)` | Executa outro playbook -- veja [Executando Outros Playbooks](#playing-other-playbooks) |

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

Os passos são executados em ordem e param na primeira falha. Os passos restantes são reportados como ignorados.

<div id="targets"></div>

## Alvos

A maioria dos passos recebe um alvo: o que tocar, onde digitar ou o que procurar.

<div id="plain-strings"></div>

### Strings Simples

Uma string simples é associada da forma como uma pessoa lê a tela. Ela tenta, nesta ordem:

1. Texto visível
2. O hint ou o rótulo de um campo
3. Um tooltip
4. Um rótulo de semantics
5. Uma `ValueKey`
6. Texto que contém a string

``` dart
tap('Create account'),
type('Email', 'demo@nylo.dev'),
```

Uma `Key`, um `Type` ou um `IconData` também podem ser passados diretamente a um passo.

<div id="find"></div>

### Find

Use `Find` quando uma string simples não for precisa o bastante:

``` dart
tap(Find.icon(Icons.add)),
tap(Find.type<FloatingActionButton>()),
tap(Find.key(const ValueKey('checkout'))),
type(Find.field('email'), 'demo@nylo.dev'),
tap(Find.text('Delete').inside(Find.type<AlertDialog>())),
tap(Find.text('Add').at(1)),
```

| Finder | Corresponde a |
|--------|---------|
| `Find.text(text)` | Texto visível |
| `Find.textContaining(text)` | Texto que contém a string |
| `Find.icon(icon, label:)` | Um ícone. `label:` dá nome a ele nas legendas dos passos |
| `Find.type<T>()` | Um widget do tipo `T` |
| `Find.key(key)` | Um widget com a key |
| `Find.field(name)` | Um campo de `NyForm` pelo nome |
| `Find.input(text)` | Um campo de texto por hint, rótulo ou placeholder |
| `Find.tooltip(message)` | Um tooltip |
| `Find.semantics(label)` | Um rótulo de semantics |
| `Find.where(test)` | Widgets que passam no seu teste |

Dê um `label` a um ícone e as legendas dos seus passos dizem `Tap the bag icon` em vez de `Tap icon f37d`:

``` dart
tap(Find.icon(Icons.shopping_bag_outlined, label: 'bag')),
```

Restrinja qualquer finder com `.inside(ancestor)`, `.at(index)` (contando a partir de 0), `.first` ou `.last`.

Um toque é recusado quando algo não relacionado, como a barreira de um dialog, cobre o alvo.

<div id="data-and-setup"></div>

## Dados e Configuração

`defaults` guarda os dados com que os passos começam. Leia-os por meio de `data` dentro de `steps`. `setUp` é executado antes dos passos e `tearDown` depois deles, mesmo quando um passo falhou:

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

`seed([...])` executa [Seeders](/docs/{{ $version }}/metro-live#seeders) (instâncias ou nomes registrados) e os registra como `seed` no `metro live`.

<div id="playing-other-playbooks"></div>

## Executando Outros Playbooks

`play()` executa outro playbook por completo, com seus próprios `setUp` e `tearDown`. Passe uma instância ou o nome com que ele está registrado:

``` dart
@override
List<PlaybookStep> get steps => [
  play(SignUpPlaybook()),
  play('add_to_bag', data: {'item': 'Running shoes'}),
  tap('Checkout'),
];
```

- **Dados** são mesclados nesta ordem: os `defaults` do filho, depois os `data` do pai, depois os `data:` passados para `play`.
- **Velocidade** é herdada do pai, a menos que você passe `speed:`.
- **Resultados** ficam aninhados sob o passo no relatório.
- **Um filho que falha** faz o passo falhar.
- **Ciclos** são recusados, e playbooks se aninham em no máximo 8 níveis.

<div id="speed"></div>

## Velocidade

`PlaybookSpeed` define o ritmo de cada espera:

| Velocidade | Descrição |
|-------|-------------|
| `PlaybookSpeed.instant` | Nenhuma espera além do que a rolagem precisa. Feito para testes |
| `PlaybookSpeed.fast` | Rápido, mas ainda fácil de acompanhar |
| `PlaybookSpeed.natural` | O ritmo de uma pessoa usando o app. O padrão |
| `PlaybookSpeed.slow` | Lento o bastante para narrar por cima |

Escale qualquer velocidade com `.times(n)`:

``` dart
@override
PlaybookSpeed get speed => PlaybookSpeed.natural.times(2); // duas vezes mais lento
```

Uma velocidade passada para `run()`, `--speed` pelo Metro, ou a velocidade de um playbook que executa este prevalece sobre a do próprio playbook.

<div id="finger-and-caption"></div>

## Dedo e Legenda

O dedo e as legendas dos passos vêm desativados por padrão. Sobrescreva `showFinger` e `caption` para ativá-los:

``` dart
@override
bool get showFinger => true;

@override
PlaybookCaption get caption => PlaybookCaption.bottom; // ou .top
```

`showFinger` tem como padrão `false` e `caption` tem como padrão `PlaybookCaption.hidden`; `PlaybookCaption` é `top`, `bottom` ou `hidden`. Toques e swipes acontecem de qualquer forma. Um playbook executado por este com `play()` usa sua própria configuração, e a deste volta quando ele termina. Para ocultar ambos em uma execução, use `--no-overlay` pelo Metro ou `run(overlay: false)` pelo Dart.

Antes do nylo_support 7.33.0 ({{ config('app.name') }} 7.4.0), o dedo e a legenda inferior vinham ativados por padrão. Adicione as sobrescritas acima para manter essa aparência.

<div id="screenshots"></div>

## Screenshots

<x-doc-strip label="Requer" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

Adicione um passo `screenshot` onde quer que a tela valha a pena ser guardada:

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

`metro live:run playbook <name>` salva cada uma como `screenshots/<playbook>/<name>.png`, sem precisar de nenhuma flag. Uma execução pelo código as coleta com [`onScreenshot`](#collecting-screenshots), e qualquer outra execução, como `PLAYBOOK_ON_BOOT`, apenas espera a tela se estabilizar. Um nome começa com uma letra ou dígito e usa letras, dígitos, `-`, `_` e `.`.

| Parâmetro | Descrição |
|-----------|-------------|
| `name` | O nome com o qual a captura é salva |
| `locales` | Captura uma vez em cada um destes códigos de idioma. `Screenshot.allLocales` é todo idioma que o app inclui |
| `themes` | Captura uma vez em cada um destes ids de tema. `Screenshot.allThemes` é todo tema registrado |
| `caption` | O texto acima da tela quando a execução enquadra suas capturas, ou uma chave de tradução |
| `native` | Captura com o Metro, no iOS Simulator e no Android (padrão `true`) |

No iOS Simulator e no Android, o Metro captura a tela inteira do dispositivo, então a barra de status e o teclado aparecem nela, e o dedo e a legenda ficam ocultos enquanto isso. Adicione `--clean-status-bar` à execução para obter 9:41 com sinal e bateria completos. Em outros casos, e em execuções com `--store` e `--frame`, o app captura a própria tela. Passe `native: false` para sempre usar a captura do app:

``` dart
screenshot('checkout', native: false),
```

Para salvar a tela de cada passo, execute `metro live:run playbook <name> --screenshots`. Veja [Metro Live](/docs/{{ $version }}/metro-live#playbook-screenshots) para as opções que dimensionam as capturas para a App Store e o Google Play, e [Screenshots](/docs/{{ $version }}/screenshots) para a classe `Screenshot` por trás delas.

<div id="screenshots-locales-and-themes"></div>

### Idiomas e Temas

Dê a um passo `screenshot` `locales`, `themes` ou ambos para capturar a mesma tela uma vez em cada um:

``` dart
screenshot('cart', locales: ['en', 'es'], themes: ['light', 'dark']),
screenshot('home', locales: Screenshot.allLocales),
```

O app troca no lugar, mantendo a página e seu estado, e volta antes do próximo passo. Cada captura é salva em uma pasta com o nome do seu idioma e tema:

``` plaintext
screenshots/sign_up/en/cart.png
screenshots/sign_up/en-dark/cart.png
screenshots/sign_up/es/cart.png
screenshots/sign_up/es-dark/cart.png
```

Para executar um playbook inteiro em todos os idiomas e temas, passe `--locales` e `--themes` para `metro live:run playbook`.

<div id="collecting-screenshots"></div>

### Coletando pelo Código

`run()` aceita `onScreenshot` para receber as capturas de uma execução como `PlaybookShot`s, o que é útil para um teste ou para suas próprias ferramentas:

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

`screenshots` escolhe quais telas são capturadas:

| Modo | Captura |
|------|----------|
| `PlaybookScreenshots.off` | Nada |
| `PlaybookScreenshots.failures` | Apenas a tela de um passo que falha |
| `PlaybookScreenshots.marked` | Os passos `screenshot()` e os passos que falham |
| `PlaybookScreenshots.auto` | Os passos `screenshot()` de um playbook que tem algum, e todo passo de um que não tem. Os passos que falham também. O padrão |
| `PlaybookScreenshots.all` | Todo passo, os passos `screenshot()` e os passos que falham |

`pixelRatio` dimensiona as capturas em relação às do dispositivo (padrão `1.0`). Cada `PlaybookShot` tem um `screenshot` para [salvar](/docs/{{ $version }}/screenshots#saving-a-screenshot) ou do qual ler `bytes`, um `kind`, e o `name`, `locale` e `theme` com que foi tirada.

<div id="registering-playbooks"></div>

## Registrando Playbooks

Registre seus playbooks para que o Metro e `play('name')` possam encontrá-los:

``` dart
nylo.addPlaybooks({
  'sign_up': SignUpPlaybook.new,
});
```

`nylo.getPlaybooks()` retorna o que está registrado. Você também pode passá-los ao configurar o Nylo no seu `AppProvider`:

``` dart
await nylo.configure(
  // ...
  playbooks: playbooks,
);
```

`metro make:playbook` faz isso por você. Ele mantém o registro em `lib/bootstrap/playbooks.dart`, adiciona cada novo playbook a ele e passa `playbooks` para `nylo.configure(...)`. Em builds de release, o registro é um mapa vazio:

``` dart
final Map<String, Playbook Function()> playbooks = kReleaseMode ? const {} : {
  'sign_up': SignUpPlaybook.new,
};
```

Um playbook registrado é executado sob a sua chave. Um não registrado é executado sob o seu `name`, ou o nome da classe em snake case (`SignUpPlaybook` vira `sign_up`).

<div id="running-a-playbook"></div>

## Executando um Playbook

<div id="running-from-metro"></div>

### Pelo Metro

Com seu app rodando em modo debug:

``` bash
metro live:run playbook sign_up --speed fast
```

Dentro do `metro live`, digite `playbook sign_up`. Veja [Metro Live](/docs/{{ $version }}/metro-live#playbooks) para todas as opções. Adicione `--record` para salvar um vídeo da execução; veja [Gravação](/docs/{{ $version }}/metro-live#recording-playbooks). Se a execução parar de esperar após 20 segundos, o app provavelmente foi iniciado com `flutter run --no-dds`; veja [Metro Live](/docs/{{ $version }}/metro-live#playbooks).

<div id="running-on-boot"></div>

### Na Inicialização

Adicione estas chaves ao seu arquivo `.env` para executar playbooks assim que o app estiver na tela:

``` bash
PLAYBOOK_ON_BOOT="sign_up,add_to_bag"
PLAYBOOK_SPEED="fast"
```

`PLAYBOOK_ON_BOOT` é uma lista de nomes de playbooks separados por vírgula e `PLAYBOOK_SPEED` é um entre `instant`, `fast`, `natural` ou `slow`. Ambos valem apenas em builds de debug e profile.

<div id="running-from-dart"></div>

### Pelo Dart

``` dart
final PlaybookReport report = await SignUpPlaybook().run(
  speed: PlaybookSpeed.fast,
  data: {'email': 'jane@example.com'},
);

if (!report.passed) {
  print(report);
}
```

`run()` aceita `speed`, `data`, `clock`, `overlay` (padrão `true`), `onEvent`, e `onScreenshot`, `screenshots` e `pixelRatio` para [coletar screenshots](#collecting-screenshots). Um passo que falha não lança exceção -- verifique `report.passed`. Apenas um playbook pode rodar por vez; use `play()` para executar um dentro de outro.

Pare um playbook em execução com `Playbook.stop()`. Os passos restantes são ignorados, mas `tearDown` ainda é executado. `Playbook.isRunning` informa se há um em execução.

<div id="reports"></div>

## Relatórios

`run()` retorna um `PlaybookReport` com o `name` do playbook, a `speed`, o tempo `elapsed`, se ele foi interrompido (`stopped`) e uma lista `steps` de `PlaybookStepResult` (cada um com `title`, `status`, `elapsed`, `error` e `steps` aninhados). `report.passed` é true quando todos os passos passaram, e imprimir um relatório se parece com a saída do Metro. `report.toJson()` fornece os mesmos dados em JSON.

<div id="release-builds"></div>

## Builds de Release

Playbooks nunca são executados em um build de release, então uma jornada não pode controlar o app de um usuário:

- `Playbook.run()` lança um `UnsupportedError`.
- `addPlaybooks` é ignorado.
- O registro `lib/bootstrap/playbooks.dart` gerado pelo Metro fica vazio.

`Playbook.isAvailable` informa se o build atual pode executar playbooks.

<div id="testing"></div>

## Testes

Execute um playbook em um widget test com `NyTesterClock` e depois faça asserções sobre o relatório com `expectPlaybookPassed`:

``` dart
nyWidgetTest('signs up', (tester) async {
  await tester.visit(SignUpPage.path);
  final report = await SignUpPlaybook().run(clock: NyTesterClock(tester));
  expectPlaybookPassed(report);
});
```

Veja [Testando Playbooks](/docs/{{ $version }}/testing#testing-playbooks) para testes de integração.
