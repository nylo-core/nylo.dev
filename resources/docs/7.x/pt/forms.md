# Forms

---

<a name="section-1"></a>
- [Introdução](#introduction "Introdução aos formulários")
- Primeiros Passos
  - [Criando um Formulário](#creating-forms "Criando formulários")
  - [Exibindo um Formulário](#displaying-a-form "Exibindo um formulário")
  - [Enviando um Formulário](#submitting-a-form "Enviando um formulário")
- Tipos de Campos
  - [Campos de Texto](#text-fields "Campos de Texto")
  - [Campos Numéricos](#number-fields "Campos Numéricos")
  - [Campos de Senha](#password-fields "Campos de Senha")
  - [Campos de Email](#email-fields "Campos de Email")
  - [Campos de URL](#url-fields "Campos de URL")
  - [Campos de Área de Texto](#text-area-fields "Campos de Área de Texto")
  - [Campos de Telefone](#phone-number-fields "Campos de Telefone")
  - [Capitalizar Palavras](#capitalize-words-fields "Campos de Capitalizar Palavras")
  - [Capitalizar Sentenças](#capitalize-sentences-fields "Campos de Capitalizar Sentenças")
  - [Campos de Data](#date-fields "Campos de Data")
  - [Campos de Data e Hora](#datetime-fields "Campos de Data e Hora")
  - [Campos de Input com Máscara](#masked-input-fields "Campos de Input com Máscara")
  - [Campos de Moeda](#currency-fields "Campos de Moeda")
  - [Campos de Checkbox](#checkbox-fields "Campos de Checkbox")
  - [Campos de Switch Box](#switch-box-fields "Campos de Switch Box")
  - [Campos de Picker](#picker-fields "Campos de Picker")
  - [Campos de Radio](#radio-fields "Campos de Radio")
  - [Campos de Chip](#chip-fields "Campos de Chip")
  - [Campos de Slider](#slider-fields "Campos de Slider")
  - [Campos de Range Slider](#range-slider-fields "Campos de Range Slider")
  - [Campos Personalizados](#custom-fields "Campos Personalizados")
  - [Campos de Widget](#widget-fields "Campos de Widget")
- [FormCollection](#form-collection "FormCollection")
- [Validação de Formulário](#form-validation "Validação de Formulário")
- [Gerenciando Dados do Formulário](#managing-form-data "Gerenciando Dados do Formulário")
  - [Dados Iniciais](#initial-data "Dados Iniciais")
  - [Definindo Valores de Campos](#setting-field-values "Definindo Valores de Campos")
  - [Definindo Opções de Campos](#setting-field-options "Definindo Opções de Campos")
  - [Lendo Dados do Formulário](#reading-form-data "Lendo Dados do Formulário")
  - [Limpando Dados](#clearing-data "Limpando Dados")
  - [Atualizando Campos](#finding-and-updating-fields "Atualizando Campos")
- [Botão de Envio](#submit-button "Botão de Envio")
- [Layout do Formulário](#form-layout "Layout do Formulário")
- [Visibilidade de Campos](#field-visibility "Visibilidade de Campos")
- [Estilização de Campos](#field-styling "Estilização de Campos")
- [Métodos Estáticos do NyFormWidget](#ny-form-widget-static-methods "Métodos Estáticos do NyFormWidget")
- [Referência do Construtor NyFormWidget](#ny-form-widget-constructor-reference "Referência do Construtor NyFormWidget")
- [NyFormActions](#ny-form-actions "NyFormActions")
- [Referência de Todos os Tipos de Campo](#all-field-types-reference "Referência de Todos os Tipos de Campo")

<div id="introduction"></div>

## Introdução

{{ config('app.name') }} v7 fornece um sistema de formulários construído em torno do `NyFormWidget`. Sua classe de formulário estende `NyFormWidget` e **é** o widget -- nenhum wrapper separado é necessário. Formulários suportam validação integrada, vários tipos de campos, estilização e gerenciamento de dados.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// 1. Definir um formulário
class LoginForm extends NyFormWidget {
  LoginForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  fields() => [
    Field.email("Email", validator: FormValidator.email()),
    Field.password("Password", validator: FormValidator.password()),
  ];

  static NyFormActions get actions => const NyFormActions('LoginForm');
}

// 2. Exibir e enviar
LoginForm(
  submitButton: Button.primary(text: "Login"),
  onSubmit: (data) {
    print(data); // {email: "...", password: "..."}
  },
)
```


<div id="creating-forms"></div>

## Criando um Formulário

Use o Metro CLI para criar um novo formulário:

``` bash
metro make:form LoginForm
```

Isso cria `lib/app/forms/login_form.dart`:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class LoginForm extends NyFormWidget {
  LoginForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  fields() => [
    Field.email("Email", validator: FormValidator.email()),
    Field.password("Password", validator: FormValidator.password()),
  ];

  static NyFormActions get actions => const NyFormActions('LoginForm');
}
```

Formulários estendem `NyFormWidget` e sobrescrevem o método `fields()` para definir os campos do formulário. Cada campo usa um construtor nomeado como `Field.text()`, `Field.email()` ou `Field.password()`. O getter `static NyFormActions get actions` fornece uma maneira conveniente de interagir com o formulário de qualquer lugar no seu app.


<div id="displaying-a-form"></div>

## Exibindo um Formulário

Como sua classe de formulário estende `NyFormWidget`, ela **é** o widget. Use-a diretamente na sua árvore de widgets:

``` dart
@override
Widget view(BuildContext context) {
  return Scaffold(
    body: SafeArea(
      child: LoginForm(
        submitButton: Button.primary(text: "Submit"),
        onSubmit: (data) {
          print(data);
        },
      ),
    ),
  );
}
```


<div id="submitting-a-form"></div>

## Enviando um Formulário

Existem três maneiras de enviar um formulário:

### Usando onSubmit e submitButton

Passe `onSubmit` e um `submitButton` ao construir o formulário. {{ config('app.name') }} fornece botões pré-construídos que funcionam como botões de envio:

``` dart
LoginForm(
  submitButton: Button.primary(text: "Submit"),
  onSubmit: (data) {
    print(data); // {email: "...", password: "..."}
  },
  onFailure: (errors) {
    print(errors.first.rule.getMessage());
  },
)
```

Estilos de botão disponíveis: `Button.primary`, `Button.secondary`, `Button.outlined`, `Button.textOnly`, `Button.icon`, `Button.gradient`, `Button.rounded`, `Button.transparency`.

### Usando NyFormActions

Use o getter `actions` para enviar de qualquer lugar:

``` dart
LoginForm.actions.submit(
  onSuccess: (data) {
    print(data);
  },
  onFailure: (errors) {
    print(errors.first.rule.getMessage());
  },
  showToastError: true,
);
```

### Usando o método estático NyFormWidget.submit()

Envie um formulário pelo nome de qualquer lugar:

``` dart
NyFormWidget.submit("LoginForm",
  onSuccess: (data) {
    print(data);
  },
  onFailure: (errors) {
    print(errors.first.rule.getMessage());
  },
  showToastError: true,
);
```

Quando enviado, o formulário valida todos os campos. Se válido, `onSuccess` é chamado com um `Map<String, dynamic>` dos dados dos campos (as chaves são versões em snake_case dos nomes dos campos). Se inválido, um erro toast é exibido por padrão e `onFailure` é chamado se fornecido.


<div id="field-types"></div>

## Tipos de Campos

{{ config('app.name') }} v7 fornece 22 tipos de campos via construtores nomeados na classe `Field`. Todos os construtores de campos compartilham estes parâmetros comuns:

| Parâmetro | Tipo | Padrão | Descrição |
|-----------|------|---------|-------------|
| `key` | `String` | Obrigatório | O identificador do campo (posicional) |
| `label` | `String?` | `null` | Rótulo de exibição personalizado (padrão: chave em title case) |
| `value` | `dynamic` | `null` | Valor inicial |
| `validator` | `FormValidator?` | `null` | Regras de validação |
| `autofocus` | `bool` | `false` | Foco automático ao carregar |
| `dummyData` | `String?` | `null` | Dados de teste/desenvolvimento |
| `header` | `Widget?` | `null` | Widget exibido acima do campo |
| `footer` | `Widget?` | `null` | Widget exibido abaixo do campo |
| `titleStyle` | `TextStyle?` | `null` | Estilo de texto personalizado do rótulo |
| `hidden` | `bool` | `false` | Ocultar o campo |
| `readOnly` | `bool?` | `null` | Tornar o campo somente leitura |
| `style` | `FieldStyle?` | Varia | Configuração de estilo específica do campo |
| `onChanged` | `Function(dynamic)?` | `null` | Callback de mudança de valor |

<div id="text-fields"></div>

### Campos de Texto

``` dart
Field.text("Name")

Field.text("Name",
  value: "John",
  validator: FormValidator.notEmpty(),
  autofocus: true,
)
```

Tipo de estilo: `FieldStyleTextField`

<div id="number-fields"></div>

### Campos Numéricos

``` dart
Field.number("Age")

// Números decimais
Field.number("Score", decimal: true)
```

O parâmetro `decimal` controla se a entrada decimal é permitida. Tipo de estilo: `FieldStyleTextField`

<div id="password-fields"></div>

### Campos de Senha

``` dart
Field.password("Password")

// Com botão de visibilidade
Field.password("Password", viewable: true)
```

O parâmetro `viewable` adiciona um botão de mostrar/ocultar. Tipo de estilo: `FieldStyleTextField`

<div id="email-fields"></div>

### Campos de Email

``` dart
Field.email("Email", validator: FormValidator.email())
```

Define automaticamente o tipo de teclado para email e filtra espaços em branco. Tipo de estilo: `FieldStyleTextField`

<div id="url-fields"></div>

### Campos de URL

``` dart
Field.url("Website", validator: FormValidator.url())
```

Define o tipo de teclado para URL. Tipo de estilo: `FieldStyleTextField`

<div id="text-area-fields"></div>

### Campos de Área de Texto

``` dart
Field.textArea("Description")
```

Entrada de texto com múltiplas linhas. Tipo de estilo: `FieldStyleTextField`

<div id="phone-number-fields"></div>

### Campos de Telefone

``` dart
Field.phoneNumber("Mobile Phone")
```

Formata automaticamente a entrada de número de telefone. Tipo de estilo: `FieldStyleTextField`

<div id="capitalize-words-fields"></div>

### Capitalizar Palavras

``` dart
Field.capitalizeWords("Full Name")
```

Capitaliza a primeira letra de cada palavra. Tipo de estilo: `FieldStyleTextField`

<div id="capitalize-sentences-fields"></div>

### Capitalizar Sentenças

``` dart
Field.capitalizeSentences("Bio")
```

Capitaliza a primeira letra de cada sentença. Tipo de estilo: `FieldStyleTextField`

<div id="date-fields"></div>

### Campos de Data

``` dart
Field.date("Birthday")

Field.date("Birthday",
  dummyData: "1990-01-01",
  style: FieldStyleDateTimePicker(
    firstDate: DateTime(1900),
    lastDate: DateTime.now(),
  ),
)

// Desabilitar o botão de limpar
Field.date("Birthday",
  style: FieldStyleDateTimePicker(
    canClear: false,
  ),
)

// Ícone de limpar personalizado
Field.date("Birthday",
  style: FieldStyleDateTimePicker(
    clearIconData: Icons.close,
  ),
)
```

Abre um seletor de data. Por padrão, o campo mostra um botão de limpar que permite aos usuários redefinir o valor. Defina `canClear: false` para ocultá-lo, ou use `clearIconData` para mudar o ícone. Tipo de estilo: `FieldStyleDateTimePicker`

<div id="datetime-fields"></div>

### Campos de Data e Hora

``` dart
Field.datetime("Check in Date")

Field.datetime("Appointment",
  firstDate: DateTime(2025),
  lastDate: DateTime(2030),
  dateFormat: DateFormat('yyyy-MM-dd HH:mm'),
  initialPickerDateTime: DateTime.now(),
)
```

Abre um seletor de data e hora. Você pode definir `firstDate`, `lastDate`, `dateFormat` e `initialPickerDateTime` diretamente como parâmetros de nível superior. Tipo de estilo: `FieldStyleDateTimePicker`

<div id="masked-input-fields"></div>

### Campos de Input com Máscara

``` dart
Field.mask("Phone", mask: "(###) ###-####")

Field.mask("Credit Card", mask: "#### #### #### ####")

Field.mask("Custom Code",
  mask: "AA-####",
  match: r'[\w\d]',
  maskReturnValue: true, // Retorna o valor formatado
)
```

O caractere `#` na máscara é substituído pela entrada do usuário. Use `match` para controlar os caracteres permitidos. Quando `maskReturnValue` é `true`, o valor retornado inclui a formatação da máscara.

<div id="currency-fields"></div>

### Campos de Moeda

``` dart
Field.currency("Price", currency: "usd")
```

O parâmetro `currency` é obrigatório e determina o formato da moeda. Tipo de estilo: `FieldStyleTextField`

<div id="checkbox-fields"></div>

### Campos de Checkbox

``` dart
Field.checkbox("Accept Terms")

Field.checkbox("Agree to terms",
  header: Text("Terms and conditions"),
  footer: Text("You must agree to continue."),
  validator: FormValidator.booleanTrue(message: "You must accept the terms"),
)
```

Tipo de estilo: `FieldStyleCheckbox`

<div id="switch-box-fields"></div>

### Campos de Switch Box

``` dart
Field.switchBox("Enable Notifications")
```

Tipo de estilo: `FieldStyleSwitchBox`

<div id="picker-fields"></div>

### Campos de Picker

``` dart
Field.picker("Category",
  options: FormCollection.from(["Electronics", "Clothing", "Books"]),
)

// Com pares chave-valor
Field.picker("Country",
  options: FormCollection.fromMap({
    "us": "United States",
    "ca": "Canada",
    "uk": "United Kingdom",
  }),
)
```

O parâmetro `options` requer um `FormCollection` (não uma lista simples). Veja [FormCollection](#form-collection) para detalhes. Tipo de estilo: `FieldStylePicker`

#### Estilos de List Tile

Você pode personalizar como os itens aparecem no bottom sheet do picker usando `PickerListTileStyle`. Por padrão, o bottom sheet mostra tiles de texto simples. Use os presets integrados para adicionar indicadores de seleção, ou forneça um builder totalmente personalizado.

**Estilo radio** -- mostra um ícone de botão de rádio como widget principal:

``` dart
Field.picker("Country",
  options: FormCollection.from(["United States", "Canada", "United Kingdom"]),
  style: FieldStylePicker(
    listTileStyle: PickerListTileStyle.radio(),
  ),
)

// Com uma cor ativa personalizada
FieldStylePicker(
  listTileStyle: PickerListTileStyle.radio(activeColor: Colors.blue),
)
```

**Estilo checkmark** -- mostra um ícone de check como widget final quando selecionado:

``` dart
Field.picker("Category",
  options: FormCollection.from(["Electronics", "Clothing", "Books"]),
  style: FieldStylePicker(
    listTileStyle: PickerListTileStyle.checkmark(activeColor: Colors.green),
  ),
)
```

**Builder personalizado** -- controle total sobre o widget de cada tile:

``` dart
Field.picker("Color",
  options: FormCollection.from(["Red", "Green", "Blue"]),
  style: FieldStylePicker(
    listTileStyle: PickerListTileStyle.custom(
      builder: (option, isSelected, onTap) {
        return ListTile(
          title: Text(option.label,
            style: TextStyle(
              fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
            ),
          ),
          trailing: isSelected ? Icon(Icons.check_circle) : null,
          onTap: onTap,
        );
      },
    ),
  ),
)
```

Ambos os estilos preset também suportam `textStyle`, `selectedTextStyle`, `contentPadding`, `tileColor` e `selectedTileColor`:

``` dart
FieldStylePicker(
  listTileStyle: PickerListTileStyle.radio(
    activeColor: Colors.blue,
    textStyle: TextStyle(fontSize: 16),
    selectedTextStyle: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
    selectedTileColor: Colors.blue.shade50,
  ),
)
```

<div id="radio-fields"></div>

### Campos de Radio

``` dart
Field.radio("Newsletter",
  options: FormCollection.fromMap({
    "Yes": "Yes, I want to receive newsletters",
    "No": "No, I do not want to receive newsletters",
  }),
)
```

O parâmetro `options` requer um `FormCollection`. Tipo de estilo: `FieldStyleRadio`

<div id="chip-fields"></div>

### Campos de Chip

``` dart
Field.chips("Tags",
  options: FormCollection.from(["Featured", "Sale", "New"]),
)

// Com pares chave-valor
Field.chips("Engine Size",
  options: FormCollection.fromMap({
    "125": "125cc",
    "250": "250cc",
    "500": "500cc",
  }),
)
```

Permite seleção múltipla via widgets de chip. O parâmetro `options` requer um `FormCollection`. Tipo de estilo: `FieldStyleChip`

<div id="slider-fields"></div>

### Campos de Slider

``` dart
Field.slider("Rating",
  label: "Rate us",
  validator: FormValidator.minValue(4, message: "Rating must be at least 4"),
  style: FieldStyleSlider(
    min: 0,
    max: 10,
    divisions: 10,
    activeColor: Colors.blue,
    inactiveColor: Colors.grey,
  ),
)
```

Tipo de estilo: `FieldStyleSlider` -- configure `min`, `max`, `divisions`, cores, exibição de valor e mais.

<div id="range-slider-fields"></div>

### Campos de Range Slider

``` dart
Field.rangeSlider("Price Range",
  style: FieldStyleRangeSlider(
    min: 0,
    max: 1000,
    divisions: 20,
    activeColor: Colors.blue,
    inactiveColor: Colors.grey,
  ),
)
```

Retorna um objeto `RangeValues`. Tipo de estilo: `FieldStyleRangeSlider`

<div id="custom-fields"></div>

### Campos Personalizados

Use `Field.custom()` para fornecer seu próprio widget stateful:

``` dart
Field.custom("My Field",
  child: MyCustomFieldWidget(),
)
```

O parâmetro `child` requer um widget que estenda `NyFieldStatefulWidget`. Isso lhe dá controle total sobre a renderização e comportamento do campo.

<div id="widget-fields"></div>

### Campos de Widget

Use `Field.widget()` para incorporar qualquer widget dentro do formulário sem que ele seja um campo de formulário:

``` dart
Field.widget(child: Divider())

Field.widget(child: Text("Section Header", style: TextStyle(fontSize: 18)))
```

Campos de widget não participam da validação ou coleta de dados. Eles são puramente para layout.


<div id="form-collection"></div>

## FormCollection

Campos de picker, radio e chip requerem um `FormCollection` para suas opções. `FormCollection` fornece uma interface unificada para lidar com diferentes formatos de opções.

### Criando um FormCollection

``` dart
// A partir de uma lista de strings (valor e rótulo são iguais)
FormCollection.from(["Red", "Green", "Blue"])

// Mesmo que acima, explícito
FormCollection.fromArray(["Red", "Green", "Blue"])

// A partir de um map (chave = valor, valor = rótulo)
FormCollection.fromMap({
  "us": "United States",
  "ca": "Canada",
})

// A partir de dados estruturados (útil para respostas de API)
FormCollection.fromKeyValue([
  {"value": "en", "label": "English"},
  {"value": "es", "label": "Spanish"},
])
```

`FormCollection.from()` detecta automaticamente o formato dos dados e delega ao construtor apropriado.

### FormOption

Cada opção em um `FormCollection` é um `FormOption` com propriedades `value` e `label`:

``` dart
FormOption option = FormOption(value: "us", label: "United States");
print(option.value); // "us"
print(option.label); // "United States"
```

### Consultando Opções

``` dart
FormCollection options = FormCollection.fromMap({"us": "United States", "ca": "Canada"});

options.getByValue("us");          // FormOption(value: us, label: United States)
options.getLabelByValue("us");     // "United States"
options.containsValue("ca");      // true
options.searchByLabel("can");      // [FormOption(value: ca, label: Canada)]
options.values;                    // ["us", "ca"]
options.labels;                    // ["United States", "Canada"]
```


<div id="form-validation"></div>

## Validação de Formulário

Adicione validação a qualquer campo usando o parâmetro `validator` com `FormValidator`:

``` dart
// Construtor nomeado
Field.email("Email", validator: FormValidator.email())

// Regras encadeadas
Field.text("Username",
  validator: FormValidator()
    .notEmpty()
    .minLength(3)
    .maxLength(20)
)

// Senha com nível de força
Field.password("Password",
  validator: FormValidator.password(strength: 2)
)

// Validação booleana
Field.checkbox("Terms",
  validator: FormValidator.booleanTrue(message: "You must accept the terms")
)

// Validação personalizada inline
Field.number("Age",
  validator: FormValidator.custom(
    message: "Age must be between 18 and 100",
    validate: (data) {
      int? age = int.tryParse(data.toString());
      return age != null && age >= 18 && age <= 100;
    },
  )
)
```

Quando um formulário é enviado, todos os validadores são verificados. Se algum falhar, um erro toast mostra a primeira mensagem de erro e o callback `onFailure` é chamado.

**Veja também:** [Validação](/docs/7.x/validation#validation-rules) para uma lista completa de validadores disponíveis.


<div id="managing-form-data"></div>

## Gerenciando Dados do Formulário

<div id="initial-data"></div>

### Dados Iniciais

Existem duas maneiras de definir dados iniciais em um formulário.

**Opção 1: Sobrescrever o getter `init` na sua classe de formulário**

``` dart
class EditAccountForm extends NyFormWidget {
  EditAccountForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  Function()? get init => () async {
    final user = await api<ApiService>((request) => request.getUserData());

    return {
      "First Name": user?.firstName,
      "Last Name": user?.lastName,
    };
  };

  @override
  fields() => [
    Field.text("First Name"),
    Field.text("Last Name"),
  ];

  static NyFormActions get actions => const NyFormActions('EditAccountForm');
}
```

O getter `init` pode retornar um `Map` síncrono ou um `Future<Map>` assíncrono. As chaves são correspondidas aos nomes dos campos usando normalização snake_case, então `"First Name"` mapeia para um campo com chave `"First Name"`.

#### Usando `define()` no init

Use o helper `define()` quando precisar definir **opções** (ou tanto um valor quanto opções) para um campo no `init`. Isso é útil para campos picker, chip e radio onde as opções vêm de uma API ou outra fonte assíncrona.

``` dart
class CreatePostForm extends NyFormWidget {
  CreatePostForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  Function()? get init => () async {
    final categories = await api<ApiService>((request) => request.getCategories());

    return {
      "Title": "My Post",
      "Category": define(options: categories),
    };
  };

  @override
  fields() => [
    Field.text("Title"),
    Field.picker("Category", options: FormCollection.from([])),
  ];

  static NyFormActions get actions => const NyFormActions('CreatePostForm');
}
```

`define()` aceita dois parâmetros nomeados:

| Parâmetro | Descrição |
|-----------|-------------|
| `value` | O valor inicial para o campo |
| `options` | As opções para campos picker, chip ou radio |

``` dart
// Definir apenas opções (sem valor inicial)
"Category": define(options: categories),

// Definir apenas um valor inicial
"Price": define(value: "100"),

// Definir tanto um valor quanto opções
"Country": define(value: "us", options: countries),

// Valores simples ainda funcionam para campos simples
"Name": "John",
```

Opções passadas para `define()` podem ser uma `List`, `Map` ou `FormCollection`. Elas são automaticamente convertidas para um `FormCollection` quando aplicadas.

**Opção 2: Passar `initialData` para o widget de formulário**

``` dart
EditAccountForm(
  initialData: {
    "first_name": "John",
    "last_name": "Doe",
  },
)
```

<div id="setting-field-values"></div>

### Definindo Valores de Campos

Use `NyFormActions` para definir valores de campos de qualquer lugar:

``` dart
// Definir o valor de um campo
EditAccountForm.actions.updateField("First Name", "Jane");
```

<div id="setting-field-options"></div>

### Definindo Opções de Campos

Atualize opções em campos picker, chip ou radio dinamicamente:

``` dart
EditAccountForm.actions.setOptions("Category", FormCollection.from(["New Option 1", "New Option 2"]));
```

<div id="reading-form-data"></div>

### Lendo Dados do Formulário

Os dados do formulário são acessados através do callback `onSubmit` quando o formulário é enviado, ou através do callback `onChanged` para atualizações em tempo real:

``` dart
EditAccountForm(
  onSubmit: (data) {
    // data é um Map<String, dynamic>
    // {first_name: "Jane", last_name: "Doe", email: "jane@example.com"}
    print(data);
  },
  onChanged: (Field field, dynamic value) {
    print("${field.key} changed to: $value");
  },
)
```

<div id="clearing-data"></div>

### Limpando Dados

``` dart
// Limpar todos os campos
EditAccountForm.actions.clear();

// Limpar um campo específico
EditAccountForm.actions.clearField("First Name");
```


<div id="finding-and-updating-fields"></div>

### Atualizando Campos

``` dart
// Atualizar o valor de um campo
EditAccountForm.actions.updateField("First Name", "Jane");

// Atualizar a UI do formulário
EditAccountForm.actions.refresh();

// Atualizar campos do formulário (re-chama fields())
EditAccountForm.actions.refreshForm();
```


<div id="submit-button"></div>

## Botão de Envio

Passe um `submitButton` e callback `onSubmit` ao construir o formulário:

``` dart
UserInfoForm(
  submitButton: Button.primary(text: "Submit"),
  onSubmit: (data) {
    print(data);
  },
  onFailure: (errors) {
    print(errors.first.rule.getMessage());
  },
)
```

O `submitButton` é automaticamente exibido abaixo dos campos do formulário. Você pode usar qualquer um dos estilos de botão integrados ou um widget personalizado.

Você também pode usar qualquer widget como botão de envio passando-o como `footer`:

``` dart
UserInfoForm(
  onSubmit: (data) {
    print(data);
  },
  footer: ElevatedButton(
    onPressed: () {
      UserInfoForm.actions.submit(
        onSuccess: (data) {
          print(data);
        },
      );
    },
    child: Text("Submit"),
  ),
)
```


<div id="form-layout"></div>

## Layout do Formulário

Coloque campos lado a lado envolvendo-os em uma `List`:

``` dart
@override
fields() => [
  // Campo único (largura total)
  Field.text("Title"),

  // Dois campos em uma linha
  [
    Field.text("First Name"),
    Field.text("Last Name"),
  ],

  // Outro campo único
  Field.textArea("Bio"),

  // Slider e range slider em uma linha
  [
    Field.slider("Rating", style: FieldStyleSlider(min: 0, max: 10)),
    Field.rangeSlider("Budget", style: FieldStyleRangeSlider(min: 0, max: 1000)),
  ],

  // Incorporar um widget que não é campo
  Field.widget(child: Divider()),

  Field.email("Email"),
];
```

Campos em uma `List` são renderizados em um `Row` com larguras `Expanded` iguais. O espaçamento entre campos é controlado pelo parâmetro `crossAxisSpacing` no `NyFormWidget`.


<div id="field-visibility"></div>

## Visibilidade de Campos

Mostre ou oculte campos programaticamente usando os métodos `hide()` e `show()` no `Field`. Você pode acessar campos dentro da sua classe de formulário ou através do callback `onChanged`:

``` dart
// Dentro da sua subclasse NyFormWidget ou callback onChanged
Field nameField = ...;

// Ocultar o campo
nameField.hide();

// Mostrar o campo
nameField.show();
```

Campos ocultos não são renderizados na UI, mas ainda existem na lista de campos do formulário.


<div id="field-styling"></div>

## Estilização de Campos

Cada tipo de campo tem uma subclasse `FieldStyle` correspondente para estilização:

| Tipo de Campo | Classe de Estilo |
|------------|-------------|
| Text, Email, Password, Number, URL, TextArea, PhoneNumber, Currency, Mask, CapitalizeWords, CapitalizeSentences | `FieldStyleTextField` |
| Date, DateTime | `FieldStyleDateTimePicker` |
| Picker | `FieldStylePicker` |
| Checkbox | `FieldStyleCheckbox` |
| Switch Box | `FieldStyleSwitchBox` |
| Radio | `FieldStyleRadio` |
| Chip | `FieldStyleChip` |
| Slider | `FieldStyleSlider` |
| Range Slider | `FieldStyleRangeSlider` |

Passe um objeto de estilo para o parâmetro `style` de qualquer campo:

``` dart
Field.text("Name",
  style: FieldStyleTextField(
    filled: true,
    fillColor: Colors.grey.shade100,
    border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
    contentPadding: EdgeInsets.symmetric(horizontal: 16, vertical: 12),
    prefixIcon: Icon(Icons.person),
  ),
)

Field.slider("Rating",
  style: FieldStyleSlider(
    min: 0,
    max: 10,
    divisions: 10,
    activeColor: Colors.blue,
    showValue: true,
  ),
)

Field.chips("Tags",
  options: FormCollection.from(["Sale", "New", "Featured"]),
  style: FieldStyleChip(
    selectedColor: Colors.blue,
    checkmarkColor: Colors.white,
    spacing: 8.0,
    runSpacing: 8.0,
  ),
)
```


<div id="ny-form-widget-static-methods"></div>

## Métodos Estáticos do NyFormWidget

`NyFormWidget` fornece métodos estáticos para interagir com formulários pelo nome de qualquer lugar no seu app:

| Método | Descrição |
|--------|-------------|
| `NyFormWidget.submit(name, onSuccess:, onFailure:, showToastError:)` | Enviar um formulário pelo nome |
| `NyFormWidget.stateRefresh(name)` | Atualizar o estado da UI do formulário |
| `NyFormWidget.stateSetValue(name, key, value)` | Definir um valor de campo pelo nome do formulário |
| `NyFormWidget.stateSetOptions(name, key, options)` | Definir opções de campo pelo nome do formulário |
| `NyFormWidget.stateClearData(name)` | Limpar todos os campos pelo nome do formulário |
| `NyFormWidget.stateRefreshForm(name)` | Atualizar campos do formulário (re-chama `fields()`) |

``` dart
// Enviar um formulário chamado "LoginForm" de qualquer lugar
NyFormWidget.submit("LoginForm", onSuccess: (data) {
  print(data);
});

// Atualizar um valor de campo remotamente
NyFormWidget.stateSetValue("LoginForm", "Email", "new@email.com");

// Limpar todos os dados do formulário
NyFormWidget.stateClearData("LoginForm");
```

> **Dica:** Prefira usar `NyFormActions` (veja abaixo) em vez de chamar esses métodos estáticos diretamente -- é mais conciso e menos propenso a erros.


<div id="ny-form-widget-constructor-reference"></div>

## Referência do Construtor NyFormWidget

Ao estender `NyFormWidget`, estes são os parâmetros do construtor que você pode passar:

``` dart
LoginForm(
  Key? key,
  double crossAxisSpacing = 10,  // Espaçamento horizontal entre campos em linha
  double mainAxisSpacing = 10,   // Espaçamento vertical entre campos
  Map<String, dynamic>? initialData, // Valores iniciais dos campos
  Function(Field field, dynamic value)? onChanged, // Callback de mudança de campo
  Widget? header,                // Widget acima do formulário
  Widget? submitButton,          // Widget do botão de envio
  Widget? footer,                // Widget abaixo do formulário
  double headerSpacing = 10,     // Espaçamento após o header
  double submitButtonSpacing = 10, // Espaçamento após o botão de envio
  double footerSpacing = 10,     // Espaçamento antes do footer
  LoadingStyle? loadingStyle,    // Estilo do indicador de carregamento
  bool locked = false,           // Torna o formulário somente leitura
  Function(dynamic data)? onSubmit,   // Chamado com dados do formulário em validação bem-sucedida
  Function(dynamic error)? onFailure, // Chamado com erros em validação falha
)
```

O callback `onChanged` recebe o `Field` que mudou e seu novo valor:

``` dart
LoginForm(
  onChanged: (Field field, dynamic value) {
    print("${field.key} changed to: $value");
  },
)
```


<div id="ny-form-actions"></div>

## NyFormActions

`NyFormActions` fornece uma maneira conveniente de interagir com um formulário de qualquer lugar no seu app. Defina-o como um getter estático na sua classe de formulário:

``` dart
class LoginForm extends NyFormWidget {
  LoginForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  fields() => [
    Field.email("Email", validator: FormValidator.email()),
    Field.password("Password", validator: FormValidator.password()),
  ];

  static NyFormActions get actions => const NyFormActions('LoginForm');
}
```

### Ações Disponíveis

| Método | Descrição |
|--------|-------------|
| `actions.updateField(key, value)` | Definir o valor de um campo |
| `actions.clearField(key)` | Limpar um campo específico |
| `actions.clear()` | Limpar todos os campos |
| `actions.refresh()` | Atualizar o estado da UI do formulário |
| `actions.refreshForm()` | Re-chamar `fields()` e reconstruir |
| `actions.setOptions(key, options)` | Definir opções em campos picker/chip/radio |
| `actions.submit(onSuccess:, onFailure:, showToastError:)` | Enviar com validação |

``` dart
// Atualizar o valor de um campo
LoginForm.actions.updateField("Email", "new@email.com");

// Limpar todos os dados do formulário
LoginForm.actions.clear();

// Enviar o formulário
LoginForm.actions.submit(
  onSuccess: (data) {
    print(data);
  },
);
```

### Sobrescritas do NyFormWidget

Métodos que você pode sobrescrever na sua subclasse `NyFormWidget`:

| Sobrescrita | Descrição |
|----------|-------------|
| `fields()` | Definir os campos do formulário (obrigatório) |
| `init` | Fornecer dados iniciais (síncrono ou assíncrono) |
| `onChange(field, data)` | Lidar com mudanças de campo internamente |


<div id="all-field-types-reference"></div>

## Referência de Todos os Tipos de Campo

| Construtor | Parâmetros Principais | Descrição |
|-------------|----------------|-------------|
| `Field.text()` | -- | Entrada de texto padrão |
| `Field.email()` | -- | Entrada de email com tipo de teclado |
| `Field.password()` | `viewable` | Senha com botão de visibilidade opcional |
| `Field.number()` | `decimal` | Entrada numérica, decimal opcional |
| `Field.currency()` | `currency` (obrigatório) | Entrada formatada em moeda |
| `Field.capitalizeWords()` | -- | Entrada de texto em title case |
| `Field.capitalizeSentences()` | -- | Entrada de texto em sentence case |
| `Field.textArea()` | -- | Entrada de texto com múltiplas linhas |
| `Field.phoneNumber()` | -- | Número de telefone formatado automaticamente |
| `Field.url()` | -- | Entrada de URL com tipo de teclado |
| `Field.mask()` | `mask` (obrigatório), `match`, `maskReturnValue` | Entrada de texto com máscara |
| `Field.date()` | -- | Seletor de data |
| `Field.datetime()` | `firstDate`, `lastDate`, `dateFormat`, `initialPickerDateTime` | Seletor de data e hora |
| `Field.checkbox()` | -- | Checkbox booleano |
| `Field.switchBox()` | -- | Toggle switch booleano |
| `Field.picker()` | `options` (`FormCollection` obrigatório) | Seleção única de lista |
| `Field.radio()` | `options` (`FormCollection` obrigatório) | Grupo de botões radio |
| `Field.chips()` | `options` (`FormCollection` obrigatório) | Chips de seleção múltipla |
| `Field.slider()` | -- | Slider de valor único |
| `Field.rangeSlider()` | -- | Slider de intervalo de valores |
| `Field.custom()` | `child` (`NyFieldStatefulWidget` obrigatório) | Widget stateful personalizado |
| `Field.widget()` | `child` (`Widget` obrigatório) | Incorporar qualquer widget (não-campo) |
