# Backpack

---

<a name="section-1"></a>
- [Introdução](#introduction "Introdução")
- [Uso Básico](#basic-usage "Uso Básico")
- [Lendo Dados](#reading-data "Lendo Dados")
- [Salvando Dados](#saving-data "Salvando Dados")
- [Excluindo Dados](#deleting-data "Excluindo Dados")
- [Sessões](#sessions "Sessões")
- [Acessando a Instância do Nylo](#nylo-instance "Acessando a Instância do Nylo")
- [Funções Auxiliares](#helper-functions "Funções Auxiliares")
- [Integração com NyStorage](#integration-with-nystorage "Integração com NyStorage")
- [Exemplos](#examples "Exemplos Práticos")

<div id="introduction"></div>

## Introdução

**Backpack** é um sistema de armazenamento singleton em memória no {{ config('app.name') }}. Ele fornece acesso rápido e síncrono aos dados durante a execução do seu app. Diferente do `NyStorage` que persiste dados no dispositivo, o Backpack armazena dados em memória e é limpo quando o app é fechado.

O Backpack é usado internamente pelo framework para armazenar instâncias críticas como o objeto `Nylo`, `EventBus` e dados de autenticação. Você também pode usá-lo para armazenar seus próprios dados que precisam ser acessados rapidamente sem chamadas assíncronas.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Salvar um valor
Backpack.instance.save("user_name", "Anthony");

// Ler um valor (síncrono)
String? name = Backpack.instance.read("user_name");

// Excluir um valor
Backpack.instance.delete("user_name");
```

<div id="basic-usage"></div>

## Uso Básico

O Backpack usa o **padrão singleton** -- acesse-o através de `Backpack.instance`:

``` dart
// Salvar dados
Backpack.instance.save("theme", "dark");

// Ler dados
String? theme = Backpack.instance.read("theme"); // "dark"

// Verificar se os dados existem
bool hasTheme = Backpack.instance.contains("theme"); // true
```

<div id="reading-data"></div>

## Lendo Dados

Leia valores do Backpack usando o método `read<T>()`. Ele suporta tipos genéricos e um valor padrão opcional:

``` dart
// Ler uma String
String? name = Backpack.instance.read<String>("name");

// Ler com um valor padrão
String name = Backpack.instance.read<String>("name", defaultValue: "Guest") ?? "Guest";

// Ler um int
int? score = Backpack.instance.read<int>("score");
```

O Backpack desserializa automaticamente valores armazenados para objetos de modelo quando um tipo é fornecido. Isso funciona tanto para strings JSON quanto para valores raw `Map<String, dynamic>`:

``` dart
// Se um modelo User estiver armazenado como string JSON, ele será desserializado
User? user = Backpack.instance.read<User>("current_user");

// Se um Map raw foi armazenado (ex: via syncKeys do NyStorage), ele também é
// automaticamente desserializado no modelo tipado na leitura
Backpack.instance.save("current_user", {"name": "Alice", "age": 30});
User? user = Backpack.instance.read<User>("current_user"); // retorna um User
```

<div id="saving-data"></div>

## Salvando Dados

Salve valores usando o método `save()`:

``` dart
Backpack.instance.save("api_token", "abc123");
Backpack.instance.save("is_premium", true);
Backpack.instance.save("cart_count", 3);
```

### Adicionando Dados

Use `append()` para adicionar valores a uma lista armazenada em uma chave:

``` dart
// Adicionar a uma lista
Backpack.instance.append("recent_searches", "Flutter");
Backpack.instance.append("recent_searches", "Dart");

// Adicionar com um limite (mantém apenas os últimos N itens)
Backpack.instance.append("recent_searches", "Nylo", limit: 10);
```

<div id="deleting-data"></div>

## Excluindo Dados

### Excluir uma Única Chave

``` dart
Backpack.instance.delete("api_token");
```

### Excluir Todos os Dados

O método `deleteAll()` remove todos os valores **exceto** as chaves reservadas do framework (`nylo` e `event_bus`):

``` dart
Backpack.instance.deleteAll();
```

<div id="sessions"></div>

## Sessões

O Backpack fornece gerenciamento de sessão para organizar dados em grupos nomeados. Isso é útil para armazenar dados relacionados juntos.

### Atualizar um Valor de Sessão

``` dart
Backpack.instance.sessionUpdate("cart", "item_count", 3);
Backpack.instance.sessionUpdate("cart", "total", 29.99);
```

### Obter um Valor de Sessão

``` dart
int? itemCount = Backpack.instance.sessionGet<int>("cart", "item_count"); // 3
double? total = Backpack.instance.sessionGet<double>("cart", "total"); // 29.99
```

### Remover uma Chave de Sessão

``` dart
Backpack.instance.sessionRemove("cart", "item_count");
```

### Limpar uma Sessão Inteira

``` dart
Backpack.instance.sessionFlush("cart");
```

### Obter Todos os Dados da Sessão

``` dart
Map<String, dynamic>? cartData = Backpack.instance.sessionData("cart");
// {"item_count": 3, "total": 29.99}
```

<div id="nylo-instance"></div>

## Acessando a Instância do Nylo

O Backpack armazena a instância da aplicação `Nylo`. Você pode recuperá-la usando:

``` dart
Nylo nylo = Backpack.instance.nylo();
```

Verifique se a instância do Nylo foi inicializada:

``` dart
bool isReady = Backpack.instance.isNyloInitialized(); // true
```

<div id="helper-functions"></div>

## Funções Auxiliares

{{ config('app.name') }} fornece funções auxiliares globais para operações comuns do Backpack:

| Função | Descrição |
|----------|-------------|
| `backpackRead<T>(key)` | Ler um valor do Backpack |
| `backpackSave(key, value)` | Salvar um valor no Backpack |
| `backpackDelete(key)` | Excluir um valor do Backpack |
| `backpackDeleteAll()` | Excluir todos os valores (preserva chaves do framework) |
| `backpackNylo()` | Obter a instância do Nylo do Backpack |

### Exemplo

``` dart
// Usando funções auxiliares
backpackSave("locale", "en");

String? locale = backpackRead<String>("locale"); // "en"

backpackDelete("locale");

// Acessar a instância do Nylo
Nylo nylo = backpackNylo();
```

<div id="integration-with-nystorage"></div>

## Integração com NyStorage

O Backpack se integra com `NyStorage` para armazenamento combinado persistente + em memória:

``` dart
// Salvar tanto no NyStorage (persistente) quanto no Backpack (em memória)
await NyStorage.save("auth_token", "abc123", inBackpack: true);

// Agora acessível de forma síncrona via Backpack
String? token = Backpack.instance.read("auth_token");

// Ao excluir do NyStorage, também limpar do Backpack
await NyStorage.deleteAll(andFromBackpack: true);
```

Este padrão é útil para dados como tokens de autenticação que precisam tanto de persistência quanto de acesso síncrono rápido (ex: em interceptadores HTTP).

<div id="examples"></div>

## Exemplos

### Armazenando Tokens de Auth para Requisições de API

``` dart
// No seu interceptador de autenticação
class BearerAuthInterceptor extends Interceptor {
  @override
  void onRequest(RequestOptions options, RequestInterceptorHandler handler) {
    String? userToken = Backpack.instance.read(StorageKeysConfig.auth);

    if (userToken != null) {
      options.headers.addAll({"Authorization": "Bearer $userToken"});
    }

    return super.onRequest(options, handler);
  }
}
```

### Gerenciamento de Carrinho Baseado em Sessão

``` dart
// Adicionar itens a uma sessão de carrinho
Backpack.instance.sessionUpdate("cart", "items", ["item_1", "item_2"]);
Backpack.instance.sessionUpdate("cart", "total", 49.99);

// Ler dados do carrinho
Map<String, dynamic>? cart = Backpack.instance.sessionData("cart");

// Limpar o carrinho
Backpack.instance.sessionFlush("cart");
```

### Feature Flags Rápidas

``` dart
// Armazenar feature flags no Backpack para acesso rápido
backpackSave("feature_dark_mode", true);
backpackSave("feature_notifications", false);

// Verificar um feature flag
bool darkMode = backpackRead<bool>("feature_dark_mode") ?? false;
```
