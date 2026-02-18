# Button

---

<a name="section-1"></a>
- [Introdução](#introduction "Introdução")
- [Uso Básico](#basic-usage "Uso Básico")
- [Tipos de Botão Disponíveis](#button-types "Tipos de Botão Disponíveis")
    - [Primary](#primary "Primary")
    - [Secondary](#secondary "Secondary")
    - [Outlined](#outlined "Outlined")
    - [Text Only](#text-only "Text Only")
    - [Icon](#icon "Icon")
    - [Gradient](#gradient "Gradient")
    - [Rounded](#rounded "Rounded")
    - [Transparency](#transparency "Transparency")
- [Estado de Carregamento Assíncrono](#async-loading "Estado de Carregamento Assíncrono")
- [Estilos de Animação](#animation-styles "Estilos de Animação")
    - [Clickable](#anim-clickable "Clickable")
    - [Bounce](#anim-bounce "Bounce")
    - [Pulse](#anim-pulse "Pulse")
    - [Squeeze](#anim-squeeze "Squeeze")
    - [Jelly](#anim-jelly "Jelly")
    - [Shine](#anim-shine "Shine")
    - [Ripple](#anim-ripple "Ripple")
    - [Morph](#anim-morph "Morph")
    - [Shake](#anim-shake "Shake")
- [Estilos de Splash](#splash-styles "Estilos de Splash")
- [Estilos de Carregamento](#loading-styles "Estilos de Carregamento")
- [Envio de Formulário](#form-submission "Envio de Formulário")
- [Personalizando Botões](#customizing-buttons "Personalizando Botões")
- [Referência de Parâmetros](#parameters "Referência de Parâmetros")


<div id="introduction"></div>

## Introdução

{{ config('app.name') }} fornece uma classe `Button` com oito estilos de botão pré-construídos prontos para uso. Cada botão vem com suporte integrado para:

- **Estados de carregamento assíncronos** — retorne um `Future` de `onPressed` e o botão exibe automaticamente um indicador de carregamento
- **Estilos de animação** — escolha entre efeitos clickable, bounce, pulse, squeeze, jelly, shine, ripple, morph e shake
- **Estilos de splash** — adicione feedback de toque ripple, highlight, glow ou ink
- **Envio de formulário** — conecte um botão diretamente a uma instância `NyFormData`

Você pode encontrar as definições de botão do seu app em `lib/resources/widgets/buttons/buttons.dart`. Este arquivo contém uma classe `Button` com métodos estáticos para cada tipo de botão, facilitando a personalização dos padrões do seu projeto.

<div id="basic-usage"></div>

## Uso Básico

Use a classe `Button` em qualquer lugar nos seus widgets. Aqui está um exemplo simples dentro de uma página:

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: SafeArea(
      child: Padding(
        padding: EdgeInsets.all(16),
        child: Column(
          children: [
            Button.primary(
              text: "Sign Up",
              onPressed: () {
                routeTo(SignUpPage.path);
              },
            ),

            SizedBox(height: 12),

            Button.secondary(
              text: "Learn More",
              onPressed: () {
                routeTo(AboutPage.path);
              },
            ),

            SizedBox(height: 12),

            Button.outlined(
              text: "Cancel",
              onPressed: () {
                Navigator.pop(context);
              },
            ),
          ],
        ),
      ),
    ),
  );
}
```

Todos os tipos de botão seguem o mesmo padrão — passe um rótulo `text` e um callback `onPressed`.

<div id="button-types"></div>

## Tipos de Botão Disponíveis

Todos os botões são acessados através da classe `Button` usando métodos estáticos.

<div id="primary"></div>

### Primary

Um botão preenchido com sombra, usando a cor primária do seu tema. Melhor para elementos de chamada para ação principais.

``` dart
Button.primary(
  text: "Sign Up",
  onPressed: () {
    // Handle press
  },
)
```

<div id="secondary"></div>

### Secondary

Um botão preenchido com uma cor de superfície mais suave e sombra sutil. Bom para ações secundárias ao lado de um botão primary.

``` dart
Button.secondary(
  text: "Learn More",
  onPressed: () {
    // Handle press
  },
)
```

<div id="outlined"></div>

### Outlined

Um botão transparente com uma borda. Útil para ações menos proeminentes ou botões de cancelar.

``` dart
Button.outlined(
  text: "Cancel",
  onPressed: () {
    // Handle press
  },
)
```

Você pode personalizar as cores da borda e do texto:

``` dart
Button.outlined(
  text: "Custom Outline",
  borderColor: Colors.red,
  textColor: Colors.red,
  onPressed: () {},
)
```

<div id="text-only"></div>

### Text Only

Um botão mínimo sem fundo ou borda. Ideal para ações inline ou links.

``` dart
Button.textOnly(
  text: "Skip",
  onPressed: () {
    // Handle press
  },
)
```

Você pode personalizar a cor do texto:

``` dart
Button.textOnly(
  text: "View Details",
  textColor: Colors.blue,
  onPressed: () {},
)
```

<div id="icon"></div>

### Icon

Um botão preenchido que exibe um ícone ao lado do texto. O ícone aparece antes do texto por padrão.

``` dart
Button.icon(
  text: "Add to Cart",
  icon: Icon(Icons.shopping_cart),
  onPressed: () {
    // Handle press
  },
)
```

Você pode personalizar a cor de fundo:

``` dart
Button.icon(
  text: "Download",
  icon: Icon(Icons.download),
  color: Colors.green,
  onPressed: () {},
)
```

<div id="gradient"></div>

### Gradient

Um botão com fundo de gradiente linear. Usa as cores primária e terciária do seu tema por padrão.

``` dart
Button.gradient(
  text: "Get Started",
  onPressed: () {
    // Handle press
  },
)
```

Você pode fornecer cores de gradiente personalizadas:

``` dart
Button.gradient(
  text: "Premium",
  gradientColors: [Colors.purple, Colors.pink],
  onPressed: () {},
)
```

<div id="rounded"></div>

### Rounded

Um botão em formato de pílula com cantos totalmente arredondados. O raio da borda padrão é metade da altura do botão.

``` dart
Button.rounded(
  text: "Continue",
  onPressed: () {
    // Handle press
  },
)
```

Você pode personalizar a cor de fundo e o raio da borda:

``` dart
Button.rounded(
  text: "Apply",
  backgroundColor: Colors.teal,
  borderRadius: BorderRadius.circular(20),
  onPressed: () {},
)
```

<div id="transparency"></div>

### Transparency

Um botão estilo vidro fosco com efeito de desfoque de fundo. Funciona bem quando colocado sobre imagens ou fundos coloridos.

``` dart
Button.transparency(
  text: "Explore",
  onPressed: () {
    // Handle press
  },
)
```

Você pode personalizar a cor do texto:

``` dart
Button.transparency(
  text: "View More",
  color: Colors.white,
  onPressed: () {},
)
```

<div id="async-loading"></div>

## Estado de Carregamento Assíncrono

Uma das funcionalidades mais poderosas dos botões do {{ config('app.name') }} é o **gerenciamento automático de estado de carregamento**. Quando seu callback `onPressed` retorna um `Future`, o botão exibirá automaticamente um indicador de carregamento e desabilitará a interação até que a operação seja concluída.

``` dart
Button.primary(
  text: "Submit",
  onPressed: () async {
    await sleep(3); // Simulates a 3 second async task
  },
)
```

Enquanto a operação assíncrona está em execução, o botão mostrará um efeito de carregamento skeleton (por padrão). Quando o `Future` é concluído, o botão retorna ao seu estado normal.

Isso funciona com qualquer operação assíncrona — chamadas de API, escritas em banco de dados, uploads de arquivos ou qualquer coisa que retorne um `Future`:

``` dart
Button.primary(
  text: "Save Profile",
  onPressed: () async {
    await api<ApiService>((request) =>
      request.updateProfile(name: "John", email: "john@example.com")
    );
    showToastSuccess(description: "Profile saved!");
  },
)
```

``` dart
Button.secondary(
  text: "Sync Data",
  onPressed: () async {
    await fetchAndStoreData();
    await clearOldCache();
  },
)
```

Não é necessário gerenciar variáveis de estado `isLoading`, chamar `setState` ou envolver nada em um `StatefulWidget` — {{ config('app.name') }} cuida de tudo para você.

### Como Funciona

Quando o botão detecta que `onPressed` retorna um `Future`, ele usa o mecanismo `lockRelease` para:

1. Exibir um indicador de carregamento (controlado por `LoadingStyle`)
2. Desabilitar o botão para evitar toques duplicados
3. Aguardar o `Future` ser concluído
4. Restaurar o botão ao seu estado normal

<div id="animation-styles"></div>

## Estilos de Animação

Os botões suportam animações de pressionar através do `ButtonAnimationStyle`. Essas animações fornecem feedback visual quando um usuário interage com um botão. Você pode definir o estilo de animação ao personalizar seus botões em `lib/resources/widgets/buttons/buttons.dart`.

<div id="anim-clickable"></div>

### Clickable

Um efeito de pressionar 3D no estilo Duolingo. O botão se move para baixo ao pressionar e volta ao soltar. Melhor para ações primárias e UX estilo jogo.

``` dart
animationStyle: ButtonAnimationStyle.clickable()
```

Ajuste fino do efeito:

``` dart
ButtonAnimationStyle.clickable(
  translateY: 6.0,        // How far the button moves down (default: 4.0)
  shadowOffset: 6.0,      // Shadow depth (default: 4.0)
  duration: Duration(milliseconds: 100),
  enableHapticFeedback: true,
)
```

<div id="anim-bounce"></div>

### Bounce

Reduz a escala do botão ao pressionar e volta ao soltar. Melhor para botões de adicionar ao carrinho, curtir e favoritar.

``` dart
animationStyle: ButtonAnimationStyle.bounce()
```

Ajuste fino do efeito:

``` dart
ButtonAnimationStyle.bounce(
  scaleMin: 0.90,         // Minimum scale on press (default: 0.92)
  duration: Duration(milliseconds: 150),
  curve: Curves.easeOutBack,
  enableHapticFeedback: true,
)
```

<div id="anim-pulse"></div>

### Pulse

Uma pulsação sutil e contínua de escala enquanto o botão é mantido pressionado. Melhor para ações de pressionar longo ou chamar atenção.

``` dart
animationStyle: ButtonAnimationStyle.pulse()
```

Ajuste fino do efeito:

``` dart
ButtonAnimationStyle.pulse(
  pulseScale: 1.08,       // Max scale during pulse (default: 1.05)
  duration: Duration(milliseconds: 800),
  curve: Curves.easeInOut,
)
```

<div id="anim-squeeze"></div>

### Squeeze

Comprime o botão horizontalmente e expande verticalmente ao pressionar. Melhor para UIs divertidas e interativas.

``` dart
animationStyle: ButtonAnimationStyle.squeeze()
```

Ajuste fino do efeito:

``` dart
ButtonAnimationStyle.squeeze(
  squeezeX: 0.93,         // Horizontal scale (default: 0.95)
  squeezeY: 1.07,         // Vertical scale (default: 1.05)
  duration: Duration(milliseconds: 120),
  enableHapticFeedback: true,
)
```

<div id="anim-jelly"></div>

### Jelly

Um efeito de deformação elástica trêmulo. Melhor para apps divertidos, casuais ou de entretenimento.

``` dart
animationStyle: ButtonAnimationStyle.jelly()
```

Ajuste fino do efeito:

``` dart
ButtonAnimationStyle.jelly(
  jellyStrength: 0.2,     // Wobble intensity (default: 0.15)
  duration: Duration(milliseconds: 300),
  curve: Curves.elasticOut,
  enableHapticFeedback: true,
)
```

<div id="anim-shine"></div>

### Shine

Um destaque brilhante que percorre o botão ao pressionar. Melhor para funcionalidades premium ou CTAs para os quais você quer chamar atenção.

``` dart
animationStyle: ButtonAnimationStyle.shine()
```

Ajuste fino do efeito:

``` dart
ButtonAnimationStyle.shine(
  shineColor: Colors.white,  // Color of the shine streak (default: white)
  shineWidth: 0.4,           // Width of the shine band (default: 0.3)
  duration: Duration(milliseconds: 600),
)
```

<div id="anim-ripple"></div>

### Ripple

Um efeito de ripple aprimorado que se expande a partir do ponto de toque. Melhor para ênfase no Material Design.

``` dart
animationStyle: ButtonAnimationStyle.ripple()
```

Ajuste fino do efeito:

``` dart
ButtonAnimationStyle.ripple(
  rippleScale: 2.5,       // How far the ripple expands (default: 2.0)
  duration: Duration(milliseconds: 400),
  curve: Curves.easeOut,
  enableHapticFeedback: true,
)
```

<div id="anim-morph"></div>

### Morph

O raio da borda do botão aumenta ao pressionar, criando um efeito de mudança de forma. Melhor para feedback sutil e elegante.

``` dart
animationStyle: ButtonAnimationStyle.morph()
```

Ajuste fino do efeito:

``` dart
ButtonAnimationStyle.morph(
  morphRadius: 30.0,      // Target border radius on press (default: 24.0)
  duration: Duration(milliseconds: 150),
  curve: Curves.easeInOut,
)
```

<div id="anim-shake"></div>

### Shake

Uma animação de tremor horizontal. Melhor para estados de erro ou ações inválidas — tremendo o botão para sinalizar que algo deu errado.

``` dart
animationStyle: ButtonAnimationStyle.shake()
```

Ajuste fino do efeito:

``` dart
ButtonAnimationStyle.shake(
  shakeOffset: 10.0,      // Horizontal displacement (default: 8.0)
  shakeCount: 4,          // Number of shakes (default: 3)
  duration: Duration(milliseconds: 400),
  enableHapticFeedback: true,
)
```

### Desabilitando Animações

Para usar um botão sem animação:

``` dart
animationStyle: ButtonAnimationStyle.none()
```

### Alterando a Animação Padrão

Para alterar a animação padrão de um tipo de botão, modifique o arquivo `lib/resources/widgets/buttons/buttons.dart`:

``` dart
class Button {
  static Widget primary({
    required String text,
    VoidCallback? onPressed,
    ...
  }) {
    return PrimaryButton(
      text: text,
      onPressed: onPressed,
      animationStyle: ButtonAnimationStyle.bounce(), // Change the default
    );
  }
}
```

<div id="splash-styles"></div>

## Estilos de Splash

Efeitos de splash fornecem feedback visual de toque nos botões. Configure-os através do `ButtonSplashStyle`. Estilos de splash podem ser combinados com estilos de animação para feedback em camadas.

### Estilos de Splash Disponíveis

| Splash | Factory | Descrição |
|--------|---------|-------------|
| Ripple | `ButtonSplashStyle.ripple()` | Ripple Material padrão a partir do ponto de toque |
| Highlight | `ButtonSplashStyle.highlight()` | Destaque sutil sem animação de ripple |
| Glow | `ButtonSplashStyle.glow()` | Brilho suave irradiando do ponto de toque |
| Ink | `ButtonSplashStyle.ink()` | Splash de tinta rápido, mais rápido e responsivo |
| None | `ButtonSplashStyle.none()` | Sem efeito de splash |
| Custom | `ButtonSplashStyle.custom()` | Controle total sobre a factory do splash |

### Exemplo

``` dart
class Button {
  static Widget outlined({
    required String text,
    VoidCallback? onPressed,
    ...
  }) {
    return OutlinedButton(
      text: text,
      onPressed: onPressed,
      splashStyle: ButtonSplashStyle.ripple(),
      animationStyle: ButtonAnimationStyle.clickable(),
    );
  }
}
```

Você pode personalizar cores e opacidade do splash:

``` dart
ButtonSplashStyle.ripple(
  splashColor: Colors.blue,
  highlightColor: Colors.blue,
  splashOpacity: 0.2,
  highlightOpacity: 0.1,
)
```

<div id="loading-styles"></div>

## Estilos de Carregamento

O indicador de carregamento exibido durante operações assíncronas é controlado pelo `LoadingStyle`. Você pode defini-lo por tipo de botão no arquivo de botões.

### Skeletonizer (Padrão)

Exibe um efeito de shimmer skeleton sobre o botão:

``` dart
loadingStyle: LoadingStyle.skeletonizer()
```

### Normal

Exibe um widget de carregamento (padrão para o loader do app):

``` dart
loadingStyle: LoadingStyle.normal(
  child: Text("Please wait..."),
)
```

### None

Mantém o botão visível mas desabilita a interação durante o carregamento:

``` dart
loadingStyle: LoadingStyle.none()
```

<div id="form-submission"></div>

## Envio de Formulário

Todos os botões suportam o parâmetro `submitForm`, que conecta o botão a um `NyForm`. Quando tocado, o botão validará o formulário e chamará seu handler de sucesso com os dados do formulário.

``` dart
Button.primary(
  text: "Submit",
  submitForm: (LoginForm(), (data) {
    // data contains the validated form fields
    print(data);
  }),
  onFailure: (error) {
    // Handle validation errors
    print(error);
  },
)
```

O parâmetro `submitForm` aceita um record com dois valores:
1. Uma instância de `NyFormData` (ou nome do formulário como `String`)
2. Um callback que recebe os dados validados

Por padrão, `showToastError` é `true`, que exibe uma notificação toast quando a validação do formulário falha. Defina como `false` para tratar erros silenciosamente:

``` dart
Button.primary(
  text: "Login",
  submitForm: (LoginForm(), (data) async {
    await api<AuthApiService>((request) => request.login(data));
  }),
  showToastError: false,
  onFailure: (error) {
    // Custom error handling
  },
)
```

Quando o callback `submitForm` retorna um `Future`, o botão exibirá automaticamente um estado de carregamento até que a operação assíncrona seja concluída.

<div id="customizing-buttons"></div>

## Personalizando Botões

Todos os padrões de botão são definidos no seu projeto em `lib/resources/widgets/buttons/buttons.dart`. Cada tipo de botão tem uma classe widget correspondente em `lib/resources/widgets/buttons/partials/`.

### Alterando Estilos Padrão

Para modificar a aparência padrão de um botão, edite a classe `Button`:

``` dart
class Button {
  static Widget primary({
    required String text,
    VoidCallback? onPressed,
    Function(dynamic error)? onFailure,
    bool showToastError = true,
    double? width,
  }) {
    return PrimaryButton(
      text: text,
      onPressed: onPressed,
      submitForm: submitForm,
      onFailure: onFailure,
      showToastError: showToastError,
      loadingStyle: LoadingStyle.skeletonizer(),
      width: width,
      height: 52.0,
      animationStyle: ButtonAnimationStyle.bounce(),
      splashStyle: ButtonSplashStyle.glow(),
    );
  }
}
```

### Personalizando um Widget de Botão

Para alterar a aparência visual de um tipo de botão, edite o widget correspondente em `lib/resources/widgets/buttons/partials/`. Por exemplo, para alterar o raio da borda ou sombra do botão primary:

``` dart
// lib/resources/widgets/buttons/partials/primary_button_widget.dart

class PrimaryButton extends StatefulAppButton {
  ...

  @override
  Widget buildButton(BuildContext context) {
    final theme = Theme.of(context);
    final bgColor = backgroundColor ?? theme.colorScheme.primary;
    final fgColor = contentColor ?? theme.colorScheme.onPrimary;

    return Container(
      width: width ?? double.infinity,
      height: height,
      decoration: BoxDecoration(
        color: bgColor,
        borderRadius: BorderRadius.circular(8), // Change the radius
      ),
      child: Center(
        child: Text(
          text,
          style: TextStyle(
            color: fgColor,
            fontSize: 16,
            fontWeight: FontWeight.w600,
          ),
        ),
      ),
    );
  }
}
```

### Criando um Novo Tipo de Botão

Para adicionar um novo tipo de botão:

1. Crie um novo arquivo de widget em `lib/resources/widgets/buttons/partials/` estendendo `StatefulAppButton`.
2. Implemente o método `buildButton`.
3. Adicione um método estático na classe `Button`.

``` dart
// lib/resources/widgets/buttons/partials/danger_button_widget.dart

class DangerButton extends StatefulAppButton {
  DangerButton({
    required super.text,
    super.onPressed,
    super.submitForm,
    super.onFailure,
    super.showToastError,
    super.loadingStyle,
    super.width,
    super.height,
    super.animationStyle,
    super.splashStyle,
  });

  @override
  Widget buildButton(BuildContext context) {
    return Container(
      width: width ?? double.infinity,
      height: height,
      decoration: BoxDecoration(
        color: Colors.red,
        borderRadius: BorderRadius.circular(14),
      ),
      child: Center(
        child: Text(
          text,
          style: TextStyle(
            color: Colors.white,
            fontSize: 16,
            fontWeight: FontWeight.w600,
          ),
        ),
      ),
    );
  }
}
```

Depois registre-o na classe `Button`:

``` dart
class Button {
  ...

  static Widget danger({
    required String text,
    VoidCallback? onPressed,
    Function(dynamic error)? onFailure,
    bool showToastError = true,
    double? width,
  }) {
    return DangerButton(
      text: text,
      onPressed: onPressed,
      submitForm: submitForm,
      onFailure: onFailure,
      showToastError: showToastError,
      loadingStyle: LoadingStyle.skeletonizer(),
      width: width,
      height: 52.0,
      animationStyle: ButtonAnimationStyle.shake(),
    );
  }
}
```

<div id="parameters"></div>

## Referência de Parâmetros

### Parâmetros Comuns (Todos os Tipos de Botão)

| Parâmetro | Tipo | Padrão | Descrição |
|-----------|------|---------|-------------|
| `text` | `String` | obrigatório | O texto do rótulo do botão |
| `onPressed` | `VoidCallback?` | `null` | Callback quando o botão é tocado. Retorne um `Future` para estado de carregamento automático |
| `submitForm` | `(dynamic, Function(dynamic))?` | `null` | Record de envio de formulário (instância do formulário, callback de sucesso) |
| `onFailure` | `Function(dynamic)?` | `null` | Chamado quando a validação do formulário falha |
| `showToastError` | `bool` | `true` | Exibir notificação toast em erro de validação do formulário |
| `width` | `double?` | `null` | Largura do botão (padrão é largura total) |

### Parâmetros Específicos por Tipo

#### Button.outlined

| Parâmetro | Tipo | Padrão | Descrição |
|-----------|------|---------|-------------|
| `borderColor` | `Color?` | Cor de contorno do tema | Cor da borda |
| `textColor` | `Color?` | Cor primária do tema | Cor do texto |

#### Button.textOnly

| Parâmetro | Tipo | Padrão | Descrição |
|-----------|------|---------|-------------|
| `textColor` | `Color?` | Cor primária do tema | Cor do texto |

#### Button.icon

| Parâmetro | Tipo | Padrão | Descrição |
|-----------|------|---------|-------------|
| `icon` | `Widget` | obrigatório | O widget de ícone a exibir |
| `color` | `Color?` | Cor primária do tema | Cor de fundo |

#### Button.gradient

| Parâmetro | Tipo | Padrão | Descrição |
|-----------|------|---------|-------------|
| `gradientColors` | `List<Color>?` | Cores primária e terciária | Paradas de cor do gradiente |

#### Button.rounded

| Parâmetro | Tipo | Padrão | Descrição |
|-----------|------|---------|-------------|
| `backgroundColor` | `Color?` | Cor de container primário do tema | Cor de fundo |
| `borderRadius` | `BorderRadius?` | Formato de pílula (altura / 2) | Raio do canto |

#### Button.transparency

| Parâmetro | Tipo | Padrão | Descrição |
|-----------|------|---------|-------------|
| `color` | `Color?` | Adaptável ao tema | Cor do texto |

### Parâmetros do ButtonAnimationStyle

| Parâmetro | Tipo | Padrão | Descrição |
|-----------|------|---------|-------------|
| `duration` | `Duration` | Varia por tipo | Duração da animação |
| `curve` | `Curve` | Varia por tipo | Curva da animação |
| `enableHapticFeedback` | `bool` | Varia por tipo | Acionar feedback háptico ao pressionar |
| `translateY` | `double` | `4.0` | Clickable: distância vertical de pressão |
| `shadowOffset` | `double` | `4.0` | Clickable: profundidade da sombra |
| `scaleMin` | `double` | `0.92` | Bounce: escala mínima ao pressionar |
| `pulseScale` | `double` | `1.05` | Pulse: escala máxima durante o pulso |
| `squeezeX` | `double` | `0.95` | Squeeze: compressão horizontal |
| `squeezeY` | `double` | `1.05` | Squeeze: expansão vertical |
| `jellyStrength` | `double` | `0.15` | Jelly: intensidade do tremor |
| `shineColor` | `Color` | `Colors.white` | Shine: cor do destaque |
| `shineWidth` | `double` | `0.3` | Shine: largura da faixa de brilho |
| `rippleScale` | `double` | `2.0` | Ripple: escala de expansão |
| `morphRadius` | `double` | `24.0` | Morph: raio da borda alvo |
| `shakeOffset` | `double` | `8.0` | Shake: deslocamento horizontal |
| `shakeCount` | `int` | `3` | Shake: número de oscilações |

### Parâmetros do ButtonSplashStyle

| Parâmetro | Tipo | Padrão | Descrição |
|-----------|------|---------|-------------|
| `splashColor` | `Color?` | Cor de superfície do tema | Cor do efeito splash |
| `highlightColor` | `Color?` | Cor de superfície do tema | Cor do efeito highlight |
| `splashOpacity` | `double` | `0.12` | Opacidade do splash |
| `highlightOpacity` | `double` | `0.06` | Opacidade do highlight |
| `borderRadius` | `BorderRadius?` | `null` | Raio de recorte do splash |
