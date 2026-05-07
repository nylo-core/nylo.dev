# Yardımcı Fonksiyonlar
<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [Giriş](#introduction "Giriş")
- [Kullanılabilir Metotlar](#available-methods "Kullanılabilir Metotlar")
  - [Diziler](#arrays-method-list "Diziler")
  - [Dizeler](#strings-method-list "Dizeler")
  - [Sayılar](#numbers-method-list "Sayılar")
  - [Nesneler](#objects-method-list "Nesneler")
  - [Çeşitli](#miscellaneous-method-list "Çeşitli")
- [Diziler](#arrays "Diziler")
- [Dizeler](#strings "Dizeler")
- [Sayılar](#numbers "Sayılar")
- [Nesneler](#objects "Nesneler")
- [Çeşitli](#miscellaneous "Çeşitli")

<div id="introduction"></div>

## Giriş

{{ config('app.name') }}, statik yardımcı sınıflar olarak `Arr`, `Str`, `Number` ve `Obj` — ile birlikte yaygın görevler için birkaç global yardımcı fonksiyon sunar. Hepsi `nylo_framework` üzerinden dışa aktarılır:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

Arr.first([1, 2, 3]);             // 1
Str.slug('Hello World');          // 'hello-world'
Number.currency(1234.56);         // '$1,234.56'
Obj.get(user, 'profile.name');    // 'Anna'
```

> **Not:** `Backpack`, `NyStorage`, `NyCache` ve `NyLogger` için ayrılmış sayfalar mevcuttur: [Backpack](/docs/{{ $version }}/backpack), [Depolama](/docs/{{ $version }}/storage), [Önbellek](/docs/{{ $version }}/cache), [Günlükleme](/docs/{{ $version }}/logging).

<div id="available-methods"></div>

## Kullanılabilir Metotlar

<div id="arrays-method-list"></div>

### Diziler

<div class="docs-method-list" style="columns: 3 12rem; column-gap: 2rem; margin: 1rem 0;">
<div style="padding: 0.375rem 0;"><a href="#method-arr-accessible"><code>Arr.accessible</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-average"><code>Arr.average</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-chunk"><code>Arr.chunk</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-collapse"><code>Arr.collapse</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-countby"><code>Arr.countBy</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-crossjoin"><code>Arr.crossJoin</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-every"><code>Arr.every</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-exceptvalues"><code>Arr.exceptValues</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-first"><code>Arr.first</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-flatmap"><code>Arr.flatMap</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-flatten"><code>Arr.flatten</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-groupby"><code>Arr.groupBy</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-indexed"><code>Arr.indexed</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-interleave"><code>Arr.interleave</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-isassoc"><code>Arr.isAssoc</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-islist"><code>Arr.isList</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-join"><code>Arr.join</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-keyby"><code>Arr.keyBy</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-last"><code>Arr.last</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-map"><code>Arr.map</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-mapwithkeys"><code>Arr.mapWithKeys</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-max"><code>Arr.max</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-median"><code>Arr.median</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-min"><code>Arr.min</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-move"><code>Arr.move</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-onlyvalues"><code>Arr.onlyValues</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-partition"><code>Arr.partition</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-pluck"><code>Arr.pluck</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-prepend"><code>Arr.prepend</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-push"><code>Arr.push</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-random"><code>Arr.random</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-randommany"><code>Arr.randomMany</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-reject"><code>Arr.reject</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-replaceat"><code>Arr.replaceAt</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-select"><code>Arr.select</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-shuffle"><code>Arr.shuffle</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-sole"><code>Arr.sole</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-some"><code>Arr.some</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-sort"><code>Arr.sort</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-sortdesc"><code>Arr.sortDesc</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-sortrecursive"><code>Arr.sortRecursive</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-sum"><code>Arr.sum</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-swap"><code>Arr.swap</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-take"><code>Arr.take</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-unique"><code>Arr.unique</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-where"><code>Arr.where</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-wherenotnull"><code>Arr.whereNotNull</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-arr-wrap"><code>Arr.wrap</code></a></div>
</div>

<div id="strings-method-list"></div>

### Dizeler

<div class="docs-method-list" style="columns: 3 12rem; column-gap: 2rem; margin: 1rem 0;">
<div style="padding: 0.375rem 0;"><a href="#method-str-after"><code>Str.after</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-afterlast"><code>Str.afterLast</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-before"><code>Str.before</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-beforelast"><code>Str.beforeLast</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-between"><code>Str.between</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-betweenfirst"><code>Str.betweenFirst</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-camel"><code>Str.camel</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-charat"><code>Str.charAt</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-contains"><code>Str.contains</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-containsall"><code>Str.containsAll</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-deduplicate"><code>Str.deduplicate</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-endswith"><code>Str.endsWith</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-excerpt"><code>Str.excerpt</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-finish"><code>Str.finish</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-headline"><code>Str.headline</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-is"><code>Str.is_</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-isascii"><code>Str.isAscii</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-isjson"><code>Str.isJson</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-isulid"><code>Str.isUlid</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-isurl"><code>Str.isUrl</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-isuuid"><code>Str.isUuid</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-kebab"><code>Str.kebab</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-lcfirst"><code>Str.lcfirst</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-length"><code>Str.length</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-limit"><code>Str.limit</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-lower"><code>Str.lower</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-mask"><code>Str.mask</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-match"><code>Str.match</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-matchall"><code>Str.matchAll</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-padboth"><code>Str.padBoth</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-padleft"><code>Str.padLeft</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-padnumber"><code>Str.padNumber</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-padright"><code>Str.padRight</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-password"><code>Str.password</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-position"><code>Str.position</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-random"><code>Str.random</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-remove"><code>Str.remove</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-repeat"><code>Str.repeat</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-replace"><code>Str.replace</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-replacefirst"><code>Str.replaceFirst</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-replacelast"><code>Str.replaceLast</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-reverse"><code>Str.reverse</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-slug"><code>Str.slug</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-snake"><code>Str.snake</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-squish"><code>Str.squish</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-start"><code>Str.start</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-startswith"><code>Str.startsWith</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-studly"><code>Str.studly</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-substr"><code>Str.substr</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-substrcount"><code>Str.substrCount</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-swap"><code>Str.swap</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-take"><code>Str.take</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-title"><code>Str.title</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-ucfirst"><code>Str.ucfirst</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-ucsplit"><code>Str.ucsplit</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-ulid"><code>Str.ulid</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-unwrap"><code>Str.unwrap</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-upper"><code>Str.upper</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-uuid"><code>Str.uuid</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-uuid7"><code>Str.uuid7</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-wordcount"><code>Str.wordCount</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-words"><code>Str.words</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-str-wrap"><code>Str.wrap</code></a></div>
</div>

<div id="numbers-method-list"></div>

### Sayılar

<div class="docs-method-list" style="columns: 3 12rem; column-gap: 2rem; margin: 1rem 0;">
<div style="padding: 0.375rem 0;"><a href="#method-number-abbreviate"><code>Number.abbreviate</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-number-average"><code>Number.average</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-number-between"><code>Number.between</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-number-ceil"><code>Number.ceil</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-number-clamp"><code>Number.clamp</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-number-currency"><code>Number.currency</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-number-defaultcurrency"><code>Number.defaultCurrency</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-number-defaultlocale"><code>Number.defaultLocale</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-number-degrees"><code>Number.degrees</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-number-duration"><code>Number.duration</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-number-filesize"><code>Number.fileSize</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-number-floor"><code>Number.floor</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-number-forhumans"><code>Number.forHumans</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-number-format"><code>Number.format</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-number-gcd"><code>Number.gcd</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-number-lcm"><code>Number.lcm</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-number-lerp"><code>Number.lerp</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-number-max"><code>Number.max</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-number-median"><code>Number.median</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-number-min"><code>Number.min</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-number-ordinal"><code>Number.ordinal</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-number-pairs"><code>Number.pairs</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-number-parsefloat"><code>Number.parseFloat</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-number-parseint"><code>Number.parseInt</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-number-percentage"><code>Number.percentage</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-number-radians"><code>Number.radians</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-number-random"><code>Number.random</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-number-range"><code>Number.range</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-number-round"><code>Number.round</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-number-scale"><code>Number.scale</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-number-spell"><code>Number.spell</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-number-spellordinal"><code>Number.spellOrdinal</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-number-sum"><code>Number.sum</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-number-tobytes"><code>Number.toBytes</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-number-trim"><code>Number.trim</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-number-usecurrency"><code>Number.useCurrency</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-number-uselocale"><code>Number.useLocale</code></a></div>
</div>

<div id="objects-method-list"></div>

### Nesneler

<div class="docs-method-list" style="columns: 3 12rem; column-gap: 2rem; margin: 1rem 0;">
<div style="padding: 0.375rem 0;"><a href="#method-obj-add"><code>Obj.add</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-obj-deepequals"><code>Obj.deepEquals</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-obj-divide"><code>Obj.divide</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-obj-dot"><code>Obj.dot</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-obj-except"><code>Obj.except</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-obj-exists"><code>Obj.exists</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-obj-flip"><code>Obj.flip</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-obj-forget"><code>Obj.forget</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-obj-get"><code>Obj.get</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-obj-getbool"><code>Obj.getBool</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-obj-getdouble"><code>Obj.getDouble</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-obj-getint"><code>Obj.getInt</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-obj-getlist"><code>Obj.getList</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-obj-getmap"><code>Obj.getMap</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-obj-getstring"><code>Obj.getString</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-obj-has"><code>Obj.has</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-obj-hasall"><code>Obj.hasAll</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-obj-hasany"><code>Obj.hasAny</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-obj-mapkeys"><code>Obj.mapKeys</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-obj-mapvalues"><code>Obj.mapValues</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-obj-merge"><code>Obj.merge</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-obj-only"><code>Obj.only</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-obj-prependkeyswith"><code>Obj.prependKeysWith</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-obj-pull"><code>Obj.pull</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-obj-query"><code>Obj.query</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-obj-set"><code>Obj.set</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-obj-undot"><code>Obj.undot</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-obj-wherenotempty"><code>Obj.whereNotEmpty</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-obj-wherenotnull"><code>Obj.whereNotNull</code></a></div>
</div>

<div id="miscellaneous-method-list"></div>

### Çeşitli

<div class="docs-method-list" style="columns: 3 12rem; column-gap: 2rem; margin: 1rem 0;">
<div style="padding: 0.375rem 0;"><a href="#method-api"><code>api</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-badge-helpers"><code>clearBadgeNumber</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-datatomodel"><code>dataToModel</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-dump"><code>dump</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-event"><code>event</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-getasset"><code>getAsset</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-getenv"><code>getEnv</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-getimageasset"><code>getImageAsset</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-loadjson"><code>loadJson</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-match"><code>match</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-now"><code>now</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-nyhexcolor"><code>nyHexColor</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-print-helpers"><code>printDebug</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-print-helpers"><code>printError</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-print-helpers"><code>printInfo</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-print-helpers"><code>printSuccess</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-print-helpers"><code>printWarning</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-badge-helpers"><code>setBadgeNumber</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-shownextlog"><code>showNextLog</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-sleep"><code>sleep</code></a></div>
<div style="padding: 0.375rem 0;"><a href="#method-trans"><code>trans</code></a></div>
</div>

<div id="arrays"></div>

## Diziler

<div id="method-arr-accessible"></div>

#### `Arr.accessible()`

Verilen değerin dizi erişimine uygun olup olmadığını belirler (`List` veya `Map`):

``` dart
Arr.accessible([1, 2, 3]);  // true
Arr.accessible({'a': 1});   // true
Arr.accessible('hello');    // false
Arr.accessible(null);       // false
```

<div id="method-arr-average"></div>

#### `Arr.average()`

Listenin aritmetik ortalamasını döndürür; liste boşsa `0` döner. Her elemandan sayı çıkarmak için `by` parametresini geçin:

``` dart
Arr.average([2, 4, 6]);                // 4.0
Arr.average(orders, by: (o) => o.total); // toplam ortalaması
```

<div id="method-arr-chunk"></div>

#### `Arr.chunk()`

Bir listeyi verilen boyuttaki parçalara böler:

``` dart
Arr.chunk([1, 2, 3, 4, 5], 2);
// [[1, 2], [3, 4], [5]]
```

<div id="method-arr-collapse"></div>

#### `Arr.collapse()`

Liste listesini tek bir listeye daraltır:

``` dart
Arr.collapse([[1, 2], [3, 4], [5]]);
// [1, 2, 3, 4, 5]
```

<div id="method-arr-countby"></div>

#### `Arr.countBy()`

Listedeki oluşumları sayar; isteğe bağlı olarak bir değer çıkarıcıyla gruplar:

``` dart
Arr.countBy(['a', 'b', 'a', 'c']);
// {'a': 2, 'b': 1, 'c': 1}

Arr.countBy(orders, by: (o) => o.status);
// {'paid': 12, 'pending': 3}
```

<div id="method-arr-crossjoin"></div>

#### `Arr.crossJoin()`

Verilen listeleri çapraz birleştirir ve olası tüm kombinasyonları döndürür:

``` dart
Arr.crossJoin([[1, 2], ['a', 'b']]);
// [[1, 'a'], [1, 'b'], [2, 'a'], [2, 'b']]
```

<div id="method-arr-every"></div>

#### `Arr.every()`

Listenin her elemanı koşulu sağladığında `true` döndürür. Boş listeler için doğruluk değeri kabul edilir:

``` dart
Arr.every([2, 4, 6], (n) => n.isEven); // true
Arr.every([1, 2, 3], (n) => n.isEven); // false
```

<div id="method-arr-exceptvalues"></div>

#### `Arr.exceptValues()`

Verilen değerlerin her birini çıkardıktan sonra listeyi döndürür:

``` dart
Arr.exceptValues([1, 2, 3, 4], [2, 4]); // [1, 3]
```

<div id="method-arr-first"></div>

#### `Arr.first()`

Koşulu sağlayan ilk elemanı veya varsayılan değeri döndürür:

``` dart
Arr.first([1, 2, 3]);                                   // 1
Arr.first([1, 2, 3, 4], predicate: (n) => n.isEven);    // 2
Arr.first([], defaultValue: 99);                        // 99
```

<div id="method-arr-flatmap"></div>

#### `Arr.flatMap()`

Her elemanı `fn` üzerinden eşler ve ortaya çıkan yinelenebilirleri düzleştirir:

``` dart
Arr.flatMap(pages, (p) => p.items); // tüm sayfalardan tüm öğeler
Arr.flatMap([1, 2, 3], (n) => [n, n * 10]); // [1, 10, 2, 20, 3, 30]
```

<div id="method-arr-flatten"></div>

#### `Arr.flatten()`

İç içe geçmiş yinelenebilirleri tek düzeyli bir listeye düzleştirir. `depth` ne kadar derine gidileceğini sınırlar; `-1` sınırsız anlamına gelir:

``` dart
Arr.flatten([1, [2, [3]]]);              // [1, 2, 3]
Arr.flatten([1, [2, [3]]], depth: 1);    // [1, 2, [3]]
```

<div id="method-arr-groupby"></div>

#### `Arr.groupBy()`

Listeyi `by` tarafından döndürülen değere göre anahtarlanmış bir eşlemede gruplar:

``` dart
Arr.groupBy(messages, (m) => m.date);
// {2024-01-01: [...], 2024-01-02: [...]}
```

<div id="method-arr-indexed"></div>

#### `Arr.indexed()`

Her elemanı indeksiyle eşleştirir ve `(index, value)` kayıtlarından oluşan bir liste döndürür:

``` dart
for (final (i, v) in Arr.indexed(['a', 'b', 'c'])) {
  print('$i: $v'); // '0: a', '1: b', '2: c'
}
```

<div id="method-arr-interleave"></div>

#### `Arr.interleave()`

Her çift eleman arasına bir ayırıcı ekler. Ayırıcılı widget children oluşturmak için kullanışlıdır:

``` dart
Arr.interleave([1, 2, 3], 0); // [1, 0, 2, 0, 3]

Column(children: Arr.interleave(tiles, const Divider()));
```

<div id="method-arr-isassoc"></div>

#### `Arr.isAssoc()`

Değer bir `Map` (ilişkisel) olduğunda `true` döndürür:

``` dart
Arr.isAssoc({'a': 1});  // true
Arr.isAssoc([1, 2, 3]); // false
```

<div id="method-arr-islist"></div>

#### `Arr.isList()`

Değer bir `List` olduğunda `true` döndürür:

``` dart
Arr.isList([1, 2, 3]); // true
Arr.isList({'a': 1});  // false
```

<div id="method-arr-join"></div>

#### `Arr.join()`

Listeyi bir dizeye birleştirir. Son eleman farklı bir ayırıcıyla birleştirilebilir:

``` dart
Arr.join(['Anna', 'Brad', 'Carol'], ', ', ' ve ');
// 'Anna, Brad ve Carol'

Arr.join(['a', 'b', 'c']);
// 'a, b, c'
```

<div id="method-arr-keyby"></div>

#### `Arr.keyBy()`

Listeyi bir anahtardaki değere göre indeksler. O anahtarda `null` değeri olan girişler atlanır:

``` dart
Arr.keyBy([
  {'id': 1, 'name': 'Anna'},
  {'id': 2, 'name': 'Brad'},
], 'id');
// {1: {'id': 1, 'name': 'Anna'}, 2: {'id': 2, 'name': 'Brad'}}
```

<div id="method-arr-last"></div>

#### `Arr.last()`

Koşulu sağlayan son elemanı veya varsayılan değeri döndürür:

``` dart
Arr.last([1, 2, 3, 4], predicate: (n) => n.isEven); // 4
Arr.last([1, 2, 3]);                                 // 3
```

<div id="method-arr-map"></div>

#### `Arr.map()`

Her elemanı indeksi de alan bir geri çağırma üzerinden eşler:

``` dart
Arr.map(['a', 'b'], (v, i) => '$i:$v'); // ['0:a', '1:b']
```

<div id="method-arr-mapwithkeys"></div>

#### `Arr.mapWithKeys()`

Her elemanı bir `MapEntry`'ye eşler ve sonuç haritasını döndürür:

``` dart
Arr.mapWithKeys(
  [{'id': 1, 'name': 'Anna'}, {'id': 2, 'name': 'Brad'}],
  (m) => MapEntry(m['id'], m['name']),
);
// {1: 'Anna', 2: 'Brad'}
```

<div id="method-arr-max"></div>

#### `Arr.max()`

`by` değeri en büyük olan elemanı döndürür; `by` belirtilmezse en büyük elemanı döndürür:

``` dart
Arr.max([3, 1, 4, 1, 5]);            // 5
Arr.max(users, by: (u) => u.age);    // en yüksek yaştaki kullanıcı
```

<div id="method-arr-median"></div>

#### `Arr.median()`

Listenin medyanını döndürür; liste boşsa `0` döner:

``` dart
Arr.median([1, 3, 5]);    // 3.0
Arr.median([1, 2, 3, 4]); // 2.5
```

<div id="method-arr-min"></div>

#### `Arr.min()`

`by` değeri en küçük olan elemanı döndürür; `by` belirtilmezse en küçük elemanı döndürür:

``` dart
Arr.min([3, 1, 4, 1, 5]);            // 1
Arr.min(users, by: (u) => u.age);    // en genç kullanıcı
```

<div id="method-arr-move"></div>

#### `Arr.move()`

`from` konumundaki elemanı `to` konumuna taşınmış yeni bir liste döndürür. `to` geçerli aralıkla sınırlandırılır:

``` dart
Arr.move(['a', 'b', 'c', 'd'], 0, 2); // ['b', 'c', 'a', 'd']
```

<div id="method-arr-onlyvalues"></div>

#### `Arr.onlyValues()`

Listede verilen değerlerde de bulunan elemanları döndürür:

``` dart
Arr.onlyValues([1, 2, 3, 4], [2, 4, 9]); // [2, 4]
```

<div id="method-arr-partition"></div>

#### `Arr.partition()`

Listeyi koşula göre `[eşleşen, eşleşmeyen]` çiftine böler:

``` dart
final [evens, odds] = Arr.partition([1, 2, 3, 4], (n) => n.isEven);
// evens: [2, 4], odds: [1, 3]
```

<div id="method-arr-pluck"></div>

#### `Arr.pluck()`

Listedeki her haritadan bir anahtardaki değeri çeker:

``` dart
Arr.pluck([
  {'name': 'Anna'},
  {'name': 'Brad'},
], 'name');
// ['Anna', 'Brad']
```

<div id="method-arr-prepend"></div>

#### `Arr.prepend()`

Değerin başa eklendiği yeni bir liste döndürür:

``` dart
Arr.prepend([2, 3], 1); // [1, 2, 3]
```

<div id="method-arr-push"></div>

#### `Arr.push()`

Değerin sona eklendiği yeni bir liste döndürür:

``` dart
Arr.push([1, 2], 3); // [1, 2, 3]
```

<div id="method-arr-random"></div>

#### `Arr.random()`

Listeden rastgele bir eleman döndürür. Liste boşsa hata fırlatır:

``` dart
Arr.random(['rock', 'paper', 'scissors']); // üçünden biri
```

<div id="method-arr-randommany"></div>

#### `Arr.randomMany()`

Tekrar etmeksizin en fazla `count` rastgele eleman döndürür:

``` dart
Arr.randomMany([1, 2, 3, 4, 5], 2); // örn. [3, 1]
```

<div id="method-arr-reject"></div>

#### `Arr.reject()`

Listeyi koşulu *sağlamayan* elemanlara filtreler ([`where`](#method-arr-where) metodunun tersi):

``` dart
Arr.reject([1, 2, 3, 4], (n) => n.isEven); // [1, 3]
```

<div id="method-arr-replaceat"></div>

#### `Arr.replaceAt()`

`index` konumundaki elemanın `value` ile değiştirildiği yeni bir liste döndürür. `index` aralık dışındaysa `RangeError` fırlatır:

``` dart
Arr.replaceAt(['a', 'b', 'c'], 1, 'B'); // ['a', 'B', 'c']
```

<div id="method-arr-select"></div>

#### `Arr.select()`

Listedeki her haritayı yalnızca verilen anahtarlara indirgeyerek döndürür:

``` dart
Arr.select([
  {'name': 'Anna', 'role': 'admin', 'age': 30},
  {'name': 'Brad', 'role': 'user',  'age': 25},
], ['name', 'role']);
// [{'name': 'Anna', 'role': 'admin'}, {'name': 'Brad', 'role': 'user'}]
```

<div id="method-arr-shuffle"></div>

#### `Arr.shuffle()`

Listenin karıştırılmış bir kopyasını döndürür. Deterministik sonuç için `seed` geçin:

``` dart
Arr.shuffle([1, 2, 3, 4, 5]);
Arr.shuffle([1, 2, 3], seed: 42); // deterministik
```

<div id="method-arr-sole"></div>

#### `Arr.sole()`

Koşulu sağlayan tek elemanı döndürür. Sıfır veya birden fazla eleman eşleşirse hata fırlatır:

``` dart
Arr.sole([1, 2, 3, 4], predicate: (n) => n == 3); // 3
Arr.sole([1, 2, 3], predicate: (n) => n.isEven);  // 2
```

<div id="method-arr-some"></div>

#### `Arr.some()`

En az bir eleman koşulu sağladığında `true` döndürür:

``` dart
Arr.some([1, 2, 3], (n) => n.isEven); // true
Arr.some([1, 3, 5], (n) => n.isEven); // false
```

<div id="method-arr-sort"></div>

#### `Arr.sort()`

Listenin sıralanmış bir kopyasını döndürür:

``` dart
Arr.sort([3, 1, 2]);                                // [1, 2, 3]
Arr.sort(users, compare: (a, b) => a.age - b.age);   // yaşa göre artan
```

<div id="method-arr-sortdesc"></div>

#### `Arr.sortDesc()`

Listenin azalan sırada sıralanmış bir kopyasını döndürür:

``` dart
Arr.sortDesc([3, 1, 2]); // [3, 2, 1]
```

<div id="method-arr-sortrecursive"></div>

#### `Arr.sortRecursive()`

Listeyi özyinelemeli olarak sıralar. İç içe geçmiş listeler her derinlikte sıralanır:

``` dart
Arr.sortRecursive([[3, 1, 2], [9, 7, 8]]);
// [[1, 2, 3], [7, 8, 9]]
```

<div id="method-arr-sum"></div>

#### `Arr.sum()`

Listenin toplamını döndürür. Her elemandan sayı çıkarmak için `by` geçin:

``` dart
Arr.sum([1, 2, 3]);                          // 6
Arr.sum(orders, by: (o) => o.total);         // toplamların toplamı
```

<div id="method-arr-swap"></div>

#### `Arr.swap()`

`i` ve `j` konumlarındaki elemanların yer değiştirdiği yeni bir liste döndürür:

``` dart
Arr.swap(['a', 'b', 'c'], 0, 2); // ['c', 'b', 'a']
```

<div id="method-arr-take"></div>

#### `Arr.take()`

İlk `count` elemanı döndürür; `count` negatifse son `count` elemanı döndürür:

``` dart
Arr.take([1, 2, 3, 4, 5], 2);   // [1, 2]
Arr.take([1, 2, 3, 4, 5], -2);  // [4, 5]
```

<div id="method-arr-unique"></div>

#### `Arr.unique()`

Listenin benzersiz değerlerini döndürür:

``` dart
Arr.unique([1, 2, 2, 3, 1]); // [1, 2, 3]
```

<div id="method-arr-where"></div>

#### `Arr.where()`

Listeyi koşulu sağlayan elemanlara filtreler:

``` dart
Arr.where([1, 2, 3, 4], (n) => n.isEven); // [2, 4]
```

<div id="method-arr-wherenotnull"></div>

#### `Arr.whereNotNull()`

`null` değerleri çıkarılmış listeyi döndürür:

``` dart
Arr.whereNotNull([1, null, 2, null, 3]); // [1, 2, 3]
```

<div id="method-arr-wrap"></div>

#### `Arr.wrap()`

Değer zaten bir liste değilse onu `List` içine sarar. Değer `null` ise boş liste döndürür:

``` dart
Arr.wrap('foo');     // ['foo']
Arr.wrap([1, 2]);    // [1, 2]
Arr.wrap(null);      // []
```

<div id="strings"></div>

## Dizeler

<div id="method-str-after"></div>

#### `Str.after()`

`search` öğesinin `subject` içindeki ilk oluşumundan sonraki bölümü döndürür:

``` dart
Str.after('hello world hello', 'hello'); // ' world hello'
```

<div id="method-str-afterlast"></div>

#### `Str.afterLast()`

`search` öğesinin `subject` içindeki son oluşumundan sonraki bölümü döndürür:

``` dart
Str.afterLast('app/Http/Controllers', '/'); // 'Controllers'
```

<div id="method-str-before"></div>

#### `Str.before()`

`search` öğesinin `subject` içindeki ilk oluşumundan önceki bölümü döndürür:

``` dart
Str.before('hello world', ' '); // 'hello'
```

<div id="method-str-beforelast"></div>

#### `Str.beforeLast()`

`search` öğesinin `subject` içindeki son oluşumundan önceki bölümü döndürür:

``` dart
Str.beforeLast('app/Http/Controllers', '/'); // 'app/Http'
```

<div id="method-str-between"></div>

#### `Str.between()`

`from` ile `to` arasındaki `subject` bölümünü döndürür (açgözlü):

``` dart
Str.between('[a] foo [b]', '[', ']'); // 'a] foo [b'
```

<div id="method-str-betweenfirst"></div>

#### `Str.betweenFirst()`

`from` ile `to` arasındaki `subject`'in en küçük bölümünü döndürür:

``` dart
Str.betweenFirst('[a] foo [b]', '[', ']'); // 'a'
```

<div id="method-str-camel"></div>

#### `Str.camel()`

Değeri `camelCase`'e dönüştürür:

``` dart
Str.camel('foo_bar');     // 'fooBar'
Str.camel('Hello world'); // 'helloWorld'
```

<div id="method-str-charat"></div>

#### `Str.charAt()`

Verilen indeksteki karakteri döndürür. Negatif indeksler sondan sayar. Aralık dışındaysa `null` döndürür:

``` dart
Str.charAt('hello', 1);  // 'e'
Str.charAt('hello', -1); // 'o'
Str.charAt('hello', 99); // null
```

<div id="method-str-contains"></div>

#### `Str.contains()`

`haystack`'in `needles`'lardan herhangi birini içerip içermediğini belirler:

``` dart
Str.contains('hello world', 'world');                       // true
Str.contains('hello world', ['cat', 'world']);              // true
Str.contains('Hello', 'hello', ignoreCase: true);           // true
```

<div id="method-str-containsall"></div>

#### `Str.containsAll()`

`haystack`'in `needles`'ların tümünü içerip içermediğini belirler:

``` dart
Str.containsAll('hello world', ['hello', 'world']); // true
Str.containsAll('hello world', ['hello', 'cat']);   // false
```

<div id="method-str-deduplicate"></div>

#### `Str.deduplicate()`

Ardışık `character` dizilerini tek bir örnekle değiştirir:

``` dart
Str.deduplicate('hello   world');     // 'hello world'
Str.deduplicate('//path//to//', '/'); // '/path/to/'
```

<div id="method-str-endswith"></div>

#### `Str.endsWith()`

`haystack`'in `needles`'lardan herhangi biriyle bitip bitmediğini belirler:

``` dart
Str.endsWith('app.dart', '.dart');           // true
Str.endsWith('app.dart', ['.dart', '.ts']);  // true
```

<div id="method-str-excerpt"></div>

#### `Str.excerpt()`

`phrase`'in ilk oluşumu etrafındaki metin parçasını çıkarır. İfade bulunamazsa `null` döndürür:

``` dart
Str.excerpt(
  'This is my favorite quote of all time.',
  'favorite',
  radius: 5,
);
// '...is my favorite quote ...'
```

<div id="method-str-finish"></div>

#### `Str.finish()`

Değeri `cap`'ın tek bir örneğiyle sonlandırır:

``` dart
Str.finish('hello', '!');     // 'hello!'
Str.finish('hello!!!', '!');  // 'hello!'
```

<div id="method-str-headline"></div>

#### `Str.headline()`

Bir dizeyi büyük harf sınırları, alt çizgiler, kısa çizgiler ve boşluklara göre kelimelere böler, ardından başlık büyük harfine dönüştürür:

``` dart
Str.headline('steve_jobs');           // 'Steve Jobs'
Str.headline('EmailNotificationSent'); // 'Email Notification Sent'
```

<div id="method-str-is"></div>

#### `Str.is_()`

Değerin verilen desenle eşleşip eşleşmediğini belirler. Yıldız işaretleri joker karakter olarak işlev görür:

``` dart
Str.is_('foo.*', 'foo.bar');                       // true
Str.is_(['admin/*', 'user/*'], 'admin/profile');   // true
Str.is_('foo', 'bar');                              // false
```

> **Not:** Sondaki alt çizgi, `is` ayrılmış Dart anahtar sözcüğünden kaçınmak içindir.

<div id="method-str-isascii"></div>

#### `Str.isAscii()`

Değer yalnızca 7-bit ASCII karakterler içeriyorsa `true` döndürür:

``` dart
Str.isAscii('hello');  // true
Str.isAscii('héllo');  // false
```

<div id="method-str-isjson"></div>

#### `Str.isJson()`

Değer geçerli JSON ise `true` döndürür:

``` dart
Str.isJson('{"a": 1}');   // true
Str.isJson('not json');   // false
```

<div id="method-str-isulid"></div>

#### `Str.isUlid()`

Değer bir ULID ise (26 karakter, Crockford base32) `true` döndürür:

``` dart
Str.isUlid('01ARZ3NDEKTSV4RRFFQ69G5FAV'); // true
Str.isUlid('not a ulid');                  // false
```

<div id="method-str-isurl"></div>

#### `Str.isUrl()`

Değer geçerli bir URL ise `true` döndürür:

``` dart
Str.isUrl('https://nylo.dev'); // true
Str.isUrl('not a url');        // false
```

<div id="method-str-isuuid"></div>

#### `Str.isUuid()`

Değer geçerli bir UUID ise (herhangi bir sürüm) `true` döndürür:

``` dart
Str.isUuid('a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11'); // true
Str.isUuid('hello');                                 // false
```

<div id="method-str-kebab"></div>

#### `Str.kebab()`

Değeri `kebab-case`'e dönüştürür:

``` dart
Str.kebab('fooBar');      // 'foo-bar'
Str.kebab('Hello World'); // 'hello-world'
```

<div id="method-str-lcfirst"></div>

#### `Str.lcfirst()`

Değerin ilk karakterini küçük harfe dönüştürür:

``` dart
Str.lcfirst('Hello world'); // 'hello world'
```

<div id="method-str-length"></div>

#### `Str.length()`

Değerin uzunluğunu döndürür:

``` dart
Str.length('hello'); // 5
```

<div id="method-str-limit"></div>

#### `Str.limit()`

Değeri verilen karakter sayısına kısaltır ve bir sonek ekler:

``` dart
Str.limit('A long sentence', 6);              // 'A long...'
Str.limit('A long sentence', 6, '... read');  // 'A long... read'
```

<div id="method-str-lower"></div>

#### `Str.lower()`

Değeri küçük harfe dönüştürür:

``` dart
Str.lower('HELLO'); // 'hello'
```

<div id="method-str-mask"></div>

#### `Str.mask()`

`index` konumundan başlayarak `length` karakter boyunca (ya da `length` belirtilmezse sona kadar) dizeyi bir karakterle maskeler:

``` dart
Str.mask('user@example.com', '*', 3);    // 'use*************'
Str.mask('user@example.com', '*', 3, 5); // 'use*****mple.com'
```

<div id="method-str-match"></div>

#### `Str.match()`

`subject` içinde `pattern` regex deseninin ilk eşleşmesini döndürür; eşleşme yoksa `null` döndürür:

``` dart
Str.match(RegExp(r'\d+'), 'item 42 in 99 stock'); // '42'
```

> **Not:** Bu bir regex eşleştiricisidir — `Map` tabanlı değer aramalarında kullanılan global [`match`](#method-match) yardımcısından farklıdır.

<div id="method-str-matchall"></div>

#### `Str.matchAll()`

`subject` içinde `pattern`'in tüm regex eşleşmelerini döndürür:

``` dart
Str.matchAll(RegExp(r'\d+'), 'item 42 in 99 stock'); // ['42', '99']
```

<div id="method-str-padboth"></div>

#### `Str.padBoth()`

`length` ulaşana kadar değerin her iki tarafını `pad` ile doldurur:

``` dart
Str.padBoth('5', 5, '0'); // '00500'
```

<div id="method-str-padleft"></div>

#### `Str.padLeft()`

Değerin sol tarafını doldurur:

``` dart
Str.padLeft('5', 3, '0'); // '005'
```

<div id="method-str-padnumber"></div>

#### `Str.padNumber()`

Sayısal bir dizeyi baştaki sıfırlarla doldurur:

``` dart
Str.padNumber('5', 3); // '005'
```

<div id="method-str-padright"></div>

#### `Str.padRight()`

Değerin sağ tarafını doldurur:

``` dart
Str.padRight('5', 3, '0'); // '500'
```

<div id="method-str-password"></div>

#### `Str.password()`

Kriptografik olarak rastgele bir şifre oluşturur. Karakter sınıflarını adlandırılmış argümanlar aracılığıyla açıp kapatın:

``` dart
Str.password(12);                                   // harfler + sayılar + semboller
Str.password(12, symbols: false);                   // yalnızca harfler + sayılar
Str.password(8, letters: false, symbols: false);    // yalnızca rakamlar
```

<div id="method-str-position"></div>

#### `Str.position()`

`haystack` içinde `needle`'ın ilk oluşumunun indeksini döndürür; bulunamazsa `null` döndürür:

``` dart
Str.position('hello world', 'world');             // 6
Str.position('hello world hello', 'hello', offset: 1); // 12
Str.position('hello', 'cat');                      // null
```

<div id="method-str-random"></div>

#### `Str.random()`

Verilen uzunlukta kriptografik olarak rastgele alfasayısal bir dize oluşturur:

``` dart
Str.random();    // 16 karakter, örn. 'aB3k9XzMq7VnPsRt'
Str.random(8);   // 8 karakter
```

<div id="method-str-remove"></div>

#### `Str.remove()`

`subject`'ten `search`'te belirtilenlerden herhangi birini kaldırır:

``` dart
Str.remove('o', 'hello world');                  // 'hell wrld'
Str.remove(['e', 'l'], 'hello world');           // 'ho word'
Str.remove('Hello', 'hello world', caseSensitive: false); // ' world'
```

<div id="method-str-repeat"></div>

#### `Str.repeat()`

Değeri verilen sayıda tekrarlar:

``` dart
Str.repeat('ab', 3); // 'ababab'
```

<div id="method-str-replace"></div>

#### `Str.replace()`

`subject` içindeki `search`'ün her oluşumunu `replace` ile değiştirir:

``` dart
Str.replace('o', '0', 'hello world');           // 'hell0 w0rld'
Str.replace(['o', 'l'], '*', 'hello world');     // 'he*** w*r*d'
```

<div id="method-str-replacefirst"></div>

#### `Str.replaceFirst()`

`search`'ün ilk oluşumunu `replace` ile değiştirir:

``` dart
Str.replaceFirst('hello', 'hi', 'hello hello'); // 'hi hello'
```

<div id="method-str-replacelast"></div>

#### `Str.replaceLast()`

`search`'ün son oluşumunu `replace` ile değiştirir:

``` dart
Str.replaceLast('hello', 'hi', 'hello hello'); // 'hello hi'
```

<div id="method-str-reverse"></div>

#### `Str.reverse()`

Değeri tersine çevirir:

``` dart
Str.reverse('hello'); // 'olleh'
```

<div id="method-str-slug"></div>

#### `Str.slug()`

Başlıktan URL dostu bir slug oluşturur:

``` dart
Str.slug('Hello World');                                  // 'hello-world'
Str.slug('Tech & Code', dictionary: {'&': 'and'});        // 'tech-and-code'
Str.slug('Hello World', separator: '_');                  // 'hello_world'
```

<div id="method-str-snake"></div>

#### `Str.snake()`

Değeri `snake_case`'e dönüştürür. `_` dışında bir şey kullanmak için özel sınırlayıcı geçin:

``` dart
Str.snake('helloWorld');         // 'hello_world'
Str.snake('helloWorld', '-');    // 'hello-world'
```

<div id="method-str-squish"></div>

#### `Str.squish()`

Baştaki/sondaki boşlukları kırpar ve dahili boşlukları tek boşluğa daraltır:

``` dart
Str.squish('   hello    world   '); // 'hello world'
```

<div id="method-str-start"></div>

#### `Str.start()`

Değeri `prefix`'in tek bir örneğiyle başlatır:

``` dart
Str.start('this/string', '/');     // '/this/string'
Str.start('//this/string', '/');   // '/this/string'
```

<div id="method-str-startswith"></div>

#### `Str.startsWith()`

`haystack`'in `needles`'lardan herhangi biriyle başlayıp başlamadığını belirler:

``` dart
Str.startsWith('hello world', 'hello');           // true
Str.startsWith('hello world', ['cat', 'hello']);  // true
```

<div id="method-str-studly"></div>

#### `Str.studly()`

Değeri `StudlyCase` / `PascalCase`'e dönüştürür:

``` dart
Str.studly('foo_bar');     // 'FooBar'
Str.studly('hello world'); // 'HelloWorld'
```

<div id="method-str-substr"></div>

#### `Str.substr()`

`start` konumundan başlayarak `length` karakter uzunluğunda bir alt dize döndürür. Negatif `start` sondan sayar:

``` dart
Str.substr('profile', 4);       // 'ile'
Str.substr('profile', 4, 2);    // 'il'
Str.substr('profile', -3);      // 'ile'
```

<div id="method-str-substrcount"></div>

#### `Str.substrCount()`

`haystack` içinde `needle`'ın örtüşmeyen oluşumlarını sayar:

``` dart
Str.substrCount('hello hello hello', 'hello'); // 3
```

<div id="method-str-swap"></div>

#### `Str.swap()`

Arama → değiştirme çiftlerinden oluşan bir `Map` kullanarak birden fazla alt dizeyi değiştirir:

``` dart
Str.swap({'foo': 'bar', 'hello': 'hi'}, 'hello foo'); // 'hi bar'
```

<div id="method-str-take"></div>

#### `Str.take()`

Değerin ilk `limit` karakterini döndürür. Negatif limit sondan döndürür:

``` dart
Str.take('Build something amazing!', 5); // 'Build'
Str.take('Build something amazing!', -9); // ' amazing!'
```

<div id="method-str-title"></div>

#### `Str.title()`

Değeri `Başlık Büyük Harfine` dönüştürür:

``` dart
Str.title('a nice title'); // 'A Nice Title'
```

<div id="method-str-ucfirst"></div>

#### `Str.ucfirst()`

Değerin ilk karakterini büyük harfe dönüştürür:

``` dart
Str.ucfirst('hello world'); // 'Hello world'
```

<div id="method-str-ucsplit"></div>

#### `Str.ucsplit()`

Değeri büyük harf sınırlarına göre kelimelere böler:

``` dart
Str.ucsplit('fooBarBaz'); // ['foo', 'Bar', 'Baz']
```

<div id="method-str-ulid"></div>

#### `Str.ulid()`

Bir ULID (Evrensel Olarak Benzersiz Sözlüksel Sıralanabilir Tanımlayıcı) oluşturur:

``` dart
Str.ulid(); // örn. '01ARZ3NDEKTSV4RRFFQ69G5FAV'
```

<div id="method-str-unwrap"></div>

#### `Str.unwrap()`

Baştan `before`'un tek bir örneğini ve sondan `after`'ın (ya da `after` null ise `before`'un) tek bir örneğini kaldırır:

``` dart
Str.unwrap('"value"', '"');             // 'value'
Str.unwrap('<p>html</p>', '<p>', '</p>'); // 'html'
```

<div id="method-str-upper"></div>

#### `Str.upper()`

Değeri büyük harfe dönüştürür:

``` dart
Str.upper('hello'); // 'HELLO'
```

<div id="method-str-uuid"></div>

#### `Str.uuid()`

Rastgele bir UUID v4 oluşturur:

``` dart
Str.uuid(); // örn. 'a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11'
```

<div id="method-str-uuid7"></div>

#### `Str.uuid7()`

Bir UUID v7 (zamana göre sıralı, RFC 9562) oluşturur. UUID v7'ler oluşturulma zamanına göre sıralanabilir:

``` dart
Str.uuid7(); // örn. '018e0dc4-8b3f-7a2c-9d01-b1f6c87a92e3'
```

<div id="method-str-wordcount"></div>

#### `Str.wordCount()`

Değerin kelime sayısını döndürür:

``` dart
Str.wordCount('hello there friend'); // 3
```

<div id="method-str-words"></div>

#### `Str.words()`

Değeri verilen kelime sayısıyla sınırlar:

``` dart
Str.words('Perfectly balanced, as all things should be.', 3);
// 'Perfectly balanced, as...'
```

<div id="method-str-wrap"></div>

#### `Str.wrap()`

Değeri `before` ve `after` ile sarar. `after` belirtilmezse her iki tarafta da `before` kullanılır:

``` dart
Str.wrap('value', '"');             // '"value"'
Str.wrap('html', '<p>', '</p>');    // '<p>html</p>'
```

<div id="numbers"></div>

## Sayılar

<div id="method-number-abbreviate"></div>

#### `Number.abbreviate()`

Sayının kısaltılmış biçimini döndürür (`1K`, `1M`, `1B`):

``` dart
Number.abbreviate(1500);                  // '2K'
Number.abbreviate(1500, maxPrecision: 1); // '1.5K'
Number.abbreviate(2_500_000);             // '3M'
```

<div id="method-number-average"></div>

#### `Number.average()`

Değerlerin aritmetik ortalamasını döndürür; boşsa `0` döner:

``` dart
Number.average([1, 2, 3, 4]); // 2.5
```

<div id="method-number-between"></div>

#### `Number.between()`

Değer `min` ile `max` arasındaysa (sınırlar dahil) `true` döndürür:

``` dart
Number.between(5, min: 0, max: 10);  // true
Number.between(15, min: 0, max: 10); // false
```

<div id="method-number-ceil"></div>

#### `Number.ceil()`

Değeri verilen hassasiyete tavan yapar:

``` dart
Number.ceil(1.2);    // 2.0
Number.ceil(1.234, 2); // 1.24
```

<div id="method-number-clamp"></div>

#### `Number.clamp()`

Bir değeri `min` ile `max` arasında (sınırlar dahil) sıkıştırır:

``` dart
Number.clamp(5, 0, 10);   // 5
Number.clamp(15, 0, 10);  // 10
Number.clamp(-1, 0, 10);  // 0
```

<div id="method-number-currency"></div>

#### `Number.currency()`

Sayıyı para birimi değeri olarak biçimlendirir. Yapılandırılmış yerel ayar ve para birimini varsayılan olarak kullanır:

``` dart
Number.currency(1234.56);                            // '$1,234.56'
Number.currency(1234.56, currency: 'EUR');           // '€1,234.56'
Number.currency(1234.56, locale: 'de_DE', currency: 'EUR');
// '1.234,56 €'
```

<div id="method-number-defaultcurrency"></div>

#### `Number.defaultCurrency()`

Biçimlendirme metotlarının kullandığı varsayılan para birimi kodunu döndürür:

``` dart
Number.defaultCurrency(); // 'USD'
```

<div id="method-number-defaultlocale"></div>

#### `Number.defaultLocale()`

Biçimlendirme metotlarının kullandığı varsayılan yerel ayarı döndürür:

``` dart
Number.defaultLocale(); // 'en_US'
```

<div id="method-number-degrees"></div>

#### `Number.degrees()`

Radyanı dereceye dönüştürür:

``` dart
Number.degrees(3.14159);  // ~180.0
```

<div id="method-number-duration"></div>

#### `Number.duration()`

Saniye cinsinden bir sayıyı insan tarafından okunabilir süreye biçimlendirir. Kısa biçim `'1h 2m 5s'` döndürür; uzun biçim `'1:02:05'` döndürür:

``` dart
Number.duration(3661);                  // '1h 1m 1s'
Number.duration(3661, short: false);    // '1:01:01'
Number.duration(45);                    // '45s'
```

<div id="method-number-filesize"></div>

#### `Number.fileSize()`

Bayt sayısını insan tarafından okunabilir dosya boyutuna biçimlendirir:

``` dart
Number.fileSize(1024);                       // '1 KB'
Number.fileSize(1500, maxPrecision: 1);      // '1.5 KB'
Number.fileSize(1024 * 1024 * 1024);         // '1 GB'
```

<div id="method-number-floor"></div>

#### `Number.floor()`

Değeri verilen hassasiyete taban yapar:

``` dart
Number.floor(1.7);      // 1.0
Number.floor(1.234, 2); // 1.23
```

<div id="method-number-forhumans"></div>

#### `Number.forHumans()`

Sayının insan tarafından okunabilir biçimini döndürür (`1 thousand`, `1 million`):

``` dart
Number.forHumans(1500);                  // '2 thousand'
Number.forHumans(1500, maxPrecision: 1); // '1.5 thousand'
Number.forHumans(2_500_000);             // '3 million'
```

<div id="method-number-format"></div>

#### `Number.format()`

Sayıyı yerel ayara duyarlı ayırıcılarla biçimlendirir:

``` dart
Number.format(1234567);                          // '1,234,567'
Number.format(1234.5, precision: 2);             // '1,234.50'
Number.format(1234567, locale: 'de_DE');         // '1.234.567'
```

<div id="method-number-gcd"></div>

#### `Number.gcd()`

İki tam sayının en büyük ortak böleni:

``` dart
Number.gcd(12, 18); // 6
```

<div id="method-number-lcm"></div>

#### `Number.lcm()`

İki tam sayının en küçük ortak katı:

``` dart
Number.lcm(4, 6); // 12
```

<div id="method-number-lerp"></div>

#### `Number.lerp()`

`a` ile `b` arasında `t` (`0..1`) ile doğrusal enterpolasyon yapar:

``` dart
Number.lerp(0, 100, 0.25); // 25.0
```

<div id="method-number-max"></div>

#### `Number.max()`

Yinelenebilirdeki en büyük değeri döndürür:

``` dart
Number.max([3, 1, 4, 1, 5, 9, 2, 6]); // 9
```

<div id="method-number-median"></div>

#### `Number.median()`

Değerlerin medyanını döndürür; boşsa `0` döner:

``` dart
Number.median([1, 3, 5]);    // 3.0
Number.median([1, 2, 3, 4]); // 2.5
```

<div id="method-number-min"></div>

#### `Number.min()`

Yinelenebilirdeki en küçük değeri döndürür:

``` dart
Number.min([3, 1, 4, 1, 5, 9, 2, 6]); // 1
```

<div id="method-number-ordinal"></div>

#### `Number.ordinal()`

Sayının sıralı biçimini döndürür (`1st`, `2nd`, `3rd`, `4th`):

``` dart
Number.ordinal(1);   // '1st'
Number.ordinal(2);   // '2nd'
Number.ordinal(21);  // '21st'
Number.ordinal(112); // '112th'
```

<div id="method-number-pairs"></div>

#### `Number.pairs()`

`to`'ya kadar olan aralıktan `by` adımıyla çiftler (alt aralıklar) oluşturur:

``` dart
Number.pairs(25, 10);             // [[0, 9], [10, 19], [20, 25]]
Number.pairs(25, 10, offset: 0);  // [[0, 10], [10, 20], [20, 25]]
```

<div id="method-number-parsefloat"></div>

#### `Number.parseFloat()`

Yerel ayara duyarlı bir dizeyi `double` olarak ayrıştırır. Başarısızlık durumunda `null` döndürür:

``` dart
Number.parseFloat('1,234.56');                    // 1234.56
Number.parseFloat('1.234,56', locale: 'de_DE');   // 1234.56
Number.parseFloat('not a number');                // null
```

<div id="method-number-parseint"></div>

#### `Number.parseInt()`

Yerel ayara duyarlı bir dizeyi `int` olarak ayrıştırır. Başarısızlık durumunda `null` döndürür:

``` dart
Number.parseInt('1,234');                    // 1234
Number.parseInt('1.234', locale: 'de_DE');   // 1234
```

<div id="method-number-percentage"></div>

#### `Number.percentage()`

Sayıyı yüzde olarak biçimlendirir. Sayı tam yüzde değeri olarak işlenir:

``` dart
Number.percentage(10);                  // '10%'
Number.percentage(10.5, precision: 1);  // '10.5%'
```

<div id="method-number-radians"></div>

#### `Number.radians()`

Dereceyi radyana dönüştürür:

``` dart
Number.radians(180); // ~3.14159
```

<div id="method-number-random"></div>

#### `Number.random()`

`[min, max]` aralığında (sınırlar dahil) rastgele bir tam sayı döndürür. Deterministik bir dizi için `seed` geçin:

``` dart
Number.random(min: 1, max: 100);              // 1..100 arasında herhangi bir tam sayı
Number.random(min: 0, max: 9, seed: 42);      // deterministik
```

<div id="method-number-range"></div>

#### `Number.range()`

`start` (dahil) ile `end` (dahil) arasında `step` adımıyla tam sayı listesi oluşturur:

``` dart
Number.range(1, 5);              // [1, 2, 3, 4, 5]
Number.range(0, 10, step: 2);    // [0, 2, 4, 6, 8, 10]
Number.range(5, 1, step: -1);    // [5, 4, 3, 2, 1]
```

<div id="method-number-round"></div>

#### `Number.round()`

Değeri verilen hassasiyete yuvarlar:

``` dart
Number.round(1.5);      // 2.0
Number.round(1.234, 2); // 1.23
```

<div id="method-number-scale"></div>

#### `Number.scale()`

Bir değeri bir aralıktan diğerine yeniden eşler:

``` dart
Number.scale(0.5, fromMin: 0, fromMax: 1, toMin: 0, toMax: 100); // 50.0
Number.scale(75, fromMin: 0, fromMax: 100, toMin: -1, toMax: 1); // 0.5
```

<div id="method-number-spell"></div>

#### `Number.spell()`

Sayıyı İngilizce olarak yazar:

``` dart
Number.spell(0);           // 'zero'
Number.spell(123);         // 'one hundred twenty-three'
Number.spell(1_234_567);   // 'one million two hundred thirty-four thousand five hundred sixty-seven'
```

<div id="method-number-spellordinal"></div>

#### `Number.spellOrdinal()`

Sayının sıralı biçimini İngilizce olarak yazar:

``` dart
Number.spellOrdinal(1);   // 'first'
Number.spellOrdinal(2);   // 'second'
Number.spellOrdinal(21);  // 'twenty-first'
```

<div id="method-number-sum"></div>

#### `Number.sum()`

Değerlerin toplamını döndürür:

``` dart
Number.sum([1, 2, 3]); // 6
```

<div id="method-number-tobytes"></div>

#### `Number.toBytes()`

Dosya boyutunu bayta dönüştürür. [`fileSize`](#method-number-filesize) metodunun tersidir. Ayrıştırılabilir bir dize ya da `unit` ile birlikte bir sayı geçin:

``` dart
Number.toBytes('1.5 KB');     // 1536
Number.toBytes(1.5, 'KB');    // 1536
Number.toBytes(1, 'GB');      // 1073741824
Number.toBytes('not bytes');  // null
```

<div id="method-number-trim"></div>

#### `Number.trim()`

Değerin ondalık kısmındaki sondaki sıfırları kaldırır:

``` dart
Number.trim(12.0);    // '12'
Number.trim(12.30);   // '12.3'
Number.trim(12.345);  // '12.345'
```

<div id="method-number-usecurrency"></div>

#### `Number.useCurrency()`

Biçimlendirme metotlarının kullandığı varsayılan para birimi kodunu ayarlar:

``` dart
Number.useCurrency('EUR');
Number.currency(99); // '€99.00'
```

<div id="method-number-uselocale"></div>

#### `Number.useLocale()`

Biçimlendirme metotlarının kullandığı varsayılan yerel ayarı ayarlar:

``` dart
Number.useLocale('de_DE');
Number.format(1234567); // '1.234.567'
```

<div id="objects"></div>

## Nesneler

`Obj`, **nokta notasyonu** yolları kullanarak haritalarla çalışmak için yardımcı programlar sağlar. Liste elemanlarına tam sayı indeksiyle erişilebilir (örn. `'users.0.name'`).

<div id="method-obj-add"></div>

#### `Obj.add()`

`key` konumunda henüz bir değer yoksa oraya değer ekler:

``` dart
final user = {'name': 'Anna'};
Obj.add(user, 'role', 'admin');
// user: {'name': 'Anna', 'role': 'admin'}

Obj.add(user, 'name', 'Brad'); // işlem yok — name zaten ayarlı
```

<div id="method-obj-deepequals"></div>

#### `Obj.deepEquals()`

İki değer yapısal olarak eşitse (özyinelemeli `Map` ve `List` karşılaştırması) `true` döndürür:

``` dart
Obj.deepEquals({'a': [1, 2]}, {'a': [1, 2]}); // true
Obj.deepEquals({'a': 1}, {'a': 2});            // false
```

<div id="method-obj-divide"></div>

#### `Obj.divide()`

Bir haritayı `[anahtarlar, değerler]` çiftine böler:

``` dart
Obj.divide({'name': 'Desk', 'price': 100});
// [['name', 'price'], ['Desk', 100]]
```

<div id="method-obj-dot"></div>

#### `Obj.dot()`

İç içe geçmiş bir haritayı nokta yollarıyla anahtarlanmış tek düzeyli bir haritaya düzleştirir:

``` dart
Obj.dot({'a': {'b': 1, 'c': 2}});
// {'a.b': 1, 'a.c': 2}
```

<div id="method-obj-except"></div>

#### `Obj.except()`

Verilen anahtarlara sahip girişler dışındaki tüm girişleri içeren yeni bir harita döndürür:

``` dart
Obj.except({'name': 'Anna', 'role': 'admin', 'age': 30}, ['age']);
// {'name': 'Anna', 'role': 'admin'}
```

<div id="method-obj-exists"></div>

#### `Obj.exists()`

Haritada üst düzeyde verilen anahtar mevcut olduğunda (nokta notasyonu geçişi olmaksızın) `true` döndürür:

``` dart
Obj.exists({'a.b': 1}, 'a.b'); // true
Obj.exists({'a': {'b': 1}}, 'a.b'); // false (geçiş için `has` kullanın)
```

<div id="method-obj-flip"></div>

#### `Obj.flip()`

Anahtarları ve değerleri yer değiştirir. Değerler çakıştığında son giriş kazanır:

``` dart
Obj.flip({'one': 1, 'two': 2}); // {1: 'one', 2: 'two'}
```

<div id="method-obj-forget"></div>

#### `Obj.forget()`

Haritadan bir anahtarı (nokta notasyonu) kaldırır. Zincirleme için haritayı döndürür:

``` dart
final user = {'profile': {'name': 'Anna', 'email': 'a@b'}};
Obj.forget(user, 'profile.email');
// user: {'profile': {'name': 'Anna'}}
```

<div id="method-obj-get"></div>

#### `Obj.get()`

Nokta notasyonu kullanarak `key` konumundaki değeri döndürür; yol eksikse varsayılan değeri döndürür. Değişmez nokta içeren üst düzey anahtarlar geçişe göre önceliklidir:

``` dart
final user = {'profile': {'name': 'Anna', 'age': 30}};
Obj.get(user, 'profile.name');     // 'Anna'
Obj.get(user, 'profile.email');    // null
Obj.get(user, 'profile.email', 'unknown'); // 'unknown'

// Liste indeksleri:
Obj.get({'users': [{'name': 'A'}]}, 'users.0.name'); // 'A'
```

<div id="method-obj-getbool"></div>

#### `Obj.getBool()`

`key` konumundaki değeri `bool`'a dönüştürerek döndürür; yoksa varsayılan değeri döndürür. Sayılar sıfır dışındaysa truthy'dir; dizeler `'true'`/`'false'`/`'1'`/`'0'`/`'yes'`/`'no'`/`'on'`/`'off'`'ı tanır (büyük/küçük harf duyarsız):

``` dart
Obj.getBool({'flag': 'yes'}, 'flag');     // true
Obj.getBool({'flag': 0}, 'flag');         // false
Obj.getBool({}, 'flag', false);           // false
```

<div id="method-obj-getdouble"></div>

#### `Obj.getDouble()`

`key` konumundaki değeri `double`'a dönüştürerek döndürür; yoksa varsayılan değeri döndürür:

``` dart
Obj.getDouble({'price': '9.99'}, 'price'); // 9.99
Obj.getDouble({'price': 10}, 'price');     // 10.0
```

<div id="method-obj-getint"></div>

#### `Obj.getInt()`

`key` konumundaki değeri `int`'e dönüştürerek döndürür; yoksa varsayılan değeri döndürür:

``` dart
Obj.getInt({'count': '42'}, 'count');     // 42
Obj.getInt({'count': 3.7}, 'count');      // 3 (kesilerek)
Obj.getInt({}, 'count', 0);               // 0
```

<div id="method-obj-getlist"></div>

#### `Obj.getList()`

`key` konumundaki değer bir `List` ise onu döndürür; aksi takdirde varsayılan değeri döndürür:

``` dart
Obj.getList({'items': [1, 2, 3]}, 'items'); // [1, 2, 3]
Obj.getList({'items': 'oops'}, 'items', []); // []
```

<div id="method-obj-getmap"></div>

#### `Obj.getMap()`

`key` konumundaki değer bir `Map` ise onu döndürür; aksi takdirde varsayılan değeri döndürür:

``` dart
Obj.getMap({'meta': {'a': 1}}, 'meta'); // {'a': 1}
Obj.getMap({'meta': null}, 'meta', {}); // {}
```

<div id="method-obj-getstring"></div>

#### `Obj.getString()`

`key` konumundaki değeri `String`'e dönüştürerek döndürür; yoksa varsayılan değeri döndürür:

``` dart
Obj.getString({'name': 'Anna'}, 'name'); // 'Anna'
Obj.getString({'count': 42}, 'count');   // '42'
Obj.getString({}, 'name', 'Guest');      // 'Guest'
```

<div id="method-obj-has"></div>

#### `Obj.has()`

Nokta notasyonu kullanarak haritanın `key` konumunda bir değere sahip olduğunda `true` döndürür:

``` dart
final user = {'profile': {'name': 'Anna'}};
Obj.has(user, 'profile.name');  // true
Obj.has(user, 'profile.email'); // false
```

<div id="method-obj-hasall"></div>

#### `Obj.hasAll()`

Verilen anahtarların her biri haritada çözümlendiğinde `true` döndürür:

``` dart
Obj.hasAll({'a': 1, 'b': 2}, ['a', 'b']);      // true
Obj.hasAll({'a': 1, 'b': 2}, ['a', 'c']);      // false
```

<div id="method-obj-hasany"></div>

#### `Obj.hasAny()`

Verilen anahtarlardan herhangi biri haritada çözümlendiğinde `true` döndürür:

``` dart
Obj.hasAny({'a': 1}, ['a', 'b']); // true
Obj.hasAny({'a': 1}, ['x', 'y']); // false
```

<div id="method-obj-mapkeys"></div>

#### `Obj.mapKeys()`

Her anahtarın `fn` tarafından dönüştürüldüğü yeni bir harita döndürür:

``` dart
Obj.mapKeys({'firstName': 'Anna'}, (k) => k.toLowerCase());
// {'firstname': 'Anna'}
```

<div id="method-obj-mapvalues"></div>

#### `Obj.mapValues()`

Her değerin `fn` tarafından dönüştürüldüğü yeni bir harita döndürür:

``` dart
Obj.mapValues({'a': 1, 'b': 2}, (v) => v * 10);
// {'a': 10, 'b': 20}
```

<div id="method-obj-merge"></div>

#### `Obj.merge()`

`source`'u `target` ile özyinelemeli olarak birleştirir. Anahtar çakışmasında `source` kazanır; iç içe geçmiş haritalar birleştirilir:

``` dart
Obj.merge(
  {'a': {'x': 1}, 'b': 2},
  {'a': {'y': 9}, 'b': 22},
);
// {'a': {'x': 1, 'y': 9}, 'b': 22}
```

<div id="method-obj-only"></div>

#### `Obj.only()`

Yalnızca verilen anahtarlara sahip girişleri içeren yeni bir harita döndürür:

``` dart
Obj.only({'name': 'Anna', 'role': 'admin', 'age': 30}, ['name', 'role']);
// {'name': 'Anna', 'role': 'admin'}
```

<div id="method-obj-prependkeyswith"></div>

#### `Obj.prependKeysWith()`

Haritadaki her üst düzey anahtarın önüne verilen öneki ekler:

``` dart
Obj.prependKeysWith({'name': 'Anna', 'age': 30}, 'user_');
// {'user_name': 'Anna', 'user_age': 30}
```

<div id="method-obj-pull"></div>

#### `Obj.pull()`

`key` konumundaki değeri döndürür ve haritadan kaldırır:

``` dart
final user = {'name': 'Anna', 'temp': 'token'};
final temp = Obj.pull(user, 'temp'); // 'token'
// user: {'name': 'Anna'}
```

<div id="method-obj-query"></div>

#### `Obj.query()`

Bir haritayı URL sorgu dizesi olarak kodlar:

``` dart
Obj.query({'name': 'Anna', 'tags': ['a', 'b']});
// 'name=Anna&tags%5B0%5D=a&tags%5B1%5D=b'

Obj.query({'filter': {'role': 'admin'}});
// 'filter%5Brole%5D=admin'
```

<div id="method-obj-set"></div>

#### `Obj.set()`

`key` konumuna değer ayarlar; yol boyunca iç içe geçmiş haritalar oluşturur. Haritayı yerinde değiştirir ve zincirleme için döndürür:

``` dart
final user = <String, dynamic>{};
Obj.set(user, 'profile.email', 'a@b');
// user: {'profile': {'email': 'a@b'}}
```

<div id="method-obj-undot"></div>

#### `Obj.undot()`

Nokta anahtarlı bir haritayı iç içe geçmiş bir yapıya geri şişirir ([`dot`](#method-obj-dot) metodunun tersi):

``` dart
Obj.undot({'a.b': 1, 'a.c': 2});
// {'a': {'b': 1, 'c': 2}}
```

<div id="method-obj-wherenotempty"></div>

#### `Obj.whereNotEmpty()`

Değeri `null` veya boş (boş `String`, `Iterable` veya `Map`) olan girişlerin kaldırıldığı yeni bir harita döndürür:

``` dart
Obj.whereNotEmpty({'name': 'Anna', 'tags': [], 'bio': ''});
// {'name': 'Anna'}
```

<div id="method-obj-wherenotnull"></div>

#### `Obj.whereNotNull()`

Değeri `null` olan girişlerin kaldırıldığı yeni bir harita döndürür:

``` dart
Obj.whereNotNull({'name': 'Anna', 'email': null});
// {'name': 'Anna'}
```

<div id="miscellaneous"></div>

## Çeşitli

Bu global fonksiyonlar `helper.dart` dosyasında bulunur ve `nylo_framework`'ü içe aktardığınız her yerde kullanılabilir.

<div id="method-api"></div>

#### `api()`

Bir `NyApiService` üzerinden API istekleri göndermek için kolaylık yardımcısı. Tam seçenekler için [Ağ İletişimi](/docs/{{ $version }}/networking) sayfasına bakın:

``` dart
await api<ApiService>((request) =>
  request.get('https://jsonplaceholder.typicode.com/posts'));
```

<div id="method-datatomodel"></div>

#### `dataToModel()`

Ham veriyi (genellikle bir JSON `Map`) `config/decoders.dart`'a kayıtlı bir modele dönüştürür. Tür parametresi zorunludur:

``` dart
final user = dataToModel<User>(data: {'name': 'Anna', 'age': 30});
```

<div id="method-dump"></div>

#### `dump()`

Konsola bir değer döker. `alwaysPrint: true` belirtilmediği sürece uygulamanın `APP_DEBUG` bayrağına uyar:

``` dart
dump('Hello World');
dump(user, tag: 'current user');
dump(payload, alwaysPrint: true);
```

<div id="method-event"></div>

#### `event()`

`Nylo.events()` kullanarak verilen türde bir olay tetikler. Tam kullanım için [Olaylar](/docs/{{ $version }}/events) sayfasına bakın:

``` dart
await event<LoginEvent>(data: {'user': 'Anna'});
```

<div id="method-getasset"></div>

#### `getAsset()`

`/assets` dizinindeki bir varlık için tam yolu döndürür:

``` dart
final path = getAsset('videos/welcome.mp4');
// 'assets/videos/welcome.mp4'
```

<div id="method-getenv"></div>

#### `getEnv()`

Oluşturulan env dosyanızdan (`env.g.dart`) bir değer döndürür. Anahtar ayarlanmamışsa varsayılanı döndürür:

``` dart
final apiUrl = getEnv('API_URL');
final debug = getEnv('APP_DEBUG', defaultValue: false);
```

<div id="method-getimageasset"></div>

#### `getImageAsset()`

`/assets/images/` dizinindeki bir görsel için tam yolu döndürür:

``` dart
Image.asset(getImageAsset('logo.png'));
```

<div id="method-loadjson"></div>

#### `loadJson()`

Varlık paketinizden bir JSON dosyası yükler. Sonuçlar varsayılan olarak önbelleğe alınır:

``` dart
final config = await loadJson<Map<String, dynamic>>('config/app.json');
```

<div id="method-match"></div>

#### `match()`

Bir değeri `Map` durumlarıyla eşleştirir ve karşılık gelen değeri döndürür. Hiçbir durum eşleşmezse ve `defaultValue` sağlanmamışsa hata fırlatır:

``` dart
final color = match<String>(
  status,
  () => {
    'active':   'green',
    'inactive': 'red',
    'pending':  'orange',
  },
  defaultValue: 'grey',
);
```

> **Not:** Bu, `Map` tabanlı bir değer aramasıdır — regex eşleştirmesi yapan [`Str.match`](#method-str-match)'ten farklıdır.

<div id="method-now"></div>

#### `now()`

Geçerli `DateTime`'ı döndürür:

``` dart
final timestamp = now();
```

<div id="method-nyhexcolor"></div>

#### `nyHexColor()`

Bir onaltılık renk dizesini `Color`'a dönüştürür. Baştaki `#` ile veya onsuz 6 veya 8 haneli değerleri kabul eder:

``` dart
final brand = nyHexColor('#FF6B35');
final translucent = nyHexColor('80FF6B35'); // 8 haneli (önce alfa)
```

<div id="method-print-helpers"></div>

#### `printDebug()`, `printError()`, `printInfo()`, `printWarning()`, `printSuccess()`, `printVerbose()`, `printAlert()`, `printEmergency()`

Önem derecesiyle etiketlenmiş konsol günlükçüleri. Çıktı biçimlendirmesi ve `alwaysPrint` bayrağı için [Günlükleme](/docs/{{ $version }}/logging) sayfasına bakın:

``` dart
printInfo('User signed in');
printError('Payment failed', context: {'order_id': 42});
printDebug({'state': state});
```

<div id="method-badge-helpers"></div>

#### `setBadgeNumber()`, `clearBadgeNumber()`

Uygulama simgesi rozet sayısını ayarlar veya temizler. Platform kurulum ayrıntıları için [Yerel Bildirimler](/docs/{{ $version }}/local-notifications) sayfasına bakın:

``` dart
await setBadgeNumber(3);
await clearBadgeNumber();
```

<div id="method-shownextlog"></div>

#### `showNextLog()`

`APP_DEBUG` `false` olsa bile bir sonraki `NyLogger` günlüğünün görüntülenmesini zorlar. Yayın derlemelerinde tek seferlik tanılamalar için kullanışlıdır:

``` dart
showNextLog();
NyLogger.info('This will print regardless of APP_DEBUG');
```

<div id="method-sleep"></div>

#### `sleep()`

Yürütmeyi verilen süre boyunca geciktirir. İlk argüman saniyedir; isteğe bağlı ikinci argüman mikrosaniye ekler:

``` dart
await sleep(2);              // 2 saniye
await sleep(0, 500_000);     // 500 milisaniye
```

<div id="method-trans"></div>

#### `trans()`

Anahtara göre çevrilmiş bir dize döndürür. Kurulum için [Yerelleştirme](/docs/{{ $version }}/localization) sayfasına bakın:

``` dart
final greeting = trans('home.welcome');
final personalized = trans('home.hello', arguments: {'name': 'Anna'});
```
