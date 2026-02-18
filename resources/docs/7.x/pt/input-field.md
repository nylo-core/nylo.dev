# InputField

---

<a name="section-1"></a>
- [Introducao](#introduction "Introducao")
- [Uso Basico](#basic-usage "Uso Basico")
- [Validacao](#validation "Validacao")
- Variantes
    - [InputField.password](#password "InputField.password")
    - [InputField.emailAddress](#email-address "InputField.emailAddress")
    - [InputField.capitalizeWords](#capitalize-words "InputField.capitalizeWords")
- [Mascaramento de Entrada](#masking "Mascaramento de Entrada")
- [Cabecalho e Rodape](#header-footer "Cabecalho e Rodape")
- [Entrada Limpavel](#clearable "Entrada Limpavel")
- [Gerenciamento de Estado](#state-management "Gerenciamento de Estado")
- [Parametros](#parameters "Parametros")


<div id="introduction"></div>

## Introducao

O widget **InputField** e o campo de texto aprimorado do {{ config('app.name') }} com suporte integrado para:

- Validacao com mensagens de erro personalizaveis
- Alternancia de visibilidade de senha
- Mascaramento de entrada (numeros de telefone, cartoes de credito, etc.)
- Widgets de cabecalho e rodape
- Entrada limpavel
- Integracao com gerenciamento de estado
- Dados fictícios para desenvolvimento

<div id="basic-usage"></div>

## Uso Basico

``` dart
final TextEditingController _controller = TextEditingController();

@override
Widget build(BuildContext context) {
  return Scaffold(
    body: SafeArea(
      child: Padding(
        padding: EdgeInsets.all(16),
        child: InputField(
          controller: _controller,
          labelText: "Username",
          hintText: "Enter your username",
        ),
      ),
    ),
  );
}
```

<div id="validation"></div>

## Validacao

Use o parametro `formValidator` para adicionar regras de validacao:

``` dart
InputField(
  controller: _controller,
  labelText: "Email",
  formValidator: FormValidator.rule("email|not_empty"),
  validateOnFocusChange: true,
)
```

O campo sera validado quando o usuario mover o foco para fora dele.

### Handler de Validacao Personalizado

Trate erros de validacao programaticamente:

``` dart
InputField(
  controller: _controller,
  labelText: "Username",
  formValidator: FormValidator.rule("not_empty|min:3"),
  handleValidationError: (FormValidationResult result) {
    if (!result.isValid) {
      print("Error: ${result.getFirstErrorMessage()}");
    }
  },
)
```

Veja todas as regras de validacao disponiveis na documentacao de [Validacao](/docs/7.x/validation).

<div id="password"></div>

## InputField.password

Um campo de senha pre-configurado com texto oculto e alternancia de visibilidade:

``` dart
final TextEditingController _passwordController = TextEditingController();

InputField.password(
  controller: _passwordController,
  labelText: "Password",
  formValidator: FormValidator.rule("not_empty|min:8"),
)
```

### Personalizando a Visibilidade da Senha

``` dart
InputField.password(
  controller: _passwordController,
  passwordVisible: true, // Show/hide toggle icon
  passwordViewable: true, // Allow user to toggle visibility
)
```

<div id="email-address"></div>

## InputField.emailAddress

Um campo de email pre-configurado com teclado de email e autofoco:

``` dart
final TextEditingController _emailController = TextEditingController();

InputField.emailAddress(
  controller: _emailController,
  formValidator: FormValidator.rule("email|not_empty"),
)
```

<div id="capitalize-words"></div>

## InputField.capitalizeWords

Capitaliza automaticamente a primeira letra de cada palavra:

``` dart
final TextEditingController _nameController = TextEditingController();

InputField.capitalizeWords(
  controller: _nameController,
  labelText: "Full Name",
)
```

<div id="masking"></div>

## Mascaramento de Entrada

Aplique mascaras de entrada para dados formatados como numeros de telefone ou cartoes de credito:

``` dart
// Phone number mask
InputField(
  controller: _phoneController,
  labelText: "Phone Number",
  mask: "(###) ###-####",
  maskMatch: r'[0-9]',
  maskedReturnValue: false, // Returns unmasked value: 1234567890
)

// Credit card mask
InputField(
  controller: _cardController,
  labelText: "Card Number",
  mask: "#### #### #### ####",
  maskMatch: r'[0-9]',
  maskedReturnValue: true, // Returns masked value: 1234 5678 9012 3456
)
```

| Parametro | Descricao |
|-----------|-----------|
| `mask` | O padrao de mascara usando `#` como placeholder |
| `maskMatch` | Padrao regex para caracteres de entrada validos |
| `maskedReturnValue` | Se true, retorna o valor formatado; se false, retorna a entrada bruta |

<div id="header-footer"></div>

## Cabecalho e Rodape

Adicione widgets acima ou abaixo do campo de entrada:

``` dart
InputField(
  controller: _controller,
  labelText: "Bio",
  header: Text(
    "Tell us about yourself",
    style: TextStyle(fontWeight: FontWeight.bold),
  ),
  footer: Text(
    "Max 200 characters",
    style: TextStyle(color: Colors.grey, fontSize: 12),
  ),
  maxLength: 200,
)
```

<div id="clearable"></div>

## Entrada Limpavel

Adicione um botao de limpar para esvaziar rapidamente o campo:

``` dart
InputField(
  controller: _searchController,
  labelText: "Search",
  clearable: true,
  clearIcon: Icon(Icons.close, size: 20), // Custom clear icon
  onChanged: (value) {
    // Handle search
  },
)
```

<div id="state-management"></div>

## Gerenciamento de Estado

Atribua um nome de estado ao seu campo de entrada para controla-lo programaticamente:

``` dart
InputField(
  controller: _controller,
  labelText: "Username",
  stateName: "username_field",
)
```

### Acoes de Estado

``` dart
// Clear the field
InputField.stateActions("username_field").clear();

// Set a value
updateState("username_field", data: {
  "action": "setValue",
  "value": "new_value"
});
```

<div id="parameters"></div>

## Parametros

### Parametros Comuns

| Parametro | Tipo | Padrao | Descricao |
|-----------|------|--------|-----------|
| `controller` | `TextEditingController` | obrigatorio | Controla o texto sendo editado |
| `labelText` | `String?` | - | Label exibido acima do campo |
| `hintText` | `String?` | - | Texto placeholder |
| `formValidator` | `FormValidator?` | - | Regras de validacao |
| `validateOnFocusChange` | `bool` | `true` | Validar quando o foco mudar |
| `obscureText` | `bool` | `false` | Ocultar entrada (para senhas) |
| `keyboardType` | `TextInputType` | `text` | Tipo de teclado |
| `autoFocus` | `bool` | `false` | Auto-foco ao construir |
| `readOnly` | `bool` | `false` | Tornar campo somente leitura |
| `enabled` | `bool?` | - | Habilitar/desabilitar o campo |
| `maxLines` | `int?` | `1` | Maximo de linhas |
| `maxLength` | `int?` | - | Maximo de caracteres |

### Parametros de Estilo

| Parametro | Tipo | Descricao |
|-----------|------|-----------|
| `backgroundColor` | `Color?` | Cor de fundo do campo |
| `borderRadius` | `BorderRadius?` | Raio da borda |
| `border` | `InputBorder?` | Borda padrao |
| `focusedBorder` | `InputBorder?` | Borda quando focado |
| `enabledBorder` | `InputBorder?` | Borda quando habilitado |
| `contentPadding` | `EdgeInsetsGeometry?` | Preenchimento interno |
| `style` | `TextStyle?` | Estilo do texto |
| `labelStyle` | `TextStyle?` | Estilo do texto do label |
| `hintStyle` | `TextStyle?` | Estilo do texto de dica |
| `prefixIcon` | `Widget?` | Icone antes da entrada |

### Parametros de Mascara

| Parametro | Tipo | Descricao |
|-----------|------|-----------|
| `mask` | `String?` | Padrao de mascara (ex: "###-####") |
| `maskMatch` | `String?` | Regex para caracteres validos |
| `maskedReturnValue` | `bool?` | Retornar valor mascarado ou bruto |

### Parametros de Funcionalidades

| Parametro | Tipo | Descricao |
|-----------|------|-----------|
| `header` | `Widget?` | Widget acima do campo |
| `footer` | `Widget?` | Widget abaixo do campo |
| `clearable` | `bool?` | Mostrar botao de limpar |
| `clearIcon` | `Widget?` | Icone de limpar personalizado |
| `passwordVisible` | `bool?` | Mostrar alternancia de senha |
| `passwordViewable` | `bool?` | Permitir alternancia de visibilidade de senha |
| `dummyData` | `String?` | Dados ficticios para desenvolvimento |
| `stateName` | `String?` | Nome para gerenciamento de estado |
| `onChanged` | `Function(String)?` | Chamado quando o valor muda |
