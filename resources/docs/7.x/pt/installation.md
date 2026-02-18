# Installation

---

<a name="section-1"></a>
- [Instalar](#install "Instalar")
- [Executando o Projeto](#running-the-project "Executando o projeto")
- [Metro CLI](#metro-cli "Metro CLI")


<div id="install"></div>

## Instalar

### 1. Instale o nylo_installer globalmente

``` bash
dart pub global activate nylo_installer
```

Isso instala a ferramenta CLI do {{ config('app.name') }} globalmente no seu sistema.

### 2. Crie um novo projeto

``` bash
nylo new my_app
```

Este comando clona o template do {{ config('app.name') }}, configura o projeto com o nome do seu app e instala todas as dependencias automaticamente.

### 3. Configure o alias do Metro CLI

``` bash
cd my_app
nylo init
```

Isso configura o comando `metro` para o seu projeto, permitindo que voce use os comandos do Metro CLI sem a sintaxe completa `dart run`.

Apos a instalacao, voce tera uma estrutura completa de projeto Flutter com:
- Roteamento e navegacao pre-configurados
- Boilerplate de servico de API
- Configuracao de tema e localizacao
- Metro CLI para geracao de codigo


<div id="running-the-project"></div>

## Executando o Projeto

Projetos {{ config('app.name') }} rodam como qualquer app Flutter padrao.

### Usando o Terminal

``` bash
flutter run
```

### Usando uma IDE

- **Android Studio**: <a href="https://docs.flutter.dev/tools/android-studio#running-and-debugging" target="_BLANK">Executando e depurando</a>
- **VS Code**: <a href="https://docs.flutter.dev/tools/vs-code#run-app-without-breakpoints" target="_BLANK">Executar app sem breakpoints</a>

Se o build for bem-sucedido, o app exibira a tela inicial padrao do {{ config('app.name') }}.


<div id="metro-cli"></div>

## Metro CLI

{{ config('app.name') }} inclui uma ferramenta CLI chamada **Metro** para gerar arquivos do projeto.

### Executando o Metro

``` bash
metro
```

Isso exibe o menu do Metro:

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
```

### Referencia de Comandos do Metro

| Comando | Descricao |
|---------|-----------|
| `make:page` | Criar uma nova pagina |
| `make:stateful_widget` | Criar um widget stateful |
| `make:stateless_widget` | Criar um widget stateless |
| `make:state_managed_widget` | Criar um widget com gerenciamento de estado |
| `make:navigation_hub` | Criar um navigation hub (nav inferior) |
| `make:journey_widget` | Criar um widget de jornada para navigation hub |
| `make:bottom_sheet_modal` | Criar um modal bottom sheet |
| `make:button` | Criar um widget de botao personalizado |
| `make:form` | Criar um formulario com validacao |
| `make:model` | Criar uma classe modelo |
| `make:provider` | Criar um provider |
| `make:api_service` | Criar um servico de API |
| `make:controller` | Criar um controller |
| `make:event` | Criar um evento |
| `make:theme` | Criar um tema |
| `make:route_guard` | Criar um route guard |
| `make:config` | Criar um arquivo de configuracao |
| `make:interceptor` | Criar um interceptor de rede |
| `make:command` | Criar um comando Metro personalizado |
| `make:env` | Gerar configuracao de ambiente a partir do .env |

### Exemplo de Uso

``` bash
# Create a new page
metro make:page settings_page

# Create a model
metro make:model User

# Create an API service
metro make:api_service user_api_service
```
