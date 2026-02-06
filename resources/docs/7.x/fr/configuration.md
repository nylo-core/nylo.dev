# Configuration

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction a la configuration")
- Environnement
  - [Le fichier .env](#env-file "Le fichier .env")
  - [Generer la configuration d'environnement](#generating-env "Generer la configuration d'environnement")
  - [Recuperer les valeurs](#retrieving-values "Recuperer les valeurs d'environnement")
  - [Creer des classes de configuration](#creating-config-classes "Creer des classes de configuration")
  - [Types de variables](#variable-types "Types de variables d'environnement")
- [Variantes d'environnement](#environment-flavours "Variantes d'environnement")
- [Injection au moment de la compilation](#build-time-injection "Injection au moment de la compilation")


<div id="introduction"></div>

## Introduction

{{ config('app.name') }} v7 utilise un systeme de configuration d'environnement securise. Vos variables d'environnement sont stockees dans un fichier `.env` puis chiffrees dans un fichier Dart genere (`env.g.dart`) pour utilisation dans votre application.

Cette approche offre :
- **Securite** : Les valeurs d'environnement sont chiffrees par XOR dans l'application compilee
- **Typage fort** : Les valeurs sont automatiquement analysees vers les types appropries
- **Flexibilite a la compilation** : Differentes configurations pour le developpement, la pre-production et la production

<div id="env-file"></div>

## Le fichier .env

Le fichier `.env` a la racine de votre projet contient vos variables de configuration :

``` bash
# Environment configuration
APP_KEY=your-32-character-secret-key
APP_NAME="My App"
APP_ENV="developing"
APP_DEBUG=true
APP_URL="https://myapp.com"
API_BASE_URL="https://api.myapp.com"

ASSET_PATH="assets"

DEFAULT_LOCALE="en"
```

### Variables disponibles

| Variable | Description |
|----------|-------------|
| `APP_KEY` | **Requis**. Cle secrete de 32 caracteres pour le chiffrement |
| `APP_NAME` | Le nom de votre application |
| `APP_ENV` | Environnement : `developing` ou `production` |
| `APP_DEBUG` | Activer le mode debogage (`true`/`false`) |
| `APP_URL` | L'URL de votre application |
| `API_BASE_URL` | URL de base pour les requetes API |
| `ASSET_PATH` | Chemin vers le dossier des assets |
| `DEFAULT_LOCALE` | Code de langue par defaut |

<div id="generating-env"></div>

## Generer la configuration d'environnement

{{ config('app.name') }} v7 necessite de generer un fichier d'environnement chiffre avant que votre application puisse acceder aux valeurs d'environnement.

### Etape 1 : Generer un APP_KEY

Generez d'abord un APP_KEY securise :

``` bash
metro make:key
```

Cela ajoute un `APP_KEY` de 32 caracteres a votre fichier `.env`.

### Etape 2 : Generer env.g.dart

Generez le fichier d'environnement chiffre :

``` bash
metro make:env
```

Cela cree `lib/bootstrap/env.g.dart` avec vos variables d'environnement chiffrees.

Votre environnement est automatiquement enregistre au demarrage de votre application — `Nylo.init(env: Env.get, ...)` dans `main.dart` s'en charge pour vous. Aucune configuration supplementaire n'est necessaire.

### Regenerer apres des modifications

Lorsque vous modifiez votre fichier `.env`, regenerez la configuration :

``` bash
metro make:env --force
```

Le drapeau `--force` ecrase le fichier `env.g.dart` existant.

<div id="retrieving-values"></div>

## Recuperer les valeurs

La methode recommandee pour acceder aux valeurs d'environnement est via les **classes de configuration**. Votre fichier `lib/config/app.dart` utilise `getEnv()` pour exposer les valeurs d'environnement sous forme de champs statiques types :

``` dart
// lib/config/app.dart
final class AppConfig {
  static final String appName = getEnv('APP_NAME', defaultValue: 'Nylo');
  static final String appEnv = getEnv('APP_ENV', defaultValue: 'developing');
  static final bool appDebug = getEnv('APP_DEBUG', defaultValue: false);
  static final String apiBaseUrl = getEnv('API_BASE_URL');
}
```

Ensuite, dans le code de votre application, accedez aux valeurs via la classe de configuration :

``` dart
// Anywhere in your app
String name = AppConfig.appName;
bool isDebug = AppConfig.appDebug;
String apiUrl = AppConfig.apiBaseUrl;
```

Ce modele centralise l'acces aux variables d'environnement dans vos classes de configuration. Le helper `getEnv()` doit etre utilise dans les classes de configuration plutot que directement dans le code de l'application.

<div id="creating-config-classes"></div>

## Creer des classes de configuration

Vous pouvez creer des classes de configuration personnalisees pour des services tiers ou une configuration specifique a une fonctionnalite en utilisant Metro :

``` bash
metro make:config RevenueCat
```

Cela cree un nouveau fichier de configuration a `lib/config/revenue_cat_config.dart` :

``` dart
final class RevenueCatConfig {
  // Add your config values here
}
```

### Exemple : Configuration RevenueCat

**Etape 1 :** Ajoutez les variables d'environnement a votre fichier `.env` :

``` bash
REVENUECAT_API_KEY="appl_xxxxxxxxxxxxx"
REVENUECAT_ENTITLEMENT_ID="premium"
```

**Etape 2 :** Mettez a jour votre classe de configuration pour referencer ces valeurs :

``` dart
// lib/config/revenue_cat_config.dart
import 'package:nylo_framework/nylo_framework.dart';

final class RevenueCatConfig {
  static final String apiKey = getEnv('REVENUECAT_API_KEY');
  static final String entitlementId = getEnv('REVENUECAT_ENTITLEMENT_ID', defaultValue: 'premium');
}
```

**Etape 3 :** Regenerez votre configuration d'environnement :

``` bash
metro make:env --force
```

**Etape 4 :** Utilisez la classe de configuration dans votre application :

``` dart
import '/config/revenue_cat_config.dart';

// Initialize RevenueCat
await Purchases.configure(
  PurchasesConfiguration(RevenueCatConfig.apiKey),
);

// Check entitlements
if (entitlement.identifier == RevenueCatConfig.entitlementId) {
  // Grant premium access
}
```

Cette approche garde vos cles API et valeurs de configuration securisees et centralisees, facilitant la gestion de valeurs differentes selon les environnements.

<div id="variable-types"></div>

## Types de variables

Les valeurs de votre fichier `.env` sont automatiquement analysees :

| Valeur .env | Type Dart | Exemple |
|------------|-----------|---------|
| `APP_NAME="My App"` | `String` | `"My App"` |
| `DEBUG=true` | `bool` | `true` |
| `DEBUG=false` | `bool` | `false` |
| `VALUE=null` | `null` | `null` |
| `EMPTY=""` | `String` | `""` (chaine vide) |


<div id="environment-flavours"></div>

## Variantes d'environnement

Creez differentes configurations pour le developpement, la pre-production et la production.

### Etape 1 : Creer les fichiers d'environnement

Creez des fichiers `.env` separes :

``` bash
.env                  # Development (default)
.env.staging          # Staging
.env.production       # Production
```

Exemple `.env.production` :

``` bash
APP_KEY=production-secret-key-here
APP_NAME="My App"
APP_ENV="production"
APP_DEBUG=false
APP_URL="https://myapp.com"
API_BASE_URL="https://api.myapp.com"
```

### Etape 2 : Generer la configuration d'environnement

Generez a partir d'un fichier d'environnement specifique :

``` bash
# For production
metro make:env --file=".env.production" --force

# For staging
metro make:env --file=".env.staging" --force
```

### Etape 3 : Compiler votre application

Compilez avec la configuration appropriee :

``` bash
# Development
flutter run

# Production build
metro make:env --file=.env.production --force
flutter build ios
flutter build appbundle
```

<div id="build-time-injection"></div>

## Injection au moment de la compilation

Pour une securite renforcee, vous pouvez injecter l'APP_KEY au moment de la compilation au lieu de l'integrer dans le code source.

### Generer avec le mode --dart-define

``` bash
metro make:env --dart-define
```

Cela genere `env.g.dart` sans integrer l'APP_KEY.

### Compiler avec injection de l'APP_KEY

``` bash
# iOS
flutter build ios --dart-define=APP_KEY=your-secret-key

# Android
flutter build appbundle --dart-define=APP_KEY=your-secret-key

# Run
flutter run --dart-define=APP_KEY=your-secret-key
```

Cette approche garde l'APP_KEY en dehors de votre code source, ce qui est utile pour :
- Les pipelines CI/CD ou les secrets sont injectes
- Les projets open source
- Les exigences de securite renforcees

### Bonnes pratiques

1. **Ne jamais committer `.env` dans le controle de version** - Ajoutez-le au `.gitignore`
2. **Utiliser `.env-example`** - Committez un modele sans valeurs sensibles
3. **Regenerer apres les modifications** - Executez toujours `metro make:env --force` apres avoir modifie `.env`
4. **Des cles differentes par environnement** - Utilisez des APP_KEYs uniques pour le developpement, la pre-production et la production
