# Upgrade Guide

---

<a name="section-1"></a>
- [O que há de Novo na v7](#whats-new "O que há de novo na v7")
- [Visão Geral das Mudanças que Quebram Compatibilidade](#breaking-changes "Visão geral das mudanças que quebram compatibilidade")
- [Abordagem de Migração Recomendada](#recommended-migration "Abordagem de migração recomendada")
- [Checklist Rápido de Migração](#checklist "Checklist rápido de migração")
- [Guia de Migração Passo a Passo](#migration-guide "Guia de migração")
  - [Passo 1: Atualizar Dependências](#step-1-dependencies "Atualizar dependências")
  - [Passo 2: Configuração de Ambiente](#step-2-environment "Configuração de ambiente")
  - [Passo 3: Atualizar main.dart](#step-3-main "Atualizar main.dart")
  - [Passo 4: Atualizar boot.dart](#step-4-boot "Atualizar boot.dart")
  - [Passo 5: Reorganizar Arquivos de Configuração](#step-5-config "Reorganizar arquivos de configuração")
  - [Passo 6: Atualizar AppProvider](#step-6-provider "Atualizar AppProvider")
  - [Passo 7: Atualizar Configuração de Temas](#step-7-theme "Atualizar configuração de temas")
  - [Passo 10: Migrar Widgets](#step-10-widgets "Migrar widgets")
  - [Passo 11: Atualizar Caminhos de Assets](#step-11-assets "Atualizar caminhos de assets")
- [Funcionalidades Removidas e Alternativas](#removed-features "Funcionalidades removidas e alternativas")
- [Referência de Classes Removidas](#deleted-classes "Referência de classes removidas")
- [Referência de Migração de Widgets](#widget-reference "Referência de migração de widgets")
- [Solução de Problemas](#troubleshooting "Solução de problemas")


<div id="whats-new"></div>

## O que há de Novo na v7

{{ config('app.name') }} v7 é uma versão major com melhorias significativas na experiência do desenvolvedor:

### Configuração de Ambiente Criptografada
- Variáveis de ambiente agora são criptografadas com XOR em tempo de build para segurança
- Novo `metro make:key` gera sua APP_KEY
- Novo `metro make:env` gera o arquivo criptografado `env.g.dart`
- Suporte para injeção de APP_KEY via `--dart-define` para pipelines CI/CD

### Processo de Boot Simplificado
- Novo padrão `BootConfig` substitui callbacks separados de setup/finished
- `Nylo.init()` mais limpo com parâmetro `env` para ambiente criptografado
- Hooks de ciclo de vida do app diretamente no main.dart

### Nova API nylo.configure()
- Método único consolida toda a configuração do app
- Sintaxe mais limpa substitui chamadas individuais `nylo.add*()`
- Métodos de ciclo de vida `setup()` e `boot()` separados nos providers

### NyPage para Páginas
- `NyPage` substitui `NyState` para widgets de página (sintaxe mais limpa)
- `view()` substitui o método `build()`
- Getter `get init =>` substitui os métodos `init()` e `boot()`
- `NyState` ainda está disponível para widgets stateful que não são páginas

### Sistema LoadingStyle
- Novo enum `LoadingStyle` para estados de carregamento consistentes
- Opções: `LoadingStyle.normal()`, `LoadingStyle.skeletonizer()`, `LoadingStyle.none()`
- Widgets de carregamento personalizados via `LoadingStyle.normal(child: ...)`

### Roteamento Type-Safe com RouteView
- `static RouteView path` substitui `static const path`
- Definições de rota type-safe com fábrica de widgets

### Suporte a Múltiplos Temas
- Registre múltiplos temas escuros e claros
- IDs de temas definidos no código em vez do arquivo `.env`
- Novo `NyThemeType.dark` / `NyThemeType.light` para classificação de temas
- API de tema preferido: `NyTheme.setPreferredDark()`, `NyTheme.setPreferredLight()`
- Enumeração de temas: `NyTheme.lightThemes()`, `NyTheme.darkThemes()`, `NyTheme.all()`

### Novos Comandos Metro
- `make:key` - Gerar APP_KEY para criptografia
- `make:env` - Gerar arquivo de ambiente criptografado
- `make:bottom_sheet_modal` - Criar modais de bottom sheet
- `make:button` - Criar botões personalizados

<a href="https://github.com/nylo-core/nylo/compare/6.x...7.x" target="_BLANK">Ver todas as mudanças no GitHub</a>

<div id="breaking-changes"></div>

## Visão Geral das Mudanças que Quebram Compatibilidade

| Mudança | v6 | v7 |
|--------|-----|-----|
| Widget Raiz do App | `LocalizedApp(child: Main(nylo))` | `Main(nylo)` (usa `NyApp.materialApp()`) |
| Classe de Estado da Página | `NyState` | `NyPage` para páginas |
| Método de View | `build()` | `view()` |
| Método Init | `init() async {}` / `boot() async {}` | `get init => () async {}` |
| Caminho da Rota | `static const path = '/home'` | `static RouteView path = ('/home', (_) => HomePage())` |
| Boot do Provider | `boot(Nylo nylo)` | `setup(Nylo nylo)` + `boot(Nylo nylo)` |
| Configuração | Chamadas individuais `nylo.add*()` | Chamada única `nylo.configure()` |
| IDs de Temas | Arquivo `.env` (`LIGHT_THEME_ID`, `DARK_THEME_ID`) | Código (`type: NyThemeType.dark`) |
| Widget de Carregamento | `useSkeletonizer` + `loading()` | Getter `LoadingStyle` |
| Local de Config | `lib/config/` | `lib/bootstrap/` (decoders, events, providers, theme) |
| Local de Assets | `public/` | `assets/` |

<div id="recommended-migration"></div>

## Abordagem de Migração Recomendada

Para projetos maiores, recomendamos criar um projeto v7 novo e migrar os arquivos:

1. Criar novo projeto v7: `git clone https://github.com/nylo-core/nylo.git my_app_v7 -b 7.x`
2. Copiar suas páginas, controllers, models e services
3. Atualizar a sintaxe conforme mostrado acima
4. Testar completamente

Isso garante que você tenha toda a estrutura e configurações mais recentes do boilerplate.

Se você estiver interessado em ver um diff das mudanças entre v6 e v7, pode ver a comparação no GitHub: <a href="https://github.com/nylo-core/nylo/compare/6.x...7.x" target="_BLANK">https://github.com/nylo-core/nylo/compare/6.x...7.x</a>

<div id="checklist"></div>

## Checklist Rápido de Migração

Use este checklist para acompanhar o progresso da sua migração:

- [ ] Atualizar `pubspec.yaml` (Dart >=3.10.7, Flutter >=3.24.0, nylo_framework: ^7.0.0)
- [ ] Executar `flutter pub get`
- [ ] Executar `metro make:key` para gerar APP_KEY
- [ ] Executar `metro make:env` para gerar ambiente criptografado
- [ ] Atualizar `main.dart` com parâmetro env e BootConfig
- [ ] Converter classe `Boot` para usar o padrão `BootConfig`
- [ ] Mover arquivos de config de `lib/config/` para `lib/bootstrap/`
- [ ] Criar novos arquivos de config (`lib/config/app.dart`, `lib/config/storage_keys.dart`, `lib/config/toast_notification.dart`)
- [ ] Atualizar `AppProvider` para usar `nylo.configure()`
- [ ] Remover `LIGHT_THEME_ID` e `DARK_THEME_ID` do `.env`
- [ ] Adicionar `type: NyThemeType.dark` às configurações de tema escuro
- [ ] Renomear `NyState` para `NyPage` em todos os widgets de página
- [ ] Alterar `build()` para `view()` em todas as páginas
- [ ] Alterar `init()/boot()` para `get init =>` em todas as páginas
- [ ] Atualizar `static const path` para `static RouteView path`
- [ ] Alterar `router.route()` para `router.add()` nas rotas
- [ ] Renomear widgets (NyListView -> CollectionView, etc.)
- [ ] Mover assets de `public/` para `assets/`
- [ ] Atualizar caminhos de assets no `pubspec.yaml`
- [ ] Remover imports do Firebase (se estiver usando - adicionar pacotes diretamente)
- [ ] Remover uso do NyDevPanel (usar Flutter DevTools)
- [ ] Executar `flutter pub get` e testar

<div id="migration-guide"></div>

## Guia de Migração Passo a Passo

<div id="step-1-dependencies"></div>

### Passo 1: Atualizar Dependências

Atualize seu `pubspec.yaml`:

``` yaml
environment:
  sdk: '>=3.10.7 <4.0.0'
  flutter: ">=3.24.0"

dependencies:
  nylo_framework: ^7.0.0
  # ... outras dependências
```

Execute `flutter pub get` para atualizar os pacotes.

<div id="step-2-environment"></div>

### Passo 2: Configuração de Ambiente

A v7 requer variáveis de ambiente criptografadas para maior segurança.

**1. Gerar APP_KEY:**

``` bash
metro make:key
```

Isso adiciona `APP_KEY` ao seu arquivo `.env`.

**2. Gerar env.g.dart criptografado:**

``` bash
metro make:env
```

Isso cria `lib/bootstrap/env.g.dart` contendo suas variáveis de ambiente criptografadas.

**3. Remover variáveis de tema descontinuadas do .env:**

``` bash
# Remova estas linhas do seu arquivo .env:
LIGHT_THEME_ID=...
DARK_THEME_ID=...
```

<div id="step-3-main"></div>

### Passo 3: Atualizar main.dart

**v6:**
``` dart
import 'package:nylo_framework/nylo_framework.dart';
import 'bootstrap/boot.dart';

void main() async {
  await Nylo.init(
    setup: Boot.nylo,
    setupFinished: Boot.finished,
  );
}
```

**v7:**
``` dart
import '/bootstrap/env.g.dart';
import 'package:nylo_framework/nylo_framework.dart';
import 'bootstrap/boot.dart';

void main() async {
  await Nylo.init(
    env: Env.get,
    setup: Boot.nylo(),
    appLifecycle: {
      // Opcional: Adicionar hooks de ciclo de vida do app
      // AppLifecycleState.resumed: () => print("App resumed"),
      // AppLifecycleState.paused: () => print("App paused"),
    },
  );
}
```

**Mudanças Principais:**
- Importar o `env.g.dart` gerado
- Passar `Env.get` para o parâmetro `env`
- `Boot.nylo` agora é `Boot.nylo()` (retorna `BootConfig`)
- `setupFinished` foi removido (tratado dentro do `BootConfig`)
- Hooks opcionais `appLifecycle` para mudanças de estado do app

<div id="step-4-boot"></div>

### Passo 4: Atualizar boot.dart

**v6:**
``` dart
class Boot {
  static Future<Nylo> nylo() async {
    WidgetsFlutterBinding.ensureInitialized();

    if (getEnv('SHOW_SPLASH_SCREEN', defaultValue: false)) {
      runApp(SplashScreen.app());
    }

    await _setup();
    return await bootApplication(providers);
  }

  static Future<void> finished(Nylo nylo) async {
    await bootFinished(nylo, providers);
    runApp(Main(nylo));
  }
}
```

**v7:**
``` dart
class Boot {
  static BootConfig nylo() => BootConfig(
        setup: () async {
          WidgetsFlutterBinding.ensureInitialized();

          if (AppConfig.showSplashScreen) {
            runApp(SplashScreen.app());
          }

          await _init();
          return await setupApplication(providers);
        },
        boot: (Nylo nylo) async {
          await bootFinished(nylo, providers);
          runApp(Main(nylo));
        },
      );
}
```

**Mudanças Principais:**
- Retorna `BootConfig` em vez de `Future<Nylo>`
- `setup` e `finished` combinados em um único objeto `BootConfig`
- `getEnv('SHOW_SPLASH_SCREEN')` -> `AppConfig.showSplashScreen`
- `bootApplication` -> `setupApplication`

<div id="step-5-config"></div>

### Passo 5: Reorganizar Arquivos de Configuração

A v7 reorganiza os arquivos de configuração para melhor estrutura:

| Localização v6 | Localização v7 | Ação |
|-------------|-------------|--------|
| `lib/config/decoders.dart` | `lib/bootstrap/decoders.dart` | Mover |
| `lib/config/events.dart` | `lib/bootstrap/events.dart` | Mover |
| `lib/config/providers.dart` | `lib/bootstrap/providers.dart` | Mover |
| `lib/config/theme.dart` | `lib/bootstrap/theme.dart` | Mover |
| `lib/config/keys.dart` | `lib/config/storage_keys.dart` | Renomear e Refatorar |
| (novo) | `lib/config/app.dart` | Criar |
| (novo) | `lib/config/toast_notification.dart` | Criar |

**Criar lib/config/app.dart:**

Referência: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/app.dart" target="_BLANK">App Config</a>

``` dart
class AppConfig {
  // O nome da aplicação.
  static final String appName = getEnv('APP_NAME', defaultValue: 'Nylo');

  // A versão da aplicação.
  static final String version = getEnv('APP_VERSION', defaultValue: '1.0.0');

  // Adicionar outras configurações do app aqui
}
```

**Criar lib/config/storage_keys.dart:**

Referência: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/storage_keys.dart" target="_BLANK">Storage Keys</a>

``` dart
final class StorageKeysConfig {
  // Definir as chaves que deseja sincronizar no boot
  static syncedOnBoot() => () async {
        return [
          auth,
          bearerToken,
          // coins.defaultValue(10), // dar ao usuário 10 moedas por padrão
        ];
      };

  static StorageKey auth = 'SK_USER';

  static StorageKey bearerToken = 'SK_BEARER_TOKEN';

  // static StorageKey coins = 'SK_COINS';

  /// Adicione suas storage keys aqui...
}
```

**Criar lib/config/toast_notification.dart:**

Referência: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/toast_notification.dart" target="_BLANK">Toast Notification Config</a>

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class ToastNotificationConfig {
  static Map<ToastNotificationStyleMetaHelper, ToastMeta> styles = {
    // Personalizar estilos de toast aqui
  };
}
```

<div id="step-6-provider"></div>

### Passo 6: Atualizar AppProvider

**v6:**
``` dart
class AppProvider implements NyProvider {
  @override
  boot(Nylo nylo) async {
    await NyLocalization.instance.init(
      localeType: localeType,
      languageCode: languageCode,
      assetsDirectory: assetsDirectory,
    );

    nylo.addLoader(loader);
    nylo.addLogo(logo);
    nylo.addThemes(appThemes);
    nylo.addToastNotification(getToastNotificationWidget);
    nylo.addValidationRules(validationRules);
    nylo.addModelDecoders(modelDecoders);
    nylo.addControllers(controllers);
    nylo.addApiDecoders(apiDecoders);
    nylo.useErrorStack();
    nylo.addAuthKey(Keys.auth);
    await nylo.syncKeys(Keys.syncedOnBoot);

    return nylo;
  }

  @override
  afterBoot(Nylo nylo) async {}
}
```

**v7:**
``` dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    await nylo.configure(
      localization: NyLocalizationConfig(
        languageCode: LocalizationConfig.languageCode,
        localeType: LocalizationConfig.localeType,
        assetsDirectory: LocalizationConfig.assetsDirectory,
      ),
      loader: DesignConfig.loader,
      logo: DesignConfig.logo,
      themes: appThemes,
      initialThemeId: 'light_theme',
      toastNotifications: ToastNotificationConfig.styles,
      modelDecoders: modelDecoders,
      controllers: controllers,
      apiDecoders: apiDecoders,
      authKey: StorageKeysConfig.auth,
      syncKeys: StorageKeysConfig.syncedOnBoot,
      useErrorStack: true,
    );

    return nylo;
  }

  @override
  boot(Nylo nylo) async {}
}
```

**Mudanças Principais:**
- `boot()` agora é `setup()` para configuração inicial
- `boot()` agora é usado para lógica pós-setup (anteriormente `afterBoot`)
- Todas as chamadas `nylo.add*()` consolidadas em um único `nylo.configure()`
- Localização usa o objeto `NyLocalizationConfig`

<div id="step-7-theme"></div>

### Passo 7: Atualizar Configuração de Temas

**v6 (arquivo .env):**
``` bash
LIGHT_THEME_ID=default_light_theme
DARK_THEME_ID=default_dark_theme
```

**v6 (theme.dart):**
``` dart
final List<BaseThemeConfig> appThemes = [
  BaseThemeConfig(
    id: getEnv('LIGHT_THEME_ID'),
    description: "Light Theme",
    theme: lightTheme(),
    colors: LightThemeColors(),
  ),
  BaseThemeConfig(
    id: getEnv('DARK_THEME_ID'),
    description: "Dark Theme",
    theme: darkTheme(),
    colors: DarkThemeColors(),
  ),
];
```

**v7 (theme.dart):**
``` dart
final List<BaseThemeConfig<ColorStyles>> appThemes = [
  BaseThemeConfig<ColorStyles>(
    id: 'light_theme',
    theme: lightTheme,
    colors: LightThemeColors(),
    type: NyThemeType.light,
  ),
  BaseThemeConfig<ColorStyles>(
    id: 'dark_theme',
    theme: darkTheme,
    colors: DarkThemeColors(),
    type: NyThemeType.dark,
  ),
];
```

**Mudanças Principais:**
- Remover `LIGHT_THEME_ID` e `DARK_THEME_ID` do `.env`
- Definir IDs de temas diretamente no código
- Adicionar `type: NyThemeType.dark` a todas as configurações de tema escuro
- Temas claros usam `NyThemeType.light` por padrão

**Novos Métodos da API de Temas (v7):**
``` dart
// Definir e lembrar tema preferido
NyTheme.set(context, id: 'dark_theme', remember: true);

// Definir temas preferidos para seguir o sistema
NyTheme.setPreferredDark('dark_theme');
NyTheme.setPreferredLight('light_theme');

// Obter IDs de temas preferidos
String? darkId = NyTheme.preferredDarkId();
String? lightId = NyTheme.preferredLightId();

// Enumeração de temas
List<BaseThemeConfig> lights = NyTheme.lightThemes();
List<BaseThemeConfig> darks = NyTheme.darkThemes();
List<BaseThemeConfig> all = NyTheme.all();
BaseThemeConfig? theme = NyTheme.getById('dark_theme');
List<BaseThemeConfig> byType = NyTheme.getByType(NyThemeType.dark);

// Limpar preferências salvas
NyTheme.clearSavedTheme();
```

<div id="step-10-widgets"></div>

### Passo 10: Migrar Widgets

#### NyListView -> CollectionView

**v6:**
``` dart
NyListView(
  child: (context, data) {
    return ListTile(title: Text(data.name));
  },
  data: () async => await api.getUsers(),
  loading: CircularProgressIndicator(),
)
```

**v7:**
``` dart
CollectionView<User>(
  data: () async => await api.getUsers(),
  builder: (context, item) => ListTile(
    title: Text(item.data.name),
  ),
  loadingStyle: LoadingStyle.normal(),
)

// Com paginação (pull to refresh):
CollectionView<User>.pullable(
  data: (page) async => await api.getUsers(page: page),
  builder: (context, item) => ListTile(
    title: Text(item.data.name),
  ),
)
```

#### NyFutureBuilder -> FutureWidget

**v6:**
``` dart
NyFutureBuilder(
  future: fetchData(),
  child: (context, data) => Text(data),
  loading: CircularProgressIndicator(),
)
```

**v7:**
``` dart
FutureWidget<String>(
  future: fetchData(),
  child: (context, data) => Text(data ?? ''),
  loadingStyle: LoadingStyle.normal(),
)
```

#### NyTextField -> InputField

**v6:**
``` dart
NyTextField(
  controller: _controller,
  validationRules: "not_empty|email",
)
```

**v7:**
``` dart
InputField(
  controller: _controller,
  formValidator: FormValidator
                  .notEmpty()
                  .email(),
),
```

#### NyRichText -> StyledText

**v6:**
``` dart
NyRichText(children: [
	Text("Hello", style: TextStyle(color: Colors.yellow)),
	Text(" WORLD ", style: TextStyle(color: Colors.blue)),
	Text("!", style: TextStyle(color: Colors.red)),
]),
```

**v7:**
``` dart
StyledText.template(
  "@{{Hello}} @{{WORLD}}@{{!}}",
  styles: {
    "Hello": TextStyle(color: Colors.yellow),
    "WORLD": TextStyle(color: Colors.blue),
    "!": TextStyle(color: Colors.red),
  },
)
```

#### NyLanguageSwitcher -> LanguageSwitcher

**v6:**
``` dart
NyLanguageSwitcher(
  onLanguageChange: (locale) => print(locale),
)
```

**v7:**
``` dart
LanguageSwitcher(
  onLanguageChange: (locale) => print(locale),
)
```

<div id="step-11-assets"></div>

### Passo 11: Atualizar Caminhos de Assets

A v7 altera o diretório de assets de `public/` para `assets/`:

**1. Mover suas pastas de assets:**
``` bash
# Mover diretórios
mv public/fonts assets/fonts
mv public/images assets/images
mv public/app_icon assets/app_icon
```

**2. Atualizar pubspec.yaml:**

**v6:**
``` yaml
flutter:
  assets:
    - public/fonts/
    - public/images/
    - public/app_icon/
```

**v7:**
``` yaml
flutter:
  assets:
    - assets/fonts/
    - assets/images/
    - assets/app_icon/
```

**3. Atualizar quaisquer referências de assets no código:**

**v6:**
``` dart
Image.asset('public/images/logo.png')
```

**v7:**
``` dart
Image.asset('assets/images/logo.png')
```

<div id="removed-features"></div>

### Widget LocalizedApp - Removido

Referência: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/resources/widgets/main_widget.dart" target="_BLANK">Main Widget</a>

**Migração:** Use `Main(nylo)` diretamente. O `NyApp.materialApp()` trata a localização internamente.

**v6:**
``` dart
runApp(LocalizedApp(child: Main(nylo)));
```

**v7:**
``` dart
runApp(Main(nylo));
```

<div id="deleted-classes"></div>

## Referência de Classes Removidas

| Classe Removida | Alternativa |
|---------------|-------------|
| `NyTextStyle` | Usar `TextStyle` do Flutter diretamente |
| `NyBaseApiService` | Usar `DioApiService` |
| `BaseColorStyles` | Usar `ThemeColor` |
| `LocalizedApp` | Usar `Main(nylo)` diretamente |
| `NyException` | Usar exceções padrão do Dart |
| `PushNotification` | Usar `flutter_local_notifications` diretamente |
| `PushNotificationAttachments` | Usar `flutter_local_notifications` diretamente |

<div id="widget-reference"></div>

## Referência de Migração de Widgets

### Widgets Renomeados

| Widget v6 | Widget v7 | Notas |
|-----------|-----------|-------|
| `NyListView` | `CollectionView` | Nova API com `builder` em vez de `child` |
| `NyFutureBuilder` | `FutureWidget` | Widget assíncrono simplificado |
| `NyTextField` | `InputField` | Usa `FormValidator` |
| `NyLanguageSwitcher` | `LanguageSwitcher` | Mesma API |
| `NyRichText` | `StyledText` | Mesma API |
| `NyFader` | `FadeOverlay` | Mesma API |

### Widgets Removidos (Sem Substituição Direta)

| Widget Removido | Alternativa |
|----------------|-------------|
| `NyPullToRefresh` | Usar `CollectionView.pullable()` |

### Exemplos de Migração de Widgets

**NyPullToRefresh -> CollectionView.pullable():**

**v6:**
``` dart
NyPullToRefresh(
  child: (context, data) => ListTile(title: Text(data.name)),
  data: (page) async => await fetchData(page),
)
```

**v7:**
``` dart
CollectionView<MyModel>.pullable(
  data: (page) async => await fetchData(page),
  builder: (context, item) => ListTile(title: Text(item.data.name)),
)
```

**NyFader -> AnimatedOpacity:**

**v6:**
``` dart
NyFader(
  child: MyWidget(),
)
```

**v7:**
``` dart
FadeOverlay.bottom(
  child: MyWidget(),
);
```

<div id="troubleshooting"></div>

## Solução de Problemas

### "Env.get not found" ou "Env is not defined"

**Solução:** Execute os comandos de geração de ambiente:
``` bash
metro make:key
metro make:env
```
Depois importe o arquivo gerado no `main.dart`:
``` dart
import '/bootstrap/env.g.dart';
```

### "Theme not applying" ou "Dark theme not working"

**Solução:** Certifique-se de que os temas escuros tenham `type: NyThemeType.dark`:
``` dart
BaseThemeConfig(
  id: 'dark_theme',
  description: "Dark Theme",
  theme: darkTheme(),
  colors: DarkThemeColors(),
  type: NyThemeType.dark, // Adicione esta linha
),
```

### "LocalizedApp not found"

Referência: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/resources/widgets/main_widget.dart" target="_BLANK">Main Widget</a>

**Solução:** `LocalizedApp` foi removido. Altere:
``` dart
// De:
runApp(LocalizedApp(child: Main(nylo)));

// Para:
runApp(Main(nylo));
```

### "router.route is not defined"

**Solução:** Use `router.add()` em vez disso:
``` dart
// De:
router.route(HomePage.path, (context) => HomePage());

// Para:
router.add(HomePage.path);
```

### "NyListView not found"

**Solução:** `NyListView` agora é `CollectionView`:
``` dart
// De:
NyListView(...)

// Para:
CollectionView<MyModel>(...)
```

### Assets não carregando (imagens, fontes)

**Solução:** Atualize os caminhos de assets de `public/` para `assets/`:
1. Mover arquivos: `mv public/* assets/`
2. Atualizar caminhos no `pubspec.yaml`
3. Atualizar referências no código

### "init() must return a value of type Future"

**Solução:** Altere para a sintaxe de getter:
``` dart
// De:
@override
init() async { ... }

// Para:
@override
get init => () async { ... };
```

---

**Precisa de ajuda?** Consulte a [Documentação do Nylo](https://nylo.dev/docs/7.x) ou abra uma issue no [GitHub](https://github.com/nylo-core/nylo/issues).
