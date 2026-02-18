# Forms

---

<a name="section-1"></a>
- [Introducción](#introduction "Introducción a formularios")
- Primeros pasos
  - [Crear un formulario](#creating-forms "Crear formularios")
  - [Mostrar un formulario](#displaying-a-form "Mostrar un formulario")
  - [Enviar un formulario](#submitting-a-form "Enviar un formulario")
- Tipos de campo
  - [Campos de texto](#text-fields "Campos de texto")
  - [Campos numéricos](#number-fields "Campos numéricos")
  - [Campos de contraseña](#password-fields "Campos de contraseña")
  - [Campos de correo electrónico](#email-fields "Campos de correo electrónico")
  - [Campos de URL](#url-fields "Campos de URL")
  - [Campos de área de texto](#text-area-fields "Campos de área de texto")
  - [Campos de número de teléfono](#phone-number-fields "Campos de número de teléfono")
  - [Capitalizar palabras](#capitalize-words-fields "Campos de capitalizar palabras")
  - [Capitalizar oraciones](#capitalize-sentences-fields "Campos de capitalizar oraciones")
  - [Campos de fecha](#date-fields "Campos de fecha")
  - [Campos de fecha y hora](#datetime-fields "Campos de fecha y hora")
  - [Campos de entrada con máscara](#masked-input-fields "Campos de entrada con máscara")
  - [Campos de moneda](#currency-fields "Campos de moneda")
  - [Campos de casilla de verificación](#checkbox-fields "Campos de casilla de verificación")
  - [Campos de interruptor](#switch-box-fields "Campos de interruptor")
  - [Campos de selector](#picker-fields "Campos de selector")
  - [Campos de radio](#radio-fields "Campos de radio")
  - [Campos de chip](#chip-fields "Campos de chip")
  - [Campos de deslizador](#slider-fields "Campos de deslizador")
  - [Campos de deslizador de rango](#range-slider-fields "Campos de deslizador de rango")
  - [Campos personalizados](#custom-fields "Campos personalizados")
  - [Campos de widget](#widget-fields "Campos de widget")
- [FormCollection](#form-collection "FormCollection")
- [Validación de formularios](#form-validation "Validación de formularios")
- [Gestión de datos del formulario](#managing-form-data "Gestión de datos del formulario")
  - [Datos iniciales](#initial-data "Datos iniciales")
  - [Establecer valores de campo](#setting-field-values "Establecer valores de campo")
  - [Establecer opciones de campo](#setting-field-options "Establecer opciones de campo")
  - [Leer datos del formulario](#reading-form-data "Leer datos del formulario")
  - [Limpiar datos](#clearing-data "Limpiar datos")
  - [Actualizar campos](#finding-and-updating-fields "Actualizar campos")
- [Botón de envío](#submit-button "Botón de envío")
- [Diseño del formulario](#form-layout "Diseño del formulario")
- [Visibilidad de campos](#field-visibility "Visibilidad de campos")
- [Estilo de campos](#field-styling "Estilo de campos")
- [Métodos estáticos de NyFormWidget](#ny-form-widget-static-methods "Métodos estáticos de NyFormWidget")
- [Referencia del constructor de NyFormWidget](#ny-form-widget-constructor-reference "Referencia del constructor de NyFormWidget")
- [NyFormActions](#ny-form-actions "NyFormActions")
- [Referencia de todos los tipos de campo](#all-field-types-reference "Referencia de todos los tipos de campo")

<div id="introduction"></div>

## Introducción

{{ config('app.name') }} v7 proporciona un sistema de formularios construido alrededor de `NyFormWidget`. Tu clase de formulario extiende `NyFormWidget` y **es** el widget — no se necesita un envoltorio separado. Los formularios soportan validación integrada, muchos tipos de campo, estilización y gestión de datos.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// 1. Definir un formulario
class LoginForm extends NyFormWidget {
  LoginForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  fields() => [
    Field.email("Email", validator: FormValidator.email()),
    Field.password("Password", validator: FormValidator.password()),
  ];

  static NyFormActions get actions => const NyFormActions('LoginForm');
}

// 2. Mostrarlo y enviarlo
LoginForm(
  submitButton: Button.primary(text: "Login"),
  onSubmit: (data) {
    print(data); // {email: "...", password: "..."}
  },
)
```


<div id="creating-forms"></div>

## Crear un formulario

Usa Metro CLI para crear un nuevo formulario:

``` bash
metro make:form LoginForm
```

Esto crea `lib/app/forms/login_form.dart`:

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

Los formularios extienden `NyFormWidget` y sobreescriben el método `fields()` para definir los campos del formulario. Cada campo usa un constructor nombrado como `Field.text()`, `Field.email()` o `Field.password()`. El getter `static NyFormActions get actions` proporciona una forma conveniente de interactuar con el formulario desde cualquier parte de tu aplicación.


<div id="displaying-a-form"></div>

## Mostrar un formulario

Dado que tu clase de formulario extiende `NyFormWidget`, **es** el widget. Úsalo directamente en tu árbol de widgets:

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

## Enviar un formulario

Hay tres formas de enviar un formulario:

### Usando onSubmit y submitButton

Pasa `onSubmit` y un `submitButton` al construir el formulario. {{ config('app.name') }} proporciona botones preconstruidos que funcionan como botones de envío:

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

Estilos de botón disponibles: `Button.primary`, `Button.secondary`, `Button.outlined`, `Button.textOnly`, `Button.icon`, `Button.gradient`, `Button.rounded`, `Button.transparency`.

### Usando NyFormActions

Usa el getter `actions` para enviar desde cualquier lugar:

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

### Usando el método estático NyFormWidget.submit()

Envía un formulario por su nombre desde cualquier lugar:

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

Al enviar, el formulario valida todos los campos. Si es válido, se llama a `onSuccess` con un `Map<String, dynamic>` de los datos del campo (las claves son versiones en snake_case de los nombres de campo). Si no es válido, se muestra un error toast por defecto y se llama a `onFailure` si se proporcionó.


<div id="field-types"></div>

## Tipos de campo

{{ config('app.name') }} v7 proporciona 22 tipos de campo mediante constructores nombrados en la clase `Field`. Todos los constructores de campo comparten estos parámetros comunes:

| Parámetro | Tipo | Predeterminado | Descripción |
|-----------|------|----------------|-------------|
| `key` | `String` | Requerido | El identificador del campo (posicional) |
| `label` | `String?` | `null` | Etiqueta personalizada (por defecto la clave en formato título) |
| `value` | `dynamic` | `null` | Valor inicial |
| `validator` | `FormValidator?` | `null` | Reglas de validación |
| `autofocus` | `bool` | `false` | Enfoque automático al cargar |
| `dummyData` | `String?` | `null` | Datos de prueba/desarrollo |
| `header` | `Widget?` | `null` | Widget mostrado encima del campo |
| `footer` | `Widget?` | `null` | Widget mostrado debajo del campo |
| `titleStyle` | `TextStyle?` | `null` | Estilo de texto personalizado para la etiqueta |
| `hidden` | `bool` | `false` | Ocultar el campo |
| `readOnly` | `bool?` | `null` | Hacer el campo de solo lectura |
| `style` | `FieldStyle?` | Varía | Configuración de estilo específica del campo |
| `onChanged` | `Function(dynamic)?` | `null` | Callback de cambio de valor |

<div id="text-fields"></div>

### Campos de texto

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

### Campos numéricos

``` dart
Field.number("Age")

// Números decimales
Field.number("Score", decimal: true)
```

El parámetro `decimal` controla si se permite la entrada decimal. Tipo de estilo: `FieldStyleTextField`

<div id="password-fields"></div>

### Campos de contraseña

``` dart
Field.password("Password")

// Con alternancia de visibilidad
Field.password("Password", viewable: true)
```

El parámetro `viewable` agrega un botón de mostrar/ocultar. Tipo de estilo: `FieldStyleTextField`

<div id="email-fields"></div>

### Campos de correo electrónico

``` dart
Field.email("Email", validator: FormValidator.email())
```

Establece automáticamente el tipo de teclado para correo electrónico y filtra espacios en blanco. Tipo de estilo: `FieldStyleTextField`

<div id="url-fields"></div>

### Campos de URL

``` dart
Field.url("Website", validator: FormValidator.url())
```

Establece el tipo de teclado para URL. Tipo de estilo: `FieldStyleTextField`

<div id="text-area-fields"></div>

### Campos de área de texto

``` dart
Field.textArea("Description")
```

Entrada de texto multilínea. Tipo de estilo: `FieldStyleTextField`

<div id="phone-number-fields"></div>

### Campos de número de teléfono

``` dart
Field.phoneNumber("Mobile Phone")
```

Formatea automáticamente la entrada del número de teléfono. Tipo de estilo: `FieldStyleTextField`

<div id="capitalize-words-fields"></div>

### Capitalizar palabras

``` dart
Field.capitalizeWords("Full Name")
```

Capitaliza la primera letra de cada palabra. Tipo de estilo: `FieldStyleTextField`

<div id="capitalize-sentences-fields"></div>

### Capitalizar oraciones

``` dart
Field.capitalizeSentences("Bio")
```

Capitaliza la primera letra de cada oración. Tipo de estilo: `FieldStyleTextField`

<div id="date-fields"></div>

### Campos de fecha

``` dart
Field.date("Birthday")

Field.date("Birthday",
  dummyData: "1990-01-01",
  style: FieldStyleDateTimePicker(
    firstDate: DateTime(1900),
    lastDate: DateTime.now(),
  ),
)

// Deshabilitar el botón de limpiar
Field.date("Birthday",
  style: FieldStyleDateTimePicker(
    canClear: false,
  ),
)

// Ícono de limpiar personalizado
Field.date("Birthday",
  style: FieldStyleDateTimePicker(
    clearIconData: Icons.close,
  ),
)
```

Abre un selector de fecha. Por defecto, el campo muestra un botón de limpiar que permite a los usuarios restablecer el valor. Establece `canClear: false` para ocultarlo, o usa `clearIconData` para cambiar el ícono. Tipo de estilo: `FieldStyleDateTimePicker`

<div id="datetime-fields"></div>

### Campos de fecha y hora

``` dart
Field.datetime("Check in Date")

Field.datetime("Appointment",
  firstDate: DateTime(2025),
  lastDate: DateTime(2030),
  dateFormat: DateFormat('yyyy-MM-dd HH:mm'),
  initialPickerDateTime: DateTime.now(),
)
```

Abre un selector de fecha y hora. Puedes establecer `firstDate`, `lastDate`, `dateFormat` e `initialPickerDateTime` directamente como parámetros de nivel superior. Tipo de estilo: `FieldStyleDateTimePicker`

<div id="masked-input-fields"></div>

### Campos de entrada con máscara

``` dart
Field.mask("Phone", mask: "(###) ###-####")

Field.mask("Credit Card", mask: "#### #### #### ####")

Field.mask("Custom Code",
  mask: "AA-####",
  match: r'[\w\d]',
  maskReturnValue: true, // Devuelve el valor formateado
)
```

El carácter `#` en la máscara se reemplaza por la entrada del usuario. Usa `match` para controlar los caracteres permitidos. Cuando `maskReturnValue` es `true`, el valor devuelto incluye el formato de la máscara.

<div id="currency-fields"></div>

### Campos de moneda

``` dart
Field.currency("Price", currency: "usd")
```

El parámetro `currency` es requerido y determina el formato de la moneda. Tipo de estilo: `FieldStyleTextField`

<div id="checkbox-fields"></div>

### Campos de casilla de verificación

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

### Campos de interruptor

``` dart
Field.switchBox("Enable Notifications")
```

Tipo de estilo: `FieldStyleSwitchBox`

<div id="picker-fields"></div>

### Campos de selector

``` dart
Field.picker("Category",
  options: FormCollection.from(["Electronics", "Clothing", "Books"]),
)

// Con pares clave-valor
Field.picker("Country",
  options: FormCollection.fromMap({
    "us": "United States",
    "ca": "Canada",
    "uk": "United Kingdom",
  }),
)
```

El parámetro `options` requiere un `FormCollection` (no una lista directa). Consulta [FormCollection](#form-collection) para más detalles. Tipo de estilo: `FieldStylePicker`

#### Estilos de ListTile

Puedes personalizar cómo aparecen los elementos en la hoja inferior del selector usando `PickerListTileStyle`. Por defecto, la hoja inferior muestra tiles de texto simple. Usa los presets integrados para agregar indicadores de selección, o proporciona un constructor completamente personalizado.

**Estilo radio** — muestra un ícono de botón de radio como widget principal:

``` dart
Field.picker("Country",
  options: FormCollection.from(["United States", "Canada", "United Kingdom"]),
  style: FieldStylePicker(
    listTileStyle: PickerListTileStyle.radio(),
  ),
)

// Con un color activo personalizado
FieldStylePicker(
  listTileStyle: PickerListTileStyle.radio(activeColor: Colors.blue),
)
```

**Estilo marca de verificación** — muestra un ícono de verificación como widget final cuando está seleccionado:

``` dart
Field.picker("Category",
  options: FormCollection.from(["Electronics", "Clothing", "Books"]),
  style: FieldStylePicker(
    listTileStyle: PickerListTileStyle.checkmark(activeColor: Colors.green),
  ),
)
```

**Constructor personalizado** — control total sobre el widget de cada tile:

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

Ambos estilos de preset también soportan `textStyle`, `selectedTextStyle`, `contentPadding`, `tileColor` y `selectedTileColor`:

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

### Campos de radio

``` dart
Field.radio("Newsletter",
  options: FormCollection.fromMap({
    "Yes": "Yes, I want to receive newsletters",
    "No": "No, I do not want to receive newsletters",
  }),
)
```

El parámetro `options` requiere un `FormCollection`. Tipo de estilo: `FieldStyleRadio`

<div id="chip-fields"></div>

### Campos de chip

``` dart
Field.chips("Tags",
  options: FormCollection.from(["Featured", "Sale", "New"]),
)

// Con pares clave-valor
Field.chips("Engine Size",
  options: FormCollection.fromMap({
    "125": "125cc",
    "250": "250cc",
    "500": "500cc",
  }),
)
```

Permite selección múltiple mediante widgets de chip. El parámetro `options` requiere un `FormCollection`. Tipo de estilo: `FieldStyleChip`

<div id="slider-fields"></div>

### Campos de deslizador

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

Tipo de estilo: `FieldStyleSlider` — configura `min`, `max`, `divisions`, colores, visualización de valor y más.

<div id="range-slider-fields"></div>

### Campos de deslizador de rango

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

Devuelve un objeto `RangeValues`. Tipo de estilo: `FieldStyleRangeSlider`

<div id="custom-fields"></div>

### Campos personalizados

Usa `Field.custom()` para proporcionar tu propio widget con estado:

``` dart
Field.custom("My Field",
  child: MyCustomFieldWidget(),
)
```

El parámetro `child` requiere un widget que extienda `NyFieldStatefulWidget`. Esto te da control total sobre la renderización y el comportamiento del campo.

<div id="widget-fields"></div>

### Campos de widget

Usa `Field.widget()` para incrustar cualquier widget dentro del formulario sin que sea un campo de formulario:

``` dart
Field.widget(child: Divider())

Field.widget(child: Text("Section Header", style: TextStyle(fontSize: 18)))
```

Los campos de widget no participan en la validación ni en la recopilación de datos. Son puramente para diseño.


<div id="form-collection"></div>

## FormCollection

Los campos de selector, radio y chip requieren un `FormCollection` para sus opciones. `FormCollection` proporciona una interfaz unificada para manejar diferentes formatos de opciones.

### Crear un FormCollection

``` dart
// Desde una lista de cadenas (el valor y la etiqueta son iguales)
FormCollection.from(["Red", "Green", "Blue"])

// Lo mismo que arriba, explícito
FormCollection.fromArray(["Red", "Green", "Blue"])

// Desde un mapa (clave = valor, valor = etiqueta)
FormCollection.fromMap({
  "us": "United States",
  "ca": "Canada",
})

// Desde datos estructurados (útil para respuestas de API)
FormCollection.fromKeyValue([
  {"value": "en", "label": "English"},
  {"value": "es", "label": "Spanish"},
])
```

`FormCollection.from()` detecta automáticamente el formato de los datos y delega al constructor apropiado.

### FormOption

Cada opción en un `FormCollection` es un `FormOption` con propiedades `value` y `label`:

``` dart
FormOption option = FormOption(value: "us", label: "United States");
print(option.value); // "us"
print(option.label); // "United States"
```

### Consultar opciones

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

## Validación de formularios

Agrega validación a cualquier campo usando el parámetro `validator` con `FormValidator`:

``` dart
// Constructor nombrado
Field.email("Email", validator: FormValidator.email())

// Reglas encadenadas
Field.text("Username",
  validator: FormValidator()
    .notEmpty()
    .minLength(3)
    .maxLength(20)
)

// Contraseña con nivel de seguridad
Field.password("Password",
  validator: FormValidator.password(strength: 2)
)

// Validación booleana
Field.checkbox("Terms",
  validator: FormValidator.booleanTrue(message: "You must accept the terms")
)

// Validación personalizada en línea
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

Cuando se envía un formulario, se verifican todos los validadores. Si alguno falla, se muestra un error toast con el primer mensaje de error y se llama al callback `onFailure`.

**Ver también:** [Validación](/docs/7.x/validation#validation-rules) para una lista completa de validadores disponibles.


<div id="managing-form-data"></div>

## Gestión de datos del formulario

<div id="initial-data"></div>

### Datos iniciales

Hay dos formas de establecer datos iniciales en un formulario.

**Opción 1: Sobreescribir el getter `init` en tu clase de formulario**

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

El getter `init` puede devolver un `Map` síncrono o un `Future<Map>` asíncrono. Las claves se comparan con los nombres de campo usando normalización snake_case, por lo que `"First Name"` se mapea a un campo con clave `"First Name"`.

#### Usando `define()` en init

Usa el helper `define()` cuando necesites establecer **opciones** (o tanto un valor como opciones) para un campo en `init`. Esto es útil para campos de selector, chip y radio donde las opciones provienen de una API u otra fuente asíncrona.

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

`define()` acepta dos parámetros nombrados:

| Parámetro | Descripción |
|-----------|-------------|
| `value` | El valor inicial para el campo |
| `options` | Las opciones para campos de selector, chip o radio |

``` dart
// Establecer solo opciones (sin valor inicial)
"Category": define(options: categories),

// Establecer solo un valor inicial
"Price": define(value: "100"),

// Establecer tanto un valor como opciones
"Country": define(value: "us", options: countries),

// Los valores simples siguen funcionando para campos sencillos
"Name": "John",
```

Las opciones pasadas a `define()` pueden ser una `List`, `Map` o `FormCollection`. Se convierten automáticamente a un `FormCollection` al aplicarse.

**Opción 2: Pasar `initialData` al widget del formulario**

``` dart
EditAccountForm(
  initialData: {
    "first_name": "John",
    "last_name": "Doe",
  },
)
```

<div id="setting-field-values"></div>

### Establecer valores de campo

Usa `NyFormActions` para establecer valores de campo desde cualquier lugar:

``` dart
// Establecer un valor de campo único
EditAccountForm.actions.updateField("First Name", "Jane");
```

<div id="setting-field-options"></div>

### Establecer opciones de campo

Actualiza opciones en campos de selector, chip o radio dinámicamente:

``` dart
EditAccountForm.actions.setOptions("Category", FormCollection.from(["New Option 1", "New Option 2"]));
```

<div id="reading-form-data"></div>

### Leer datos del formulario

Los datos del formulario se acceden a través del callback `onSubmit` cuando se envía el formulario, o a través del callback `onChanged` para actualizaciones en tiempo real:

``` dart
EditAccountForm(
  onSubmit: (data) {
    // data es un Map<String, dynamic>
    // {first_name: "Jane", last_name: "Doe", email: "jane@example.com"}
    print(data);
  },
  onChanged: (Field field, dynamic value) {
    print("${field.key} changed to: $value");
  },
)
```

<div id="clearing-data"></div>

### Limpiar datos

``` dart
// Limpiar todos los campos
EditAccountForm.actions.clear();

// Limpiar un campo específico
EditAccountForm.actions.clearField("First Name");
```


<div id="finding-and-updating-fields"></div>

### Actualizar campos

``` dart
// Actualizar un valor de campo
EditAccountForm.actions.updateField("First Name", "Jane");

// Refrescar la UI del formulario
EditAccountForm.actions.refresh();

// Refrescar los campos del formulario (vuelve a llamar fields())
EditAccountForm.actions.refreshForm();
```


<div id="submit-button"></div>

## Botón de envío

Pasa un `submitButton` y un callback `onSubmit` al construir el formulario:

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

El `submitButton` se muestra automáticamente debajo de los campos del formulario. Puedes usar cualquiera de los estilos de botón integrados o un widget personalizado.

También puedes usar cualquier widget como botón de envío pasándolo como `footer`:

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

## Diseño del formulario

Coloca campos lado a lado envolviéndolos en una `List`:

``` dart
@override
fields() => [
  // Campo único (ancho completo)
  Field.text("Title"),

  // Dos campos en una fila
  [
    Field.text("First Name"),
    Field.text("Last Name"),
  ],

  // Otro campo único
  Field.textArea("Bio"),

  // Deslizador y deslizador de rango en una fila
  [
    Field.slider("Rating", style: FieldStyleSlider(min: 0, max: 10)),
    Field.rangeSlider("Budget", style: FieldStyleRangeSlider(min: 0, max: 1000)),
  ],

  // Incrustar un widget que no es campo
  Field.widget(child: Divider()),

  Field.email("Email"),
];
```

Los campos en una `List` se renderizan en un `Row` con anchos `Expanded` iguales. El espaciado entre campos se controla con el parámetro `crossAxisSpacing` en `NyFormWidget`.


<div id="field-visibility"></div>

## Visibilidad de campos

Muestra u oculta campos programáticamente usando los métodos `hide()` y `show()` en `Field`. Puedes acceder a los campos dentro de tu clase de formulario o a través del callback `onChanged`:

``` dart
// Dentro de tu subclase NyFormWidget o callback onChanged
Field nameField = ...;

// Ocultar el campo
nameField.hide();

// Mostrar el campo
nameField.show();
```

Los campos ocultos no se renderizan en la UI pero siguen existiendo en la lista de campos del formulario.


<div id="field-styling"></div>

## Estilo de campos

Cada tipo de campo tiene una subclase `FieldStyle` correspondiente para el estilo:

| Tipo de campo | Clase de estilo |
|---------------|-----------------|
| Text, Email, Password, Number, URL, TextArea, PhoneNumber, Currency, Mask, CapitalizeWords, CapitalizeSentences | `FieldStyleTextField` |
| Date, DateTime | `FieldStyleDateTimePicker` |
| Picker | `FieldStylePicker` |
| Checkbox | `FieldStyleCheckbox` |
| Switch Box | `FieldStyleSwitchBox` |
| Radio | `FieldStyleRadio` |
| Chip | `FieldStyleChip` |
| Slider | `FieldStyleSlider` |
| Range Slider | `FieldStyleRangeSlider` |

Pasa un objeto de estilo al parámetro `style` de cualquier campo:

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

## Métodos estáticos de NyFormWidget

`NyFormWidget` proporciona métodos estáticos para interactuar con formularios por nombre desde cualquier parte de tu aplicación:

| Método | Descripción |
|--------|-------------|
| `NyFormWidget.submit(name, onSuccess:, onFailure:, showToastError:)` | Enviar un formulario por su nombre |
| `NyFormWidget.stateRefresh(name)` | Refrescar el estado de la UI del formulario |
| `NyFormWidget.stateSetValue(name, key, value)` | Establecer un valor de campo por nombre del formulario |
| `NyFormWidget.stateSetOptions(name, key, options)` | Establecer opciones de campo por nombre del formulario |
| `NyFormWidget.stateClearData(name)` | Limpiar todos los campos por nombre del formulario |
| `NyFormWidget.stateRefreshForm(name)` | Refrescar campos del formulario (vuelve a llamar `fields()`) |

``` dart
// Enviar un formulario llamado "LoginForm" desde cualquier lugar
NyFormWidget.submit("LoginForm", onSuccess: (data) {
  print(data);
});

// Actualizar un valor de campo remotamente
NyFormWidget.stateSetValue("LoginForm", "Email", "new@email.com");

// Limpiar todos los datos del formulario
NyFormWidget.stateClearData("LoginForm");
```

> **Consejo:** Prefiere usar `NyFormActions` (ver abajo) en lugar de llamar estos métodos estáticos directamente — es más conciso y menos propenso a errores.


<div id="ny-form-widget-constructor-reference"></div>

## Referencia del constructor de NyFormWidget

Al extender `NyFormWidget`, estos son los parámetros del constructor que puedes pasar:

``` dart
LoginForm(
  Key? key,
  double crossAxisSpacing = 10,  // Espaciado horizontal entre campos en fila
  double mainAxisSpacing = 10,   // Espaciado vertical entre campos
  Map<String, dynamic>? initialData, // Valores iniciales de los campos
  Function(Field field, dynamic value)? onChanged, // Callback de cambio de campo
  Widget? header,                // Widget encima del formulario
  Widget? submitButton,          // Widget del botón de envío
  Widget? footer,                // Widget debajo del formulario
  double headerSpacing = 10,     // Espaciado después del encabezado
  double submitButtonSpacing = 10, // Espaciado después del botón de envío
  double footerSpacing = 10,     // Espaciado antes del pie de página
  LoadingStyle? loadingStyle,    // Estilo del indicador de carga
  bool locked = false,           // Hace el formulario de solo lectura
  Function(dynamic data)? onSubmit,   // Se llama con los datos al validar correctamente
  Function(dynamic error)? onFailure, // Se llama con los errores al fallar la validación
)
```

El callback `onChanged` recibe el `Field` que cambió y su nuevo valor:

``` dart
LoginForm(
  onChanged: (Field field, dynamic value) {
    print("${field.key} changed to: $value");
  },
)
```


<div id="ny-form-actions"></div>

## NyFormActions

`NyFormActions` proporciona una forma conveniente de interactuar con un formulario desde cualquier parte de tu aplicación. Defínelo como un getter estático en tu clase de formulario:

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

### Acciones disponibles

| Método | Descripción |
|--------|-------------|
| `actions.updateField(key, value)` | Establecer el valor de un campo |
| `actions.clearField(key)` | Limpiar un campo específico |
| `actions.clear()` | Limpiar todos los campos |
| `actions.refresh()` | Refrescar el estado de la UI del formulario |
| `actions.refreshForm()` | Volver a llamar `fields()` y reconstruir |
| `actions.setOptions(key, options)` | Establecer opciones en campos de selector/chip/radio |
| `actions.submit(onSuccess:, onFailure:, showToastError:)` | Enviar con validación |

``` dart
// Actualizar un valor de campo
LoginForm.actions.updateField("Email", "new@email.com");

// Limpiar todos los datos del formulario
LoginForm.actions.clear();

// Enviar el formulario
LoginForm.actions.submit(
  onSuccess: (data) {
    print(data);
  },
);
```

### Sobreescrituras de NyFormWidget

Métodos que puedes sobreescribir en tu subclase de `NyFormWidget`:

| Sobreescritura | Descripción |
|----------------|-------------|
| `fields()` | Definir los campos del formulario (requerido) |
| `init` | Proporcionar datos iniciales (síncrono o asíncrono) |
| `onChange(field, data)` | Manejar cambios de campo internamente |


<div id="all-field-types-reference"></div>

## Referencia de todos los tipos de campo

| Constructor | Parámetros clave | Descripción |
|-------------|------------------|-------------|
| `Field.text()` | — | Entrada de texto estándar |
| `Field.email()` | — | Entrada de correo con tipo de teclado |
| `Field.password()` | `viewable` | Contraseña con alternancia de visibilidad opcional |
| `Field.number()` | `decimal` | Entrada numérica, decimal opcional |
| `Field.currency()` | `currency` (requerido) | Entrada con formato de moneda |
| `Field.capitalizeWords()` | — | Entrada de texto en formato título |
| `Field.capitalizeSentences()` | — | Entrada de texto con mayúscula inicial en oraciones |
| `Field.textArea()` | — | Entrada de texto multilínea |
| `Field.phoneNumber()` | — | Número de teléfono con formato automático |
| `Field.url()` | — | Entrada de URL con tipo de teclado |
| `Field.mask()` | `mask` (requerido), `match`, `maskReturnValue` | Entrada de texto con máscara |
| `Field.date()` | — | Selector de fecha |
| `Field.datetime()` | `firstDate`, `lastDate`, `dateFormat`, `initialPickerDateTime` | Selector de fecha y hora |
| `Field.checkbox()` | — | Casilla de verificación booleana |
| `Field.switchBox()` | — | Interruptor de alternancia booleano |
| `Field.picker()` | `options` (requiere `FormCollection`) | Selección única de lista |
| `Field.radio()` | `options` (requiere `FormCollection`) | Grupo de botones de radio |
| `Field.chips()` | `options` (requiere `FormCollection`) | Chips de selección múltiple |
| `Field.slider()` | — | Deslizador de valor único |
| `Field.rangeSlider()` | — | Deslizador de rango de valores |
| `Field.custom()` | `child` (requiere `NyFieldStatefulWidget`) | Widget con estado personalizado |
| `Field.widget()` | `child` (requiere `Widget`) | Incrustar cualquier widget (no es campo) |
