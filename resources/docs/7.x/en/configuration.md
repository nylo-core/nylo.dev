# Configuration

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction to configuration")
- Environment
  - [The .env File](#env-file "The .env file")
  - [Generating Environment Config](#generating-env "Generating environment config")
  - [Retrieving Values](#retrieving-values "Retrieving environment values")
  - [Creating Config Classes](#creating-config-classes "Creating config classes")
  - [Variable Types](#variable-types "Environment variable types")
- [Environment Flavours](#environment-flavours "Environment flavours")
- [Build-Time Injection](#build-time-injection "Build-time injection")


<div id="introduction"></div>

## Introduction

{{ config('app.name') }} v7 uses a secure environment configuration system. Your environment variables are stored in a `.env` file and then encrypted into a generated Dart file (`env.g.dart`) for use in your app.

This approach provides:
- **Security**: Environment values are XOR-encrypted in the compiled app
- **Type safety**: Values are automatically parsed to appropriate types
- **Build-time flexibility**: Different configurations for development, staging, and production

<div id="env-file"></div>

## The .env File

The `.env` file at your project root contains your configuration variables:

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

### Available Variables

| Variable | Description |
|----------|-------------|
| `APP_KEY` | **Required**. 32-character secret key for encryption |
| `APP_NAME` | Your application name |
| `APP_ENV` | Environment: `developing` or `production` |
| `APP_DEBUG` | Enable debug mode (`true`/`false`) |
| `APP_URL` | Your app's URL |
| `API_BASE_URL` | Base URL for API requests |
| `ASSET_PATH` | Path to assets folder |
| `DEFAULT_LOCALE` | Default language code |

<div id="generating-env"></div>

## Generating Environment Config

{{ config('app.name') }} v7 requires you to generate an encrypted environment file before your app can access env values.

### Step 1: Generate an APP_KEY

First, generate a secure APP_KEY:

``` bash
metro make:key
```

This adds a 32-character `APP_KEY` to your `.env` file.

### Step 2: Generate env.g.dart

Generate the encrypted environment file:

``` bash
metro make:env
```

This creates `lib/bootstrap/env.g.dart` with your encrypted environment variables.

Your env is automatically registered when your app starts — `Nylo.init(env: Env.get, ...)` in `main.dart` handles this for you. No additional setup is needed.

### Regenerating After Changes

When you modify your `.env` file, regenerate the config:

``` bash
metro make:env --force
```

The `--force` flag overwrites the existing `env.g.dart`.

<div id="retrieving-values"></div>

## Retrieving Values

The recommended way to access environment values is through **config classes**. Your `lib/config/app.dart` file uses `getEnv()` to expose env values as typed static fields:

``` dart
// lib/config/app.dart
final class AppConfig {
  static final String appName = getEnv('APP_NAME', defaultValue: 'Nylo');
  static final String appEnv = getEnv('APP_ENV', defaultValue: 'developing');
  static final bool appDebug = getEnv('APP_DEBUG', defaultValue: false);
  static final String apiBaseUrl = getEnv('API_BASE_URL');
}
```

Then in your app code, access values through the config class:

``` dart
// Anywhere in your app
String name = AppConfig.appName;
bool isDebug = AppConfig.appDebug;
String apiUrl = AppConfig.apiBaseUrl;
```

This pattern keeps env access centralized in your config classes. The `getEnv()` helper should be used within config classes rather than directly in app code.

<div id="creating-config-classes"></div>

## Creating Config Classes

You can create custom config classes for third-party services or feature-specific configuration using Metro:

``` bash
metro make:config RevenueCat
```

This creates a new config file at `lib/config/revenue_cat_config.dart`:

``` dart
final class RevenueCatConfig {
  // Add your config values here
}
```

### Example: RevenueCat Configuration

**Step 1:** Add the environment variables to your `.env` file:

``` bash
REVENUECAT_API_KEY="appl_xxxxxxxxxxxxx"
REVENUECAT_ENTITLEMENT_ID="premium"
```

**Step 2:** Update your config class to reference these values:

``` dart
// lib/config/revenue_cat_config.dart
import 'package:nylo_framework/nylo_framework.dart';

final class RevenueCatConfig {
  static final String apiKey = getEnv('REVENUECAT_API_KEY');
  static final String entitlementId = getEnv('REVENUECAT_ENTITLEMENT_ID', defaultValue: 'premium');
}
```

**Step 3:** Regenerate your environment config:

``` bash
metro make:env --force
```

**Step 4:** Use the config class in your app:

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

This approach keeps your API keys and configuration values secure and centralized, making it easy to manage different values across environments.

<div id="variable-types"></div>

## Variable Types

Values in your `.env` file are automatically parsed:

| .env Value | Dart Type | Example |
|------------|-----------|---------|
| `APP_NAME="My App"` | `String` | `"My App"` |
| `DEBUG=true` | `bool` | `true` |
| `DEBUG=false` | `bool` | `false` |
| `VALUE=null` | `null` | `null` |
| `EMPTY=""` | `String` | `""` (empty string) |


<div id="environment-flavours"></div>

## Environment Flavours

Create different configurations for development, staging, and production.

### Step 1: Create Environment Files

Create separate `.env` files:

``` bash
.env                  # Development (default)
.env.staging          # Staging
.env.production       # Production
```

Example `.env.production`:

``` bash
APP_KEY=production-secret-key-here
APP_NAME="My App"
APP_ENV="production"
APP_DEBUG=false
APP_URL="https://myapp.com"
API_BASE_URL="https://api.myapp.com"
```

### Step 2: Generate Environment Config

Generate from a specific env file:

``` bash
# For production
metro make:env --file=".env.production" --force

# For staging
metro make:env --file=".env.staging" --force
```

### Step 3: Build Your App

Build with the appropriate configuration:

``` bash
# Development
flutter run

# Production build
metro make:env --file=.env.production --force
flutter build ios
flutter build appbundle
```

<div id="build-time-injection"></div>

## Build-Time Injection

For enhanced security, you can inject the APP_KEY at build time instead of embedding it in the source code.

### Generate with --dart-define Mode

``` bash
metro make:env --dart-define
```

This generates `env.g.dart` without embedding the APP_KEY.

### Build with APP_KEY Injection

``` bash
# iOS
flutter build ios --dart-define=APP_KEY=your-secret-key

# Android
flutter build appbundle --dart-define=APP_KEY=your-secret-key

# Run
flutter run --dart-define=APP_KEY=your-secret-key
```

This approach keeps the APP_KEY out of your source code, which is useful for:
- CI/CD pipelines where secrets are injected
- Open source projects
- Enhanced security requirements

### Best Practices

1. **Never commit `.env` to version control** - Add it to `.gitignore`
2. **Use `.env-example`** - Commit a template without sensitive values
3. **Regenerate after changes** - Always run `metro make:env --force` after modifying `.env`
4. **Different keys per environment** - Use unique APP_KEYs for development, staging, and production
