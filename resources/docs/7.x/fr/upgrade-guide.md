# Guide de mise a jour

---

<a name="section-1"></a>
- [Nouveautes de la v7](#whats-new "Nouveautes de la v7")
- [Apercu des changements majeurs](#breaking-changes "Apercu des changements majeurs")
- [Approche de migration recommandee](#recommended-migration "Approche de migration recommandee")
- [Liste de verification rapide pour la migration](#checklist "Liste de verification rapide pour la migration")
- [Guide de migration etape par etape](#migration-guide "Guide de migration etape par etape")
  - [Etape 1 : Mise a jour des dependances](#step-1-dependencies "Etape 1 : Mise a jour des dependances")
  - [Etape 2 : Configuration de l'environnement](#step-2-environment "Etape 2 : Configuration de l'environnement")
  - [Etape 3 : Mise a jour de main.dart](#step-3-main "Etape 3 : Mise a jour de main.dart")
  - [Etape 4 : Mise a jour de boot.dart](#step-4-boot "Etape 4 : Mise a jour de boot.dart")
  - [Etape 5 : Reorganisation des fichiers de configuration](#step-5-config "Etape 5 : Reorganisation des fichiers de configuration")
  - [Etape 6 : Mise a jour de AppProvider](#step-6-provider "Etape 6 : Mise a jour de AppProvider")
  - [Etape 7 : Mise a jour de la configuration des themes](#step-7-theme "Etape 7 : Mise a jour de la configuration des themes")
  - [Etape 10 : Migration des widgets](#step-10-widgets "Etape 10 : Migration des widgets")
  - [Etape 11 : Mise a jour des chemins des ressources](#step-11-assets "Etape 11 : Mise a jour des chemins des ressources")
- [Fonctionnalites supprimees et alternatives](#removed-features "Fonctionnalites supprimees et alternatives")
- [Reference des classes supprimees](#deleted-classes "Reference des classes supprimees")
- [Reference de migration des widgets](#widget-reference "Reference de migration des widgets")
- [Depannage](#troubleshooting "Depannage")


<div id="whats-new"></div>

## Nouveautes de la v7

{{ config('app.name') }} v7 est une version majeure avec des ameliorations significatives de l'experience developpeur :

### Configuration d'environnement chiffree
- Les variables d'environnement sont desormais chiffrees en XOR au moment de la compilation pour plus de securite
- Nouvelle commande `metro make:key` pour generer votre APP_KEY
- Nouvelle commande `metro make:env` pour generer le fichier `env.g.dart` chiffre
- Prise en charge de l'injection de APP_KEY via `--dart-define` pour les pipelines CI/CD

### Processus de demarrage simplifie
- Le nouveau modele `BootConfig` remplace les callbacks setup/finished separes
- Syntaxe plus claire pour `Nylo.init()` avec le parametre `env` pour l'environnement chiffre
- Hooks du cycle de vie de l'application directement dans main.dart

### Nouvelle API nylo.configure()
- Une methode unique consolide toute la configuration de l'application
- Syntaxe plus claire remplacant les appels individuels `nylo.add*()`
- Methodes de cycle de vie `setup()` et `boot()` separees dans les providers

### NyPage pour les pages
- `NyPage` remplace `NyState` pour les widgets de page (syntaxe plus claire)
- `view()` remplace la methode `build()`
- Le getter `get init =>` remplace les methodes `init()` et `boot()`
- `NyState` reste disponible pour les widgets stateful qui ne sont pas des pages

### Systeme LoadingStyle
- Nouvelle enumeration `LoadingStyle` pour des etats de chargement coherents
- Options : `LoadingStyle.normal()`, `LoadingStyle.skeletonizer()`, `LoadingStyle.none()`
- Widgets de chargement personnalises via `LoadingStyle.normal(child: ...)`

### Routage type-safe avec RouteView
- `static RouteView path` remplace `static const path`
- Definitions de routes type-safe avec factory de widget

### Support multi-themes
- Enregistrement de plusieurs themes sombres et clairs
- Identifiants de theme definis dans le code au lieu du fichier `.env`
- Nouveaux `NyThemeType.dark` / `NyThemeType.light` pour la classification des themes
- API de theme prefere : `NyTheme.setPreferredDark()`, `NyTheme.setPreferredLight()`
- Enumeration des themes : `NyTheme.lightThemes()`, `NyTheme.darkThemes()`, `NyTheme.all()`

### Nouvelles commandes Metro
- `make:key` - Generer APP_KEY pour le chiffrement
- `make:env` - Generer le fichier d'environnement chiffre
- `make:bottom_sheet_modal` - Creer des modales en feuille de fond
- `make:button` - Creer des boutons personnalises

<a href="https://github.com/nylo-core/nylo/compare/6.x...7.x" target="_BLANK">Voir tous les changements sur GitHub</a>

<div id="breaking-changes"></div>

## Apercu des changements majeurs

| Changement | v6 | v7 |
|--------|-----|-----|
| Widget racine de l'application | `LocalizedApp(child: Main(nylo))` | `Main(nylo)` (utilise `NyApp.materialApp()`) |
| Classe d'etat de page | `NyState` | `NyPage` pour les pages |
| Methode view | `build()` | `view()` |
| Methode init | `init() async {}` / `boot() async {}` | `get init => () async {}` |
| Chemin de route | `static const path = '/home'` | `static RouteView path = ('/home', (_) => HomePage())` |
| Boot du provider | `boot(Nylo nylo)` | `setup(Nylo nylo)` + `boot(Nylo nylo)` |
| Configuration | Appels individuels `nylo.add*()` | Appel unique `nylo.configure()` |
| IDs de theme | Fichier `.env` (`LIGHT_THEME_ID`, `DARK_THEME_ID`) | Code (`type: NyThemeType.dark`) |
| Widget de chargement | `useSkeletonizer` + `loading()` | Getter `LoadingStyle` |
| Emplacement config | `lib/config/` | `lib/bootstrap/` (decoders, events, providers, theme) |
| Emplacement ressources | `public/` | `assets/` |

<div id="recommended-migration"></div>

## Approche de migration recommandee

Pour les projets plus importants, nous vous recommandons de creer un nouveau projet v7 et de migrer les fichiers :

1. Creer un nouveau projet v7 : `git clone https://github.com/nylo-core/nylo.git my_app_v7 -b 7.x`
2. Copier vos pages, controllers, models et services
3. Mettre a jour la syntaxe comme indique ci-dessus
4. Tester minutieusement

Ceci vous assure d'avoir toute la derniere structure de boilerplate et les configurations.

Si vous souhaitez voir un diff des changements entre v6 et v7, vous pouvez consulter la comparaison sur GitHub : <a href="https://github.com/nylo-core/nylo/compare/6.x...7.x" target="_BLANK">https://github.com/nylo-core/nylo/compare/6.x...7.x</a>

<div id="checklist"></div>

## Liste de verification rapide pour la migration

Utilisez cette liste de verification pour suivre votre progression de migration :

- [ ] Mettre a jour `pubspec.yaml` (Dart >=3.10.7, Flutter >=3.24.0, nylo_framework: ^7.0.0)
- [ ] Executer `flutter pub get`
- [ ] Executer `metro make:key` pour generer APP_KEY
- [ ] Executer `metro make:env` pour generer l'environnement chiffre
- [ ] Mettre a jour `main.dart` avec le parametre env et BootConfig
- [ ] Convertir la classe `Boot` pour utiliser le modele `BootConfig`
- [ ] Deplacer les fichiers de configuration de `lib/config/` vers `lib/bootstrap/`
- [ ] Creer les nouveaux fichiers de configuration (`lib/config/app.dart`, `lib/config/storage_keys.dart`, `lib/config/toast_notification.dart`)
- [ ] Mettre a jour `AppProvider` pour utiliser `nylo.configure()`
- [ ] Supprimer `LIGHT_THEME_ID` et `DARK_THEME_ID` du fichier `.env`
- [ ] Ajouter `type: NyThemeType.dark` aux configurations de themes sombres
- [ ] Renommer `NyState` en `NyPage` pour tous les widgets de page
- [ ] Changer `build()` en `view()` dans toutes les pages
- [ ] Changer `init()/boot()` en `get init =>` dans toutes les pages
- [ ] Mettre a jour `static const path` en `static RouteView path`
- [ ] Changer `router.route()` en `router.add()` dans les routes
- [ ] Renommer les widgets (NyListView -> CollectionView, etc.)
- [ ] Deplacer les ressources de `public/` vers `assets/`
- [ ] Mettre a jour les chemins des ressources dans `pubspec.yaml`
- [ ] Supprimer les imports Firebase (si utilises - ajoutez les packages directement)
- [ ] Supprimer l'utilisation de NyDevPanel (utilisez Flutter DevTools)
- [ ] Executer `flutter pub get` et tester

<div id="migration-guide"></div>

## Guide de migration etape par etape

<div id="step-1-dependencies"></div>

### Etape 1 : Mise a jour des dependances

Mettez a jour votre `pubspec.yaml` :

``` yaml
environment:
  sdk: '>=3.10.7 <4.0.0'
  flutter: ">=3.24.0"

dependencies:
  nylo_framework: ^7.0.0
  # ... autres dependances
```

Executez `flutter pub get` pour mettre a jour les packages.

<div id="step-2-environment"></div>

### Etape 2 : Configuration de l'environnement

La v7 necessite des variables d'environnement chiffrees pour une securite amelioree.

**1. Generer APP_KEY :**

``` bash
metro make:key
```

Ceci ajoute `APP_KEY` a votre fichier `.env`.

**2. Generer env.g.dart chiffre :**

``` bash
metro make:env
```

Ceci cree `lib/bootstrap/env.g.dart` contenant vos variables d'environnement chiffrees.

**3. Supprimer les variables de theme obsoletes du fichier .env :**

``` bash
# Supprimez ces lignes de votre fichier .env :
LIGHT_THEME_ID=...
DARK_THEME_ID=...
```

<div id="step-3-main"></div>

### Etape 3 : Mise a jour de main.dart

**v6 :**
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

**v7 :**
``` dart
import '/bootstrap/env.g.dart';
import 'package:nylo_framework/nylo_framework.dart';
import 'bootstrap/boot.dart';

void main() async {
  await Nylo.init(
    env: Env.get,
    setup: Boot.nylo(),
    appLifecycle: {
      // Optionnel : Ajoutez des hooks du cycle de vie de l'application
      // AppLifecycleState.resumed: () => print("App resumed"),
      // AppLifecycleState.paused: () => print("App paused"),
    },
  );
}
```

**Changements cles :**
- Importer le fichier `env.g.dart` genere
- Passer `Env.get` au parametre `env`
- `Boot.nylo` devient `Boot.nylo()` (retourne `BootConfig`)
- `setupFinished` est supprime (gere dans `BootConfig`)
- Hooks `appLifecycle` optionnels pour les changements d'etat de l'application

<div id="step-4-boot"></div>

### Etape 4 : Mise a jour de boot.dart

**v6 :**
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

**v7 :**
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

**Changements cles :**
- Retourne `BootConfig` au lieu de `Future<Nylo>`
- `setup` et `finished` combines en un seul objet `BootConfig`
- `getEnv('SHOW_SPLASH_SCREEN')` -> `AppConfig.showSplashScreen`
- `bootApplication` -> `setupApplication`

<div id="step-5-config"></div>

### Etape 5 : Reorganisation des fichiers de configuration

La v7 reorganise les fichiers de configuration pour une meilleure structure :

| Emplacement v6 | Emplacement v7 | Action |
|-------------|-------------|--------|
| `lib/config/decoders.dart` | `lib/bootstrap/decoders.dart` | Deplacer |
| `lib/config/events.dart` | `lib/bootstrap/events.dart` | Deplacer |
| `lib/config/providers.dart` | `lib/bootstrap/providers.dart` | Deplacer |
| `lib/config/theme.dart` | `lib/bootstrap/theme.dart` | Deplacer |
| `lib/config/keys.dart` | `lib/config/storage_keys.dart` | Renommer et refactoriser |
| (nouveau) | `lib/config/app.dart` | Creer |
| (nouveau) | `lib/config/toast_notification.dart` | Creer |

**Creer lib/config/app.dart :**

Reference : <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/app.dart" target="_BLANK">App Config</a>

``` dart
class AppConfig {
  // Le nom de l'application.
  static final String appName = getEnv('APP_NAME', defaultValue: 'Nylo');

  // La version de l'application.
  static final String version = getEnv('APP_VERSION', defaultValue: '1.0.0');

  // Ajoutez d'autres configurations d'application ici
}
```

**Creer lib/config/storage_keys.dart :**

Reference : <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/storage_keys.dart" target="_BLANK">Storage Keys</a>

``` dart
final class StorageKeysConfig {
  // Definir les cles a synchroniser au demarrage
  static syncedOnBoot() => () async {
        return [
          auth,
          bearerToken,
          // coins.defaultValue(10), // donner 10 coins par defaut a l'utilisateur
        ];
      };

  static StorageKey auth = 'SK_USER';

  static StorageKey bearerToken = 'SK_BEARER_TOKEN';

  // static StorageKey coins = 'SK_COINS';

  /// Ajoutez vos cles de stockage ici...
}
```

**Creer lib/config/toast_notification.dart :**

Reference : <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/toast_notification.dart" target="_BLANK">Toast Notification Config</a>

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class ToastNotificationConfig {
  static Map<ToastNotificationStyleMetaHelper, ToastMeta> styles = {
    // Personnalisez les styles de toast ici
  };
}
```

<div id="step-6-provider"></div>

### Etape 6 : Mise a jour de AppProvider

**v6 :**
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

**v7 :**
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

**Changements cles :**
- `boot()` devient `setup()` pour la configuration initiale
- `boot()` est maintenant utilise pour la logique post-configuration (anciennement `afterBoot`)
- Tous les appels `nylo.add*()` consolides en un seul `nylo.configure()`
- La localisation utilise l'objet `NyLocalizationConfig`

<div id="step-7-theme"></div>

### Etape 7 : Mise a jour de la configuration des themes

**v6 (fichier .env) :**
``` bash
LIGHT_THEME_ID=default_light_theme
DARK_THEME_ID=default_dark_theme
```

**v6 (theme.dart) :**
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

**v7 (theme.dart) :**
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

**Changements cles :**
- Supprimer `LIGHT_THEME_ID` et `DARK_THEME_ID` du fichier `.env`
- Definir les IDs de theme directement dans le code
- Ajouter `type: NyThemeType.dark` a toutes les configurations de themes sombres
- Les themes clairs utilisent par defaut `NyThemeType.light`

**Nouvelles methodes de l'API Theme (v7) :**
``` dart
// Definir et memoriser le theme prefere
NyTheme.set(context, id: 'dark_theme', remember: true);

// Definir les themes preferes pour suivre le systeme
NyTheme.setPreferredDark('dark_theme');
NyTheme.setPreferredLight('light_theme');

// Obtenir les IDs des themes preferes
String? darkId = NyTheme.preferredDarkId();
String? lightId = NyTheme.preferredLightId();

// Enumeration des themes
List<BaseThemeConfig> lights = NyTheme.lightThemes();
List<BaseThemeConfig> darks = NyTheme.darkThemes();
List<BaseThemeConfig> all = NyTheme.all();
BaseThemeConfig? theme = NyTheme.getById('dark_theme');
List<BaseThemeConfig> byType = NyTheme.getByType(NyThemeType.dark);

// Effacer les preferences sauvegardees
NyTheme.clearSavedTheme();
```

<div id="step-10-widgets"></div>

### Etape 10 : Migration des widgets

#### NyListView -> CollectionView

**v6 :**
``` dart
NyListView(
  child: (context, data) {
    return ListTile(title: Text(data.name));
  },
  data: () async => await api.getUsers(),
  loading: CircularProgressIndicator(),
)
```

**v7 :**
``` dart
CollectionView<User>(
  data: () async => await api.getUsers(),
  builder: (context, item) => ListTile(
    title: Text(item.data.name),
  ),
  loadingStyle: LoadingStyle.normal(),
)

// Avec pagination (tirer pour actualiser) :
CollectionView<User>.pullable(
  data: (page) async => await api.getUsers(page: page),
  builder: (context, item) => ListTile(
    title: Text(item.data.name),
  ),
)
```

#### NyFutureBuilder -> FutureWidget

**v6 :**
``` dart
NyFutureBuilder(
  future: fetchData(),
  child: (context, data) => Text(data),
  loading: CircularProgressIndicator(),
)
```

**v7 :**
``` dart
FutureWidget<String>(
  future: fetchData(),
  child: (context, data) => Text(data ?? ''),
  loadingStyle: LoadingStyle.normal(),
)
```

#### NyTextField -> InputField

**v6 :**
``` dart
NyTextField(
  controller: _controller,
  validationRules: "not_empty|email",
)
```

**v7 :**
``` dart
InputField(
  controller: _controller,
  formValidator: FormValidator
                  .notEmpty()
                  .email(),
),
```

#### NyRichText -> StyledText

**v6 :**
``` dart
NyRichText(children: [
	Text("Hello", style: TextStyle(color: Colors.yellow)),
	Text(" WORLD ", style: TextStyle(color: Colors.blue)),
	Text("!", style: TextStyle(color: Colors.red)),
]),
```

**v7 :**
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

**v6 :**
``` dart
NyLanguageSwitcher(
  onLanguageChange: (locale) => print(locale),
)
```

**v7 :**
``` dart
LanguageSwitcher(
  onLanguageChange: (locale) => print(locale),
)
```

<div id="step-11-assets"></div>

### Etape 11 : Mise a jour des chemins des ressources

La v7 change le repertoire des ressources de `public/` a `assets/` :

**1. Deplacez vos dossiers de ressources :**
``` bash
# Deplacer les repertoires
mv public/fonts assets/fonts
mv public/images assets/images
mv public/app_icon assets/app_icon
```

**2. Mettez a jour pubspec.yaml :**

**v6 :**
``` yaml
flutter:
  assets:
    - public/fonts/
    - public/images/
    - public/app_icon/
```

**v7 :**
``` yaml
flutter:
  assets:
    - assets/fonts/
    - assets/images/
    - assets/app_icon/
```

**3. Mettez a jour toutes les references aux ressources dans le code :**

**v6 :**
``` dart
Image.asset('public/images/logo.png')
```

**v7 :**
``` dart
Image.asset('assets/images/logo.png')
```

<div id="removed-features"></div>

### Widget LocalizedApp - Supprime

Reference : <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/resources/widgets/main_widget.dart" target="_BLANK">Main Widget</a>

**Migration :** Utilisez `Main(nylo)` directement. Le `NyApp.materialApp()` gere la localisation en interne.

**v6 :**
``` dart
runApp(LocalizedApp(child: Main(nylo)));
```

**v7 :**
``` dart
runApp(Main(nylo));
```

<div id="deleted-classes"></div>

## Reference des classes supprimees

| Classe supprimee | Alternative |
|---------------|-------------|
| `NyTextStyle` | Utilisez le `TextStyle` de Flutter directement |
| `NyBaseApiService` | Utilisez `DioApiService` |
| `BaseColorStyles` | Utilisez `ThemeColor` |
| `LocalizedApp` | Utilisez `Main(nylo)` directement |
| `NyException` | Utilisez les exceptions Dart standard |
| `PushNotification` | Utilisez `flutter_local_notifications` directement |
| `PushNotificationAttachments` | Utilisez `flutter_local_notifications` directement |

<div id="widget-reference"></div>

## Reference de migration des widgets

### Widgets renommes

| Widget v6 | Widget v7 | Notes |
|-----------|-----------|-------|
| `NyListView` | `CollectionView` | Nouvelle API avec `builder` au lieu de `child` |
| `NyFutureBuilder` | `FutureWidget` | Widget asynchrone simplifie |
| `NyTextField` | `InputField` | Utilise `FormValidator` |
| `NyLanguageSwitcher` | `LanguageSwitcher` | Meme API |
| `NyRichText` | `StyledText` | Meme API |
| `NyFader` | `FadeOverlay` | Meme API |

### Widgets supprimes (pas de remplacement direct)

| Widget supprime | Alternative |
|----------------|-------------|
| `NyPullToRefresh` | Utilisez `CollectionView.pullable()` |

### Exemples de migration de widgets

**NyPullToRefresh -> CollectionView.pullable() :**

**v6 :**
``` dart
NyPullToRefresh(
  child: (context, data) => ListTile(title: Text(data.name)),
  data: (page) async => await fetchData(page),
)
```

**v7 :**
``` dart
CollectionView<MyModel>.pullable(
  data: (page) async => await fetchData(page),
  builder: (context, item) => ListTile(title: Text(item.data.name)),
)
```

**NyFader -> FadeOverlay :**

**v6 :**
``` dart
NyFader(
  child: MyWidget(),
)
```

**v7 :**
``` dart
FadeOverlay.bottom(
  child: MyWidget(),
);
```

<div id="troubleshooting"></div>

## Depannage

### "Env.get not found" ou "Env is not defined"

**Solution :** Executez les commandes de generation d'environnement :
``` bash
metro make:key
metro make:env
```
Ensuite, importez le fichier genere dans `main.dart` :
``` dart
import '/bootstrap/env.g.dart';
```

### "Theme not applying" ou "Dark theme not working"

**Solution :** Assurez-vous que les themes sombres ont `type: NyThemeType.dark` :
``` dart
BaseThemeConfig(
  id: 'dark_theme',
  description: "Dark Theme",
  theme: darkTheme(),
  colors: DarkThemeColors(),
  type: NyThemeType.dark, // Ajoutez cette ligne
),
```

### "LocalizedApp not found"

Reference : <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/resources/widgets/main_widget.dart" target="_BLANK">Main Widget</a>

**Solution :** `LocalizedApp` a ete supprime. Changez :
``` dart
// De :
runApp(LocalizedApp(child: Main(nylo)));

// A :
runApp(Main(nylo));
```

### "router.route is not defined"

**Solution :** Utilisez `router.add()` a la place :
``` dart
// De :
router.route(HomePage.path, (context) => HomePage());

// A :
router.add(HomePage.path);
```

### "NyListView not found"

**Solution :** `NyListView` est maintenant `CollectionView` :
``` dart
// De :
NyListView(...)

// A :
CollectionView<MyModel>(...)
```

### Les ressources ne se chargent pas (images, polices)

**Solution :** Mettez a jour les chemins des ressources de `public/` a `assets/` :
1. Deplacez les fichiers : `mv public/* assets/`
2. Mettez a jour les chemins dans `pubspec.yaml`
3. Mettez a jour les references dans le code

### "init() must return a value of type Future"

**Solution :** Changez vers la syntaxe getter :
``` dart
// De :
@override
init() async { ... }

// A :
@override
get init => () async { ... };
```

---

**Besoin d'aide ?** Consultez la [Documentation Nylo](https://nylo.dev/docs/7.x) ou ouvrez une issue sur [GitHub](https://github.com/nylo-core/nylo/issues).
