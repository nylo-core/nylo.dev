# TextTr

---

<a name="section-1"></a>
- [Introdução](#introduction "Introdução")
- [Uso Básico](#basic-usage "Uso Básico")
- [Interpolação de Strings](#string-interpolation "Interpolação de Strings")
- [Construtores Estilizados](#styled-constructors "Construtores Estilizados")
- [Parâmetros](#parameters "Parâmetros")


<div id="introduction"></div>

## Introdução

O widget **TextTr** é um wrapper de conveniência em torno do widget `Text` do Flutter que traduz automaticamente seu conteúdo usando o sistema de localização do {{ config('app.name') }}.

Em vez de escrever:

``` dart
Text("hello_world".tr())
```

Você pode escrever:

``` dart
TextTr("hello_world")
```

Isso torna seu código mais limpo e legível, especialmente ao lidar com muitas strings traduzidas.

<div id="basic-usage"></div>

## Uso Básico

``` dart
@override
Widget build(BuildContext context) {
  return Column(
    children: [
      TextTr("welcome_message"),

      TextTr(
        "app_title",
        style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
        textAlign: TextAlign.center,
      ),
    ],
  );
}
```

O widget buscará a chave de tradução nos seus arquivos de idioma (ex.: `/lang/en.json`):

``` json
{
  "welcome_message": "Welcome to our app!",
  "app_title": "My Application"
}
```

Se uma chave estiver ausente no arquivo do locale ativo, o {{ config('app.name') }} a busca automaticamente no idioma de fallback (configurado em `lib/config/localization.dart`) antes de retornar a string da chave bruta. Isso se aplica tanto a chaves de nível superior quanto a chaves aninhadas com notação de ponto.

<div id="string-interpolation"></div>

## Interpolação de Strings

Use o parâmetro `arguments` para injetar valores dinâmicos nas suas traduções:

``` dart
TextTr(
  "greeting",
  arguments: {"name": "John"},
)
```

No seu arquivo de idioma:

``` json
{
  "greeting": "Hello, @{{name}}!"
}
```

Saída: **Hello, John!**

### Múltiplos Argumentos

``` dart
TextTr(
  "order_summary",
  arguments: {
    "item": "Coffee",
    "quantity": "2",
    "total": "\$8.50",
  },
)
```

``` json
{
  "order_summary": "You ordered @{{quantity}}x @{{item}} for @{{total}}"
}
```

Saída: **You ordered 2x Coffee for $8.50**

<div id="styled-constructors"></div>

## Construtores Estilizados

O `TextTr` fornece construtores nomeados que aplicam automaticamente estilos de texto do seu tema:

### displayLarge

``` dart
TextTr.displayLarge("page_title")
```

Usa o estilo `Theme.of(context).textTheme.displayLarge`.

### headlineLarge

``` dart
TextTr.headlineLarge("section_heading")
```

Usa o estilo `Theme.of(context).textTheme.headlineLarge`.

### bodyLarge

``` dart
TextTr.bodyLarge("paragraph_text")
```

Usa o estilo `Theme.of(context).textTheme.bodyLarge`.

### labelLarge

``` dart
TextTr.labelLarge("button_label")
```

Usa o estilo `Theme.of(context).textTheme.labelLarge`.

### Exemplo com Construtores Estilizados

``` dart
@override
Widget build(BuildContext context) {
  return Column(
    crossAxisAlignment: CrossAxisAlignment.start,
    children: [
      TextTr.headlineLarge("welcome_title"),
      SizedBox(height: 16),
      TextTr.bodyLarge(
        "welcome_description",
        arguments: {"app_name": "MyApp"},
      ),
      SizedBox(height: 24),
      TextTr.labelLarge("get_started"),
    ],
  );
}
```

<div id="parameters"></div>

## Parâmetros

O `TextTr` suporta todos os parâmetros padrão do widget `Text`:

| Parâmetro | Tipo | Descrição |
|-----------|------|-------------|
| `data` | `String` | A chave de tradução a ser buscada |
| `arguments` | `Map<String, String>?` | Pares chave-valor para interpolação de strings |
| `style` | `TextStyle?` | Estilização do texto |
| `textAlign` | `TextAlign?` | Como o texto deve ser alinhado |
| `maxLines` | `int?` | Número máximo de linhas |
| `overflow` | `TextOverflow?` | Como tratar overflow |
| `softWrap` | `bool?` | Se deve quebrar o texto em quebras suaves |
| `textDirection` | `TextDirection?` | Direção do texto |
| `locale` | `Locale?` | Localidade para renderização do texto |
| `semanticsLabel` | `String?` | Label de acessibilidade |

## Comparação

| Abordagem | Código |
|----------|------|
| Tradicional | `Text("hello".tr())` |
| TextTr | `TextTr("hello")` |
| Com argumentos | `TextTr("hello", arguments: {"name": "John"})` |
| Estilizado | `TextTr.headlineLarge("title")` |
