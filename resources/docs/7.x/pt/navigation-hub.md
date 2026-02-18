# Navigation Hub

---

<a name="section-1"></a>
- [Introdução](#introduction "Introdução")
- [Uso Básico](#basic-usage "Uso básico")
  - [Criando um Navigation Hub](#creating-a-navigation-hub "Criando um Navigation Hub")
  - [Criando Abas de Navegação](#creating-navigation-tabs "Criando abas de navegação")
  - [Navegação Inferior](#bottom-navigation "Navegação inferior")
    - [Nav Bar Builder Personalizado](#custom-nav-bar-builder "Nav Bar Builder personalizado")
  - [Navegação Superior](#top-navigation "Navegação superior")
  - [Navegação Journey](#journey-navigation "Navegação Journey")
    - [Estilos de Progresso](#journey-progress-styles "Estilos de progresso do Journey")
    - [JourneyState](#journey-state "JourneyState")
    - [Métodos Auxiliares do JourneyState](#journey-state-helper-methods "Métodos auxiliares do JourneyState")
    - [onJourneyComplete](#on-journey-complete "onJourneyComplete")
    - [buildJourneyPage](#build-journey-page "buildJourneyPage")
- [Navegando dentro de uma aba](#navigating-within-a-tab "Navegando dentro de uma aba")
- [Abas](#tabs "Abas")
  - [Adicionando Badges às Abas](#adding-badges-to-tabs "Adicionando badges às abas")
  - [Adicionando Alertas às Abas](#adding-alerts-to-tabs "Adicionando alertas às abas")
- [Índice Inicial](#initial-index "Índice inicial")
- [Mantendo o estado](#maintaining-state "Mantendo o estado")
- [onTap](#on-tap "onTap")
- [State Actions](#state-actions "State Actions")
- [Estilo de Carregamento](#loading-style "Estilo de carregamento")

<div id="introduction"></div>

## Introdução

Navigation Hubs são um lugar central onde você pode **gerenciar** a navegação de todos os seus widgets.
Pronto para uso, você pode criar layouts de navegação inferior, superior e journey em segundos.

Vamos **imaginar** que você tem um app e quer adicionar uma barra de navegação inferior e permitir que os usuários naveguem entre diferentes abas no seu app.

Você pode usar um Navigation Hub para construir isso.

Vamos ver como você pode usar um Navigation Hub no seu app.

<div id="basic-usage"></div>

## Uso Básico

Você pode criar um Navigation Hub usando o comando abaixo.

``` bash
metro make:navigation_hub base
```

O comando irá guiá-lo por uma configuração interativa:

1. **Escolha um tipo de layout** - Selecione entre `navigation_tabs` (navegação inferior) ou `journey_states` (fluxo sequencial).
2. **Informe os nomes das abas/estados** - Forneça nomes separados por vírgula para suas abas ou estados de journey.

Isso criará arquivos no seu diretório `resources/pages/navigation_hubs/base/`:
- `base_navigation_hub.dart` - O widget principal do hub
- `tabs/` ou `states/` - Contém os widgets filhos para cada aba ou estado de journey

Veja como um Navigation Hub gerado se parece:

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/pages/navigation_hubs/base/tabs/home_tab_widget.dart';
import '/resources/pages/navigation_hubs/base/tabs/settings_tab_widget.dart';

class BaseNavigationHub extends NyStatefulWidget with BottomNavPageControls {
  static RouteView path = ("/base", (_) => BaseNavigationHub());

  BaseNavigationHub()
      : super(
            child: () => _BaseNavigationHubState(),
            stateName: path.stateName());

  /// State actions
  static NavigationHubStateActions stateActions = NavigationHubStateActions(path.stateName());
}

class _BaseNavigationHubState extends NavigationHub<BaseNavigationHub> {

  /// Layout builder
  @override
  NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();

  /// Should the state be maintained
  @override
  bool get maintainState => true;

  /// The initial index
  @override
  int get initialIndex => 0;

  /// Navigation pages
  _BaseNavigationHubState() : super(() => {
      0: NavigationTab.tab(title: "Home", page: HomeTab()),
      1: NavigationTab.tab(title: "Settings", page: SettingsTab()),
  });

  /// Handle the tap event
  @override
  onTap(int index) {
    super.onTap(index);
  }
}
```

Você pode ver que o Navigation Hub possui **duas** abas, Home e Settings.

O método `layout` retorna o tipo de layout para o hub. Ele recebe um `BuildContext` para que você possa acessar dados do tema e media queries ao configurar seu layout.

Você pode criar mais abas adicionando `NavigationTab`'s ao Navigation Hub.

Primeiro, você precisa criar um novo widget usando Metro.

``` bash
metro make:stateful_widget news_tab
```

Você também pode criar múltiplos widgets de uma vez.

``` bash
metro make:stateful_widget news_tab,notifications_tab
```

Em seguida, você pode adicionar o novo widget ao Navigation Hub.

``` dart
_BaseNavigationHubState() : super(() => {
    0: NavigationTab.tab(title: "Home", page: HomeTab()),
    1: NavigationTab.tab(title: "Settings", page: SettingsTab()),
    2: NavigationTab.tab(title: "News", page: NewsTab()),
});
```

Para usar o Navigation Hub, adicione-o ao seu router como a rota inicial:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

appRouter() => nyRoutes((router) {
    ...
    router.add(BaseNavigationHub.path).initialRoute();
});

// ou navegue para o Navigation Hub de qualquer lugar do seu app

routeTo(BaseNavigationHub.path);
```

Há **muito mais** que você pode fazer com um Navigation Hub, vamos explorar algumas das funcionalidades.

<div id="bottom-navigation"></div>

### Navegação Inferior

Você pode definir o layout como uma barra de navegação inferior retornando `NavigationHubLayout.bottomNav` do método `layout`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
```

Você pode personalizar a barra de navegação inferior definindo propriedades como as seguintes:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav(
        backgroundColor: Colors.white,
        selectedItemColor: Colors.blue,
        unselectedItemColor: Colors.grey,
        elevation: 8.0,
        iconSize: 24.0,
        selectedFontSize: 14.0,
        unselectedFontSize: 12.0,
        showSelectedLabels: true,
        showUnselectedLabels: true,
        type: BottomNavigationBarType.fixed,
    );
```

Você pode aplicar um estilo predefinido à sua barra de navegação inferior usando o parâmetro `style`.

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav(
    style: BottomNavStyle.material(), // Estilo material padrão do Flutter
);
```

<div id="custom-nav-bar-builder"></div>

### Nav Bar Builder Personalizado

Para controle completo sobre sua barra de navegação, você pode usar o parâmetro `navBarBuilder`.

Isso permite construir qualquer widget personalizado enquanto ainda recebe os dados de navegação.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav(
        navBarBuilder: (context, data) {
            return MyCustomNavBar(
                items: data.items,
                currentIndex: data.currentIndex,
                onTap: data.onTap,
            );
        },
    );
```

O objeto `NavBarData` contém:

| Propriedade | Tipo | Descrição |
| --- | --- | --- |
| `items` | `List<BottomNavigationBarItem>` | Os itens da barra de navegação |
| `currentIndex` | `int` | O índice atualmente selecionado |
| `onTap` | `ValueChanged<int>` | Callback quando uma aba é tocada |

Aqui está um exemplo de uma nav bar de vidro totalmente personalizada:

``` dart
NavigationHubLayout.bottomNav(
    navBarBuilder: (context, data) {
        return Padding(
            padding: EdgeInsets.all(16),
            child: ClipRRect(
                borderRadius: BorderRadius.circular(25),
                child: BackdropFilter(
                    filter: ImageFilter.blur(sigmaX: 10, sigmaY: 10),
                    child: Container(
                        decoration: BoxDecoration(
                            color: Colors.white.withValues(alpha: 0.7),
                            borderRadius: BorderRadius.circular(25),
                        ),
                        child: BottomNavigationBar(
                            items: data.items,
                            currentIndex: data.currentIndex,
                            onTap: data.onTap,
                            backgroundColor: Colors.transparent,
                            elevation: 0,
                        ),
                    ),
                ),
            ),
        );
    },
)
```

> **Nota:** Quando usar `navBarBuilder`, o parâmetro `style` é ignorado.

<div id="top-navigation"></div>

### Navegação Superior

Você pode alterar o layout para uma barra de navegação superior retornando `NavigationHubLayout.topNav` do método `layout`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.topNav();
```

Você pode personalizar a barra de navegação superior definindo propriedades como as seguintes:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.topNav(
        backgroundColor: Colors.white,
        labelColor: Colors.blue,
        unselectedLabelColor: Colors.grey,
        indicatorColor: Colors.blue,
        indicatorWeight: 3.0,
        isScrollable: false,
        hideAppBarTitle: true,
    );
```

<div id="journey-navigation"></div>

### Navegação Journey

Você pode alterar o layout para uma navegação journey retornando `NavigationHubLayout.journey` do método `layout`.

Isso é ótimo para fluxos de onboarding ou formulários de múltiplos passos.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle(
          indicator: JourneyProgressIndicator.segments(),
        ),
    );
```

Você também pode definir um `backgroundGradient` para o layout journey:

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
    backgroundGradient: LinearGradient(
        colors: [Colors.blue, Colors.purple],
        begin: Alignment.topLeft,
        end: Alignment.bottomRight,
    ),
    progressStyle: JourneyProgressStyle(
      indicator: JourneyProgressIndicator.linear(),
    ),
);
```

> **Nota:** Quando `backgroundGradient` é definido, ele tem prioridade sobre `backgroundColor`.

Se você quiser usar o layout de navegação journey, seus **widgets** devem usar `JourneyState` pois ele contém muitos métodos auxiliares para ajudar a gerenciar o journey.

Você pode criar todo o journey usando o comando `make:navigation_hub` com o layout `journey_states`:

``` bash
metro make:navigation_hub onboarding
# Selecione: journey_states
# Informe: welcome, personal_info, add_photos
```

Isso criará o hub e todos os widgets de estado de journey em `resources/pages/navigation_hubs/onboarding/states/`.

Ou você pode criar widgets de journey individuais usando:

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

Você pode então adicionar os novos widgets ao Navigation Hub.

``` dart
_MyNavigationHubState() : super(() => {
    0: NavigationTab.journey(
        page: Welcome(),
    ),
    1: NavigationTab.journey(
        page: PhoneNumberStep(),
    ),
    2: NavigationTab.journey(
        page: AddPhotosStep(),
    ),
});
```

<div id="journey-progress-styles"></div>

### Estilos de Progresso do Journey

Você pode personalizar o estilo do indicador de progresso usando a classe `JourneyProgressStyle`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle(
            indicator: JourneyProgressIndicator.linear(
                activeColor: Colors.blue,
                inactiveColor: Colors.grey,
                thickness: 4.0,
            ),
        ),
    );
```

Você pode usar os seguintes indicadores de progresso:

- `JourneyProgressIndicator.none()`: Não renderiza nada - útil para esconder o indicador em uma aba específica.
- `JourneyProgressIndicator.linear()`: Barra de progresso linear.
- `JourneyProgressIndicator.dots()`: Indicador de progresso baseado em pontos.
- `JourneyProgressIndicator.numbered()`: Indicador de progresso com passos numerados.
- `JourneyProgressIndicator.segments()`: Estilo de barra de progresso segmentada.
- `JourneyProgressIndicator.circular()`: Indicador de progresso circular.
- `JourneyProgressIndicator.timeline()`: Indicador de progresso estilo timeline.
- `JourneyProgressIndicator.custom()`: Indicador de progresso personalizado usando uma função builder.

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
    progressStyle: JourneyProgressStyle(
        indicator: JourneyProgressIndicator.custom(
            builder: (context, currentStep, totalSteps, percentage) {
                return LinearProgressIndicator(
                    value: percentage,
                    backgroundColor: Colors.grey[200],
                    color: Colors.blue,
                    minHeight: 4.0,
                );
            },
        ),
    ),
);
```

Você pode personalizar a posição e o padding do indicador de progresso dentro do `JourneyProgressStyle`:

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
    progressStyle: JourneyProgressStyle(
        indicator: JourneyProgressIndicator.dots(),
        position: ProgressIndicatorPosition.bottom,
        padding: EdgeInsets.symmetric(horizontal: 16.0, vertical: 8.0),
    ),
);
```

Você pode usar as seguintes posições do indicador de progresso:

- `ProgressIndicatorPosition.top`: Indicador de progresso no topo da tela.
- `ProgressIndicatorPosition.bottom`: Indicador de progresso na parte inferior da tela.

#### Sobrescrita de Estilo de Progresso por Aba

Você pode sobrescrever o `progressStyle` do nível do layout em abas individuais usando `NavigationTab.journey(progressStyle: ...)`. Abas sem seu próprio `progressStyle` herdam o padrão do layout. Abas sem padrão de layout e sem estilo por aba não mostrarão um indicador de progresso.

``` dart
_MyNavigationHubState() : super(() => {
    0: NavigationTab.journey(
        page: Welcome(),
    ),
    1: NavigationTab.journey(
        page: PhoneNumberStep(),
        progressStyle: JourneyProgressStyle(
            indicator: JourneyProgressIndicator.numbered(),
        ), // sobrescreve o padrão do layout apenas para esta aba
    ),
    2: NavigationTab.journey(
        page: AddPhotosStep(),
    ),
});
```

<div id="journey-state"></div>

### JourneyState

A classe `JourneyState` estende `NyState` com funcionalidade específica de journey para facilitar a criação de fluxos de onboarding e jornadas de múltiplos passos.

Para criar um novo `JourneyState`, você pode usar o comando abaixo.

``` bash
metro make:journey_widget onboard_user_dob
```

Ou se quiser criar múltiplos widgets de uma vez, você pode usar o seguinte comando.

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

Veja como um widget JourneyState gerado se parece:

``` dart
import 'package:flutter/material.dart';
import '/resources/pages/navigation_hubs/onboarding/onboarding_navigation_hub.dart';
import '/resources/widgets/buttons/buttons.dart';
import 'package:nylo_framework/nylo_framework.dart';

class Welcome extends StatefulWidget {
  const Welcome({super.key});

  @override
  createState() => _WelcomeState();
}

class _WelcomeState extends JourneyState<Welcome> {
  _WelcomeState() : super(
      navigationHubState: OnboardingNavigationHub.path.stateName());

  @override
  get init => () {
    // Sua lógica de inicialização aqui
  };

  @override
  Widget view(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(16),
      child: Column(
        children: [
          Expanded(
            child: Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Text('Welcome', style: Theme.of(context).textTheme.headlineMedium),
                  const SizedBox(height: 20),
                  Text('This onboarding journey will help you get started.'),
                ],
              ),
            ),
          ),

          // Navigation buttons
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              if (!isFirstStep)
                Flexible(
                  child: Button.textOnly(
                    text: "Back",
                    textColor: Colors.black87,
                    onPressed: onBackPressed,
                  ),
                )
              else
                const SizedBox.shrink(),
              Flexible(
                child: Button.primary(
                  text: "Continue",
                  onPressed: nextStep,
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  /// Check if the journey can continue to the next step
  @override
  Future<bool> canContinue() async {
    return true;
  }

  /// Called before navigating to the next step
  @override
  Future<void> onBeforeNext() async {
    // E.g. save data to session
  }

  /// Called when the journey is complete (at the last step)
  @override
  Future<void> onComplete() async {}
}
```

Você notará que a classe **JourneyState** usa `nextStep` para navegar para frente e `onBackPressed` para voltar.

O método `nextStep` executa todo o ciclo de vida de validação: `canContinue()` -> `onBeforeNext()` -> navegar (ou `onComplete()` se estiver no último passo) -> `onAfterNext()`.

Você também pode usar `buildJourneyContent` para construir um layout estruturado com botões de navegação opcionais:

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
      content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text('Welcome', style: Theme.of(context).textTheme.headlineMedium),
          const SizedBox(height: 20),
          Text('This onboarding journey will help you get started.'),
        ],
      ),
      nextButton: Button.primary(
        text: isLastStep ? "Get Started" : "Continue",
        onPressed: nextStep,
      ),
      backButton: isFirstStep ? null : Button.textOnly(
        text: "Back",
        textColor: Colors.black87,
        onPressed: onBackPressed,
      ),
    );
}
```

Aqui estão as propriedades que você pode usar no método `buildJourneyContent`.

| Propriedade | Tipo | Descrição |
| --- | --- | --- |
| `content` | `Widget` | O conteúdo principal da página. |
| `nextButton` | `Widget?` | O widget do botão próximo. |
| `backButton` | `Widget?` | O widget do botão voltar. |
| `contentPadding` | `EdgeInsetsGeometry` | O padding do conteúdo. |
| `header` | `Widget?` | O widget de cabeçalho. |
| `footer` | `Widget?` | O widget de rodapé. |
| `crossAxisAlignment` | `CrossAxisAlignment` | O alinhamento do eixo cruzado do conteúdo. |

<div id="journey-state-helper-methods"></div>

### Métodos Auxiliares do JourneyState

A classe `JourneyState` possui métodos auxiliares e propriedades que você pode usar para personalizar o comportamento do seu journey.

| Método / Propriedade | Descrição |
| --- | --- |
| [`nextStep()`](#next-step) | Navegar para o próximo passo com validação. Retorna `Future<bool>`. |
| [`previousStep()`](#previous-step) | Navegar para o passo anterior. Retorna `Future<bool>`. |
| [`onBackPressed()`](#on-back-pressed) | Helper simples para navegar para o passo anterior. |
| [`onComplete()`](#on-complete) | Chamado quando o journey é concluído (no último passo). |
| [`onBeforeNext()`](#on-before-next) | Chamado antes de navegar para o próximo passo. |
| [`onAfterNext()`](#on-after-next) | Chamado após navegar para o próximo passo. |
| [`canContinue()`](#can-continue) | Verificação de validação antes de navegar para o próximo passo. |
| [`isFirstStep`](#is-first-step) | Retorna true se este é o primeiro passo do journey. |
| [`isLastStep`](#is-last-step) | Retorna true se este é o último passo do journey. |
| [`currentStep`](#current-step) | Retorna o índice do passo atual (baseado em 0). |
| [`totalSteps`](#total-steps) | Retorna o número total de passos. |
| [`completionPercentage`](#completion-percentage) | Retorna a porcentagem de conclusão (0.0 a 1.0). |
| [`goToStep(int index)`](#go-to-step) | Pular para um passo específico por índice. |
| [`goToNextStep()`](#go-to-next-step) | Pular para o próximo passo (sem validação). |
| [`goToPreviousStep()`](#go-to-previous-step) | Pular para o passo anterior (sem validação). |
| [`goToFirstStep()`](#go-to-first-step) | Pular para o primeiro passo. |
| [`goToLastStep()`](#go-to-last-step) | Pular para o último passo. |
| [`exitJourney()`](#exit-journey) | Sair do journey fazendo pop do navigator raiz. |
| [`resetCurrentStep()`](#reset-current-step) | Resetar o estado do passo atual. |
| [`onJourneyComplete`](#on-journey-complete) | Callback quando o journey é concluído (sobrescrever no último passo). |
| [`buildJourneyPage()`](#build-journey-page) | Construir uma página journey em tela cheia com Scaffold. |


<div id="next-step"></div>

#### nextStep

O método `nextStep` navega para o próximo passo com validação completa. Ele executa o ciclo de vida: `canContinue()` -> `onBeforeNext()` -> navegar ou `onComplete()` -> `onAfterNext()`.

Você pode passar `force: true` para ignorar a validação e navegar diretamente.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        nextButton: Button.primary(
            text: isLastStep ? "Get Started" : "Continue",
            onPressed: nextStep, // executa validação e depois navega
        ),
    );
}
```

Para pular a validação:

``` dart
onPressed: () => nextStep(force: true),
```

<div id="previous-step"></div>

#### previousStep

O método `previousStep` navega para o passo anterior. Retorna `true` se bem-sucedido, `false` se já estiver no primeiro passo.

``` dart
onPressed: () async {
    bool success = await previousStep();
    if (!success) {
      // Já está no primeiro passo
    }
},
```

<div id="on-back-pressed"></div>

#### onBackPressed

O método `onBackPressed` é um helper simples que chama `previousStep()` internamente.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        backButton: isFirstStep ? null : Button.textOnly(
            text: "Back",
            textColor: Colors.black87,
            onPressed: onBackPressed,
        ),
    );
}
```

<div id="on-complete"></div>

#### onComplete

O método `onComplete` é chamado quando `nextStep()` é acionado no último passo (após a validação passar).

``` dart
@override
Future<void> onComplete() async {
    print("Journey completed");
}
```

<div id="on-before-next"></div>

#### onBeforeNext

O método `onBeforeNext` é chamado antes de navegar para o próximo passo.

Ex: se você quiser salvar dados antes de navegar para o próximo passo, você pode fazer isso aqui.

``` dart
@override
Future<void> onBeforeNext() async {
    // Ex: salvar dados na sessão
    // session('onboarding', {
    //   'name': 'Anthony Gordon',
    //   'occupation': 'Software Engineer',
    // });
}
```

<div id="on-after-next"></div>

#### onAfterNext

O método `onAfterNext` é chamado após navegar para o próximo passo.

``` dart
@override
Future<void> onAfterNext() async {
    // print('Navigated to the next step');
}
```

<div id="can-continue"></div>

#### canContinue

O método `canContinue` é chamado quando `nextStep()` é acionado. Retorne `false` para impedir a navegação.

``` dart
@override
Future<bool> canContinue() async {
    // Execute sua lógica de validação aqui
    // Retorne true se o journey pode continuar, false caso contrário
    if (nameController.text.isEmpty) {
        showToastSorry(description: "Please enter your name");
        return false;
    }
    return true;
}
```

<div id="is-first-step"></div>

#### isFirstStep

A propriedade `isFirstStep` retorna true se este é o primeiro passo do journey.

``` dart
backButton: isFirstStep ? null : Button.textOnly(
    text: "Back",
    textColor: Colors.black87,
    onPressed: onBackPressed,
),
```

<div id="is-last-step"></div>

#### isLastStep

A propriedade `isLastStep` retorna true se este é o último passo do journey.

``` dart
nextButton: Button.primary(
    text: isLastStep ? "Get Started" : "Continue",
    onPressed: nextStep,
),
```

<div id="current-step"></div>

#### currentStep

A propriedade `currentStep` retorna o índice do passo atual (baseado em 0).

``` dart
Text("Step ${currentStep + 1} of $totalSteps"),
```

<div id="total-steps"></div>

#### totalSteps

A propriedade `totalSteps` retorna o número total de passos no journey.

<div id="completion-percentage"></div>

#### completionPercentage

A propriedade `completionPercentage` retorna a porcentagem de conclusão como um valor de 0.0 a 1.0.

``` dart
LinearProgressIndicator(value: completionPercentage),
```

<div id="go-to-step"></div>

#### goToStep

O método `goToStep` pula diretamente para um passo específico por índice. Isso **não** aciona validação.

``` dart
nextButton: Button.primary(
    text: "Skip to photos",
    onPressed: () {
        goToStep(2); // pular para o índice 2
    },
),
```

<div id="go-to-next-step"></div>

#### goToNextStep

O método `goToNextStep` pula para o próximo passo sem validação. Se já estiver no último passo, não faz nada.

``` dart
onPressed: () {
    goToNextStep(); // pular validação e ir para o próximo passo
},
```

<div id="go-to-previous-step"></div>

#### goToPreviousStep

O método `goToPreviousStep` pula para o passo anterior sem validação. Se já estiver no primeiro passo, não faz nada.

``` dart
onPressed: () {
    goToPreviousStep();
},
```

<div id="go-to-first-step"></div>

#### goToFirstStep

O método `goToFirstStep` pula para o primeiro passo.

``` dart
onPressed: () {
    goToFirstStep();
},
```

<div id="go-to-last-step"></div>

#### goToLastStep

O método `goToLastStep` pula para o último passo.

``` dart
onPressed: () {
    goToLastStep();
},
```

<div id="exit-journey"></div>

#### exitJourney

O método `exitJourney` sai do journey fazendo pop do navigator raiz.

``` dart
onPressed: () {
    exitJourney(); // pop do navigator raiz
},
```

<div id="reset-current-step"></div>

#### resetCurrentStep

O método `resetCurrentStep` reseta o estado do passo atual.

``` dart
onPressed: () {
    resetCurrentStep();
},
```

<div id="on-journey-complete"></div>

### onJourneyComplete

O getter `onJourneyComplete` pode ser sobrescrito no **último passo** do seu journey para definir o que acontece quando o usuário completa o fluxo.

``` dart
class _CompleteStepState extends JourneyState<CompleteStep> {
  _CompleteStepState() : super(
      navigationHubState: OnboardingNavigationHub.path.stateName());

  /// Callback quando o journey é concluído
  @override
  void Function()? get onJourneyComplete => () {
    // Navegar para sua página inicial ou próximo destino
    routeTo(HomePage.path);
  };

  @override
  Widget view(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(16),
      child: Column(
        children: [
          ...
          Button.primary(
            text: "Get Started",
            onPressed: onJourneyComplete, // aciona o callback de conclusão
          ),
        ],
      ),
    );
  }
}
```

<div id="build-journey-page"></div>

### buildJourneyPage

O método `buildJourneyPage` constrói uma página journey em tela cheia envolvida em um `Scaffold` com `SafeArea`.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyPage(
      content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text('Welcome', style: Theme.of(context).textTheme.headlineMedium),
        ],
      ),
      nextButton: Button.primary(
        text: "Continue",
        onPressed: nextStep,
      ),
      backgroundColor: Colors.white,
    );
}
```

| Propriedade | Tipo | Descrição |
| --- | --- | --- |
| `content` | `Widget` | O conteúdo principal da página. |
| `nextButton` | `Widget?` | O widget do botão próximo. |
| `backButton` | `Widget?` | O widget do botão voltar. |
| `contentPadding` | `EdgeInsetsGeometry` | O padding do conteúdo. |
| `header` | `Widget?` | O widget de cabeçalho. |
| `footer` | `Widget?` | O widget de rodapé. |
| `backgroundColor` | `Color?` | A cor de fundo do Scaffold. |
| `appBar` | `Widget?` | Um widget AppBar opcional. |
| `crossAxisAlignment` | `CrossAxisAlignment` | O alinhamento do eixo cruzado do conteúdo. |

<div id="navigating-within-a-tab"></div>

## Navegando para widgets dentro de uma aba

Você pode navegar para widgets dentro de uma aba usando o helper `pushTo`.

Dentro da sua aba, você pode usar o helper `pushTo` para navegar para outro widget.

``` dart
_HomeTabState extends State<HomeTab> {
    ...
    void _navigateToSettings() {
        pushTo(SettingsPage());
    }
    ...
}
```

Você também pode passar dados para o widget para o qual está navegando.

``` dart
_HomeTabState extends State<HomeTab> {
    ...
    void _navigateToSettings() {
        pushTo(SettingsPage(), data: {"name": "Anthony"});
    }
    ...
}
```

<div id="tabs"></div>

## Abas

Abas são os blocos de construção principais de um Navigation Hub.

Você pode adicionar abas a um Navigation Hub usando a classe `NavigationTab` e seus construtores nomeados.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() => {
        0: NavigationTab.tab(
            title: "Home",
            page: HomeTab(),
            icon: Icon(Icons.home),
            activeIcon: Icon(Icons.home),
        ),
        1: NavigationTab.tab(
            title: "Settings",
            page: SettingsTab(),
            icon: Icon(Icons.settings),
            activeIcon: Icon(Icons.settings),
        ),
    });
```

No exemplo acima, adicionamos duas abas ao Navigation Hub, Home e Settings.

Você pode usar diferentes tipos de abas:

- `NavigationTab.tab()` - Uma aba de navegação padrão.
- `NavigationTab.badge()` - Uma aba com contagem de badge.
- `NavigationTab.alert()` - Uma aba com indicador de alerta.
- `NavigationTab.journey()` - Uma aba para layouts de navegação journey.

<div id="adding-badges-to-tabs"></div>

## Adicionando Badges às Abas

Facilitamos a adição de badges às suas abas.

Badges são uma ótima forma de mostrar aos usuários que há algo novo em uma aba.

Por exemplo, se você tem um app de chat, pode mostrar o número de mensagens não lidas na aba de chat.

Para adicionar um badge a uma aba, você pode usar o construtor `NavigationTab.badge`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() => {
        0: NavigationTab.badge(
            title: "Chats",
            page: ChatTab(),
            icon: Icon(Icons.message),
            activeIcon: Icon(Icons.message),
            initialCount: 10,
        ),
        1: NavigationTab.tab(
            title: "Settings",
            page: SettingsTab(),
            icon: Icon(Icons.settings),
            activeIcon: Icon(Icons.settings),
        ),
    });
```

No exemplo acima, adicionamos um badge à aba Chat com uma contagem inicial de 10.

Você também pode atualizar a contagem do badge programaticamente.

``` dart
/// Incrementar a contagem do badge
BaseNavigationHub.stateActions.incrementBadgeCount(tab: 0);

/// Atualizar a contagem do badge
BaseNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 5);

/// Limpar a contagem do badge
BaseNavigationHub.stateActions.clearBadgeCount(tab: 0);
```

Por padrão, a contagem do badge será lembrada. Se você quiser **limpar** a contagem do badge a cada sessão, pode definir `rememberCount` como `false`.

``` dart
0: NavigationTab.badge(
    title: "Chats",
    page: ChatTab(),
    icon: Icon(Icons.message),
    activeIcon: Icon(Icons.message),
    initialCount: 10,
    rememberCount: false,
),
```

<div id="adding-alerts-to-tabs"></div>

## Adicionando Alertas às Abas

Você pode adicionar alertas às suas abas.

Às vezes você pode não querer mostrar uma contagem de badge, mas quer mostrar um indicador de alerta ao usuário.

Para adicionar um alerta a uma aba, você pode usar o construtor `NavigationTab.alert`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() => {
        0: NavigationTab.alert(
            title: "Chats",
            page: ChatTab(),
            icon: Icon(Icons.message),
            activeIcon: Icon(Icons.message),
            alertColor: Colors.red,
            alertEnabled: true,
            rememberAlert: false,
        ),
        1: NavigationTab.tab(
            title: "Settings",
            page: SettingsTab(),
            icon: Icon(Icons.settings),
            activeIcon: Icon(Icons.settings),
        ),
    });
```

Isso adicionará um alerta à aba Chat com uma cor vermelha.

Você também pode atualizar o alerta programaticamente.

``` dart
/// Ativar o alerta
BaseNavigationHub.stateActions.alertEnableTab(tab: 0);

/// Desativar o alerta
BaseNavigationHub.stateActions.alertDisableTab(tab: 0);
```

<div id="initial-index"></div>

## Índice Inicial

Por padrão, o Navigation Hub inicia na primeira aba (índice 0). Você pode alterar isso sobrescrevendo o getter `initialIndex`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    int get initialIndex => 1; // Iniciar na segunda aba
    ...
}
```

<div id="maintaining-state"></div>

## Mantendo o estado

Por padrão, o estado do Navigation Hub é mantido.

Isso significa que quando você navega para uma aba, o estado da aba é preservado.

Se você quiser limpar o estado da aba cada vez que navegar para ela, pode definir `maintainState` como `false`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    bool get maintainState => false;
    ...
}
```

<div id="on-tap"></div>

## onTap

Você pode sobrescrever o método `onTap` para adicionar lógica personalizada quando uma aba é tocada.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    onTap(int index) {
        // Adicionar lógica personalizada aqui
        // Ex: rastrear analytics, mostrar confirmação, etc.
        super.onTap(index); // Sempre chame super para tratar a troca de aba
    }
}
```

<div id="state-actions"></div>

## State Actions

State actions são uma forma de interagir com o Navigation Hub de qualquer lugar do seu app.

Aqui estão as state actions que você pode usar:

``` dart
/// Resetar a aba em um determinado índice
/// Ex: MyNavigationHub.stateActions.resetTabIndex(0);
resetTabIndex(int tabIndex);

/// Alterar a aba atual programaticamente
/// Ex: MyNavigationHub.stateActions.currentTabIndex(2);
currentTabIndex(int tabIndex);

/// Atualizar a contagem do badge
/// Ex: MyNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 2);
updateBadgeCount({required int tab, required int count});

/// Incrementar a contagem do badge
/// Ex: MyNavigationHub.stateActions.incrementBadgeCount(tab: 0);
incrementBadgeCount({required int tab});

/// Limpar a contagem do badge
/// Ex: MyNavigationHub.stateActions.clearBadgeCount(tab: 0);
clearBadgeCount({required int tab});

/// Ativar o alerta para uma aba
/// Ex: MyNavigationHub.stateActions.alertEnableTab(tab: 0);
alertEnableTab({required int tab});

/// Desativar o alerta para uma aba
/// Ex: MyNavigationHub.stateActions.alertDisableTab(tab: 0);
alertDisableTab({required int tab});

/// Navegar para a próxima página em um layout journey
/// Ex: await MyNavigationHub.stateActions.nextPage();
Future<bool> nextPage();

/// Navegar para a página anterior em um layout journey
/// Ex: await MyNavigationHub.stateActions.previousPage();
Future<bool> previousPage();
```

Para usar uma state action, você pode fazer o seguinte:

``` dart
MyNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 2);

MyNavigationHub.stateActions.resetTabIndex(0);

MyNavigationHub.stateActions.currentTabIndex(2); // Trocar para a aba 2

await MyNavigationHub.stateActions.nextPage(); // Journey: ir para a próxima página
```

<div id="loading-style"></div>

## Estilo de Carregamento

Pronto para uso, o Navigation Hub mostrará seu Widget de carregamento **padrão** (resources/widgets/loader_widget.dart) quando a aba estiver carregando.

Você pode personalizar o `loadingStyle` para atualizar o estilo de carregamento.

| Estilo | Descrição |
| --- | --- |
| normal | Estilo de carregamento padrão |
| skeletonizer | Estilo de carregamento skeleton |
| none | Sem estilo de carregamento |

Você pode alterar o estilo de carregamento assim:

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal();
// ou
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer();
```

Se você quiser atualizar o Widget de carregamento em um dos estilos, pode passar um `child` para o `LoadingStyle`.

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal(
    child: Center(
        child: Text("Loading..."),
    ),
);
```

Agora, quando a aba estiver carregando, o texto "Loading..." será exibido.

Exemplo abaixo:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    _MyNavigationHubState() : super(() async {

      await sleep(3); // simular carregamento por 3 segundos

      return {
        0: NavigationTab.tab(
          title: "Home",
          page: HomeTab(),
        ),
        1: NavigationTab.tab(
          title: "Settings",
          page: SettingsTab(),
        ),
      };
    });

    @override
    LoadingStyle get loadingStyle => LoadingStyle.normal(
        child: Center(
            child: Text("Loading..."),
        ),
    );
    ...
}
```

<div id="creating-a-navigation-hub"></div>

## Criando um Navigation Hub

Para criar um Navigation Hub, você pode usar o [Metro](/docs/{{$version}}/metro), use o comando abaixo.

``` bash
metro make:navigation_hub base
```

O comando irá guiá-lo por uma configuração interativa onde você pode escolher o tipo de layout e definir suas abas ou estados de journey.

Isso criará um arquivo `base_navigation_hub.dart` no seu diretório `resources/pages/navigation_hubs/base/` com widgets filhos organizados em subpastas `tabs/` ou `states/`.
