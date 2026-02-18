# Directory Structure

---

<a name="section-1"></a>
- [Introdução](#introduction "Introdução à estrutura de diretórios")
- [Diretório Raiz](#root-directory "Diretório raiz")
- [O Diretório lib](#lib-directory "O diretório lib")
  - [app](#app-directory "Diretório app")
  - [bootstrap](#bootstrap-directory "Diretório bootstrap")
  - [config](#config-directory "Diretório config")
  - [resources](#resources-directory "Diretório resources")
  - [routes](#routes-directory "Diretório routes")
- [Diretório de Assets](#assets-directory "Diretório de assets")
- [Helpers de Assets](#asset-helpers "Helpers de assets")


<div id="introduction"></div>

## Introdução

{{ config('app.name') }} usa uma estrutura de diretórios limpa e organizada inspirada no <a href="https://github.com/laravel/laravel" target="_BLANK">Laravel</a>. Essa estrutura ajuda a manter consistência entre projetos e facilita encontrar arquivos.

<div id="root-directory"></div>

## Diretório Raiz

```
nylo_app/
├── android/          # Arquivos da plataforma Android
├── assets/           # Imagens, fontes e outros assets
├── ios/              # Arquivos da plataforma iOS
├── lang/             # Arquivos JSON de idioma/tradução
├── lib/              # Código da aplicação Dart
├── test/             # Arquivos de teste
├── .env              # Variáveis de ambiente
├── pubspec.yaml      # Dependências e configuração do projeto
└── ...
```

<div id="lib-directory"></div>

## O Diretório lib

A pasta `lib/` contém todo o código Dart da sua aplicação:

```
lib/
├── app/              # Lógica da aplicação
├── bootstrap/        # Configuração de inicialização
├── config/           # Arquivos de configuração
├── resources/        # Componentes de UI
├── routes/           # Definições de rotas
└── main.dart         # Ponto de entrada da aplicação
```

<div id="app-directory"></div>

### app/

O diretório `app/` contém a lógica principal da sua aplicação:

| Diretório | Finalidade |
|-----------|---------|
| `commands/` | Comandos personalizados do Metro CLI |
| `controllers/` | Controllers de página para lógica de negócios |
| `events/` | Classes de eventos para o sistema de eventos |
| `forms/` | Classes de formulário com validação |
| `models/` | Classes de modelo de dados |
| `networking/` | Serviços de API e configuração de rede |
| `networking/dio/interceptors/` | Interceptors HTTP do Dio |
| `providers/` | Provedores de serviço inicializados na inicialização do app |
| `services/` | Classes de serviços gerais |

<div id="bootstrap-directory"></div>

### bootstrap/

O diretório `bootstrap/` contém arquivos que configuram como seu app é inicializado:

| Arquivo | Finalidade |
|------|---------|
| `boot.dart` | Configuração principal da sequência de inicialização |
| `decoders.dart` | Registro de model e API decoders |
| `env.g.dart` | Configuração de ambiente criptografada gerada |
| `events.dart` | Registro de eventos |
| `extensions.dart` | Extensões personalizadas |
| `helpers.dart` | Funções helper personalizadas |
| `providers.dart` | Registro de providers |
| `theme.dart` | Configuração de temas |

<div id="config-directory"></div>

### config/

O diretório `config/` contém configurações da aplicação:

| Arquivo | Finalidade |
|------|---------|
| `app.dart` | Configurações principais do app |
| `design.dart` | Design do app (fonte, logo, loader) |
| `localization.dart` | Configurações de idioma e localização |
| `storage_keys.dart` | Definições de chaves de armazenamento local |
| `toast_notification.dart` | Estilos de notificação toast |

<div id="resources-directory"></div>

### resources/

O diretório `resources/` contém componentes de UI:

| Diretório | Finalidade |
|-----------|---------|
| `pages/` | Widgets de página (telas) |
| `themes/` | Definições de temas |
| `themes/light/` | Cores do tema claro |
| `themes/dark/` | Cores do tema escuro |
| `widgets/` | Componentes de widgets reutilizáveis |
| `widgets/buttons/` | Widgets de botões personalizados |
| `widgets/bottom_sheet_modals/` | Widgets de modais de bottom sheet |

<div id="routes-directory"></div>

### routes/

O diretório `routes/` contém a configuração de rotas:

| Arquivo/Diretório | Finalidade |
|----------------|---------|
| `router.dart` | Definições de rotas |
| `guards/` | Classes de route guard |

<div id="assets-directory"></div>

## Diretório de Assets

O diretório `assets/` armazena arquivos estáticos:

```
assets/
├── app_icon/         # Ícone do app (fonte)
├── fonts/            # Fontes personalizadas
└── images/           # Assets de imagens
```

### Registrando Assets

Assets são registrados no `pubspec.yaml`:

``` yaml
flutter:
  assets:
    - assets/fonts/
    - assets/images/
    - assets/app_icon/
    - lang/
```

<div id="asset-helpers"></div>

## Helpers de Assets

{{ config('app.name') }} fornece helpers para trabalhar com assets.

### Assets de Imagem

``` dart
// Forma padrão do Flutter
Image.asset(
  'assets/images/logo.png',
  height: 50,
  width: 50,
)

// Usando o widget LocalAsset
LocalAsset.image(
  "logo.png",
  height: 50,
  width: 50,
)
```

### Assets Gerais

``` dart
// Obter o caminho de qualquer asset
String fontPath = getAsset('fonts/custom.ttf');

// Exemplo com vídeo
VideoPlayerController.asset(
  getAsset('videos/intro.mp4')
)
```

### Arquivos de Idioma

Arquivos de idioma são armazenados em `lang/` na raiz do projeto:

```
lang/
├── en.json           # Inglês
├── es.json           # Espanhol
├── fr.json           # Francês
└── ...
```

Consulte [Localização](/docs/7.x/localization) para mais detalhes.
