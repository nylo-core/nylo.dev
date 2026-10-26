# Validation

---

<a name="section-1"></a>
- [Introdução](#introduction "Introdução à validação")
- Básico
  - [Validando Dados com check()](#validating-data "Validando dados com check")
  - [Validando em um Controller](#validating-in-a-controller "Validando em um controller")
  - [Resultados da Validação](#validation-results "Resultados da validação")
- FormValidator
  - [Usando FormValidator](#using-form-validator "Usando FormValidator")
  - [Construtores Nomeados do FormValidator](#form-validator-named-constructors "Construtores nomeados do FormValidator")
  - [Mensagens de Validação Traduzidas](#translated-validation-messages "Mensagens de validação traduzidas")
  - [Encadeando Regras de Validação](#chaining-validation-rules "Encadeando regras de validação")
  - [Campos Nullable](#nullable-fields "Campos Nullable")
  - [Validação Personalizada](#custom-validation "Validação personalizada")
  - [Usando FormValidator com Campos](#form-validator-with-fields "Usando FormValidator com campos")
- [Regras de Validação Disponíveis](#validation-rules "Regras de validação")
- [Criando Regras de Validação Personalizadas](#creating-custom-validation-rules "Criando regras de validação personalizadas")

<div id="introduction"></div>

## Introdução

{{ config('app.name') }} v7 fornece um sistema de validação construído em torno da classe `FormValidator`. Você pode validar dados dentro de páginas usando o método `check()`, ou usar `FormValidator` diretamente para validação standalone e no nível de campo.

``` dart
// Validar dados em um NyPage/NyState usando check()
check((validate) {
  validate.that('user@example.com').email();
  validate.that('Anthony')
              .capitalized()
              .maxLength(50);
}, onSuccess: () {
  print("All validations passed!");
});

// FormValidator com campos de formulário
Field.text("Email", validator: FormValidator.email())
```

<div id="validating-data"></div>

## Validando Dados com check()

Dentro de qualquer `NyPage`, use o método `check()` para validar dados. Ele aceita um callback que recebe uma lista de validadores. Use `.that()` para adicionar dados e encadear regras:

``` dart
class _RegisterPageState extends NyPage<RegisterPage> {

  void handleForm() {
    check((validate) {
      validate.that(_emailController.text, label: "Email").email();
      validate.that(_passwordController.text, label: "Password")
          .notEmpty()
          .password(strength: 2);
    }, onSuccess: () {
      // Todas as validações passaram
      _submitForm();
    }, onValidationError: (FormValidationResponseBag bag) {
      // Validação falhou
      print(bag.firstErrorMessage);
    });
  }
}
```

### Como check() Funciona

1. `check()` cria uma `List<FormValidator>` vazia
2. Seu callback usa `.that(data)` para adicionar um novo `FormValidator` com dados à lista
3. Cada `.that()` retorna um `FormValidator` no qual você pode encadear regras
4. Após o callback, cada validador na lista é verificado
5. Os resultados são coletados em um `FormValidationResponseBag`

### Validando Múltiplos Campos

``` dart
check((validate) {
  validate.that(_nameController.text, label: "Name").notEmpty().capitalized();
  validate.that(_emailController.text, label: "Email").email();
  validate.that(_phoneController.text, label: "Phone").phoneNumberUs();
  validate.that(_ageController.text, label: "Age").numeric().minValue(18);
}, onSuccess: () {
  _submitForm();
});
```

O parâmetro opcional `label` define um nome legível usado nas mensagens de erro (ex.: "The Email must be a valid email address.").

<div id="validating-in-a-controller"></div>

### Validando em um Controller

<x-doc-strip label="Requer" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.36.0+" />

Um `NyController` tem o mesmo `check()`, então pode validar seus próprios campos sem o estado da sua página:

``` dart
class RegisterController extends NyController {
  TextEditingController email = TextEditingController();
  TextEditingController password = TextEditingController();

  void register() {
    check((validate) {
      validate.that(email.text, label: "Email").email();
      validate.that(password.text, label: "Password").notEmpty();
    }, onSuccess: submit, onValidationError: (FormValidationResponseBag bag) {
      showToastDanger(description: bag.firstErrorMessage ?? '');
    });
  }

  void submit() {
    // Todas as validações passaram
  }
}
```

`check()` retorna o `FormValidationResponseBag`, então o controller pode guardar o resultado.

<div id="validation-results"></div>

## Resultados da Validação

O método `check()` retorna um `FormValidationResponseBag` (uma `List<FormValidationResult>`), que você também pode inspecionar diretamente:

``` dart
FormValidationResponseBag bag = check((validate) {
  validate.that(_emailController.text, label: "Email").email();
  validate.that(_passwordController.text, label: "Password")
      .password(strength: 1);
});

if (bag.isValid) {
  print("All valid!");
} else {
  // Obter a primeira mensagem de erro
  String? error = bag.firstErrorMessage;
  print(error);

  // Obter todos os resultados com falha
  List<FormValidationResult> errors = bag.validationErrors;

  // Obter todos os resultados bem-sucedidos
  List<FormValidationResult> successes = bag.validationSuccess;
}
```

### FormValidationResult

Cada `FormValidationResult` representa o resultado da validação de um único valor:

``` dart
FormValidator validator = FormValidator(data: "test@email.com")
    .email()
    .maxLength(50);

FormValidationResult result = validator.check();

if (result.isValid) {
  print("Valid!");
} else {
  // Primeira mensagem de erro
  String? message = result.getFirstErrorMessage();

  // Todas as mensagens de erro
  List<String> messages = result.errorMessages();

  // Respostas de erro
  List<FormValidationError> errors = result.errorResponses;
}
```

`firstError` retorna o `FormValidationError` da primeira regra que falhou, ou `null` quando todas as regras passaram. Ele não monta a lista completa de erros, então é barato chamá-lo a cada tecla digitada:

``` dart
FormValidationError? error = result.firstError;
```

<div id="using-form-validator"></div>

## Usando FormValidator

`FormValidator` pode ser usado standalone ou com campos de formulário.

### Uso Standalone

``` dart
// Usando um construtor nomeado
FormValidator validator = FormValidator.email();
FormValidationResult result = validator.check("user@example.com");

if (result.isValid) {
  print("Email is valid");
} else {
  String? errorMessage = result.getFirstErrorMessage();
  print("Error: $errorMessage");
}
```

### Com Dados no Construtor

``` dart
FormValidator validator = FormValidator(data: "user@example.com", attribute: "Email")
    .email()
    .maxLength(50);

FormValidationResult result = validator.check();
print(result.isValid); // true
```

<div id="form-validator-named-constructors"></div>

## Construtores Nomeados do FormValidator

{{ config('app.name') }} v7 fornece construtores nomeados para validações comuns:

``` dart
// Email validation
FormValidator.email(message: "Please enter a valid email")

// Password validation (strength 1 or 2)
FormValidator.password(strength: 1)
FormValidator.password(strength: 2, message: "Password too weak")

// Phone numbers
FormValidator.phoneNumberUk()
FormValidator.phoneNumberUs()

// URL validation
FormValidator.url()

// Length constraints
FormValidator.minLength(5, message: "Too short")
FormValidator.maxLength(100, message: "Too long")

// Value constraints
FormValidator.minValue(18, message: "Must be 18+")
FormValidator.maxValue(100)

// Size constraints (for lists/strings)
FormValidator.minSize(2, message: "Select at least 2")
FormValidator.maxSize(5)

// Not empty
FormValidator.notEmpty(message: "This field is required")

// Contains values
FormValidator.contains(['option1', 'option2'])

// Starts/ends with
FormValidator.beginsWith("https://")
FormValidator.endsWith(".com")

// Boolean checks
FormValidator.booleanTrue(message: "Must accept terms")
FormValidator.booleanFalse()

// Numeric
FormValidator.numeric()

// Date validations
FormValidator.date()
FormValidator.dateInPast()
FormValidator.dateInFuture()
FormValidator.dateAgeIsOlder(18, message: "Must be 18+")
FormValidator.dateAgeIsYounger(65)

// Text case
FormValidator.uppercase()
FormValidator.lowercase()
FormValidator.capitalized()

// Location formats
FormValidator.zipcodeUs()
FormValidator.postcodeUk()

// Regex pattern
FormValidator.regex(r'^[A-Z]{3}\d{4}$', message: "Invalid format")

// Custom validation
FormValidator.custom(
  message: "Invalid value",
  validate: (data) => data != null,
)
```

<div id="translated-validation-messages"></div>

## Mensagens de Validação Traduzidas

<x-doc-strip label="Requer" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Todo construtor nomeado acima funciona sem passar `message:` — uma regra sem mensagem personalizada resolve seu padrão através de uma chave de tradução `nylo.validation.*` em vez de uma string fixa:

``` dart
FormValidator validator = FormValidator(data: "ab", attribute: "Username")
    .minLength(8);

FormValidationResult result = validator.check();
print(result.getFirstErrorMessage());
// "The Username must be at least 8 characters long." — vem de nylo.validation.min_length
```

Sobrescreva o padrão de qualquer regra da mesma forma que faria com [os outros textos integrados do Nylo](/docs/{{ $version }}/localization#built-in-translations), adicionando a chave correspondente sob `nylo.validation` no seu próprio arquivo de idioma:

``` json
// lang/en.json
{
  "nylo": {
    "validation": {
      "min_length": "@{{attribute}} needs at least @{{minLength}} characters."
    }
  }
}
```

Uma regra que recebe seu próprio valor -- `minLength`, `maxLength`, `minValue`, `dateAgeIsOlder`, e outras -- o expõe para a mensagem traduzida como um `@{{placeholder}}` ao lado de `@{{attribute}}`, através de `FormRule.messageArguments`. O próprio `@{{attribute}}` é preenchido a partir do nome do campo (ou do `attribute:` que você passou), traduzido da mesma forma.

`FormValidationResponse` (e suas subclasses `FormValidationError`/`FormValidationSuccess`, como vistas no `onFailure` de um `FormValidator` ou `Field`) carrega o nome do campo em `attribute` ao lado da `message` traduzida:

``` dart
onFailure: (errors) {
  for (final error in errors) {
    print('${error.attribute}: ${error.message}');
  }
}
```

<div id="chaining-validation-rules"></div>

## Encadeando Regras de Validação

Encadeie múltiplas regras de forma fluente usando métodos de instância. Cada método retorna o `FormValidator`, permitindo que você construa regras:

``` dart
FormValidator validator = FormValidator()
    .notEmpty()
    .email()
    .maxLength(50);

FormValidationResult result = validator.check("user@example.com");

if (!result.isValid) {
  List<String> errors = result.errorMessages();
  print(errors);
}
```

Todos os construtores nomeados têm métodos de instância encadeáveis correspondentes:

``` dart
FormValidator()
    .notEmpty(message: "Required")
    .email(message: "Invalid email")
    .minLength(5, message: "Too short")
    .maxLength(100, message: "Too long")
    .beginsWith("user", message: "Must start with 'user'")
    .lowercase(message: "Must be lowercase")
```

<div id="nullable-fields"></div>

## Campos Nullable

Use `.nullable()` para marcar um validador como opcional. Quando nullable, um valor nulo ou vazio passa automaticamente na validação — todas as outras regras só são aplicadas se o valor estiver presente.

``` dart
// Nickname é opcional, mas se fornecido deve ter entre 3 e 20 caracteres
Field.text(
  "Nickname",
  validator: FormValidator()
      .minLength(3)
      .maxLength(20)
      .nullable(),
)
```

Sem `.nullable()`, um campo vazio falharia na regra `minLength`. Com `.nullable()`, deixar o campo em branco passa na validação.

Isso é útil para campos de perfil opcionais, informações de contato secundárias ou qualquer campo que só é validado quando o usuário o preenche. Consulte a [documentação de Forms](/docs/{{ $version }}/forms) para saber como `nullable()` se integra com os widgets `Field`.

<div id="custom-validation"></div>

## Validação Personalizada

### Regra Personalizada (Inline)

Use `.custom()` para adicionar lógica de validação inline:

``` dart
FormValidator.custom(
  message: "Username already taken",
  validate: (data) {
    // Retornar true se válido, false se inválido
    return !_takenUsernames.contains(data);
  },
)
```

Ou encadeie com outras regras:

``` dart
FormValidator()
    .notEmpty()
    .custom(
      message: "Must start with a letter",
      validate: (data) => RegExp(r'^[a-zA-Z]').hasMatch(data.toString()),
    )
```

### Validação de Igualdade

Verifique se um valor corresponde a outro:

``` dart
FormValidator()
    .notEmpty()
    .equals(
      _passwordController.text,
      message: "Passwords must match",
    )
```

<div id="form-validator-with-fields"></div>

## Usando FormValidator com Campos

`FormValidator` integra-se com widgets `Field` em formulários. Passe um validador para o parâmetro `validator`:

``` dart
class RegisterForm extends NyFormData {
  RegisterForm({String? name}) : super(name ?? "register");

  @override
  fields() => [
        Field.text(
          "Name",
          autofocus: true,
          validator: FormValidator.notEmpty(),
        ),
        Field.email("Email", validator: FormValidator.email()),
        Field.password(
          "Password",
          validator: FormValidator.password(strength: 1),
        ),
      ];
}
```

Você também pode usar validadores encadeados com campos:

``` dart
Field.text(
  "Username",
  validator: FormValidator()
      .notEmpty(message: "Username is required")
      .minLength(3, message: "At least 3 characters")
      .maxLength(20, message: "At most 20 characters"),
)

Field.slider(
  "Rating",
  validator: FormValidator.minValue(4, message: "Rating must be at least 4"),
)
```

<div id="validation-rules"></div>

## Regras de Validação Disponíveis

Todas as regras disponíveis para `FormValidator`, tanto como construtores nomeados quanto como métodos encadeáveis:

| Regra | Construtor Nomeado | Método Encadeável | Descrição |
|------|-------------------|------------------|-------------|
| Email | `FormValidator.email()` | `.email()` | Valida formato de email |
| Password | `FormValidator.password(strength: 1)` | `.password(strength: 1)` | Força 1: 8+ caracteres, 1 maiúscula, 1 dígito. Força 2: + 1 caractere especial |
| Not Empty | `FormValidator.notEmpty()` | `.notEmpty()` | Não pode estar vazio |
| Min Length | `FormValidator.minLength(5)` | `.minLength(5)` | Comprimento mínimo da string |
| Max Length | `FormValidator.maxLength(100)` | `.maxLength(100)` | Comprimento máximo da string |
| Min Value | `FormValidator.minValue(18)` | `.minValue(18)` | Valor numérico mínimo (também funciona com comprimento de string, lista, map) |
| Max Value | `FormValidator.maxValue(100)` | `.maxValue(100)` | Valor numérico máximo |
| Min Size | `FormValidator.minSize(2)` | `.minSize(2)` | Tamanho mínimo para listas/strings |
| Max Size | `FormValidator.maxSize(5)` | `.maxSize(5)` | Tamanho máximo para listas/strings |
| Contains | `FormValidator.contains(['a', 'b'])` | `.contains(['a', 'b'])` | O valor deve conter um dos valores fornecidos |
| Begins With | `FormValidator.beginsWith("https://")` | `.beginsWith("https://")` | A string deve começar com o prefixo |
| Ends With | `FormValidator.endsWith(".com")` | `.endsWith(".com")` | A string deve terminar com o sufixo |
| URL | `FormValidator.url()` | `.url()` | Valida formato de URL |
| Numeric | `FormValidator.numeric()` | `.numeric()` | Deve ser um número |
| Boolean True | `FormValidator.booleanTrue()` | `.booleanTrue()` | Deve ser `true` |
| Boolean False | `FormValidator.booleanFalse()` | `.booleanFalse()` | Deve ser `false` |
| Date | `FormValidator.date()` | `.date()` | Deve ser uma data válida |
| Date In Past | `FormValidator.dateInPast()` | `.dateInPast()` | A data deve estar no passado |
| Date In Future | `FormValidator.dateInFuture()` | `.dateInFuture()` | A data deve estar no futuro |
| Age Is Older | `FormValidator.dateAgeIsOlder(18)` | `.dateAgeIsOlder(18)` | A idade deve ser maior que N |
| Age Is Younger | `FormValidator.dateAgeIsYounger(65)` | `.dateAgeIsYounger(65)` | A idade deve ser menor que N |
| Capitalized | `FormValidator.capitalized()` | `.capitalized()` | A primeira letra deve ser maiúscula |
| Lowercase | `FormValidator.lowercase()` | `.lowercase()` | Todos os caracteres devem ser minúsculos |
| Uppercase | `FormValidator.uppercase()` | `.uppercase()` | Todos os caracteres devem ser maiúsculos |
| Phone US | `FormValidator.phoneNumberUs()` | `.phoneNumberUs()` | Formato de telefone dos EUA |
| Phone UK | `FormValidator.phoneNumberUk()` | `.phoneNumberUk()` | Formato de telefone do Reino Unido |
| Zipcode US | `FormValidator.zipcodeUs()` | `.zipcodeUs()` | Formato de CEP dos EUA |
| Postcode UK | `FormValidator.postcodeUk()` | `.postcodeUk()` | Formato de código postal do Reino Unido |
| Regex | `FormValidator.regex(r'pattern')` | `.regex(r'pattern')` | Deve corresponder ao padrão regex |
| Equals | — | `.equals(otherValue)` | Deve ser igual a outro valor |
| Custom | `FormValidator.custom(validate: fn)` | `.custom(validate: fn)` | Função de validação personalizada |
| Nullable | — | `.nullable()` | Valores nulos ou vazios passam automaticamente; as regras só se aplicam quando um valor está presente |

Todas as regras aceitam um parâmetro opcional `message` para personalizar a mensagem de erro.

<div id="creating-custom-validation-rules"></div>

## Criando Regras de Validação Personalizadas

Para criar uma regra de validação reutilizável, estenda a classe `FormRule`:

``` dart
class FormRuleUsername extends FormRule {
  @override
  String? rule = "username";

  @override
  String? message = "The @{{attribute}} must be a valid username.";

  FormRuleUsername({String? message}) {
    if (message != null) {
      this.message = message;
    }
  }

  @override
  bool validate(data) {
    if (data is! String) return false;
    // Username: alfanumérico, underscores, 3-20 caracteres
    return RegExp(r'^[a-zA-Z0-9_]{3,20}$').hasMatch(data);
  }
}
```

Use `@{{attribute}}` como placeholder na `message` — ele será substituído pelo label do campo em tempo de execução.

### Usando uma FormRule Personalizada

Adicione sua regra personalizada a um `FormValidator` usando `FormValidator.rule()`:

``` dart
FormValidator validator = FormValidator.rule([
  FormRuleNotEmpty(),
  FormRuleUsername(),
]);

FormValidationResult result = validator.check("my_username");
```

Ou use o método `.custom()` para regras únicas sem criar uma classe:

``` dart
FormValidator()
    .notEmpty()
    .custom(
      message: "Username must start with a letter",
      validate: (data) => RegExp(r'^[a-zA-Z]').hasMatch(data.toString()),
    )
```
