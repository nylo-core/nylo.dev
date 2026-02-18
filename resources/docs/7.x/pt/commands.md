# Commands

---

<a name="section-1"></a>
- [Introdução](#introduction "Introdução")
- [Criando Comandos](#creating-commands "Criando Comandos")
- [Estrutura do Comando](#command-structure "Estrutura do Comando")
- [Executando Comandos](#running-commands "Executando Comandos")
- [Registro de Comandos](#command-registry "Registro de Comandos")
- [Opções e Flags](#options-and-flags "Opções e Flags")
  - [Adicionando Opções](#adding-options "Adicionando Opções")
  - [Adicionando Flags](#adding-flags "Adicionando Flags")
- [Resultado do Comando](#command-result "Resultado do Comando")
- [Entrada Interativa](#interactive-input "Entrada Interativa")
  - [Entrada de Texto](#text-input "Entrada de Texto")
  - [Confirmação](#confirmation "Confirmação")
  - [Seleção Única](#single-selection "Seleção Única")
  - [Seleção Múltipla](#multiple-selection "Seleção Múltipla")
  - [Entrada Secreta](#secret-input "Entrada Secreta")
- [Formatação de Saída](#output-formatting "Formatação de Saída")
- [Spinner e Progresso](#spinner-and-progress "Spinner e Progresso")
  - [Usando withSpinner](#using-withspinner "Usando withSpinner")
  - [Controle Manual do Spinner](#manual-spinner-control "Controle Manual do Spinner")
  - [Barra de Progresso](#progress-bar "Barra de Progresso")
  - [Processando Itens com Progresso](#processing-items-with-progress "Processando Itens com Progresso")
- [Helper de API](#api-helper "Helper de API")
- [Helpers de Sistema de Arquivos](#file-system-helpers "Helpers de Sistema de Arquivos")
- [Helpers de JSON e YAML](#json-and-yaml-helpers "Helpers de JSON e YAML")
- [Manipulação de Arquivos Dart](#dart-file-manipulation "Manipulação de Arquivos Dart")
- [Helpers de Diretório](#directory-helpers "Helpers de Diretório")
- [Helpers de Conversão de Case](#case-conversion-helpers "Helpers de Conversão de Case")
- [Helpers de Caminhos do Projeto](#project-path-helpers "Helpers de Caminhos do Projeto")
- [Helpers de Plataforma](#platform-helpers "Helpers de Plataforma")
- [Comandos Dart e Flutter](#dart-and-flutter-commands "Comandos Dart e Flutter")
- [Helpers de Validação](#validation-helpers "Helpers de Validação")
- [Scaffolding de Arquivos](#file-scaffolding "Scaffolding de Arquivos")
- [Executor de Tarefas](#task-runner "Executor de Tarefas")
- [Saída em Tabela](#table-output "Saída em Tabela")
- [Exemplos](#examples "Exemplos")
  - [Comando de Hora Atual](#current-time-command "Comando de Hora Atual")
  - [Comando de Download de Fontes](#download-fonts-command "Comando de Download de Fontes")
  - [Comando de Pipeline de Deploy](#deployment-pipeline-command "Comando de Pipeline de Deploy")

<div id="introduction"></div>

## Introdução

Comandos permitem que você estenda a CLI do {{ config('app.name') }} com ferramentas personalizadas específicas do projeto. Ao estender `NyCustomCommand`, você pode automatizar tarefas repetitivas, construir workflows de deploy, gerar código ou adicionar qualquer funcionalidade que precisar diretamente no seu terminal.

Todo comando personalizado tem acesso a um conjunto rico de helpers integrados para I/O de arquivos, JSON/YAML, prompts interativos, spinners, barras de progresso, requisições de API e mais -- tudo sem importar pacotes extras.

> **Nota:** Comandos personalizados são executados fora do runtime do Flutter. Você não pode importar `nylo_framework.dart` nos seus comandos. Use `ny_cli.dart` em vez disso.

<div id="creating-commands"></div>

## Criando Comandos

Crie um novo comando usando Metro ou a CLI do Dart:

```bash
metro make:command current_time
```

Você pode especificar uma categoria para o seu comando usando a opção `--category`:

```bash
metro make:command current_time --category="project"
```

Isso cria um novo arquivo em `lib/app/commands/current_time.dart` e o registra no registro de comandos.

<div id="command-structure"></div>

## Estrutura do Comando

Todo comando estende `NyCustomCommand` e implementa dois métodos principais:

- **`builder()`** -- configurar opções e flags
- **`handle()`** -- executar a lógica do comando

```dart
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
    info("Current time format: $format");
  }
}
```

<div id="running-commands"></div>

## Executando Comandos

Execute seu comando usando Metro:

```bash
metro app:current_time
```

O nome do comando segue o padrão `categoria:nome`. Quando você executa `metro` sem argumentos, seus comandos personalizados aparecem na seção **Custom Commands**:

```
[Custom Commands]
  app:current_time
  project:install_firebase
  project:deploy
```

Para exibir a ajuda de um comando:

```bash
metro app:current_time --help
```

<div id="command-registry"></div>

## Registro de Comandos

Todos os comandos personalizados são registrados em `lib/app/commands/commands.json`. Este arquivo é atualizado automaticamente quando você usa `make:command`:

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

Cada entrada possui:

| Campo | Descrição |
|-------|-------------|
| `name` | O nome do comando (usado após o prefixo da categoria) |
| `category` | A categoria do comando (ex: `app`, `project`) |
| `script` | O arquivo Dart em `lib/app/commands/` |

<div id="options-and-flags"></div>

## Opções e Flags

Configure as opções e flags do seu comando no método `builder()` usando `CommandBuilder`.

<div id="adding-options"></div>

### Adicionando Opções

Opções aceitam um valor do usuário:

```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addOption(
    'environment',
    abbr: 'e',
    help: 'Target deployment environment',
    defaultValue: 'development',
    allowed: ['development', 'staging', 'production'],
  );
  return command;
}
```

Uso:

```bash
metro project:deploy --environment=production
# or using abbreviation
metro project:deploy -e production
```

| Parâmetro | Tipo | Descrição |
|-----------|------|-------------|
| `name` | `String` | Nome da opção |
| `abbr` | `String?` | Abreviação de um caractere |
| `help` | `String?` | Texto de ajuda exibido com `--help` |
| `allowed` | `List<String>?` | Restringir a valores permitidos |
| `defaultValue` | `String?` | Padrão se não fornecido |

<div id="adding-flags"></div>

### Adicionando Flags

Flags são toggles booleanos:

```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addFlag(
    'verbose',
    abbr: 'v',
    help: 'Enable verbose output',
    defaultValue: false,
  );
  return command;
}
```

Uso:

```bash
metro project:deploy --verbose
metro project:deploy -v
```

| Parâmetro | Tipo | Descrição |
|-----------|------|-------------|
| `name` | `String` | Nome da flag |
| `abbr` | `String?` | Abreviação de um caractere |
| `help` | `String?` | Texto de ajuda exibido com `--help` |
| `defaultValue` | `bool` | Valor padrão (padrão: `false`) |

<div id="command-result"></div>

## Resultado do Comando

O método `handle()` recebe um objeto `CommandResult` com acessores tipados para ler opções, flags e argumentos parseados.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Get a string option
  final name = result.getString('name');

  // Get a boolean flag
  final verbose = result.getBool('verbose');

  // Get an integer option
  final count = result.getInt('count');

  // Generic typed access
  final value = result.get<String>('key');

  // Built-in flag checks
  if (result.hasForceFlag) { /* --force was passed */ }
  if (result.hasHelpFlag) { /* --help was passed */ }

  // Raw arguments
  List<String> allArgs = result.arguments;
  List<String> unparsed = result.rest;
}
```

| Método / Propriedade | Retorno | Descrição |
|-------------------|---------|-------------|
| `getString(String name, {String? defaultValue})` | `String?` | Obter valor string |
| `getBool(String name, {bool? defaultValue})` | `bool?` | Obter valor booleano |
| `getInt(String name, {int? defaultValue})` | `int?` | Obter valor inteiro |
| `get<T>(String name)` | `T?` | Obter valor tipado |
| `hasForceFlag` | `bool` | Se `--force` foi passado |
| `hasHelpFlag` | `bool` | Se `--help` foi passado |
| `arguments` | `List<String>` | Todos os argumentos da linha de comando |
| `rest` | `List<String>` | Argumentos restantes não parseados |

<div id="interactive-input"></div>

## Entrada Interativa

`NyCustomCommand` fornece métodos para coletar entrada do usuário no terminal.

<div id="text-input"></div>

### Entrada de Texto

```dart
// Ask a question with optional default
final name = prompt('What is your project name?', defaultValue: 'my_app');

// ask() is an alias for prompt()
final description = ask('Enter a description:');
```

| Parâmetro | Tipo | Descrição |
|-----------|------|-------------|
| `question` | `String` | A pergunta a exibir |
| `defaultValue` | `String` | Padrão se o usuário pressionar Enter (padrão: `''`) |

<div id="confirmation"></div>

### Confirmação

```dart
if (confirm('Would you like to continue?', defaultValue: true)) {
  await runProcess('flutter pub get');
} else {
  info('Operation canceled');
}
```

| Parâmetro | Tipo | Descrição |
|-----------|------|-------------|
| `question` | `String` | A pergunta sim/não |
| `defaultValue` | `bool` | Resposta padrão (padrão: `false`) |

<div id="single-selection"></div>

### Seleção Única

```dart
final environment = select(
  'Select deployment environment:',
  ['development', 'staging', 'production'],
  defaultOption: 'development',
);
```

| Parâmetro | Tipo | Descrição |
|-----------|------|-------------|
| `question` | `String` | O texto do prompt |
| `options` | `List<String>` | Opções disponíveis |
| `defaultOption` | `String?` | Opção pré-selecionada |

<div id="multiple-selection"></div>

### Seleção Múltipla

```dart
final packages = multiSelect(
  'Select packages to install:',
  ['firebase_auth', 'dio', 'provider', 'shared_preferences'],
);

if (packages.isNotEmpty) {
  addPackages(packages);
  await runProcess('flutter pub get');
}
```

O usuário insere números separados por vírgula ou `"all"`.

<div id="secret-input"></div>

### Entrada Secreta

```dart
final apiKey = promptSecret('Enter your API key:');
```

A entrada é ocultada do display do terminal. Retorna para entrada visível se o modo de eco não for suportado.

<div id="output-formatting"></div>

## Formatação de Saída

Métodos para imprimir saída estilizada no console:

```dart
@override
Future<void> handle(CommandResult result) async {
  info('Processing files...');       // Blue text
  error('Operation failed');         // Red text
  success('Deployment complete');    // Green text
  warning('Outdated package');       // Yellow text
  line('Plain text output');         // No color
  comment('Background note');        // Gray text
  alert('Important notice');         // Bordered alert box
  newLine();                         // One blank line
  newLine(3);                        // Three blank lines

  // Exit the command with an error
  abort('Fatal error occurred');     // Prints red, exits with code 1
}
```

| Método | Descrição |
|--------|-------------|
| `info(String message)` | Imprimir texto azul |
| `error(String message)` | Imprimir texto vermelho |
| `success(String message)` | Imprimir texto verde |
| `warning(String message)` | Imprimir texto amarelo |
| `line(String message)` | Imprimir texto simples (sem cor) |
| `newLine([int count = 1])` | Imprimir linhas em branco |
| `comment(String message)` | Imprimir texto cinza/suave |
| `alert(String message)` | Imprimir uma caixa de alerta com borda |
| `abort([String? message, int exitCode = 1])` | Sair do comando com um erro |

<div id="spinner-and-progress"></div>

## Spinner e Progresso

Spinners e barras de progresso fornecem feedback visual durante operações de longa duração.

<div id="using-withspinner"></div>

### Usando withSpinner

Envolva uma tarefa assíncrona com um spinner automático:

```dart
final projectFiles = await withSpinner(
  task: () async {
    await sleep(2);
    return ['pubspec.yaml', 'lib/main.dart', 'README.md'];
  },
  message: 'Analyzing project structure',
  successMessage: 'Project analysis complete',
  errorMessage: 'Failed to analyze project',
);

info('Found ${projectFiles.length} key files');
```

| Parâmetro | Tipo | Descrição |
|-----------|------|-------------|
| `task` | `Future<T> Function()` | A função assíncrona a executar |
| `message` | `String` | Texto exibido enquanto o spinner executa |
| `successMessage` | `String?` | Exibido em caso de sucesso |
| `errorMessage` | `String?` | Exibido em caso de falha |

<div id="manual-spinner-control"></div>

### Controle Manual do Spinner

Para workflows de múltiplos passos, crie um spinner e controle-o manualmente:

```dart
final spinner = createSpinner('Deploying to production');
spinner.start();

try {
  await runProcess('flutter clean', silent: true);
  spinner.update('Building release version');

  await runProcess('flutter build web --release', silent: true);
  spinner.update('Uploading to server');

  await runProcess('./deploy.sh', silent: true);
  spinner.stop(completionMessage: 'Deployment completed', success: true);
} catch (e) {
  spinner.stop(completionMessage: 'Deployment failed: $e', success: false);
}
```

**Métodos do ConsoleSpinner:**

| Método | Descrição |
|--------|-------------|
| `start([String? message])` | Iniciar a animação do spinner |
| `update(String message)` | Alterar a mensagem exibida |
| `stop({String? completionMessage, bool success = true})` | Parar o spinner |

<div id="progress-bar"></div>

### Barra de Progresso

Crie e gerencie uma barra de progresso manualmente:

```dart
final progress = progressBar(100, message: 'Processing files');
progress.start();

for (int i = 0; i < 100; i++) {
  await Future.delayed(Duration(milliseconds: 50));
  progress.tick();
}

progress.complete('All files processed');
```

**Métodos do ConsoleProgressBar:**

| Método / Propriedade | Descrição |
|-------------------|-------------|
| `start()` | Iniciar a barra de progresso |
| `tick([int amount = 1])` | Incrementar o progresso |
| `update(int value)` | Definir o progresso para um valor específico |
| `updateMessage(String newMessage)` | Alterar a mensagem exibida |
| `complete([String? completionMessage])` | Completar com mensagem opcional |
| `stop()` | Parar sem completar |
| `current` | Valor atual do progresso (getter) |
| `percentage` | Progresso como porcentagem 0-100 (getter) |

<div id="processing-items-with-progress"></div>

### Processando Itens com Progresso

Processe uma lista de itens com rastreamento automático de progresso:

```dart
// Async processing
final results = await withProgress<File, String>(
  items: findFiles('lib/', extension: '.dart'),
  process: (file, index) async {
    return file.path;
  },
  message: 'Analyzing Dart files',
  completionMessage: 'Analysis complete',
);

// Synchronous processing
final upperItems = withProgressSync<String, String>(
  items: ['a', 'b', 'c', 'd', 'e'],
  process: (item, index) => item.toUpperCase(),
  message: 'Converting items',
);
```

<div id="api-helper"></div>

## Helper de API

O helper `api` fornece um wrapper simplificado em torno do Dio para fazer requisições HTTP:

```dart
// GET request
final userData = await api((request) =>
  request.get('https://api.example.com/users/1')
);

// POST request
final result = await api((request) =>
  request.post(
    'https://api.example.com/items',
    data: {'name': 'New Item', 'price': 19.99},
  )
);

// PUT request
final updateResult = await api((request) =>
  request.put(
    'https://api.example.com/items/42',
    data: {'name': 'Updated Item', 'price': 29.99},
  )
);

// DELETE request
final deleteResult = await api((request) =>
  request.delete('https://api.example.com/items/42')
);

// PATCH request
final patchResult = await api((request) =>
  request.patch(
    'https://api.example.com/items/42',
    data: {'price': 24.99},
  )
);

// With query parameters
final searchResults = await api((request) =>
  request.get(
    'https://api.example.com/search',
    queryParameters: {'q': 'keyword', 'limit': 10},
  )
);
```

Combine com `withSpinner` para uma melhor experiência do usuário:

```dart
final data = await withSpinner(
  task: () => api((request) =>
    request.get('https://api.example.com/config')
  ),
  message: 'Loading configuration',
);
```

O `ApiService` suporta os métodos `get`, `post`, `put`, `delete` e `patch`, cada um aceitando `queryParameters`, `data`, `options` e `cancelToken` opcionais.

<div id="file-system-helpers"></div>

## Helpers de Sistema de Arquivos

Helpers integrados de sistema de arquivos para que você não precise importar `dart:io`:

```dart
// Check existence
if (fileExists('lib/config/app.dart')) { /* ... */ }
if (directoryExists('lib/app/models')) { /* ... */ }

// Read files
String content = await readFile('pubspec.yaml');
String contentSync = readFileSync('pubspec.yaml');

// Write files
await writeFile('lib/generated/output.dart', 'class Output {}');
writeFileSync('lib/generated/output.dart', 'class Output {}');

// Append to a file
await appendFile('log.txt', 'New log entry\n');

// Ensure a directory exists
await ensureDirectory('lib/generated');

// Delete and copy files
await deleteFile('lib/generated/output.dart');
await copyFile('lib/config/app.dart', 'lib/config/app.bak.dart');
```

| Método | Descrição |
|--------|-------------|
| `fileExists(String path)` | Retorna `true` se o arquivo existir |
| `directoryExists(String path)` | Retorna `true` se o diretório existir |
| `readFile(String path)` | Ler arquivo como string (assíncrono) |
| `readFileSync(String path)` | Ler arquivo como string (síncrono) |
| `writeFile(String path, String content)` | Escrever conteúdo em arquivo (assíncrono) |
| `writeFileSync(String path, String content)` | Escrever conteúdo em arquivo (síncrono) |
| `appendFile(String path, String content)` | Anexar conteúdo a um arquivo |
| `ensureDirectory(String path)` | Criar diretório se não existir |
| `deleteFile(String path)` | Excluir um arquivo |
| `copyFile(String source, String destination)` | Copiar um arquivo |

<div id="json-and-yaml-helpers"></div>

## Helpers de JSON e YAML

Leia e escreva arquivos JSON e YAML com helpers integrados:

```dart
// Read JSON as Map
Map<String, dynamic> config = await readJson('config.json');

// Read JSON as List
List<dynamic> items = await readJsonArray('lib/app/commands/commands.json');

// Write JSON (pretty printed by default)
await writeJson('output.json', {'name': 'MyApp', 'version': '1.0.0'});

// Write compact JSON
await writeJson('output.json', data, pretty: false);

// Append to a JSON array file (with duplicate prevention)
await appendToJsonArray(
  'lib/app/commands/commands.json',
  {'name': 'my_command', 'category': 'app', 'script': 'my_command.dart'},
  uniqueKey: 'name',
);

// Read YAML as Map
Map<String, dynamic> pubspec = await readYaml('pubspec.yaml');
info('Project: ${pubspec['name']}');
```

| Método | Descrição |
|--------|-------------|
| `readJson(String path)` | Ler arquivo JSON como `Map<String, dynamic>` |
| `readJsonArray(String path)` | Ler arquivo JSON como `List<dynamic>` |
| `writeJson(String path, dynamic data, {bool pretty = true})` | Escrever dados como JSON |
| `appendToJsonArray(String path, Map item, {String? uniqueKey})` | Anexar a um arquivo de array JSON |
| `readYaml(String path)` | Ler arquivo YAML como `Map<String, dynamic>` |

<div id="dart-file-manipulation"></div>

## Manipulação de Arquivos Dart

Helpers para editar programaticamente arquivos fonte Dart -- útil ao construir ferramentas de scaffolding:

```dart
// Add an import (skips if already present)
await addImport(
  'lib/bootstrap/providers.dart',
  "import '/app/providers/firebase_provider.dart';",
);

// Insert code before the last closing brace
await insertBeforeClosingBrace(
  'lib/bootstrap/providers.dart',
  '  FirebaseProvider(),',
);

// Check if a file contains a string
bool hasImport = await fileContains(
  'lib/bootstrap/providers.dart',
  'firebase_provider',
);

// Check if a file matches a regex pattern
bool hasClass = await fileContainsPattern(
  'lib/app/models/user.dart',
  RegExp(r'class User'),
);
```

| Método | Descrição |
|--------|-------------|
| `addImport(String filePath, String importStatement)` | Adicionar import a arquivo Dart (ignora se presente) |
| `insertBeforeClosingBrace(String filePath, String code)` | Inserir código antes da última `}` no arquivo |
| `fileContains(String filePath, String identifier)` | Verificar se arquivo contém uma string |
| `fileContainsPattern(String filePath, Pattern pattern)` | Verificar se arquivo corresponde a um padrão |

<div id="directory-helpers"></div>

## Helpers de Diretório

Helpers para trabalhar com diretórios e encontrar arquivos:

```dart
// List directory contents
var entities = listDirectory('lib/app/models');
var allEntities = listDirectory('lib/', recursive: true);

// Find files by extension
List<File> dartFiles = findFiles(
  'lib/app/models',
  extension: '.dart',
  recursive: true,
);

// Find files by name pattern
List<File> testFiles = findFiles(
  'test/',
  namePattern: RegExp(r'_test\.dart$'),
);

// Delete a directory recursively
await deleteDirectory('build/');

// Copy a directory recursively
await copyDirectory('lib/templates', 'lib/generated');
```

| Método | Descrição |
|--------|-------------|
| `listDirectory(String path, {bool recursive = false})` | Listar conteúdo do diretório |
| `findFiles(String directory, {String? extension, Pattern? namePattern, bool recursive = true})` | Encontrar arquivos que correspondam aos critérios |
| `deleteDirectory(String path)` | Excluir diretório recursivamente |
| `copyDirectory(String source, String destination)` | Copiar diretório recursivamente |

<div id="case-conversion-helpers"></div>

## Helpers de Conversão de Case

Converta strings entre convenções de nomenclatura sem importar o pacote `recase`:

```dart
String input = 'user profile page';

snakeCase(input);    // user_profile_page
camelCase(input);    // userProfilePage
pascalCase(input);   // UserProfilePage
titleCase(input);    // User Profile Page
kebabCase(input);    // user-profile-page
constantCase(input); // USER_PROFILE_PAGE
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

Getters para diretórios padrão do projeto {{ config('app.name') }}, retornando caminhos relativos à raiz do projeto:

```dart
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

// Build a custom path
String customPath = projectPath('app/services/auth_service.dart');
// Returns: lib/app/services/auth_service.dart
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

Verifique a plataforma e acesse variáveis de ambiente:

```dart
if (isWindows) {
  info('Running on Windows');
} else if (isMacOS) {
  info('Running on macOS');
} else if (isLinux) {
  info('Running on Linux');
}

info('Working in: $workingDirectory');

String home = env('HOME', '/default/path');
```

| Propriedade / Método | Descrição |
|-------------------|-------------|
| `isWindows` | `true` se executando no Windows |
| `isMacOS` | `true` se executando no macOS |
| `isLinux` | `true` se executando no Linux |
| `workingDirectory` | Caminho do diretório de trabalho atual |
| `env(String key, [String defaultValue = ''])` | Ler variável de ambiente do sistema |

<div id="dart-and-flutter-commands"></div>

## Comandos Dart e Flutter

Execute comandos comuns da CLI do Dart e Flutter como métodos auxiliares. Cada um retorna o código de saída do processo:

```dart
// Format a Dart file or directory
await dartFormat('lib/app/models/user.dart');

// Run dart analyze
int analyzeResult = await dartAnalyze('lib/');

// Run flutter pub get
await flutterPubGet();

// Run flutter clean
await flutterClean();

// Build for a target with additional args
await flutterBuild('apk', args: ['--release', '--split-per-abi']);
await flutterBuild('web', args: ['--release']);

// Run flutter test
await flutterTest();
await flutterTest('test/unit/');
```

| Método | Descrição |
|--------|-------------|
| `dartFormat(String path)` | Executar `dart format` em um arquivo ou diretório |
| `dartAnalyze([String? path])` | Executar `dart analyze` |
| `flutterPubGet()` | Executar `flutter pub get` |
| `flutterClean()` | Executar `flutter clean` |
| `flutterBuild(String target, {List<String> args})` | Executar `flutter build <target>` |
| `flutterTest([String? path])` | Executar `flutter test` |

<div id="validation-helpers"></div>

## Helpers de Validação

Helpers para validar e limpar entrada do usuário para geração de código:

```dart
// Validate a Dart identifier
if (!isValidDartIdentifier('MyClass')) {
  error('Invalid Dart identifier');
}

// Require a non-empty first argument (aborts if missing)
String name = requireArgument(result, message: 'Please provide a name');

// Clean a class name (PascalCase, remove suffixes)
String className = cleanClassName('user_model', removeSuffixes: ['_model']);
// Returns: 'User'

// Clean a file name (snake_case with extension)
String fileName = cleanFileName('UserModel', extension: '.dart');
// Returns: 'user_model.dart'
```

| Método | Descrição |
|--------|-------------|
| `isValidDartIdentifier(String name)` | Validar um nome de identificador Dart |
| `requireArgument(CommandResult result, {String? message})` | Exigir primeiro argumento não vazio ou abortar |
| `cleanClassName(String name, {List<String> removeSuffixes})` | Limpar e aplicar PascalCase a um nome de classe |
| `cleanFileName(String name, {String extension = '.dart'})` | Limpar e aplicar snake_case a um nome de arquivo |

<div id="file-scaffolding"></div>

## Scaffolding de Arquivos

Crie um ou vários arquivos com conteúdo usando o sistema de scaffolding.

### Arquivo Único

```dart
await scaffold(
  path: 'lib/app/services/auth_service.dart',
  content: '''
class AuthService {
  Future<bool> login(String email, String password) async {
    return false;
  }
}
''',
  force: false,
  successMessage: 'AuthService created',
);
```

| Parâmetro | Tipo | Descrição |
|-----------|------|-------------|
| `path` | `String` | Caminho do arquivo a criar |
| `content` | `String` | Conteúdo do arquivo |
| `force` | `bool` | Sobrescrever se existir (padrão: `false`) |
| `successMessage` | `String?` | Mensagem exibida em caso de sucesso |

### Múltiplos Arquivos

```dart
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
```

A classe `ScaffoldFile`:

| Propriedade | Tipo | Descrição |
|----------|------|-------------|
| `path` | `String` | Caminho do arquivo a criar |
| `content` | `String` | Conteúdo do arquivo |
| `successMessage` | `String?` | Mensagem exibida em caso de sucesso |

<div id="task-runner"></div>

## Executor de Tarefas

Execute uma série de tarefas nomeadas com saída automática de status.

### Executor de Tarefas Básico

```dart
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
    stopOnError: true,
  ),
]);
```

### Executor de Tarefas com Spinner

```dart
await runTasksWithSpinner([
  CommandTask(
    'Preparing release',
    () async {
      await flutterClean();
      await flutterPubGet();
    },
  ),
  CommandTask(
    'Building APK',
    () => flutterBuild('apk', args: ['--release']),
  ),
]);
```

A classe `CommandTask`:

| Propriedade | Tipo | Padrão | Descrição |
|----------|------|---------|-------------|
| `name` | `String` | obrigatório | Nome da tarefa exibido na saída |
| `action` | `Future<void> Function()` | obrigatório | Função assíncrona a executar |
| `stopOnError` | `bool` | `true` | Se deve parar as tarefas restantes em caso de falha |

<div id="table-output"></div>

## Saída em Tabela

Exiba tabelas ASCII formatadas no console:

```dart
table(
  ['Name', 'Version', 'Status'],
  [
    ['nylo_framework', '7.0.0', 'installed'],
    ['nylo_support', '7.0.0', 'installed'],
    ['dio', '5.4.0', 'installed'],
  ],
);
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

<div id="examples"></div>

## Exemplos

<div id="current-time-command"></div>

### Comando de Hora Atual

Um comando simples que exibe a hora atual:

```dart
import 'package:nylo_framework/metro/ny_cli.dart';

void main(arguments) => _CurrentTimeCommand(arguments).run();

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
    final now = DateTime.now();
    info("The current time is ${now.toIso8601String()}");
    info("Requested format: $format");
  }
}
```

<div id="download-fonts-command"></div>

### Comando de Download de Fontes

Um comando que baixa e instala Google Fonts no projeto:

```dart
import 'package:nylo_framework/metro/ny_cli.dart';

void main(arguments) => _DownloadFontsCommand(arguments).run();

class _DownloadFontsCommand extends NyCustomCommand {
  _DownloadFontsCommand(super.arguments);

  @override
  CommandBuilder builder(CommandBuilder command) {
    command.addOption('font', abbr: 'f', help: 'Font family name');
    command.addFlag('verbose', abbr: 'v', defaultValue: false);
    return command;
  }

  @override
  Future<void> handle(CommandResult result) async {
    final fontName = result.getString('font') ??
        prompt('Enter font family name:', defaultValue: 'Roboto');

    final verbose = result.getBool('verbose') ?? false;

    await withSpinner(
      task: () async {
        await ensureDirectory('assets/fonts');
        final fontData = await api((request) =>
          request.get('https://fonts.google.com/download?family=$fontName')
        );
        if (fontData != null) {
          await writeFile('assets/fonts/$fontName.ttf', fontData.toString());
        }
      },
      message: 'Downloading $fontName font',
      successMessage: '$fontName font installed',
      errorMessage: 'Failed to download $fontName',
    );

    if (verbose) {
      info('Font saved to: assets/fonts/$fontName.ttf');
    }
  }
}
```

<div id="deployment-pipeline-command"></div>

### Comando de Pipeline de Deploy

Um comando que executa um pipeline completo de deploy usando o executor de tarefas:

```dart
import 'package:nylo_framework/metro/ny_cli.dart';

void main(arguments) => _DeployCommand(arguments).run();

class _DeployCommand extends NyCustomCommand {
  _DeployCommand(super.arguments);

  @override
  CommandBuilder builder(CommandBuilder command) {
    command.addOption(
      'environment',
      abbr: 'e',
      defaultValue: 'development',
      allowed: ['development', 'staging', 'production'],
    );
    command.addFlag('skip-tests', defaultValue: false);
    return command;
  }

  @override
  Future<void> handle(CommandResult result) async {
    final env = result.getString('environment') ?? 'development';
    final skipTests = result.getBool('skip-tests') ?? false;

    alert('Deploying to $env');
    newLine();

    if (env == 'production') {
      if (!confirm('Are you sure you want to deploy to production?')) {
        abort('Deployment canceled');
      }
    }

    final tasks = <CommandTask>[
      CommandTask('Clean project', () => flutterClean()),
      CommandTask('Fetch dependencies', () => flutterPubGet()),
    ];

    if (!skipTests) {
      tasks.add(CommandTask(
        'Run tests',
        () => flutterTest(),
        stopOnError: true,
      ));
    }

    tasks.add(CommandTask(
      'Build for web',
      () => flutterBuild('web', args: ['--release']),
    ));

    await runTasksWithSpinner(tasks);

    newLine();
    success('Deployment to $env completed');

    table(
      ['Step', 'Status'],
      tasks.map((t) => [t.name, 'Done']).toList(),
    );
  }
}
```
