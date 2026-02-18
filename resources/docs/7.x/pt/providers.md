# Providers

---

<a name="section-1"></a>
- [Introducao](#introduction "Introducao")
- [Criar um provider](#create-a-provider "Criar um provider")
- [Objeto Provider](#provider-object "Objeto Provider")


<div id="introduction"></div>

## Introducao aos Providers

No {{ config('app.name') }}, os providers sao inicializados a partir do seu arquivo <b>main.dart</b> quando sua aplicacao e executada. Todos os seus providers ficam em `/lib/app/providers/*`, voce pode modificar esses arquivos ou criar seus providers usando o <a href="/docs/7.x/metro#make-provider" target="_BLANK">Metro</a>.

Providers podem ser usados quando voce precisa inicializar uma classe, pacote ou criar algo antes que o app seja carregado inicialmente. Ou seja, a classe `route_provider.dart` e responsavel por adicionar todas as rotas ao {{ config('app.name') }}.

### Mergulho profundo

```dart
import 'package:nylo_framework/nylo_framework.dart';
import 'bootstrap/boot.dart';

/// Nylo - Framework for Flutter Developers
/// Docs: https://nylo.dev/docs/7.x

/// Main entry point for the application.
void main() async {
  await Nylo.init(
    setup: Boot.nylo,
    setupFinished: Boot.finished,

    // showSplashScreen: true,
    // Uncomment showSplashScreen to show the splash screen
    // File: lib/resources/widgets/splash_screen.dart
  );
}
```

### Ciclo de Vida

- `Boot.{{ config('app.name') }}` ira percorrer seus providers registrados dentro do arquivo <b>config/providers.dart</b> e inicializa-los.

- `Boot.Finished` e chamado logo apos **"Boot.{{ config('app.name') }}"** ter terminado, este metodo vinculara a instancia do {{ config('app.name') }} ao `Backpack` com o valor 'nylo'.

Ex.: Backpack.instance.read('nylo'); // instancia do {{ config('app.name') }}


<div id="create-a-provider"></div>

## Criar um novo Provider

Voce pode criar novos providers executando o comando abaixo no terminal.

```bash
metro make:provider cache_provider
```

<div id="provider-object"></div>

## Objeto Provider

Seu provider tera dois metodos, `setup(Nylo nylo)` e `boot(Nylo nylo)`.

Quando o app e executado pela primeira vez, qualquer codigo dentro do seu metodo **setup** sera executado primeiro. Voce tambem pode manipular o objeto `Nylo` como no exemplo abaixo.

Exemplo: `lib/app/providers/app_provider.dart`

```dart
class AppProvider extends NyProvider {

  @override
  Future<Nylo?> setup(Nylo nylo) async {
    await NyLocalization.instance.init(
        localeType: localeType,
        languageCode: languageCode,
        languagesList: languagesList,
        assetsDirectory: assetsDirectory,
        valuesAsMap: valuesAsMap);

    return nylo;
  }

  @override
  Future<void> boot(Nylo nylo) async {
    User user = await Auth.user();
    if (!user.isSubscribed) {
      await Auth.remove();
    }
  }
}
```

### Ciclo de Vida

1. `setup(Nylo nylo)` - Inicialize seu provider. Retorne a instancia `Nylo` ou `null`.
2. `boot(Nylo nylo)` - Chamado apos todos os providers terem finalizado o setup. Use isso para inicializacoes que dependem de outros providers estarem prontos.

> Dentro do metodo `setup`, voce deve **retornar** uma instancia de `Nylo` ou `null` como no exemplo acima.
