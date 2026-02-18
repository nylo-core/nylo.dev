# Localization

---

<a name="section-1"></a>
- [Introducao](#introduction "Introducao a localizacao")
- [Configuracao](#configuration "Configuracao")
- [Adicionando Arquivos Localizados](#adding-localized-files "Adicionando arquivos localizados")
- Basico
  - [Localizando Texto](#localizing-text "Localizando texto")
    - [Argumentos](#arguments "Argumentos")
    - [Placeholders StyledText](#styled-text-placeholders "Placeholders StyledText")
  - [Atualizando a Localidade](#updating-the-locale "Atualizando a localidade")
  - [Definindo uma Localidade Padrao](#setting-a-default-locale "Definindo uma localidade padrao")
- Avancado
  - [Localidades Suportadas](#supported-locales "Localidades suportadas")
  - [Idioma de Fallback](#fallback-language "Idioma de fallback")
  - [Suporte RTL](#rtl-support "Suporte RTL")
  - [Depurar Chaves Ausentes](#debug-missing-keys "Depurar chaves ausentes")
  - [API NyLocalization](#nylocalization-api "API NyLocalization")
  - [NyLocaleHelper](#nylocalehelper "Classe utilitaria NyLocaleHelper")
  - [Mudando Idioma a partir de um Controller](#changing-language-from-controller "Mudando idioma a partir de um controller")


<div id="introduction"></div>

## Introducao

A localizacao permite que voce disponibilize seu app em multiplos idiomas. {{ config('app.name') }} v7 facilita a localizacao de texto usando arquivos JSON de idioma.

Aqui esta um exemplo rapido:

**lang/en.json**
``` json
{
  "welcome": "Welcome",
  "greeting": "Hello @{{name}}"
}
```

**No seu widget:**
``` dart
Text("welcome".tr())              // "Welcome"
Text("greeting".tr(arguments: {"name": "Anthony"}))  // "Hello Anthony"
```

<div id="configuration"></div>

## Configuracao

A localizacao e configurada em `lib/config/localization.dart`:

``` dart
final class LocalizationConfig {
  // Default language code (matches your JSON file, e.g., 'en' for lang/en.json)
  static final String languageCode =
      getEnv('DEFAULT_LOCALE', defaultValue: "en");

  // LocaleType.device - Use device's language setting
  // LocaleType.asDefined - Use languageCode above
  static final LocaleType localeType =
      getEnv('LOCALE_TYPE', defaultValue: 'asDefined') == 'device'
          ? LocaleType.device
          : LocaleType.asDefined;

  // Directory containing language JSON files
  static const String assetsDirectory = 'lang/';

  // List of supported locales
  static const List<Locale> supportedLocales = [
    Locale('en'),
    Locale('es'),
    // Add more locales as needed
  ];

  // Fallback when a key is not found in the active locale
  static const String fallbackLanguageCode = 'en';

  // RTL language codes
  static const List<String> rtlLanguages = ['ar', 'he', 'fa', 'ur'];

  // Log warnings for missing translation keys
  static final bool debugMissingKeys =
      getEnv('DEBUG_TRANSLATIONS', defaultValue: 'false') == 'true';
}
```

<div id="adding-localized-files"></div>

## Adicionando Arquivos Localizados

Adicione seus arquivos JSON de idioma ao diretorio `lang/`:

```
lang/
├── en.json   # English
├── es.json   # Spanish
├── fr.json   # French
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

### Registrar no pubspec.yaml

Certifique-se de que seus arquivos de idioma estao incluidos no seu `pubspec.yaml`:

``` yaml
flutter:
  assets:
    - lang/
```

<div id="localizing-text"></div>

## Localizando Texto

Use a extensao `.tr()` ou o helper `trans()` para traduzir strings:

``` dart
// Using the .tr() extension
"welcome".tr()

// Using the trans() helper
trans("welcome")
```

### Chaves Aninhadas

Acesse chaves JSON aninhadas usando notacao de ponto:

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

Passe valores dinamicos nas suas traducoes usando a sintaxe `@{{key}}`:

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

### Placeholders StyledText

Ao usar `StyledText.template` com strings localizadas, voce pode usar a sintaxe `@{{key:text}}`. Isso mantem a **chave** estavel em todas as localidades (para que seus estilos e handlers de toque sempre correspondam), enquanto o **texto** e traduzido por localidade.

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

**No seu widget:**
``` dart
StyledText.template(
  "learn_skills".tr(),
  styles: {
    "lang|read|speak": TextStyle(fontWeight: FontWeight.bold),
  },
)
```

As chaves `lang`, `read` e `speak` sao as mesmas em todos os arquivos de localidade, entao o mapa de estilos funciona para todos os idiomas. O texto de exibicao apos o `:` e o que o usuario ve — "Languages" em ingles, "Idiomas" em espanhol, etc.

Voce tambem pode usar isso com `onTap`:

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

> **Nota:** A sintaxe `@{{key}}` (com prefixo `@`) e para argumentos substituidos por `.tr(arguments:)` no momento da traducao. A sintaxe `@{{key:text}}` (sem `@`) e para placeholders do `StyledText` analisados no momento da renderizacao. Nao misture — use `@{{}}` para valores dinamicos e `@{{}}` para trechos estilizados.

<div id="updating-the-locale"></div>

## Atualizando a Localidade

Mude o idioma do app em tempo de execucao:

``` dart
// Using NyLocalization directly
await NyLocalization.instance.setLanguage(
  context,
  language: 'es'  // Must match your JSON filename (es.json)
);
```

Se seu widget estende `NyPage`, use o helper `changeLanguage`:

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

## Definindo uma Localidade Padrao

Defina o idioma padrao no seu arquivo `.env`:

``` bash
DEFAULT_LOCALE="en"
```

Ou use a localidade do dispositivo definindo:

``` bash
LOCALE_TYPE="device"
```

Apos alterar o `.env`, regenere a configuracao de ambiente:

``` bash
metro make:env --force
```

<div id="supported-locales"></div>

## Localidades Suportadas

Defina quais localidades seu app suporta em `LocalizationConfig`:

``` dart
static const List<Locale> supportedLocales = [
  Locale('en'),
  Locale('es'),
  Locale('fr'),
  Locale('de'),
  Locale('ar'),
];
```

Esta lista e usada pelo `MaterialApp.supportedLocales` do Flutter.

<div id="fallback-language"></div>

## Idioma de Fallback

Quando uma chave de traducao nao e encontrada na localidade ativa, {{ config('app.name') }} recorre ao idioma especificado:

``` dart
static const String fallbackLanguageCode = 'en';
```

Isso garante que seu app nunca mostre chaves brutas se uma traducao estiver faltando.

<div id="rtl-support"></div>

## Suporte RTL

{{ config('app.name') }} v7 inclui suporte integrado para idiomas da direita para a esquerda (RTL):

``` dart
static const List<String> rtlLanguages = ['ar', 'he', 'fa', 'ur'];

// Check if current language is RTL
if (LocalizationConfig.isRtl(currentLanguageCode)) {
  // Handle RTL layout
}
```

<div id="debug-missing-keys"></div>

## Depurar Chaves Ausentes

Habilite avisos para chaves de traducao ausentes durante o desenvolvimento:

No seu arquivo `.env`:
``` bash
DEBUG_TRANSLATIONS="true"
```

Isso registra avisos quando `.tr()` nao consegue encontrar uma chave, ajudando voce a identificar strings nao traduzidas.

<div id="nylocalization-api"></div>

## API NyLocalization

`NyLocalization` e um singleton que gerencia toda a localizacao. Alem do metodo basico `translate()`, ele fornece varios metodos adicionais:

### Verificar se uma Traducao Existe

``` dart
bool exists = NyLocalization.instance.hasTranslation('welcome');
// true if the key exists in the current language file

// Also works with nested keys
bool nestedExists = NyLocalization.instance.hasTranslation('navigation.home');
```

### Obter Todas as Chaves de Traducao

Util para depuracao para ver quais chaves estao carregadas:

``` dart
List<String> keys = NyLocalization.instance.getAllKeys();
// ['welcome', 'settings', 'navigation', ...]
```

### Mudar Localidade Sem Reiniciar

Se voce quiser mudar a localidade silenciosamente (sem reiniciar o app):

``` dart
await NyLocalization.instance.setLocale(locale: Locale('fr'));
```

Isso carrega o novo arquivo de idioma mas **nao** reinicia o app. Util quando voce quer lidar com atualizacoes da interface manualmente.

### Verificar Direcao RTL

``` dart
bool isRtl = NyLocalization.instance.isDirectionRTL(context);
```

### Acessar a Localidade Atual

``` dart
// Get the current language code
String code = NyLocalization.instance.languageCode;  // e.g., 'en'

// Get the current Locale object
Locale currentLocale = NyLocalization.instance.locale;

// Get Flutter localization delegates (used in MaterialApp)
var delegates = NyLocalization.instance.delegates;
```

### Referencia Completa da API

| Metodo / Propriedade | Retorno | Descricao |
|----------------------|---------|-----------|
| `instance` | `NyLocalization` | Instancia singleton |
| `translate(key, [arguments])` | `String` | Traduzir uma chave com argumentos opcionais |
| `hasTranslation(key)` | `bool` | Verificar se uma chave de traducao existe |
| `getAllKeys()` | `List<String>` | Obter todas as chaves de traducao carregadas |
| `setLanguage(context, {language, restart})` | `Future<void>` | Mudar idioma, opcionalmente reiniciar |
| `setLocale({locale})` | `Future<void>` | Mudar localidade sem reiniciar |
| `setDebugMissingKeys(enabled)` | `void` | Habilitar/desabilitar log de chaves ausentes |
| `isDirectionRTL(context)` | `bool` | Verificar se a direcao atual e RTL |
| `restart(context)` | `void` | Reiniciar o app |
| `languageCode` | `String` | Codigo do idioma atual |
| `locale` | `Locale` | Objeto Locale atual |
| `delegates` | `Iterable<LocalizationsDelegate>` | Delegates de localizacao do Flutter |

<div id="nylocalehelper"></div>

## NyLocaleHelper

`NyLocaleHelper` e uma classe utilitaria estatica para operacoes de localidade. Ela fornece metodos para detectar a localidade atual, verificar suporte RTL e criar objetos Locale.

``` dart
// Get the current system locale
Locale locale = NyLocaleHelper.getCurrentLocale(context: context);

// Get language and country codes
String langCode = NyLocaleHelper.getLanguageCode(context: context);  // 'en'
String? countryCode = NyLocaleHelper.getCountryCode(context: context);  // 'US' or null

// Check if current locale matches
bool isEnglish = NyLocaleHelper.matchesLocale(context, 'en');
bool isUsEnglish = NyLocaleHelper.matchesLocale(context, 'en', 'US');

// RTL detection
bool isRtl = NyLocaleHelper.isRtlLanguage('ar');  // true
bool currentIsRtl = NyLocaleHelper.isCurrentLocaleRtl(context: context);

// Get text direction
TextDirection direction = NyLocaleHelper.getTextDirection('ar');  // TextDirection.rtl
TextDirection currentDir = NyLocaleHelper.getCurrentTextDirection(context: context);

// Create a Locale from strings
Locale newLocale = NyLocaleHelper.toLocale('en', 'US');
```

### Referencia Completa da API

| Metodo | Retorno | Descricao |
|--------|---------|-----------|
| `getCurrentLocale({context})` | `Locale` | Obter a localidade atual do sistema |
| `getLanguageCode({context})` | `String` | Obter o codigo do idioma atual |
| `getCountryCode({context})` | `String?` | Obter o codigo do pais atual |
| `matchesLocale(context, languageCode, [countryCode])` | `bool` | Verificar se a localidade atual corresponde |
| `isRtlLanguage(languageCode)` | `bool` | Verificar se um codigo de idioma e RTL |
| `isCurrentLocaleRtl({context})` | `bool` | Verificar se a localidade atual e RTL |
| `getTextDirection(languageCode)` | `TextDirection` | Obter TextDirection para um idioma |
| `getCurrentTextDirection({context})` | `TextDirection` | Obter TextDirection para a localidade atual |
| `toLocale(languageCode, [countryCode])` | `Locale` | Criar um Locale a partir de strings |

A constante `rtlLanguages` contem: `ar`, `he`, `fa`, `ur`, `yi`, `ps`, `ku`, `sd`, `dv`.

<div id="changing-language-from-controller"></div>

## Mudando Idioma a partir de um Controller

Se voce usa controllers com suas paginas, voce pode mudar o idioma a partir do `NyController`:

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

O parametro `restartState` controla se o app reinicia apos mudar o idioma. Defina como `false` se voce quiser lidar com a atualizacao da interface por conta propria.
