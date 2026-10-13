# Metro Live

<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [はじめに](#introduction "Nylo Live の概要")
- metro live シェル
  - [共通オプション](#shared-options "すべての live コマンドが受け付けるオプション")
  - [タブ補完と履歴](#tab-completion-and-history "タブ補完と履歴")
  - [スクリプトの実行](#running-a-script ".live スクリプトの実行")
- [live:devices](#live-devices "実行中のアプリを一覧表示")
- [live:status](#live-status "アプリの状態を表示")
- [live:run](#live-run "組み込みの live コマンドを実行")
- [プレイブック](#playbooks "Metro からプレイブックを再生")
- ライブコマンド
  - [ライブコマンドの作成](#creating-a-live-command "ライブコマンドの作成")
  - [ライブコマンドの実行](#running-a-live-command "ライブコマンドの実行")
- シーダー
  - [シーダーの作成](#creating-a-seeder "シーダーの作成")
  - [シードとロールバック](#seeding-and-rolling-back "シードとロールバック")
- ストレージスナップショット
  - [スナップショットのエクスポート](#exporting-a-snapshot "スナップショットのエクスポート")
  - [スナップショットの読み込み](#loading-a-snapshot "スナップショットの読み込み")
- [ページアクション](#page-actions "外部からページを呼び出す")

<div id="introduction"></div>

## はじめに

<x-doc-strip label="必須" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

**Nylo Live** は、Dart VM サービスを介して、デバッグ（またはプロファイル）モードで実行中のアプリに Metro が入り込めるようにする機能です。セットアップなしで Dart Tooling Daemon を通じてアプリを検出し、現在のプロジェクトからビルドされたアプリにのみ接続します。

Nylo Live はデバッグビルドとプロファイルビルドではデフォルトで有効になっており、リリースビルドでは何も登録されません。アプリを読み取るだけのコマンド（`live:status`、`live:run data`、`live:run routes` など）はプロファイルビルドでも動作します。アプリを変更するコマンド（`route`、`storage`、`auth`、シーダーなど）にはデバッグビルドが必要です。

他の処理が実行される前に、プロバイダから自分で無効にすることもできます:

``` dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.useLive(false);
    return nylo;
  }

  @override
  boot(Nylo nylo) async {}
}
```

<div id="metro-live-shell"></div>

## metro live シェル

<x-doc-strip label="必須" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

実行中のアプリに接続されたシェルを開きます:

``` bash
metro live
```

Nylo Live はプロジェクトの実行中のアプリを検出して接続します -- 複数実行されている場合はどれに接続するか選択を求められます。接続すると、プロンプトには現在のデバイスと画面に表示されているルートが表示されます:

``` plaintext
Nylo Live · my_app
Connected to iPhone 15 Pro (debug)
Type help to see commands, and exit to leave.
iPhone 15 Pro /home ›
```

ライブコマンドは、シェル内では `metro live:` の接頭辞を省略します -- `metro live:status` の代わりに `status`、`metro live:run route /profile` の代わりに `route /profile` のように入力します。一部のコマンドはシェル内でのみ実行できます: `seed`、`seed:rollback`、`export`、`reload`、`restart` です。`help` と入力するとすべてのコマンドが一覧表示され、`help <command>` で特定のコマンドのオプションを確認できます。`exit`（または Ctrl+D）と入力するとシェルを終了します。

接続中にアプリが再起動した場合、シェルは自動的に再接続します。

<div id="shared-options"></div>

### 共通オプション

すべての live コマンド -- シェル内でも `metro live:*` としてでも -- は同じオプションを受け付けます:

| オプション | 説明 |
|--------|-------------|
| `-d, --device <#\|name>` | `metro live:devices` の番号またはデバイス名でアプリを指定 |
| `--all` | このプロジェクトの実行中のアプリすべてで実行 |
| `--json` | 機械可読な JSON を出力 |
| `--uri` | アプリを検出する代わりにこの VM サービスアドレスを使用 |
| `--timeout` | アプリを検出する際に各アプリを待機する秒数（デフォルト `3`） |

`@path` の形式で書かれたオプション値は、そのファイルから読み込まれます -- 大きな `--data` ペイロードに便利です:

``` bash
metro live:run route /profile --data @user.json
```

値が実際に `@` で始まる場合は `@@` から始めてください。

<div id="tab-completion-and-history"></div>

### タブ補完と履歴

シェル内では、Tab キーでコマンド名、オプション、ルート、ストレージや Backpack のキー、シーダー名、プレイブック名を、接続中のアプリに対して補完できます。履歴は `.dart_tool/nylo/live_history` に保存され、セッションをまたいで保持されます。

<div id="running-a-script"></div>

### スクリプトの実行

ファイルを `metro live` にパイプすると、1 行につき 1 コマンドとしてスクリプトを実行し、最初に失敗したコマンドで停止します:

``` bash
metro live < seeds/demo.live
```

空行と `#` で始まる行はスキップされます。

<div id="live-devices"></div>

## live:devices

<x-doc-strip label="必須" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

このプロジェクトの実行中のアプリを、`-d` 用の番号付きで一覧表示します:

``` bash
metro live:devices
```
``` plaintext
Running apps for my_app:
#  DEVICE          PLATFORM  MODE   ROUTE   APP
1  iPhone 15 Pro   ios       debug  /home   my_app
2  Pixel 8         android   debug  /login  my_app
```

<div id="live-status"></div>

## live:status

<x-doc-strip label="必須" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

アプリ、デバイス、ビルドモード、現在のルートとスタック、ロケール、テーマ、ユーザーがサインインしているかどうかを表示します:

``` bash
metro live:status
```
``` plaintext
App            my_app (development)
Device         iPhone 15 Pro · ios 17.4
Mode           debug
Route          /home
Stack          /home
Locale         en
Theme          default
Authenticated  yes
Live commands  cart:seed
Seeders        demo_user
Playbooks      sign_up
```

<div id="live-run"></div>

## live:run

<x-doc-strip label="必須" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

アプリを調べたり操作したりする組み込みコマンドを実行します:

``` bash
metro live:run <command> [arguments]
```

| コマンド | 説明 |
|---------|-------------|
| `data` | 画面に表示されているページのデータとフィールドを表示 |
| `routes` | 登録されているルートを一覧表示 |
| `route` | ページを開く（任意で `--data` を指定可能） |
| `back` | 1 つ前のページに戻る、またはルートに戻る |
| `deeplink` | ディープリンクを開く、またはその設定方法を表示 |
| `storage` | ローカルストレージを一覧表示、または 1 つの値を表示・保存・削除 |
| `storage:clear` | ローカルストレージをクリア（任意で `--keep` を指定可能） |
| `backpack` | Backpack の値を一覧表示、または 1 つを表示・保存・削除 |
| `auth` | サインイン中のユーザーを表示、またはサインイン・サインアウト |
| `locale` | アプリの言語を切り替える |
| `theme` | アプリのテーマを切り替える |
| `state` | `updateState` である state にデータを送信 |
| `event` | 自分のイベントの 1 つを発火 |
| `toast` | トースト通知を表示 |
| `playbook` | [プレイブック](/docs/{{ $version }}/playbooks)を再生、または一覧表示 |

`metro live` の中では、`live:run` の部分を省略します -- `metro live:run route /profile` の代わりに `route /profile` のように入力します:

``` bash
metro live:run route /profile --data '{"id": 42}'
metro live:run storage SK_COINS 10
metro live:run backpack auth_user
metro live:run auth login --data '{"id": 42}'
metro live:run deeplink myapp://product/42
metro live:run state HomePage --data '{"count": 2}'
metro live:run event LogoutEvent --data '{"reason": "test"}'
metro live:run toast "Item saved" --title Success
```

`data` は、実行中のアプリから直接、ページの state が宣言しているフィールドも読み取ります -- Flutter にはリフレクションがないため、これは実際に画面に表示されている state に対してのみ機能します:

``` bash
metro live:run data HomePage
```

<div id="playbooks"></div>

## プレイブック

<x-doc-strip label="必須" items="{{ config('app.name') }} 7.3.0+, nylo_support 7.32.0+" />

実行中のアプリで 1 つ以上の[プレイブック](/docs/{{ $version }}/playbooks)を再生します。各ステップは実行されるたびに出力されます:

``` bash
metro live:run playbook sign_up
metro live:run playbook sign_up add_to_bag --speed fast
```

複数のプレイブック名を指定すると、続けて再生されます。名前を指定しない場合、または `--list` を指定した場合は、アプリのプレイブックを一覧表示します。

| オプション | 説明 |
|--------|-------------|
| `--speed instant\|fast\|natural\|slow` | 各プレイブック自身のペースではなく、このペースで再生 |
| `--data <json>` | 実行内のすべてのプレイブックが `data` で読み取る JSON オブジェクト。デフォルト値より優先されます |
| `--no-overlay` | 指とステップのキャプションを非表示にする |
| `--loop` | Ctrl+C を押すまで、プレイブックを繰り返し再生 |
| `--list` | プレイブックを再生せず、アプリのプレイブックを一覧表示 |

Ctrl+C を押すとアプリに停止を要求します。残りのステップはスキップされますが、`tearDown` は引き続き実行されます。もう一度 Ctrl+C を押すと、待機を終了します。

`metro live` シェル内ではコマンドは `playbook <name>` で、Tab キーでプレイブック名を補完できます:

``` plaintext
› playbook sign_up --speed fast
```

<div id="playbook-live-commands"></div>

### ライブコマンドリファレンス

その裏側のコマンドは `playbooks.list`、`playbooks.run`、`playbooks.stop` です。実行は ID をすぐに返し、各ステップを `nylo.playbook` イベントとしてストリーミングするため、コマンドのタイムアウトより長く実行できます。`live:status` はアプリのプレイブックを一覧表示します。プレイブックは Nylo Live プロトコルバージョン 9 で導入されました。

<div id="live-commands"></div>

## ライブコマンド

<x-doc-strip label="必須" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

上記の組み込みコマンドに加えて、アプリ内で実行する独自のコマンドを登録できます。

<div id="creating-a-live-command"></div>

### ライブコマンドの作成

``` bash
metro make:command seed_cart --live
```

| オプション | 説明 |
|--------|-------------|
| `--category`, `-c` | コマンドのカテゴリ（デフォルト: `app`） |
| `--description` | コマンドの `--help` に表示される 1 行の説明 |
| `--force`, `-f` | 既に存在する場合でもファイルを作成 |

これにより、`lib/app/commands/` に `LiveCommand` が作成され、`commands.json` で `"type": "live"` としてマークされ、`lib/bootstrap/live_commands.dart` に登録され、`app_provider.dart` の `nylo.configure(...)` に `liveCommands: liveCommands` が組み込まれます:

``` dart
class SeedCartCommand extends LiveCommand {
  @override
  CommandBuilder builder(CommandBuilder command) {
    command.addOption('count', abbr: 'c', defaultValue: '3');
    command.addFlag('open', help: 'Open the cart afterwards');
    return command;
  }

  @override
  Future<void> handle(CommandResult result) async {
    final int count = result.getInt('count') ?? 3;
    await CartItem.seed(count: count);
    success('Seeded $count cart items');
  }
}
```

`LiveCommand` は Metro 自身のコマンドと `builder`/`handle` の形を共有していますが、`handle` は**アプリの内部**で実行されるため、`NyStorage`、`routeTo`、`Auth`、そして自分のモデルを使用できます。`package:nylo_framework/live.dart` は `LiveCommand` を `Seeder`、`StorageSnapshot`、`LiveException`、`LiveOutput` と共にエクスポートしています。

<div id="running-a-live-command"></div>

### ライブコマンドの実行

新しいライブコマンドは、実行中のアプリで実行できるようになる前にホットリスタートが必要です。その後、ターミナルから:

``` bash
metro app:seed_cart --count 5
```

<div id="seeders"></div>

## シーダー

<!-- uncertain: "Seeder" is new Nylo-specific terminology -- translated as シーダー (katakana), following the common ja Laravel-docs convention for "Seeder"; the class name itself stays verbatim as `Seeder` -->

<x-doc-strip label="必須" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

`Seeder` は、実行中のアプリを既知の状態 -- サインイン済み、オンボーディング完了、サンプルレコードなど -- にし、また元に戻します。

<div id="creating-a-seeder"></div>

### シーダーの作成

``` bash
metro make:seeder demo_user
```

| オプション | 説明 |
|--------|-------------|
| `--description` | `seed` がシーダーを一覧表示する際に表示される 1 行の説明 |
| `--force`, `-f` | 既に存在する場合でもファイルを作成 |

これにより、`lib/app/seeders/` に `Seeder` が作成され、`lib/bootstrap/seeders.dart` に登録され、`nylo.configure(...)` に `seeders: seeders` が組み込まれます:

``` dart
class DemoUserSeeder extends Seeder {
  @override
  String get description => 'Jane Doe, signed in, onboarding done';

  @override
  Future<void> up() async {
    await Auth.authenticate(data: {'name': 'Jane Doe', 'token': 'demo'});
    await saveToStorage({'onboarding_complete': true});
    success('Signed in as Jane Doe');
  }

  @override
  Future<void> down() async {
    await restore();
  }
}
```

`up()` が変更するストレージと Backpack の値はすべて自動的に記録されるため、デフォルトの `down()`（`restore()`）は、ホットリスタートを挟んでも、それぞれを逆順で元どおりに戻します。シーダーの内部から `seed(otherSeeders)` を呼び出すと、他のシーダーをその一部として実行できます -- それらの変更も記録され、ロールバックされます。

<div id="seeding-and-rolling-back"></div>

### シードとロールバック

シード、ロールバック、エクスポートは `metro live` シェル内でのみ実行できます:

``` plaintext
› seed demo_user
Seeding demo_user
  + storage    SK_ONBOARDING  added
  ✓ Signed in as Jane Doe
✓ Seeded demo_user in 42ms
Undo with seed:rollback demo_user

› seed:rollback demo_user
Rolling back demo_user
  - storage    SK_ONBOARDING  removed
✓ Rolled back demo_user in 8ms
```

`seed` を単独で実行すると、登録されているシーダーが一覧表示されます。`--fresh` はシードする前にストレージと Backpack をクリアし、`--restart` はその後アプリをホットリスタートするため、起動時には既にシード済みの状態になります:

``` plaintext
› seed demo_user --fresh --restart
```

<div id="storage-snapshots"></div>

## ストレージスナップショット

<x-doc-strip label="必須" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

`export` は、アプリが現在ストレージと Backpack に保持しているすべてのものを -- シーダーとして、または持ち運び可能な JSON ファイルとして -- 保存し、`seed <file>` はそれを読み込みます。

<div id="exporting-a-snapshot"></div>

### スナップショットのエクスポート

名前を指定せずに実行すると、何がキャプチャされるかのプレビューが表示されます:

``` plaintext
› export
Storage on iPhone 15 Pro (2 values, 96 B)
KEY          TYPE    VALUE
SK_THEME     string  dark
SK_USER      model   {id: 1, name: Jane Doe}

Backpack (0 values)
Backpack is empty

Save it as a seeder with export <name>, or as a file with export --to <path>.json
```

名前を付けると、`lib/app/seeders/` にシーダーとして保存されます（`make:seeder` と同じですが、キャプチャした値が既に入力されています）:

``` plaintext
› export pro_user
```

または、代わりにファイルに書き出すこともできます:

``` plaintext
› export pro_user --to snapshots/pro_user.json
```

| オプション | 説明 |
|--------|-------------|
| `--to <path>` | シーダーの代わりにこの JSON ファイルにスナップショットを書き出す |
| `--description` | シーダー用の 1 行の説明 |
| `--only <keys>` | エクスポートするキーのカンマ区切りリスト。`*` は任意の文字列にマッチ（例: `--only SK_USER,onboarding_*`） |
| `--except <keys>` | 除外するキーのカンマ区切りリスト（例: `--except 'cache_*'`） |
| `--backpack` / `--no-backpack` | Backpack の値を含めるか（デフォルト: 含める） |
| `--force`, `-f` | 既に存在するシーダーやファイルを置き換える |

`Model`、または登録済みのデコーダーを持つ任意のクラスは、クラス名でタグ付けされるため、単純な map としてではなく、それ自身のデコーダーを通じて復元されます。スナップショットに認証情報のように見えるキーが含まれる場合や、`--except` を使ったほうがよいほど大きい場合、`export` は警告を表示します。

<div id="loading-a-snapshot"></div>

### スナップショットの読み込み

``` plaintext
› seed snapshots/pro_user.json
```

ファイルの読み込みはシーダーと同じ方法で記録されるため、`seed:rollback <name>`（ファイル名、または `--as` で指定した名前）はそれも取り消します。コードからは、シーダーは `importSnapshot` でキャプチャ済みのスナップショットを適用できます:

``` dart
class ProUserSeeder extends Seeder {
  @override
  Future<void> up() async {
    await importSnapshot(snapshot);
  }

  static const Map<String, Object?> snapshot = {
    'storage': {
      'SK_USER': {'type': 'model', 'model': 'User', 'value': {'id': 1}},
    },
    'backpack': {},
  };
}
```

`export` はまさにこの形式で書き出すため、`--to` で書き出したファイルはそのままシーダーの `snapshot` フィールドに貼り付けられます。

<div id="page-actions"></div>

## ページアクション

<x-doc-strip label="必須" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

ページの `path.actions` を使うと、手動で `stateAction(...)` 呼び出しを組み立てなくても、外部からそのページを呼び出すための `PageStateActions` が得られます:

``` dart
class ProductPage extends NyStatefulWidget {
  static RouteView path = ("/product", (_) => ProductPage());
  static final actions = path.actions;

  ProductPage({super.key}) : super(child: () => _ProductPageState());
}

ProductPage.actions.showToast("Added to bag");
ProductPage.actions.refreshPage();
```

組み込みアクションの全一覧と、独自の型付きアクションを宣言する方法については、[ページのアクションを呼び出す](/docs/{{ $version }}/state-management#page-actions-shortcut) を参照してください。Nylo Live も同じアクションを読み取り、使用します: `metro live:run data` は画面上のページが `actions` の下で公開しているものを一覧表示し、`metro live:run state` はページのデータをその state 名で直接送信します。
