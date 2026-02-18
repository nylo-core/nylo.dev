# LanguageSwitcher

---

<a name="section-1"></a>
- [Introducción](#introduction "Introducción")
- Uso
    - [Widget desplegable](#usage-dropdown "Widget desplegable")
    - [Modal de hoja inferior](#usage-bottom-modal "Modal de hoja inferior")
- [Constructor personalizado de desplegable](#custom-builder "Constructor personalizado de desplegable")
- [Parámetros](#parameters "Parámetros")
- [Métodos estáticos](#methods "Métodos estáticos")


<div id="introduction"></div>

## Introducción

El widget **LanguageSwitcher** proporciona una forma sencilla de manejar el cambio de idioma en tus proyectos de {{ config('app.name') }}. Detecta automáticamente los idiomas disponibles en tu directorio `/lang` y los muestra al usuario.

**¿Qué hace LanguageSwitcher?**

- Muestra los idiomas disponibles desde tu directorio `/lang`
- Cambia el idioma de la aplicación cuando el usuario selecciona uno
- Persiste el idioma seleccionado entre reinicios de la aplicación
- Actualiza automáticamente la UI cuando el idioma cambia

> **Nota**: Si tu aplicación aún no está localizada, aprende cómo hacerlo en la documentación de [Localización](/docs/7.x/localization) antes de usar este widget.

<div id="usage-dropdown"></div>

## Widget desplegable

La forma más sencilla de usar `LanguageSwitcher` es como un desplegable en tu barra de aplicación:

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    appBar: AppBar(
      title: Text("Settings"),
      actions: [
        LanguageSwitcher(), // Agregar a la barra de aplicación
      ],
    ),
    body: Center(
      child: Text("Hello World".tr()),
    ),
  );
}
```

Cuando el usuario toca el desplegable, verá una lista de idiomas disponibles. Después de seleccionar un idioma, la aplicación cambiará automáticamente y actualizará la UI.

<div id="usage-bottom-modal"></div>

## Modal de hoja inferior

También puedes mostrar los idiomas en un modal de hoja inferior:

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

El modal inferior muestra una lista de idiomas con una marca de verificación junto al idioma actualmente seleccionado.

### Personalizar la altura del modal

``` dart
LanguageSwitcher.showBottomModal(
  context,
  height: 300, // Altura personalizada
);
```

<div id="custom-builder"></div>

## Constructor personalizado de desplegable

Personaliza cómo aparece cada opción de idioma en el desplegable:

``` dart
LanguageSwitcher(
  dropdownBuilder: (Map<String, dynamic> language) {
    return Row(
      children: [
        Icon(Icons.language),
        SizedBox(width: 8),
        Text(language['name']), // ej., "English"
        // language['locale'] contiene el código de localización, ej., "en"
      ],
    );
  },
)
```

### Manejar cambios de idioma

``` dart
LanguageSwitcher(
  onLanguageChange: (Map<String, dynamic> language) {
    print('Language changed to: ${language['name']}');
    // Realizar acciones adicionales cuando el idioma cambia
  },
)
```

<div id="parameters"></div>

## Parámetros

| Parámetro | Tipo | Predeterminado | Descripción |
|-----------|------|----------------|-------------|
| `icon` | `Widget?` | - | Ícono personalizado para el botón desplegable |
| `iconEnabledColor` | `Color?` | - | Color del ícono del desplegable |
| `iconSize` | `double` | `24` | Tamaño del ícono del desplegable |
| `dropdownBgColor` | `Color?` | - | Color de fondo del menú desplegable |
| `hint` | `Widget?` | - | Widget de sugerencia cuando no hay idioma seleccionado |
| `itemHeight` | `double` | `kMinInteractiveDimension` | Altura de cada elemento del desplegable |
| `elevation` | `int` | `8` | Elevación del menú desplegable |
| `padding` | `EdgeInsetsGeometry?` | - | Relleno alrededor del desplegable |
| `borderRadius` | `BorderRadius?` | - | Radio de borde del menú desplegable |
| `textStyle` | `TextStyle?` | - | Estilo de texto para elementos del desplegable |
| `langPath` | `String` | `'lang'` | Ruta a los archivos de idioma en assets |
| `dropdownBuilder` | `Function(Map<String, dynamic>)?` | - | Constructor personalizado para elementos del desplegable |
| `dropdownAlignment` | `AlignmentGeometry` | `AlignmentDirectional.centerStart` | Alineación de los elementos del desplegable |
| `dropdownOnTap` | `Function()?` | - | Callback cuando se toca un elemento del desplegable |
| `onTap` | `Function()?` | - | Callback cuando se toca el botón del desplegable |
| `onLanguageChange` | `Function(Map<String, dynamic>)?` | - | Callback cuando se cambia el idioma |

<div id="methods"></div>

## Métodos estáticos

### Obtener idioma actual

Recupera el idioma actualmente seleccionado:

``` dart
Map<String, dynamic>? lang = await LanguageSwitcher.currentLanguage();
// Devuelve: {"en": "English"} o null si no está establecido
```

### Almacenar idioma

Almacena manualmente una preferencia de idioma:

``` dart
await LanguageSwitcher.storeLanguage(
  object: {"fr": "French"},
);
```

### Limpiar idioma

Elimina la preferencia de idioma almacenada:

``` dart
await LanguageSwitcher.clearLanguage();
```

### Obtener datos del idioma

Obtiene información del idioma a partir de un código de localización:

``` dart
Map<String, String>? langData = LanguageSwitcher.getLanguageData("en");
// Devuelve: {"en": "English"}

Map<String, String>? langData = LanguageSwitcher.getLanguageData("fr_CA");
// Devuelve: {"fr_CA": "French (Canada)"}
```

### Obtener lista de idiomas

Obtiene todos los idiomas disponibles desde el directorio `/lang`:

``` dart
List<Map<String, String>> languages = await LanguageSwitcher.getLanguageList();
// Devuelve: [{"en": "English"}, {"es": "Spanish"}, ...]
```

### Mostrar modal inferior

Muestra el modal de selección de idioma:

``` dart
await LanguageSwitcher.showBottomModal(context);

// Con altura personalizada
await LanguageSwitcher.showBottomModal(context, height: 400);
```

## Localizaciones soportadas

El widget `LanguageSwitcher` soporta cientos de códigos de localización con nombres legibles. Algunos ejemplos:

| Código de localización | Nombre del idioma |
|------------------------|-------------------|
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

La lista completa incluye variantes regionales para la mayoría de los idiomas.
