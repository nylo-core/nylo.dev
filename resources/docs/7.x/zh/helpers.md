# 辅助函数

<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- [可用方法](#available-methods "可用方法")
  - [数组](#arrays-method-list "数组")
  - [字符串](#strings-method-list "字符串")
  - [数字](#numbers-method-list "数字")
  - [对象](#objects-method-list "对象")
  - [其他](#miscellaneous-method-list "其他")
- [数组](#arrays "数组")
- [字符串](#strings "字符串")
- [数字](#numbers "数字")
- [对象](#objects "对象")
- [其他](#miscellaneous "其他")

<div id="introduction"></div>

## 简介

{{ config('app.name') }} 附带一组静态工具类 — `Arr`、`Str`、`Number` 和 `Obj` — 以及用于常见任务的全局辅助函数。它们都从 `nylo_framework` 导出：

``` dart
import 'package:nylo_framework/nylo_framework.dart';

Arr.first([1, 2, 3]);             // 1
Str.slug('Hello World');          // 'hello-world'
Number.currency(1234.56);         // '$1,234.56'
Obj.get(user, 'profile.name');    // 'Anna'
```

> **注意：** `Backpack`、`NyStorage`、`NyCache` 和 `NyLogger` 有各自的专属页面：[Backpack](/docs/{{ $version }}/backpack)、[Storage](/docs/{{ $version }}/storage)、[Cache](/docs/{{ $version }}/cache)、[Logging](/docs/{{ $version }}/logging)。

<div id="available-methods"></div>

## 可用方法

<div id="arrays-method-list"></div>

### 数组

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

### 字符串

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

### 数字

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

### 对象

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

### 其他

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

## 数组

<div id="method-arr-accessible"></div>

#### `Arr.accessible()`

判断给定值是否可以以数组方式访问（`List` 或 `Map`）：

``` dart
Arr.accessible([1, 2, 3]);  // true
Arr.accessible({'a': 1});   // true
Arr.accessible('hello');    // false
Arr.accessible(null);       // false
```

<div id="method-arr-average"></div>

#### `Arr.average()`

返回列表的算术平均值，为空时返回 `0`。传入 `by` 可从每个元素中提取数字：

``` dart
Arr.average([2, 4, 6]);                // 4.0
Arr.average(orders, by: (o) => o.total); // 总额的平均值
```

<div id="method-arr-chunk"></div>

#### `Arr.chunk()`

将列表分割为给定大小的块：

``` dart
Arr.chunk([1, 2, 3, 4, 5], 2);
// [[1, 2], [3, 4], [5]]
```

<div id="method-arr-collapse"></div>

#### `Arr.collapse()`

将列表的列表折叠为单个列表：

``` dart
Arr.collapse([[1, 2], [3, 4], [5]]);
// [1, 2, 3, 4, 5]
```

<div id="method-arr-countby"></div>

#### `Arr.countBy()`

统计列表中的出现次数，可选择通过值提取器分组：

``` dart
Arr.countBy(['a', 'b', 'a', 'c']);
// {'a': 2, 'b': 1, 'c': 1}

Arr.countBy(orders, by: (o) => o.status);
// {'paid': 12, 'pending': 3}
```

<div id="method-arr-crossjoin"></div>

#### `Arr.crossJoin()`

交叉连接给定的列表，返回所有可能的组合：

``` dart
Arr.crossJoin([[1, 2], ['a', 'b']]);
// [[1, 'a'], [1, 'b'], [2, 'a'], [2, 'b']]
```

<div id="method-arr-every"></div>

#### `Arr.every()`

当列表中每个元素都通过条件时返回 `true`。空列表恒为真：

``` dart
Arr.every([2, 4, 6], (n) => n.isEven); // true
Arr.every([1, 2, 3], (n) => n.isEven); // false
```

<div id="method-arr-exceptvalues"></div>

#### `Arr.exceptValues()`

返回去除了给定值的列表：

``` dart
Arr.exceptValues([1, 2, 3, 4], [2, 4]); // [1, 3]
```

<div id="method-arr-first"></div>

#### `Arr.first()`

返回与条件匹配的第一个元素，或默认值：

``` dart
Arr.first([1, 2, 3]);                                   // 1
Arr.first([1, 2, 3, 4], predicate: (n) => n.isEven);    // 2
Arr.first([], defaultValue: 99);                        // 99
```

<div id="method-arr-flatmap"></div>

#### `Arr.flatMap()`

通过 `fn` 映射每个元素并将结果可迭代对象展平：

``` dart
Arr.flatMap(pages, (p) => p.items); // 所有页面的所有项目
Arr.flatMap([1, 2, 3], (n) => [n, n * 10]); // [1, 10, 2, 20, 3, 30]
```

<div id="method-arr-flatten"></div>

#### `Arr.flatten()`

将嵌套的可迭代对象展平为单层列表。`depth` 限制展平深度，`-1` 表示无限制：

``` dart
Arr.flatten([1, [2, [3]]]);              // [1, 2, 3]
Arr.flatten([1, [2, [3]]], depth: 1);    // [1, 2, [3]]
```

<div id="method-arr-groupby"></div>

#### `Arr.groupBy()`

以 `by` 返回的值为键将列表分组为映射：

``` dart
Arr.groupBy(messages, (m) => m.date);
// {2024-01-01: [...], 2024-01-02: [...]}
```

<div id="method-arr-indexed"></div>

#### `Arr.indexed()`

将每个元素与其索引配对，返回 `(index, value)` 记录的列表：

``` dart
for (final (i, v) in Arr.indexed(['a', 'b', 'c'])) {
  print('$i: $v'); // '0: a', '1: b', '2: c'
}
```

<div id="method-arr-interleave"></div>

#### `Arr.interleave()`

在每对元素之间插入分隔符。适用于构建带分隔符的组件子元素：

``` dart
Arr.interleave([1, 2, 3], 0); // [1, 0, 2, 0, 3]

Column(children: Arr.interleave(tiles, const Divider()));
```

<div id="method-arr-isassoc"></div>

#### `Arr.isAssoc()`

当值为 `Map`（关联数组）时返回 `true`：

``` dart
Arr.isAssoc({'a': 1});  // true
Arr.isAssoc([1, 2, 3]); // false
```

<div id="method-arr-islist"></div>

#### `Arr.isList()`

当值为 `List` 时返回 `true`：

``` dart
Arr.isList([1, 2, 3]); // true
Arr.isList({'a': 1});  // false
```

<div id="method-arr-join"></div>

#### `Arr.join()`

将列表连接为字符串。最后一个元素可以使用不同的分隔符连接：

``` dart
Arr.join(['Anna', 'Brad', 'Carol'], ', ', ' and ');
// 'Anna, Brad and Carol'

Arr.join(['a', 'b', 'c']);
// 'a, b, c'
```

<div id="method-arr-keyby"></div>

#### `Arr.keyBy()`

按键的值对列表进行索引。该键值为 `null` 的条目将被跳过：

``` dart
Arr.keyBy([
  {'id': 1, 'name': 'Anna'},
  {'id': 2, 'name': 'Brad'},
], 'id');
// {1: {'id': 1, 'name': 'Anna'}, 2: {'id': 2, 'name': 'Brad'}}
```

<div id="method-arr-last"></div>

#### `Arr.last()`

返回与条件匹配的最后一个元素，或默认值：

``` dart
Arr.last([1, 2, 3, 4], predicate: (n) => n.isEven); // 4
Arr.last([1, 2, 3]);                                 // 3
```

<div id="method-arr-map"></div>

#### `Arr.map()`

通过也接收索引的回调映射每个元素：

``` dart
Arr.map(['a', 'b'], (v, i) => '$i:$v'); // ['0:a', '1:b']
```

<div id="method-arr-mapwithkeys"></div>

#### `Arr.mapWithKeys()`

将每个元素映射为 `MapEntry`，返回结果映射：

``` dart
Arr.mapWithKeys(
  [{'id': 1, 'name': 'Anna'}, {'id': 2, 'name': 'Brad'}],
  (m) => MapEntry(m['id'], m['name']),
);
// {1: 'Anna', 2: 'Brad'}
```

<div id="method-arr-max"></div>

#### `Arr.max()`

返回 `by` 值最大的元素，省略 `by` 时返回最大元素：

``` dart
Arr.max([3, 1, 4, 1, 5]);            // 5
Arr.max(users, by: (u) => u.age);    // 年龄最大的用户
```

<div id="method-arr-median"></div>

#### `Arr.median()`

返回列表的中位数，为空时返回 `0`：

``` dart
Arr.median([1, 3, 5]);    // 3.0
Arr.median([1, 2, 3, 4]); // 2.5
```

<div id="method-arr-min"></div>

#### `Arr.min()`

返回 `by` 值最小的元素，省略 `by` 时返回最小元素：

``` dart
Arr.min([3, 1, 4, 1, 5]);            // 1
Arr.min(users, by: (u) => u.age);    // 年龄最小的用户
```

<div id="method-arr-move"></div>

#### `Arr.move()`

返回将 `from` 位置的元素移动到 `to` 位置后的新列表。`to` 会被限制在有效范围内：

``` dart
Arr.move(['a', 'b', 'c', 'd'], 0, 2); // ['b', 'c', 'a', 'd']
```

<div id="method-arr-onlyvalues"></div>

#### `Arr.onlyValues()`

返回列表中也存在于给定值中的元素：

``` dart
Arr.onlyValues([1, 2, 3, 4], [2, 4, 9]); // [2, 4]
```

<div id="method-arr-partition"></div>

#### `Arr.partition()`

根据条件将列表分割为 `[匹配, 不匹配]` 对：

``` dart
final [evens, odds] = Arr.partition([1, 2, 3, 4], (n) => n.isEven);
// evens: [2, 4], odds: [1, 3]
```

<div id="method-arr-pluck"></div>

#### `Arr.pluck()`

从列表中每个映射中提取键的值：

``` dart
Arr.pluck([
  {'name': 'Anna'},
  {'name': 'Brad'},
], 'name');
// ['Anna', 'Brad']
```

<div id="method-arr-prepend"></div>

#### `Arr.prepend()`

返回在开头插入值的新列表：

``` dart
Arr.prepend([2, 3], 1); // [1, 2, 3]
```

<div id="method-arr-push"></div>

#### `Arr.push()`

返回在末尾追加值的新列表：

``` dart
Arr.push([1, 2], 3); // [1, 2, 3]
```

<div id="method-arr-random"></div>

#### `Arr.random()`

返回列表中的随机元素。列表为空时抛出异常：

``` dart
Arr.random(['rock', 'paper', 'scissors']); // 三者之一
```

<div id="method-arr-randommany"></div>

#### `Arr.randomMany()`

不重复地返回最多 `count` 个随机元素：

``` dart
Arr.randomMany([1, 2, 3, 4, 5], 2); // 例如 [3, 1]
```

<div id="method-arr-reject"></div>

#### `Arr.reject()`

将列表过滤为*不*匹配条件的元素（[`where`](#method-arr-where) 的反向）：

``` dart
Arr.reject([1, 2, 3, 4], (n) => n.isEven); // [1, 3]
```

<div id="method-arr-replaceat"></div>

#### `Arr.replaceAt()`

返回将 `index` 处元素替换为 `value` 的新列表。`index` 超出范围时抛出 `RangeError`：

``` dart
Arr.replaceAt(['a', 'b', 'c'], 1, 'B'); // ['a', 'B', 'c']
```

<div id="method-arr-select"></div>

#### `Arr.select()`

返回列表中每个映射仅保留给定键的结果：

``` dart
Arr.select([
  {'name': 'Anna', 'role': 'admin', 'age': 30},
  {'name': 'Brad', 'role': 'user',  'age': 25},
], ['name', 'role']);
// [{'name': 'Anna', 'role': 'admin'}, {'name': 'Brad', 'role': 'user'}]
```

<div id="method-arr-shuffle"></div>

#### `Arr.shuffle()`

返回列表的随机排列副本。传入 `seed` 可获得确定性结果：

``` dart
Arr.shuffle([1, 2, 3, 4, 5]);
Arr.shuffle([1, 2, 3], seed: 42); // 确定性
```

<div id="method-arr-sole"></div>

#### `Arr.sole()`

返回与条件匹配的唯一元素。匹配零个或多个元素时抛出异常：

``` dart
Arr.sole([1, 2, 3, 4], predicate: (n) => n == 3); // 3
Arr.sole([1, 2, 3], predicate: (n) => n.isEven);  // 2
```

<div id="method-arr-some"></div>

#### `Arr.some()`

当至少一个元素通过条件时返回 `true`：

``` dart
Arr.some([1, 2, 3], (n) => n.isEven); // true
Arr.some([1, 3, 5], (n) => n.isEven); // false
```

<div id="method-arr-sort"></div>

#### `Arr.sort()`

返回列表的已排序副本：

``` dart
Arr.sort([3, 1, 2]);                                // [1, 2, 3]
Arr.sort(users, compare: (a, b) => a.age - b.age);   // 按年龄升序
```

<div id="method-arr-sortdesc"></div>

#### `Arr.sortDesc()`

返回列表的降序排列副本：

``` dart
Arr.sortDesc([3, 1, 2]); // [3, 2, 1]
```

<div id="method-arr-sortrecursive"></div>

#### `Arr.sortRecursive()`

递归地对列表排序。嵌套列表在每个深度都会被排序：

``` dart
Arr.sortRecursive([[3, 1, 2], [9, 7, 8]]);
// [[1, 2, 3], [7, 8, 9]]
```

<div id="method-arr-sum"></div>

#### `Arr.sum()`

返回列表的总和。传入 `by` 可从每个元素中提取数字：

``` dart
Arr.sum([1, 2, 3]);                          // 6
Arr.sum(orders, by: (o) => o.total);         // 总额之和
```

<div id="method-arr-swap"></div>

#### `Arr.swap()`

返回将 `i` 和 `j` 处元素互换的新列表：

``` dart
Arr.swap(['a', 'b', 'c'], 0, 2); // ['c', 'b', 'a']
```

<div id="method-arr-take"></div>

#### `Arr.take()`

返回前 `count` 个元素，`count` 为负数时返回最后 `count` 个：

``` dart
Arr.take([1, 2, 3, 4, 5], 2);   // [1, 2]
Arr.take([1, 2, 3, 4, 5], -2);  // [4, 5]
```

<div id="method-arr-unique"></div>

#### `Arr.unique()`

返回列表的唯一值：

``` dart
Arr.unique([1, 2, 2, 3, 1]); // [1, 2, 3]
```

<div id="method-arr-where"></div>

#### `Arr.where()`

将列表过滤为匹配条件的元素：

``` dart
Arr.where([1, 2, 3, 4], (n) => n.isEven); // [2, 4]
```

<div id="method-arr-wherenotnull"></div>

#### `Arr.whereNotNull()`

返回去除了 `null` 值的列表：

``` dart
Arr.whereNotNull([1, null, 2, null, 3]); // [1, 2, 3]
```

<div id="method-arr-wrap"></div>

#### `Arr.wrap()`

如果值还不是 `List`，则将其包裹在 `List` 中。值为 `null` 时返回空列表：

``` dart
Arr.wrap('foo');     // ['foo']
Arr.wrap([1, 2]);    // [1, 2]
Arr.wrap(null);      // []
```

<div id="strings"></div>

## 字符串

<div id="method-str-after"></div>

#### `Str.after()`

返回 `subject` 中 `search` 首次出现之后的部分：

``` dart
Str.after('hello world hello', 'hello'); // ' world hello'
```

<div id="method-str-afterlast"></div>

#### `Str.afterLast()`

返回 `subject` 中 `search` 最后一次出现之后的部分：

``` dart
Str.afterLast('app/Http/Controllers', '/'); // 'Controllers'
```

<div id="method-str-before"></div>

#### `Str.before()`

返回 `subject` 中 `search` 首次出现之前的部分：

``` dart
Str.before('hello world', ' '); // 'hello'
```

<div id="method-str-beforelast"></div>

#### `Str.beforeLast()`

返回 `subject` 中 `search` 最后一次出现之前的部分：

``` dart
Str.beforeLast('app/Http/Controllers', '/'); // 'app/Http'
```

<div id="method-str-between"></div>

#### `Str.between()`

返回 `subject` 中 `from` 与 `to` 之间的部分（贪婪匹配）：

``` dart
Str.between('[a] foo [b]', '[', ']'); // 'a] foo [b'
```

<div id="method-str-betweenfirst"></div>

#### `Str.betweenFirst()`

返回 `subject` 中 `from` 与 `to` 之间的最短部分：

``` dart
Str.betweenFirst('[a] foo [b]', '[', ']'); // 'a'
```

<div id="method-str-camel"></div>

#### `Str.camel()`

将值转换为 `camelCase`：

``` dart
Str.camel('foo_bar');     // 'fooBar'
Str.camel('Hello world'); // 'helloWorld'
```

<div id="method-str-charat"></div>

#### `Str.charAt()`

返回给定索引处的字符。负索引从末尾计数。超出范围时返回 `null`：

``` dart
Str.charAt('hello', 1);  // 'e'
Str.charAt('hello', -1); // 'o'
Str.charAt('hello', 99); // null
```

<div id="method-str-contains"></div>

#### `Str.contains()`

判断 `haystack` 是否包含 `needles` 中的任意一个：

``` dart
Str.contains('hello world', 'world');                       // true
Str.contains('hello world', ['cat', 'world']);              // true
Str.contains('Hello', 'hello', ignoreCase: true);           // true
```

<div id="method-str-containsall"></div>

#### `Str.containsAll()`

判断 `haystack` 是否包含所有 `needles`：

``` dart
Str.containsAll('hello world', ['hello', 'world']); // true
Str.containsAll('hello world', ['hello', 'cat']);   // false
```

<div id="method-str-deduplicate"></div>

#### `Str.deduplicate()`

将 `character` 的连续出现替换为单个实例：

``` dart
Str.deduplicate('hello   world');     // 'hello world'
Str.deduplicate('//path//to//', '/'); // '/path/to/'
```

<div id="method-str-endswith"></div>

#### `Str.endsWith()`

判断 `haystack` 是否以 `needles` 中的任意一个结尾：

``` dart
Str.endsWith('app.dart', '.dart');           // true
Str.endsWith('app.dart', ['.dart', '.ts']);  // true
```

<div id="method-str-excerpt"></div>

#### `Str.excerpt()`

提取 `phrase` 首次出现位置周围的文本片段。未找到短语时返回 `null`：

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

确保值以单个 `cap` 实例结尾：

``` dart
Str.finish('hello', '!');     // 'hello!'
Str.finish('hello!!!', '!');  // 'hello!'
```

<div id="method-str-headline"></div>

#### `Str.headline()`

按大写边界、下划线、连字符和空格将字符串分割为单词，然后转换为标题格式：

``` dart
Str.headline('steve_jobs');           // 'Steve Jobs'
Str.headline('EmailNotificationSent'); // 'Email Notification Sent'
```

<div id="method-str-is"></div>

#### `Str.is_()`

判断值是否与给定模式匹配。星号用作通配符：

``` dart
Str.is_('foo.*', 'foo.bar');                       // true
Str.is_(['admin/*', 'user/*'], 'admin/profile');   // true
Str.is_('foo', 'bar');                              // false
```

> **注意：** 末尾的下划线是为了避免与 Dart 保留关键字 `is` 冲突。

<div id="method-str-isascii"></div>

#### `Str.isAscii()`

如果值仅包含 7 位 ASCII 字符则返回 `true`：

``` dart
Str.isAscii('hello');  // true
Str.isAscii('héllo');  // false
```

<div id="method-str-isjson"></div>

#### `Str.isJson()`

如果值是有效的 JSON 则返回 `true`：

``` dart
Str.isJson('{"a": 1}');   // true
Str.isJson('not json');   // false
```

<div id="method-str-isulid"></div>

#### `Str.isUlid()`

如果值是 ULID（26 个字符，Crockford base32）则返回 `true`：

``` dart
Str.isUlid('01ARZ3NDEKTSV4RRFFQ69G5FAV'); // true
Str.isUlid('not a ulid');                  // false
```

<div id="method-str-isurl"></div>

#### `Str.isUrl()`

如果值是有效的 URL 则返回 `true`：

``` dart
Str.isUrl('https://nylo.dev'); // true
Str.isUrl('not a url');        // false
```

<div id="method-str-isuuid"></div>

#### `Str.isUuid()`

如果值是有效的 UUID（任意版本）则返回 `true`：

``` dart
Str.isUuid('a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11'); // true
Str.isUuid('hello');                                 // false
```

<div id="method-str-kebab"></div>

#### `Str.kebab()`

将值转换为 `kebab-case`：

``` dart
Str.kebab('fooBar');      // 'foo-bar'
Str.kebab('Hello World'); // 'hello-world'
```

<div id="method-str-lcfirst"></div>

#### `Str.lcfirst()`

将值的第一个字符转为小写：

``` dart
Str.lcfirst('Hello world'); // 'hello world'
```

<div id="method-str-length"></div>

#### `Str.length()`

返回值的长度：

``` dart
Str.length('hello'); // 5
```

<div id="method-str-limit"></div>

#### `Str.limit()`

将值截断到给定字符数并追加后缀：

``` dart
Str.limit('A long sentence', 6);              // 'A long...'
Str.limit('A long sentence', 6, '... read');  // 'A long... read'
```

<div id="method-str-lower"></div>

#### `Str.lower()`

将值转为小写：

``` dart
Str.lower('HELLO'); // 'hello'
```

<div id="method-str-mask"></div>

#### `Str.mask()`

用字符遮蔽字符串的一部分，从 `index` 开始遮蔽 `length` 个字符（省略 `length` 时遮蔽到末尾）：

``` dart
Str.mask('user@example.com', '*', 3);    // 'use*************'
Str.mask('user@example.com', '*', 3, 5); // 'use*****mple.com'
```

<div id="method-str-match"></div>

#### `Str.match()`

返回 `subject` 中 `pattern` 的第一个正则表达式匹配，无匹配时返回 `null`：

``` dart
Str.match(RegExp(r'\d+'), 'item 42 in 99 stock'); // '42'
```

> **注意：** 这是一个正则表达式匹配器，与用于 `Map` 值查找的全局 [`match`](#method-match) 辅助函数不同。

<div id="method-str-matchall"></div>

#### `Str.matchAll()`

返回 `subject` 中 `pattern` 的所有正则表达式匹配：

``` dart
Str.matchAll(RegExp(r'\d+'), 'item 42 in 99 stock'); // ['42', '99']
```

<div id="method-str-padboth"></div>

#### `Str.padBoth()`

在值两侧用 `pad` 填充直到达到 `length`：

``` dart
Str.padBoth('5', 5, '0'); // '00500'
```

<div id="method-str-padleft"></div>

#### `Str.padLeft()`

在值左侧填充：

``` dart
Str.padLeft('5', 3, '0'); // '005'
```

<div id="method-str-padnumber"></div>

#### `Str.padNumber()`

用前导零填充数字字符串：

``` dart
Str.padNumber('5', 3); // '005'
```

<div id="method-str-padright"></div>

#### `Str.padRight()`

在值右侧填充：

``` dart
Str.padRight('5', 3, '0'); // '500'
```

<div id="method-str-password"></div>

#### `Str.password()`

生成加密安全的随机密码。通过命名参数切换字符类别：

``` dart
Str.password(12);                                   // 字母 + 数字 + 符号
Str.password(12, symbols: false);                   // 仅字母 + 数字
Str.password(8, letters: false, symbols: false);    // 仅数字
```

<div id="method-str-position"></div>

#### `Str.position()`

返回 `needle` 在 `haystack` 中首次出现的索引，未找到时返回 `null`：

``` dart
Str.position('hello world', 'world');             // 6
Str.position('hello world hello', 'hello', offset: 1); // 12
Str.position('hello', 'cat');                      // null
```

<div id="method-str-random"></div>

#### `Str.random()`

生成给定长度的加密安全随机字母数字字符串：

``` dart
Str.random();    // 16 个字符，例如 'aB3k9XzMq7VnPsRt'
Str.random(8);   // 8 个字符
```

<div id="method-str-remove"></div>

#### `Str.remove()`

从 `subject` 中删除 `search` 中的任意内容：

``` dart
Str.remove('o', 'hello world');                  // 'hell wrld'
Str.remove(['e', 'l'], 'hello world');           // 'ho word'
Str.remove('Hello', 'hello world', caseSensitive: false); // ' world'
```

<div id="method-str-repeat"></div>

#### `Str.repeat()`

将值重复给定次数：

``` dart
Str.repeat('ab', 3); // 'ababab'
```

<div id="method-str-replace"></div>

#### `Str.replace()`

将 `subject` 中所有出现的 `search` 替换为 `replace`：

``` dart
Str.replace('o', '0', 'hello world');           // 'hell0 w0rld'
Str.replace(['o', 'l'], '*', 'hello world');     // 'he*** w*r*d'
```

<div id="method-str-replacefirst"></div>

#### `Str.replaceFirst()`

将 `search` 的第一次出现替换为 `replace`：

``` dart
Str.replaceFirst('hello', 'hi', 'hello hello'); // 'hi hello'
```

<div id="method-str-replacelast"></div>

#### `Str.replaceLast()`

将 `search` 的最后一次出现替换为 `replace`：

``` dart
Str.replaceLast('hello', 'hi', 'hello hello'); // 'hello hi'
```

<div id="method-str-reverse"></div>

#### `Str.reverse()`

反转值：

``` dart
Str.reverse('hello'); // 'olleh'
```

<div id="method-str-slug"></div>

#### `Str.slug()`

从标题生成 URL 友好的 slug：

``` dart
Str.slug('Hello World');                                  // 'hello-world'
Str.slug('Tech & Code', dictionary: {'&': 'and'});        // 'tech-and-code'
Str.slug('Hello World', separator: '_');                  // 'hello_world'
```

<div id="method-str-snake"></div>

#### `Str.snake()`

将值转换为 `snake_case`。传入自定义分隔符可使用 `_` 以外的字符：

``` dart
Str.snake('helloWorld');         // 'hello_world'
Str.snake('helloWorld', '-');    // 'hello-world'
```

<div id="method-str-squish"></div>

#### `Str.squish()`

修剪首尾空白并将内部空白折叠为单个空格：

``` dart
Str.squish('   hello    world   '); // 'hello world'
```

<div id="method-str-start"></div>

#### `Str.start()`

确保值以单个 `prefix` 实例开头：

``` dart
Str.start('this/string', '/');     // '/this/string'
Str.start('//this/string', '/');   // '/this/string'
```

<div id="method-str-startswith"></div>

#### `Str.startsWith()`

判断 `haystack` 是否以 `needles` 中的任意一个开头：

``` dart
Str.startsWith('hello world', 'hello');           // true
Str.startsWith('hello world', ['cat', 'hello']);  // true
```

<div id="method-str-studly"></div>

#### `Str.studly()`

将值转换为 `StudlyCase` / `PascalCase`：

``` dart
Str.studly('foo_bar');     // 'FooBar'
Str.studly('hello world'); // 'HelloWorld'
```

<div id="method-str-substr"></div>

#### `Str.substr()`

返回从 `start` 开始 `length` 个字符的子字符串。负 `start` 从末尾计数：

``` dart
Str.substr('profile', 4);       // 'ile'
Str.substr('profile', 4, 2);    // 'il'
Str.substr('profile', -3);      // 'ile'
```

<div id="method-str-substrcount"></div>

#### `Str.substrCount()`

统计 `haystack` 中 `needle` 不重叠的出现次数：

``` dart
Str.substrCount('hello hello hello', 'hello'); // 3
```

<div id="method-str-swap"></div>

#### `Str.swap()`

使用搜索 → 替换对的 `Map` 替换多个子字符串：

``` dart
Str.swap({'foo': 'bar', 'hello': 'hi'}, 'hello foo'); // 'hi bar'
```

<div id="method-str-take"></div>

#### `Str.take()`

返回值的前 `limit` 个字符。负限制从末尾取：

``` dart
Str.take('Build something amazing!', 5); // 'Build'
Str.take('Build something amazing!', -9); // ' amazing!'
```

<div id="method-str-title"></div>

#### `Str.title()`

将值转换为 `Title Case`：

``` dart
Str.title('a nice title'); // 'A Nice Title'
```

<div id="method-str-ucfirst"></div>

#### `Str.ucfirst()`

将值的第一个字符转为大写：

``` dart
Str.ucfirst('hello world'); // 'Hello world'
```

<div id="method-str-ucsplit"></div>

#### `Str.ucsplit()`

在大写边界处将值分割为单词：

``` dart
Str.ucsplit('fooBarBaz'); // ['foo', 'Bar', 'Baz']
```

<div id="method-str-ulid"></div>

#### `Str.ulid()`

生成 ULID（通用唯一词典排序标识符）：

``` dart
Str.ulid(); // 例如 '01ARZ3NDEKTSV4RRFFQ69G5FAV'
```

<div id="method-str-unwrap"></div>

#### `Str.unwrap()`

从开头去除单个 `before` 实例，从末尾去除 `after`（若 `after` 为 null 则去除 `before`）实例：

``` dart
Str.unwrap('"value"', '"');             // 'value'
Str.unwrap('<p>html</p>', '<p>', '</p>'); // 'html'
```

<div id="method-str-upper"></div>

#### `Str.upper()`

将值转为大写：

``` dart
Str.upper('hello'); // 'HELLO'
```

<div id="method-str-uuid"></div>

#### `Str.uuid()`

生成随机 UUID v4：

``` dart
Str.uuid(); // 例如 'a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11'
```

<div id="method-str-uuid7"></div>

#### `Str.uuid7()`

生成 UUID v7（时间有序，RFC 9562）。UUIDv7 可按创建时间排序：

``` dart
Str.uuid7(); // 例如 '018e0dc4-8b3f-7a2c-9d01-b1f6c87a92e3'
```

<div id="method-str-wordcount"></div>

#### `Str.wordCount()`

返回值的单词数：

``` dart
Str.wordCount('hello there friend'); // 3
```

<div id="method-str-words"></div>

#### `Str.words()`

将值限制到给定的单词数：

``` dart
Str.words('Perfectly balanced, as all things should be.', 3);
// 'Perfectly balanced, as...'
```

<div id="method-str-wrap"></div>

#### `Str.wrap()`

用 `before` 和 `after` 包裹值。省略 `after` 时两侧都使用 `before`：

``` dart
Str.wrap('value', '"');             // '"value"'
Str.wrap('html', '<p>', '</p>');    // '<p>html</p>'
```

<div id="numbers"></div>

## 数字

<div id="method-number-abbreviate"></div>

#### `Number.abbreviate()`

返回数字的缩写形式（`1K`、`1M`、`1B`）：

``` dart
Number.abbreviate(1500);                  // '2K'
Number.abbreviate(1500, maxPrecision: 1); // '1.5K'
Number.abbreviate(2_500_000);             // '3M'
```

<div id="method-number-average"></div>

#### `Number.average()`

返回值的算术平均值，为空时返回 `0`：

``` dart
Number.average([1, 2, 3, 4]); // 2.5
```

<div id="method-number-between"></div>

#### `Number.between()`

当值在 `min` 和 `max` 之间（含端点）时返回 `true`：

``` dart
Number.between(5, min: 0, max: 10);  // true
Number.between(15, min: 0, max: 10); // false
```

<div id="method-number-ceil"></div>

#### `Number.ceil()`

将值向上取整到给定精度：

``` dart
Number.ceil(1.2);    // 2.0
Number.ceil(1.234, 2); // 1.24
```

<div id="method-number-clamp"></div>

#### `Number.clamp()`

将值限制在 `min` 和 `max` 之间（含端点）：

``` dart
Number.clamp(5, 0, 10);   // 5
Number.clamp(15, 0, 10);  // 10
Number.clamp(-1, 0, 10);  // 0
```

<div id="method-number-currency"></div>

#### `Number.currency()`

将数字格式化为货币值。默认使用配置的语言区域和货币：

``` dart
Number.currency(1234.56);                            // '$1,234.56'
Number.currency(1234.56, currency: 'EUR');           // '€1,234.56'
Number.currency(1234.56, locale: 'de_DE', currency: 'EUR');
// '1.234,56 €'
```

<div id="method-number-defaultcurrency"></div>

#### `Number.defaultCurrency()`

返回格式化方法使用的默认货币代码：

``` dart
Number.defaultCurrency(); // 'USD'
```

<div id="method-number-defaultlocale"></div>

#### `Number.defaultLocale()`

返回格式化方法使用的默认语言区域：

``` dart
Number.defaultLocale(); // 'en_US'
```

<div id="method-number-degrees"></div>

#### `Number.degrees()`

将弧度转换为度：

``` dart
Number.degrees(3.14159);  // ~180.0
```

<div id="method-number-duration"></div>

#### `Number.duration()`

将秒数格式化为人类可读的时长。短格式返回 `'1h 2m 5s'`，长格式返回 `'1:02:05'`：

``` dart
Number.duration(3661);                  // '1h 1m 1s'
Number.duration(3661, short: false);    // '1:01:01'
Number.duration(45);                    // '45s'
```

<div id="method-number-filesize"></div>

#### `Number.fileSize()`

将字节数格式化为人类可读的文件大小：

``` dart
Number.fileSize(1024);                       // '1 KB'
Number.fileSize(1500, maxPrecision: 1);      // '1.5 KB'
Number.fileSize(1024 * 1024 * 1024);         // '1 GB'
```

<div id="method-number-floor"></div>

#### `Number.floor()`

将值向下取整到给定精度：

``` dart
Number.floor(1.7);      // 1.0
Number.floor(1.234, 2); // 1.23
```

<div id="method-number-forhumans"></div>

#### `Number.forHumans()`

返回数字的人类可读形式（`1 thousand`、`1 million`）：

``` dart
Number.forHumans(1500);                  // '2 thousand'
Number.forHumans(1500, maxPrecision: 1); // '1.5 thousand'
Number.forHumans(2_500_000);             // '3 million'
```

<div id="method-number-format"></div>

#### `Number.format()`

使用区域感知分隔符格式化数字：

``` dart
Number.format(1234567);                          // '1,234,567'
Number.format(1234.5, precision: 2);             // '1,234.50'
Number.format(1234567, locale: 'de_DE');         // '1.234.567'
```

<div id="method-number-gcd"></div>

#### `Number.gcd()`

两个整数的最大公约数：

``` dart
Number.gcd(12, 18); // 6
```

<div id="method-number-lcm"></div>

#### `Number.lcm()`

两个整数的最小公倍数：

``` dart
Number.lcm(4, 6); // 12
```

<div id="method-number-lerp"></div>

#### `Number.lerp()`

按 `t`（`0..1`）在 `a` 和 `b` 之间进行线性插值：

``` dart
Number.lerp(0, 100, 0.25); // 25.0
```

<div id="method-number-max"></div>

#### `Number.max()`

返回可迭代对象中的最大值：

``` dart
Number.max([3, 1, 4, 1, 5, 9, 2, 6]); // 9
```

<div id="method-number-median"></div>

#### `Number.median()`

返回值的中位数，为空时返回 `0`：

``` dart
Number.median([1, 3, 5]);    // 3.0
Number.median([1, 2, 3, 4]); // 2.5
```

<div id="method-number-min"></div>

#### `Number.min()`

返回可迭代对象中的最小值：

``` dart
Number.min([3, 1, 4, 1, 5, 9, 2, 6]); // 1
```

<div id="method-number-ordinal"></div>

#### `Number.ordinal()`

返回数字的序数形式（`1st`、`2nd`、`3rd`、`4th`）：

``` dart
Number.ordinal(1);   // '1st'
Number.ordinal(2);   // '2nd'
Number.ordinal(21);  // '21st'
Number.ordinal(112); // '112th'
```

<div id="method-number-pairs"></div>

#### `Number.pairs()`

从范围生成到 `to` 的配对（子区间），以 `by` 为步长：

``` dart
Number.pairs(25, 10);             // [[0, 9], [10, 19], [20, 25]]
Number.pairs(25, 10, offset: 0);  // [[0, 10], [10, 20], [20, 25]]
```

<div id="method-number-parsefloat"></div>

#### `Number.parseFloat()`

将区域感知字符串解析为 `double`。失败时返回 `null`：

``` dart
Number.parseFloat('1,234.56');                    // 1234.56
Number.parseFloat('1.234,56', locale: 'de_DE');   // 1234.56
Number.parseFloat('not a number');                // null
```

<div id="method-number-parseint"></div>

#### `Number.parseInt()`

将区域感知字符串解析为 `int`。失败时返回 `null`：

``` dart
Number.parseInt('1,234');                    // 1234
Number.parseInt('1.234', locale: 'de_DE');   // 1234
```

<div id="method-number-percentage"></div>

#### `Number.percentage()`

将数字格式化为百分比。数字被视为完整的百分比值：

``` dart
Number.percentage(10);                  // '10%'
Number.percentage(10.5, precision: 1);  // '10.5%'
```

<div id="method-number-radians"></div>

#### `Number.radians()`

将度转换为弧度：

``` dart
Number.radians(180); // ~3.14159
```

<div id="method-number-random"></div>

#### `Number.random()`

返回 `[min, max]`（含端点）中的随机整数。传入 `seed` 可获得确定性序列：

``` dart
Number.random(min: 1, max: 100);              // 1..100 中的任意整数
Number.random(min: 0, max: 9, seed: 42);      // 确定性
```

<div id="method-number-range"></div>

#### `Number.range()`

生成从 `start`（含）到 `end`（含）以 `step` 为步长的整数列表：

``` dart
Number.range(1, 5);              // [1, 2, 3, 4, 5]
Number.range(0, 10, step: 2);    // [0, 2, 4, 6, 8, 10]
Number.range(5, 1, step: -1);    // [5, 4, 3, 2, 1]
```

<div id="method-number-round"></div>

#### `Number.round()`

将值四舍五入到给定精度：

``` dart
Number.round(1.5);      // 2.0
Number.round(1.234, 2); // 1.23
```

<div id="method-number-scale"></div>

#### `Number.scale()`

将值从一个范围重新映射到另一个范围：

``` dart
Number.scale(0.5, fromMin: 0, fromMax: 1, toMin: 0, toMax: 100); // 50.0
Number.scale(75, fromMin: 0, fromMax: 100, toMin: -1, toMax: 1); // 0.5
```

<div id="method-number-spell"></div>

#### `Number.spell()`

用英文拼写数字：

``` dart
Number.spell(0);           // 'zero'
Number.spell(123);         // 'one hundred twenty-three'
Number.spell(1_234_567);   // 'one million two hundred thirty-four thousand five hundred sixty-seven'
```

<div id="method-number-spellordinal"></div>

#### `Number.spellOrdinal()`

用英文拼写数字的序数形式：

``` dart
Number.spellOrdinal(1);   // 'first'
Number.spellOrdinal(2);   // 'second'
Number.spellOrdinal(21);  // 'twenty-first'
```

<div id="method-number-sum"></div>

#### `Number.sum()`

返回值的总和：

``` dart
Number.sum([1, 2, 3]); // 6
```

<div id="method-number-tobytes"></div>

#### `Number.toBytes()`

将文件大小转换为字节。是 [`fileSize`](#method-number-filesize) 的反向操作。传入可解析的字符串或带 `unit` 的数字：

``` dart
Number.toBytes('1.5 KB');     // 1536
Number.toBytes(1.5, 'KB');    // 1536
Number.toBytes(1, 'GB');      // 1073741824
Number.toBytes('not bytes');  // null
```

<div id="method-number-trim"></div>

#### `Number.trim()`

去除值小数部分的尾随零：

``` dart
Number.trim(12.0);    // '12'
Number.trim(12.30);   // '12.3'
Number.trim(12.345);  // '12.345'
```

<div id="method-number-usecurrency"></div>

#### `Number.useCurrency()`

设置格式化方法使用的默认货币代码：

``` dart
Number.useCurrency('EUR');
Number.currency(99); // '€99.00'
```

<div id="method-number-uselocale"></div>

#### `Number.useLocale()`

设置格式化方法使用的默认语言区域：

``` dart
Number.useLocale('de_DE');
Number.format(1234567); // '1.234.567'
```

<div id="objects"></div>

## 对象

`Obj` 提供使用**点表示法**路径处理映射的工具。列表元素可通过整数索引寻址（例如 `'users.0.name'`）。

<div id="method-obj-add"></div>

#### `Obj.add()`

仅当 `key` 处当前不存在值时才添加值：

``` dart
final user = {'name': 'Anna'};
Obj.add(user, 'role', 'admin');
// user: {'name': 'Anna', 'role': 'admin'}

Obj.add(user, 'name', 'Brad'); // 无操作 — name 已设置
```

<div id="method-obj-deepequals"></div>

#### `Obj.deepEquals()`

当两个值在结构上相等时（对 `Map` 和 `List` 进行递归比较）返回 `true`：

``` dart
Obj.deepEquals({'a': [1, 2]}, {'a': [1, 2]}); // true
Obj.deepEquals({'a': 1}, {'a': 2});            // false
```

<div id="method-obj-divide"></div>

#### `Obj.divide()`

将映射分割为 `[keys, values]` 对：

``` dart
Obj.divide({'name': 'Desk', 'price': 100});
// [['name', 'price'], ['Desk', 100]]
```

<div id="method-obj-dot"></div>

#### `Obj.dot()`

将嵌套映射展平为以点路径为键的单层映射：

``` dart
Obj.dot({'a': {'b': 1, 'c': 2}});
// {'a.b': 1, 'a.c': 2}
```

<div id="method-obj-except"></div>

#### `Obj.except()`

返回包含除给定键以外所有条目的新映射：

``` dart
Obj.except({'name': 'Anna', 'role': 'admin', 'age': 30}, ['age']);
// {'name': 'Anna', 'role': 'admin'}
```

<div id="method-obj-exists"></div>

#### `Obj.exists()`

当映射在顶层具有给定键时返回 `true`（不进行点表示法遍历）：

``` dart
Obj.exists({'a.b': 1}, 'a.b'); // true
Obj.exists({'a': {'b': 1}}, 'a.b'); // false（遍历请使用 `has`）
```

<div id="method-obj-flip"></div>

#### `Obj.flip()`

交换键和值。值冲突时最后一个条目获胜：

``` dart
Obj.flip({'one': 1, 'two': 2}); // {1: 'one', 2: 'two'}
```

<div id="method-obj-forget"></div>

#### `Obj.forget()`

从映射中删除键（点表示法）。返回映射以支持链式调用：

``` dart
final user = {'profile': {'name': 'Anna', 'email': 'a@b'}};
Obj.forget(user, 'profile.email');
// user: {'profile': {'name': 'Anna'}}
```

<div id="method-obj-get"></div>

#### `Obj.get()`

使用点表示法返回 `key` 处的值，路径不存在时返回默认值。包含字面点的顶层键优先于遍历：

``` dart
final user = {'profile': {'name': 'Anna', 'age': 30}};
Obj.get(user, 'profile.name');     // 'Anna'
Obj.get(user, 'profile.email');    // null
Obj.get(user, 'profile.email', 'unknown'); // 'unknown'

// 列表索引：
Obj.get({'users': [{'name': 'A'}]}, 'users.0.name'); // 'A'
```

<div id="method-obj-getbool"></div>

#### `Obj.getBool()`

返回 `key` 处强制转换为 `bool` 的值，或默认值。非零数字为真；字符串识别 `'true'`/`'false'`/`'1'`/`'0'`/`'yes'`/`'no'`/`'on'`/`'off'`（不区分大小写）：

``` dart
Obj.getBool({'flag': 'yes'}, 'flag');     // true
Obj.getBool({'flag': 0}, 'flag');         // false
Obj.getBool({}, 'flag', false);           // false
```

<div id="method-obj-getdouble"></div>

#### `Obj.getDouble()`

返回 `key` 处强制转换为 `double` 的值，或默认值：

``` dart
Obj.getDouble({'price': '9.99'}, 'price'); // 9.99
Obj.getDouble({'price': 10}, 'price');     // 10.0
```

<div id="method-obj-getint"></div>

#### `Obj.getInt()`

返回 `key` 处强制转换为 `int` 的值，或默认值：

``` dart
Obj.getInt({'count': '42'}, 'count');     // 42
Obj.getInt({'count': 3.7}, 'count');      // 3（截断）
Obj.getInt({}, 'count', 0);               // 0
```

<div id="method-obj-getlist"></div>

#### `Obj.getList()`

当 `key` 处的值为 `List` 时返回该值，否则返回默认值：

``` dart
Obj.getList({'items': [1, 2, 3]}, 'items'); // [1, 2, 3]
Obj.getList({'items': 'oops'}, 'items', []); // []
```

<div id="method-obj-getmap"></div>

#### `Obj.getMap()`

当 `key` 处的值为 `Map` 时返回该值，否则返回默认值：

``` dart
Obj.getMap({'meta': {'a': 1}}, 'meta'); // {'a': 1}
Obj.getMap({'meta': null}, 'meta', {}); // {}
```

<div id="method-obj-getstring"></div>

#### `Obj.getString()`

返回 `key` 处强制转换为 `String` 的值，或默认值：

``` dart
Obj.getString({'name': 'Anna'}, 'name'); // 'Anna'
Obj.getString({'count': 42}, 'count');   // '42'
Obj.getString({}, 'name', 'Guest');      // 'Guest'
```

<div id="method-obj-has"></div>

#### `Obj.has()`

使用点表示法当映射在 `key` 处有值时返回 `true`：

``` dart
final user = {'profile': {'name': 'Anna'}};
Obj.has(user, 'profile.name');  // true
Obj.has(user, 'profile.email'); // false
```

<div id="method-obj-hasall"></div>

#### `Obj.hasAll()`

当所有给定键在映射中都能解析时返回 `true`：

``` dart
Obj.hasAll({'a': 1, 'b': 2}, ['a', 'b']);      // true
Obj.hasAll({'a': 1, 'b': 2}, ['a', 'c']);      // false
```

<div id="method-obj-hasany"></div>

#### `Obj.hasAny()`

当给定键中有任意一个在映射中能解析时返回 `true`：

``` dart
Obj.hasAny({'a': 1}, ['a', 'b']); // true
Obj.hasAny({'a': 1}, ['x', 'y']); // false
```

<div id="method-obj-mapkeys"></div>

#### `Obj.mapKeys()`

返回每个键都经过 `fn` 转换的新映射：

``` dart
Obj.mapKeys({'firstName': 'Anna'}, (k) => k.toLowerCase());
// {'firstname': 'Anna'}
```

<div id="method-obj-mapvalues"></div>

#### `Obj.mapValues()`

返回每个值都经过 `fn` 转换的新映射：

``` dart
Obj.mapValues({'a': 1, 'b': 2}, (v) => v * 10);
// {'a': 10, 'b': 20}
```

<div id="method-obj-merge"></div>

#### `Obj.merge()`

将 `source` 递归合并到 `target` 中。键冲突时 `source` 获胜；嵌套映射会被合并：

``` dart
Obj.merge(
  {'a': {'x': 1}, 'b': 2},
  {'a': {'y': 9}, 'b': 22},
);
// {'a': {'x': 1, 'y': 9}, 'b': 22}
```

<div id="method-obj-only"></div>

#### `Obj.only()`

返回仅包含给定键条目的新映射：

``` dart
Obj.only({'name': 'Anna', 'role': 'admin', 'age': 30}, ['name', 'role']);
// {'name': 'Anna', 'role': 'admin'}
```

<div id="method-obj-prependkeyswith"></div>

#### `Obj.prependKeysWith()`

为映射中所有顶层键添加给定前缀：

``` dart
Obj.prependKeysWith({'name': 'Anna', 'age': 30}, 'user_');
// {'user_name': 'Anna', 'user_age': 30}
```

<div id="method-obj-pull"></div>

#### `Obj.pull()`

返回 `key` 处的值并将其从映射中删除：

``` dart
final user = {'name': 'Anna', 'temp': 'token'};
final temp = Obj.pull(user, 'temp'); // 'token'
// user: {'name': 'Anna'}
```

<div id="method-obj-query"></div>

#### `Obj.query()`

将映射编码为 URL 查询字符串：

``` dart
Obj.query({'name': 'Anna', 'tags': ['a', 'b']});
// 'name=Anna&tags%5B0%5D=a&tags%5B1%5D=b'

Obj.query({'filter': {'role': 'admin'}});
// 'filter%5Brole%5D=admin'
```

<div id="method-obj-set"></div>

#### `Obj.set()`

在 `key` 处设置值，沿路径创建嵌套映射。就地修改映射并返回以支持链式调用：

``` dart
final user = <String, dynamic>{};
Obj.set(user, 'profile.email', 'a@b');
// user: {'profile': {'email': 'a@b'}}
```

<div id="method-obj-undot"></div>

#### `Obj.undot()`

将点键映射展开为嵌套结构（[`dot`](#method-obj-dot) 的反向操作）：

``` dart
Obj.undot({'a.b': 1, 'a.c': 2});
// {'a': {'b': 1, 'c': 2}}
```

<div id="method-obj-wherenotempty"></div>

#### `Obj.whereNotEmpty()`

返回去除了值为 `null` 或空（空 `String`、`Iterable` 或 `Map`）条目的新映射：

``` dart
Obj.whereNotEmpty({'name': 'Anna', 'tags': [], 'bio': ''});
// {'name': 'Anna'}
```

<div id="method-obj-wherenotnull"></div>

#### `Obj.whereNotNull()`

返回去除了值为 `null` 条目的新映射：

``` dart
Obj.whereNotNull({'name': 'Anna', 'email': null});
// {'name': 'Anna'}
```

<div id="miscellaneous"></div>

## 其他

这些全局函数位于 `helper.dart` 中，在导入 `nylo_framework` 的任何地方都可以使用。

<div id="method-api"></div>

#### `api()`

通过 `NyApiService` 发送 API 请求的便捷辅助函数。完整选项请参阅 [Networking](/docs/{{ $version }}/networking) 页面：

``` dart
await api<ApiService>((request) =>
  request.get('https://jsonplaceholder.typicode.com/posts'));
```

<div id="method-datatomodel"></div>

#### `dataToModel()`

将原始数据（通常是 JSON `Map`）转换为在 `config/decoders.dart` 中注册的模型。需要类型参数：

``` dart
final user = dataToModel<User>(data: {'name': 'Anna', 'age': 30});
```

<div id="method-dump"></div>

#### `dump()`

将值转储到控制台。除非 `alwaysPrint: true`，否则遵循应用的 `APP_DEBUG` 标志：

``` dart
dump('Hello World');
dump(user, tag: 'current user');
dump(payload, alwaysPrint: true);
```

<div id="method-event"></div>

#### `event()`

使用 `Nylo.events()` 触发给定类型的事件。完整用法请参阅 [Events](/docs/{{ $version }}/events)：

``` dart
await event<LoginEvent>(data: {'user': 'Anna'});
```

<div id="method-getasset"></div>

#### `getAsset()`

返回 `/assets` 目录中资源的完整路径：

``` dart
final path = getAsset('videos/welcome.mp4');
// 'assets/videos/welcome.mp4'
```

<div id="method-getenv"></div>

#### `getEnv()`

从生成的 env（`env.g.dart`）中返回值。键未设置时返回默认值：

``` dart
final apiUrl = getEnv('API_URL');
final debug = getEnv('APP_DEBUG', defaultValue: false);
```

<div id="method-getimageasset"></div>

#### `getImageAsset()`

返回 `/assets/images/` 中图像的完整路径：

``` dart
Image.asset(getImageAsset('logo.png'));
```

<div id="method-loadjson"></div>

#### `loadJson()`

从资源包中加载 JSON 文件。结果默认会被缓存：

``` dart
final config = await loadJson<Map<String, dynamic>>('config/app.json');
```

<div id="method-match"></div>

#### `match()`

将值与 `Map` 中的各个分支匹配并返回对应值。无分支匹配且未提供 `defaultValue` 时抛出异常：

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

> **注意：** 这是基于 `Map` 的值查找，与执行正则表达式匹配的 [`Str.match`](#method-str-match) 不同。

<div id="method-now"></div>

#### `now()`

返回当前 `DateTime`：

``` dart
final timestamp = now();
```

<div id="method-nyhexcolor"></div>

#### `nyHexColor()`

将十六进制颜色字符串转换为 `Color`。接受 6 位或 8 位值，有无前导 `#` 均可：

``` dart
final brand = nyHexColor('#FF6B35');
final translucent = nyHexColor('80FF6B35'); // 8 位（Alpha 在前）
```

<div id="method-print-helpers"></div>

#### `printDebug()`、`printError()`、`printInfo()`、`printWarning()`、`printSuccess()`、`printVerbose()`、`printAlert()`、`printEmergency()`

带严重级别标签的控制台日志记录器。输出格式和 `alwaysPrint` 标志请参阅 [Logging](/docs/{{ $version }}/logging) 页面：

``` dart
printInfo('User signed in');
printError('Payment failed', context: {'order_id': 42});
printDebug({'state': state});
```

<div id="method-badge-helpers"></div>

#### `setBadgeNumber()`、`clearBadgeNumber()`

设置或清除应用图标徽标计数。平台设置详情请参阅 [Local Notifications](/docs/{{ $version }}/local-notifications)：

``` dart
await setBadgeNumber(3);
await clearBadgeNumber();
```

<div id="method-shownextlog"></div>

#### `showNextLog()`

即使 `APP_DEBUG` 为 `false`，也强制显示下一条 `NyLogger` 日志。适用于发布版本中的一次性诊断：

``` dart
showNextLog();
NyLogger.info('This will print regardless of APP_DEBUG');
```

<div id="method-sleep"></div>

#### `sleep()`

延迟执行给定时长。第一个参数为秒数；可选的第二个参数添加微秒：

``` dart
await sleep(2);              // 2 秒
await sleep(0, 500_000);     // 500 毫秒
```

<div id="method-trans"></div>

#### `trans()`

通过键返回翻译后的字符串。设置请参阅 [Localization](/docs/{{ $version }}/localization)：

``` dart
final greeting = trans('home.welcome');
final personalized = trans('home.hello', arguments: {'name': 'Anna'});
```
