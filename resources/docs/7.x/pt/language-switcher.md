# LanguageSwitcher

---

<a name="section-1"></a>
- [Introducao](#introduction "Introducao")
- Uso
    - [Widget Dropdown](#usage-dropdown "Widget Dropdown")
    - [Modal Bottom Sheet](#usage-bottom-modal "Modal Bottom Sheet")
- [Estilo de Animacao](#animation-style "Estilo de Animacao")
- [Builder de Dropdown Personalizado](#custom-builder "Builder de Dropdown Personalizado")
- [Acoes de Estado](#state-actions "Acoes de Estado")
- [Parametros](#parameters "Parametros")
- [Metodos Estaticos](#methods "Metodos Estaticos")


<div id="introduction"></div>

## Introducao

O widget **LanguageSwitcher** oferece uma forma facil de gerenciar a troca de idioma nos seus projetos {{ config('app.name') }}. Ele detecta automaticamente os idiomas disponiveis no seu diretorio `/lang` e os exibe ao usuario.

**O que o LanguageSwitcher faz?**

- Exibe os idiomas disponiveis do seu diretorio `/lang`
- Troca o idioma do app quando o usuario seleciona um
- Persiste o idioma selecionado entre reinicializacoes do app
- Atualiza automaticamente a interface quando o idioma muda

> **Nota**: Se seu app ainda nao esta localizado, aprenda como fazer isso na documentacao de [Localization](/docs/7.x/localization) antes de usar este widget.

<div id="usage-dropdown"></div>

## Widget Dropdown

A forma mais simples de usar o `LanguageSwitcher` e como um dropdown na barra do app:

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    appBar: AppBar(
      title: Text("Settings"),
      actions: [
        LanguageSwitcher(), // Adicionar a barra do app
      ],
    ),
    body: Center(
      child: Text("Hello World".tr()),
    ),
  );
}
```

Quando o usuario tocar no dropdown, vera uma lista de idiomas disponiveis. Apos selecionar um idioma, o app ira trocar automaticamente e atualizar a interface.

<div id="usage-bottom-modal"></div>

## Modal Bottom Sheet

Voce tambem pode exibir os idiomas em um modal bottom sheet:

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: Center(
      child: MaterialButton(
        child: Text("Change Language"),
        onPressed: () {
          LanguageSwitcher.showBottomModal(context);
        },
      ),
    ),
  );
}
```

O modal bottom exibe uma lista de idiomas com uma marca de selecao ao lado do idioma atualmente selecionado.

### Personalizando o Modal

``` dart
LanguageSwitcher.showBottomModal(
  context,
  height: 300,
  useRootNavigator: true, // Exibir modal acima de todas as rotas, incluindo barras de abas
  onLanguageChange: (String languageKey) {
    print('Language changed to: $languageKey');
  },
);
```

<div id="animation-style"></div>

## Estilo de Animacao

O parametro `animationStyle` controla as animacoes de transicao tanto para o gatilho do dropdown quanto para os itens da lista no modal bottom. Quatro presets estao disponiveis:

``` dart
// Sem animacoes
LanguageSwitcher(
  animationStyle: LanguageSwitcherAnimationStyle.none(),
)

// Animacoes sutis e refinadas (recomendado para a maioria dos apps)
LanguageSwitcher(
  animationStyle: LanguageSwitcherAnimationStyle.subtle(),
)

// Animacoes divertidas e elasticas
LanguageSwitcher(
  animationStyle: LanguageSwitcherAnimationStyle.bouncy(),
)

// Fade-in suave com escala gentil
LanguageSwitcher(
  animationStyle: LanguageSwitcherAnimationStyle.fadeIn(),
)
```

Voce tambem pode passar um `LanguageSwitcherAnimationStyle()` personalizado com parametros individuais, ou usar `copyWith` para ajustar um preset.

O mesmo parametro `animationStyle` e aceito por `LanguageSwitcher.showBottomModal`.

<div id="custom-builder"></div>

## Builder de Dropdown Personalizado

Personalize como cada opcao de idioma aparece no dropdown:

``` dart
LanguageSwitcher(
  dropdownBuilder: (Map<String, dynamic> language) {
    return Row(
      children: [
        Icon(Icons.language),
        SizedBox(width: 8),
        Text(language['name']), // ex., "English"
        // language['locale'] contem o codigo da localidade, ex., "en"
      ],
    );
  },
)
```

### Tratando Mudancas de Idioma

``` dart
LanguageSwitcher(
  onLanguageChange: (Map<String, dynamic> language) {
    print('Language changed to: ${language['name']}');
    // Executar acoes adicionais quando o idioma mudar
  },
)
```

<div id="state-actions"></div>

## Acoes de Estado

Controle o `LanguageSwitcher` programaticamente usando `stateActions()`:

``` dart
// Atualizar a lista de idiomas (rele os idiomas disponiveis)
LanguageSwitcher.stateActions().refresh();

// Trocar para um idioma pelo codigo da localidade
LanguageSwitcher.stateActions().setLanguage("es");
LanguageSwitcher.stateActions().setLanguage("fr");
```

Util quando voce quer mudar o idioma do app sem interacao do usuario, por exemplo apos fazer login com uma preferencia de usuario.

<div id="parameters"></div>

## Parametros

| Parametro | Tipo | Padrao | Descricao |
|-----------|------|--------|-----------|
| `icon` | `Widget?` | - | Icone personalizado para o botao dropdown |
| `iconEnabledColor` | `Color?` | - | Cor do icone do dropdown |
| `iconSize` | `double` | `24` | Tamanho do icone do dropdown |
| `dropdownBgColor` | `Color?` | - | Cor de fundo do menu dropdown |
| `hint` | `Widget?` | - | Widget de dica quando nenhum idioma esta selecionado |
| `itemHeight` | `double` | `kMinInteractiveDimension` | Altura de cada item do dropdown |
| `elevation` | `int` | `8` | Elevacao do menu dropdown |
| `padding` | `EdgeInsetsGeometry?` | - | Preenchimento ao redor do dropdown |
| `borderRadius` | `BorderRadius?` | - | Raio da borda do menu dropdown |
| `textStyle` | `TextStyle?` | - | Estilo de texto para os itens do dropdown |
| `langPath` | `String` | `'lang'` | Caminho para os arquivos de idioma nos assets |
| `dropdownBuilder` | `Function(Map<String, dynamic>)?` | - | Builder personalizado para os itens do dropdown |
| `dropdownAlignment` | `AlignmentGeometry` | `AlignmentDirectional.centerStart` | Alinhamento dos itens do dropdown |
| `dropdownOnTap` | `Function()?` | - | Callback quando um item do dropdown e tocado |
| `onTap` | `Function()?` | - | Callback quando o botao dropdown e tocado |
| `onLanguageChange` | `Function(Map<String, dynamic>)?` | - | Callback quando o idioma e alterado |

<div id="methods"></div>

## Metodos Estaticos

### Obter o Idioma Atual

Recupere o idioma atualmente selecionado:

``` dart
Map<String, dynamic>? lang = await LanguageSwitcher.currentLanguage();
// Returns: {"en": "English"} or null if not set
```

### Armazenar Idioma

Armazene manualmente uma preferencia de idioma:

``` dart
await LanguageSwitcher.storeLanguage(
  object: {"fr": "French"},
);
```

### Limpar Idioma

Remova a preferencia de idioma armazenada:

``` dart
await LanguageSwitcher.clearLanguage();
```

### Obter Dados do Idioma

Obtenha informacoes do idioma a partir de um codigo de localidade:

``` dart
Map<String, String>? langData = LanguageSwitcher.getLanguageData("en");
// Returns: {"en": "English"}

Map<String, String>? langData = LanguageSwitcher.getLanguageData("fr_CA");
// Returns: {"fr_CA": "French (Canada)"}
```

### Obter Lista de Idiomas

Obtenha todos os idiomas disponiveis do diretorio `/lang`:

``` dart
List<Map<String, String>> languages = await LanguageSwitcher.getLanguageList();
// Returns: [{"en": "English"}, {"es": "Spanish"}, ...]
```

### Exibir Modal Bottom

Exiba o modal de selecao de idioma:

``` dart
await LanguageSwitcher.showBottomModal(context);

// Com opcoes
await LanguageSwitcher.showBottomModal(
  context,
  height: 400,
  useRootNavigator: true,
  onLanguageChange: (String languageKey) {
    print('Language changed to: $languageKey');
  },
  animationStyle: LanguageSwitcherAnimationStyle.subtle(),
);
```

## Localidades Suportadas

O widget `LanguageSwitcher` suporta centenas de codigos de localidade com nomes legiveis. Alguns exemplos:

| Codigo de Localidade | Nome do Idioma |
|----------------------|----------------|
| `en` | English |
| `en_US` | English (United States) |
| `en_GB` | English (United Kingdom) |
| `es` | Spanish |
| `fr` | French |
| `de` | German |
| `zh` | Chinese |
| `zh_Hans` | Chinese (Simplified) |
| `ja` | Japanese |
| `ko` | Korean |
| `ar` | Arabic |
| `hi` | Hindi |
| `pt` | Portuguese |
| `ru` | Russian |

A lista completa inclui variantes regionais para a maioria dos idiomas.
