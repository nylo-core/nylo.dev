# Playbooks
<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [Introdução](#introduction "Introdução aos Playbooks")
- [Criando um Playbook](#creating-a-playbook "Criando um playbook")
- [Passos](#steps "Os passos que um playbook pode executar")
- [Alvos](#targets "Encontrando elementos na tela")
  - [Strings Simples](#plain-strings "Como uma string simples é associada")
  - [Find](#find "Alvos precisos com Find")
- [Dados e Configuração](#data-and-setup "Dados, setUp e tearDown")
- [Executando Outros Playbooks](#playing-other-playbooks "Executando um playbook a partir de outro playbook")
- [Velocidade](#speed "PlaybookSpeed")
- [Dedo e Legenda](#finger-and-caption "O dedo e a legenda do passo")
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
| `Find.icon(icon)` | Um ícone |
| `Find.type<T>()` | Um widget do tipo `T` |
| `Find.key(key)` | Um widget com a key |
| `Find.field(name)` | Um campo de `NyForm` pelo nome |
| `Find.input(text)` | Um campo de texto por hint, rótulo ou placeholder |
| `Find.tooltip(message)` | Um tooltip |
| `Find.semantics(label)` | Um rótulo de semantics |
| `Find.where(test)` | Widgets que passam no seu teste |

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

Dentro do `metro live`, digite `playbook sign_up`. Veja [Metro Live](/docs/{{ $version }}/metro-live#playbooks) para todas as opções. Adicione `--record` para salvar um vídeo da execução; veja [Gravação](/docs/{{ $version }}/metro-live#recording-playbooks).

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

`run()` aceita `speed`, `data`, `clock`, `overlay` (padrão `true`) e `onEvent`. Um passo que falha não lança exceção -- verifique `report.passed`. Apenas um playbook pode rodar por vez; use `play()` para executar um dentro de outro.

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
testWidgets('signs up', (tester) async {
  await tester.pumpWidget(const MyApp());
  final report = await SignUpPlaybook().run(clock: NyTesterClock(tester));
  expectPlaybookPassed(report);
});
```

Veja [Testando Playbooks](/docs/{{ $version }}/testing#testing-playbooks) para testes de integração.
