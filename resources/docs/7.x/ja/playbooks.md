# プレイブック
<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [はじめに](#introduction "プレイブックの概要")
- [プレイブックの作成](#creating-a-playbook "プレイブックの作成")
- [プレイブックの記録](#recording-a-playbook "実行中のアプリからプレイブックを記録する")
- [ステップ](#steps "プレイブックが実行できるステップ")
- [ターゲット](#targets "画面上の要素を探す")
  - [プレーン文字列](#plain-strings "プレーン文字列のマッチ方法")
  - [Find](#find "Find による正確なターゲット指定")
- [データとセットアップ](#data-and-setup "データ、setUp、tearDown")
- [他のプレイブックの再生](#playing-other-playbooks "プレイブックから別のプレイブックを実行する")
- [スピード](#speed "PlaybookSpeed")
- [指とキャプション](#finger-and-caption "指とステップのキャプション")
- [スクリーンショット](#screenshots "プレイブック中にスクリーンショットを撮る")
  - [ロケールとテーマ](#screenshots-locales-and-themes "すべてのロケールとテーマで画面を撮る")
  - [コードからの収集](#collecting-screenshots "onScreenshot でスクリーンショットを収集する")
- [プレイブックの登録](#registering-playbooks "Nylo にプレイブックを登録する")
- [プレイブックの実行](#running-a-playbook "プレイブックの実行")
  - [Metro から](#running-from-metro "Metro から実行")
  - [起動時](#running-on-boot "起動時に実行")
  - [Dart から](#running-from-dart "Dart から実行")
- [レポート](#reports "PlaybookReport")
- [リリースビルド](#release-builds "リリースビルドでのプレイブック")
- [テスト](#testing "プレイブックのテスト")

<div id="introduction"></div>

## はじめに

<x-doc-strip label="必須" items="{{ config('app.name') }} 7.3.0+, nylo_support 7.32.0+" />

**プレイブック**は、ユーザージャーニーのタップ、入力、スクロール、ページ遷移を列挙するクラスです。実行中のアプリが、それらを自分自身で実行し、指と各ステップのキャプションを表示することもできます -- デモ、画面録画、ジャーニーのエンドツーエンドの確認に便利です。

``` dart
import 'package:nylo_framework/nylo_framework.dart';
import 'package:nylo_framework/playbook.dart';

class SignUpPlaybook extends Playbook {
  @override
  String get description => 'New user signs up and lands on the dashboard';

  @override
  Map<String, dynamic> get defaults => {'email': 'demo@nylo.dev'};

  @override
  List<PlaybookStep> get steps => [
    visit(SignUpPage.path),
    type('Email', data['email']),
    type('Password', 'secret123', secret: true),
    tap('Create account'),
    waitForPage(DashboardPage.path),
    see('Welcome'),
  ];
}
```

プレイブックを作成または登録する場所では、必ず `package:nylo_framework/playbook.dart` をインポートしてください。

<div id="creating-a-playbook"></div>

## プレイブックの作成

Metro を使って作成します:

``` bash
metro make:playbook sign_up
```

これにより `lib/app/playbooks/sign_up_playbook.dart` が作成され、アプリに登録されます。コマンドのオプションについては [プレイブックの作成](/docs/{{ $version }}/metro#make-playbook) を参照してください。

ルーターが追加するすべてのページから始めるには、`--from-routes` を渡します:

``` bash
metro make:playbook tour --from-routes
```

Metro は各ページに対して `visit()` と [`screenshot()`](#screenshots) を書き出すため、1 回の実行でアプリ全体を撮影できます。

<div id="recording-a-playbook"></div>

## プレイブックの記録

<x-doc-strip label="必須" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.36.0+" />

ステップを書く代わりに、記録できます。アプリをデバッグモードで実行した状態で `metro live` を開き、次のように入力します:

``` plaintext
› record sign_up
```

その後、アプリを操作します。タップ、長押し、入力したフィールド、スクロール、ページ遷移のそれぞれが、発生した瞬間にステップとして書き出され、その場で表示されます:

``` plaintext
● Recording sign_up on iPhone 17. Use the app; each step is written as you go.
· Enter finishes · a: the next tap becomes see() · d: dontSee() · s <title>: start a scene · u: undo the last step · p: pause or resume · Ctrl+C discards
  1  visit(HomePage.path)
  2  tap('Get started')
  3  waitForPage(SignUpPage.path)
  4  type('Email', data['email'])
  5  type('Password', data['password'], secret: true, submit: true)
  6  tap('Create account')
  7  waitForPage(DashboardPage.path)
  8  see('Welcome')
✓ Recorded 8 steps in 41s
✓ Registered as 'sign_up' in lib/bootstrap/playbooks.dart
Play it back now at fast speed? It takes a hot restart. [Y/n]
```

Enter を押して終了します。プレイブックは `make:playbook` が作成するものと同じ構成で `lib/app/playbooks/sign_up_playbook.dart` に書き出されて登録されるため、アプリが再起動するとすぐに再生できます。`metro make:playbook sign_up --record` はターミナルから同じことを行います。`metro live` を開き、すぐに記録を開始します。

記録中に、キーを入力して Enter を押します:

| キー | 説明 |
|-----|-------------|
| `a` | 次のタップを、タップではなく `see(...)` として書き出す |
| `d` | 次のタップを `dontSee(...)` として書き出す |
| `s <title>` | そのタイトルの `scene` を開始する。続くステップはその中にネストされます |
| `u` | 最後のステップを削除する |
| `p` | 記録を一時停止する。例: 実際の認証情報でサインインするため。もう一度 `p` で再開します |
| Enter | 終了してプレイブックを書き出す |
| Ctrl+C | 記録を破棄する |

ターゲットは、プレイヤーが読み取るとおりに、表示されているテキストを優先して名前が付けられ、書き出される前にそれぞれの名前が実行中のアプリに対して確認されるため、記録されたステップは再生できます。プレーン文字列が複数のウィジェットにマッチする場合、ステップは `Find` で絞り込まれます: `tap(Find.text('Add').at(1))`。テキストもツールチップもないアイコンボタンは `tap(Find.icon(Icons.add))` になります。何も名前が付けられないウィジェットは、作成されたファイルと行を示す `// TODO` コメントの下に `tap(Find.type<GestureDetector>().at(3))` として書き出され、記録の終了時に Metro がそれを一覧表示します。それに `Key` を付けて、代わりにそのキーを使用してください。

- **入力** は、`data[...]` のプレースホルダーを読み取る `type` ステップになり、入力された値は `defaults` に入ります。キーボードのアクションキーが残っているフィールドには `submit: true` が付きます。伏せ字のフィールドには `secret: true` が付き、その値は `defaults` に入り、コミットする前に変更するよう警告が表示されます。値を入力されたとおりに書き出すには、`--no-data` を渡します。
- **ページ遷移** は、タップの後、いずれかのページがそのルートを宣言していれば `waitForPage(SignUpPage.path)` に、そうでなければ `waitForPage('/sign-up')` になります。戻る操作は `back()` になります。
- **スクロール** は、次にタップするものに対する `scrollTo(...)` として書き出され、スクロールによってそれがツリーに入った場合にのみ書き出されます。それ以外はすべて、プレイヤーが自動的に表示します。
- **スクロールではないスワイプ** -- `Dismissible`、`PageView`、`Slider` -- には、まだプレイブックの動詞がないため、開始点と終了点を示す `// TODO` コメントとして残されます。

記録にはデバッグビルドが必要で、シミュレーター、エミュレーター、実機のいずれでも動作します。プレイブック自身のタッチは記録されません。コマンドのオプションについては [Metro Live](/docs/{{ $version }}/metro-live#recording-a-journey) を参照してください。

<div id="steps"></div>

## ステップ

プレイブックの `steps` は、継承した動詞を使って組み立てます:

| ステップ | 説明 |
|------|-------------|
| `tap(target)` | ターゲットをタップ |
| `longPress(target)` | ターゲットを長押し |
| `type(target, text)` | フィールドに入力。`clear: false` を指定しない限り先にクリアされます。`submit: true` はキーボードのアクションボタンを押し、`secret: true` はキャプションとレポートでテキストを隠します |
| `scrollTo(target)` | ターゲットが画面に表示されるまでスクロール。特定のスクロール領域をスクロールするには `within:` を渡します |
| `see(target)` | ターゲットが画面にあることを確認（表示されるまで待機） |
| `dontSee(target)` | ターゲットが画面にないことを確認（消えるまで待機） |
| `waitFor(target)` | `timeout`（デフォルトは 10 秒）までターゲットを待機 |
| `waitForPage(path)` | ルートパスまたは `RouteView`（例: `HomePage.path`）のページを待機 |
| `visit(path)` | ルートパスまたは `RouteView` でページを開く（任意で `data:` を指定可能） |
| `back()` | 1 つ前のページに戻る |
| `pause(seconds: 1)` | 静止する。例: デモに間を持たせる |
| `step(title, action)` | `PlaybookActor` を介して、同じ動詞で任意のコードを実行 |
| `scene(title, steps)` | ステップをタイトルでグループ化。キャプションの先頭にタイトルが付き、結果はレポート内でネストされます |
| `screenshot(name)` | 画面をキャプチャ -- [スクリーンショット](#screenshots) を参照 |
| `play(playbook)` | 別のプレイブックを実行 -- [他のプレイブックの再生](#playing-other-playbooks) を参照 |

``` dart
@override
List<PlaybookStep> get steps => [
  scene('Add to bag', [
    scrollTo('Running shoes'),
    tap('Running shoes'),
    tap('Add to bag'),
  ]),
  step('Clear the cache', (actor) async {
    await Backpack.instance.delete('cart_cache');
  }),
  see('1 item'),
];
```

ステップは順番に実行され、最初の失敗で停止します。残りのステップはスキップとして報告されます。

<div id="targets"></div>

## ターゲット

ほとんどのステップはターゲットを受け取ります。タップする対象、入力先、探す対象のことです。

<div id="plain-strings"></div>

### プレーン文字列

プレーン文字列は、人が画面を読むのと同じ方法でマッチされます。次の順に試されます:

1. 表示されているテキスト
2. フィールドのヒントまたはラベル
3. ツールチップ
4. セマンティクスラベル
5. `ValueKey`
6. その文字列を含むテキスト

``` dart
tap('Create account'),
type('Email', 'demo@nylo.dev'),
```

`Key`、`Type`、`IconData` もそのままステップに渡せます。

<div id="find"></div>

### Find

プレーン文字列では精度が足りない場合は `Find` を使用します:

``` dart
tap(Find.icon(Icons.add)),
tap(Find.type<FloatingActionButton>()),
tap(Find.key(const ValueKey('checkout'))),
type(Find.field('email'), 'demo@nylo.dev'),
tap(Find.text('Delete').inside(Find.type<AlertDialog>())),
tap(Find.text('Add').at(1)),
```

| ファインダー | マッチ対象 |
|--------|---------|
| `Find.text(text)` | 表示されているテキスト |
| `Find.textContaining(text)` | その文字列を含むテキスト |
| `Find.icon(icon, label:)` | アイコン。`label:` でステップのキャプションに表示する名前を付けます |
| `Find.type<T>()` | 型 `T` のウィジェット |
| `Find.key(key)` | そのキーを持つウィジェット |
| `Find.field(name)` | 名前で指定する `NyForm` のフィールド |
| `Find.input(text)` | ヒント、ラベル、プレースホルダーで指定するテキストフィールド |
| `Find.tooltip(message)` | ツールチップ |
| `Find.semantics(label)` | セマンティクスラベル |
| `Find.where(test)` | テストを通過するウィジェット |

アイコンに `label` を付けると、ステップのキャプションが `Tap icon f37d` ではなく `Tap the bag icon` と表示されます:

``` dart
tap(Find.icon(Icons.shopping_bag_outlined, label: 'bag')),
```

任意のファインダーは `.inside(ancestor)`、`.at(index)`（0 から数える）、`.first`、`.last` で絞り込めます。

ダイアログのバリアなど無関係なものがターゲットを覆っている場合、タップは拒否されます。

<div id="data-and-setup"></div>

## データとセットアップ

`defaults` には、ステップが最初に使うデータを持たせます。`steps` 内では `data` を通して読み取ります。`setUp` はステップの前に、`tearDown` はステップの後に実行され、ステップが失敗した場合でも実行されます:

``` dart
class CheckoutPlaybook extends Playbook {
  @override
  Map<String, dynamic> get defaults => {'coupon': 'SAVE10'};

  @override
  Future<void> setUp(PlaybookActor actor) async {
    await Auth.logout();
    await seed(['demo_user']);
  }

  @override
  Future<void> tearDown(PlaybookActor actor) async {
    await Auth.logout();
  }

  @override
  List<PlaybookStep> get steps => [
    tap('Checkout'),
    type('Coupon', data['coupon']),
  ];
}
```

`seed([...])` は[シーダー](/docs/{{ $version }}/metro-live#seeders)（インスタンスまたは登録名）を実行し、`metro live` の `seed` と同様に記録します。

<div id="playing-other-playbooks"></div>

## 他のプレイブックの再生

`play()` は、別のプレイブックをそれ自身の `setUp` と `tearDown` を含めて丸ごと実行します。インスタンス、または登録されている名前を渡します:

``` dart
@override
List<PlaybookStep> get steps => [
  play(SignUpPlaybook()),
  play('add_to_bag', data: {'item': 'Running shoes'}),
  tap('Checkout'),
];
```

- **データ**は次の順にマージされます: 子の `defaults`、親の `data`、`play` に渡した `data:`。
- **スピード**は、`speed:` を渡さない限り親から継承されます。
- **結果**はレポート内でそのステップの下にネストされます。
- **子が失敗**すると、そのステップも失敗します。
- **循環**は拒否され、プレイブックのネストは最大 8 段までです。

<div id="speed"></div>

## スピード

`PlaybookSpeed` はすべての待機のペースを設定します:

| スピード | 説明 |
|-------|-------------|
| `PlaybookSpeed.instant` | スクロールに必要な分以外は待機しません。テスト向けです |
| `PlaybookSpeed.fast` | 素早いが、それでも追いやすい速さ |
| `PlaybookSpeed.natural` | 人がアプリを使うときのペース。デフォルトです |
| `PlaybookSpeed.slow` | ナレーションを重ねられるほどゆっくり |

`.times(n)` で任意のスピードを倍率調整できます:

``` dart
@override
PlaybookSpeed get speed => PlaybookSpeed.natural.times(2); // 2 倍遅く
```

`run()` に渡したスピード、Metro の `--speed`、またはこのプレイブックを再生する側のプレイブックのスピードは、プレイブック自身のスピードより優先されます。

<div id="finger-and-caption"></div>

## 指とキャプション

指とステップのキャプションはデフォルトでオフです。`showFinger` と `caption` をオーバーライドしてオンにします:

``` dart
@override
bool get showFinger => true;

@override
PlaybookCaption get caption => PlaybookCaption.bottom; // または .top
```

`showFinger` のデフォルトは `false`、`caption` のデフォルトは `PlaybookCaption.hidden` です。`PlaybookCaption` は `top`、`bottom`、`hidden` のいずれかです。タップやスワイプはどちらの場合も実行されます。このプレイブックが `play()` で再生した別のプレイブックは、そのプレイブック自身の設定を使用し、終了するとこのプレイブックの設定に戻ります。実行中に両方を非表示にするには、Metro では `--no-overlay`、Dart では `run(overlay: false)` を使用します。

nylo_support 7.33.0（{{ config('app.name') }} 7.4.0）より前は、指と下部のキャプションがデフォルトでオンでした。以前の見た目を維持するには、上記のオーバーライドを追加してください。

<div id="screenshots"></div>

## スクリーンショット

<x-doc-strip label="必須" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

画面を残す価値のある場所に `screenshot` ステップを追加します:

``` dart
@override
List<PlaybookStep> get steps => [
  visit(ShopPage.path),
  screenshot('shop'),
  tap('Running shoes'),
  tap('Add to bag'),
  screenshot('cart', caption: 'Your bag, ready to go'),
];
```

`metro live:run playbook <name>` は、フラグなしでそれぞれを `screenshots/<playbook>/<name>.png` として保存します。コードからの実行は [`onScreenshot`](#collecting-screenshots) でそれらを収集し、`PLAYBOOK_ON_BOOT` などそれ以外の実行では、画面が落ち着くのを待つだけです。名前は英字または数字で始まり、英字、数字、`-`、`_`、`.` を使用します。

| パラメータ | 説明 |
|-----------|-------------|
| `name` | スクリーンショットを保存する名前 |
| `locales` | これらの言語コードそれぞれで 1 回ずつキャプチャします。`Screenshot.allLocales` はアプリがバンドルするすべての言語です |
| `themes` | これらのテーマ ID それぞれで 1 回ずつキャプチャします。`Screenshot.allThemes` は登録されているすべてのテーマです |
| `caption` | 実行がスクリーンショットをフレームで囲む場合に画面の上に表示されるテキスト、または翻訳キー |
| `native` | iOS シミュレーターと Android で Metro を使ってキャプチャします（デフォルトは `true`） |

iOS シミュレーターと Android では、Metro がデバイスの画面全体をキャプチャするため、ステータスバーとキーボードも含まれ、その間、指とキャプションは非表示になります。実行に `--clean-status-bar` を追加すると、9:41、電波・バッテリー満タンで表示されます。それ以外の環境や、`--store` と `--frame` の実行では、アプリが自身の画面をキャプチャします。常にアプリのキャプチャを使用するには、`native: false` を渡します:

``` dart
screenshot('checkout', native: false),
```

すべてのステップの画面を保存するには、`metro live:run playbook <name> --screenshots` を実行します。App Store と Google Play 向けにスクリーンショットのサイズを調整するオプションについては [Metro Live](/docs/{{ $version }}/metro-live#playbook-screenshots) を、その裏側にある `Screenshot` クラスについては [スクリーンショット](/docs/{{ $version }}/screenshots) を参照してください。

<div id="screenshots-locales-and-themes"></div>

### ロケールとテーマ

`screenshot` ステップに `locales`、`themes`、またはその両方を指定すると、同じ画面をそれぞれで 1 回ずつキャプチャします:

``` dart
screenshot('cart', locales: ['en', 'es'], themes: ['light', 'dark']),
screenshot('home', locales: Screenshot.allLocales),
```

アプリはページとその状態を維持したままその場で切り替わり、次のステップの前に元に戻ります。各キャプチャは、そのロケールとテーマの名前のフォルダに保存されます:

``` plaintext
screenshots/sign_up/en/cart.png
screenshots/sign_up/en-dark/cart.png
screenshots/sign_up/es/cart.png
screenshots/sign_up/es-dark/cart.png
```

代わりにプレイブック全体をすべての言語とテーマで再生するには、`metro live:run playbook` に `--locales` と `--themes` を渡します。

<div id="collecting-screenshots"></div>

### コードからの収集

`run()` は `onScreenshot` を受け取り、実行のスクリーンショットを `PlaybookShot` として受け取れます。テストや独自のツールに便利です:

``` dart
final List<PlaybookShot> shots = [];

final PlaybookReport report = await ShopPlaybook().run(
  onScreenshot: shots.add,
  screenshots: PlaybookScreenshots.marked,
  pixelRatio: 2,
);

for (final PlaybookShot shot in shots) {
  print('${shot.name} ${shot.locale} ${shot.theme}');
}
```

`screenshots` は、どの画面をキャプチャするかを選択します:

| モード | キャプチャ対象 |
|------|----------|
| `PlaybookScreenshots.off` | なし |
| `PlaybookScreenshots.failures` | 失敗したステップの画面のみ |
| `PlaybookScreenshots.marked` | `screenshot()` ステップと、失敗したステップ |
| `PlaybookScreenshots.auto` | `screenshot()` ステップがあるプレイブックではそのステップ、ないプレイブックではすべてのステップ。失敗したステップも含みます。デフォルトです |
| `PlaybookScreenshots.all` | すべてのステップ、`screenshot()` ステップ、失敗したステップ |

`pixelRatio` は、デバイスのサイズに対するスクリーンショットのサイズを設定します（デフォルトは `1.0`）。各 `PlaybookShot` には、[保存](/docs/{{ $version }}/screenshots#saving-a-screenshot)したり `bytes` を読み取ったりできる `screenshot` と、`kind`、そして撮影時の `name`、`locale`、`theme` があります。

<div id="registering-playbooks"></div>

## プレイブックの登録

Metro と `play('name')` が見つけられるように、プレイブックを登録します:

``` dart
nylo.addPlaybooks({
  'sign_up': SignUpPlaybook.new,
});
```

`nylo.getPlaybooks()` は登録済みのものを返します。`AppProvider` で Nylo を設定するときに渡すこともできます:

``` dart
await nylo.configure(
  // ...
  playbooks: playbooks,
);
```

`metro make:playbook` がこれを自動で行います。レジストリを `lib/bootstrap/playbooks.dart` に保持し、新しいプレイブックをそこに追加し、`nylo.configure(...)` に `playbooks` を渡します。レジストリはリリースビルドでは空のマップになります:

``` dart
final Map<String, Playbook Function()> playbooks = kReleaseMode ? const {} : {
  'sign_up': SignUpPlaybook.new,
};
```

登録済みのプレイブックはそのキーの名前で実行されます。未登録のものは `name`、またはスネークケースにしたクラス名（`SignUpPlaybook` は `sign_up`）で実行されます。

<div id="running-a-playbook"></div>

## プレイブックの実行

<div id="running-from-metro"></div>

### Metro から

アプリをデバッグモードで実行した状態で:

``` bash
metro live:run playbook sign_up --speed fast
```

`metro live` の中では `playbook sign_up` と入力します。すべてのオプションについては [Metro Live](/docs/{{ $version }}/metro-live#playbooks) を参照してください。`--record` を追加すると実行の動画を保存します。[録画](/docs/{{ $version }}/metro-live#recording-playbooks)を参照してください。実行が 20 秒後に待機を止める場合、アプリはおそらく `flutter run --no-dds` で起動されています。[Metro Live](/docs/{{ $version }}/metro-live#playbooks) を参照してください。

<div id="running-on-boot"></div>

### 起動時

アプリが画面に表示され次第プレイブックを再生するには、`.env` ファイルにこれらのキーを追加します:

``` bash
PLAYBOOK_ON_BOOT="sign_up,add_to_bag"
PLAYBOOK_SPEED="fast"
```

`PLAYBOOK_ON_BOOT` はプレイブック名のカンマ区切りリストで、`PLAYBOOK_SPEED` は `instant`、`fast`、`natural`、`slow` のいずれかです。どちらもデバッグビルドとプロファイルビルドでのみ有効です。

<div id="running-from-dart"></div>

### Dart から

``` dart
final PlaybookReport report = await SignUpPlaybook().run(
  speed: PlaybookSpeed.fast,
  data: {'email': 'jane@example.com'},
);

if (!report.passed) {
  print(report);
}
```

`run()` は `speed`、`data`、`clock`、`overlay`（デフォルトは `true`）、`onEvent` に加えて、[スクリーンショットの収集](#collecting-screenshots)用の `onScreenshot`、`screenshots`、`pixelRatio` を受け取ります。ステップが失敗しても例外はスローされません -- `report.passed` を確認してください。同時に実行できるプレイブックは 1 つだけです。プレイブックの中で別のプレイブックを実行するには `play()` を使用します。

実行中のプレイブックは `Playbook.stop()` で停止します。残りのステップはスキップされますが、`tearDown` は引き続き実行されます。`Playbook.isRunning` で実行中かどうかを確認できます。

<div id="reports"></div>

## レポート

`run()` は `PlaybookReport` を返します。これには、プレイブックの `name`、`speed`、経過時間 `elapsed`、`stopped` かどうか、そして `PlaybookStepResult` の `steps` リスト（それぞれ `title`、`status`、`elapsed`、`error`、ネストされた `steps` を持つ）が含まれます。`report.passed` はすべてのステップが成功した場合に true となり、レポートを出力すると Metro の出力と同じような見た目になります。`report.toJson()` は同じデータを JSON として返します。

<div id="release-builds"></div>

## リリースビルド

プレイブックはリリースビルドでは実行されないため、ジャーニーがユーザーのアプリを操作することはありません:

- `Playbook.run()` は `UnsupportedError` をスローします。
- `addPlaybooks` は無視されます。
- Metro が生成する `lib/bootstrap/playbooks.dart` のレジストリは空になります。

`Playbook.isAvailable` で、現在のビルドがプレイブックを実行できるかどうかを確認できます。

<div id="testing"></div>

## テスト

ウィジェットテストで `NyTesterClock` を使ってプレイブックを実行し、`expectPlaybookPassed` でそのレポートをアサートします:

``` dart
nyWidgetTest('signs up', (tester) async {
  await tester.visit(SignUpPage.path);
  final report = await SignUpPlaybook().run(clock: NyTesterClock(tester));
  expectPlaybookPassed(report);
});
```

統合テストについては [プレイブックのテスト](/docs/{{ $version }}/testing#testing-playbooks) を参照してください。
