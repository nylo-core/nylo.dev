# InputField

---

<a name="section-1"></a>
- [Introduccion](#introduction "Introduccion")
- [Uso basico](#basic-usage "Uso basico")
- [Validacion](#validation "Validacion")
- Variantes
    - [InputField.password](#password "InputField.password")
    - [InputField.emailAddress](#email-address "InputField.emailAddress")
    - [InputField.capitalizeWords](#capitalize-words "InputField.capitalizeWords")
- [Enmascaramiento de entrada](#masking "Enmascaramiento de entrada")
- [Encabezado y pie de pagina](#header-footer "Encabezado y pie de pagina")
- [Entrada limpiable](#clearable "Entrada limpiable")
- [Gestion de estado](#state-management "Gestion de estado")
- [Parametros](#parameters "Parametros")


<div id="introduction"></div>

## Introduccion

El widget **InputField** es el campo de texto mejorado de {{ config('app.name') }} con soporte integrado para:

- Validacion con mensajes de error personalizables
- Alternador de visibilidad de contrasena
- Enmascaramiento de entrada (numeros de telefono, tarjetas de credito, etc.)
- Widgets de encabezado y pie de pagina
- Entrada limpiable
- Integracion con gestion de estado
- Datos ficticios para desarrollo

<div id="basic-usage"></div>

## Uso basico

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

## Validacion

Usa el parametro `formValidator` para agregar reglas de validacion:

``` dart
InputField(
  controller: _controller,
  labelText: "Email",
  formValidator: FormValidator.rule("email|not_empty"),
  validateOnFocusChange: true,
)
```

El campo se validara cuando el usuario mueva el foco fuera de el.

### Manejador de validacion personalizado

Maneja errores de validacion programaticamente:

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

Consulta todas las reglas de validacion disponibles en la documentacion de [Validacion](/docs/7.x/validation).

<div id="password"></div>

## InputField.password

Un campo de contrasena preconfigurado con texto oculto y alternador de visibilidad:

``` dart
final TextEditingController _passwordController = TextEditingController();

InputField.password(
  controller: _passwordController,
  labelText: "Password",
  formValidator: FormValidator.rule("not_empty|min:8"),
)
```

### Personalizar la visibilidad de contrasena

``` dart
InputField.password(
  controller: _passwordController,
  passwordVisible: true, // Show/hide toggle icon
  passwordViewable: true, // Allow user to toggle visibility
)
```

<div id="email-address"></div>

## InputField.emailAddress

Un campo de correo electronico preconfigurado con teclado de email y enfoque automatico:

``` dart
final TextEditingController _emailController = TextEditingController();

InputField.emailAddress(
  controller: _emailController,
  formValidator: FormValidator.rule("email|not_empty"),
)
```

<div id="capitalize-words"></div>

## InputField.capitalizeWords

Capitaliza automaticamente la primera letra de cada palabra:

``` dart
final TextEditingController _nameController = TextEditingController();

InputField.capitalizeWords(
  controller: _nameController,
  labelText: "Full Name",
)
```

<div id="masking"></div>

## Enmascaramiento de entrada

Aplica mascaras de entrada para datos formateados como numeros de telefono o tarjetas de credito:

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

| Parametro | Descripcion |
|-----------|-------------|
| `mask` | El patron de mascara usando `#` como marcador de posicion |
| `maskMatch` | Patron regex para caracteres de entrada validos |
| `maskedReturnValue` | Si es true, devuelve el valor formateado; si es false, devuelve la entrada sin formato |

<div id="header-footer"></div>

## Encabezado y pie de pagina

Agrega widgets arriba o abajo del campo de entrada:

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

## Entrada limpiable

Agrega un boton de limpiar para vaciar rapidamente el campo:

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

## Gestion de estado

Asigna un nombre de estado a tu campo de entrada para controlarlo programaticamente:

``` dart
InputField(
  controller: _controller,
  labelText: "Username",
  stateName: "username_field",
)
```

### Acciones de estado

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

### Parametros comunes

| Parametro | Tipo | Predeterminado | Descripcion |
|-----------|------|---------|-------------|
| `controller` | `TextEditingController` | requerido | Controla el texto que se esta editando |
| `labelText` | `String?` | - | Etiqueta mostrada sobre el campo |
| `hintText` | `String?` | - | Texto de marcador de posicion |
| `formValidator` | `FormValidator?` | - | Reglas de validacion |
| `validateOnFocusChange` | `bool` | `true` | Validar cuando cambia el foco |
| `obscureText` | `bool` | `false` | Ocultar entrada (para contrasenas) |
| `keyboardType` | `TextInputType` | `text` | Tipo de teclado |
| `autoFocus` | `bool` | `false` | Enfocar automaticamente al construir |
| `readOnly` | `bool` | `false` | Hacer el campo de solo lectura |
| `enabled` | `bool?` | - | Habilitar/deshabilitar el campo |
| `maxLines` | `int?` | `1` | Lineas maximas |
| `maxLength` | `int?` | - | Caracteres maximos |

### Parametros de estilo

| Parametro | Tipo | Descripcion |
|-----------|------|-------------|
| `backgroundColor` | `Color?` | Color de fondo del campo |
| `borderRadius` | `BorderRadius?` | Radio del borde |
| `border` | `InputBorder?` | Borde por defecto |
| `focusedBorder` | `InputBorder?` | Borde cuando esta enfocado |
| `enabledBorder` | `InputBorder?` | Borde cuando esta habilitado |
| `contentPadding` | `EdgeInsetsGeometry?` | Relleno interno |
| `style` | `TextStyle?` | Estilo del texto |
| `labelStyle` | `TextStyle?` | Estilo del texto de la etiqueta |
| `hintStyle` | `TextStyle?` | Estilo del texto de sugerencia |
| `prefixIcon` | `Widget?` | Icono antes de la entrada |

### Parametros de enmascaramiento

| Parametro | Tipo | Descripcion |
|-----------|------|-------------|
| `mask` | `String?` | Patron de mascara (ej. "###-####") |
| `maskMatch` | `String?` | Regex para caracteres validos |
| `maskedReturnValue` | `bool?` | Devolver valor enmascarado o sin formato |

### Parametros de funcionalidad

| Parametro | Tipo | Descripcion |
|-----------|------|-------------|
| `header` | `Widget?` | Widget sobre el campo |
| `footer` | `Widget?` | Widget debajo del campo |
| `clearable` | `bool?` | Mostrar boton de limpiar |
| `clearIcon` | `Widget?` | Icono de limpiar personalizado |
| `passwordVisible` | `bool?` | Mostrar alternador de contrasena |
| `passwordViewable` | `bool?` | Permitir alternador de visibilidad de contrasena |
| `dummyData` | `String?` | Datos ficticios para desarrollo |
| `stateName` | `String?` | Nombre para gestion de estado |
| `onChanged` | `Function(String)?` | Se llama cuando el valor cambia |
