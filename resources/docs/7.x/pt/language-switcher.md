# LanguageSwitcher

---

<a name="section-1"></a>
- [Introducao](#introduction "Introducao")
- Uso
    - [Widget Dropdown](#usage-dropdown "Widget Dropdown")
    - [Modal Bottom Sheet](#usage-bottom-modal "Modal Bottom Sheet")
- [Builder de Dropdown Personalizado](#custom-builder "Builder de Dropdown Personalizado")
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
        LanguageSwitcher(), // Add to the app bar
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

### Personalizando a Altura do Modal

``` dart
LanguageSwitcher.showBottomModal(
  context,
  height: 300, // Custom height
);
```

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
        Text(language['name']), // e.g., "English"
        // language['locale'] contains the locale code, e.g., "en"
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
    // Perform additional actions when language changes
  },
)
```

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

// With custom height
await LanguageSwitcher.showBottomModal(context, height: 400);
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
