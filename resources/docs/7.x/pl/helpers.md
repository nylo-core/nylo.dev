# Funkcje pomocnicze

<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie")
- [Dostępne metody](#available-methods "Dostępne metody")
  - [Tablice](#arrays-method-list "Tablice")
  - [Ciągi znaków](#strings-method-list "Ciągi znaków")
  - [Liczby](#numbers-method-list "Liczby")
  - [Obiekty](#objects-method-list "Obiekty")
  - [Różne](#miscellaneous-method-list "Różne")
- [Tablice](#arrays "Tablice")
- [Ciągi znaków](#strings "Ciągi znaków")
- [Liczby](#numbers "Liczby")
- [Obiekty](#objects "Obiekty")
- [Różne](#miscellaneous "Różne")

<div id="introduction"></div>

## Wprowadzenie

{{ config('app.name') }} dostarcza zestaw statycznych klas narzędziowych — `Arr`, `Str`, `Number` i `Obj` — oraz kilka globalnych funkcji pomocniczych do typowych zadań. Wszystkie są eksportowane z `nylo_framework`:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

Arr.first([1, 2, 3]);             // 1
Str.slug('Hello World');          // 'hello-world'
Number.currency(1234.56);         // '$1,234.56'
Obj.get(user, 'profile.name');    // 'Anna'
```

> **Uwaga:** `Backpack`, `NyStorage`, `NyCache` i `NyLogger` mają własne dedykowane strony: [Backpack](/docs/{{ $version }}/backpack), [Storage](/docs/{{ $version }}/storage), [Cache](/docs/{{ $version }}/cache), [Logging](/docs/{{ $version }}/logging).

<div id="available-methods"></div>

## Dostępne metody

<div id="arrays-method-list"></div>

### Tablice

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

### Ciągi znaków

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

### Liczby

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

### Obiekty

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

### Różne

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

## Tablice

<div id="method-arr-accessible"></div>

#### `Arr.accessible()`

Sprawdza, czy podana wartość jest dostępna jak tablica (jest `List` lub `Map`):

``` dart
Arr.accessible([1, 2, 3]);  // true
Arr.accessible({'a': 1});   // true
Arr.accessible('hello');    // false
Arr.accessible(null);       // false
```

<div id="method-arr-average"></div>

#### `Arr.average()`

Zwraca średnią arytmetyczną listy lub `0` gdy jest pusta. Podaj `by`, aby wyodrębnić liczbę z każdego elementu:

``` dart
Arr.average([2, 4, 6]);                // 4.0
Arr.average(orders, by: (o) => o.total); // średnia sumy zamówień
```

<div id="method-arr-chunk"></div>

#### `Arr.chunk()`

Dzieli listę na fragmenty o podanym rozmiarze:

``` dart
Arr.chunk([1, 2, 3, 4, 5], 2);
// [[1, 2], [3, 4], [5]]
```

<div id="method-arr-collapse"></div>

#### `Arr.collapse()`

Spłaszcza listę list do pojedynczej listy:

``` dart
Arr.collapse([[1, 2], [3, 4], [5]]);
// [1, 2, 3, 4, 5]
```

<div id="method-arr-countby"></div>

#### `Arr.countBy()`

Zlicza wystąpienia w liście, opcjonalnie grupując według ekstraktora wartości:

``` dart
Arr.countBy(['a', 'b', 'a', 'c']);
// {'a': 2, 'b': 1, 'c': 1}

Arr.countBy(orders, by: (o) => o.status);
// {'paid': 12, 'pending': 3}
```

<div id="method-arr-crossjoin"></div>

#### `Arr.crossJoin()`

Wykonuje iloczyn kartezjański podanych list, zwracając każdą możliwą kombinację:

``` dart
Arr.crossJoin([[1, 2], ['a', 'b']]);
// [[1, 'a'], [1, 'b'], [2, 'a'], [2, 'b']]
```

<div id="method-arr-every"></div>

#### `Arr.every()`

Zwraca `true`, gdy każdy element listy spełnia predykat. Dla pustych list zawsze prawdziwe:

``` dart
Arr.every([2, 4, 6], (n) => n.isEven); // true
Arr.every([1, 2, 3], (n) => n.isEven); // false
```

<div id="method-arr-exceptvalues"></div>

#### `Arr.exceptValues()`

Zwraca listę z usuniętymi podanymi wartościami:

``` dart
Arr.exceptValues([1, 2, 3, 4], [2, 4]); // [1, 3]
```

<div id="method-arr-first"></div>

#### `Arr.first()`

Zwraca pierwszy element spełniający predykat lub wartość domyślną:

``` dart
Arr.first([1, 2, 3]);                                   // 1
Arr.first([1, 2, 3, 4], predicate: (n) => n.isEven);    // 2
Arr.first([], defaultValue: 99);                        // 99
```

<div id="method-arr-flatmap"></div>

#### `Arr.flatMap()`

Mapuje każdy element przez `fn` i spłaszcza wynikowe iterowalne obiekty:

``` dart
Arr.flatMap(pages, (p) => p.items); // wszystkie elementy ze wszystkich stron
Arr.flatMap([1, 2, 3], (n) => [n, n * 10]); // [1, 10, 2, 20, 3, 30]
```

<div id="method-arr-flatten"></div>

#### `Arr.flatten()`

Spłaszcza zagnieżdżony iterowalny obiekt do listy jednopoziomowej. `depth` ogranicza głębokość spłaszczania; `-1` oznacza nieograniczone:

``` dart
Arr.flatten([1, [2, [3]]]);              // [1, 2, 3]
Arr.flatten([1, [2, [3]]], depth: 1);    // [1, 2, [3]]
```

<div id="method-arr-groupby"></div>

#### `Arr.groupBy()`

Grupuje listę w mapę indeksowaną wartością zwróconą przez `by`:

``` dart
Arr.groupBy(messages, (m) => m.date);
// {2024-01-01: [...], 2024-01-02: [...]}
```

<div id="method-arr-indexed"></div>

#### `Arr.indexed()`

Łączy każdy element z jego indeksem, zwracając listę rekordów `(index, value)`:

``` dart
for (final (i, v) in Arr.indexed(['a', 'b', 'c'])) {
  print('$i: $v'); // '0: a', '1: b', '2: c'
}
```

<div id="method-arr-interleave"></div>

#### `Arr.interleave()`

Wstawia separator między każdą parą elementów. Przydatne do budowania dzieci widgetów z separatorami:

``` dart
Arr.interleave([1, 2, 3], 0); // [1, 0, 2, 0, 3]

Column(children: Arr.interleave(tiles, const Divider()));
```

<div id="method-arr-isassoc"></div>

#### `Arr.isAssoc()`

Zwraca `true`, gdy wartość jest `Map` (asocjacyjna):

``` dart
Arr.isAssoc({'a': 1});  // true
Arr.isAssoc([1, 2, 3]); // false
```

<div id="method-arr-islist"></div>

#### `Arr.isList()`

Zwraca `true`, gdy wartość jest `List`:

``` dart
Arr.isList([1, 2, 3]); // true
Arr.isList({'a': 1});  // false
```

<div id="method-arr-join"></div>

#### `Arr.join()`

Łączy listę w ciąg znaków. Ostatni element można połączyć innym separatorem:

``` dart
Arr.join(['Anna', 'Brad', 'Carol'], ', ', ' and ');
// 'Anna, Brad and Carol'

Arr.join(['a', 'b', 'c']);
// 'a, b, c'
```

<div id="method-arr-keyby"></div>

#### `Arr.keyBy()`

Indeksuje listę według wartości pod danym kluczem. Wpisy z wartością `null` pod tym kluczem są pomijane:

``` dart
Arr.keyBy([
  {'id': 1, 'name': 'Anna'},
  {'id': 2, 'name': 'Brad'},
], 'id');
// {1: {'id': 1, 'name': 'Anna'}, 2: {'id': 2, 'name': 'Brad'}}
```

<div id="method-arr-last"></div>

#### `Arr.last()`

Zwraca ostatni element spełniający predykat lub wartość domyślną:

``` dart
Arr.last([1, 2, 3, 4], predicate: (n) => n.isEven); // 4
Arr.last([1, 2, 3]);                                 // 3
```

<div id="method-arr-map"></div>

#### `Arr.map()`

Mapuje każdy element przez callback, który otrzymuje też indeks:

``` dart
Arr.map(['a', 'b'], (v, i) => '$i:$v'); // ['0:a', '1:b']
```

<div id="method-arr-mapwithkeys"></div>

#### `Arr.mapWithKeys()`

Mapuje każdy element na `MapEntry` i zwraca wynikową mapę:

``` dart
Arr.mapWithKeys(
  [{'id': 1, 'name': 'Anna'}, {'id': 2, 'name': 'Brad'}],
  (m) => MapEntry(m['id'], m['name']),
);
// {1: 'Anna', 2: 'Brad'}
```

<div id="method-arr-max"></div>

#### `Arr.max()`

Zwraca element z największą wartością `by` lub największy element, gdy `by` jest pominięte:

``` dart
Arr.max([3, 1, 4, 1, 5]);            // 5
Arr.max(users, by: (u) => u.age);    // użytkownik z najwyższym wiekiem
```

<div id="method-arr-median"></div>

#### `Arr.median()`

Zwraca medianę listy lub `0` gdy jest pusta:

``` dart
Arr.median([1, 3, 5]);    // 3.0
Arr.median([1, 2, 3, 4]); // 2.5
```

<div id="method-arr-min"></div>

#### `Arr.min()`

Zwraca element z najmniejszą wartością `by` lub najmniejszy element, gdy `by` jest pominięte:

``` dart
Arr.min([3, 1, 4, 1, 5]);            // 1
Arr.min(users, by: (u) => u.age);    // użytkownik z najniższym wiekiem
```

<div id="method-arr-move"></div>

#### `Arr.move()`

Zwraca nową listę z elementem z pozycji `from` przesuniętym na pozycję `to`. `to` jest ograniczane do prawidłowego zakresu:

``` dart
Arr.move(['a', 'b', 'c', 'd'], 0, 2); // ['b', 'c', 'a', 'd']
```

<div id="method-arr-onlyvalues"></div>

#### `Arr.onlyValues()`

Zwraca elementy listy, które są również w podanych wartościach:

``` dart
Arr.onlyValues([1, 2, 3, 4], [2, 4, 9]); // [2, 4]
```

<div id="method-arr-partition"></div>

#### `Arr.partition()`

Dzieli listę na parę `[matching, nonMatching]` na podstawie predykatu:

``` dart
final [evens, odds] = Arr.partition([1, 2, 3, 4], (n) => n.isEven);
// evens: [2, 4], odds: [1, 3]
```

<div id="method-arr-pluck"></div>

#### `Arr.pluck()`

Wyciąga wartość pod danym kluczem z każdej mapy na liście:

``` dart
Arr.pluck([
  {'name': 'Anna'},
  {'name': 'Brad'},
], 'name');
// ['Anna', 'Brad']
```

<div id="method-arr-prepend"></div>

#### `Arr.prepend()`

Zwraca nową listę z wartością wstawioną na początku:

``` dart
Arr.prepend([2, 3], 1); // [1, 2, 3]
```

<div id="method-arr-push"></div>

#### `Arr.push()`

Zwraca nową listę z wartością dołączoną na końcu:

``` dart
Arr.push([1, 2], 3); // [1, 2, 3]
```

<div id="method-arr-random"></div>

#### `Arr.random()`

Zwraca losowy element listy. Zgłasza wyjątek, gdy lista jest pusta:

``` dart
Arr.random(['rock', 'paper', 'scissors']); // jeden z trzech
```

<div id="method-arr-randommany"></div>

#### `Arr.randomMany()`

Zwraca do `count` losowych elementów bez powtórzeń:

``` dart
Arr.randomMany([1, 2, 3, 4, 5], 2); // np. [3, 1]
```

<div id="method-arr-reject"></div>

#### `Arr.reject()`

Filtruje listę do elementów *niespełniających* predykatu (odwrotność [`where`](#method-arr-where)):

``` dart
Arr.reject([1, 2, 3, 4], (n) => n.isEven); // [1, 3]
```

<div id="method-arr-replaceat"></div>

#### `Arr.replaceAt()`

Zwraca nową listę z elementem pod `index` zastąpionym przez `value`. Zgłasza `RangeError`, gdy `index` jest poza zakresem:

``` dart
Arr.replaceAt(['a', 'b', 'c'], 1, 'B'); // ['a', 'B', 'c']
```

<div id="method-arr-select"></div>

#### `Arr.select()`

Zwraca każdą mapę z listy ograniczoną do podanych kluczy:

``` dart
Arr.select([
  {'name': 'Anna', 'role': 'admin', 'age': 30},
  {'name': 'Brad', 'role': 'user',  'age': 25},
], ['name', 'role']);
// [{'name': 'Anna', 'role': 'admin'}, {'name': 'Brad', 'role': 'user'}]
```

<div id="method-arr-shuffle"></div>

#### `Arr.shuffle()`

Zwraca przetasowaną kopię listy. Podaj `seed` dla deterministycznych wyników:

``` dart
Arr.shuffle([1, 2, 3, 4, 5]);
Arr.shuffle([1, 2, 3], seed: 42); // deterministyczny
```

<div id="method-arr-sole"></div>

#### `Arr.sole()`

Zwraca jedyny element spełniający predykat. Zgłasza wyjątek, gdy zero lub więcej niż jeden element pasuje:

``` dart
Arr.sole([1, 2, 3, 4], predicate: (n) => n == 3); // 3
Arr.sole([1, 2, 3], predicate: (n) => n.isEven);  // 2
```

<div id="method-arr-some"></div>

#### `Arr.some()`

Zwraca `true`, gdy co najmniej jeden element spełnia predykat:

``` dart
Arr.some([1, 2, 3], (n) => n.isEven); // true
Arr.some([1, 3, 5], (n) => n.isEven); // false
```

<div id="method-arr-sort"></div>

#### `Arr.sort()`

Zwraca posortowaną kopię listy:

``` dart
Arr.sort([3, 1, 2]);                                // [1, 2, 3]
Arr.sort(users, compare: (a, b) => a.age - b.age);   // według wieku rosnąco
```

<div id="method-arr-sortdesc"></div>

#### `Arr.sortDesc()`

Zwraca posortowaną kopię listy w kolejności malejącej:

``` dart
Arr.sortDesc([3, 1, 2]); // [3, 2, 1]
```

<div id="method-arr-sortrecursive"></div>

#### `Arr.sortRecursive()`

Rekursywnie sortuje listę. Zagnieżdżone listy są sortowane na każdej głębokości:

``` dart
Arr.sortRecursive([[3, 1, 2], [9, 7, 8]]);
// [[1, 2, 3], [7, 8, 9]]
```

<div id="method-arr-sum"></div>

#### `Arr.sum()`

Zwraca sumę listy. Podaj `by`, aby wyodrębnić liczbę z każdego elementu:

``` dart
Arr.sum([1, 2, 3]);                          // 6
Arr.sum(orders, by: (o) => o.total);         // suma wartości zamówień
```

<div id="method-arr-swap"></div>

#### `Arr.swap()`

Zwraca nową listę z zamienionymi elementami na pozycjach `i` i `j`:

``` dart
Arr.swap(['a', 'b', 'c'], 0, 2); // ['c', 'b', 'a']
```

<div id="method-arr-take"></div>

#### `Arr.take()`

Zwraca pierwsze `count` elementów lub ostatnie `count`, jeśli `count` jest ujemne:

``` dart
Arr.take([1, 2, 3, 4, 5], 2);   // [1, 2]
Arr.take([1, 2, 3, 4, 5], -2);  // [4, 5]
```

<div id="method-arr-unique"></div>

#### `Arr.unique()`

Zwraca unikalne wartości listy:

``` dart
Arr.unique([1, 2, 2, 3, 1]); // [1, 2, 3]
```

<div id="method-arr-where"></div>

#### `Arr.where()`

Filtruje listę do elementów spełniających predykat:

``` dart
Arr.where([1, 2, 3, 4], (n) => n.isEven); // [2, 4]
```

<div id="method-arr-wherenotnull"></div>

#### `Arr.whereNotNull()`

Zwraca listę z usuniętymi wartościami `null`:

``` dart
Arr.whereNotNull([1, null, 2, null, 3]); // [1, 2, 3]
```

<div id="method-arr-wrap"></div>

#### `Arr.wrap()`

Owija wartość w `List`, jeśli jeszcze nią nie jest. Zwraca pustą listę, gdy wartość to `null`:

``` dart
Arr.wrap('foo');     // ['foo']
Arr.wrap([1, 2]);    // [1, 2]
Arr.wrap(null);      // []
```

<div id="strings"></div>

## Ciągi znaków

<div id="method-str-after"></div>

#### `Str.after()`

Zwraca część `subject` po pierwszym wystąpieniu `search`:

``` dart
Str.after('hello world hello', 'hello'); // ' world hello'
```

<div id="method-str-afterlast"></div>

#### `Str.afterLast()`

Zwraca część `subject` po ostatnim wystąpieniu `search`:

``` dart
Str.afterLast('app/Http/Controllers', '/'); // 'Controllers'
```

<div id="method-str-before"></div>

#### `Str.before()`

Zwraca część `subject` przed pierwszym wystąpieniem `search`:

``` dart
Str.before('hello world', ' '); // 'hello'
```

<div id="method-str-beforelast"></div>

#### `Str.beforeLast()`

Zwraca część `subject` przed ostatnim wystąpieniem `search`:

``` dart
Str.beforeLast('app/Http/Controllers', '/'); // 'app/Http'
```

<div id="method-str-between"></div>

#### `Str.between()`

Zwraca część `subject` między `from` a `to` (zachłannie):

``` dart
Str.between('[a] foo [b]', '[', ']'); // 'a] foo [b'
```

<div id="method-str-betweenfirst"></div>

#### `Str.betweenFirst()`

Zwraca najmniejszą część `subject` między `from` a `to`:

``` dart
Str.betweenFirst('[a] foo [b]', '[', ']'); // 'a'
```

<div id="method-str-camel"></div>

#### `Str.camel()`

Konwertuje wartość do formatu `camelCase`:

``` dart
Str.camel('foo_bar');     // 'fooBar'
Str.camel('Hello world'); // 'helloWorld'
```

<div id="method-str-charat"></div>

#### `Str.charAt()`

Zwraca znak pod podanym indeksem. Ujemne indeksy liczą od końca. Zwraca `null`, gdy poza zakresem:

``` dart
Str.charAt('hello', 1);  // 'e'
Str.charAt('hello', -1); // 'o'
Str.charAt('hello', 99); // null
```

<div id="method-str-contains"></div>

#### `Str.contains()`

Sprawdza, czy `haystack` zawiera którekolwiek z `needles`:

``` dart
Str.contains('hello world', 'world');                       // true
Str.contains('hello world', ['cat', 'world']);              // true
Str.contains('Hello', 'hello', ignoreCase: true);           // true
```

<div id="method-str-containsall"></div>

#### `Str.containsAll()`

Sprawdza, czy `haystack` zawiera wszystkie `needles`:

``` dart
Str.containsAll('hello world', ['hello', 'world']); // true
Str.containsAll('hello world', ['hello', 'cat']);   // false
```

<div id="method-str-deduplicate"></div>

#### `Str.deduplicate()`

Zastępuje kolejne wystąpienia `character` pojedynczą instancją:

``` dart
Str.deduplicate('hello   world');     // 'hello world'
Str.deduplicate('//path//to//', '/'); // '/path/to/'
```

<div id="method-str-endswith"></div>

#### `Str.endsWith()`

Sprawdza, czy `haystack` kończy się którymkolwiek z `needles`:

``` dart
Str.endsWith('app.dart', '.dart');           // true
Str.endsWith('app.dart', ['.dart', '.ts']);  // true
```

<div id="method-str-excerpt"></div>

#### `Str.excerpt()`

Wyodrębnia fragment tekstu wokół pierwszego wystąpienia `phrase`. Zwraca `null`, gdy fraza nie zostanie znaleziona:

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

Kończy wartość pojedynczą instancją `cap`:

``` dart
Str.finish('hello', '!');     // 'hello!'
Str.finish('hello!!!', '!');  // 'hello!'
```

<div id="method-str-headline"></div>

#### `Str.headline()`

Dzieli ciąg znaków na słowa na granicach wielkich liter, podkreśleń, myślników i spacji, a następnie konwertuje do formatu tytułowego:

``` dart
Str.headline('steve_jobs');           // 'Steve Jobs'
Str.headline('EmailNotificationSent'); // 'Email Notification Sent'
```

<div id="method-str-is"></div>

#### `Str.is_()`

Sprawdza, czy wartość pasuje do podanego wzorca. Gwiazdki pełnią rolę symboli wieloznacznych:

``` dart
Str.is_('foo.*', 'foo.bar');                       // true
Str.is_(['admin/*', 'user/*'], 'admin/profile');   // true
Str.is_('foo', 'bar');                              // false
```

> **Uwaga:** Końcowy podkreślnik pozwala uniknąć zarezerwowanego słowa kluczowego Dart `is`.

<div id="method-str-isascii"></div>

#### `Str.isAscii()`

Zwraca `true`, jeśli wartość zawiera tylko 7-bitowe znaki ASCII:

``` dart
Str.isAscii('hello');  // true
Str.isAscii('héllo');  // false
```

<div id="method-str-isjson"></div>

#### `Str.isJson()`

Zwraca `true`, jeśli wartość jest prawidłowym JSON:

``` dart
Str.isJson('{"a": 1}');   // true
Str.isJson('not json');   // false
```

<div id="method-str-isulid"></div>

#### `Str.isUlid()`

Zwraca `true`, jeśli wartość jest ULID (26 znaków, Crockford base32):

``` dart
Str.isUlid('01ARZ3NDEKTSV4RRFFQ69G5FAV'); // true
Str.isUlid('not a ulid');                  // false
```

<div id="method-str-isurl"></div>

#### `Str.isUrl()`

Zwraca `true`, jeśli wartość jest prawidłowym adresem URL:

``` dart
Str.isUrl('https://nylo.dev'); // true
Str.isUrl('not a url');        // false
```

<div id="method-str-isuuid"></div>

#### `Str.isUuid()`

Zwraca `true`, jeśli wartość jest prawidłowym UUID (dowolna wersja):

``` dart
Str.isUuid('a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11'); // true
Str.isUuid('hello');                                 // false
```

<div id="method-str-kebab"></div>

#### `Str.kebab()`

Konwertuje wartość do formatu `kebab-case`:

``` dart
Str.kebab('fooBar');      // 'foo-bar'
Str.kebab('Hello World'); // 'hello-world'
```

<div id="method-str-lcfirst"></div>

#### `Str.lcfirst()`

Zamienia pierwszy znak wartości na małą literę:

``` dart
Str.lcfirst('Hello world'); // 'hello world'
```

<div id="method-str-length"></div>

#### `Str.length()`

Zwraca długość wartości:

``` dart
Str.length('hello'); // 5
```

<div id="method-str-limit"></div>

#### `Str.limit()`

Skraca wartość do podanej liczby znaków i dołącza sufiks:

``` dart
Str.limit('A long sentence', 6);              // 'A long...'
Str.limit('A long sentence', 6, '... read');  // 'A long... read'
```

<div id="method-str-lower"></div>

#### `Str.lower()`

Zamienia wartość na małe litery:

``` dart
Str.lower('HELLO'); // 'hello'
```

<div id="method-str-mask"></div>

#### `Str.mask()`

Maskuje część ciągu znaków podanym znakiem, zaczynając od `index` przez `length` znaków (lub do końca, gdy `length` jest pominięte):

``` dart
Str.mask('user@example.com', '*', 3);    // 'use*************'
Str.mask('user@example.com', '*', 3, 5); // 'use*****mple.com'
```

<div id="method-str-match"></div>

#### `Str.match()`

Zwraca pierwsze dopasowanie wyrażenia regularnego `pattern` w `subject` lub `null`, gdy nie ma dopasowania:

``` dart
Str.match(RegExp(r'\d+'), 'item 42 in 99 stock'); // '42'
```

> **Uwaga:** To jest dopasowywacz wyrażeń regularnych — różni się od globalnego pomocnika [`match`](#method-match) używanego do wyszukiwania wartości w `Map`.

<div id="method-str-matchall"></div>

#### `Str.matchAll()`

Zwraca wszystkie dopasowania wyrażenia regularnego `pattern` w `subject`:

``` dart
Str.matchAll(RegExp(r'\d+'), 'item 42 in 99 stock'); // ['42', '99']
```

<div id="method-str-padboth"></div>

#### `Str.padBoth()`

Uzupełnia obie strony wartości znakiem `pad` aż do osiągnięcia długości `length`:

``` dart
Str.padBoth('5', 5, '0'); // '00500'
```

<div id="method-str-padleft"></div>

#### `Str.padLeft()`

Uzupełnia lewą stronę wartości:

``` dart
Str.padLeft('5', 3, '0'); // '005'
```

<div id="method-str-padnumber"></div>

#### `Str.padNumber()`

Uzupełnia numeryczny ciąg znaków wiodącymi zerami:

``` dart
Str.padNumber('5', 3); // '005'
```

<div id="method-str-padright"></div>

#### `Str.padRight()`

Uzupełnia prawą stronę wartości:

``` dart
Str.padRight('5', 3, '0'); // '500'
```

<div id="method-str-password"></div>

#### `Str.password()`

Generuje kryptograficznie losowe hasło. Klasy znaków można przełączać za pomocą nazwanych argumentów:

``` dart
Str.password(12);                                   // litery + cyfry + symbole
Str.password(12, symbols: false);                   // tylko litery + cyfry
Str.password(8, letters: false, symbols: false);    // tylko cyfry
```

<div id="method-str-position"></div>

#### `Str.position()`

Zwraca indeks pierwszego wystąpienia `needle` w `haystack` lub `null`, gdy nie znaleziono:

``` dart
Str.position('hello world', 'world');             // 6
Str.position('hello world hello', 'hello', offset: 1); // 12
Str.position('hello', 'cat');                      // null
```

<div id="method-str-random"></div>

#### `Str.random()`

Generuje kryptograficznie losowy alfanumeryczny ciąg znaków o podanej długości:

``` dart
Str.random();    // 16 znaków, np. 'aB3k9XzMq7VnPsRt'
Str.random(8);   // 8 znaków
```

<div id="method-str-remove"></div>

#### `Str.remove()`

Usuwa dowolne `search` z `subject`:

``` dart
Str.remove('o', 'hello world');                  // 'hell wrld'
Str.remove(['e', 'l'], 'hello world');           // 'ho word'
Str.remove('Hello', 'hello world', caseSensitive: false); // ' world'
```

<div id="method-str-repeat"></div>

#### `Str.repeat()`

Powtarza wartość podaną liczbę razy:

``` dart
Str.repeat('ab', 3); // 'ababab'
```

<div id="method-str-replace"></div>

#### `Str.replace()`

Zastępuje każde wystąpienie `search` przez `replace` w `subject`:

``` dart
Str.replace('o', '0', 'hello world');           // 'hell0 w0rld'
Str.replace(['o', 'l'], '*', 'hello world');     // 'he*** w*r*d'
```

<div id="method-str-replacefirst"></div>

#### `Str.replaceFirst()`

Zastępuje pierwsze wystąpienie `search` przez `replace`:

``` dart
Str.replaceFirst('hello', 'hi', 'hello hello'); // 'hi hello'
```

<div id="method-str-replacelast"></div>

#### `Str.replaceLast()`

Zastępuje ostatnie wystąpienie `search` przez `replace`:

``` dart
Str.replaceLast('hello', 'hi', 'hello hello'); // 'hello hi'
```

<div id="method-str-reverse"></div>

#### `Str.reverse()`

Odwraca wartość:

``` dart
Str.reverse('hello'); // 'olleh'
```

<div id="method-str-slug"></div>

#### `Str.slug()`

Generuje przyjazny dla URL slug z tytułu:

``` dart
Str.slug('Hello World');                                  // 'hello-world'
Str.slug('Tech & Code', dictionary: {'&': 'and'});        // 'tech-and-code'
Str.slug('Hello World', separator: '_');                  // 'hello_world'
```

<div id="method-str-snake"></div>

#### `Str.snake()`

Konwertuje wartość do formatu `snake_case`. Podaj własny separator, aby użyć czegoś innego niż `_`:

``` dart
Str.snake('helloWorld');         // 'hello_world'
Str.snake('helloWorld', '-');    // 'hello-world'
```

<div id="method-str-squish"></div>

#### `Str.squish()`

Usuwa wiodące/końcowe białe znaki i zija wewnętrzne białe znaki do pojedynczych spacji:

``` dart
Str.squish('   hello    world   '); // 'hello world'
```

<div id="method-str-start"></div>

#### `Str.start()`

Rozpoczyna wartość od pojedynczej instancji `prefix`:

``` dart
Str.start('this/string', '/');     // '/this/string'
Str.start('//this/string', '/');   // '/this/string'
```

<div id="method-str-startswith"></div>

#### `Str.startsWith()`

Sprawdza, czy `haystack` zaczyna się od któregokolwiek z `needles`:

``` dart
Str.startsWith('hello world', 'hello');           // true
Str.startsWith('hello world', ['cat', 'hello']);  // true
```

<div id="method-str-studly"></div>

#### `Str.studly()`

Konwertuje wartość do formatu `StudlyCase` / `PascalCase`:

``` dart
Str.studly('foo_bar');     // 'FooBar'
Str.studly('hello world'); // 'HelloWorld'
```

<div id="method-str-substr"></div>

#### `Str.substr()`

Zwraca podciąg zaczynający się od `start` o długości `length` znaków. Ujemny `start` liczy od końca:

``` dart
Str.substr('profile', 4);       // 'ile'
Str.substr('profile', 4, 2);    // 'il'
Str.substr('profile', -3);      // 'ile'
```

<div id="method-str-substrcount"></div>

#### `Str.substrCount()`

Zlicza niepokrywające się wystąpienia `needle` w `haystack`:

``` dart
Str.substrCount('hello hello hello', 'hello'); // 3
```

<div id="method-str-swap"></div>

#### `Str.swap()`

Zastępuje wiele podciągów używając `Map` par szukaj → zamień:

``` dart
Str.swap({'foo': 'bar', 'hello': 'hi'}, 'hello foo'); // 'hi bar'
```

<div id="method-str-take"></div>

#### `Str.take()`

Zwraca pierwsze `limit` znaków wartości. Ujemny limit zwraca od końca:

``` dart
Str.take('Build something amazing!', 5); // 'Build'
Str.take('Build something amazing!', -9); // ' amazing!'
```

<div id="method-str-title"></div>

#### `Str.title()`

Konwertuje wartość do formatu `Title Case`:

``` dart
Str.title('a nice title'); // 'A Nice Title'
```

<div id="method-str-ucfirst"></div>

#### `Str.ucfirst()`

Zamienia pierwszy znak wartości na wielką literę:

``` dart
Str.ucfirst('hello world'); // 'Hello world'
```

<div id="method-str-ucsplit"></div>

#### `Str.ucsplit()`

Dzieli wartość na słowa na granicach wielkich liter:

``` dart
Str.ucsplit('fooBarBaz'); // ['foo', 'Bar', 'Baz']
```

<div id="method-str-ulid"></div>

#### `Str.ulid()`

Generuje ULID (Universally Unique Lexicographically Sortable Identifier):

``` dart
Str.ulid(); // np. '01ARZ3NDEKTSV4RRFFQ69G5FAV'
```

<div id="method-str-unwrap"></div>

#### `Str.unwrap()`

Usuwa pojedynczą instancję `before` z początku i `after` (lub `before`, gdy `after` jest null) z końca:

``` dart
Str.unwrap('"value"', '"');             // 'value'
Str.unwrap('<p>html</p>', '<p>', '</p>'); // 'html'
```

<div id="method-str-upper"></div>

#### `Str.upper()`

Zamienia wartość na wielkie litery:

``` dart
Str.upper('hello'); // 'HELLO'
```

<div id="method-str-uuid"></div>

#### `Str.uuid()`

Generuje losowy UUID v4:

``` dart
Str.uuid(); // np. 'a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11'
```

<div id="method-str-uuid7"></div>

#### `Str.uuid7()`

Generuje UUID v7 (uporządkowany czasowo, RFC 9562). UUIDv7 można sortować według czasu utworzenia:

``` dart
Str.uuid7(); // np. '018e0dc4-8b3f-7a2c-9d01-b1f6c87a92e3'
```

<div id="method-str-wordcount"></div>

#### `Str.wordCount()`

Zwraca liczbę słów w wartości:

``` dart
Str.wordCount('hello there friend'); // 3
```

<div id="method-str-words"></div>

#### `Str.words()`

Ogranicza wartość do podanej liczby słów:

``` dart
Str.words('Perfectly balanced, as all things should be.', 3);
// 'Perfectly balanced, as...'
```

<div id="method-str-wrap"></div>

#### `Str.wrap()`

Owija wartość ciągami `before` i `after`. Gdy `after` jest pominięte, `before` jest używane po obu stronach:

``` dart
Str.wrap('value', '"');             // '"value"'
Str.wrap('html', '<p>', '</p>');    // '<p>html</p>'
```

<div id="numbers"></div>

## Liczby

<div id="method-number-abbreviate"></div>

#### `Number.abbreviate()`

Zwraca skróconą formę liczby (`1K`, `1M`, `1B`):

``` dart
Number.abbreviate(1500);                  // '2K'
Number.abbreviate(1500, maxPrecision: 1); // '1.5K'
Number.abbreviate(2_500_000);             // '3M'
```

<div id="method-number-average"></div>

#### `Number.average()`

Zwraca średnią arytmetyczną wartości lub `0` gdy pusta:

``` dart
Number.average([1, 2, 3, 4]); // 2.5
```

<div id="method-number-between"></div>

#### `Number.between()`

Zwraca `true`, gdy wartość jest między `min` a `max` (włącznie):

``` dart
Number.between(5, min: 0, max: 10);  // true
Number.between(15, min: 0, max: 10); // false
```

<div id="method-number-ceil"></div>

#### `Number.ceil()`

Zaokrągla wartość w górę do podanej precyzji:

``` dart
Number.ceil(1.2);    // 2.0
Number.ceil(1.234, 2); // 1.24
```

<div id="method-number-clamp"></div>

#### `Number.clamp()`

Ogranicza wartość do zakresu między `min` a `max` (włącznie):

``` dart
Number.clamp(5, 0, 10);   // 5
Number.clamp(15, 0, 10);  // 10
Number.clamp(-1, 0, 10);  // 0
```

<div id="method-number-currency"></div>

#### `Number.currency()`

Formatuje liczbę jako wartość walutową. Domyślnie używa skonfigurowanego ustawienia regionalnego i waluty:

``` dart
Number.currency(1234.56);                            // '$1,234.56'
Number.currency(1234.56, currency: 'EUR');           // '€1,234.56'
Number.currency(1234.56, locale: 'de_DE', currency: 'EUR');
// '1.234,56 €'
```

<div id="method-number-defaultcurrency"></div>

#### `Number.defaultCurrency()`

Zwraca domyślny kod waluty używany przez metody formatowania:

``` dart
Number.defaultCurrency(); // 'USD'
```

<div id="method-number-defaultlocale"></div>

#### `Number.defaultLocale()`

Zwraca domyślne ustawienie regionalne używane przez metody formatowania:

``` dart
Number.defaultLocale(); // 'en_US'
```

<div id="method-number-degrees"></div>

#### `Number.degrees()`

Konwertuje radiany na stopnie:

``` dart
Number.degrees(3.14159);  // ~180.0
```

<div id="method-number-duration"></div>

#### `Number.duration()`

Formatuje liczbę sekund jako czytelny dla człowieka czas trwania. Forma krótka zwraca `'1h 2m 5s'`; forma długa zwraca `'1:02:05'`:

``` dart
Number.duration(3661);                  // '1h 1m 1s'
Number.duration(3661, short: false);    // '1:01:01'
Number.duration(45);                    // '45s'
```

<div id="method-number-filesize"></div>

#### `Number.fileSize()`

Formatuje liczbę bajtów jako czytelny dla człowieka rozmiar pliku:

``` dart
Number.fileSize(1024);                       // '1 KB'
Number.fileSize(1500, maxPrecision: 1);      // '1.5 KB'
Number.fileSize(1024 * 1024 * 1024);         // '1 GB'
```

<div id="method-number-floor"></div>

#### `Number.floor()`

Zaokrągla wartość w dół do podanej precyzji:

``` dart
Number.floor(1.7);      // 1.0
Number.floor(1.234, 2); // 1.23
```

<div id="method-number-forhumans"></div>

#### `Number.forHumans()`

Zwraca czytelną dla człowieka formę liczby (`1 thousand`, `1 million`):

``` dart
Number.forHumans(1500);                  // '2 thousand'
Number.forHumans(1500, maxPrecision: 1); // '1.5 thousand'
Number.forHumans(2_500_000);             // '3 million'
```

<div id="method-number-format"></div>

#### `Number.format()`

Formatuje liczbę z separatorami uwzględniającymi ustawienie regionalne:

``` dart
Number.format(1234567);                          // '1,234,567'
Number.format(1234.5, precision: 2);             // '1,234.50'
Number.format(1234567, locale: 'de_DE');         // '1.234.567'
```

<div id="method-number-gcd"></div>

#### `Number.gcd()`

Największy wspólny dzielnik dwóch liczb całkowitych:

``` dart
Number.gcd(12, 18); // 6
```

<div id="method-number-lcm"></div>

#### `Number.lcm()`

Najmniejsza wspólna wielokrotność dwóch liczb całkowitych:

``` dart
Number.lcm(4, 6); // 12
```

<div id="method-number-lerp"></div>

#### `Number.lerp()`

Liniowa interpolacja między `a` i `b` przy współczynniku `t` (`0..1`):

``` dart
Number.lerp(0, 100, 0.25); // 25.0
```

<div id="method-number-max"></div>

#### `Number.max()`

Zwraca największą wartość w iterowalnym obiekcie:

``` dart
Number.max([3, 1, 4, 1, 5, 9, 2, 6]); // 9
```

<div id="method-number-median"></div>

#### `Number.median()`

Zwraca medianę wartości lub `0` gdy pusta:

``` dart
Number.median([1, 3, 5]);    // 3.0
Number.median([1, 2, 3, 4]); // 2.5
```

<div id="method-number-min"></div>

#### `Number.min()`

Zwraca najmniejszą wartość w iterowalnym obiekcie:

``` dart
Number.min([3, 1, 4, 1, 5, 9, 2, 6]); // 1
```

<div id="method-number-ordinal"></div>

#### `Number.ordinal()`

Zwraca formę porządkową liczby (`1st`, `2nd`, `3rd`, `4th`):

``` dart
Number.ordinal(1);   // '1st'
Number.ordinal(2);   // '2nd'
Number.ordinal(21);  // '21st'
Number.ordinal(112); // '112th'
```

<div id="method-number-pairs"></div>

#### `Number.pairs()`

Generuje pary (podzakresy) z zakresu do `to`, krokiem `by`:

``` dart
Number.pairs(25, 10);             // [[0, 9], [10, 19], [20, 25]]
Number.pairs(25, 10, offset: 0);  // [[0, 10], [10, 20], [20, 25]]
```

<div id="method-number-parsefloat"></div>

#### `Number.parseFloat()`

Parsuje uwzględniający ustawienie regionalne ciąg znaków jako `double`. Zwraca `null` przy niepowodzeniu:

``` dart
Number.parseFloat('1,234.56');                    // 1234.56
Number.parseFloat('1.234,56', locale: 'de_DE');   // 1234.56
Number.parseFloat('not a number');                // null
```

<div id="method-number-parseint"></div>

#### `Number.parseInt()`

Parsuje uwzględniający ustawienie regionalne ciąg znaków jako `int`. Zwraca `null` przy niepowodzeniu:

``` dart
Number.parseInt('1,234');                    // 1234
Number.parseInt('1.234', locale: 'de_DE');   // 1234
```

<div id="method-number-percentage"></div>

#### `Number.percentage()`

Formatuje liczbę jako procent. Liczba jest traktowana jako całkowita wartość procentowa:

``` dart
Number.percentage(10);                  // '10%'
Number.percentage(10.5, precision: 1);  // '10.5%'
```

<div id="method-number-radians"></div>

#### `Number.radians()`

Konwertuje stopnie na radiany:

``` dart
Number.radians(180); // ~3.14159
```

<div id="method-number-random"></div>

#### `Number.random()`

Zwraca losową liczbę całkowitą z zakresu `[min, max]` (włącznie). Podaj `seed` dla deterministycznej sekwencji:

``` dart
Number.random(min: 1, max: 100);              // dowolna liczba 1..100
Number.random(min: 0, max: 9, seed: 42);      // deterministyczny
```

<div id="method-number-range"></div>

#### `Number.range()`

Generuje listę liczb całkowitych od `start` (włącznie) do `end` (włącznie), krokiem `step`:

``` dart
Number.range(1, 5);              // [1, 2, 3, 4, 5]
Number.range(0, 10, step: 2);    // [0, 2, 4, 6, 8, 10]
Number.range(5, 1, step: -1);    // [5, 4, 3, 2, 1]
```

<div id="method-number-round"></div>

#### `Number.round()`

Zaokrągla wartość do podanej precyzji:

``` dart
Number.round(1.5);      // 2.0
Number.round(1.234, 2); // 1.23
```

<div id="method-number-scale"></div>

#### `Number.scale()`

Odwzorowuje wartość z jednego zakresu na inny:

``` dart
Number.scale(0.5, fromMin: 0, fromMax: 1, toMin: 0, toMax: 100); // 50.0
Number.scale(75, fromMin: 0, fromMax: 100, toMin: -1, toMax: 1); // 0.5
```

<div id="method-number-spell"></div>

#### `Number.spell()`

Wypisuje liczbę słownie po angielsku:

``` dart
Number.spell(0);           // 'zero'
Number.spell(123);         // 'one hundred twenty-three'
Number.spell(1_234_567);   // 'one million two hundred thirty-four thousand five hundred sixty-seven'
```

<div id="method-number-spellordinal"></div>

#### `Number.spellOrdinal()`

Wypisuje formę porządkową liczby słownie po angielsku:

``` dart
Number.spellOrdinal(1);   // 'first'
Number.spellOrdinal(2);   // 'second'
Number.spellOrdinal(21);  // 'twenty-first'
```

<div id="method-number-sum"></div>

#### `Number.sum()`

Zwraca sumę wartości:

``` dart
Number.sum([1, 2, 3]); // 6
```

<div id="method-number-tobytes"></div>

#### `Number.toBytes()`

Konwertuje rozmiar pliku na bajty. Odwrotność [`fileSize`](#method-number-filesize). Podaj parsowalny ciąg znaków lub liczbę z `unit`:

``` dart
Number.toBytes('1.5 KB');     // 1536
Number.toBytes(1.5, 'KB');    // 1536
Number.toBytes(1, 'GB');      // 1073741824
Number.toBytes('not bytes');  // null
```

<div id="method-number-trim"></div>

#### `Number.trim()`

Usuwa końcowe zera z części dziesiętnej wartości:

``` dart
Number.trim(12.0);    // '12'
Number.trim(12.30);   // '12.3'
Number.trim(12.345);  // '12.345'
```

<div id="method-number-usecurrency"></div>

#### `Number.useCurrency()`

Ustawia domyślny kod waluty używany przez metody formatowania:

``` dart
Number.useCurrency('EUR');
Number.currency(99); // '€99.00'
```

<div id="method-number-uselocale"></div>

#### `Number.useLocale()`

Ustawia domyślne ustawienie regionalne używane przez metody formatowania:

``` dart
Number.useLocale('de_DE');
Number.format(1234567); // '1.234.567'
```

<div id="objects"></div>

## Obiekty

`Obj` dostarcza narzędzia do pracy z mapami używając ścieżek w **notacji kropkowej**. Elementy list można adresować przez indeks całkowity (np. `'users.0.name'`).

<div id="method-obj-add"></div>

#### `Obj.add()`

Dodaje wartość pod `key` tylko wtedy, gdy nie ma tam jeszcze żadnej wartości:

``` dart
final user = {'name': 'Anna'};
Obj.add(user, 'role', 'admin');
// user: {'name': 'Anna', 'role': 'admin'}

Obj.add(user, 'name', 'Brad'); // brak akcji — name już ustawione
```

<div id="method-obj-deepequals"></div>

#### `Obj.deepEquals()`

Zwraca `true`, gdy dwie wartości są strukturalnie równe (rekursywne porównanie `Map` i `List`):

``` dart
Obj.deepEquals({'a': [1, 2]}, {'a': [1, 2]}); // true
Obj.deepEquals({'a': 1}, {'a': 2});            // false
```

<div id="method-obj-divide"></div>

#### `Obj.divide()`

Dzieli mapę na parę `[keys, values]`:

``` dart
Obj.divide({'name': 'Desk', 'price': 100});
// [['name', 'price'], ['Desk', 100]]
```

<div id="method-obj-dot"></div>

#### `Obj.dot()`

Spłaszcza zagnieżdżoną mapę do jednopoziomowej mapy z kluczami w postaci ścieżek kropkowych:

``` dart
Obj.dot({'a': {'b': 1, 'c': 2}});
// {'a.b': 1, 'a.c': 2}
```

<div id="method-obj-except"></div>

#### `Obj.except()`

Zwraca nową mapę zawierającą wszystkie wpisy z wyjątkiem tych z podanymi kluczami:

``` dart
Obj.except({'name': 'Anna', 'role': 'admin', 'age': 30}, ['age']);
// {'name': 'Anna', 'role': 'admin'}
```

<div id="method-obj-exists"></div>

#### `Obj.exists()`

Zwraca `true`, gdy mapa ma podany klucz na najwyższym poziomie (bez przechodzenia notacją kropkową):

``` dart
Obj.exists({'a.b': 1}, 'a.b'); // true
Obj.exists({'a': {'b': 1}}, 'a.b'); // false (użyj `has` do przechodzenia)
```

<div id="method-obj-flip"></div>

#### `Obj.flip()`

Zamienia klucze i wartości. Gdy wartości kolidują, wygrywa ostatni wpis:

``` dart
Obj.flip({'one': 1, 'two': 2}); // {1: 'one', 2: 'two'}
```

<div id="method-obj-forget"></div>

#### `Obj.forget()`

Usuwa klucz (notacja kropkowa) z mapy. Zwraca mapę do łańcuchowania:

``` dart
final user = {'profile': {'name': 'Anna', 'email': 'a@b'}};
Obj.forget(user, 'profile.email');
// user: {'profile': {'name': 'Anna'}}
```

<div id="method-obj-get"></div>

#### `Obj.get()`

Zwraca wartość pod `key` używając notacji kropkowej lub wartość domyślną, gdy ścieżka nie istnieje. Klucze najwyższego poziomu zawierające literalne kropki mają pierwszeństwo przed przechodzeniem:

``` dart
final user = {'profile': {'name': 'Anna', 'age': 30}};
Obj.get(user, 'profile.name');     // 'Anna'
Obj.get(user, 'profile.email');    // null
Obj.get(user, 'profile.email', 'unknown'); // 'unknown'

// Indeksy list:
Obj.get({'users': [{'name': 'A'}]}, 'users.0.name'); // 'A'
```

<div id="method-obj-getbool"></div>

#### `Obj.getBool()`

Zwraca wartość pod `key` rzutowaną na `bool` lub wartość domyślną. Liczby są prawdziwe, gdy niezerowe; ciągi rozpoznają `'true'`/`'false'`/`'1'`/`'0'`/`'yes'`/`'no'`/`'on'`/`'off'` (bez uwzględniania wielkości liter):

``` dart
Obj.getBool({'flag': 'yes'}, 'flag');     // true
Obj.getBool({'flag': 0}, 'flag');         // false
Obj.getBool({}, 'flag', false);           // false
```

<div id="method-obj-getdouble"></div>

#### `Obj.getDouble()`

Zwraca wartość pod `key` rzutowaną na `double` lub wartość domyślną:

``` dart
Obj.getDouble({'price': '9.99'}, 'price'); // 9.99
Obj.getDouble({'price': 10}, 'price');     // 10.0
```

<div id="method-obj-getint"></div>

#### `Obj.getInt()`

Zwraca wartość pod `key` rzutowaną na `int` lub wartość domyślną:

``` dart
Obj.getInt({'count': '42'}, 'count');     // 42
Obj.getInt({'count': 3.7}, 'count');      // 3 (obcięte)
Obj.getInt({}, 'count', 0);               // 0
```

<div id="method-obj-getlist"></div>

#### `Obj.getList()`

Zwraca wartość pod `key`, gdy jest `List`, w przeciwnym razie wartość domyślną:

``` dart
Obj.getList({'items': [1, 2, 3]}, 'items'); // [1, 2, 3]
Obj.getList({'items': 'oops'}, 'items', []); // []
```

<div id="method-obj-getmap"></div>

#### `Obj.getMap()`

Zwraca wartość pod `key`, gdy jest `Map`, w przeciwnym razie wartość domyślną:

``` dart
Obj.getMap({'meta': {'a': 1}}, 'meta'); // {'a': 1}
Obj.getMap({'meta': null}, 'meta', {}); // {}
```

<div id="method-obj-getstring"></div>

#### `Obj.getString()`

Zwraca wartość pod `key` rzutowaną na `String` lub wartość domyślną:

``` dart
Obj.getString({'name': 'Anna'}, 'name'); // 'Anna'
Obj.getString({'count': 42}, 'count');   // '42'
Obj.getString({}, 'name', 'Guest');      // 'Guest'
```

<div id="method-obj-has"></div>

#### `Obj.has()`

Zwraca `true`, gdy mapa ma wartość pod `key` używając notacji kropkowej:

``` dart
final user = {'profile': {'name': 'Anna'}};
Obj.has(user, 'profile.name');  // true
Obj.has(user, 'profile.email'); // false
```

<div id="method-obj-hasall"></div>

#### `Obj.hasAll()`

Zwraca `true`, gdy każdy z podanych kluczy można odnaleźć w mapie:

``` dart
Obj.hasAll({'a': 1, 'b': 2}, ['a', 'b']);      // true
Obj.hasAll({'a': 1, 'b': 2}, ['a', 'c']);      // false
```

<div id="method-obj-hasany"></div>

#### `Obj.hasAny()`

Zwraca `true`, gdy którykolwiek z podanych kluczy można odnaleźć w mapie:

``` dart
Obj.hasAny({'a': 1}, ['a', 'b']); // true
Obj.hasAny({'a': 1}, ['x', 'y']); // false
```

<div id="method-obj-mapkeys"></div>

#### `Obj.mapKeys()`

Zwraca nową mapę z każdym kluczem przekształconym przez `fn`:

``` dart
Obj.mapKeys({'firstName': 'Anna'}, (k) => k.toLowerCase());
// {'firstname': 'Anna'}
```

<div id="method-obj-mapvalues"></div>

#### `Obj.mapValues()`

Zwraca nową mapę z każdą wartością przekształconą przez `fn`:

``` dart
Obj.mapValues({'a': 1, 'b': 2}, (v) => v * 10);
// {'a': 10, 'b': 20}
```

<div id="method-obj-merge"></div>

#### `Obj.merge()`

Rekursywnie scala `source` do `target`. Przy kolizji kluczy wygrywa `source`; zagnieżdżone mapy są scalane:

``` dart
Obj.merge(
  {'a': {'x': 1}, 'b': 2},
  {'a': {'y': 9}, 'b': 22},
);
// {'a': {'x': 1, 'y': 9}, 'b': 22}
```

<div id="method-obj-only"></div>

#### `Obj.only()`

Zwraca nową mapę zawierającą tylko wpisy z podanymi kluczami:

``` dart
Obj.only({'name': 'Anna', 'role': 'admin', 'age': 30}, ['name', 'role']);
// {'name': 'Anna', 'role': 'admin'}
```

<div id="method-obj-prependkeyswith"></div>

#### `Obj.prependKeysWith()`

Dodaje podany prefiks do każdego klucza najwyższego poziomu w mapie:

``` dart
Obj.prependKeysWith({'name': 'Anna', 'age': 30}, 'user_');
// {'user_name': 'Anna', 'user_age': 30}
```

<div id="method-obj-pull"></div>

#### `Obj.pull()`

Zwraca wartość pod `key` i usuwa ją z mapy:

``` dart
final user = {'name': 'Anna', 'temp': 'token'};
final temp = Obj.pull(user, 'temp'); // 'token'
// user: {'name': 'Anna'}
```

<div id="method-obj-query"></div>

#### `Obj.query()`

Koduje mapę jako ciąg zapytania URL:

``` dart
Obj.query({'name': 'Anna', 'tags': ['a', 'b']});
// 'name=Anna&tags%5B0%5D=a&tags%5B1%5D=b'

Obj.query({'filter': {'role': 'admin'}});
// 'filter%5Brole%5D=admin'
```

<div id="method-obj-set"></div>

#### `Obj.set()`

Ustawia wartość pod `key`, tworząc zagnieżdżone mapy wzdłuż ścieżki. Modyfikuje mapę w miejscu i zwraca ją do łańcuchowania:

``` dart
final user = <String, dynamic>{};
Obj.set(user, 'profile.email', 'a@b');
// user: {'profile': {'email': 'a@b'}}
```

<div id="method-obj-undot"></div>

#### `Obj.undot()`

Rozpakowuje mapę z kluczami kropkowymi z powrotem do struktury zagnieżdżonej (odwrotność [`dot`](#method-obj-dot)):

``` dart
Obj.undot({'a.b': 1, 'a.c': 2});
// {'a': {'b': 1, 'c': 2}}
```

<div id="method-obj-wherenotempty"></div>

#### `Obj.whereNotEmpty()`

Zwraca nową mapę z usuniętymi wpisami, których wartości są `null` lub puste (pusty `String`, `Iterable` lub `Map`):

``` dart
Obj.whereNotEmpty({'name': 'Anna', 'tags': [], 'bio': ''});
// {'name': 'Anna'}
```

<div id="method-obj-wherenotnull"></div>

#### `Obj.whereNotNull()`

Zwraca nową mapę z usuniętymi wpisami, których wartości są `null`:

``` dart
Obj.whereNotNull({'name': 'Anna', 'email': null});
// {'name': 'Anna'}
```

<div id="miscellaneous"></div>

## Różne

Te globalne funkcje znajdują się w `helper.dart` i są dostępne wszędzie, gdzie importujesz `nylo_framework`.

<div id="method-api"></div>

#### `api()`

Pomocnicza funkcja ułatwiająca wysyłanie żądań API przez `NyApiService`. Pełne opcje znajdziesz na stronie [Networking](/docs/{{ $version }}/networking):

``` dart
await api<ApiService>((request) =>
  request.get('https://jsonplaceholder.typicode.com/posts'));
```

<div id="method-datatomodel"></div>

#### `dataToModel()`

Konwertuje surowe dane (zazwyczaj JSON `Map`) na model zarejestrowany w `config/decoders.dart`. Parametr typu jest wymagany:

``` dart
final user = dataToModel<User>(data: {'name': 'Anna', 'age': 30});
```

<div id="method-dump"></div>

#### `dump()`

Wyświetla wartość w konsoli. Respektuje flagę `APP_DEBUG` aplikacji, chyba że `alwaysPrint: true`:

``` dart
dump('Hello World');
dump(user, tag: 'current user');
dump(payload, alwaysPrint: true);
```

<div id="method-event"></div>

#### `event()`

Wywołuje zdarzenie podanego typu za pomocą `Nylo.events()`. Pełne użycie znajdziesz na stronie [Events](/docs/{{ $version }}/events):

``` dart
await event<LoginEvent>(data: {'user': 'Anna'});
```

<div id="method-getasset"></div>

#### `getAsset()`

Zwraca pełną ścieżkę do zasobu w katalogu `/assets`:

``` dart
final path = getAsset('videos/welcome.mp4');
// 'assets/videos/welcome.mp4'
```

<div id="method-getenv"></div>

#### `getEnv()`

Zwraca wartość z wygenerowanego pliku env (`env.g.dart`). Zwraca wartość domyślną, gdy klucz nie jest ustawiony:

``` dart
final apiUrl = getEnv('API_URL');
final debug = getEnv('APP_DEBUG', defaultValue: false);
```

<div id="method-getimageasset"></div>

#### `getImageAsset()`

Zwraca pełną ścieżkę do obrazu w `/assets/images/`:

``` dart
Image.asset(getImageAsset('logo.png'));
```

<div id="method-loadjson"></div>

#### `loadJson()`

Wczytuje plik JSON z pakietu zasobów. Wyniki są domyślnie buforowane:

``` dart
final config = await loadJson<Map<String, dynamic>>('config/app.json');
```

<div id="method-match"></div>

#### `match()`

Porównuje wartość z `Map` przypadków i zwraca odpowiednią wartość. Zgłasza wyjątek, gdy żaden przypadek nie pasuje i nie podano `defaultValue`:

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

> **Uwaga:** To jest wyszukiwanie wartości w `Map` — różni się od [`Str.match`](#method-str-match), które wykonuje dopasowywanie wyrażeń regularnych.

<div id="method-now"></div>

#### `now()`

Zwraca bieżący `DateTime`:

``` dart
final timestamp = now();
```

<div id="method-nyhexcolor"></div>

#### `nyHexColor()`

Konwertuje ciąg znaków koloru szesnastkowego na `Color`. Akceptuje wartości 6- lub 8-cyfrowe, z lub bez wiodącego `#`:

``` dart
final brand = nyHexColor('#FF6B35');
final translucent = nyHexColor('80FF6B35'); // 8-cyfrowy (alfa na początku)
```

<div id="method-print-helpers"></div>

#### `printDebug()`, `printError()`, `printInfo()`, `printWarning()`, `printSuccess()`, `printVerbose()`, `printAlert()`, `printEmergency()`

Loggery konsolowe oznaczone poziomem ważności. Formatowanie wyjścia i flagę `alwaysPrint` znajdziesz na stronie [Logging](/docs/{{ $version }}/logging):

``` dart
printInfo('User signed in');
printError('Payment failed', context: {'order_id': 42});
printDebug({'state': state});
```

<div id="method-badge-helpers"></div>

#### `setBadgeNumber()`, `clearBadgeNumber()`

Ustawia lub usuwa liczbę plakietki ikony aplikacji. Szczegóły konfiguracji platformy znajdziesz na stronie [Local Notifications](/docs/{{ $version }}/local-notifications):

``` dart
await setBadgeNumber(3);
await clearBadgeNumber();
```

<div id="method-shownextlog"></div>

#### `showNextLog()`

Wymusza wyświetlenie następnego logu `NyLogger` nawet gdy `APP_DEBUG` jest `false`. Przydatne do jednorazowej diagnostyki w buildach produkcyjnych:

``` dart
showNextLog();
NyLogger.info('This will print regardless of APP_DEBUG');
```

<div id="method-sleep"></div>

#### `sleep()`

Opóźnia wykonanie o podany czas. Pierwszy argument to sekundy; opcjonalny drugi argument dodaje mikrosekundy:

``` dart
await sleep(2);              // 2 sekundy
await sleep(0, 500_000);     // 500 milisekund
```

<div id="method-trans"></div>

#### `trans()`

Zwraca przetłumaczony ciąg znaków według klucza. Konfigurację znajdziesz na stronie [Localization](/docs/{{ $version }}/localization):

``` dart
final greeting = trans('home.welcome');
final personalized = trans('home.hello', arguments: {'name': 'Anna'});
```
