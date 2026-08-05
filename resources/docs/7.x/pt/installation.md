# Installation

---

<a name="section-1"></a>
- [Instalar](#install "Instalar")
- [Executando o Projeto](#running-the-project "Executando o projeto")
- [Metro CLI](#metro-cli "Metro CLI")

<div id="install"></div>

Três comandos levam você de uma pasta vazia a um aplicativo Flutter em execução, com roteamento, rede, temas e geração de código já configurados.

<x-doc-strip label="Antes de começar" items="Flutter SDK instalado, Dart 3" linkText="Requisitos completos" linkHref="/pt/docs/{{ $version }}/requirements" />

## Instalar

Execute estes comandos em ordem. É seguro executar cada um deles novamente.

<x-doc-steps>
<x-doc-step number="1" title="Instale a CLI do Nylo globalmente">
Isso instala a ferramenta CLI do {{ config('app.name') }} globalmente no seu sistema.

``` bash
dart pub global activate nylo_installer
```
</x-doc-step>

<x-doc-step number="2" title="Crie um novo projeto">
Este comando clona o template do {{ config('app.name') }}, configura o projeto com o nome do seu app e instala todas as dependencias automaticamente.

``` bash
nylo new my_app
```
</x-doc-step>

<x-doc-step number="3" title="Configure o alias do Metro">
Isso configura o comando `metro` para o seu projeto, permitindo que voce use os comandos do Metro CLI sem a sintaxe completa `dart run`.

``` bash
cd my_app
nylo init
```
</x-doc-step>
</x-doc-steps>

<x-doc-panel title="O que você recebe" items="Roteamento e navegacao pre-configurados
Boilerplate de servico de API
Configuracao de tema e localizacao
Metro CLI para geracao de codigo" />


<div id="running-the-project"></div>

## Executando o Projeto

Projetos {{ config('app.name') }} rodam como qualquer app Flutter padrao.

<x-doc-tabs tabs="Terminal, Android Studio, VS Code">
<x-doc-tab label="Terminal">

``` bash
flutter run
```

Se o build for bem-sucedido, o app exibira a tela inicial padrao do {{ config('app.name') }}.
</x-doc-tab>

<x-doc-tab label="Android Studio">
Abra a pasta do projeto, escolha um dispositivo no seletor de destino e pressione **Run**.

<a href="https://docs.flutter.dev/tools/android-studio#running-and-debugging" target="_BLANK">Documentação do Flutter: execução e depuração ↗</a>
</x-doc-tab>

<x-doc-tab label="VS Code">
Abra a pasta do projeto e execute **Debug: Start Without Debugging** na paleta de comandos.

<a href="https://docs.flutter.dev/tools/vs-code#run-app-without-breakpoints" target="_BLANK">Documentação do Flutter: executar sem pontos de interrupção ↗</a>
</x-doc-tab>
</x-doc-tabs>


<div id="metro-cli"></div>

## Metro CLI

O Metro gera os arquivos do projeto para você. Execute-o sem argumentos para ver o menu ou chame um comando diretamente.

``` plaintext
$ metro

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
  make:route_guard
  make:config
  make:interceptor
  make:command
  make:env
```

### Referência de comandos

Cada comando recebe um nome, por exemplo, `metro make:page settings_page`

<x-doc-commands title="Comandos de widgets" rows="
metro make:page | Criar uma nova pagina
metro make:stateful_widget | Criar um widget stateful
metro make:stateless_widget | Criar um widget stateless
metro make:state_managed_widget | Criar um widget com gerenciamento de estado
metro make:navigation_hub | Criar um navigation hub (nav inferior)
metro make:journey_widget | Criar um widget de jornada para navigation hub
metro make:bottom_sheet_modal | Criar um modal bottom sheet
metro make:button | Criar um widget de botao personalizado
metro make:form | Criar um formulario com validacao
" />

<x-doc-commands title="Comandos auxiliares" rows="
metro make:model | Criar uma classe modelo
metro make:provider | Criar um provider
metro make:api_service | Criar um servico de API
metro make:controller | Criar um controller
metro make:event | Criar um evento
metro make:route_guard | Criar um route guard
metro make:config | Criar um arquivo de configuracao
metro make:interceptor | Criar um interceptor de rede
metro make:command | Criar um comando Metro personalizado
metro make:env | Gerar configuracao de ambiente a partir do .env
" />

### Exemplo de Uso

``` bash
# Create a new page
metro make:page settings_page

# Create a model
metro make:model User

# Create an API service
metro make:api_service user_api_service
```
