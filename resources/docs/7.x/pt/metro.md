# Metro CLI tool

---

<a name="section-1"></a>
- [Introdução](#introduction "Introdução")
- [Instalar](#install "Instalando o alias do Metro para {{ config('app.name') }}")
- Comandos Make
  - [Make controller](#make-controller "Criar um novo controller")
  - [Make model](#make-model "Criar um novo model")
  - [Make page](#make-page "Criar uma nova página")
  - [Make stateless widget](#make-stateless-widget "Criar um novo stateless widget")
  - [Make stateful widget](#make-stateful-widget "Criar um novo stateful widget")
  - [Make journey widget](#make-journey-widget "Criar um novo journey widget")
  - [Make API Service](#make-api-service "Criar um novo API Service")
  - [Make Event](#make-event "Criar um novo Event")
  - [Make Provider](#make-provider "Criar um novo provider")
  - [Make Theme](#make-theme "Criar um novo tema")
  - [Make Forms](#make-forms "Criar um novo formulário")
  - [Make Route Guard](#make-route-guard "Criar um novo route guard")
  - [Make Config File](#make-config-file "Criar um novo arquivo de configuração")
  - [Make Command](#make-command "Criar um novo comando")
  - [Make State Managed Widget](#make-state-managed-widget "Criar um novo state managed widget")
  - [Make Navigation Hub](#make-navigation-hub "Criar um novo navigation hub")
  - [Make Bottom Sheet Modal](#make-bottom-sheet-modal "Criar um novo bottom sheet modal")
  - [Make Button](#make-button "Criar um novo botão")
  - [Make Interceptor](#make-interceptor "Criar um novo interceptor")
  - [Make Env File](#make-env-file "Criar um novo arquivo env")
  - [Make Key](#make-key "Gerar APP_KEY")
- Ícones do App
  - [Gerando Ícones do App](#build-app-icons "Gerando ícones do app com Metro")
- Comandos Personalizados
  - [Criando comandos personalizados](#creating-custom-commands "Criando comandos personalizados")
  - [Executando Comandos Personalizados](#running-custom-commands "Executando comandos personalizados")
  - [Adicionando opções aos comandos](#adding-options-to-custom-commands "Adicionando opções aos comandos personalizados")
  - [Adicionando flags aos comandos](#adding-flags-to-custom-commands "Adicionando flags aos comandos personalizados")
  - [Métodos auxiliares](#custom-command-helper-methods "Métodos auxiliares de comandos personalizados")
  - [Métodos de entrada interativa](#interactive-input-methods "Métodos de entrada interativa")
  - [Formatação de saída](#output-formatting "Formatação de saída")
  - [Helpers de sistema de arquivos](#file-system-helpers "Helpers de sistema de arquivos")
  - [Helpers de JSON e YAML](#json-yaml-helpers "Helpers de JSON e YAML")
  - [Helpers de conversão de case](#case-conversion-helpers "Helpers de conversão de case")
  - [Helpers de caminhos do projeto](#project-path-helpers "Helpers de caminhos do projeto")
  - [Helpers de plataforma](#platform-helpers "Helpers de plataforma")
  - [Comandos Dart e Flutter](#dart-flutter-commands "Comandos Dart e Flutter")
  - [Manipulação de arquivos Dart](#dart-file-manipulation "Manipulação de arquivos Dart")
  - [Helpers de diretório](#directory-helpers "Helpers de diretório")
  - [Helpers de validação](#validation-helpers "Helpers de validação")
  - [Scaffolding de arquivos](#file-scaffolding "Scaffolding de arquivos")
  - [Executor de tarefas](#task-runner "Executor de tarefas")
  - [Saída em tabela](#table-output "Saída em tabela")
  - [Barra de progresso](#progress-bar "Barra de progresso")


<div id="introduction"></div>

## Introdução

Metro é uma ferramenta CLI que funciona por baixo dos panos do framework {{ config('app.name') }}.
Ela fornece muitas ferramentas úteis para acelerar o desenvolvimento.

<div id="install"></div>

## Instalar

Quando você cria um novo projeto Nylo usando `nylo init`, o comando `metro` é automaticamente configurado para o seu terminal. Você pode começar a usá-lo imediatamente em qualquer projeto Nylo.

Execute `metro` a partir do diretório do seu projeto para ver todos os comandos disponíveis:

``` bash
metro
```

Você deve ver uma saída semelhante à abaixo.

``` bash
Metro - Nylo's Companion to Build Flutter apps by Anthony Gordon

Usage:
    command [options] [arguments]

Options
    -h

All commands:

[Widget Commands]
  make:page
  make:stateful_widget
  make:stateless_widget
  make:state_managed_widget
  make:navigation_hub
  make:journey_widget
  make:bottom_sheet_modal
  make:button
  make:form

[Helper Commands]
  make:model
  make:provider
  make:api_service
  make:controller
  make:event
  make:theme
  make:route_guard
  make:config
  make:interceptor
  make:command
  make:env
  make:key
```

<div id="make-controller"></div>

## Make controller

- [Criando um novo controller](#making-a-new-controller "Criar um novo controller com Metro")
- [Forçar criação de um controller](#forcefully-make-a-controller "Forçar criação de um novo controller com Metro")
<div id="making-a-new-controller"></div>

### Criando um novo controller

Você pode criar um novo controller executando o comando abaixo no terminal.

``` bash
metro make:controller profile_controller
```

Isso criará um novo controller se ele não existir dentro do diretório `lib/app/controllers/`.

<div id="forcefully-make-a-controller"></div>

### Forçar criação de um controller

**Argumentos:**

Usando a flag `--force` ou `-f`, um controller existente será sobrescrito caso já exista.

``` bash
metro make:controller profile_controller --force
```

<div id="make-model"></div>

## Make model

- [Criando um novo model](#making-a-new-model "Criar um novo model com Metro")
- [Criar model a partir de JSON](#make-model-from-json "Criar um novo model a partir de JSON com Metro")
- [Forçar criação de um model](#forcefully-make-a-model "Forçar criação de um novo model com Metro")
<div id="making-a-new-model"></div>

### Criando um novo model

Você pode criar um novo model executando o comando abaixo no terminal.

``` bash
metro make:model product
```

Ele colocará o model recém-criado em `lib/app/models/`.

<div id="make-model-from-json"></div>

### Criar um model a partir de JSON

**Argumentos:**

Usando a flag `--json` ou `-j`, um novo model será criado a partir de um payload JSON.

``` bash
metro make:model product --json
```

Em seguida, você pode colar seu JSON no terminal e ele gerará um model para você.

<div id="forcefully-make-a-model"></div>

### Forçar criação de um model

**Argumentos:**

Usando a flag `--force` ou `-f`, um model existente será sobrescrito caso já exista.

``` bash
metro make:model product --force
```

<div id="make-page"></div>

## Make page

- [Criando uma nova página](#making-a-new-page "Criar uma nova página com Metro")
- [Criar uma página com controller](#create-a-page-with-a-controller "Criar uma nova página com controller com Metro")
- [Criar uma página de autenticação](#create-an-auth-page "Criar uma nova página de autenticação com Metro")
- [Criar uma página inicial](#create-an-initial-page "Criar uma nova página inicial com Metro")
- [Forçar criação de uma página](#forcefully-make-a-page "Forçar criação de uma nova página com Metro")

<div id="making-a-new-page"></div>

### Criando uma nova página

Você pode criar uma nova página executando o comando abaixo no terminal.

``` bash
metro make:page product_page
```

Isso criará uma nova página se ela não existir dentro do diretório `lib/resources/pages/`.

<div id="create-a-page-with-a-controller"></div>

### Criar uma página com controller

Você pode criar uma nova página com controller executando o comando abaixo no terminal.

**Argumentos:**

Usando a flag `--controller` ou `-c`, uma nova página com controller será criada.

``` bash
metro make:page product_page -c
```

<div id="create-an-auth-page"></div>

### Criar uma página de autenticação

Você pode criar uma nova página de autenticação executando o comando abaixo no terminal.

**Argumentos:**

Usando a flag `--auth` ou `-a`, uma nova página de autenticação será criada.

``` bash
metro make:page login_page -a
```

<div id="create-an-initial-page"></div>

### Criar uma página inicial

Você pode criar uma nova página inicial executando o comando abaixo no terminal.

**Argumentos:**

Usando a flag `--initial` ou `-i`, uma nova página inicial será criada.

``` bash
metro make:page home_page -i
```

<div id="forcefully-make-a-page"></div>

### Forçar criação de uma página

**Argumentos:**

Usando a flag `--force` ou `-f`, uma página existente será sobrescrita caso já exista.

``` bash
metro make:page product_page --force
```

<div id="make-stateless-widget"></div>

## Make stateless widget

- [Criando um novo stateless widget](#making-a-new-stateless-widget "Criar um novo stateless widget com Metro")
- [Forçar criação de um stateless widget](#forcefully-make-a-stateless-widget "Forçar criação de um novo stateless widget com Metro")
<div id="making-a-new-stateless-widget"></div>

### Criando um novo stateless widget

Você pode criar um novo stateless widget executando o comando abaixo no terminal.

``` bash
metro make:stateless_widget product_rating_widget
```

O comando acima criará um novo widget se ele não existir dentro do diretório `lib/resources/widgets/`.

<div id="forcefully-make-a-stateless-widget"></div>

### Forçar criação de um stateless widget

**Argumentos:**

Usando a flag `--force` ou `-f`, um widget existente será sobrescrito caso já exista.

``` bash
metro make:stateless_widget product_rating_widget --force
```

<div id="make-stateful-widget"></div>

## Make stateful widget

- [Criando um novo stateful widget](#making-a-new-stateful-widget "Criar um novo stateful widget com Metro")
- [Forçar criação de um stateful widget](#forcefully-make-a-stateful-widget "Forçar criação de um novo stateful widget com Metro")

<div id="making-a-new-stateful-widget"></div>

### Criando um novo stateful widget

Você pode criar um novo stateful widget executando o comando abaixo no terminal.

``` bash
metro make:stateful_widget product_rating_widget
```

O comando acima criará um novo widget se ele não existir dentro do diretório `lib/resources/widgets/`.

<div id="forcefully-make-a-stateful-widget"></div>

### Forçar criação de um stateful widget

**Argumentos:**

Usando a flag `--force` ou `-f`, um widget existente será sobrescrito caso já exista.

``` bash
metro make:stateful_widget product_rating_widget --force
```

<div id="make-journey-widget"></div>

## Make journey widget

- [Criando um novo journey widget](#making-a-new-journey-widget "Criar um novo journey widget com Metro")
- [Forçar criação de um journey widget](#forcefully-make-a-journey-widget "Forçar criação de um novo journey widget com Metro")

<div id="making-a-new-journey-widget"></div>

### Criando um novo journey widget

Você pode criar um novo journey widget executando o comando abaixo no terminal.

``` bash
metro make:journey_widget product_journey --parent="[NAVIGATION_HUB]"

# Exemplo completo se você tiver um BaseNavigationHub
metro make:journey_widget welcome,user_dob,user_photos --parent="Base"
```

O comando acima criará um novo widget se ele não existir dentro do diretório `lib/resources/widgets/`.

O argumento `--parent` é usado para especificar o widget pai ao qual o novo journey widget será adicionado.

Exemplo

``` bash
metro make:navigation_hub onboarding
```

Em seguida, adicione os novos journey widgets.
``` bash
metro make:journey_widget welcome,user_dob,user_photos --parent="onboarding"
```

<div id="forcefully-make-a-journey-widget"></div>

### Forçar criação de um journey widget
**Argumentos:**
Usando a flag `--force` ou `-f`, um widget existente será sobrescrito caso já exista.

``` bash
metro make:journey_widget product_journey --force --parent="[YOUR_NAVIGATION_HUB]"
```

<div id="make-api-service"></div>

## Make API Service

- [Criando um novo API Service](#making-a-new-api-service "Criar um novo API Service com Metro")
- [Criando um novo API Service com model](#making-a-new-api-service-with-a-model "Criar um novo API Service com model com Metro")
- [Criar API Service usando Postman](#make-api-service-using-postman "Criar API services com Postman")
- [Forçar criação de um API Service](#forcefully-make-an-api-service "Forçar criação de um novo API Service com Metro")

<div id="making-a-new-api-service"></div>

### Criando um novo API Service

Você pode criar um novo API service executando o comando abaixo no terminal.

``` bash
metro make:api_service user_api_service
```

Ele colocará o API service recém-criado em `lib/app/networking/`.

<div id="making-a-new-api-service-with-a-model"></div>

### Criando um novo API Service com model

Você pode criar um novo API service com um model executando o comando abaixo no terminal.

**Argumentos:**

Usando a opção `--model` ou `-m`, um novo API service com model será criado.

``` bash
metro make:api_service user --model="User"
```

Ele colocará o API service recém-criado em `lib/app/networking/`.

### Forçar criação de um API Service

**Argumentos:**

Usando a flag `--force` ou `-f`, um API Service existente será sobrescrito caso já exista.

``` bash
metro make:api_service user --force
```

<div id="make-event"></div>

## Make event

- [Criando um novo evento](#making-a-new-event "Criar um novo evento com Metro")
- [Forçar criação de um evento](#forcefully-make-an-event "Forçar criação de um novo evento com Metro")

<div id="making-a-new-event"></div>

### Criando um novo evento

Você pode criar um novo evento executando o comando abaixo no terminal.

``` bash
metro make:event login_event
```

Isso criará um novo evento em `lib/app/events`.

<div id="forcefully-make-an-event"></div>

### Forçar criação de um evento

**Argumentos:**

Usando a flag `--force` ou `-f`, um evento existente será sobrescrito caso já exista.

``` bash
metro make:event login_event --force
```

<div id="make-provider"></div>

## Make provider

- [Criando um novo provider](#making-a-new-provider "Criar um novo provider com Metro")
- [Forçar criação de um provider](#forcefully-make-a-provider "Forçar criação de um novo provider com Metro")

<div id="making-a-new-provider"></div>

### Criando um novo provider

Crie novos providers na sua aplicação usando o comando abaixo.

``` bash
metro make:provider firebase_provider
```

Ele colocará o provider recém-criado em `lib/app/providers/`.

<div id="forcefully-make-a-provider"></div>

### Forçar criação de um provider

**Argumentos:**

Usando a flag `--force` ou `-f`, um provider existente será sobrescrito caso já exista.

``` bash
metro make:provider firebase_provider --force
```

<div id="make-theme"></div>

## Make theme

- [Criando um novo tema](#making-a-new-theme "Criar um novo tema com Metro")
- [Forçar criação de um tema](#forcefully-make-a-theme "Forçar criação de um novo tema com Metro")

<div id="making-a-new-theme"></div>

### Criando um novo tema

Você pode criar temas executando o comando abaixo no terminal.

``` bash
metro make:theme bright_theme
```

Isso criará um novo tema em `lib/resources/themes/`.

<div id="forcefully-make-a-theme"></div>

### Forçar criação de um tema

**Argumentos:**

Usando a flag `--force` ou `-f`, um tema existente será sobrescrito caso já exista.

``` bash
metro make:theme bright_theme --force
```

<div id="make-forms"></div>

## Make Forms

- [Criando um novo formulário](#making-a-new-form "Criar um novo formulário com Metro")
- [Forçar criação de um formulário](#forcefully-make-a-form "Forçar criação de um novo formulário com Metro")

<div id="making-a-new-form"></div>

### Criando um novo formulário

Você pode criar um novo formulário executando o comando abaixo no terminal.

``` bash
metro make:form car_advert_form
```

Isso criará um novo formulário em `lib/app/forms`.

<div id="forcefully-make-a-form"></div>

### Forçar criação de um formulário

**Argumentos:**

Usando a flag `--force` ou `-f`, um formulário existente será sobrescrito caso já exista.

``` bash
metro make:form login_form --force
```

<div id="make-route-guard"></div>

## Make Route Guard

- [Criando um novo route guard](#making-a-new-route-guard "Criar um novo route guard com Metro")
- [Forçar criação de um route guard](#forcefully-make-a-route-guard "Forçar criação de um novo route guard com Metro")

<div id="making-a-new-route-guard"></div>

### Criando um novo route guard

Você pode criar um route guard executando o comando abaixo no terminal.

``` bash
metro make:route_guard premium_content
```

Isso criará um novo route guard em `lib/app/route_guards`.

<div id="forcefully-make-a-route-guard"></div>

### Forçar criação de um route guard

**Argumentos:**

Usando a flag `--force` ou `-f`, um route guard existente será sobrescrito caso já exista.

``` bash
metro make:route_guard premium_content --force
```

<div id="make-config-file"></div>

## Make Config File

- [Criando um novo arquivo de configuração](#making-a-new-config-file "Criar um novo arquivo de configuração com Metro")
- [Forçar criação de um arquivo de configuração](#forcefully-make-a-config-file "Forçar criação de um novo arquivo de configuração com Metro")

<div id="making-a-new-config-file"></div>

### Criando um novo arquivo de configuração

Você pode criar um novo arquivo de configuração executando o comando abaixo no terminal.

``` bash
metro make:config shopping_settings
```

Isso criará um novo arquivo de configuração em `lib/app/config`.

<div id="forcefully-make-a-config-file"></div>

### Forçar criação de um arquivo de configuração

**Argumentos:**

Usando a flag `--force` ou `-f`, um arquivo de configuração existente será sobrescrito caso já exista.

``` bash
metro make:config app_config --force
```


<div id="make-command"></div>

## Make Command

- [Criando um novo comando](#making-a-new-command "Criar um novo comando com Metro")
- [Forçar criação de um comando](#forcefully-make-a-command "Forçar criação de um novo comando com Metro")

<div id="making-a-new-command"></div>

### Criando um novo comando

Você pode criar um novo comando executando o comando abaixo no terminal.

``` bash
metro make:command my_command
```

Isso criará um novo comando em `lib/app/commands`.

<div id="forcefully-make-a-command"></div>

### Forçar criação de um comando

**Argumentos:**
Usando a flag `--force` ou `-f`, um comando existente será sobrescrito caso já exista.

``` bash
metro make:command my_command --force
```


<div id="make-state-managed-widget"></div>

## Make State Managed Widget

Você pode criar um novo state managed widget executando o comando abaixo no terminal.

``` bash
metro make:state_managed_widget product_rating_widget
```

O comando acima criará um novo widget em `lib/resources/widgets/`.

Usando a flag `--force` ou `-f`, um widget existente será sobrescrito caso já exista.

``` bash
metro make:state_managed_widget product_rating_widget --force
```

<div id="make-navigation-hub"></div>

## Make Navigation Hub

Você pode criar um novo navigation hub executando o comando abaixo no terminal.

``` bash
metro make:navigation_hub dashboard
```

Isso criará um novo navigation hub em `lib/resources/pages/` e adicionará a rota automaticamente.

**Argumentos:**

| Flag | Abreviação | Descrição |
|------|-------|-------------|
| `--auth` | `-a` | Criar como uma página de autenticação |
| `--initial` | `-i` | Criar como a página inicial |
| `--force` | `-f` | Sobrescrever se existir |

``` bash
# Criar como a página inicial
metro make:navigation_hub dashboard --initial
```

<div id="make-bottom-sheet-modal"></div>

## Make Bottom Sheet Modal

Você pode criar um novo bottom sheet modal executando o comando abaixo no terminal.

``` bash
metro make:bottom_sheet_modal payment_options
```

Isso criará um novo bottom sheet modal em `lib/resources/widgets/`.

Usando a flag `--force` ou `-f`, um modal existente será sobrescrito caso já exista.

``` bash
metro make:bottom_sheet_modal payment_options --force
```

<div id="make-button"></div>

## Make Button

Você pode criar um novo widget de botão executando o comando abaixo no terminal.

``` bash
metro make:button checkout_button
```

Isso criará um novo widget de botão em `lib/resources/widgets/`.

Usando a flag `--force` ou `-f`, um botão existente será sobrescrito caso já exista.

``` bash
metro make:button checkout_button --force
```

<div id="make-interceptor"></div>

## Make Interceptor

Você pode criar um novo interceptor de rede executando o comando abaixo no terminal.

``` bash
metro make:interceptor auth_interceptor
```

Isso criará um novo interceptor em `lib/app/networking/dio/interceptors/`.

Usando a flag `--force` ou `-f`, um interceptor existente será sobrescrito caso já exista.

``` bash
metro make:interceptor auth_interceptor --force
```

<div id="make-env-file"></div>

## Make Env File

Você pode criar um novo arquivo de ambiente executando o comando abaixo no terminal.

``` bash
metro make:env .env.staging
```

Isso criará um novo arquivo `.env` na raiz do seu projeto.

<div id="make-key"></div>

## Make Key

Gere uma `APP_KEY` segura para criptografia de ambiente. Isso é usado para arquivos `.env` criptografados na v7.

``` bash
metro make:key
```

**Argumentos:**

| Flag / Opção | Abreviação | Descrição |
|---------------|-------|-------------|
| `--force` | `-f` | Sobrescrever APP_KEY existente |
| `--file` | `-e` | Arquivo .env de destino (padrão: `.env`) |

``` bash
# Gerar chave e sobrescrever existente
metro make:key --force

# Gerar chave para um arquivo env específico
metro make:key --file=.env.production
```

<div id="build-app-icons"></div>

## Gerando Ícones do App

Você pode gerar todos os ícones do app para iOS e Android executando o comando abaixo.

``` bash
dart run flutter_launcher_icons:main
```

Isso usa a configuração <b>flutter_icons</b> no seu arquivo `pubspec.yaml`.

<div id="custom-commands"></div>

## Comandos Personalizados

Comandos personalizados permitem que você estenda o CLI do Nylo com seus próprios comandos específicos do projeto. Essa funcionalidade permite automatizar tarefas repetitivas, implementar fluxos de deploy ou adicionar qualquer funcionalidade personalizada diretamente nas ferramentas de linha de comando do seu projeto.

- [Criando comandos personalizados](#creating-custom-commands)
- [Executando Comandos Personalizados](#running-custom-commands)
- [Adicionando opções aos comandos](#adding-options-to-custom-commands)
- [Adicionando flags aos comandos](#adding-flags-to-custom-commands)
- [Métodos auxiliares](#custom-command-helper-methods)

> **Nota:** Atualmente você não pode importar nylo_framework.dart nos seus comandos personalizados, por favor use ny_cli.dart em vez disso.

<div id="creating-custom-commands"></div>

## Criando Comandos Personalizados

Para criar um novo comando personalizado, você pode usar a funcionalidade `make:command`:

```bash
metro make:command current_time
```

Você pode especificar uma categoria para seu comando usando a opção `--category`:

```bash
# Especificar uma categoria
metro make:command current_time --category="project"
```

Isso criará um novo arquivo de comando em `lib/app/commands/current_time.dart` com a seguinte estrutura:

``` dart
import 'package:nylo_framework/metro/ny_cli.dart';

void main(arguments) => _CurrentTimeCommand(arguments).run();

/// Current Time Command
///
/// Usage:
///   metro app:current_time
class _CurrentTimeCommand extends NyCustomCommand {
  _CurrentTimeCommand(super.arguments);

  @override
  CommandBuilder builder(CommandBuilder command) {
    command.addOption('format', defaultValue: 'HH:mm:ss');
    return command;
  }

  @override
  Future<void> handle(CommandResult result) async {
      final format = result.getString("format");

      // Get the current time
      final now = DateTime.now();
      final DateFormat dateFormat = DateFormat(format);

      // Format the current time
      final formattedTime = dateFormat.format(now);
      info("The current time is " + formattedTime);
  }
}
```

O comando será automaticamente registrado no arquivo `lib/app/commands/commands.json`, que contém uma lista de todos os comandos registrados:

```json
[
  {
    "name": "install_firebase",
    "category": "project",
    "script": "install_firebase.dart"
  },
  {
    "name": "current_time",
    "category": "app",
    "script": "current_time.dart"
  }
]
```

<div id="running-custom-commands"></div>

## Executando Comandos Personalizados

Uma vez criado, você pode executar seu comando personalizado usando o atalho do Metro ou o comando Dart completo:

```bash
metro app:current_time
```

Quando você executa `metro` sem argumentos, você verá seus comandos personalizados listados no menu na seção "Custom Commands":

```
[Custom Commands]
  app:app_icon
  app:clear_pub
  project:install_firebase
  project:deploy
```

Para exibir informações de ajuda para seu comando, use a flag `--help` ou `-h`:

```bash
metro project:install_firebase --help
```

<div id="adding-options-to-custom-commands"></div>

## Adicionando Opções aos Comandos

Opções permitem que seu comando aceite entrada adicional dos usuários. Você pode adicionar opções ao seu comando no método `builder`:

```dart
@override
CommandBuilder builder(CommandBuilder command) {

  // Adicionar uma opção com valor padrão
  command.addOption(
    'environment',     // nome da opção
    abbr: 'e',         // abreviação
    help: 'Target deployment environment', // texto de ajuda
    defaultValue: 'development',  // valor padrão
    allowed: ['development', 'staging', 'production'] // valores permitidos
  );

  return command;
}
```

Em seguida, acesse o valor da opção no método `handle` do seu comando:

```dart
@override
Future<void> handle(CommandResult result) async {
  final environment = result.getString('environment');
  info('Deploying to $environment environment...');

  // Implementação do comando...
}
```

Exemplo de uso:

```bash
metro project:deploy --environment=production
# ou usando abreviação
metro project:deploy -e production
```

<div id="adding-flags-to-custom-commands"></div>

## Adicionando Flags aos Comandos

Flags são opções booleanas que podem ser ativadas ou desativadas. Adicione flags ao seu comando usando o método `addFlag`:

```dart
@override
CommandBuilder builder(CommandBuilder command) {

  command.addFlag(
    'verbose',       // nome da flag
    abbr: 'v',       // abreviação
    help: 'Enable verbose output', // texto de ajuda
    defaultValue: false  // desativado por padrão
  );

  return command;
}
```

Em seguida, verifique o estado da flag no método `handle` do seu comando:

```dart
@override
Future<void> handle(CommandResult result) async {
  final verbose = result.getBool('verbose');

  if (verbose) {
    info('Verbose mode enabled');
    // Logging adicional...
  }

  // Implementação do comando...
}
```

Exemplo de uso:

```bash
metro project:deploy --verbose
# ou usando abreviação
metro project:deploy -v
```

<div id="custom-command-helper-methods"></div>

## Métodos Auxiliares

A classe base `NyCustomCommand` fornece vários métodos auxiliares para ajudar com tarefas comuns:

#### Imprimindo Mensagens

Aqui estão alguns métodos para imprimir mensagens em diferentes cores:

| |  |
|-------------|-------------|
| [`info`](#custom-command-helper-formatting)      | Imprimir uma mensagem informativa em texto azul |
| [`error`](#custom-command-helper-formatting)     | Imprimir uma mensagem de erro em texto vermelho |
| [`success`](#custom-command-helper-formatting)   | Imprimir uma mensagem de sucesso em texto verde |
| [`warning`](#custom-command-helper-formatting)   | Imprimir uma mensagem de aviso em texto amarelo |

#### Executando Processos

Execute processos e exiba a saída no console:

| |  |
|-------------|-------------|
| [`addPackage`](#custom-command-helper-add-package) | Adicionar um pacote ao `pubspec.yaml` |
| [`addPackages`](#custom-command-helper-add-packages) | Adicionar múltiplos pacotes ao `pubspec.yaml` |
| [`runProcess`](#custom-command-helper-run-process) | Executar um processo externo e exibir saída no console |
| [`prompt`](#custom-command-helper-prompt)    | Coletar entrada do usuário como texto |
| [`confirm`](#custom-command-helper-confirm)   | Fazer uma pergunta sim/não e retornar um resultado booleano |
| [`select`](#custom-command-helper-select)    | Apresentar uma lista de opções e deixar o usuário selecionar uma |
| [`multiSelect`](#custom-command-helper-multi-select) | Permitir que o usuário selecione múltiplas opções de uma lista |

#### Requisições de Rede

Fazendo requisições de rede via console:

| |  |
|-------------|-------------|
| [`api`](#custom-command-helper-multi-select) | Fazer uma chamada de API usando o cliente API do Nylo |


#### Loading Spinner

Exibir um spinner de carregamento enquanto executa uma função:

| |  |
|-------------|-------------|
| [`withSpinner`](#using-with-spinner) | Mostrar um spinner de carregamento enquanto executa uma função |
| [`createSpinner`](#manual-spinner-control) | Criar uma instância de spinner para controle manual |

#### Helpers de Comandos Personalizados

Você também pode usar os seguintes métodos auxiliares para gerenciar argumentos de comandos:

| |  |
|-------------|-------------|
| [`getString`](#custom-command-helper-get-string) | Obter um valor string dos argumentos do comando |
| [`getBool`](#custom-command-helper-get-bool)   | Obter um valor booleano dos argumentos do comando |
| [`getInt`](#custom-command-helper-get-int)    | Obter um valor inteiro dos argumentos do comando |
| [`sleep`](#custom-command-helper-sleep) | Pausar a execução por uma duração especificada |


### Executando Processos Externos

```dart
// Executar um processo com saída exibida no console
await runProcess('flutter build web --release');

// Executar um processo silenciosamente
await runProcess('flutter pub get', silent: true);

// Executar um processo em um diretório específico
await runProcess('git pull', workingDirectory: './my-project');
```

### Gerenciamento de Pacotes

<div id="custom-command-helper-add-package"></div>
<div id="custom-command-helper-add-packages"></div>

```dart
// Adicionar um pacote ao pubspec.yaml
addPackage('firebase_core', version: '^2.4.0');

// Adicionar um pacote dev ao pubspec.yaml
addPackage('build_runner', dev: true);

// Adicionar múltiplos pacotes de uma vez
addPackages(['firebase_auth', 'firebase_storage', 'quickalert']);
```

<div id="custom-command-helper-formatting"></div>

### Formatação de Saída

```dart
// Imprimir mensagens de status com codificação de cores
info('Processing files...');    // Texto azul
error('Operation failed');      // Texto vermelho
success('Deployment complete'); // Texto verde
warning('Outdated package');    // Texto amarelo
```

<div id="interactive-input-methods"></div>

## Métodos de Entrada Interativa

A classe base `NyCustomCommand` fornece vários métodos para coletar entrada do usuário no terminal. Esses métodos facilitam a criação de interfaces de linha de comando interativas para seus comandos personalizados.

<div id="custom-command-helper-prompt"></div>

### Entrada de Texto

```dart
String prompt(String question, {String defaultValue = ''})
```

Exibe uma pergunta ao usuário e coleta sua resposta em texto.

**Parâmetros:**
- `question`: A pergunta ou prompt a ser exibido
- `defaultValue`: Valor padrão opcional se o usuário apenas pressionar Enter

**Retorna:** A entrada do usuário como string, ou o valor padrão se nenhuma entrada for fornecida

**Exemplo:**
```dart
final name = prompt('What is your project name?', defaultValue: 'my_app');
final description = prompt('Enter a project description:');
```

<div id="custom-command-helper-confirm"></div>

### Confirmação

```dart
bool confirm(String question, {bool defaultValue = false})
```

Faz uma pergunta sim/não ao usuário e retorna um resultado booleano.

**Parâmetros:**
- `question`: A pergunta sim/não a ser feita
- `defaultValue`: A resposta padrão (true para sim, false para não)

**Retorna:** `true` se o usuário respondeu sim, `false` se respondeu não

**Exemplo:**
```dart
if (confirm('Would you like to continue?', defaultValue: true)) {
  // Usuário confirmou ou pressionou Enter (aceitando o padrão)
  await runProcess('flutter pub get');
} else {
  // Usuário recusou
  info('Operation canceled');
}
```

<div id="custom-command-helper-select"></div>

### Seleção Única

```dart
String select(String question, List<String> options, {String? defaultOption})
```

Apresenta uma lista de opções e permite ao usuário selecionar uma.

**Parâmetros:**
- `question`: O prompt de seleção
- `options`: Lista de opções disponíveis
- `defaultOption`: Seleção padrão opcional

**Retorna:** A opção selecionada como string

**Exemplo:**
```dart
final environment = select(
  'Select deployment environment:',
  ['development', 'staging', 'production'],
  defaultOption: 'development'
);

info('Deploying to $environment environment...');
```

<div id="custom-command-helper-multi-select"></div>

### Seleção Múltipla

```dart
List<String> multiSelect(String question, List<String> options)
```

Permite ao usuário selecionar múltiplas opções de uma lista.

**Parâmetros:**
- `question`: O prompt de seleção
- `options`: Lista de opções disponíveis

**Retorna:** Uma lista das opções selecionadas

**Exemplo:**
```dart
final packages = multiSelect(
  'Select packages to install:',
  ['firebase_auth', 'dio', 'provider', 'shared_preferences', 'path_provider']
);

if (packages.isNotEmpty) {
  info('Installing ${packages.length} packages...');
  addPackages(packages);
  await runProcess('flutter pub get');
}
```

<div id="custom-command-helper-api"></div>

## Método Auxiliar de API

O método auxiliar `api` simplifica a realização de requisições de rede a partir dos seus comandos personalizados.

```dart
Future<T?> api<T>(Future<T?> Function(ApiService) request) async
```

## Exemplos Básicos de Uso

### Requisição GET

```dart
// Buscar dados
final userData = await api((request) =>
  request.get('https://api.example.com/users/1')
);
```

### Requisição POST

```dart
// Criar um recurso
final result = await api((request) =>
  request.post(
    'https://api.example.com/items',
    data: {'name': 'New Item', 'price': 19.99}
  )
);
```

### Requisição PUT

```dart
// Atualizar um recurso
final updateResult = await api((request) =>
  request.put(
    'https://api.example.com/items/42',
    data: {'name': 'Updated Item', 'price': 29.99}
  )
);
```

### Requisição DELETE

```dart
// Excluir um recurso
final deleteResult = await api((request) => request.delete('https://api.example.com/items/42'));
```

### Requisição PATCH

```dart
// Atualizar parcialmente um recurso
final patchResult = await api((request) => request.patch(
    'https://api.example.com/items/42',
    data: {'price': 24.99}
  )
);
```

### Com Parâmetros de Query

```dart
// Adicionar parâmetros de query
final searchResults = await api((request) => request.get(
    'https://api.example.com/search',
    queryParameters: {'q': 'keyword', 'limit': 10}
  )
);
```

### Com Spinner

```dart
// Usando com spinner para melhor UX
final data = await withSpinner(
  task: () async {
    final data = await api((request) => request.get('https://api.example.com/config'));
    // Processar os dados
  },
  message: 'Loading configuration',
);
```


<div id="using-spinners"></div>

## Funcionalidade de Spinner

Spinners fornecem feedback visual durante operações de longa duração nos seus comandos personalizados. Eles exibem um indicador animado junto com uma mensagem enquanto seu comando executa tarefas assíncronas, melhorando a experiência do usuário ao mostrar progresso e status.

- [Usando with spinner](#using-with-spinner)
- [Controle manual do spinner](#manual-spinner-control)
- [Exemplos](#spinner-examples)

<div id="using-with-spinner"></div>

## Usando with spinner

O método `withSpinner` permite envolver uma tarefa assíncrona com uma animação de spinner que inicia automaticamente quando a tarefa começa e para quando ela completa ou falha:

```dart
Future<T> withSpinner<T>({
  required Future<T> Function() task,
  required String message,
  String? successMessage,
  String? errorMessage,
}) async
```

**Parâmetros:**
- `task`: A função assíncrona a ser executada
- `message`: Texto a ser exibido enquanto o spinner está rodando
- `successMessage`: Mensagem opcional a ser exibida ao completar com sucesso
- `errorMessage`: Mensagem opcional a ser exibida se a tarefa falhar

**Retorna:** O resultado da função task

**Exemplo:**
```dart
@override
Future<void> handle(CommandResult result) async {
  // Executar uma tarefa com spinner
  final projectFiles = await withSpinner(
    task: () async {
      // Tarefa de longa duração (ex: analisando arquivos do projeto)
      await sleep(2);
      return ['pubspec.yaml', 'lib/main.dart', 'README.md'];
    },
    message: 'Analyzing project structure',
    successMessage: 'Project analysis complete',
    errorMessage: 'Failed to analyze project',
  );

  // Continuar com os resultados
  info('Found ${projectFiles.length} key files');
}
```

<div id="manual-spinner-control"></div>

## Controle Manual do Spinner

Para cenários mais complexos onde você precisa controlar o estado do spinner manualmente, você pode criar uma instância de spinner:

```dart
ConsoleSpinner createSpinner(String message)
```

**Parâmetros:**
- `message`: Texto a ser exibido enquanto o spinner está rodando

**Retorna:** Uma instância de `ConsoleSpinner` que você pode controlar manualmente

**Exemplo com controle manual:**
```dart
@override
Future<void> handle(CommandResult result) async {
  // Criar uma instância de spinner
  final spinner = createSpinner('Deploying to production');
  spinner.start();

  try {
    // Primeira tarefa
    await runProcess('flutter clean', silent: true);
    spinner.update('Building release version');

    // Segunda tarefa
    await runProcess('flutter build web --release', silent: true);
    spinner.update('Uploading to server');

    // Terceira tarefa
    await runProcess('./deploy.sh', silent: true);

    // Completar com sucesso
    spinner.stop(completionMessage: 'Deployment completed successfully', success: true);
  } catch (e) {
    // Tratar falha
    spinner.stop(completionMessage: 'Deployment failed: $e', success: false);
    rethrow;
  }
}
```

<div id="spinner-examples"></div>

## Exemplos

### Tarefa Simples com Spinner

```dart
@override
Future<void> handle(CommandResult result) async {
  await withSpinner(
    task: () async {
      // Instalar dependências
      await runProcess('flutter pub get', silent: true);
      return true;
    },
    message: 'Installing dependencies',
    successMessage: 'Dependencies installed successfully',
  );
}
```

### Múltiplas Operações Consecutivas

```dart
@override
Future<void> handle(CommandResult result) async {
  // Primeira operação com spinner
  await withSpinner(
    task: () => runProcess('flutter clean', silent: true),
    message: 'Cleaning project',
  );

  // Segunda operação com spinner
  await withSpinner(
    task: () => runProcess('flutter pub get', silent: true),
    message: 'Updating dependencies',
  );

  // Terceira operação com spinner
  final buildSuccess = await withSpinner(
    task: () async {
      await runProcess('flutter build apk --release', silent: true);
      return true;
    },
    message: 'Building release APK',
    successMessage: 'Release APK built successfully',
  );

  if (buildSuccess) {
    success('Build process completed');
  }
}
```

### Fluxo de Trabalho Complexo com Controle Manual

```dart
@override
Future<void> handle(CommandResult result) async {
  final spinner = createSpinner('Starting deployment process');
  spinner.start();

  try {
    // Executar múltiplos passos com atualizações de status
    spinner.update('Step 1: Cleaning project');
    await runProcess('flutter clean', silent: true);

    spinner.update('Step 2: Fetching dependencies');
    await runProcess('flutter pub get', silent: true);

    spinner.update('Step 3: Building release');
    await runProcess('flutter build web --release', silent: true);

    // Completar o processo
    spinner.stop(completionMessage: 'Deployment completed successfully', success: true);

  } catch (e) {
    spinner.stop(completionMessage: 'Deployment failed: $e', success: false);
  }
}
```

Usar spinners nos seus comandos personalizados fornece feedback visual claro aos usuários durante operações de longa duração, criando uma experiência de linha de comando mais polida e profissional.

<div id="custom-command-helper-get-string"></div>

### Obter um valor string das opções

```dart
String getString(String name, {String defaultValue = ''})
```

**Parâmetros:**

- `name`: O nome da opção a ser recuperada
- `defaultValue`: Valor padrão opcional se a opção não for fornecida

**Retorna:** O valor da opção como string

**Exemplo:**
```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addOption("name", defaultValue: "Anthony");
  return command;
}

Future<void> handle(CommandResult result) async {
  final name = result.getString('name');
  info('Hello, $name!');
}
```

<div id="custom-command-helper-get-bool"></div>

### Obter um valor booleano das opções

```dart
bool getBool(String name, {bool defaultValue = false})
```

**Parâmetros:**
- `name`: O nome da opção a ser recuperada
- `defaultValue`: Valor padrão opcional se a opção não for fornecida

**Retorna:** O valor da opção como booleano


**Exemplo:**
```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addFlag("verbose", defaultValue: false);
  return command;
}

Future<void> handle(CommandResult result) async {
  final verbose = result.getBool('verbose');
  if (verbose) {
    info('Verbose mode enabled');
  } else {
    info('Verbose mode disabled');
  }
}
```

<div id="custom-command-helper-get-int"></div>

### Obter um valor inteiro das opções

```dart
int getInt(String name, {int defaultValue = 0})
```

**Parâmetros:**
- `name`: O nome da opção a ser recuperada
- `defaultValue`: Valor padrão opcional se a opção não for fornecida

**Retorna:** O valor da opção como inteiro

**Exemplo:**
```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addOption("count", defaultValue: 5);
  return command;
}

Future<void> handle(CommandResult result) async {
  final count = result.getInt('count');
  info('Count is set to $count');
}
```

<div id="custom-command-helper-sleep"></div>

### Pausar por uma duração especificada

```dart
void sleep(int seconds)
```

**Parâmetros:**
- `seconds`: O número de segundos para pausar

**Retorna:** Nenhum

**Exemplo:**
```dart
@override
Future<void> handle(CommandResult result) async {
  info('Sleeping for 5 seconds...');
  await sleep(5);
  info('Awake now!');
}
```

<div id="output-formatting"></div>

## Formatação de Saída

Além dos métodos básicos `info`, `error`, `success` e `warning`, `NyCustomCommand` fornece helpers adicionais de saída:

```dart
@override
Future<void> handle(CommandResult result) async {
  // Imprimir texto simples (sem cor)
  line('Processing your request...');

  // Imprimir linhas em branco
  newLine();       // uma linha em branco
  newLine(3);      // três linhas em branco

  // Imprimir um comentário discreto (texto cinza)
  comment('This is a background note');

  // Imprimir uma caixa de alerta proeminente
  alert('Important: Please read carefully');

  // Ask é um alias para prompt
  final name = ask('What is your name?');

  // Entrada oculta para dados sensíveis (ex: senhas, chaves de API)
  final apiKey = promptSecret('Enter your API key:');

  // Abortar o comando com uma mensagem de erro e código de saída
  if (name.isEmpty) {
    abort('Name is required');  // sai com código 1
  }
}
```

| Método | Descrição |
|--------|-------------|
| `line(String message)` | Imprimir texto simples sem cor |
| `newLine([int count = 1])` | Imprimir linhas em branco |
| `comment(String message)` | Imprimir texto discreto/cinza |
| `alert(String message)` | Imprimir uma caixa de alerta proeminente |
| `ask(String question, {String defaultValue})` | Alias para `prompt` |
| `promptSecret(String question)` | Entrada oculta para dados sensíveis |
| `abort([String? message, int exitCode = 1])` | Sair do comando com um erro |

<div id="file-system-helpers"></div>

## Helpers de Sistema de Arquivos

`NyCustomCommand` inclui helpers integrados de sistema de arquivos para que você não precise importar manualmente `dart:io` para operações comuns.

### Leitura e Escrita de Arquivos

```dart
@override
Future<void> handle(CommandResult result) async {
  // Verificar se um arquivo existe
  if (fileExists('lib/config/app.dart')) {
    info('Config file found');
  }

  // Verificar se um diretório existe
  if (directoryExists('lib/app/models')) {
    info('Models directory found');
  }

  // Ler um arquivo (assíncrono)
  String content = await readFile('pubspec.yaml');

  // Ler um arquivo (síncrono)
  String contentSync = readFileSync('pubspec.yaml');

  // Escrever em um arquivo (assíncrono)
  await writeFile('lib/generated/output.dart', 'class Output {}');

  // Escrever em um arquivo (síncrono)
  writeFileSync('lib/generated/output.dart', 'class Output {}');

  // Adicionar conteúdo a um arquivo
  await appendFile('log.txt', 'New log entry\n');

  // Garantir que um diretório existe (cria se não existir)
  await ensureDirectory('lib/generated');

  // Excluir um arquivo
  await deleteFile('lib/generated/output.dart');

  // Copiar um arquivo
  await copyFile('lib/config/app.dart', 'lib/config/app.bak.dart');
}
```

| Método | Descrição |
|--------|-------------|
| `fileExists(String path)` | Retorna `true` se o arquivo existir |
| `directoryExists(String path)` | Retorna `true` se o diretório existir |
| `readFile(String path)` | Ler arquivo como string (assíncrono) |
| `readFileSync(String path)` | Ler arquivo como string (síncrono) |
| `writeFile(String path, String content)` | Escrever conteúdo em arquivo (assíncrono) |
| `writeFileSync(String path, String content)` | Escrever conteúdo em arquivo (síncrono) |
| `appendFile(String path, String content)` | Adicionar conteúdo a arquivo |
| `ensureDirectory(String path)` | Criar diretório se não existir |
| `deleteFile(String path)` | Excluir um arquivo |
| `copyFile(String source, String destination)` | Copiar um arquivo |

<div id="json-yaml-helpers"></div>

## Helpers de JSON e YAML

Leia e escreva arquivos JSON e YAML com helpers integrados.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Ler um arquivo JSON como Map
  Map<String, dynamic> config = await readJson('config.json');

  // Ler um arquivo JSON como List
  List<dynamic> items = await readJsonArray('lib/app/commands/commands.json');

  // Escrever dados em um arquivo JSON (formatado por padrão)
  await writeJson('output.json', {'name': 'MyApp', 'version': '1.0.0'});

  // Escrever JSON compacto
  await writeJson('output.json', data, pretty: false);

  // Adicionar um item a um arquivo de array JSON
  // Se o arquivo contém [{"name": "a"}], isso adiciona a esse array
  await appendToJsonArray(
    'lib/app/commands/commands.json',
    {'name': 'my_command', 'category': 'app', 'script': 'my_command.dart'},
    uniqueKey: 'name',  // previne duplicatas por essa chave
  );

  // Ler um arquivo YAML como Map
  Map<String, dynamic> pubspec = await readYaml('pubspec.yaml');
  info('Project: ${pubspec['name']}');
}
```

| Método | Descrição |
|--------|-------------|
| `readJson(String path)` | Ler arquivo JSON como `Map<String, dynamic>` |
| `readJsonArray(String path)` | Ler arquivo JSON como `List<dynamic>` |
| `writeJson(String path, dynamic data, {bool pretty = true})` | Escrever dados como JSON |
| `appendToJsonArray(String path, Map item, {String? uniqueKey})` | Adicionar a um arquivo de array JSON |
| `readYaml(String path)` | Ler arquivo YAML como `Map<String, dynamic>` |

<div id="case-conversion-helpers"></div>

## Helpers de Conversão de Case

Converta strings entre convenções de nomenclatura sem importar o pacote `recase`.

```dart
@override
Future<void> handle(CommandResult result) async {
  String input = 'user profile page';

  info(snakeCase(input));    // user_profile_page
  info(camelCase(input));    // userProfilePage
  info(pascalCase(input));   // UserProfilePage
  info(titleCase(input));    // User Profile Page
  info(kebabCase(input));    // user-profile-page
  info(constantCase(input)); // USER_PROFILE_PAGE
}
```

| Método | Formato de Saída | Exemplo |
|--------|--------------|---------|
| `snakeCase(String input)` | `snake_case` | `user_profile` |
| `camelCase(String input)` | `camelCase` | `userProfile` |
| `pascalCase(String input)` | `PascalCase` | `UserProfile` |
| `titleCase(String input)` | `Title Case` | `User Profile` |
| `kebabCase(String input)` | `kebab-case` | `user-profile` |
| `constantCase(String input)` | `CONSTANT_CASE` | `USER_PROFILE` |

<div id="project-path-helpers"></div>

## Helpers de Caminhos do Projeto

Getters para diretórios padrão de projetos {{ config('app.name') }}. Eles retornam caminhos relativos à raiz do projeto.

```dart
@override
Future<void> handle(CommandResult result) async {
  info(modelsPath);       // lib/app/models
  info(controllersPath);  // lib/app/controllers
  info(widgetsPath);      // lib/resources/widgets
  info(pagesPath);        // lib/resources/pages
  info(commandsPath);     // lib/app/commands
  info(configPath);       // lib/config
  info(providersPath);    // lib/app/providers
  info(eventsPath);       // lib/app/events
  info(networkingPath);   // lib/app/networking
  info(themesPath);       // lib/resources/themes

  // Construir um caminho personalizado relativo à raiz do projeto
  String customPath = projectPath('lib/app/services/auth_service.dart');
}
```

| Propriedade | Caminho |
|----------|------|
| `modelsPath` | `lib/app/models` |
| `controllersPath` | `lib/app/controllers` |
| `widgetsPath` | `lib/resources/widgets` |
| `pagesPath` | `lib/resources/pages` |
| `commandsPath` | `lib/app/commands` |
| `configPath` | `lib/config` |
| `providersPath` | `lib/app/providers` |
| `eventsPath` | `lib/app/events` |
| `networkingPath` | `lib/app/networking` |
| `themesPath` | `lib/resources/themes` |
| `projectPath(String relativePath)` | Resolve um caminho relativo dentro do projeto |

<div id="platform-helpers"></div>

## Helpers de Plataforma

Verifique a plataforma e acesse variáveis de ambiente.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Verificações de plataforma
  if (isWindows) {
    info('Running on Windows');
  } else if (isMacOS) {
    info('Running on macOS');
  } else if (isLinux) {
    info('Running on Linux');
  }

  // Diretório de trabalho atual
  info('Working in: $workingDirectory');

  // Ler variáveis de ambiente do sistema
  String home = env('HOME', '/default/path');
}
```

| Propriedade / Método | Descrição |
|-------------------|-------------|
| `isWindows` | `true` se estiver rodando no Windows |
| `isMacOS` | `true` se estiver rodando no macOS |
| `isLinux` | `true` se estiver rodando no Linux |
| `workingDirectory` | Caminho do diretório de trabalho atual |
| `env(String key, [String defaultValue = ''])` | Ler variável de ambiente do sistema |

<div id="dart-flutter-commands"></div>

## Comandos Dart e Flutter

Execute comandos comuns do CLI Dart e Flutter como métodos auxiliares. Cada um retorna o código de saída do processo.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Formatar um arquivo ou diretório Dart
  await dartFormat('lib/app/models/user.dart');

  // Executar dart analyze
  int analyzeResult = await dartAnalyze('lib/');

  // Executar flutter pub get
  await flutterPubGet();

  // Executar flutter clean
  await flutterClean();

  // Build para um alvo com argumentos adicionais
  await flutterBuild('apk', args: ['--release', '--split-per-abi']);
  await flutterBuild('web', args: ['--release']);

  // Executar flutter test
  await flutterTest();
  await flutterTest('test/unit/');  // diretório específico
}
```

| Método | Descrição |
|--------|-------------|
| `dartFormat(String path)` | Executar `dart format` em um arquivo ou diretório |
| `dartAnalyze([String? path])` | Executar `dart analyze` |
| `flutterPubGet()` | Executar `flutter pub get` |
| `flutterClean()` | Executar `flutter clean` |
| `flutterBuild(String target, {List<String> args})` | Executar `flutter build <target>` |
| `flutterTest([String? path])` | Executar `flutter test` |

<div id="dart-file-manipulation"></div>

## Manipulação de Arquivos Dart

Helpers para editar programaticamente arquivos Dart, úteis ao criar ferramentas de scaffolding.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Adicionar uma instrução de import a um arquivo Dart (evita duplicatas)
  await addImport(
    'lib/bootstrap/providers.dart',
    "import '/app/providers/firebase_provider.dart';",
  );

  // Inserir código antes da última chave de fechamento em um arquivo
  // Útil para adicionar entradas a mapas de registro
  await insertBeforeClosingBrace(
    'lib/bootstrap/providers.dart',
    '  FirebaseProvider(),',
  );

  // Verificar se um arquivo contém uma string específica
  bool hasImport = await fileContains(
    'lib/bootstrap/providers.dart',
    'firebase_provider',
  );

  // Verificar se um arquivo corresponde a um padrão regex
  bool hasClass = await fileContainsPattern(
    'lib/app/models/user.dart',
    RegExp(r'class User'),
  );
}
```

| Método | Descrição |
|--------|-------------|
| `addImport(String filePath, String importStatement)` | Adicionar import a arquivo Dart (pula se já presente) |
| `insertBeforeClosingBrace(String filePath, String code)` | Inserir código antes da última `}` no arquivo |
| `fileContains(String filePath, String identifier)` | Verificar se arquivo contém uma string |
| `fileContainsPattern(String filePath, Pattern pattern)` | Verificar se arquivo corresponde a um padrão |

<div id="directory-helpers"></div>

## Helpers de Diretório

Helpers para trabalhar com diretórios e encontrar arquivos.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Listar conteúdo do diretório
  var entities = listDirectory('lib/app/models');
  for (var entity in entities) {
    info(entity.path);
  }

  // Listar recursivamente
  var allEntities = listDirectory('lib/', recursive: true);

  // Encontrar arquivos correspondentes a critérios
  List<File> dartFiles = findFiles(
    'lib/app/models',
    extension: '.dart',
    recursive: true,
  );

  // Encontrar arquivos por padrão de nome
  List<File> testFiles = findFiles(
    'test/',
    namePattern: RegExp(r'_test\.dart$'),
  );

  // Excluir um diretório recursivamente
  await deleteDirectory('build/');

  // Copiar um diretório (recursivo)
  await copyDirectory('lib/templates', 'lib/generated');
}
```

| Método | Descrição |
|--------|-------------|
| `listDirectory(String path, {bool recursive = false})` | Listar conteúdo do diretório |
| `findFiles(String directory, {String? extension, Pattern? namePattern, bool recursive = true})` | Encontrar arquivos correspondentes a critérios |
| `deleteDirectory(String path)` | Excluir diretório recursivamente |
| `copyDirectory(String source, String destination)` | Copiar diretório recursivamente |

<div id="validation-helpers"></div>

## Helpers de Validação

Helpers para validar e limpar entrada do usuário para geração de código.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Validar um identificador Dart
  if (!isValidDartIdentifier('MyClass')) {
    error('Invalid Dart identifier');
  }

  // Exigir um primeiro argumento não vazio
  String name = requireArgument(result, message: 'Please provide a name');

  // Limpar um nome de classe (PascalCase, remover sufixos)
  String className = cleanClassName('user_model', removeSuffixes: ['_model']);
  // Retorna: 'User'

  // Limpar um nome de arquivo (snake_case com extensão)
  String fileName = cleanFileName('UserModel', extension: '.dart');
  // Retorna: 'user_model.dart'
}
```

| Método | Descrição |
|--------|-------------|
| `isValidDartIdentifier(String name)` | Validar um nome de identificador Dart |
| `requireArgument(CommandResult result, {String? message})` | Exigir primeiro argumento não vazio ou abortar |
| `cleanClassName(String name, {List<String> removeSuffixes})` | Limpar e converter para PascalCase um nome de classe |
| `cleanFileName(String name, {String extension = '.dart'})` | Limpar e converter para snake_case um nome de arquivo |

<div id="file-scaffolding"></div>

## Scaffolding de Arquivos

Crie um ou vários arquivos com conteúdo usando o sistema de scaffolding.

### Arquivo Único

```dart
@override
Future<void> handle(CommandResult result) async {
  await scaffold(
    path: 'lib/app/services/auth_service.dart',
    content: '''
class AuthService {
  Future<bool> login(String email, String password) async {
    // TODO: implement login
    return false;
  }
}
''',
    force: false,  // não sobrescrever se existir
    successMessage: 'AuthService created',
  );
}
```

### Múltiplos Arquivos

```dart
@override
Future<void> handle(CommandResult result) async {
  await scaffoldMany([
    ScaffoldFile(
      path: 'lib/app/models/product.dart',
      content: 'class Product {}',
      successMessage: 'Product model created',
    ),
    ScaffoldFile(
      path: 'lib/app/networking/product_api_service.dart',
      content: 'class ProductApiService {}',
      successMessage: 'Product API service created',
    ),
  ], force: false);
}
```

A classe `ScaffoldFile` aceita:

| Propriedade | Tipo | Descrição |
|----------|------|-------------|
| `path` | `String` | Caminho do arquivo a ser criado |
| `content` | `String` | Conteúdo do arquivo |
| `successMessage` | `String?` | Mensagem exibida ao sucesso |

<div id="task-runner"></div>

## Executor de Tarefas

Execute uma série de tarefas nomeadas com saída automática de status.

### Executor de Tarefas Básico

```dart
@override
Future<void> handle(CommandResult result) async {
  await runTasks([
    CommandTask(
      'Clean project',
      () => runProcess('flutter clean', silent: true),
    ),
    CommandTask(
      'Fetch dependencies',
      () => runProcess('flutter pub get', silent: true),
    ),
    CommandTask(
      'Run tests',
      () => runProcess('flutter test', silent: true),
      stopOnError: true,  // parar pipeline se falhar (padrão)
    ),
  ]);
}
```

### Executor de Tarefas com Spinner

```dart
@override
Future<void> handle(CommandResult result) async {
  await runTasksWithSpinner([
    CommandTask(
      name: 'Preparing release',
      action: () async {
        await flutterClean();
        await flutterPubGet();
      },
    ),
    CommandTask(
      name: 'Building APK',
      action: () => flutterBuild('apk', args: ['--release']),
    ),
  ]);
}
```

A classe `CommandTask` aceita:

| Propriedade | Tipo | Padrão | Descrição |
|----------|------|---------|-------------|
| `name` | `String` | obrigatório | Nome da tarefa exibido na saída |
| `action` | `Future<void> Function()` | obrigatório | Função assíncrona a ser executada |
| `stopOnError` | `bool` | `true` | Se deve parar tarefas restantes se esta falhar |

<div id="table-output"></div>

## Saída em Tabela

Exiba tabelas ASCII formatadas no console.

```dart
@override
Future<void> handle(CommandResult result) async {
  table(
    ['Name', 'Version', 'Status'],
    [
      ['nylo_framework', '7.0.0', 'installed'],
      ['nylo_support', '7.0.0', 'installed'],
      ['dio', '5.4.0', 'installed'],
    ],
  );
}
```

Saída:

```
┌─────────────────┬─────────┬───────────┐
│ Name            │ Version │ Status    │
├─────────────────┼─────────┼───────────┤
│ nylo_framework  │ 7.0.0   │ installed │
│ nylo_support    │ 7.0.0   │ installed │
│ dio             │ 5.4.0   │ installed │
└─────────────────┴─────────┴───────────┘
```

<div id="progress-bar"></div>

## Barra de Progresso

Exiba uma barra de progresso para operações com contagem de itens conhecida.

### Barra de Progresso Manual

```dart
@override
Future<void> handle(CommandResult result) async {
  // Criar uma barra de progresso para 100 itens
  final progress = progressBar(100, message: 'Processing files');
  progress.start();

  for (int i = 0; i < 100; i++) {
    await Future.delayed(Duration(milliseconds: 50));
    progress.tick();  // incrementar em 1
  }

  progress.complete('All files processed');
}
```

### Processar Itens com Progresso

```dart
@override
Future<void> handle(CommandResult result) async {
  final files = findFiles('lib/', extension: '.dart');

  // Processar itens com rastreamento automático de progresso
  final results = await withProgress<File, String>(
    items: files,
    process: (file, index) async {
      // processar cada arquivo
      return file.path;
    },
    message: 'Analyzing Dart files',
    completionMessage: 'Analysis complete',
  );

  info('Processed ${results.length} files');
}
```

### Progresso Síncrono

```dart
@override
Future<void> handle(CommandResult result) async {
  final items = ['a', 'b', 'c', 'd', 'e'];

  final results = withProgressSync<String, String>(
    items: items,
    process: (item, index) {
      // processamento síncrono
      return item.toUpperCase();
    },
    message: 'Converting items',
  );

  info('Results: $results');
}
```

A classe `ConsoleProgressBar` fornece:

| Método | Descrição |
|--------|-------------|
| `start()` | Iniciar a barra de progresso |
| `tick([int amount = 1])` | Incrementar progresso |
| `update(int value)` | Definir progresso para um valor específico |
| `updateMessage(String newMessage)` | Alterar a mensagem exibida |
| `complete([String? completionMessage])` | Completar com mensagem opcional |
| `stop()` | Parar sem completar |
| `current` | Valor de progresso atual (getter) |
| `percentage` | Progresso como porcentagem (getter) |
