# Helpers

<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำ")
- [เมธอดที่ใช้ได้](#available-methods "เมธอดที่ใช้ได้")
  - [Arrays](#arrays-method-list "Arrays")
  - [Strings](#strings-method-list "Strings")
  - [Numbers](#numbers-method-list "Numbers")
  - [Objects](#objects-method-list "Objects")
  - [เบ็ดเตล็ด](#miscellaneous-method-list "เบ็ดเตล็ด")
- [Arrays](#arrays "Arrays")
- [Strings](#strings "Strings")
- [Numbers](#numbers "Numbers")
- [Objects](#objects "Objects")
- [เบ็ดเตล็ด](#miscellaneous "เบ็ดเตล็ด")

<div id="introduction"></div>

## บทนำ

{{ config('app.name') }} มาพร้อมกับชุดคลาสอรรถประโยชน์แบบ static — `Arr`, `Str`, `Number` และ `Obj` — รวมถึงฟังก์ชัน helper แบบ global สำหรับงานทั่วไป ทั้งหมดถูก export จาก `nylo_framework`:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

Arr.first([1, 2, 3]);             // 1
Str.slug('Hello World');          // 'hello-world'
Number.currency(1234.56);         // '$1,234.56'
Obj.get(user, 'profile.name');    // 'Anna'
```

> **หมายเหตุ:** `Backpack`, `NyStorage`, `NyCache` และ `NyLogger` มีหน้าเอกสารเฉพาะของตัวเอง: [Backpack](/docs/{{ $version }}/backpack), [Storage](/docs/{{ $version }}/storage), [Cache](/docs/{{ $version }}/cache), [Logging](/docs/{{ $version }}/logging).

<div id="available-methods"></div>

## เมธอดที่ใช้ได้

<div id="arrays-method-list"></div>

### Arrays

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

### Strings

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

### Numbers

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

### Objects

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

### เบ็ดเตล็ด

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

## Arrays

<div id="method-arr-accessible"></div>

#### `Arr.accessible()`

ตรวจสอบว่าค่าที่กำหนดสามารถเข้าถึงได้เหมือน array หรือไม่ (`List` หรือ `Map`):

``` dart
Arr.accessible([1, 2, 3]);  // true
Arr.accessible({'a': 1});   // true
Arr.accessible('hello');    // false
Arr.accessible(null);       // false
```

<div id="method-arr-average"></div>

#### `Arr.average()`

คืนค่าเฉลี่ยเลขคณิตของ list หรือ `0` เมื่อว่างเปล่า ใช้ `by` เพื่อดึงตัวเลขจากแต่ละสมาชิก:

``` dart
Arr.average([2, 4, 6]);                // 4.0
Arr.average(orders, by: (o) => o.total); // ค่าเฉลี่ยยอดรวม
```

<div id="method-arr-chunk"></div>

#### `Arr.chunk()`

แบ่ง list ออกเป็นส่วนๆ ตามขนาดที่กำหนด:

``` dart
Arr.chunk([1, 2, 3, 4, 5], 2);
// [[1, 2], [3, 4], [5]]
```

<div id="method-arr-collapse"></div>

#### `Arr.collapse()`

รวม list ของ list เป็น list เดียว:

``` dart
Arr.collapse([[1, 2], [3, 4], [5]]);
// [1, 2, 3, 4, 5]
```

<div id="method-arr-countby"></div>

#### `Arr.countBy()`

นับจำนวนการเกิดขึ้นใน list โดยจัดกลุ่มตาม value extractor ที่ระบุ:

``` dart
Arr.countBy(['a', 'b', 'a', 'c']);
// {'a': 2, 'b': 1, 'c': 1}

Arr.countBy(orders, by: (o) => o.status);
// {'paid': 12, 'pending': 3}
```

<div id="method-arr-crossjoin"></div>

#### `Arr.crossJoin()`

Cross-join list ที่กำหนด คืนค่าทุกชุดค่าผสมที่เป็นไปได้:

``` dart
Arr.crossJoin([[1, 2], ['a', 'b']]);
// [[1, 'a'], [1, 'b'], [2, 'a'], [2, 'b']]
```

<div id="method-arr-every"></div>

#### `Arr.every()`

คืนค่า `true` เมื่อทุกสมาชิกของ list ผ่าน predicate โดยจะเป็น true เสมอสำหรับ list ว่างเปล่า:

``` dart
Arr.every([2, 4, 6], (n) => n.isEven); // true
Arr.every([1, 2, 3], (n) => n.isEven); // false
```

<div id="method-arr-exceptvalues"></div>

#### `Arr.exceptValues()`

คืนค่า list โดยนำค่าที่กำหนดออก:

``` dart
Arr.exceptValues([1, 2, 3, 4], [2, 4]); // [1, 3]
```

<div id="method-arr-first"></div>

#### `Arr.first()`

คืนค่าสมาชิกแรกที่ตรงกับ predicate หรือค่า default:

``` dart
Arr.first([1, 2, 3]);                                   // 1
Arr.first([1, 2, 3, 4], predicate: (n) => n.isEven);    // 2
Arr.first([], defaultValue: 99);                        // 99
```

<div id="method-arr-flatmap"></div>

#### `Arr.flatMap()`

map แต่ละสมาชิกผ่าน `fn` และ flatten iterable ที่ได้:

``` dart
Arr.flatMap(pages, (p) => p.items); // รายการทั้งหมดจากทุกหน้า
Arr.flatMap([1, 2, 3], (n) => [n, n * 10]); // [1, 10, 2, 20, 3, 30]
```

<div id="method-arr-flatten"></div>

#### `Arr.flatten()`

ทำให้ iterable ที่ซ้อนกันแบน เป็น list ระดับเดียว `depth` จำกัดความลึกในการทำให้แบน; `-1` หมายถึงไม่จำกัด:

``` dart
Arr.flatten([1, [2, [3]]]);              // [1, 2, 3]
Arr.flatten([1, [2, [3]]], depth: 1);    // [1, 2, [3]]
```

<div id="method-arr-groupby"></div>

#### `Arr.groupBy()`

จัดกลุ่ม list เป็น map โดยใช้ค่าที่คืนโดย `by` เป็น key:

``` dart
Arr.groupBy(messages, (m) => m.date);
// {2024-01-01: [...], 2024-01-02: [...]}
```

<div id="method-arr-indexed"></div>

#### `Arr.indexed()`

จับคู่แต่ละสมาชิกกับ index คืนค่า list ของ record `(index, value)`:

``` dart
for (final (i, v) in Arr.indexed(['a', 'b', 'c'])) {
  print('$i: $v'); // '0: a', '1: b', '2: c'
}
```

<div id="method-arr-interleave"></div>

#### `Arr.interleave()`

แทรก separator ระหว่างทุกคู่ของสมาชิก มีประโยชน์สำหรับสร้าง widget children ที่มี separator:

``` dart
Arr.interleave([1, 2, 3], 0); // [1, 0, 2, 0, 3]

Column(children: Arr.interleave(tiles, const Divider()));
```

<div id="method-arr-isassoc"></div>

#### `Arr.isAssoc()`

คืนค่า `true` เมื่อค่าเป็น `Map` (associative):

``` dart
Arr.isAssoc({'a': 1});  // true
Arr.isAssoc([1, 2, 3]); // false
```

<div id="method-arr-islist"></div>

#### `Arr.isList()`

คืนค่า `true` เมื่อค่าเป็น `List`:

``` dart
Arr.isList([1, 2, 3]); // true
Arr.isList({'a': 1});  // false
```

<div id="method-arr-join"></div>

#### `Arr.join()`

รวม list เป็น string สมาชิกสุดท้ายสามารถรวมด้วย separator ที่แตกต่างกัน:

``` dart
Arr.join(['Anna', 'Brad', 'Carol'], ', ', ' and ');
// 'Anna, Brad and Carol'

Arr.join(['a', 'b', 'c']);
// 'a, b, c'
```

<div id="method-arr-keyby"></div>

#### `Arr.keyBy()`

จัดทำดัชนี list ตามค่าที่ key ที่ระบุ รายการที่มีค่า `null` ที่ key นั้นจะถูกข้ามไป:

``` dart
Arr.keyBy([
  {'id': 1, 'name': 'Anna'},
  {'id': 2, 'name': 'Brad'},
], 'id');
// {1: {'id': 1, 'name': 'Anna'}, 2: {'id': 2, 'name': 'Brad'}}
```

<div id="method-arr-last"></div>

#### `Arr.last()`

คืนค่าสมาชิกสุดท้ายที่ตรงกับ predicate หรือค่า default:

``` dart
Arr.last([1, 2, 3, 4], predicate: (n) => n.isEven); // 4
Arr.last([1, 2, 3]);                                 // 3
```

<div id="method-arr-map"></div>

#### `Arr.map()`

map แต่ละสมาชิกผ่าน callback ที่รับ index ด้วย:

``` dart
Arr.map(['a', 'b'], (v, i) => '$i:$v'); // ['0:a', '1:b']
```

<div id="method-arr-mapwithkeys"></div>

#### `Arr.mapWithKeys()`

map แต่ละสมาชิกไปยัง `MapEntry` คืนค่า map ที่ได้:

``` dart
Arr.mapWithKeys(
  [{'id': 1, 'name': 'Anna'}, {'id': 2, 'name': 'Brad'}],
  (m) => MapEntry(m['id'], m['name']),
);
// {1: 'Anna', 2: 'Brad'}
```

<div id="method-arr-max"></div>

#### `Arr.max()`

คืนค่าสมาชิกที่มีค่า `by` มากที่สุด หรือสมาชิกที่ใหญ่ที่สุดเมื่อไม่ระบุ `by`:

``` dart
Arr.max([3, 1, 4, 1, 5]);            // 5
Arr.max(users, by: (u) => u.age);    // ผู้ใช้ที่มีอายุมากที่สุด
```

<div id="method-arr-median"></div>

#### `Arr.median()`

คืนค่ามัธยฐานของ list หรือ `0` เมื่อว่างเปล่า:

``` dart
Arr.median([1, 3, 5]);    // 3.0
Arr.median([1, 2, 3, 4]); // 2.5
```

<div id="method-arr-min"></div>

#### `Arr.min()`

คืนค่าสมาชิกที่มีค่า `by` น้อยที่สุด หรือสมาชิกที่เล็กที่สุดเมื่อไม่ระบุ `by`:

``` dart
Arr.min([3, 1, 4, 1, 5]);            // 1
Arr.min(users, by: (u) => u.age);    // ผู้ใช้ที่มีอายุน้อยที่สุด
```

<div id="method-arr-move"></div>

#### `Arr.move()`

คืนค่า list ใหม่โดยย้ายสมาชิกที่ตำแหน่ง `from` ไปยังตำแหน่ง `to` โดย `to` ถูก clamp ให้อยู่ในช่วงที่ถูกต้อง:

``` dart
Arr.move(['a', 'b', 'c', 'd'], 0, 2); // ['b', 'c', 'a', 'd']
```

<div id="method-arr-onlyvalues"></div>

#### `Arr.onlyValues()`

คืนค่าสมาชิกของ list ที่มีอยู่ในค่าที่กำหนดด้วย:

``` dart
Arr.onlyValues([1, 2, 3, 4], [2, 4, 9]); // [2, 4]
```

<div id="method-arr-partition"></div>

#### `Arr.partition()`

แบ่ง list เป็นคู่ `[matching, nonMatching]` ตาม predicate:

``` dart
final [evens, odds] = Arr.partition([1, 2, 3, 4], (n) => n.isEven);
// evens: [2, 4], odds: [1, 3]
```

<div id="method-arr-pluck"></div>

#### `Arr.pluck()`

ดึงค่าที่ key จากแต่ละ map ใน list:

``` dart
Arr.pluck([
  {'name': 'Anna'},
  {'name': 'Brad'},
], 'name');
// ['Anna', 'Brad']
```

<div id="method-arr-prepend"></div>

#### `Arr.prepend()`

คืนค่า list ใหม่โดยแทรกค่าไว้ด้านหน้า:

``` dart
Arr.prepend([2, 3], 1); // [1, 2, 3]
```

<div id="method-arr-push"></div>

#### `Arr.push()`

คืนค่า list ใหม่โดยเพิ่มค่าไว้ท้าย:

``` dart
Arr.push([1, 2], 3); // [1, 2, 3]
```

<div id="method-arr-random"></div>

#### `Arr.random()`

คืนค่าสมาชิกสุ่มของ list จะ throw เมื่อ list ว่างเปล่า:

``` dart
Arr.random(['rock', 'paper', 'scissors']); // หนึ่งในสามตัวเลือก
```

<div id="method-arr-randommany"></div>

#### `Arr.randomMany()`

คืนค่าสมาชิกสุ่มสูงสุด `count` ตัวโดยไม่ซ้ำ:

``` dart
Arr.randomMany([1, 2, 3, 4, 5], 2); // เช่น [3, 1]
```

<div id="method-arr-reject"></div>

#### `Arr.reject()`

กรอง list เหลือสมาชิกที่*ไม่*ตรงกับ predicate (ตรงข้ามกับ [`where`](#method-arr-where)):

``` dart
Arr.reject([1, 2, 3, 4], (n) => n.isEven); // [1, 3]
```

<div id="method-arr-replaceat"></div>

#### `Arr.replaceAt()`

คืนค่า list ใหม่โดยแทนที่สมาชิกที่ `index` ด้วย `value` จะ throw `RangeError` เมื่อ `index` อยู่นอกช่วง:

``` dart
Arr.replaceAt(['a', 'b', 'c'], 1, 'B'); // ['a', 'B', 'c']
```

<div id="method-arr-select"></div>

#### `Arr.select()`

คืนค่าแต่ละ map ใน list ที่ลดเหลือเฉพาะ key ที่กำหนด:

``` dart
Arr.select([
  {'name': 'Anna', 'role': 'admin', 'age': 30},
  {'name': 'Brad', 'role': 'user',  'age': 25},
], ['name', 'role']);
// [{'name': 'Anna', 'role': 'admin'}, {'name': 'Brad', 'role': 'user'}]
```

<div id="method-arr-shuffle"></div>

#### `Arr.shuffle()`

คืนค่าสำเนาของ list ที่ถูกสุ่ม ใช้ `seed` เพื่อให้ได้ผลลัพธ์ที่แน่นอน:

``` dart
Arr.shuffle([1, 2, 3, 4, 5]);
Arr.shuffle([1, 2, 3], seed: 42); // กำหนดได้แน่นอน
```

<div id="method-arr-sole"></div>

#### `Arr.sole()`

คืนค่าสมาชิกเดียวที่ตรงกับ predicate จะ throw เมื่อไม่มีหรือมีมากกว่าหนึ่งสมาชิกที่ตรง:

``` dart
Arr.sole([1, 2, 3, 4], predicate: (n) => n == 3); // 3
Arr.sole([1, 2, 3], predicate: (n) => n.isEven);  // 2
```

<div id="method-arr-some"></div>

#### `Arr.some()`

คืนค่า `true` เมื่อมีสมาชิกอย่างน้อยหนึ่งตัวผ่าน predicate:

``` dart
Arr.some([1, 2, 3], (n) => n.isEven); // true
Arr.some([1, 3, 5], (n) => n.isEven); // false
```

<div id="method-arr-sort"></div>

#### `Arr.sort()`

คืนค่าสำเนาของ list ที่เรียงลำดับแล้ว:

``` dart
Arr.sort([3, 1, 2]);                                // [1, 2, 3]
Arr.sort(users, compare: (a, b) => a.age - b.age);   // เรียงตามอายุจากน้อยไปมาก
```

<div id="method-arr-sortdesc"></div>

#### `Arr.sortDesc()`

คืนค่าสำเนาของ list ที่เรียงลำดับจากมากไปน้อย:

``` dart
Arr.sortDesc([3, 1, 2]); // [3, 2, 1]
```

<div id="method-arr-sortrecursive"></div>

#### `Arr.sortRecursive()`

เรียงลำดับ list แบบ recursive list ที่ซ้อนกันจะถูกเรียงในทุกความลึก:

``` dart
Arr.sortRecursive([[3, 1, 2], [9, 7, 8]]);
// [[1, 2, 3], [7, 8, 9]]
```

<div id="method-arr-sum"></div>

#### `Arr.sum()`

คืนค่าผลรวมของ list ใช้ `by` เพื่อดึงตัวเลขจากแต่ละสมาชิก:

``` dart
Arr.sum([1, 2, 3]);                          // 6
Arr.sum(orders, by: (o) => o.total);         // ผลรวมยอดรวม
```

<div id="method-arr-swap"></div>

#### `Arr.swap()`

คืนค่า list ใหม่โดยสลับตำแหน่งสมาชิกที่ `i` และ `j`:

``` dart
Arr.swap(['a', 'b', 'c'], 0, 2); // ['c', 'b', 'a']
```

<div id="method-arr-take"></div>

#### `Arr.take()`

คืนค่าสมาชิก `count` ตัวแรก หรือ `count` ตัวสุดท้ายหาก `count` เป็นลบ:

``` dart
Arr.take([1, 2, 3, 4, 5], 2);   // [1, 2]
Arr.take([1, 2, 3, 4, 5], -2);  // [4, 5]
```

<div id="method-arr-unique"></div>

#### `Arr.unique()`

คืนค่าสมาชิกที่ไม่ซ้ำกันของ list:

``` dart
Arr.unique([1, 2, 2, 3, 1]); // [1, 2, 3]
```

<div id="method-arr-where"></div>

#### `Arr.where()`

กรอง list เหลือสมาชิกที่ตรงกับ predicate:

``` dart
Arr.where([1, 2, 3, 4], (n) => n.isEven); // [2, 4]
```

<div id="method-arr-wherenotnull"></div>

#### `Arr.whereNotNull()`

คืนค่า list โดยนำค่า `null` ออก:

``` dart
Arr.whereNotNull([1, null, 2, null, 3]); // [1, 2, 3]
```

<div id="method-arr-wrap"></div>

#### `Arr.wrap()`

ห่อค่าใน `List` หากยังไม่เป็น list คืนค่า list ว่างเมื่อค่าเป็น `null`:

``` dart
Arr.wrap('foo');     // ['foo']
Arr.wrap([1, 2]);    // [1, 2]
Arr.wrap(null);      // []
```

<div id="strings"></div>

## Strings

<div id="method-str-after"></div>

#### `Str.after()`

คืนค่าส่วนของ `subject` หลังการเกิดขึ้นครั้งแรกของ `search`:

``` dart
Str.after('hello world hello', 'hello'); // ' world hello'
```

<div id="method-str-afterlast"></div>

#### `Str.afterLast()`

คืนค่าส่วนของ `subject` หลังการเกิดขึ้นครั้งสุดท้ายของ `search`:

``` dart
Str.afterLast('app/Http/Controllers', '/'); // 'Controllers'
```

<div id="method-str-before"></div>

#### `Str.before()`

คืนค่าส่วนของ `subject` ก่อนการเกิดขึ้นครั้งแรกของ `search`:

``` dart
Str.before('hello world', ' '); // 'hello'
```

<div id="method-str-beforelast"></div>

#### `Str.beforeLast()`

คืนค่าส่วนของ `subject` ก่อนการเกิดขึ้นครั้งสุดท้ายของ `search`:

``` dart
Str.beforeLast('app/Http/Controllers', '/'); // 'app/Http'
```

<div id="method-str-between"></div>

#### `Str.between()`

คืนค่าส่วนของ `subject` ระหว่าง `from` และ `to` (greedy):

``` dart
Str.between('[a] foo [b]', '[', ']'); // 'a] foo [b'
```

<div id="method-str-betweenfirst"></div>

#### `Str.betweenFirst()`

คืนค่าส่วนที่เล็กที่สุดของ `subject` ระหว่าง `from` และ `to`:

``` dart
Str.betweenFirst('[a] foo [b]', '[', ']'); // 'a'
```

<div id="method-str-camel"></div>

#### `Str.camel()`

แปลงค่าเป็น `camelCase`:

``` dart
Str.camel('foo_bar');     // 'fooBar'
Str.camel('Hello world'); // 'helloWorld'
```

<div id="method-str-charat"></div>

#### `Str.charAt()`

คืนค่าอักขระที่ index ที่กำหนด index ลบนับจากท้าย คืนค่า `null` เมื่ออยู่นอกช่วง:

``` dart
Str.charAt('hello', 1);  // 'e'
Str.charAt('hello', -1); // 'o'
Str.charAt('hello', 99); // null
```

<div id="method-str-contains"></div>

#### `Str.contains()`

ตรวจสอบว่า `haystack` มี `needles` ใดๆ อยู่หรือไม่:

``` dart
Str.contains('hello world', 'world');                       // true
Str.contains('hello world', ['cat', 'world']);              // true
Str.contains('Hello', 'hello', ignoreCase: true);           // true
```

<div id="method-str-containsall"></div>

#### `Str.containsAll()`

ตรวจสอบว่า `haystack` มี `needles` ทั้งหมดอยู่หรือไม่:

``` dart
Str.containsAll('hello world', ['hello', 'world']); // true
Str.containsAll('hello world', ['hello', 'cat']);   // false
```

<div id="method-str-deduplicate"></div>

#### `Str.deduplicate()`

แทนที่ `character` ที่ปรากฏต่อเนื่องกันด้วย instance เดียว:

``` dart
Str.deduplicate('hello   world');     // 'hello world'
Str.deduplicate('//path//to//', '/'); // '/path/to/'
```

<div id="method-str-endswith"></div>

#### `Str.endsWith()`

ตรวจสอบว่า `haystack` ลงท้ายด้วย `needles` ใดๆ หรือไม่:

``` dart
Str.endsWith('app.dart', '.dart');           // true
Str.endsWith('app.dart', ['.dart', '.ts']);  // true
```

<div id="method-str-excerpt"></div>

#### `Str.excerpt()`

ดึงส่วนย่อของข้อความรอบการเกิดขึ้นครั้งแรกของ `phrase` คืนค่า `null` เมื่อไม่พบ phrase:

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

เติม `cap` instance เดียวต่อท้ายค่า:

``` dart
Str.finish('hello', '!');     // 'hello!'
Str.finish('hello!!!', '!');  // 'hello!'
```

<div id="method-str-headline"></div>

#### `Str.headline()`

แยก string เป็นคำตามขอบเขตตัวพิมพ์ใหญ่ เครื่องหมายขีดล่าง ขีดกลาง และช่องว่าง จากนั้นแปลงเป็น title case:

``` dart
Str.headline('steve_jobs');           // 'Steve Jobs'
Str.headline('EmailNotificationSent'); // 'Email Notification Sent'
```

<div id="method-str-is"></div>

#### `Str.is_()`

ตรวจสอบว่าค่าตรงกับ pattern ที่กำหนดหรือไม่ โดย asterisk ทำหน้าที่เป็น wildcard:

``` dart
Str.is_('foo.*', 'foo.bar');                       // true
Str.is_(['admin/*', 'user/*'], 'admin/profile');   // true
Str.is_('foo', 'bar');                              // false
```

> **หมายเหตุ:** เครื่องหมายขีดล่างท้ายชื่อเพื่อหลีกเลี่ยง keyword ที่สงวนไว้ของ Dart คือ `is`

<div id="method-str-isascii"></div>

#### `Str.isAscii()`

คืนค่า `true` หากค่ามีเฉพาะอักขระ ASCII 7-bit:

``` dart
Str.isAscii('hello');  // true
Str.isAscii('héllo');  // false
```

<div id="method-str-isjson"></div>

#### `Str.isJson()`

คืนค่า `true` หากค่าเป็น JSON ที่ถูกต้อง:

``` dart
Str.isJson('{"a": 1}');   // true
Str.isJson('not json');   // false
```

<div id="method-str-isulid"></div>

#### `Str.isUlid()`

คืนค่า `true` หากค่าเป็น ULID (26 ตัวอักษร, Crockford base32):

``` dart
Str.isUlid('01ARZ3NDEKTSV4RRFFQ69G5FAV'); // true
Str.isUlid('not a ulid');                  // false
```

<div id="method-str-isurl"></div>

#### `Str.isUrl()`

คืนค่า `true` หากค่าเป็น URL ที่ถูกต้อง:

``` dart
Str.isUrl('https://nylo.dev'); // true
Str.isUrl('not a url');        // false
```

<div id="method-str-isuuid"></div>

#### `Str.isUuid()`

คืนค่า `true` หากค่าเป็น UUID ที่ถูกต้อง (ทุก version):

``` dart
Str.isUuid('a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11'); // true
Str.isUuid('hello');                                 // false
```

<div id="method-str-kebab"></div>

#### `Str.kebab()`

แปลงค่าเป็น `kebab-case`:

``` dart
Str.kebab('fooBar');      // 'foo-bar'
Str.kebab('Hello World'); // 'hello-world'
```

<div id="method-str-lcfirst"></div>

#### `Str.lcfirst()`

เปลี่ยนอักขระแรกของค่าเป็นตัวพิมพ์เล็ก:

``` dart
Str.lcfirst('Hello world'); // 'hello world'
```

<div id="method-str-length"></div>

#### `Str.length()`

คืนค่าความยาวของค่า:

``` dart
Str.length('hello'); // 5
```

<div id="method-str-limit"></div>

#### `Str.limit()`

ตัดค่าให้เหลือจำนวนอักขระที่กำหนดและเพิ่ม suffix:

``` dart
Str.limit('A long sentence', 6);              // 'A long...'
Str.limit('A long sentence', 6, '... read');  // 'A long... read'
```

<div id="method-str-lower"></div>

#### `Str.lower()`

แปลงค่าเป็นตัวพิมพ์เล็กทั้งหมด:

``` dart
Str.lower('HELLO'); // 'hello'
```

<div id="method-str-mask"></div>

#### `Str.mask()`

ซ่อนส่วนหนึ่งของ string ด้วยอักขระที่กำหนด เริ่มต้นที่ `index` ยาว `length` ตัวอักษร (หรือจนถึงท้ายเมื่อไม่ระบุ `length`):

``` dart
Str.mask('user@example.com', '*', 3);    // 'use*************'
Str.mask('user@example.com', '*', 3, 5); // 'use*****mple.com'
```

<div id="method-str-match"></div>

#### `Str.match()`

คืนค่า regex match แรกของ `pattern` ใน `subject` หรือ `null` เมื่อไม่พบ:

``` dart
Str.match(RegExp(r'\d+'), 'item 42 in 99 stock'); // '42'
```

> **หมายเหตุ:** นี่คือ regex matcher — แตกต่างจาก helper global [`match`](#method-match) ที่ใช้สำหรับการค้นหาค่าแบบ `Map`

<div id="method-str-matchall"></div>

#### `Str.matchAll()`

คืนค่า regex match ทั้งหมดของ `pattern` ใน `subject`:

``` dart
Str.matchAll(RegExp(r'\d+'), 'item 42 in 99 stock'); // ['42', '99']
```

<div id="method-str-padboth"></div>

#### `Str.padBoth()`

เติม `pad` ทั้งสองด้านของค่าจนถึง `length`:

``` dart
Str.padBoth('5', 5, '0'); // '00500'
```

<div id="method-str-padleft"></div>

#### `Str.padLeft()`

เติมด้านซ้ายของค่า:

``` dart
Str.padLeft('5', 3, '0'); // '005'
```

<div id="method-str-padnumber"></div>

#### `Str.padNumber()`

เติมศูนย์นำหน้า string ตัวเลข:

``` dart
Str.padNumber('5', 3); // '005'
```

<div id="method-str-padright"></div>

#### `Str.padRight()`

เติมด้านขวาของค่า:

``` dart
Str.padRight('5', 3, '0'); // '500'
```

<div id="method-str-password"></div>

#### `Str.password()`

สร้างรหัสผ่านแบบสุ่มที่ปลอดภัยด้านการเข้ารหัส สลับ character class ผ่าน named arguments:

``` dart
Str.password(12);                                   // ตัวอักษร + ตัวเลข + สัญลักษณ์
Str.password(12, symbols: false);                   // ตัวอักษร + ตัวเลขเท่านั้น
Str.password(8, letters: false, symbols: false);    // ตัวเลขเท่านั้น
```

<div id="method-str-position"></div>

#### `Str.position()`

คืนค่า index ของการเกิดขึ้นครั้งแรกของ `needle` ใน `haystack` หรือ `null` เมื่อไม่พบ:

``` dart
Str.position('hello world', 'world');             // 6
Str.position('hello world hello', 'hello', offset: 1); // 12
Str.position('hello', 'cat');                      // null
```

<div id="method-str-random"></div>

#### `Str.random()`

สร้าง string alphanumeric แบบสุ่มที่ปลอดภัยด้านการเข้ารหัสตามความยาวที่กำหนด:

``` dart
Str.random();    // 16 ตัวอักษร เช่น 'aB3k9XzMq7VnPsRt'
Str.random(8);   // 8 ตัวอักษร
```

<div id="method-str-remove"></div>

#### `Str.remove()`

นำ `search` ใดๆ ออกจาก `subject`:

``` dart
Str.remove('o', 'hello world');                  // 'hell wrld'
Str.remove(['e', 'l'], 'hello world');           // 'ho word'
Str.remove('Hello', 'hello world', caseSensitive: false); // ' world'
```

<div id="method-str-repeat"></div>

#### `Str.repeat()`

ทำซ้ำค่าตามจำนวนที่กำหนด:

``` dart
Str.repeat('ab', 3); // 'ababab'
```

<div id="method-str-replace"></div>

#### `Str.replace()`

แทนที่ทุกการเกิดขึ้นของ `search` ด้วย `replace` ใน `subject`:

``` dart
Str.replace('o', '0', 'hello world');           // 'hell0 w0rld'
Str.replace(['o', 'l'], '*', 'hello world');     // 'he*** w*r*d'
```

<div id="method-str-replacefirst"></div>

#### `Str.replaceFirst()`

แทนที่การเกิดขึ้นครั้งแรกของ `search` ด้วย `replace`:

``` dart
Str.replaceFirst('hello', 'hi', 'hello hello'); // 'hi hello'
```

<div id="method-str-replacelast"></div>

#### `Str.replaceLast()`

แทนที่การเกิดขึ้นครั้งสุดท้ายของ `search` ด้วย `replace`:

``` dart
Str.replaceLast('hello', 'hi', 'hello hello'); // 'hello hi'
```

<div id="method-str-reverse"></div>

#### `Str.reverse()`

กลับทิศทางค่า:

``` dart
Str.reverse('hello'); // 'olleh'
```

<div id="method-str-slug"></div>

#### `Str.slug()`

สร้าง slug ที่เป็นมิตรกับ URL จากชื่อเรื่อง:

``` dart
Str.slug('Hello World');                                  // 'hello-world'
Str.slug('Tech & Code', dictionary: {'&': 'and'});        // 'tech-and-code'
Str.slug('Hello World', separator: '_');                  // 'hello_world'
```

<div id="method-str-snake"></div>

#### `Str.snake()`

แปลงค่าเป็น `snake_case` ใช้ delimiter กำหนดเองเพื่อใช้สิ่งอื่นนอกจาก `_`:

``` dart
Str.snake('helloWorld');         // 'hello_world'
Str.snake('helloWorld', '-');    // 'hello-world'
```

<div id="method-str-squish"></div>

#### `Str.squish()`

ตัด whitespace นำหน้า/ท้าย และยุบ whitespace ภายในเป็น space เดียว:

``` dart
Str.squish('   hello    world   '); // 'hello world'
```

<div id="method-str-start"></div>

#### `Str.start()`

เริ่มต้นค่าด้วย `prefix` instance เดียว:

``` dart
Str.start('this/string', '/');     // '/this/string'
Str.start('//this/string', '/');   // '/this/string'
```

<div id="method-str-startswith"></div>

#### `Str.startsWith()`

ตรวจสอบว่า `haystack` เริ่มต้นด้วย `needles` ใดๆ หรือไม่:

``` dart
Str.startsWith('hello world', 'hello');           // true
Str.startsWith('hello world', ['cat', 'hello']);  // true
```

<div id="method-str-studly"></div>

#### `Str.studly()`

แปลงค่าเป็น `StudlyCase` / `PascalCase`:

``` dart
Str.studly('foo_bar');     // 'FooBar'
Str.studly('hello world'); // 'HelloWorld'
```

<div id="method-str-substr"></div>

#### `Str.substr()`

คืนค่า substring เริ่มต้นที่ `start` ยาว `length` อักขระ `start` ลบนับจากท้าย:

``` dart
Str.substr('profile', 4);       // 'ile'
Str.substr('profile', 4, 2);    // 'il'
Str.substr('profile', -3);      // 'ile'
```

<div id="method-str-substrcount"></div>

#### `Str.substrCount()`

นับการเกิดขึ้นของ `needle` ที่ไม่ทับซ้อนกันใน `haystack`:

``` dart
Str.substrCount('hello hello hello', 'hello'); // 3
```

<div id="method-str-swap"></div>

#### `Str.swap()`

แทนที่หลาย substring โดยใช้ `Map` ของคู่ค้นหา → แทนที่:

``` dart
Str.swap({'foo': 'bar', 'hello': 'hi'}, 'hello foo'); // 'hi bar'
```

<div id="method-str-take"></div>

#### `Str.take()`

คืนค่า `limit` อักขระแรกของค่า limit ลบคืนค่าจากท้าย:

``` dart
Str.take('Build something amazing!', 5); // 'Build'
Str.take('Build something amazing!', -9); // ' amazing!'
```

<div id="method-str-title"></div>

#### `Str.title()`

แปลงค่าเป็น `Title Case`:

``` dart
Str.title('a nice title'); // 'A Nice Title'
```

<div id="method-str-ucfirst"></div>

#### `Str.ucfirst()`

เปลี่ยนอักขระแรกของค่าเป็นตัวพิมพ์ใหญ่:

``` dart
Str.ucfirst('hello world'); // 'Hello world'
```

<div id="method-str-ucsplit"></div>

#### `Str.ucsplit()`

แยกค่าเป็นคำตามขอบเขตตัวพิมพ์ใหญ่:

``` dart
Str.ucsplit('fooBarBaz'); // ['foo', 'Bar', 'Baz']
```

<div id="method-str-ulid"></div>

#### `Str.ulid()`

สร้าง ULID (Universally Unique Lexicographically Sortable Identifier):

``` dart
Str.ulid(); // เช่น '01ARZ3NDEKTSV4RRFFQ69G5FAV'
```

<div id="method-str-unwrap"></div>

#### `Str.unwrap()`

นำ `before` instance เดียวออกจากต้น และ `after` (หรือ `before` หาก `after` เป็น null) จากท้าย:

``` dart
Str.unwrap('"value"', '"');             // 'value'
Str.unwrap('<p>html</p>', '<p>', '</p>'); // 'html'
```

<div id="method-str-upper"></div>

#### `Str.upper()`

แปลงค่าเป็นตัวพิมพ์ใหญ่ทั้งหมด:

``` dart
Str.upper('hello'); // 'HELLO'
```

<div id="method-str-uuid"></div>

#### `Str.uuid()`

สร้าง UUID v4 แบบสุ่ม:

``` dart
Str.uuid(); // เช่น 'a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11'
```

<div id="method-str-uuid7"></div>

#### `Str.uuid7()`

สร้าง UUID v7 (time-ordered, RFC 9562) UUIDv7 สามารถเรียงลำดับตามเวลาที่สร้าง:

``` dart
Str.uuid7(); // เช่น '018e0dc4-8b3f-7a2c-9d01-b1f6c87a92e3'
```

<div id="method-str-wordcount"></div>

#### `Str.wordCount()`

คืนค่าจำนวนคำของค่า:

``` dart
Str.wordCount('hello there friend'); // 3
```

<div id="method-str-words"></div>

#### `Str.words()`

จำกัดค่าให้เหลือจำนวนคำที่กำหนด:

``` dart
Str.words('Perfectly balanced, as all things should be.', 3);
// 'Perfectly balanced, as...'
```

<div id="method-str-wrap"></div>

#### `Str.wrap()`

ห่อค่าด้วย `before` และ `after` เมื่อไม่ระบุ `after` จะใช้ `before` ทั้งสองด้าน:

``` dart
Str.wrap('value', '"');             // '"value"'
Str.wrap('html', '<p>', '</p>');    // '<p>html</p>'
```

<div id="numbers"></div>

## Numbers

<div id="method-number-abbreviate"></div>

#### `Number.abbreviate()`

คืนค่ารูปแบบย่อของตัวเลข (`1K`, `1M`, `1B`):

``` dart
Number.abbreviate(1500);                  // '2K'
Number.abbreviate(1500, maxPrecision: 1); // '1.5K'
Number.abbreviate(2_500_000);             // '3M'
```

<div id="method-number-average"></div>

#### `Number.average()`

คืนค่าเฉลี่ยเลขคณิตของค่าต่างๆ หรือ `0` เมื่อว่างเปล่า:

``` dart
Number.average([1, 2, 3, 4]); // 2.5
```

<div id="method-number-between"></div>

#### `Number.between()`

คืนค่า `true` เมื่อค่าอยู่ระหว่าง `min` และ `max` (inclusive):

``` dart
Number.between(5, min: 0, max: 10);  // true
Number.between(15, min: 0, max: 10); // false
```

<div id="method-number-ceil"></div>

#### `Number.ceil()`

ปัดเศษขึ้นของค่าตามความแม่นยำที่กำหนด:

``` dart
Number.ceil(1.2);    // 2.0
Number.ceil(1.234, 2); // 1.24
```

<div id="method-number-clamp"></div>

#### `Number.clamp()`

จำกัดค่าให้อยู่ระหว่าง `min` และ `max` (inclusive):

``` dart
Number.clamp(5, 0, 10);   // 5
Number.clamp(15, 0, 10);  // 10
Number.clamp(-1, 0, 10);  // 0
```

<div id="method-number-currency"></div>

#### `Number.currency()`

จัดรูปแบบตัวเลขเป็นค่าสกุลเงิน ใช้ locale และสกุลเงินที่กำหนดค่าไว้เป็นค่าเริ่มต้น:

``` dart
Number.currency(1234.56);                            // '$1,234.56'
Number.currency(1234.56, currency: 'EUR');           // '€1,234.56'
Number.currency(1234.56, locale: 'de_DE', currency: 'EUR');
// '1.234,56 €'
```

<div id="method-number-defaultcurrency"></div>

#### `Number.defaultCurrency()`

คืนค่ารหัสสกุลเงินเริ่มต้นที่ใช้โดยเมธอดการจัดรูปแบบ:

``` dart
Number.defaultCurrency(); // 'USD'
```

<div id="method-number-defaultlocale"></div>

#### `Number.defaultLocale()`

คืนค่า locale เริ่มต้นที่ใช้โดยเมธอดการจัดรูปแบบ:

``` dart
Number.defaultLocale(); // 'en_US'
```

<div id="method-number-degrees"></div>

#### `Number.degrees()`

แปลง radian เป็นองศา:

``` dart
Number.degrees(3.14159);  // ~180.0
```

<div id="method-number-duration"></div>

#### `Number.duration()`

จัดรูปแบบจำนวนวินาทีเป็นช่วงเวลาที่อ่านได้ รูปแบบสั้นคืน `'1h 2m 5s'`; รูปแบบยาวคืน `'1:02:05'`:

``` dart
Number.duration(3661);                  // '1h 1m 1s'
Number.duration(3661, short: false);    // '1:01:01'
Number.duration(45);                    // '45s'
```

<div id="method-number-filesize"></div>

#### `Number.fileSize()`

จัดรูปแบบจำนวน byte เป็นขนาดไฟล์ที่อ่านได้:

``` dart
Number.fileSize(1024);                       // '1 KB'
Number.fileSize(1500, maxPrecision: 1);      // '1.5 KB'
Number.fileSize(1024 * 1024 * 1024);         // '1 GB'
```

<div id="method-number-floor"></div>

#### `Number.floor()`

ปัดเศษลงของค่าตามความแม่นยำที่กำหนด:

``` dart
Number.floor(1.7);      // 1.0
Number.floor(1.234, 2); // 1.23
```

<div id="method-number-forhumans"></div>

#### `Number.forHumans()`

คืนค่ารูปแบบตัวเลขที่อ่านได้ (`1 thousand`, `1 million`):

``` dart
Number.forHumans(1500);                  // '2 thousand'
Number.forHumans(1500, maxPrecision: 1); // '1.5 thousand'
Number.forHumans(2_500_000);             // '3 million'
```

<div id="method-number-format"></div>

#### `Number.format()`

จัดรูปแบบตัวเลขด้วยตัวคั่นที่รองรับ locale:

``` dart
Number.format(1234567);                          // '1,234,567'
Number.format(1234.5, precision: 2);             // '1,234.50'
Number.format(1234567, locale: 'de_DE');         // '1.234.567'
```

<div id="method-number-gcd"></div>

#### `Number.gcd()`

ตัวหารร่วมมากของจำนวนเต็มสองจำนวน:

``` dart
Number.gcd(12, 18); // 6
```

<div id="method-number-lcm"></div>

#### `Number.lcm()`

ตัวคูณร่วมน้อยของจำนวนเต็มสองจำนวน:

``` dart
Number.lcm(4, 6); // 12
```

<div id="method-number-lerp"></div>

#### `Number.lerp()`

interpolation เชิงเส้นระหว่าง `a` และ `b` ด้วย `t` (`0..1`):

``` dart
Number.lerp(0, 100, 0.25); // 25.0
```

<div id="method-number-max"></div>

#### `Number.max()`

คืนค่าที่มากที่สุดใน iterable:

``` dart
Number.max([3, 1, 4, 1, 5, 9, 2, 6]); // 9
```

<div id="method-number-median"></div>

#### `Number.median()`

คืนค่ามัธยฐานของค่าต่างๆ หรือ `0` เมื่อว่างเปล่า:

``` dart
Number.median([1, 3, 5]);    // 3.0
Number.median([1, 2, 3, 4]); // 2.5
```

<div id="method-number-min"></div>

#### `Number.min()`

คืนค่าที่น้อยที่สุดใน iterable:

``` dart
Number.min([3, 1, 4, 1, 5, 9, 2, 6]); // 1
```

<div id="method-number-ordinal"></div>

#### `Number.ordinal()`

คืนค่ารูปแบบ ordinal ของตัวเลข (`1st`, `2nd`, `3rd`, `4th`):

``` dart
Number.ordinal(1);   // '1st'
Number.ordinal(2);   // '2nd'
Number.ordinal(21);  // '21st'
Number.ordinal(112); // '112th'
```

<div id="method-number-pairs"></div>

#### `Number.pairs()`

สร้างคู่ (sub-range) จาก range ถึง `to` โดย step ด้วย `by`:

``` dart
Number.pairs(25, 10);             // [[0, 9], [10, 19], [20, 25]]
Number.pairs(25, 10, offset: 0);  // [[0, 10], [10, 20], [20, 25]]
```

<div id="method-number-parsefloat"></div>

#### `Number.parseFloat()`

แปลง string ที่รองรับ locale เป็น `double` คืนค่า `null` เมื่อล้มเหลว:

``` dart
Number.parseFloat('1,234.56');                    // 1234.56
Number.parseFloat('1.234,56', locale: 'de_DE');   // 1234.56
Number.parseFloat('not a number');                // null
```

<div id="method-number-parseint"></div>

#### `Number.parseInt()`

แปลง string ที่รองรับ locale เป็น `int` คืนค่า `null` เมื่อล้มเหลว:

``` dart
Number.parseInt('1,234');                    // 1234
Number.parseInt('1.234', locale: 'de_DE');   // 1234
```

<div id="method-number-percentage"></div>

#### `Number.percentage()`

จัดรูปแบบตัวเลขเป็นเปอร์เซ็นต์ ตัวเลขถือเป็นค่าเปอร์เซ็นต์เต็ม:

``` dart
Number.percentage(10);                  // '10%'
Number.percentage(10.5, precision: 1);  // '10.5%'
```

<div id="method-number-radians"></div>

#### `Number.radians()`

แปลงองศาเป็น radian:

``` dart
Number.radians(180); // ~3.14159
```

<div id="method-number-random"></div>

#### `Number.random()`

คืนค่าจำนวนเต็มสุ่มใน `[min, max]` (inclusive) ใช้ `seed` เพื่อได้ลำดับที่กำหนดได้:

``` dart
Number.random(min: 1, max: 100);              // int ใดก็ได้ 1..100
Number.random(min: 0, max: 9, seed: 42);      // กำหนดได้แน่นอน
```

<div id="method-number-range"></div>

#### `Number.range()`

สร้าง list ของจำนวนเต็มจาก `start` (inclusive) ถึง `end` (inclusive) โดย step ด้วย `step`:

``` dart
Number.range(1, 5);              // [1, 2, 3, 4, 5]
Number.range(0, 10, step: 2);    // [0, 2, 4, 6, 8, 10]
Number.range(5, 1, step: -1);    // [5, 4, 3, 2, 1]
```

<div id="method-number-round"></div>

#### `Number.round()`

ปัดเศษค่าตามความแม่นยำที่กำหนด:

``` dart
Number.round(1.5);      // 2.0
Number.round(1.234, 2); // 1.23
```

<div id="method-number-scale"></div>

#### `Number.scale()`

re-map ค่าจาก range หนึ่งไปยังอีก range หนึ่ง:

``` dart
Number.scale(0.5, fromMin: 0, fromMax: 1, toMin: 0, toMax: 100); // 50.0
Number.scale(75, fromMin: 0, fromMax: 100, toMin: -1, toMax: 1); // 0.5
```

<div id="method-number-spell"></div>

#### `Number.spell()`

เขียนตัวเลขเป็นคำในภาษาอังกฤษ:

``` dart
Number.spell(0);           // 'zero'
Number.spell(123);         // 'one hundred twenty-three'
Number.spell(1_234_567);   // 'one million two hundred thirty-four thousand five hundred sixty-seven'
```

<div id="method-number-spellordinal"></div>

#### `Number.spellOrdinal()`

เขียนรูปแบบ ordinal ของตัวเลขเป็นคำในภาษาอังกฤษ:

``` dart
Number.spellOrdinal(1);   // 'first'
Number.spellOrdinal(2);   // 'second'
Number.spellOrdinal(21);  // 'twenty-first'
```

<div id="method-number-sum"></div>

#### `Number.sum()`

คืนค่าผลรวมของค่าต่างๆ:

``` dart
Number.sum([1, 2, 3]); // 6
```

<div id="method-number-tobytes"></div>

#### `Number.toBytes()`

แปลงขนาดไฟล์เป็น byte ตรงข้ามกับ [`fileSize`](#method-number-filesize) ใช้ string ที่แปลงได้หรือตัวเลขพร้อม `unit`:

``` dart
Number.toBytes('1.5 KB');     // 1536
Number.toBytes(1.5, 'KB');    // 1536
Number.toBytes(1, 'GB');      // 1073741824
Number.toBytes('not bytes');  // null
```

<div id="method-number-trim"></div>

#### `Number.trim()`

นำศูนย์ท้ายออกจากส่วนทศนิยมของค่า:

``` dart
Number.trim(12.0);    // '12'
Number.trim(12.30);   // '12.3'
Number.trim(12.345);  // '12.345'
```

<div id="method-number-usecurrency"></div>

#### `Number.useCurrency()`

กำหนดรหัสสกุลเงินเริ่มต้นที่ใช้โดยเมธอดการจัดรูปแบบ:

``` dart
Number.useCurrency('EUR');
Number.currency(99); // '€99.00'
```

<div id="method-number-uselocale"></div>

#### `Number.useLocale()`

กำหนด locale เริ่มต้นที่ใช้โดยเมธอดการจัดรูปแบบ:

``` dart
Number.useLocale('de_DE');
Number.format(1234567); // '1.234.567'
```

<div id="objects"></div>

## Objects

`Obj` มีเครื่องมือสำหรับทำงานกับ map โดยใช้ path แบบ **dot-notation** สมาชิกของ List สามารถระบุด้วย integer index ได้ (เช่น `'users.0.name'`)

<div id="method-obj-add"></div>

#### `Obj.add()`

เพิ่มค่าที่ `key` เฉพาะเมื่อยังไม่มีค่าอยู่ที่นั่น:

``` dart
final user = {'name': 'Anna'};
Obj.add(user, 'role', 'admin');
// user: {'name': 'Anna', 'role': 'admin'}

Obj.add(user, 'name', 'Brad'); // no-op — name มีค่าอยู่แล้ว
```

<div id="method-obj-deepequals"></div>

#### `Obj.deepEquals()`

คืนค่า `true` เมื่อสองค่ามีโครงสร้างเท่ากัน (เปรียบเทียบ recursive ของ `Map` และ `List`):

``` dart
Obj.deepEquals({'a': [1, 2]}, {'a': [1, 2]}); // true
Obj.deepEquals({'a': 1}, {'a': 2});            // false
```

<div id="method-obj-divide"></div>

#### `Obj.divide()`

แบ่ง map เป็นคู่ `[keys, values]`:

``` dart
Obj.divide({'name': 'Desk', 'price': 100});
// [['name', 'price'], ['Desk', 100]]
```

<div id="method-obj-dot"></div>

#### `Obj.dot()`

ทำให้ map ที่ซ้อนกันแบนเป็น map ระดับเดียวที่ใช้ dot-path เป็น key:

``` dart
Obj.dot({'a': {'b': 1, 'c': 2}});
// {'a.b': 1, 'a.c': 2}
```

<div id="method-obj-except"></div>

#### `Obj.except()`

คืนค่า map ใหม่ที่มีทุก entry ยกเว้นที่มี key ที่กำหนด:

``` dart
Obj.except({'name': 'Anna', 'role': 'admin', 'age': 30}, ['age']);
// {'name': 'Anna', 'role': 'admin'}
```

<div id="method-obj-exists"></div>

#### `Obj.exists()`

คืนค่า `true` เมื่อ map มี key ที่กำหนดอยู่ที่ระดับบนสุด (ไม่ใช่การ traverse ด้วย dot-notation):

``` dart
Obj.exists({'a.b': 1}, 'a.b'); // true
Obj.exists({'a': {'b': 1}}, 'a.b'); // false (ใช้ `has` สำหรับการ traverse)
```

<div id="method-obj-flip"></div>

#### `Obj.flip()`

สลับ key และ value เมื่อ value ซ้ำกัน entry สุดท้ายจะชนะ:

``` dart
Obj.flip({'one': 1, 'two': 2}); // {1: 'one', 2: 'two'}
```

<div id="method-obj-forget"></div>

#### `Obj.forget()`

นำ key (dot notation) ออกจาก map คืนค่า map เพื่อ chaining:

``` dart
final user = {'profile': {'name': 'Anna', 'email': 'a@b'}};
Obj.forget(user, 'profile.email');
// user: {'profile': {'name': 'Anna'}}
```

<div id="method-obj-get"></div>

#### `Obj.get()`

คืนค่าที่ `key` โดยใช้ dot notation หรือค่า default เมื่อ path ไม่มีอยู่ Key ระดับบนสุดที่มีจุดจริงมีความสำคัญกว่าการ traverse:

``` dart
final user = {'profile': {'name': 'Anna', 'age': 30}};
Obj.get(user, 'profile.name');     // 'Anna'
Obj.get(user, 'profile.email');    // null
Obj.get(user, 'profile.email', 'unknown'); // 'unknown'

// List indices:
Obj.get({'users': [{'name': 'A'}]}, 'users.0.name'); // 'A'
```

<div id="method-obj-getbool"></div>

#### `Obj.getBool()`

คืนค่าที่ `key` โดยแปลงเป็น `bool` หรือค่า default ตัวเลขเป็น truthy เมื่อไม่ใช่ศูนย์; string รู้จัก `'true'`/`'false'`/`'1'`/`'0'`/`'yes'`/`'no'`/`'on'`/`'off'` (case-insensitive):

``` dart
Obj.getBool({'flag': 'yes'}, 'flag');     // true
Obj.getBool({'flag': 0}, 'flag');         // false
Obj.getBool({}, 'flag', false);           // false
```

<div id="method-obj-getdouble"></div>

#### `Obj.getDouble()`

คืนค่าที่ `key` โดยแปลงเป็น `double` หรือค่า default:

``` dart
Obj.getDouble({'price': '9.99'}, 'price'); // 9.99
Obj.getDouble({'price': 10}, 'price');     // 10.0
```

<div id="method-obj-getint"></div>

#### `Obj.getInt()`

คืนค่าที่ `key` โดยแปลงเป็น `int` หรือค่า default:

``` dart
Obj.getInt({'count': '42'}, 'count');     // 42
Obj.getInt({'count': 3.7}, 'count');      // 3 (ตัดทศนิยม)
Obj.getInt({}, 'count', 0);               // 0
```

<div id="method-obj-getlist"></div>

#### `Obj.getList()`

คืนค่าที่ `key` เมื่อเป็น `List` มิฉะนั้นคืนค่า default:

``` dart
Obj.getList({'items': [1, 2, 3]}, 'items'); // [1, 2, 3]
Obj.getList({'items': 'oops'}, 'items', []); // []
```

<div id="method-obj-getmap"></div>

#### `Obj.getMap()`

คืนค่าที่ `key` เมื่อเป็น `Map` มิฉะนั้นคืนค่า default:

``` dart
Obj.getMap({'meta': {'a': 1}}, 'meta'); // {'a': 1}
Obj.getMap({'meta': null}, 'meta', {}); // {}
```

<div id="method-obj-getstring"></div>

#### `Obj.getString()`

คืนค่าที่ `key` โดยแปลงเป็น `String` หรือค่า default:

``` dart
Obj.getString({'name': 'Anna'}, 'name'); // 'Anna'
Obj.getString({'count': 42}, 'count');   // '42'
Obj.getString({}, 'name', 'Guest');      // 'Guest'
```

<div id="method-obj-has"></div>

#### `Obj.has()`

คืนค่า `true` เมื่อ map มีค่าที่ `key` โดยใช้ dot notation:

``` dart
final user = {'profile': {'name': 'Anna'}};
Obj.has(user, 'profile.name');  // true
Obj.has(user, 'profile.email'); // false
```

<div id="method-obj-hasall"></div>

#### `Obj.hasAll()`

คืนค่า `true` เมื่อทุก key ที่กำหนด resolve ได้ใน map:

``` dart
Obj.hasAll({'a': 1, 'b': 2}, ['a', 'b']);      // true
Obj.hasAll({'a': 1, 'b': 2}, ['a', 'c']);      // false
```

<div id="method-obj-hasany"></div>

#### `Obj.hasAny()`

คืนค่า `true` เมื่อ key ใดๆ ที่กำหนด resolve ได้ใน map:

``` dart
Obj.hasAny({'a': 1}, ['a', 'b']); // true
Obj.hasAny({'a': 1}, ['x', 'y']); // false
```

<div id="method-obj-mapkeys"></div>

#### `Obj.mapKeys()`

คืนค่า map ใหม่โดยแต่ละ key ถูก transform ด้วย `fn`:

``` dart
Obj.mapKeys({'firstName': 'Anna'}, (k) => k.toLowerCase());
// {'firstname': 'Anna'}
```

<div id="method-obj-mapvalues"></div>

#### `Obj.mapValues()`

คืนค่า map ใหม่โดยแต่ละ value ถูก transform ด้วย `fn`:

``` dart
Obj.mapValues({'a': 1, 'b': 2}, (v) => v * 10);
// {'a': 10, 'b': 20}
```

<div id="method-obj-merge"></div>

#### `Obj.merge()`

รวม `source` เข้ากับ `target` แบบ recursive เมื่อ key ซ้ำ `source` ชนะ; map ที่ซ้อนกันจะถูกรวม:

``` dart
Obj.merge(
  {'a': {'x': 1}, 'b': 2},
  {'a': {'y': 9}, 'b': 22},
);
// {'a': {'x': 1, 'y': 9}, 'b': 22}
```

<div id="method-obj-only"></div>

#### `Obj.only()`

คืนค่า map ใหม่ที่มีเฉพาะ entry ที่มี key ที่กำหนด:

``` dart
Obj.only({'name': 'Anna', 'role': 'admin', 'age': 30}, ['name', 'role']);
// {'name': 'Anna', 'role': 'admin'}
```

<div id="method-obj-prependkeyswith"></div>

#### `Obj.prependKeysWith()`

เติม prefix ให้ทุก key ระดับบนสุดใน map:

``` dart
Obj.prependKeysWith({'name': 'Anna', 'age': 30}, 'user_');
// {'user_name': 'Anna', 'user_age': 30}
```

<div id="method-obj-pull"></div>

#### `Obj.pull()`

คืนค่าที่ `key` และนำออกจาก map:

``` dart
final user = {'name': 'Anna', 'temp': 'token'};
final temp = Obj.pull(user, 'temp'); // 'token'
// user: {'name': 'Anna'}
```

<div id="method-obj-query"></div>

#### `Obj.query()`

encode map เป็น URL query string:

``` dart
Obj.query({'name': 'Anna', 'tags': ['a', 'b']});
// 'name=Anna&tags%5B0%5D=a&tags%5B1%5D=b'

Obj.query({'filter': {'role': 'admin'}});
// 'filter%5Brole%5D=admin'
```

<div id="method-obj-set"></div>

#### `Obj.set()`

กำหนดค่าที่ `key` โดยสร้าง map ที่ซ้อนกันตาม path mutate map ใน place และคืนค่าเพื่อ chaining:

``` dart
final user = <String, dynamic>{};
Obj.set(user, 'profile.email', 'a@b');
// user: {'profile': {'email': 'a@b'}}
```

<div id="method-obj-undot"></div>

#### `Obj.undot()`

ขยาย map ที่มี key แบบจุดกลับเป็นโครงสร้างซ้อนกัน (ตรงข้ามกับ [`dot`](#method-obj-dot)):

``` dart
Obj.undot({'a.b': 1, 'a.c': 2});
// {'a': {'b': 1, 'c': 2}}
```

<div id="method-obj-wherenotempty"></div>

#### `Obj.whereNotEmpty()`

คืนค่า map ใหม่โดยนำ entry ที่มีค่า `null` หรือว่างเปล่า (`String`, `Iterable` หรือ `Map` ว่างเปล่า) ออก:

``` dart
Obj.whereNotEmpty({'name': 'Anna', 'tags': [], 'bio': ''});
// {'name': 'Anna'}
```

<div id="method-obj-wherenotnull"></div>

#### `Obj.whereNotNull()`

คืนค่า map ใหม่โดยนำ entry ที่มีค่า `null` ออก:

``` dart
Obj.whereNotNull({'name': 'Anna', 'email': null});
// {'name': 'Anna'}
```

<div id="miscellaneous"></div>

## เบ็ดเตล็ด

ฟังก์ชัน global เหล่านี้อยู่ใน `helper.dart` และสามารถใช้ได้ทุกที่ที่ import `nylo_framework`

<div id="method-api"></div>

#### `api()`

helper ลัดสำหรับส่ง API request ผ่าน `NyApiService` ดูหน้า [Networking](/docs/{{ $version }}/networking) สำหรับตัวเลือกทั้งหมด:

``` dart
await api<ApiService>((request) =>
  request.get('https://jsonplaceholder.typicode.com/posts'));
```

<div id="method-datatomodel"></div>

#### `dataToModel()`

แปลง raw data (โดยทั่วไปเป็น JSON `Map`) เป็น model ที่ลงทะเบียนใน `config/decoders.dart` type parameter เป็นสิ่งจำเป็น:

``` dart
final user = dataToModel<User>(data: {'name': 'Anna', 'age': 30});
```

<div id="method-dump"></div>

#### `dump()`

แสดงค่าบน console เคารพ flag `APP_DEBUG` ของแอป เว้นแต่ `alwaysPrint: true`:

``` dart
dump('Hello World');
dump(user, tag: 'current user');
dump(payload, alwaysPrint: true);
```

<div id="method-event"></div>

#### `event()`

เรียก event ของ type ที่กำหนดโดยใช้ `Nylo.events()` ดู [Events](/docs/{{ $version }}/events) สำหรับการใช้งานทั้งหมด:

``` dart
await event<LoginEvent>(data: {'user': 'Anna'});
```

<div id="method-getasset"></div>

#### `getAsset()`

คืนค่า path เต็มสำหรับ asset ในไดเรกทอรี `/assets`:

``` dart
final path = getAsset('videos/welcome.mp4');
// 'assets/videos/welcome.mp4'
```

<div id="method-getenv"></div>

#### `getEnv()`

คืนค่าจาก env ที่สร้างขึ้น (`env.g.dart`) คืนค่า default เมื่อ key ไม่ถูกตั้งค่า:

``` dart
final apiUrl = getEnv('API_URL');
final debug = getEnv('APP_DEBUG', defaultValue: false);
```

<div id="method-getimageasset"></div>

#### `getImageAsset()`

คืนค่า path เต็มสำหรับรูปภาพใน `/assets/images/`:

``` dart
Image.asset(getImageAsset('logo.png'));
```

<div id="method-loadjson"></div>

#### `loadJson()`

โหลดไฟล์ JSON จาก assets bundle ผลลัพธ์ถูก cache ไว้โดยค่าเริ่มต้น:

``` dart
final config = await loadJson<Map<String, dynamic>>('config/app.json');
```

<div id="method-match"></div>

#### `match()`

จับคู่ค่ากับ `Map` ของ case และคืนค่าที่สอดคล้องกัน throw เมื่อไม่มี case ที่ตรงและไม่มี `defaultValue`:

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

> **หมายเหตุ:** นี่คือการค้นหาค่าแบบ `Map` — แตกต่างจาก [`Str.match`](#method-str-match) ที่ทำ regex matching

<div id="method-now"></div>

#### `now()`

คืนค่า `DateTime` ปัจจุบัน:

``` dart
final timestamp = now();
```

<div id="method-nyhexcolor"></div>

#### `nyHexColor()`

แปลง string สีในรูปแบบ hex เป็น `Color` รับค่า 6 หรือ 8 หลัก พร้อมหรือไม่มี `#` นำหน้า:

``` dart
final brand = nyHexColor('#FF6B35');
final translucent = nyHexColor('80FF6B35'); // 8 หลัก (alpha ก่อน)
```

<div id="method-print-helpers"></div>

#### `printDebug()`, `printError()`, `printInfo()`, `printWarning()`, `printSuccess()`, `printVerbose()`, `printAlert()`, `printEmergency()`

logger ที่ติดแท็ก severity บน console ดูหน้า [Logging](/docs/{{ $version }}/logging) สำหรับการจัดรูปแบบ output และ flag `alwaysPrint`:

``` dart
printInfo('User signed in');
printError('Payment failed', context: {'order_id': 42});
printDebug({'state': state});
```

<div id="method-badge-helpers"></div>

#### `setBadgeNumber()`, `clearBadgeNumber()`

กำหนดหรือล้างจำนวน badge ของไอคอนแอป ดู [Local Notifications](/docs/{{ $version }}/local-notifications) สำหรับรายละเอียดการตั้งค่า platform:

``` dart
await setBadgeNumber(3);
await clearBadgeNumber();
```

<div id="method-shownextlog"></div>

#### `showNextLog()`

บังคับให้ log `NyLogger` ถัดไปแสดงแม้ `APP_DEBUG` เป็น `false` มีประโยชน์สำหรับ diagnostic ครั้งเดียวใน release build:

``` dart
showNextLog();
NyLogger.info('This will print regardless of APP_DEBUG');
```

<div id="method-sleep"></div>

#### `sleep()`

หน่วงการทำงานตาม duration ที่กำหนด argument แรกคือวินาที; argument ที่สองเป็นตัวเลือกเพิ่ม microsecond:

``` dart
await sleep(2);              // 2 วินาที
await sleep(0, 500_000);     // 500 มิลลิวินาที
```

<div id="method-trans"></div>

#### `trans()`

คืนค่า string ที่แปลแล้วตาม key ดู [Localization](/docs/{{ $version }}/localization) สำหรับการตั้งค่า:

``` dart
final greeting = trans('home.welcome');
final personalized = trans('home.hello', arguments: {'name': 'Anna'});
```
