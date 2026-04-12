# Configuration

---

<a name="section-1"></a>
- [Introdução](#introduction "Introdução à configuração")
- Ambiente
  - [O Arquivo .env](#env-file "O arquivo .env")
  - [Gerando a Configuração de Ambiente](#generating-env "Gerando a configuração de ambiente")
  - [Recuperando Valores](#retrieving-values "Recuperando valores do ambiente")
  - [Criando Classes de Config](#creating-config-classes "Criando classes de config")
  - [Tipos de Variáveis](#variable-types "Tipos de variáveis de ambiente")
  - [Interpolação de Variáveis](#variable-interpolation "Interpolação de variáveis")
- [Flavours de Ambiente](#environment-flavours "Flavours de ambiente")
- [Injeção em Tempo de Build](#build-time-injection "Injeção em tempo de build")


<div id="introduction"></div>

## Introdução

{{ config('app.name') }} v7 usa um sistema seguro de configuração de ambiente. Suas variáveis de ambiente são armazenadas em um arquivo `.env` e então encriptadas em um arquivo Dart gerado (`env.g.dart`) para uso no seu app.

Esta abordagem fornece:
- **Segurança**: Valores de ambiente são encriptados com XOR no app compilado
- **Segurança de tipo**: Valores são automaticamente parseados para os tipos apropriados
- **Flexibilidade em tempo de build**: Diferentes configurações para desenvolvimento, staging e produção

<div id="env-file"></div>

## O Arquivo .env

O arquivo `.env` na raiz do seu projeto contém suas variáveis de configuração:

``` bash
# Configuração do ambiente
APP_KEY=your-32-character-secret-key
APP_NAME="My App"
APP_ENV="developing"
APP_DEBUG=true
APP_URL="https://myapp.com"
API_BASE_URL="https://api.myapp.com"

ASSET_PATH="assets"

DEFAULT_LOCALE="en"
```

### Variáveis Disponíveis

| Variável | Descrição |
|----------|-------------|
| `APP_KEY` | **Obrigatório**. Chave secreta de 32 caracteres para encriptação |
| `APP_NAME` | Nome da sua aplicação |
| `APP_ENV` | Ambiente: `developing` ou `production` |
| `APP_DEBUG` | Ativar modo debug (`true`/`false`) |
| `APP_URL` | URL do seu app |
| `API_BASE_URL` | URL base para requisições de API |
| `ASSET_PATH` | Caminho para a pasta de assets |
| `DEFAULT_LOCALE` | Código de idioma padrão |

<div id="generating-env"></div>

## Gerando a Configuração de Ambiente

{{ config('app.name') }} v7 requer que você gere um arquivo de ambiente encriptado antes que seu app possa acessar os valores do env.

### Passo 1: Gerar uma APP_KEY

Primeiro, gere uma APP_KEY segura:

``` bash
metro make:key
```

Isso adiciona uma `APP_KEY` de 32 caracteres ao seu arquivo `.env`.

### Passo 2: Gerar env.g.dart

Gere o arquivo de ambiente encriptado:

``` bash
metro make:env
```

Isso cria `lib/bootstrap/env.g.dart` com suas variáveis de ambiente encriptadas.

Seu env é automaticamente registrado quando seu app inicia -- `Nylo.init(env: Env.get, ...)` em `main.dart` cuida disso para você. Nenhuma configuração adicional é necessária.

### Regenerando Após Mudanças

Quando você modificar seu arquivo `.env`, regenere a configuração:

``` bash
metro make:env
```

Isso sempre sobrescreve o `env.g.dart` existente.

<div id="retrieving-values"></div>

## Recuperando Valores

A forma recomendada de acessar valores de ambiente é através de **classes de config**. Seu arquivo `lib/config/app.dart` usa `getEnv()` para expor valores do env como campos estáticos tipados:

``` dart
// lib/config/app.dart
final class AppConfig {
  static final String appName = getEnv('APP_NAME', defaultValue: 'Nylo');
  static final String appEnv = getEnv('APP_ENV', defaultValue: 'developing');
  static final bool appDebug = getEnv('APP_DEBUG', defaultValue: false);
  static final String apiBaseUrl = getEnv('API_BASE_URL');
}
```

Então no código do seu app, acesse os valores através da classe de config:

``` dart
// Em qualquer lugar do seu app
String name = AppConfig.appName;
bool isDebug = AppConfig.appDebug;
String apiUrl = AppConfig.apiBaseUrl;
```

Este padrão mantém o acesso ao env centralizado nas suas classes de config. O helper `getEnv()` deve ser usado dentro de classes de config em vez de diretamente no código do app.

<div id="creating-config-classes"></div>

## Criando Classes de Config

Você pode criar classes de config personalizadas para serviços de terceiros ou configuração específica de funcionalidades usando Metro:

``` bash
metro make:config RevenueCat
```

Isso cria um novo arquivo de config em `lib/config/revenue_cat_config.dart`:

``` dart
final class RevenueCatConfig {
  // Adicione seus valores de config aqui
}
```

### Exemplo: Configuração do RevenueCat

**Passo 1:** Adicione as variáveis de ambiente ao seu arquivo `.env`:

``` bash
REVENUECAT_API_KEY="appl_xxxxxxxxxxxxx"
REVENUECAT_ENTITLEMENT_ID="premium"
```

**Passo 2:** Atualize sua classe de config para referenciar esses valores:

``` dart
// lib/config/revenue_cat_config.dart
import 'package:nylo_framework/nylo_framework.dart';

final class RevenueCatConfig {
  static final String apiKey = getEnv('REVENUECAT_API_KEY');
  static final String entitlementId = getEnv('REVENUECAT_ENTITLEMENT_ID', defaultValue: 'premium');
}
```

**Passo 3:** Regenere a configuração de ambiente:

``` bash
metro make:env
```

**Passo 4:** Use a classe de config no seu app:

``` dart
import '/config/revenue_cat_config.dart';

// Inicializar RevenueCat
await Purchases.configure(
  PurchasesConfiguration(RevenueCatConfig.apiKey),
);

// Verificar direitos
if (entitlement.identifier == RevenueCatConfig.entitlementId) {
  // Conceder acesso premium
}
```

Esta abordagem mantém suas chaves de API e valores de configuração seguros e centralizados, facilitando o gerenciamento de diferentes valores entre ambientes.

<div id="variable-types"></div>

## Tipos de Variáveis

Os valores no seu arquivo `.env` são automaticamente parseados:

| Valor .env | Tipo Dart | Exemplo |
|------------|-----------|---------|
| `APP_NAME="My App"` | `String` | `"My App"` |
| `DEBUG=true` | `bool` | `true` |
| `DEBUG=false` | `bool` | `false` |
| `VALUE=null` | `null` | `null` |
| `EMPTY=""` | `String` | `""` (string vazia) |


<div id="variable-interpolation"></div>

## Interpolação de Variáveis

Valores de string no seu arquivo `.env` podem referenciar outras variáveis usando a sintaxe `${VAR_NAME}`:

``` bash
APP_DOMAIN="myapp.com"
APP_URL="https://${APP_DOMAIN}"
API_BASE_URL="https://api.${APP_DOMAIN}/v1"
```

Quando seu código chama `getEnv('APP_URL')`, o valor retornado é `https://myapp.com`. Referências são resolvidas recursivamente, então referências encadeadas funcionam como esperado:

``` bash
HOST="example.com"
BASE="https://${HOST}"
UPLOADS="${BASE}/uploads"
```

`getEnv('UPLOADS')` retorna `https://example.com/uploads`.

Referências circulares são protegidas — se uma variável referencia a si mesma (diretamente ou por uma cadeia), o placeholder não resolvido `${VAR_NAME}` é mantido na saída em vez de causar um loop infinito.

<div id="environment-flavours"></div>

## Flavours de Ambiente

Crie diferentes configurações para desenvolvimento, staging e produção.

### Passo 1: Criar Arquivos de Ambiente

Crie arquivos `.env` separados:

``` bash
.env                  # Development (default)
.env.staging          # Staging
.env.production       # Production
```

Exemplo `.env.production`:

``` bash
APP_KEY=production-secret-key-here
APP_NAME="My App"
APP_ENV="production"
APP_DEBUG=false
APP_URL="https://myapp.com"
API_BASE_URL="https://api.myapp.com"
```

### Passo 2: Gerar a Configuração de Ambiente

Gere a partir de um arquivo env específico:

``` bash
# Para produção
metro make:env --file=".env.production"

# Para staging
metro make:env --file=".env.staging"
```

### Passo 3: Compilar Seu App

Compile com a configuração apropriada:

``` bash
# Desenvolvimento
flutter run

# Build de produção
metro make:env --file=".env.production"
flutter build ios
flutter build appbundle
```

<div id="build-time-injection"></div>

## Injeção em Tempo de Build

Para segurança aprimorada, você pode injetar a APP_KEY em tempo de build em vez de incorporá-la no código-fonte.

### Gerar com Modo --dart-define

``` bash
metro make:env --dart-define
```

Isso gera `env.g.dart` sem incorporar a APP_KEY.

### Compilar com Injeção da APP_KEY

``` bash
# iOS
flutter build ios --dart-define=APP_KEY=your-secret-key

# Android
flutter build appbundle --dart-define=APP_KEY=your-secret-key

# Executar
flutter run --dart-define=APP_KEY=your-secret-key
```

Esta abordagem mantém a APP_KEY fora do seu código-fonte, o que é útil para:
- Pipelines CI/CD onde segredos são injetados
- Projetos open source
- Requisitos de segurança aprimorados

### Boas Práticas

1. **Nunca commite `.env` no controle de versão** - Adicione ao `.gitignore`
2. **Use `.env-example`** - Commite um template sem valores sensíveis
3. **Regenere após mudanças** - Sempre execute `metro make:env` após modificar `.env`
4. **Chaves diferentes por ambiente** - Use APP_KEYs únicos para desenvolvimento, staging e produção
