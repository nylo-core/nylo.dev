# Helpers

<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Methodes disponibles](#available-methods "Methodes disponibles")
  - [Tableaux](#arrays-method-list "Tableaux")
  - [Chaines](#strings-method-list "Chaines")
  - [Nombres](#numbers-method-list "Nombres")
  - [Objets](#objects-method-list "Objets")
  - [Divers](#miscellaneous-method-list "Divers")
- [Tableaux](#arrays "Tableaux")
- [Chaines](#strings "Chaines")
- [Nombres](#numbers "Nombres")
- [Objets](#objects "Objets")
- [Divers](#miscellaneous "Divers")

<div id="introduction"></div>

## Introduction

{{ config('app.name') }} est livre avec un ensemble de classes utilitaires statiques — `Arr`, `Str`, `Number` et `Obj` — ainsi qu'une poignee de fonctions d'assistance globales pour les taches courantes. Elles sont toutes exportees depuis `nylo_framework` :

``` dart
import 'package:nylo_framework/nylo_framework.dart';

Arr.first([1, 2, 3]);             // 1
Str.slug('Hello World');          // 'hello-world'
Number.currency(1234.56);         // '$1,234.56'
Obj.get(user, 'profile.name');    // 'Anna'
```

> **Note :** `Backpack`, `NyStorage`, `NyCache` et `NyLogger` disposent de leurs propres pages dedicees : [Backpack](/docs/{{ $version }}/backpack), [Storage](/docs/{{ $version }}/storage), [Cache](/docs/{{ $version }}/cache), [Logging](/docs/{{ $version }}/logging).

<div id="available-methods"></div>

## Methodes disponibles

<div id="arrays-method-list"></div>

### Tableaux

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

### Chaines

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

### Nombres

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

### Objets

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

### Divers

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

## Tableaux

<div id="method-arr-accessible"></div>

#### `Arr.accessible()`

Determines si la valeur donnee est accessible comme tableau (un `List` ou `Map`) :

``` dart
Arr.accessible([1, 2, 3]);  // true
Arr.accessible({'a': 1});   // true
Arr.accessible('hello');    // false
Arr.accessible(null);       // false
```

<div id="method-arr-average"></div>

#### `Arr.average()`

Retourne la moyenne arithmetique de la liste, ou `0` quand elle est vide. Passez `by` pour extraire un nombre de chaque element :

``` dart
Arr.average([2, 4, 6]);                // 4.0
Arr.average(orders, by: (o) => o.total); // moyenne du total
```

<div id="method-arr-chunk"></div>

#### `Arr.chunk()`

Divise une liste en morceaux de la taille donnee :

``` dart
Arr.chunk([1, 2, 3, 4, 5], 2);
// [[1, 2], [3, 4], [5]]
```

<div id="method-arr-collapse"></div>

#### `Arr.collapse()`

Reduit une liste de listes en une seule liste :

``` dart
Arr.collapse([[1, 2], [3, 4], [5]]);
// [1, 2, 3, 4, 5]
```

<div id="method-arr-countby"></div>

#### `Arr.countBy()`

Compte les occurrences dans la liste, en regroupant optionnellement par un extracteur de valeur :

``` dart
Arr.countBy(['a', 'b', 'a', 'c']);
// {'a': 2, 'b': 1, 'c': 1}

Arr.countBy(orders, by: (o) => o.status);
// {'paid': 12, 'pending': 3}
```

<div id="method-arr-crossjoin"></div>

#### `Arr.crossJoin()`

Effectue le produit cartesien des listes donnees, retournant toutes les combinaisons possibles :

``` dart
Arr.crossJoin([[1, 2], ['a', 'b']]);
// [[1, 'a'], [1, 'b'], [2, 'a'], [2, 'b']]
```

<div id="method-arr-every"></div>

#### `Arr.every()`

Retourne `true` quand chaque element de la liste satisfait le predicat. Vrai par vacuite pour les listes vides :

``` dart
Arr.every([2, 4, 6], (n) => n.isEven); // true
Arr.every([1, 2, 3], (n) => n.isEven); // false
```

<div id="method-arr-exceptvalues"></div>

#### `Arr.exceptValues()`

Retourne la liste sans chacune des valeurs donnees :

``` dart
Arr.exceptValues([1, 2, 3, 4], [2, 4]); // [1, 3]
```

<div id="method-arr-first"></div>

#### `Arr.first()`

Retourne le premier element satisfaisant le predicat, ou la valeur par defaut :

``` dart
Arr.first([1, 2, 3]);                                   // 1
Arr.first([1, 2, 3, 4], predicate: (n) => n.isEven);    // 2
Arr.first([], defaultValue: 99);                        // 99
```

<div id="method-arr-flatmap"></div>

#### `Arr.flatMap()`

Applique `fn` a chaque element et aplatit les iterables resultants :

``` dart
Arr.flatMap(pages, (p) => p.items); // tous les items de toutes les pages
Arr.flatMap([1, 2, 3], (n) => [n, n * 10]); // [1, 10, 2, 20, 3, 30]
```

<div id="method-arr-flatten"></div>

#### `Arr.flatten()`

Aplatit un iterable imbrique en une liste a un seul niveau. `depth` limite la profondeur d'aplatissement ; `-1` signifie illimite :

``` dart
Arr.flatten([1, [2, [3]]]);              // [1, 2, 3]
Arr.flatten([1, [2, [3]]], depth: 1);    // [1, 2, [3]]
```

<div id="method-arr-groupby"></div>

#### `Arr.groupBy()`

Regroupe la liste dans une map indexee par la valeur retournee par `by` :

``` dart
Arr.groupBy(messages, (m) => m.date);
// {2024-01-01: [...], 2024-01-02: [...]}
```

<div id="method-arr-indexed"></div>

#### `Arr.indexed()`

Associe chaque element a son indice, retournant une liste d'enregistrements `(indice, valeur)` :

``` dart
for (final (i, v) in Arr.indexed(['a', 'b', 'c'])) {
  print('$i: $v'); // '0: a', '1: b', '2: c'
}
```

<div id="method-arr-interleave"></div>

#### `Arr.interleave()`

Insere un separateur entre chaque paire d'elements. Utile pour construire des enfants de widgets avec des separateurs :

``` dart
Arr.interleave([1, 2, 3], 0); // [1, 0, 2, 0, 3]

Column(children: Arr.interleave(tiles, const Divider()));
```

<div id="method-arr-isassoc"></div>

#### `Arr.isAssoc()`

Retourne `true` quand la valeur est un `Map` (associatif) :

``` dart
Arr.isAssoc({'a': 1});  // true
Arr.isAssoc([1, 2, 3]); // false
```

<div id="method-arr-islist"></div>

#### `Arr.isList()`

Retourne `true` quand la valeur est un `List` :

``` dart
Arr.isList([1, 2, 3]); // true
Arr.isList({'a': 1});  // false
```

<div id="method-arr-join"></div>

#### `Arr.join()`

Joint la liste en une chaine. Le dernier element peut etre joint avec un separateur different :

``` dart
Arr.join(['Anna', 'Brad', 'Carol'], ', ', ' and ');
// 'Anna, Brad and Carol'

Arr.join(['a', 'b', 'c']);
// 'a, b, c'
```

<div id="method-arr-keyby"></div>

#### `Arr.keyBy()`

Indexe la liste par la valeur a une cle. Les entrees avec une valeur `null` a cette cle sont ignorees :

``` dart
Arr.keyBy([
  {'id': 1, 'name': 'Anna'},
  {'id': 2, 'name': 'Brad'},
], 'id');
// {1: {'id': 1, 'name': 'Anna'}, 2: {'id': 2, 'name': 'Brad'}}
```

<div id="method-arr-last"></div>

#### `Arr.last()`

Retourne le dernier element satisfaisant le predicat, ou la valeur par defaut :

``` dart
Arr.last([1, 2, 3, 4], predicate: (n) => n.isEven); // 4
Arr.last([1, 2, 3]);                                 // 3
```

<div id="method-arr-map"></div>

#### `Arr.map()`

Applique un rappel a chaque element en recevant egalement l'indice :

``` dart
Arr.map(['a', 'b'], (v, i) => '$i:$v'); // ['0:a', '1:b']
```

<div id="method-arr-mapwithkeys"></div>

#### `Arr.mapWithKeys()`

Convertit chaque element en `MapEntry`, retournant la map resultante :

``` dart
Arr.mapWithKeys(
  [{'id': 1, 'name': 'Anna'}, {'id': 2, 'name': 'Brad'}],
  (m) => MapEntry(m['id'], m['name']),
);
// {1: 'Anna', 2: 'Brad'}
```

<div id="method-arr-max"></div>

#### `Arr.max()`

Retourne l'element ayant la plus grande valeur de `by`, ou le plus grand element quand `by` est omis :

``` dart
Arr.max([3, 1, 4, 1, 5]);            // 5
Arr.max(users, by: (u) => u.age);    // utilisateur le plus age
```

<div id="method-arr-median"></div>

#### `Arr.median()`

Retourne la mediane de la liste, ou `0` quand elle est vide :

``` dart
Arr.median([1, 3, 5]);    // 3.0
Arr.median([1, 2, 3, 4]); // 2.5
```

<div id="method-arr-min"></div>

#### `Arr.min()`

Retourne l'element ayant la plus petite valeur de `by`, ou le plus petit element quand `by` est omis :

``` dart
Arr.min([3, 1, 4, 1, 5]);            // 1
Arr.min(users, by: (u) => u.age);    // utilisateur le plus jeune
```

<div id="method-arr-move"></div>

#### `Arr.move()`

Retourne une nouvelle liste avec l'element en `from` deplace a la position `to`. `to` est limite a la plage valide :

``` dart
Arr.move(['a', 'b', 'c', 'd'], 0, 2); // ['b', 'c', 'a', 'd']
```

<div id="method-arr-onlyvalues"></div>

#### `Arr.onlyValues()`

Retourne les elements de la liste qui sont aussi parmi les valeurs donnees :

``` dart
Arr.onlyValues([1, 2, 3, 4], [2, 4, 9]); // [2, 4]
```

<div id="method-arr-partition"></div>

#### `Arr.partition()`

Divise la liste en une paire `[correspondants, nonCorrespondants]` selon le predicat :

``` dart
final [evens, odds] = Arr.partition([1, 2, 3, 4], (n) => n.isEven);
// evens: [2, 4], odds: [1, 3]
```

<div id="method-arr-pluck"></div>

#### `Arr.pluck()`

Extrait la valeur a une cle de chaque map dans la liste :

``` dart
Arr.pluck([
  {'name': 'Anna'},
  {'name': 'Brad'},
], 'name');
// ['Anna', 'Brad']
```

<div id="method-arr-prepend"></div>

#### `Arr.prepend()`

Retourne une nouvelle liste avec la valeur inseree au debut :

``` dart
Arr.prepend([2, 3], 1); // [1, 2, 3]
```

<div id="method-arr-push"></div>

#### `Arr.push()`

Retourne une nouvelle liste avec la valeur ajoutee a la fin :

``` dart
Arr.push([1, 2], 3); // [1, 2, 3]
```

<div id="method-arr-random"></div>

#### `Arr.random()`

Retourne un element aleatoire de la liste. Leve une exception quand la liste est vide :

``` dart
Arr.random(['rock', 'paper', 'scissors']); // l'un des trois
```

<div id="method-arr-randommany"></div>

#### `Arr.randomMany()`

Retourne jusqu'a `count` elements aleatoires sans remplacement :

``` dart
Arr.randomMany([1, 2, 3, 4, 5], 2); // ex. [3, 1]
```

<div id="method-arr-reject"></div>

#### `Arr.reject()`

Filtre la liste aux elements ne satisfaisant *pas* le predicat (inverse de [`where`](#method-arr-where)) :

``` dart
Arr.reject([1, 2, 3, 4], (n) => n.isEven); // [1, 3]
```

<div id="method-arr-replaceat"></div>

#### `Arr.replaceAt()`

Retourne une nouvelle liste avec l'element a `index` remplace par `value`. Leve `RangeError` quand `index` est hors plage :

``` dart
Arr.replaceAt(['a', 'b', 'c'], 1, 'B'); // ['a', 'B', 'c']
```

<div id="method-arr-select"></div>

#### `Arr.select()`

Retourne chaque map de la liste reduite aux cles indiquees uniquement :

``` dart
Arr.select([
  {'name': 'Anna', 'role': 'admin', 'age': 30},
  {'name': 'Brad', 'role': 'user',  'age': 25},
], ['name', 'role']);
// [{'name': 'Anna', 'role': 'admin'}, {'name': 'Brad', 'role': 'user'}]
```

<div id="method-arr-shuffle"></div>

#### `Arr.shuffle()`

Retourne une copie melangee de la liste. Passez `seed` pour le determinisme :

``` dart
Arr.shuffle([1, 2, 3, 4, 5]);
Arr.shuffle([1, 2, 3], seed: 42); // deterministe
```

<div id="method-arr-sole"></div>

#### `Arr.sole()`

Retourne l'unique element satisfaisant le predicat. Leve une exception quand zero ou plus d'un element correspond :

``` dart
Arr.sole([1, 2, 3, 4], predicate: (n) => n == 3); // 3
Arr.sole([1, 2, 3], predicate: (n) => n.isEven);  // 2
```

<div id="method-arr-some"></div>

#### `Arr.some()`

Retourne `true` quand au moins un element satisfait le predicat :

``` dart
Arr.some([1, 2, 3], (n) => n.isEven); // true
Arr.some([1, 3, 5], (n) => n.isEven); // false
```

<div id="method-arr-sort"></div>

#### `Arr.sort()`

Retourne une copie triee de la liste :

``` dart
Arr.sort([3, 1, 2]);                                // [1, 2, 3]
Arr.sort(users, compare: (a, b) => a.age - b.age);   // par age croissant
```

<div id="method-arr-sortdesc"></div>

#### `Arr.sortDesc()`

Retourne une copie triee de la liste en ordre decroissant :

``` dart
Arr.sortDesc([3, 1, 2]); // [3, 2, 1]
```

<div id="method-arr-sortrecursive"></div>

#### `Arr.sortRecursive()`

Trie la liste de maniere recursive. Les listes imbriquees sont triees a chaque profondeur :

``` dart
Arr.sortRecursive([[3, 1, 2], [9, 7, 8]]);
// [[1, 2, 3], [7, 8, 9]]
```

<div id="method-arr-sum"></div>

#### `Arr.sum()`

Retourne la somme de la liste. Passez `by` pour extraire un nombre de chaque element :

``` dart
Arr.sum([1, 2, 3]);                          // 6
Arr.sum(orders, by: (o) => o.total);         // somme des totaux
```

<div id="method-arr-swap"></div>

#### `Arr.swap()`

Retourne une nouvelle liste avec les elements en `i` et `j` echanges :

``` dart
Arr.swap(['a', 'b', 'c'], 0, 2); // ['c', 'b', 'a']
```

<div id="method-arr-take"></div>

#### `Arr.take()`

Retourne les premiers `count` elements, ou les derniers `count` si `count` est negatif :

``` dart
Arr.take([1, 2, 3, 4, 5], 2);   // [1, 2]
Arr.take([1, 2, 3, 4, 5], -2);  // [4, 5]
```

<div id="method-arr-unique"></div>

#### `Arr.unique()`

Retourne les valeurs uniques de la liste :

``` dart
Arr.unique([1, 2, 2, 3, 1]); // [1, 2, 3]
```

<div id="method-arr-where"></div>

#### `Arr.where()`

Filtre la liste aux elements satisfaisant le predicat :

``` dart
Arr.where([1, 2, 3, 4], (n) => n.isEven); // [2, 4]
```

<div id="method-arr-wherenotnull"></div>

#### `Arr.whereNotNull()`

Retourne la liste sans les valeurs `null` :

``` dart
Arr.whereNotNull([1, null, 2, null, 3]); // [1, 2, 3]
```

<div id="method-arr-wrap"></div>

#### `Arr.wrap()`

Enveloppe une valeur dans un `List` si elle ne l'est pas deja. Retourne une liste vide quand la valeur est `null` :

``` dart
Arr.wrap('foo');     // ['foo']
Arr.wrap([1, 2]);    // [1, 2]
Arr.wrap(null);      // []
```

<div id="strings"></div>

## Chaines

<div id="method-str-after"></div>

#### `Str.after()`

Retourne la portion de `subject` apres la premiere occurrence de `search` :

``` dart
Str.after('hello world hello', 'hello'); // ' world hello'
```

<div id="method-str-afterlast"></div>

#### `Str.afterLast()`

Retourne la portion de `subject` apres la derniere occurrence de `search` :

``` dart
Str.afterLast('app/Http/Controllers', '/'); // 'Controllers'
```

<div id="method-str-before"></div>

#### `Str.before()`

Retourne la portion de `subject` avant la premiere occurrence de `search` :

``` dart
Str.before('hello world', ' '); // 'hello'
```

<div id="method-str-beforelast"></div>

#### `Str.beforeLast()`

Retourne la portion de `subject` avant la derniere occurrence de `search` :

``` dart
Str.beforeLast('app/Http/Controllers', '/'); // 'app/Http'
```

<div id="method-str-between"></div>

#### `Str.between()`

Retourne la portion de `subject` entre `from` et `to` (gourmand) :

``` dart
Str.between('[a] foo [b]', '[', ']'); // 'a] foo [b'
```

<div id="method-str-betweenfirst"></div>

#### `Str.betweenFirst()`

Retourne la plus petite portion de `subject` entre `from` et `to` :

``` dart
Str.betweenFirst('[a] foo [b]', '[', ']'); // 'a'
```

<div id="method-str-camel"></div>

#### `Str.camel()`

Convertit la valeur en `camelCase` :

``` dart
Str.camel('foo_bar');     // 'fooBar'
Str.camel('Hello world'); // 'helloWorld'
```

<div id="method-str-charat"></div>

#### `Str.charAt()`

Retourne le caractere a l'indice donne. Les indices negatifs comptent depuis la fin. Retourne `null` quand hors plage :

``` dart
Str.charAt('hello', 1);  // 'e'
Str.charAt('hello', -1); // 'o'
Str.charAt('hello', 99); // null
```

<div id="method-str-contains"></div>

#### `Str.contains()`

Determine si `haystack` contient l'un des `needles` :

``` dart
Str.contains('hello world', 'world');                       // true
Str.contains('hello world', ['cat', 'world']);              // true
Str.contains('Hello', 'hello', ignoreCase: true);           // true
```

<div id="method-str-containsall"></div>

#### `Str.containsAll()`

Determine si `haystack` contient tous les `needles` :

``` dart
Str.containsAll('hello world', ['hello', 'world']); // true
Str.containsAll('hello world', ['hello', 'cat']);   // false
```

<div id="method-str-deduplicate"></div>

#### `Str.deduplicate()`

Remplace les sequences consecutives de `character` par une seule instance :

``` dart
Str.deduplicate('hello   world');     // 'hello world'
Str.deduplicate('//path//to//', '/'); // '/path/to/'
```

<div id="method-str-endswith"></div>

#### `Str.endsWith()`

Determine si `haystack` se termine par l'un des `needles` :

``` dart
Str.endsWith('app.dart', '.dart');           // true
Str.endsWith('app.dart', ['.dart', '.ts']);  // true
```

<div id="method-str-excerpt"></div>

#### `Str.excerpt()`

Extrait un extrait de texte autour de la premiere occurrence de `phrase`. Retourne `null` quand la phrase n'est pas trouvee :

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

Termine la valeur avec une seule instance de `cap` :

``` dart
Str.finish('hello', '!');     // 'hello!'
Str.finish('hello!!!', '!');  // 'hello!'
```

<div id="method-str-headline"></div>

#### `Str.headline()`

Divise une chaine en mots sur les limites de majuscules, tirets bas, tirets et espaces, puis convertit en titre :

``` dart
Str.headline('steve_jobs');           // 'Steve Jobs'
Str.headline('EmailNotificationSent'); // 'Email Notification Sent'
```

<div id="method-str-is"></div>

#### `Str.is_()`

Determine si la valeur correspond au motif donne. Les asterisques agissent comme des jokers :

``` dart
Str.is_('foo.*', 'foo.bar');                       // true
Str.is_(['admin/*', 'user/*'], 'admin/profile');   // true
Str.is_('foo', 'bar');                              // false
```

> **Note :** Le tiret bas final evite le mot-cle reserve Dart `is`.

<div id="method-str-isascii"></div>

#### `Str.isAscii()`

Retourne `true` si la valeur ne contient que des caracteres ASCII 7 bits :

``` dart
Str.isAscii('hello');  // true
Str.isAscii('héllo');  // false
```

<div id="method-str-isjson"></div>

#### `Str.isJson()`

Retourne `true` si la valeur est un JSON valide :

``` dart
Str.isJson('{"a": 1}');   // true
Str.isJson('not json');   // false
```

<div id="method-str-isulid"></div>

#### `Str.isUlid()`

Retourne `true` si la valeur est un ULID (26 caracteres, base32 de Crockford) :

``` dart
Str.isUlid('01ARZ3NDEKTSV4RRFFQ69G5FAV'); // true
Str.isUlid('not a ulid');                  // false
```

<div id="method-str-isurl"></div>

#### `Str.isUrl()`

Retourne `true` si la valeur est une URL valide :

``` dart
Str.isUrl('https://nylo.dev'); // true
Str.isUrl('not a url');        // false
```

<div id="method-str-isuuid"></div>

#### `Str.isUuid()`

Retourne `true` si la valeur est un UUID valide (toute version) :

``` dart
Str.isUuid('a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11'); // true
Str.isUuid('hello');                                 // false
```

<div id="method-str-kebab"></div>

#### `Str.kebab()`

Convertit la valeur en `kebab-case` :

``` dart
Str.kebab('fooBar');      // 'foo-bar'
Str.kebab('Hello World'); // 'hello-world'
```

<div id="method-str-lcfirst"></div>

#### `Str.lcfirst()`

Met en minuscule le premier caractere de la valeur :

``` dart
Str.lcfirst('Hello world'); // 'hello world'
```

<div id="method-str-length"></div>

#### `Str.length()`

Retourne la longueur de la valeur :

``` dart
Str.length('hello'); // 5
```

<div id="method-str-limit"></div>

#### `Str.limit()`

Tronque la valeur au nombre de caracteres donne et ajoute un suffixe :

``` dart
Str.limit('A long sentence', 6);              // 'A long...'
Str.limit('A long sentence', 6, '... read');  // 'A long... read'
```

<div id="method-str-lower"></div>

#### `Str.lower()`

Met la valeur en minuscules :

``` dart
Str.lower('HELLO'); // 'hello'
```

<div id="method-str-mask"></div>

#### `Str.mask()`

Masque une portion de la chaine avec un caractere, a partir de `index` pendant `length` caracteres (ou jusqu'a la fin quand `length` est omis) :

``` dart
Str.mask('user@example.com', '*', 3);    // 'use*************'
Str.mask('user@example.com', '*', 3, 5); // 'use*****mple.com'
```

<div id="method-str-match"></div>

#### `Str.match()`

Retourne la premiere correspondance de l'expression reguliere `pattern` dans `subject`, ou `null` quand il n'y a pas de correspondance :

``` dart
Str.match(RegExp(r'\d+'), 'item 42 in 99 stock'); // '42'
```

> **Note :** Il s'agit d'un comparateur d'expressions regulieres — distinct du helper global [`match`](#method-match) utilise pour les recherches de valeurs basees sur `Map`.

<div id="method-str-matchall"></div>

#### `Str.matchAll()`

Retourne toutes les correspondances de l'expression reguliere `pattern` dans `subject` :

``` dart
Str.matchAll(RegExp(r'\d+'), 'item 42 in 99 stock'); // ['42', '99']
```

<div id="method-str-padboth"></div>

#### `Str.padBoth()`

Remplit les deux cotes de la valeur avec `pad` jusqu'a atteindre `length` :

``` dart
Str.padBoth('5', 5, '0'); // '00500'
```

<div id="method-str-padleft"></div>

#### `Str.padLeft()`

Remplit le cote gauche de la valeur :

``` dart
Str.padLeft('5', 3, '0'); // '005'
```

<div id="method-str-padnumber"></div>

#### `Str.padNumber()`

Remplit une chaine numerique avec des zeros a gauche :

``` dart
Str.padNumber('5', 3); // '005'
```

<div id="method-str-padright"></div>

#### `Str.padRight()`

Remplit le cote droit de la valeur :

``` dart
Str.padRight('5', 3, '0'); // '500'
```

<div id="method-str-password"></div>

#### `Str.password()`

Genere un mot de passe aleatoire cryptographiquement sur. Activez ou desactivez les classes de caracteres via les arguments nommes :

``` dart
Str.password(12);                                   // letters + numbers + symbols
Str.password(12, symbols: false);                   // letters + numbers only
Str.password(8, letters: false, symbols: false);    // digits only
```

<div id="method-str-position"></div>

#### `Str.position()`

Retourne l'indice de la premiere occurrence de `needle` dans `haystack`, ou `null` quand non trouve :

``` dart
Str.position('hello world', 'world');             // 6
Str.position('hello world hello', 'hello', offset: 1); // 12
Str.position('hello', 'cat');                      // null
```

<div id="method-str-random"></div>

#### `Str.random()`

Genere une chaine alphanumerique aleatoire cryptographiquement sure de la longueur donnee :

``` dart
Str.random();    // 16 chars, ex. 'aB3k9XzMq7VnPsRt'
Str.random(8);   // 8 chars
```

<div id="method-str-remove"></div>

#### `Str.remove()`

Supprime tout element de `search` dans `subject` :

``` dart
Str.remove('o', 'hello world');                  // 'hell wrld'
Str.remove(['e', 'l'], 'hello world');           // 'ho word'
Str.remove('Hello', 'hello world', caseSensitive: false); // ' world'
```

<div id="method-str-repeat"></div>

#### `Str.repeat()`

Repete la valeur le nombre de fois donne :

``` dart
Str.repeat('ab', 3); // 'ababab'
```

<div id="method-str-replace"></div>

#### `Str.replace()`

Remplace chaque occurrence de `search` par `replace` dans `subject` :

``` dart
Str.replace('o', '0', 'hello world');           // 'hell0 w0rld'
Str.replace(['o', 'l'], '*', 'hello world');     // 'he*** w*r*d'
```

<div id="method-str-replacefirst"></div>

#### `Str.replaceFirst()`

Remplace la premiere occurrence de `search` par `replace` :

``` dart
Str.replaceFirst('hello', 'hi', 'hello hello'); // 'hi hello'
```

<div id="method-str-replacelast"></div>

#### `Str.replaceLast()`

Remplace la derniere occurrence de `search` par `replace` :

``` dart
Str.replaceLast('hello', 'hi', 'hello hello'); // 'hello hi'
```

<div id="method-str-reverse"></div>

#### `Str.reverse()`

Inverse la valeur :

``` dart
Str.reverse('hello'); // 'olleh'
```

<div id="method-str-slug"></div>

#### `Str.slug()`

Genere un slug adapte aux URLs a partir du titre :

``` dart
Str.slug('Hello World');                                  // 'hello-world'
Str.slug('Tech & Code', dictionary: {'&': 'and'});        // 'tech-and-code'
Str.slug('Hello World', separator: '_');                  // 'hello_world'
```

<div id="method-str-snake"></div>

#### `Str.snake()`

Convertit la valeur en `snake_case`. Passez un delimiteur personnalise pour utiliser autre chose que `_` :

``` dart
Str.snake('helloWorld');         // 'hello_world'
Str.snake('helloWorld', '-');    // 'hello-world'
```

<div id="method-str-squish"></div>

#### `Str.squish()`

Supprime les espaces de debut et de fin et reduit les espaces internes en espaces simples :

``` dart
Str.squish('   hello    world   '); // 'hello world'
```

<div id="method-str-start"></div>

#### `Str.start()`

Commence la valeur avec une seule instance de `prefix` :

``` dart
Str.start('this/string', '/');     // '/this/string'
Str.start('//this/string', '/');   // '/this/string'
```

<div id="method-str-startswith"></div>

#### `Str.startsWith()`

Determine si `haystack` commence par l'un des `needles` :

``` dart
Str.startsWith('hello world', 'hello');           // true
Str.startsWith('hello world', ['cat', 'hello']);  // true
```

<div id="method-str-studly"></div>

#### `Str.studly()`

Convertit la valeur en `StudlyCase` / `PascalCase` :

``` dart
Str.studly('foo_bar');     // 'FooBar'
Str.studly('hello world'); // 'HelloWorld'
```

<div id="method-str-substr"></div>

#### `Str.substr()`

Retourne une sous-chaine a partir de `start` pendant `length` caracteres. Un `start` negatif compte depuis la fin :

``` dart
Str.substr('profile', 4);       // 'ile'
Str.substr('profile', 4, 2);    // 'il'
Str.substr('profile', -3);      // 'ile'
```

<div id="method-str-substrcount"></div>

#### `Str.substrCount()`

Compte les occurrences non chevauchantes de `needle` dans `haystack` :

``` dart
Str.substrCount('hello hello hello', 'hello'); // 3
```

<div id="method-str-swap"></div>

#### `Str.swap()`

Remplace plusieurs sous-chaines a l'aide d'une `Map` de paires recherche → remplacement :

``` dart
Str.swap({'foo': 'bar', 'hello': 'hi'}, 'hello foo'); // 'hi bar'
```

<div id="method-str-take"></div>

#### `Str.take()`

Retourne les premiers `limit` caracteres de la valeur. Une limite negative retourne depuis la fin :

``` dart
Str.take('Build something amazing!', 5); // 'Build'
Str.take('Build something amazing!', -9); // ' amazing!'
```

<div id="method-str-title"></div>

#### `Str.title()`

Convertit la valeur en `Titre` :

``` dart
Str.title('a nice title'); // 'A Nice Title'
```

<div id="method-str-ucfirst"></div>

#### `Str.ucfirst()`

Met en majuscule le premier caractere de la valeur :

``` dart
Str.ucfirst('hello world'); // 'Hello world'
```

<div id="method-str-ucsplit"></div>

#### `Str.ucsplit()`

Divise la valeur en mots sur les limites de majuscules :

``` dart
Str.ucsplit('fooBarBaz'); // ['foo', 'Bar', 'Baz']
```

<div id="method-str-ulid"></div>

#### `Str.ulid()`

Genere un ULID (Identifiant Universel Unique Lexicographiquement Triable) :

``` dart
Str.ulid(); // ex. '01ARZ3NDEKTSV4RRFFQ69G5FAV'
```

<div id="method-str-unwrap"></div>

#### `Str.unwrap()`

Supprime une seule instance de `before` au debut et de `after` (ou `before` si `after` est null) a la fin :

``` dart
Str.unwrap('"value"', '"');             // 'value'
Str.unwrap('<p>html</p>', '<p>', '</p>'); // 'html'
```

<div id="method-str-upper"></div>

#### `Str.upper()`

Met la valeur en majuscules :

``` dart
Str.upper('hello'); // 'HELLO'
```

<div id="method-str-uuid"></div>

#### `Str.uuid()`

Genere un UUID v4 aleatoire :

``` dart
Str.uuid(); // ex. 'a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11'
```

<div id="method-str-uuid7"></div>

#### `Str.uuid7()`

Genere un UUID v7 (ordonne dans le temps, RFC 9562). Les UUIDv7 sont triables par heure de creation :

``` dart
Str.uuid7(); // ex. '018e0dc4-8b3f-7a2c-9d01-b1f6c87a92e3'
```

<div id="method-str-wordcount"></div>

#### `Str.wordCount()`

Retourne le nombre de mots de la valeur :

``` dart
Str.wordCount('hello there friend'); // 3
```

<div id="method-str-words"></div>

#### `Str.words()`

Limite la valeur au nombre de mots donne :

``` dart
Str.words('Perfectly balanced, as all things should be.', 3);
// 'Perfectly balanced, as...'
```

<div id="method-str-wrap"></div>

#### `Str.wrap()`

Enveloppe la valeur avec `before` et `after`. Quand `after` est omis, `before` est utilise des deux cotes :

``` dart
Str.wrap('value', '"');             // '"value"'
Str.wrap('html', '<p>', '</p>');    // '<p>html</p>'
```

<div id="numbers"></div>

## Nombres

<div id="method-number-abbreviate"></div>

#### `Number.abbreviate()`

Retourne une forme abregee du nombre (`1K`, `1M`, `1B`) :

``` dart
Number.abbreviate(1500);                  // '2K'
Number.abbreviate(1500, maxPrecision: 1); // '1.5K'
Number.abbreviate(2_500_000);             // '3M'
```

<div id="method-number-average"></div>

#### `Number.average()`

Retourne la moyenne arithmetique des valeurs, ou `0` quand elles sont vides :

``` dart
Number.average([1, 2, 3, 4]); // 2.5
```

<div id="method-number-between"></div>

#### `Number.between()`

Retourne `true` quand la valeur est entre `min` et `max` (inclusif) :

``` dart
Number.between(5, min: 0, max: 10);  // true
Number.between(15, min: 0, max: 10); // false
```

<div id="method-number-ceil"></div>

#### `Number.ceil()`

Arrondit la valeur au plafond avec la precision donnee :

``` dart
Number.ceil(1.2);    // 2.0
Number.ceil(1.234, 2); // 1.24
```

<div id="method-number-clamp"></div>

#### `Number.clamp()`

Bride une valeur entre `min` et `max` (inclusif) :

``` dart
Number.clamp(5, 0, 10);   // 5
Number.clamp(15, 0, 10);  // 10
Number.clamp(-1, 0, 10);  // 0
```

<div id="method-number-currency"></div>

#### `Number.currency()`

Formate le nombre comme valeur monetaire. Utilise par defaut la locale et la devise configurees :

``` dart
Number.currency(1234.56);                            // '$1,234.56'
Number.currency(1234.56, currency: 'EUR');           // '€1,234.56'
Number.currency(1234.56, locale: 'de_DE', currency: 'EUR');
// '1.234,56 €'
```

<div id="method-number-defaultcurrency"></div>

#### `Number.defaultCurrency()`

Retourne le code devise par defaut utilise par les methodes de formatage :

``` dart
Number.defaultCurrency(); // 'USD'
```

<div id="method-number-defaultlocale"></div>

#### `Number.defaultLocale()`

Retourne la locale par defaut utilisee par les methodes de formatage :

``` dart
Number.defaultLocale(); // 'en_US'
```

<div id="method-number-degrees"></div>

#### `Number.degrees()`

Convertit des radians en degres :

``` dart
Number.degrees(3.14159);  // ~180.0
```

<div id="method-number-duration"></div>

#### `Number.duration()`

Formate un nombre de secondes en duree lisible. La forme courte retourne `'1h 2m 5s'` ; la forme longue retourne `'1:02:05'` :

``` dart
Number.duration(3661);                  // '1h 1m 1s'
Number.duration(3661, short: false);    // '1:01:01'
Number.duration(45);                    // '45s'
```

<div id="method-number-filesize"></div>

#### `Number.fileSize()`

Formate un nombre d'octets en taille de fichier lisible :

``` dart
Number.fileSize(1024);                       // '1 KB'
Number.fileSize(1500, maxPrecision: 1);      // '1.5 KB'
Number.fileSize(1024 * 1024 * 1024);         // '1 GB'
```

<div id="method-number-floor"></div>

#### `Number.floor()`

Arrondit la valeur au plancher avec la precision donnee :

``` dart
Number.floor(1.7);      // 1.0
Number.floor(1.234, 2); // 1.23
```

<div id="method-number-forhumans"></div>

#### `Number.forHumans()`

Retourne une forme lisible du nombre (`1 thousand`, `1 million`) :

``` dart
Number.forHumans(1500);                  // '2 thousand'
Number.forHumans(1500, maxPrecision: 1); // '1.5 thousand'
Number.forHumans(2_500_000);             // '3 million'
```

<div id="method-number-format"></div>

#### `Number.format()`

Formate le nombre avec des separateurs adaptes a la locale :

``` dart
Number.format(1234567);                          // '1,234,567'
Number.format(1234.5, precision: 2);             // '1,234.50'
Number.format(1234567, locale: 'de_DE');         // '1.234.567'
```

<div id="method-number-gcd"></div>

#### `Number.gcd()`

Plus grand commun diviseur de deux entiers :

``` dart
Number.gcd(12, 18); // 6
```

<div id="method-number-lcm"></div>

#### `Number.lcm()`

Plus petit commun multiple de deux entiers :

``` dart
Number.lcm(4, 6); // 12
```

<div id="method-number-lerp"></div>

#### `Number.lerp()`

Interpole lineairement entre `a` et `b` par `t` (`0..1`) :

``` dart
Number.lerp(0, 100, 0.25); // 25.0
```

<div id="method-number-max"></div>

#### `Number.max()`

Retourne la plus grande valeur de l'iterable :

``` dart
Number.max([3, 1, 4, 1, 5, 9, 2, 6]); // 9
```

<div id="method-number-median"></div>

#### `Number.median()`

Retourne la mediane des valeurs, ou `0` quand elles sont vides :

``` dart
Number.median([1, 3, 5]);    // 3.0
Number.median([1, 2, 3, 4]); // 2.5
```

<div id="method-number-min"></div>

#### `Number.min()`

Retourne la plus petite valeur de l'iterable :

``` dart
Number.min([3, 1, 4, 1, 5, 9, 2, 6]); // 1
```

<div id="method-number-ordinal"></div>

#### `Number.ordinal()`

Retourne la forme ordinale du nombre (`1st`, `2nd`, `3rd`, `4th`) :

``` dart
Number.ordinal(1);   // '1st'
Number.ordinal(2);   // '2nd'
Number.ordinal(21);  // '21st'
Number.ordinal(112); // '112th'
```

<div id="method-number-pairs"></div>

#### `Number.pairs()`

Genere des paires (sous-plages) d'une plage jusqu'a `to`, par pas de `by` :

``` dart
Number.pairs(25, 10);             // [[0, 9], [10, 19], [20, 25]]
Number.pairs(25, 10, offset: 0);  // [[0, 10], [10, 20], [20, 25]]
```

<div id="method-number-parsefloat"></div>

#### `Number.parseFloat()`

Analyse une chaine adaptee a la locale en `double`. Retourne `null` en cas d'echec :

``` dart
Number.parseFloat('1,234.56');                    // 1234.56
Number.parseFloat('1.234,56', locale: 'de_DE');   // 1234.56
Number.parseFloat('not a number');                // null
```

<div id="method-number-parseint"></div>

#### `Number.parseInt()`

Analyse une chaine adaptee a la locale en `int`. Retourne `null` en cas d'echec :

``` dart
Number.parseInt('1,234');                    // 1234
Number.parseInt('1.234', locale: 'de_DE');   // 1234
```

<div id="method-number-percentage"></div>

#### `Number.percentage()`

Formate le nombre en pourcentage. Le nombre est traite comme la valeur de pourcentage entiere :

``` dart
Number.percentage(10);                  // '10%'
Number.percentage(10.5, precision: 1);  // '10.5%'
```

<div id="method-number-radians"></div>

#### `Number.radians()`

Convertit des degres en radians :

``` dart
Number.radians(180); // ~3.14159
```

<div id="method-number-random"></div>

#### `Number.random()`

Retourne un entier aleatoire dans `[min, max]` (inclusif). Passez `seed` pour une sequence deterministe :

``` dart
Number.random(min: 1, max: 100);              // n'importe quel entier entre 1..100
Number.random(min: 0, max: 9, seed: 42);      // deterministe
```

<div id="method-number-range"></div>

#### `Number.range()`

Genere une liste d'entiers de `start` (inclusif) a `end` (inclusif), par pas de `step` :

``` dart
Number.range(1, 5);              // [1, 2, 3, 4, 5]
Number.range(0, 10, step: 2);    // [0, 2, 4, 6, 8, 10]
Number.range(5, 1, step: -1);    // [5, 4, 3, 2, 1]
```

<div id="method-number-round"></div>

#### `Number.round()`

Arrondit la valeur a la precision donnee :

``` dart
Number.round(1.5);      // 2.0
Number.round(1.234, 2); // 1.23
```

<div id="method-number-scale"></div>

#### `Number.scale()`

Remappez une valeur d'une plage a une autre :

``` dart
Number.scale(0.5, fromMin: 0, fromMax: 1, toMin: 0, toMax: 100); // 50.0
Number.scale(75, fromMin: 0, fromMax: 100, toMin: -1, toMax: 1); // 0.5
```

<div id="method-number-spell"></div>

#### `Number.spell()`

Ecrit le nombre en lettres en anglais :

``` dart
Number.spell(0);           // 'zero'
Number.spell(123);         // 'one hundred twenty-three'
Number.spell(1_234_567);   // 'one million two hundred thirty-four thousand five hundred sixty-seven'
```

<div id="method-number-spellordinal"></div>

#### `Number.spellOrdinal()`

Ecrit la forme ordinale du nombre en lettres en anglais :

``` dart
Number.spellOrdinal(1);   // 'first'
Number.spellOrdinal(2);   // 'second'
Number.spellOrdinal(21);  // 'twenty-first'
```

<div id="method-number-sum"></div>

#### `Number.sum()`

Retourne la somme des valeurs :

``` dart
Number.sum([1, 2, 3]); // 6
```

<div id="method-number-tobytes"></div>

#### `Number.toBytes()`

Convertit une taille de fichier en octets. Inverse de [`fileSize`](#method-number-filesize). Passez soit une chaine analysable soit un nombre avec `unit` :

``` dart
Number.toBytes('1.5 KB');     // 1536
Number.toBytes(1.5, 'KB');    // 1536
Number.toBytes(1, 'GB');      // 1073741824
Number.toBytes('not bytes');  // null
```

<div id="method-number-trim"></div>

#### `Number.trim()`

Supprime les zeros de fin de la partie decimale de la valeur :

``` dart
Number.trim(12.0);    // '12'
Number.trim(12.30);   // '12.3'
Number.trim(12.345);  // '12.345'
```

<div id="method-number-usecurrency"></div>

#### `Number.useCurrency()`

Definit le code devise par defaut utilise par les methodes de formatage :

``` dart
Number.useCurrency('EUR');
Number.currency(99); // '€99.00'
```

<div id="method-number-uselocale"></div>

#### `Number.useLocale()`

Definit la locale par defaut utilisee par les methodes de formatage :

``` dart
Number.useLocale('de_DE');
Number.format(1234567); // '1.234.567'
```

<div id="objects"></div>

## Objets

`Obj` fournit des utilitaires pour travailler avec des maps en utilisant des chemins en **notation pointee**. Les elements de liste peuvent etre adresses par indice entier (ex. `'users.0.name'`).

<div id="method-obj-add"></div>

#### `Obj.add()`

Ajoute une valeur a `key` uniquement quand aucune valeur n'existe deja :

``` dart
final user = {'name': 'Anna'};
Obj.add(user, 'role', 'admin');
// user: {'name': 'Anna', 'role': 'admin'}

Obj.add(user, 'name', 'Brad'); // sans effet — name deja defini
```

<div id="method-obj-deepequals"></div>

#### `Obj.deepEquals()`

Retourne `true` quand deux valeurs sont structurellement egales (comparaison recursive de `Map`s et `List`s) :

``` dart
Obj.deepEquals({'a': [1, 2]}, {'a': [1, 2]}); // true
Obj.deepEquals({'a': 1}, {'a': 2});            // false
```

<div id="method-obj-divide"></div>

#### `Obj.divide()`

Divise une map en une paire `[cles, valeurs]` :

``` dart
Obj.divide({'name': 'Desk', 'price': 100});
// [['name', 'price'], ['Desk', 100]]
```

<div id="method-obj-dot"></div>

#### `Obj.dot()`

Aplatit une map imbriquee en une map a un seul niveau indexee par des chemins pointes :

``` dart
Obj.dot({'a': {'b': 1, 'c': 2}});
// {'a.b': 1, 'a.c': 2}
```

<div id="method-obj-except"></div>

#### `Obj.except()`

Retourne une nouvelle map contenant toutes les entrees sauf celles avec les cles donnees :

``` dart
Obj.except({'name': 'Anna', 'role': 'admin', 'age': 30}, ['age']);
// {'name': 'Anna', 'role': 'admin'}
```

<div id="method-obj-exists"></div>

#### `Obj.exists()`

Retourne `true` quand la map a la cle donnee au niveau superieur (sans traversee en notation pointee) :

``` dart
Obj.exists({'a.b': 1}, 'a.b'); // true
Obj.exists({'a': {'b': 1}}, 'a.b'); // false (utilisez `has` pour la traversee)
```

<div id="method-obj-flip"></div>

#### `Obj.flip()`

Echange cles et valeurs. En cas de collision de valeurs, la derniere entree l'emporte :

``` dart
Obj.flip({'one': 1, 'two': 2}); // {1: 'one', 2: 'two'}
```

<div id="method-obj-forget"></div>

#### `Obj.forget()`

Supprime une cle (notation pointee) de la map. Retourne la map pour le chainage :

``` dart
final user = {'profile': {'name': 'Anna', 'email': 'a@b'}};
Obj.forget(user, 'profile.email');
// user: {'profile': {'name': 'Anna'}}
```

<div id="method-obj-get"></div>

#### `Obj.get()`

Retourne la valeur a `key` en notation pointee, ou la valeur par defaut quand le chemin est absent. Les cles de niveau superieur contenant des points litteraux ont priorite sur la traversee :

``` dart
final user = {'profile': {'name': 'Anna', 'age': 30}};
Obj.get(user, 'profile.name');     // 'Anna'
Obj.get(user, 'profile.email');    // null
Obj.get(user, 'profile.email', 'unknown'); // 'unknown'

// Indices de liste :
Obj.get({'users': [{'name': 'A'}]}, 'users.0.name'); // 'A'
```

<div id="method-obj-getbool"></div>

#### `Obj.getBool()`

Retourne la valeur a `key` convertie en `bool`, ou la valeur par defaut. Les nombres sont vrais quand non nuls ; les chaines reconnaissent `'true'`/`'false'`/`'1'`/`'0'`/`'yes'`/`'no'`/`'on'`/`'off'` (insensible a la casse) :

``` dart
Obj.getBool({'flag': 'yes'}, 'flag');     // true
Obj.getBool({'flag': 0}, 'flag');         // false
Obj.getBool({}, 'flag', false);           // false
```

<div id="method-obj-getdouble"></div>

#### `Obj.getDouble()`

Retourne la valeur a `key` convertie en `double`, ou la valeur par defaut :

``` dart
Obj.getDouble({'price': '9.99'}, 'price'); // 9.99
Obj.getDouble({'price': 10}, 'price');     // 10.0
```

<div id="method-obj-getint"></div>

#### `Obj.getInt()`

Retourne la valeur a `key` convertie en `int`, ou la valeur par defaut :

``` dart
Obj.getInt({'count': '42'}, 'count');     // 42
Obj.getInt({'count': 3.7}, 'count');      // 3 (tronque)
Obj.getInt({}, 'count', 0);               // 0
```

<div id="method-obj-getlist"></div>

#### `Obj.getList()`

Retourne la valeur a `key` quand c'est un `List`, sinon la valeur par defaut :

``` dart
Obj.getList({'items': [1, 2, 3]}, 'items'); // [1, 2, 3]
Obj.getList({'items': 'oops'}, 'items', []); // []
```

<div id="method-obj-getmap"></div>

#### `Obj.getMap()`

Retourne la valeur a `key` quand c'est un `Map`, sinon la valeur par defaut :

``` dart
Obj.getMap({'meta': {'a': 1}}, 'meta'); // {'a': 1}
Obj.getMap({'meta': null}, 'meta', {}); // {}
```

<div id="method-obj-getstring"></div>

#### `Obj.getString()`

Retourne la valeur a `key` convertie en `String`, ou la valeur par defaut :

``` dart
Obj.getString({'name': 'Anna'}, 'name'); // 'Anna'
Obj.getString({'count': 42}, 'count');   // '42'
Obj.getString({}, 'name', 'Guest');      // 'Guest'
```

<div id="method-obj-has"></div>

#### `Obj.has()`

Retourne `true` quand la map a une valeur a `key` en notation pointee :

``` dart
final user = {'profile': {'name': 'Anna'}};
Obj.has(user, 'profile.name');  // true
Obj.has(user, 'profile.email'); // false
```

<div id="method-obj-hasall"></div>

#### `Obj.hasAll()`

Retourne `true` quand chacune des cles donnees se resout dans la map :

``` dart
Obj.hasAll({'a': 1, 'b': 2}, ['a', 'b']);      // true
Obj.hasAll({'a': 1, 'b': 2}, ['a', 'c']);      // false
```

<div id="method-obj-hasany"></div>

#### `Obj.hasAny()`

Retourne `true` quand l'une des cles donnees se resout dans la map :

``` dart
Obj.hasAny({'a': 1}, ['a', 'b']); // true
Obj.hasAny({'a': 1}, ['x', 'y']); // false
```

<div id="method-obj-mapkeys"></div>

#### `Obj.mapKeys()`

Retourne une nouvelle map avec chaque cle transformee par `fn` :

``` dart
Obj.mapKeys({'firstName': 'Anna'}, (k) => k.toLowerCase());
// {'firstname': 'Anna'}
```

<div id="method-obj-mapvalues"></div>

#### `Obj.mapValues()`

Retourne une nouvelle map avec chaque valeur transformee par `fn` :

``` dart
Obj.mapValues({'a': 1, 'b': 2}, (v) => v * 10);
// {'a': 10, 'b': 20}
```

<div id="method-obj-merge"></div>

#### `Obj.merge()`

Fusionne recursivement `source` dans `target`. En cas de collision de cles, `source` l'emporte ; les maps imbriquees sont fusionnees :

``` dart
Obj.merge(
  {'a': {'x': 1}, 'b': 2},
  {'a': {'y': 9}, 'b': 22},
);
// {'a': {'x': 1, 'y': 9}, 'b': 22}
```

<div id="method-obj-only"></div>

#### `Obj.only()`

Retourne une nouvelle map contenant uniquement les entrees avec les cles donnees :

``` dart
Obj.only({'name': 'Anna', 'role': 'admin', 'age': 30}, ['name', 'role']);
// {'name': 'Anna', 'role': 'admin'}
```

<div id="method-obj-prependkeyswith"></div>

#### `Obj.prependKeysWith()`

Prefixe chaque cle de niveau superieur de la map avec le prefixe donne :

``` dart
Obj.prependKeysWith({'name': 'Anna', 'age': 30}, 'user_');
// {'user_name': 'Anna', 'user_age': 30}
```

<div id="method-obj-pull"></div>

#### `Obj.pull()`

Retourne la valeur a `key` et la supprime de la map :

``` dart
final user = {'name': 'Anna', 'temp': 'token'};
final temp = Obj.pull(user, 'temp'); // 'token'
// user: {'name': 'Anna'}
```

<div id="method-obj-query"></div>

#### `Obj.query()`

Encode une map en chaine de requete URL :

``` dart
Obj.query({'name': 'Anna', 'tags': ['a', 'b']});
// 'name=Anna&tags%5B0%5D=a&tags%5B1%5D=b'

Obj.query({'filter': {'role': 'admin'}});
// 'filter%5Brole%5D=admin'
```

<div id="method-obj-set"></div>

#### `Obj.set()`

Definit une valeur a `key`, en creant des maps imbriquees le long du chemin. Mute la map en place et la retourne pour le chainage :

``` dart
final user = <String, dynamic>{};
Obj.set(user, 'profile.email', 'a@b');
// user: {'profile': {'email': 'a@b'}}
```

<div id="method-obj-undot"></div>

#### `Obj.undot()`

Gonfle une map a cles pointees en une structure imbriquee (inverse de [`dot`](#method-obj-dot)) :

``` dart
Obj.undot({'a.b': 1, 'a.c': 2});
// {'a': {'b': 1, 'c': 2}}
```

<div id="method-obj-wherenotempty"></div>

#### `Obj.whereNotEmpty()`

Retourne une nouvelle map sans les entrees dont les valeurs sont `null` ou vides (`String`, `Iterable` ou `Map` vides) :

``` dart
Obj.whereNotEmpty({'name': 'Anna', 'tags': [], 'bio': ''});
// {'name': 'Anna'}
```

<div id="method-obj-wherenotnull"></div>

#### `Obj.whereNotNull()`

Retourne une nouvelle map sans les entrees dont les valeurs sont `null` :

``` dart
Obj.whereNotNull({'name': 'Anna', 'email': null});
// {'name': 'Anna'}
```

<div id="miscellaneous"></div>

## Divers

Ces fonctions globales se trouvent dans `helper.dart` et sont disponibles partout ou vous importez `nylo_framework`.

<div id="method-api"></div>

#### `api()`

Helper de commodite pour envoyer des requetes API via un `NyApiService`. Consultez la page [Networking](/docs/{{ $version }}/networking) pour toutes les options :

``` dart
await api<ApiService>((request) =>
  request.get('https://jsonplaceholder.typicode.com/posts'));
```

<div id="method-datatomodel"></div>

#### `dataToModel()`

Convertit des donnees brutes (generalement une `Map` JSON) en un modele enregistre dans `config/decoders.dart`. Le parametre de type est obligatoire :

``` dart
final user = dataToModel<User>(data: {'name': 'Anna', 'age': 30});
```

<div id="method-dump"></div>

#### `dump()`

Affiche une valeur dans la console. Respecte le drapeau `APP_DEBUG` de l'application sauf si `alwaysPrint: true` :

``` dart
dump('Hello World');
dump(user, tag: 'current user');
dump(payload, alwaysPrint: true);
```

<div id="method-event"></div>

#### `event()`

Declenche un evenement du type donne en utilisant `Nylo.events()`. Consultez [Events](/docs/{{ $version }}/events) pour l'utilisation complete :

``` dart
await event<LoginEvent>(data: {'user': 'Anna'});
```

<div id="method-getasset"></div>

#### `getAsset()`

Retourne le chemin complet d'une ressource dans le repertoire `/assets` :

``` dart
final path = getAsset('videos/welcome.mp4');
// 'assets/videos/welcome.mp4'
```

<div id="method-getenv"></div>

#### `getEnv()`

Retourne une valeur de votre env genere (`env.g.dart`). Retourne la valeur par defaut quand la cle n'est pas definie :

``` dart
final apiUrl = getEnv('API_URL');
final debug = getEnv('APP_DEBUG', defaultValue: false);
```

<div id="method-getimageasset"></div>

#### `getImageAsset()`

Retourne le chemin complet d'une image dans `/assets/images/` :

``` dart
Image.asset(getImageAsset('logo.png'));
```

<div id="method-loadjson"></div>

#### `loadJson()`

Charge un fichier JSON depuis votre bundle d'assets. Les resultats sont mis en cache par defaut :

``` dart
final config = await loadJson<Map<String, dynamic>>('config/app.json');
```

<div id="method-match"></div>

#### `match()`

Compare une valeur a une `Map` de cas et retourne la valeur correspondante. Leve une exception quand aucun cas ne correspond et qu'aucun `defaultValue` n'est fourni :

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

> **Note :** Il s'agit d'une recherche de valeur basee sur `Map` — distincte de [`Str.match`](#method-str-match), qui effectue une correspondance par expression reguliere.

<div id="method-now"></div>

#### `now()`

Retourne le `DateTime` actuel :

``` dart
final timestamp = now();
```

<div id="method-nyhexcolor"></div>

#### `nyHexColor()`

Convertit une chaine de couleur hexadecimale en `Color`. Accepte des valeurs de 6 ou 8 chiffres, avec ou sans `#` initial :

``` dart
final brand = nyHexColor('#FF6B35');
final translucent = nyHexColor('80FF6B35'); // 8 chiffres (alpha en premier)
```

<div id="method-print-helpers"></div>

#### `printDebug()`, `printError()`, `printInfo()`, `printWarning()`, `printSuccess()`, `printVerbose()`, `printAlert()`, `printEmergency()`

Enregistreurs de console avec etiquette de severite. Consultez la page [Logging](/docs/{{ $version }}/logging) pour le format de sortie et le drapeau `alwaysPrint` :

``` dart
printInfo('User signed in');
printError('Payment failed', context: {'order_id': 42});
printDebug({'state': state});
```

<div id="method-badge-helpers"></div>

#### `setBadgeNumber()`, `clearBadgeNumber()`

Definit ou efface le compteur de badge de l'icone de l'application. Consultez [Local Notifications](/docs/{{ $version }}/local-notifications) pour les details de configuration par plateforme :

``` dart
await setBadgeNumber(3);
await clearBadgeNumber();
```

<div id="method-shownextlog"></div>

#### `showNextLog()`

Force le prochain journal `NyLogger` a s'afficher meme quand `APP_DEBUG` est `false`. Utile pour les diagnostics ponctuels dans les versions de production :

``` dart
showNextLog();
NyLogger.info('This will print regardless of APP_DEBUG');
```

<div id="method-sleep"></div>

#### `sleep()`

Retarde l'execution pour la duree donnee. Le premier argument est en secondes ; un deuxieme argument optionnel ajoute des microsecondes :

``` dart
await sleep(2);              // 2 secondes
await sleep(0, 500_000);     // 500 millisecondes
```

<div id="method-trans"></div>

#### `trans()`

Retourne une chaine traduite par cle. Consultez [Localization](/docs/{{ $version }}/localization) pour la configuration :

``` dart
final greeting = trans('home.welcome');
final personalized = trans('home.hello', arguments: {'name': 'Anna'});
```
