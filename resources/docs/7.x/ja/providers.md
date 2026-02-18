# Provider

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- [Provider の作成](#create-a-provider "Provider の作成")
- [Provider オブジェクト](#provider-object "Provider オブジェクト")


<div id="introduction"></div>

## Provider について

{{ config('app.name') }} では、Provider はアプリケーション実行時に <b>main.dart</b> ファイルから最初にブートされます。すべての Provider は `/lib/app/providers/*` にあり、これらのファイルを変更したり、<a href="/docs/7.x/metro#make-provider" target="_BLANK">Metro</a> を使用して Provider を作成できます。

Provider は、アプリが最初にロードされる前にクラスやパッケージを初期化したり、何かを作成する必要がある場合に使用できます。例えば、`route_provider.dart` クラスは {{ config('app.name') }} にすべてのルートを追加する役割を担っています。

### 詳細

```dart
import 'package:nylo_framework/nylo_framework.dart';
import 'bootstrap/boot.dart';

/// Nylo - Framework for Flutter Developers
/// Docs: https://nylo.dev/docs/7.x

/// アプリケーションのメインエントリーポイント
void main() async {
  await Nylo.init(
    setup: Boot.nylo,
    setupFinished: Boot.finished,

    // showSplashScreen: true,
    // showSplashScreen のコメントを解除するとスプラッシュ画面を表示
    // ファイル: lib/resources/widgets/splash_screen.dart
  );
}
```

### ライフサイクル

- `Boot.{{ config('app.name') }}` は <b>config/providers.dart</b> ファイル内に登録された Provider をループし、ブートします。

- `Boot.Finished` は **"Boot.{{ config('app.name') }}"** が完了した直後に呼び出され、このメソッドは {{ config('app.name') }} インスタンスを `Backpack` に 'nylo' という値でバインドします。

例: Backpack.instance.read('nylo'); // {{ config('app.name') }} インスタンス


<div id="create-a-provider"></div>

## 新しい Provider の作成

ターミナルで以下のコマンドを実行して新しい Provider を作成できます。

```bash
metro make:provider cache_provider
```

<div id="provider-object"></div>

## Provider オブジェクト

Provider には `setup(Nylo nylo)` と `boot(Nylo nylo)` の 2 つのメソッドがあります。

アプリが初めて実行されるとき、**setup** メソッド内のコードが最初に実行されます。以下の例のように `Nylo` オブジェクトを操作することもできます。

例: `lib/app/providers/app_provider.dart`

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

### ライフサイクル

1. `setup(Nylo nylo)` - Provider を初期化します。`Nylo` インスタンスまたは `null` を返します。
2. `boot(Nylo nylo)` - すべての Provider のセットアップが完了した後に呼び出されます。他の Provider の準備が整った後に必要な初期化に使用します。

> `setup` メソッド内では、上記のように `Nylo` のインスタンスまたは `null` を**返す**必要があります。
