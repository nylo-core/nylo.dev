# Localization

---

<a name="section-1"></a>
- [Introducción](#introduction "Introducción a la localización")
- [Configuración](#configuration "Configuración")
- [Agregar archivos localizados](#adding-localized-files "Agregar archivos localizados")
- Conceptos básicos
  - [Localizar texto](#localizing-text "Localizar texto")
    - [Argumentos](#arguments "Argumentos")
    - [Marcadores de StyledText](#styled-text-placeholders "Marcadores de StyledText")
  - [Actualizar la localización](#updating-the-locale "Actualizar la localización")
  - [Establecer una localización predeterminada](#setting-a-default-locale "Establecer una localización predeterminada")
- Avanzado
  - [Localizaciones soportadas](#supported-locales "Localizaciones soportadas")
  - [Idioma de respaldo](#fallback-language "Idioma de respaldo")
  - [Soporte RTL](#rtl-support "Soporte RTL")
  - [Depurar claves faltantes](#debug-missing-keys "Depurar claves faltantes")
  - [API de NyLocalization](#nylocalization-api "API de NyLocalization")
  - [NyLocaleHelper](#nylocalehelper "Clase utilitaria NyLocaleHelper")
  - [Cambiar idioma desde un controlador](#changing-language-from-controller "Cambiar idioma desde un controlador")


<div id="introduction"></div>

## Introducción

La localización te permite proporcionar tu aplicación en múltiples idiomas. {{ config('app.name') }} v7 facilita la localización de texto usando archivos de idioma JSON.

Aquí tienes un ejemplo rápido:

**lang/en.json**
``` json
{
  "welcome": "Welcome",
  "greeting": "Hello @{{name}}"
}
```

**En tu widget:**
``` dart
Text("welcome".tr())              // "Welcome"
Text("greeting".tr(arguments: {"name": "Anthony"}))  // "Hello Anthony"
```

<div id="configuration"></div>

## Configuración

La localización se configura en `lib/config/localization.dart`:

``` dart
final class LocalizationConfig {
  // Código de idioma predeterminado (coincide con tu archivo JSON, ej., 'en' para lang/en.json)
  static final String languageCode =
      getEnv('DEFAULT_LOCALE', defaultValue: "en");

  // LocaleType.device - Usar la configuración de idioma del dispositivo
  // LocaleType.asDefined - Usar el languageCode de arriba
  static final LocaleType localeType =
      getEnv('LOCALE_TYPE', defaultValue: 'asDefined') == 'device'
          ? LocaleType.device
          : LocaleType.asDefined;

  // Directorio que contiene los archivos JSON de idioma
  static const String assetsDirectory = 'lang/';

  // Lista de localizaciones soportadas
  static const List<Locale> supportedLocales = [
    Locale('en'),
    Locale('es'),
    // Agregar más localizaciones según sea necesario
  ];

  // Respaldo cuando no se encuentra una clave en la localización activa
  static const String fallbackLanguageCode = 'en';

  // Códigos de idiomas RTL
  static const List<String> rtlLanguages = ['ar', 'he', 'fa', 'ur'];

  // Registrar advertencias por claves de traducción faltantes
  static final bool debugMissingKeys =
      getEnv('DEBUG_TRANSLATIONS', defaultValue: 'false') == 'true';
}
```

<div id="adding-localized-files"></div>

## Agregar archivos localizados

Agrega tus archivos JSON de idioma al directorio `lang/`:

```
lang/
├── en.json   # Inglés
├── es.json   # Español
├── fr.json   # Francés
└── ...
```

**lang/en.json**
``` json
{
  "welcome": "Welcome",
  "settings": "Settings",
  "navigation": {
    "home": "Home",
    "profile": "Profile"
  }
}
```

**lang/es.json**
``` json
{
  "welcome": "Bienvenido",
  "settings": "Configuración",
  "navigation": {
    "home": "Inicio",
    "profile": "Perfil"
  }
}
```

### Registrar en pubspec.yaml

Asegúrate de que tus archivos de idioma estén incluidos en tu `pubspec.yaml`:

``` yaml
flutter:
  assets:
    - lang/
```

<div id="localizing-text"></div>

## Localizar texto

Usa la extensión `.tr()` o el helper `trans()` para traducir cadenas:

``` dart
// Usando la extensión .tr()
"welcome".tr()

// Usando el helper trans()
trans("welcome")
```

### Claves anidadas

Accede a claves JSON anidadas usando notación de puntos:

**lang/en.json**
``` json
{
  "navigation": {
    "home": "Home",
    "profile": "Profile"
  }
}
```

``` dart
"navigation.home".tr()       // "Home"
trans("navigation.profile")  // "Profile"
```

<div id="arguments"></div>

### Argumentos

Pasa valores dinámicos a tus traducciones usando la sintaxis `@{{key}}`:

**lang/en.json**
``` json
{
  "greeting": "Hello @{{name}}",
  "items_count": "You have @{{count}} items"
}
```

``` dart
"greeting".tr(arguments: {"name": "Anthony"})
// "Hello Anthony"

trans("items_count", arguments: {"count": "5"})
// "You have 5 items"
```

<div id="styled-text-placeholders"></div>

### Marcadores de StyledText

Al usar `StyledText.template` con cadenas localizadas, puedes usar la sintaxis `@{{key:text}}`. Esto mantiene la **clave** estable en todas las localizaciones (para que tus estilos y manejadores de tap siempre coincidan), mientras que el **texto** se traduce por localización.

**lang/en.json**
``` json
{
  "learn_skills": "Learn @{{lang:Languages}}, @{{read:Reading}} and @{{speak:Speaking}} skills",
  "already_have_account": "Already have an account? @{{login:Login}}"
}
```

**lang/es.json**
``` json
{
  "learn_skills": "Aprende @{{lang:Idiomas}}, @{{read:Lectura}} y @{{speak:Habla}}",
  "already_have_account": "¿Ya tienes una cuenta? @{{login:Iniciar sesión}}"
}
```

**En tu widget:**
``` dart
StyledText.template(
  "learn_skills".tr(),
  styles: {
    "lang|read|speak": TextStyle(fontWeight: FontWeight.bold),
  },
)
```

Las claves `lang`, `read` y `speak` son las mismas en cada archivo de localización, por lo que el mapa de estilos funciona para todos los idiomas. El texto de visualización después de `:` es lo que ve el usuario — "Languages" en inglés, "Idiomas" en español, etc.

También puedes usar esto con `onTap`:

``` dart
StyledText.template(
  "already_have_account".tr(),
  styles: {
    "login": TextStyle(fontWeight: FontWeight.bold),
  },
  onTap: {
    "login": () => routeTo(LoginPage.path),
  },
)
```

> **Nota:** La sintaxis `@{{key}}` (con prefijo `@`) es para argumentos reemplazados por `.tr(arguments:)` en el momento de la traducción. La sintaxis `@{{key:text}}` (sin `@`) es para marcadores de `StyledText` que se analizan en el momento de la renderización. No los mezcles — usa `@{{}}` para valores dinámicos y `@{{}}` para spans con estilo.

<div id="updating-the-locale"></div>

## Actualizar la localización

Cambia el idioma de la aplicación en tiempo de ejecución:

``` dart
// Usando NyLocalization directamente
await NyLocalization.instance.setLanguage(
  context,
  language: 'es'  // Debe coincidir con el nombre de tu archivo JSON (es.json)
);
```

Si tu widget extiende `NyPage`, usa el helper `changeLanguage`:

``` dart
class _SettingsPageState extends NyPage<SettingsPage> {

  @override
  Widget view(BuildContext context) {
    return ListView(
      children: [
        ListTile(
          title: Text("English"),
          onTap: () => changeLanguage('en'),
        ),
        ListTile(
          title: Text("Español"),
          onTap: () => changeLanguage('es'),
        ),
      ],
    );
  }
}
```

<div id="setting-a-default-locale"></div>

## Establecer una localización predeterminada

Establece el idioma predeterminado en tu archivo `.env`:

``` bash
DEFAULT_LOCALE="en"
```

O usa la localización del dispositivo estableciendo:

``` bash
LOCALE_TYPE="device"
```

Después de cambiar `.env`, regenera tu configuración de entorno:

``` bash
metro make:env --force
```

<div id="supported-locales"></div>

## Localizaciones soportadas

Define qué localizaciones soporta tu aplicación en `LocalizationConfig`:

``` dart
static const List<Locale> supportedLocales = [
  Locale('en'),
  Locale('es'),
  Locale('fr'),
  Locale('de'),
  Locale('ar'),
];
```

Esta lista es utilizada por `MaterialApp.supportedLocales` de Flutter.

<div id="fallback-language"></div>

## Idioma de respaldo

Cuando no se encuentra una clave de traducción en la localización activa, {{ config('app.name') }} recurre al idioma especificado:

``` dart
static const String fallbackLanguageCode = 'en';
```

Esto asegura que tu aplicación nunca muestre claves crudas si falta una traducción.

<div id="rtl-support"></div>

## Soporte RTL

{{ config('app.name') }} v7 incluye soporte integrado para idiomas de derecha a izquierda (RTL):

``` dart
static const List<String> rtlLanguages = ['ar', 'he', 'fa', 'ur'];

// Verificar si el idioma actual es RTL
if (LocalizationConfig.isRtl(currentLanguageCode)) {
  // Manejar diseño RTL
}
```

<div id="debug-missing-keys"></div>

## Depurar claves faltantes

Habilita advertencias para claves de traducción faltantes durante el desarrollo:

En tu archivo `.env`:
``` bash
DEBUG_TRANSLATIONS="true"
```

Esto registra advertencias cuando `.tr()` no puede encontrar una clave, ayudándote a detectar cadenas sin traducir.

<div id="nylocalization-api"></div>

## API de NyLocalization

`NyLocalization` es un singleton que gestiona toda la localización. Más allá del método básico `translate()`, proporciona varios métodos adicionales:

### Verificar si existe una traducción

``` dart
bool exists = NyLocalization.instance.hasTranslation('welcome');
// true si la clave existe en el archivo de idioma actual

// También funciona con claves anidadas
bool nestedExists = NyLocalization.instance.hasTranslation('navigation.home');
```

### Obtener todas las claves de traducción

Útil para depuración para ver qué claves están cargadas:

``` dart
List<String> keys = NyLocalization.instance.getAllKeys();
// ['welcome', 'settings', 'navigation', ...]
```

### Cambiar localización sin reiniciar

Si quieres cambiar la localización silenciosamente (sin reiniciar la aplicación):

``` dart
await NyLocalization.instance.setLocale(locale: Locale('fr'));
```

Esto carga el nuevo archivo de idioma pero **no** reinicia la aplicación. Útil cuando quieres manejar las actualizaciones de UI manualmente.

### Verificar dirección RTL

``` dart
bool isRtl = NyLocalization.instance.isDirectionRTL(context);
```

### Acceder a la localización actual

``` dart
// Obtener el código de idioma actual
String code = NyLocalization.instance.languageCode;  // ej., 'en'

// Obtener el objeto Locale actual
Locale currentLocale = NyLocalization.instance.locale;

// Obtener los delegados de localización de Flutter (usado en MaterialApp)
var delegates = NyLocalization.instance.delegates;
```

### Referencia completa de API

| Método / Propiedad | Devuelve | Descripción |
|---------------------|----------|-------------|
| `instance` | `NyLocalization` | Instancia singleton |
| `translate(key, [arguments])` | `String` | Traducir una clave con argumentos opcionales |
| `hasTranslation(key)` | `bool` | Verificar si existe una clave de traducción |
| `getAllKeys()` | `List<String>` | Obtener todas las claves de traducción cargadas |
| `setLanguage(context, {language, restart})` | `Future<void>` | Cambiar idioma, opcionalmente reiniciar |
| `setLocale({locale})` | `Future<void>` | Cambiar localización sin reiniciar |
| `setDebugMissingKeys(enabled)` | `void` | Habilitar/deshabilitar registro de claves faltantes |
| `isDirectionRTL(context)` | `bool` | Verificar si la dirección actual es RTL |
| `restart(context)` | `void` | Reiniciar la aplicación |
| `languageCode` | `String` | Código de idioma actual |
| `locale` | `Locale` | Objeto Locale actual |
| `delegates` | `Iterable<LocalizationsDelegate>` | Delegados de localización de Flutter |

<div id="nylocalehelper"></div>

## NyLocaleHelper

`NyLocaleHelper` es una clase utilitaria estática para operaciones de localización. Proporciona métodos para detectar la localización actual, verificar soporte RTL y crear objetos Locale.

``` dart
// Obtener la localización actual del sistema
Locale locale = NyLocaleHelper.getCurrentLocale(context: context);

// Obtener códigos de idioma y país
String langCode = NyLocaleHelper.getLanguageCode(context: context);  // 'en'
String? countryCode = NyLocaleHelper.getCountryCode(context: context);  // 'US' o null

// Verificar si la localización actual coincide
bool isEnglish = NyLocaleHelper.matchesLocale(context, 'en');
bool isUsEnglish = NyLocaleHelper.matchesLocale(context, 'en', 'US');

// Detección RTL
bool isRtl = NyLocaleHelper.isRtlLanguage('ar');  // true
bool currentIsRtl = NyLocaleHelper.isCurrentLocaleRtl(context: context);

// Obtener dirección de texto
TextDirection direction = NyLocaleHelper.getTextDirection('ar');  // TextDirection.rtl
TextDirection currentDir = NyLocaleHelper.getCurrentTextDirection(context: context);

// Crear un Locale desde cadenas
Locale newLocale = NyLocaleHelper.toLocale('en', 'US');
```

### Referencia completa de API

| Método | Devuelve | Descripción |
|--------|----------|-------------|
| `getCurrentLocale({context})` | `Locale` | Obtener la localización actual del sistema |
| `getLanguageCode({context})` | `String` | Obtener el código de idioma actual |
| `getCountryCode({context})` | `String?` | Obtener el código de país actual |
| `matchesLocale(context, languageCode, [countryCode])` | `bool` | Verificar si la localización actual coincide |
| `isRtlLanguage(languageCode)` | `bool` | Verificar si un código de idioma es RTL |
| `isCurrentLocaleRtl({context})` | `bool` | Verificar si la localización actual es RTL |
| `getTextDirection(languageCode)` | `TextDirection` | Obtener TextDirection para un idioma |
| `getCurrentTextDirection({context})` | `TextDirection` | Obtener TextDirection para la localización actual |
| `toLocale(languageCode, [countryCode])` | `Locale` | Crear un Locale desde cadenas |

La constante `rtlLanguages` contiene: `ar`, `he`, `fa`, `ur`, `yi`, `ps`, `ku`, `sd`, `dv`.

<div id="changing-language-from-controller"></div>

## Cambiar idioma desde un controlador

Si usas controladores con tus páginas, puedes cambiar el idioma desde `NyController`:

``` dart
class SettingsController extends NyController {

  void switchToSpanish() {
    changeLanguage('es');
  }

  void switchToEnglishNoRestart() {
    changeLanguage('en', restartState: false);
  }
}
```

El parámetro `restartState` controla si la aplicación se reinicia después de cambiar el idioma. Establécelo en `false` si quieres manejar la actualización de la UI tú mismo.
