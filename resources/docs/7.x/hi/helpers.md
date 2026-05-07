# Helpers

<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [परिचय](#introduction "परिचय")
- [उपलब्ध मेथड](#available-methods "उपलब्ध मेथड")
  - [Arrays](#arrays-method-list "Arrays")
  - [Strings](#strings-method-list "Strings")
  - [Numbers](#numbers-method-list "Numbers")
  - [Objects](#objects-method-list "Objects")
  - [विविध](#miscellaneous-method-list "विविध")
- [Arrays](#arrays "Arrays")
- [Strings](#strings "Strings")
- [Numbers](#numbers "Numbers")
- [Objects](#objects "Objects")
- [विविध](#miscellaneous "विविध")

<div id="introduction"></div>

## परिचय

{{ config('app.name') }} स्थैतिक उपयोगिता क्लासों का एक सेट प्रदान करता है — `Arr`, `Str`, `Number`, और `Obj` — साथ ही सामान्य कार्यों के लिए कुछ ग्लोबल हेल्पर फंक्शन। ये सभी `nylo_framework` से एक्सपोर्ट किए गए हैं:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

Arr.first([1, 2, 3]);             // 1
Str.slug('Hello World');          // 'hello-world'
Number.currency(1234.56);         // '$1,234.56'
Obj.get(user, 'profile.name');    // 'Anna'
```

> **नोट:** `Backpack`, `NyStorage`, `NyCache`, और `NyLogger` के अपने समर्पित पेज हैं: [Backpack](/docs/{{ $version }}/backpack), [Storage](/docs/{{ $version }}/storage), [Cache](/docs/{{ $version }}/cache), [Logging](/docs/{{ $version }}/logging).

<div id="available-methods"></div>

## उपलब्ध मेथड

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

### विविध

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

निर्धारित करता है कि दिया गया मान array-accessible है या नहीं (एक `List` या `Map`):

``` dart
Arr.accessible([1, 2, 3]);  // true
Arr.accessible({'a': 1});   // true
Arr.accessible('hello');    // false
Arr.accessible(null);       // false
```

<div id="method-arr-average"></div>

#### `Arr.average()`

सूची का अंकगणितीय माध्य लौटाता है, या खाली होने पर `0`। प्रत्येक तत्व से संख्या निकालने के लिए `by` पास करें:

``` dart
Arr.average([2, 4, 6]);                // 4.0
Arr.average(orders, by: (o) => o.total); // औसत कुल
```

<div id="method-arr-chunk"></div>

#### `Arr.chunk()`

एक सूची को दिए गए आकार के भागों में विभाजित करता है:

``` dart
Arr.chunk([1, 2, 3, 4, 5], 2);
// [[1, 2], [3, 4], [5]]
```

<div id="method-arr-collapse"></div>

#### `Arr.collapse()`

सूचियों की सूची को एक ही सूची में सिकोड़ता है:

``` dart
Arr.collapse([[1, 2], [3, 4], [5]]);
// [1, 2, 3, 4, 5]
```

<div id="method-arr-countby"></div>

#### `Arr.countBy()`

सूची में घटनाओं की गणना करता है, वैकल्पिक रूप से एक मान एक्सट्रैक्टर द्वारा समूहीकरण करता है:

``` dart
Arr.countBy(['a', 'b', 'a', 'c']);
// {'a': 2, 'b': 1, 'c': 1}

Arr.countBy(orders, by: (o) => o.status);
// {'paid': 12, 'pending': 3}
```

<div id="method-arr-crossjoin"></div>

#### `Arr.crossJoin()`

दी गई सूचियों को क्रॉस-जॉइन करता है, हर संभावित संयोजन लौटाता है:

``` dart
Arr.crossJoin([[1, 2], ['a', 'b']]);
// [[1, 'a'], [1, 'b'], [2, 'a'], [2, 'b']]
```

<div id="method-arr-every"></div>

#### `Arr.every()`

`true` लौटाता है जब सूची का हर तत्व predicate को पास करता हो। खाली सूचियों के लिए vacuously true:

``` dart
Arr.every([2, 4, 6], (n) => n.isEven); // true
Arr.every([1, 2, 3], (n) => n.isEven); // false
```

<div id="method-arr-exceptvalues"></div>

#### `Arr.exceptValues()`

दिए गए मानों को हटाकर सूची लौटाता है:

``` dart
Arr.exceptValues([1, 2, 3, 4], [2, 4]); // [1, 3]
```

<div id="method-arr-first"></div>

#### `Arr.first()`

predicate से मेल खाने वाला पहला तत्व लौटाता है, या डिफ़ॉल्ट मान:

``` dart
Arr.first([1, 2, 3]);                                   // 1
Arr.first([1, 2, 3, 4], predicate: (n) => n.isEven);    // 2
Arr.first([], defaultValue: 99);                        // 99
```

<div id="method-arr-flatmap"></div>

#### `Arr.flatMap()`

प्रत्येक तत्व को `fn` के माध्यम से मैप करता है और परिणामी iterables को समतल करता है:

``` dart
Arr.flatMap(pages, (p) => p.items); // सभी पेजों से सभी आइटम
Arr.flatMap([1, 2, 3], (n) => [n, n * 10]); // [1, 10, 2, 20, 3, 30]
```

<div id="method-arr-flatten"></div>

#### `Arr.flatten()`

नेस्टेड iterable को एकल-स्तरीय सूची में समतल करता है। `depth` समतल करने की गहराई सीमित करता है; `-1` का अर्थ असीमित है:

``` dart
Arr.flatten([1, [2, [3]]]);              // [1, 2, 3]
Arr.flatten([1, [2, [3]]], depth: 1);    // [1, 2, [3]]
```

<div id="method-arr-groupby"></div>

#### `Arr.groupBy()`

`by` द्वारा लौटाए गए मान से keyed map में सूची को समूहित करता है:

``` dart
Arr.groupBy(messages, (m) => m.date);
// {2024-01-01: [...], 2024-01-02: [...]}
```

<div id="method-arr-indexed"></div>

#### `Arr.indexed()`

प्रत्येक तत्व को उसके इंडेक्स के साथ जोड़ता है, `(index, value)` रिकॉर्ड की सूची लौटाता है:

``` dart
for (final (i, v) in Arr.indexed(['a', 'b', 'c'])) {
  print('$i: $v'); // '0: a', '1: b', '2: c'
}
```

<div id="method-arr-interleave"></div>

#### `Arr.interleave()`

तत्वों के हर जोड़े के बीच एक separator डालता है। widget children बनाने के लिए उपयोगी:

``` dart
Arr.interleave([1, 2, 3], 0); // [1, 0, 2, 0, 3]

Column(children: Arr.interleave(tiles, const Divider()));
```

<div id="method-arr-isassoc"></div>

#### `Arr.isAssoc()`

`true` लौटाता है जब मान एक `Map` (associative) हो:

``` dart
Arr.isAssoc({'a': 1});  // true
Arr.isAssoc([1, 2, 3]); // false
```

<div id="method-arr-islist"></div>

#### `Arr.isList()`

`true` लौटाता है जब मान एक `List` हो:

``` dart
Arr.isList([1, 2, 3]); // true
Arr.isList({'a': 1});  // false
```

<div id="method-arr-join"></div>

#### `Arr.join()`

सूची को एक string में जोड़ता है। अंतिम तत्व को एक अलग separator से जोड़ा जा सकता है:

``` dart
Arr.join(['Anna', 'Brad', 'Carol'], ', ', ' and ');
// 'Anna, Brad and Carol'

Arr.join(['a', 'b', 'c']);
// 'a, b, c'
```

<div id="method-arr-keyby"></div>

#### `Arr.keyBy()`

एक key पर मान द्वारा सूची को इंडेक्स करता है। उस key पर `null` मान वाली प्रविष्टियाँ छोड़ी जाती हैं:

``` dart
Arr.keyBy([
  {'id': 1, 'name': 'Anna'},
  {'id': 2, 'name': 'Brad'},
], 'id');
// {1: {'id': 1, 'name': 'Anna'}, 2: {'id': 2, 'name': 'Brad'}}
```

<div id="method-arr-last"></div>

#### `Arr.last()`

predicate से मेल खाने वाला अंतिम तत्व लौटाता है, या डिफ़ॉल्ट मान:

``` dart
Arr.last([1, 2, 3, 4], predicate: (n) => n.isEven); // 4
Arr.last([1, 2, 3]);                                 // 3
```

<div id="method-arr-map"></div>

#### `Arr.map()`

प्रत्येक तत्व को एक callback के माध्यम से मैप करता है जो इंडेक्स भी प्राप्त करता है:

``` dart
Arr.map(['a', 'b'], (v, i) => '$i:$v'); // ['0:a', '1:b']
```

<div id="method-arr-mapwithkeys"></div>

#### `Arr.mapWithKeys()`

प्रत्येक तत्व को एक `MapEntry` में मैप करता है, परिणामी map लौटाता है:

``` dart
Arr.mapWithKeys(
  [{'id': 1, 'name': 'Anna'}, {'id': 2, 'name': 'Brad'}],
  (m) => MapEntry(m['id'], m['name']),
);
// {1: 'Anna', 2: 'Brad'}
```

<div id="method-arr-max"></div>

#### `Arr.max()`

`by` के सबसे बड़े मान वाला तत्व लौटाता है, या `by` छोड़ने पर सबसे बड़ा तत्व:

``` dart
Arr.max([3, 1, 4, 1, 5]);            // 5
Arr.max(users, by: (u) => u.age);    // सबसे अधिक उम्र वाला उपयोगकर्ता
```

<div id="method-arr-median"></div>

#### `Arr.median()`

सूची का माध्यिका लौटाता है, या खाली होने पर `0`:

``` dart
Arr.median([1, 3, 5]);    // 3.0
Arr.median([1, 2, 3, 4]); // 2.5
```

<div id="method-arr-min"></div>

#### `Arr.min()`

`by` के सबसे छोटे मान वाला तत्व लौटाता है, या `by` छोड़ने पर सबसे छोटा तत्व:

``` dart
Arr.min([3, 1, 4, 1, 5]);            // 1
Arr.min(users, by: (u) => u.age);    // सबसे कम उम्र वाला उपयोगकर्ता
```

<div id="method-arr-move"></div>

#### `Arr.move()`

`from` पर तत्व को `to` स्थान पर ले जाकर नई सूची लौटाता है। `to` वैध रेंज तक clamped है:

``` dart
Arr.move(['a', 'b', 'c', 'd'], 0, 2); // ['b', 'c', 'a', 'd']
```

<div id="method-arr-onlyvalues"></div>

#### `Arr.onlyValues()`

सूची के वे तत्व लौटाता है जो दिए गए मानों में भी हैं:

``` dart
Arr.onlyValues([1, 2, 3, 4], [2, 4, 9]); // [2, 4]
```

<div id="method-arr-partition"></div>

#### `Arr.partition()`

predicate के आधार पर सूची को `[matching, nonMatching]` जोड़े में विभाजित करता है:

``` dart
final [evens, odds] = Arr.partition([1, 2, 3, 4], (n) => n.isEven);
// evens: [2, 4], odds: [1, 3]
```

<div id="method-arr-pluck"></div>

#### `Arr.pluck()`

सूची में प्रत्येक map से एक key पर मान निकालता है:

``` dart
Arr.pluck([
  {'name': 'Anna'},
  {'name': 'Brad'},
], 'name');
// ['Anna', 'Brad']
```

<div id="method-arr-prepend"></div>

#### `Arr.prepend()`

सामने मान डालकर नई सूची लौटाता है:

``` dart
Arr.prepend([2, 3], 1); // [1, 2, 3]
```

<div id="method-arr-push"></div>

#### `Arr.push()`

अंत में मान जोड़कर नई सूची लौटाता है:

``` dart
Arr.push([1, 2], 3); // [1, 2, 3]
```

<div id="method-arr-random"></div>

#### `Arr.random()`

सूची का एक यादृच्छिक तत्व लौटाता है। सूची खाली होने पर throws:

``` dart
Arr.random(['rock', 'paper', 'scissors']); // तीनों में से एक
```

<div id="method-arr-randommany"></div>

#### `Arr.randomMany()`

बिना replacement के `count` तक यादृच्छिक तत्व लौटाता है:

``` dart
Arr.randomMany([1, 2, 3, 4, 5], 2); // उदा. [3, 1]
```

<div id="method-arr-reject"></div>

#### `Arr.reject()`

predicate से *मेल न खाने* वाले तत्वों को फ़िल्टर करता है ([`where`](#method-arr-where) का उलटा):

``` dart
Arr.reject([1, 2, 3, 4], (n) => n.isEven); // [1, 3]
```

<div id="method-arr-replaceat"></div>

#### `Arr.replaceAt()`

`index` पर तत्व को `value` से बदलकर नई सूची लौटाता है। `index` रेंज से बाहर होने पर `RangeError` throws:

``` dart
Arr.replaceAt(['a', 'b', 'c'], 1, 'B'); // ['a', 'B', 'c']
```

<div id="method-arr-select"></div>

#### `Arr.select()`

सूची में प्रत्येक map को केवल दिए गए keys तक सीमित करके लौटाता है:

``` dart
Arr.select([
  {'name': 'Anna', 'role': 'admin', 'age': 30},
  {'name': 'Brad', 'role': 'user',  'age': 25},
], ['name', 'role']);
// [{'name': 'Anna', 'role': 'admin'}, {'name': 'Brad', 'role': 'user'}]
```

<div id="method-arr-shuffle"></div>

#### `Arr.shuffle()`

सूची की फेरबदल की गई कॉपी लौटाता है। निर्धारणवाद के लिए `seed` पास करें:

``` dart
Arr.shuffle([1, 2, 3, 4, 5]);
Arr.shuffle([1, 2, 3], seed: 42); // निर्धारणवादी
```

<div id="method-arr-sole"></div>

#### `Arr.sole()`

predicate से मेल खाने वाला एकल तत्व लौटाता है। शून्य या एक से अधिक तत्व मेल खाने पर throws:

``` dart
Arr.sole([1, 2, 3, 4], predicate: (n) => n == 3); // 3
Arr.sole([1, 2, 3], predicate: (n) => n.isEven);  // 2
```

<div id="method-arr-some"></div>

#### `Arr.some()`

`true` लौटाता है जब कम से कम एक तत्व predicate को पास करे:

``` dart
Arr.some([1, 2, 3], (n) => n.isEven); // true
Arr.some([1, 3, 5], (n) => n.isEven); // false
```

<div id="method-arr-sort"></div>

#### `Arr.sort()`

सूची की क्रमबद्ध कॉपी लौटाता है:

``` dart
Arr.sort([3, 1, 2]);                                // [1, 2, 3]
Arr.sort(users, compare: (a, b) => a.age - b.age);   // उम्र के अनुसार आरोही
```

<div id="method-arr-sortdesc"></div>

#### `Arr.sortDesc()`

सूची की अवरोही क्रम में क्रमबद्ध कॉपी लौटाता है:

``` dart
Arr.sortDesc([3, 1, 2]); // [3, 2, 1]
```

<div id="method-arr-sortrecursive"></div>

#### `Arr.sortRecursive()`

सूची को पुनरावर्ती रूप से क्रमबद्ध करता है। नेस्टेड सूचियाँ हर गहराई पर क्रमबद्ध होती हैं:

``` dart
Arr.sortRecursive([[3, 1, 2], [9, 7, 8]]);
// [[1, 2, 3], [7, 8, 9]]
```

<div id="method-arr-sum"></div>

#### `Arr.sum()`

सूची का योग लौटाता है। प्रत्येक तत्व से संख्या निकालने के लिए `by` पास करें:

``` dart
Arr.sum([1, 2, 3]);                          // 6
Arr.sum(orders, by: (o) => o.total);         // कुल का योग
```

<div id="method-arr-swap"></div>

#### `Arr.swap()`

`i` और `j` पर तत्वों को बदलकर नई सूची लौटाता है:

``` dart
Arr.swap(['a', 'b', 'c'], 0, 2); // ['c', 'b', 'a']
```

<div id="method-arr-take"></div>

#### `Arr.take()`

पहले `count` तत्व लौटाता है, या `count` ऋणात्मक होने पर अंतिम `count`:

``` dart
Arr.take([1, 2, 3, 4, 5], 2);   // [1, 2]
Arr.take([1, 2, 3, 4, 5], -2);  // [4, 5]
```

<div id="method-arr-unique"></div>

#### `Arr.unique()`

सूची के अद्वितीय मान लौटाता है:

``` dart
Arr.unique([1, 2, 2, 3, 1]); // [1, 2, 3]
```

<div id="method-arr-where"></div>

#### `Arr.where()`

predicate से मेल खाने वाले तत्वों को फ़िल्टर करता है:

``` dart
Arr.where([1, 2, 3, 4], (n) => n.isEven); // [2, 4]
```

<div id="method-arr-wherenotnull"></div>

#### `Arr.whereNotNull()`

`null` मान हटाकर सूची लौटाता है:

``` dart
Arr.whereNotNull([1, null, 2, null, 3]); // [1, 2, 3]
```

<div id="method-arr-wrap"></div>

#### `Arr.wrap()`

यदि मान पहले से `List` नहीं है तो उसे `List` में लपेटता है। `null` होने पर खाली सूची लौटाता है:

``` dart
Arr.wrap('foo');     // ['foo']
Arr.wrap([1, 2]);    // [1, 2]
Arr.wrap(null);      // []
```

<div id="strings"></div>

## Strings

<div id="method-str-after"></div>

#### `Str.after()`

`search` की पहली घटना के बाद `subject` का भाग लौटाता है:

``` dart
Str.after('hello world hello', 'hello'); // ' world hello'
```

<div id="method-str-afterlast"></div>

#### `Str.afterLast()`

`search` की अंतिम घटना के बाद `subject` का भाग लौटाता है:

``` dart
Str.afterLast('app/Http/Controllers', '/'); // 'Controllers'
```

<div id="method-str-before"></div>

#### `Str.before()`

`search` की पहली घटना से पहले `subject` का भाग लौटाता है:

``` dart
Str.before('hello world', ' '); // 'hello'
```

<div id="method-str-beforelast"></div>

#### `Str.beforeLast()`

`search` की अंतिम घटना से पहले `subject` का भाग लौटाता है:

``` dart
Str.beforeLast('app/Http/Controllers', '/'); // 'app/Http'
```

<div id="method-str-between"></div>

#### `Str.between()`

`from` और `to` के बीच `subject` का भाग लौटाता है (greedy):

``` dart
Str.between('[a] foo [b]', '[', ']'); // 'a] foo [b'
```

<div id="method-str-betweenfirst"></div>

#### `Str.betweenFirst()`

`from` और `to` के बीच `subject` का सबसे छोटा भाग लौटाता है:

``` dart
Str.betweenFirst('[a] foo [b]', '[', ']'); // 'a'
```

<div id="method-str-camel"></div>

#### `Str.camel()`

मान को `camelCase` में परिवर्तित करता है:

``` dart
Str.camel('foo_bar');     // 'fooBar'
Str.camel('Hello world'); // 'helloWorld'
```

<div id="method-str-charat"></div>

#### `Str.charAt()`

दिए गए इंडेक्स पर वर्ण लौटाता है। ऋणात्मक इंडेक्स अंत से गिनते हैं। रेंज से बाहर होने पर `null` लौटाता है:

``` dart
Str.charAt('hello', 1);  // 'e'
Str.charAt('hello', -1); // 'o'
Str.charAt('hello', 99); // null
```

<div id="method-str-contains"></div>

#### `Str.contains()`

निर्धारित करता है कि `haystack` में `needles` में से कोई भी है:

``` dart
Str.contains('hello world', 'world');                       // true
Str.contains('hello world', ['cat', 'world']);              // true
Str.contains('Hello', 'hello', ignoreCase: true);           // true
```

<div id="method-str-containsall"></div>

#### `Str.containsAll()`

निर्धारित करता है कि `haystack` में सभी `needles` हैं:

``` dart
Str.containsAll('hello world', ['hello', 'world']); // true
Str.containsAll('hello world', ['hello', 'cat']);   // false
```

<div id="method-str-deduplicate"></div>

#### `Str.deduplicate()`

`character` के लगातार रनों को एक ही instance से बदलता है:

``` dart
Str.deduplicate('hello   world');     // 'hello world'
Str.deduplicate('//path//to//', '/'); // '/path/to/'
```

<div id="method-str-endswith"></div>

#### `Str.endsWith()`

निर्धारित करता है कि `haystack` किसी भी `needles` से समाप्त होता है:

``` dart
Str.endsWith('app.dart', '.dart');           // true
Str.endsWith('app.dart', ['.dart', '.ts']);  // true
```

<div id="method-str-excerpt"></div>

#### `Str.excerpt()`

`phrase` की पहली घटना के आसपास पाठ का एक अंश निकालता है। phrase न मिलने पर `null` लौटाता है:

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

मान को `cap` के एकल instance के साथ समाप्त करता है:

``` dart
Str.finish('hello', '!');     // 'hello!'
Str.finish('hello!!!', '!');  // 'hello!'
```

<div id="method-str-headline"></div>

#### `Str.headline()`

अपरकेस सीमाओं, अंडरस्कोर, हाइफन, और whitespace पर string को शब्दों में विभाजित करता है, फिर title case में परिवर्तित करता है:

``` dart
Str.headline('steve_jobs');           // 'Steve Jobs'
Str.headline('EmailNotificationSent'); // 'Email Notification Sent'
```

<div id="method-str-is"></div>

#### `Str.is_()`

निर्धारित करता है कि मान दिए गए pattern से मेल खाता है। Asterisks wildcard के रूप में काम करते हैं:

``` dart
Str.is_('foo.*', 'foo.bar');                       // true
Str.is_(['admin/*', 'user/*'], 'admin/profile');   // true
Str.is_('foo', 'bar');                              // false
```

> **नोट:** अनुगामी underscore Dart के reserved keyword `is` से बचने के लिए है।

<div id="method-str-isascii"></div>

#### `Str.isAscii()`

`true` लौटाता है यदि मान में केवल 7-bit ASCII वर्ण हैं:

``` dart
Str.isAscii('hello');  // true
Str.isAscii('héllo');  // false
```

<div id="method-str-isjson"></div>

#### `Str.isJson()`

`true` लौटाता है यदि मान valid JSON है:

``` dart
Str.isJson('{"a": 1}');   // true
Str.isJson('not json');   // false
```

<div id="method-str-isulid"></div>

#### `Str.isUlid()`

`true` लौटाता है यदि मान एक ULID है (26 chars, Crockford base32):

``` dart
Str.isUlid('01ARZ3NDEKTSV4RRFFQ69G5FAV'); // true
Str.isUlid('not a ulid');                  // false
```

<div id="method-str-isurl"></div>

#### `Str.isUrl()`

`true` लौटाता है यदि मान एक valid URL है:

``` dart
Str.isUrl('https://nylo.dev'); // true
Str.isUrl('not a url');        // false
```

<div id="method-str-isuuid"></div>

#### `Str.isUuid()`

`true` लौटाता है यदि मान एक valid UUID है (कोई भी version):

``` dart
Str.isUuid('a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11'); // true
Str.isUuid('hello');                                 // false
```

<div id="method-str-kebab"></div>

#### `Str.kebab()`

मान को `kebab-case` में परिवर्तित करता है:

``` dart
Str.kebab('fooBar');      // 'foo-bar'
Str.kebab('Hello World'); // 'hello-world'
```

<div id="method-str-lcfirst"></div>

#### `Str.lcfirst()`

मान के पहले वर्ण को lowercase में करता है:

``` dart
Str.lcfirst('Hello world'); // 'hello world'
```

<div id="method-str-length"></div>

#### `Str.length()`

मान की लंबाई लौटाता है:

``` dart
Str.length('hello'); // 5
```

<div id="method-str-limit"></div>

#### `Str.limit()`

मान को दिए गए वर्णों की संख्या तक छोटा करता है और suffix जोड़ता है:

``` dart
Str.limit('A long sentence', 6);              // 'A long...'
Str.limit('A long sentence', 6, '... read');  // 'A long... read'
```

<div id="method-str-lower"></div>

#### `Str.lower()`

मान को lowercase में करता है:

``` dart
Str.lower('HELLO'); // 'hello'
```

<div id="method-str-mask"></div>

#### `Str.mask()`

`index` से शुरू होकर `length` chars के लिए string के एक भाग को एक character से मास्क करता है (या `length` छोड़ने पर अंत तक):

``` dart
Str.mask('user@example.com', '*', 3);    // 'use*************'
Str.mask('user@example.com', '*', 3, 5); // 'use*****mple.com'
```

<div id="method-str-match"></div>

#### `Str.match()`

`subject` में `pattern` का पहला regex match लौटाता है, या कोई match न होने पर `null`:

``` dart
Str.match(RegExp(r'\d+'), 'item 42 in 99 stock'); // '42'
```

> **नोट:** यह एक regex matcher है — global [`match`](#method-match) helper से अलग जो `Map`-आधारित मान lookup के लिए उपयोग होता है।

<div id="method-str-matchall"></div>

#### `Str.matchAll()`

`subject` में `pattern` के सभी regex matches लौटाता है:

``` dart
Str.matchAll(RegExp(r'\d+'), 'item 42 in 99 stock'); // ['42', '99']
```

<div id="method-str-padboth"></div>

#### `Str.padBoth()`

`length` तक पहुँचने तक मान के दोनों तरफ `pad` से पैड करता है:

``` dart
Str.padBoth('5', 5, '0'); // '00500'
```

<div id="method-str-padleft"></div>

#### `Str.padLeft()`

मान के बाईं ओर पैड करता है:

``` dart
Str.padLeft('5', 3, '0'); // '005'
```

<div id="method-str-padnumber"></div>

#### `Str.padNumber()`

एक numeric string को leading zeros से पैड करता है:

``` dart
Str.padNumber('5', 3); // '005'
```

<div id="method-str-padright"></div>

#### `Str.padRight()`

मान के दाईं ओर पैड करता है:

``` dart
Str.padRight('5', 3, '0'); // '500'
```

<div id="method-str-password"></div>

#### `Str.password()`

एक cryptographically random पासवर्ड जनरेट करता है। named arguments के माध्यम से character classes toggle करें:

``` dart
Str.password(12);                                   // अक्षर + संख्याएँ + प्रतीक
Str.password(12, symbols: false);                   // केवल अक्षर + संख्याएँ
Str.password(8, letters: false, symbols: false);    // केवल अंक
```

<div id="method-str-position"></div>

#### `Str.position()`

`haystack` में `needle` की पहली घटना का इंडेक्स लौटाता है, या न मिलने पर `null`:

``` dart
Str.position('hello world', 'world');             // 6
Str.position('hello world hello', 'hello', offset: 1); // 12
Str.position('hello', 'cat');                      // null
```

<div id="method-str-random"></div>

#### `Str.random()`

दिए गए लंबाई का cryptographically random alphanumeric string जनरेट करता है:

``` dart
Str.random();    // 16 chars, उदा. 'aB3k9XzMq7VnPsRt'
Str.random(8);   // 8 chars
```

<div id="method-str-remove"></div>

#### `Str.remove()`

`subject` से `search` में से कोई भी हटाता है:

``` dart
Str.remove('o', 'hello world');                  // 'hell wrld'
Str.remove(['e', 'l'], 'hello world');           // 'ho word'
Str.remove('Hello', 'hello world', caseSensitive: false); // ' world'
```

<div id="method-str-repeat"></div>

#### `Str.repeat()`

मान को दिए गए बार दोहराता है:

``` dart
Str.repeat('ab', 3); // 'ababab'
```

<div id="method-str-replace"></div>

#### `Str.replace()`

`subject` में `search` की हर घटना को `replace` से बदलता है:

``` dart
Str.replace('o', '0', 'hello world');           // 'hell0 w0rld'
Str.replace(['o', 'l'], '*', 'hello world');     // 'he*** w*r*d'
```

<div id="method-str-replacefirst"></div>

#### `Str.replaceFirst()`

`search` की पहली घटना को `replace` से बदलता है:

``` dart
Str.replaceFirst('hello', 'hi', 'hello hello'); // 'hi hello'
```

<div id="method-str-replacelast"></div>

#### `Str.replaceLast()`

`search` की अंतिम घटना को `replace` से बदलता है:

``` dart
Str.replaceLast('hello', 'hi', 'hello hello'); // 'hello hi'
```

<div id="method-str-reverse"></div>

#### `Str.reverse()`

मान को उलटता है:

``` dart
Str.reverse('hello'); // 'olleh'
```

<div id="method-str-slug"></div>

#### `Str.slug()`

शीर्षक से URL-friendly slug जनरेट करता है:

``` dart
Str.slug('Hello World');                                  // 'hello-world'
Str.slug('Tech & Code', dictionary: {'&': 'and'});        // 'tech-and-code'
Str.slug('Hello World', separator: '_');                  // 'hello_world'
```

<div id="method-str-snake"></div>

#### `Str.snake()`

मान को `snake_case` में परिवर्तित करता है। `_` के अलावा कुछ और उपयोग करने के लिए custom delimiter पास करें:

``` dart
Str.snake('helloWorld');         // 'hello_world'
Str.snake('helloWorld', '-');    // 'hello-world'
```

<div id="method-str-squish"></div>

#### `Str.squish()`

leading/trailing whitespace ट्रिम करता है और आंतरिक whitespace को एकल spaces में सिकोड़ता है:

``` dart
Str.squish('   hello    world   '); // 'hello world'
```

<div id="method-str-start"></div>

#### `Str.start()`

मान को `prefix` के एकल instance से शुरू करता है:

``` dart
Str.start('this/string', '/');     // '/this/string'
Str.start('//this/string', '/');   // '/this/string'
```

<div id="method-str-startswith"></div>

#### `Str.startsWith()`

निर्धारित करता है कि `haystack` किसी भी `needles` से शुरू होता है:

``` dart
Str.startsWith('hello world', 'hello');           // true
Str.startsWith('hello world', ['cat', 'hello']);  // true
```

<div id="method-str-studly"></div>

#### `Str.studly()`

मान को `StudlyCase` / `PascalCase` में परिवर्तित करता है:

``` dart
Str.studly('foo_bar');     // 'FooBar'
Str.studly('hello world'); // 'HelloWorld'
```

<div id="method-str-substr"></div>

#### `Str.substr()`

`start` से `length` वर्णों के लिए substring लौटाता है। ऋणात्मक `start` अंत से गिनता है:

``` dart
Str.substr('profile', 4);       // 'ile'
Str.substr('profile', 4, 2);    // 'il'
Str.substr('profile', -3);      // 'ile'
```

<div id="method-str-substrcount"></div>

#### `Str.substrCount()`

`haystack` में `needle` की non-overlapping घटनाओं की गणना करता है:

``` dart
Str.substrCount('hello hello hello', 'hello'); // 3
```

<div id="method-str-swap"></div>

#### `Str.swap()`

search → replace जोड़ों के `Map` का उपयोग करके एकाधिक substrings बदलता है:

``` dart
Str.swap({'foo': 'bar', 'hello': 'hi'}, 'hello foo'); // 'hi bar'
```

<div id="method-str-take"></div>

#### `Str.take()`

मान के पहले `limit` वर्ण लौटाता है। ऋणात्मक limit अंत से लौटाता है:

``` dart
Str.take('Build something amazing!', 5); // 'Build'
Str.take('Build something amazing!', -9); // ' amazing!'
```

<div id="method-str-title"></div>

#### `Str.title()`

मान को `Title Case` में परिवर्तित करता है:

``` dart
Str.title('a nice title'); // 'A Nice Title'
```

<div id="method-str-ucfirst"></div>

#### `Str.ucfirst()`

मान के पहले वर्ण को uppercase में करता है:

``` dart
Str.ucfirst('hello world'); // 'Hello world'
```

<div id="method-str-ucsplit"></div>

#### `Str.ucsplit()`

अपरकेस सीमाओं पर मान को शब्दों में विभाजित करता है:

``` dart
Str.ucsplit('fooBarBaz'); // ['foo', 'Bar', 'Baz']
```

<div id="method-str-ulid"></div>

#### `Str.ulid()`

एक ULID (Universally Unique Lexicographically Sortable Identifier) जनरेट करता है:

``` dart
Str.ulid(); // उदा. '01ARZ3NDEKTSV4RRFFQ69G5FAV'
```

<div id="method-str-unwrap"></div>

#### `Str.unwrap()`

शुरू से `before` और अंत से `after` (या `after` null होने पर `before`) का एकल instance हटाता है:

``` dart
Str.unwrap('"value"', '"');             // 'value'
Str.unwrap('<p>html</p>', '<p>', '</p>'); // 'html'
```

<div id="method-str-upper"></div>

#### `Str.upper()`

मान को uppercase में करता है:

``` dart
Str.upper('hello'); // 'HELLO'
```

<div id="method-str-uuid"></div>

#### `Str.uuid()`

एक random UUID v4 जनरेट करता है:

``` dart
Str.uuid(); // उदा. 'a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11'
```

<div id="method-str-uuid7"></div>

#### `Str.uuid7()`

एक UUID v7 जनरेट करता है (time-ordered, RFC 9562)। UUIDv7s निर्माण समय के अनुसार sortable हैं:

``` dart
Str.uuid7(); // उदा. '018e0dc4-8b3f-7a2c-9d01-b1f6c87a92e3'
```

<div id="method-str-wordcount"></div>

#### `Str.wordCount()`

मान की शब्द गणना लौटाता है:

``` dart
Str.wordCount('hello there friend'); // 3
```

<div id="method-str-words"></div>

#### `Str.words()`

मान को दिए गए शब्दों की संख्या तक सीमित करता है:

``` dart
Str.words('Perfectly balanced, as all things should be.', 3);
// 'Perfectly balanced, as...'
```

<div id="method-str-wrap"></div>

#### `Str.wrap()`

मान को `before` और `after` से लपेटता है। `after` छोड़ने पर, `before` दोनों तरफ उपयोग होता है:

``` dart
Str.wrap('value', '"');             // '"value"'
Str.wrap('html', '<p>', '</p>');    // '<p>html</p>'
```

<div id="numbers"></div>

## Numbers

<div id="method-number-abbreviate"></div>

#### `Number.abbreviate()`

संख्या का संक्षिप्त रूप लौटाता है (`1K`, `1M`, `1B`):

``` dart
Number.abbreviate(1500);                  // '2K'
Number.abbreviate(1500, maxPrecision: 1); // '1.5K'
Number.abbreviate(2_500_000);             // '3M'
```

<div id="method-number-average"></div>

#### `Number.average()`

मानों का अंकगणितीय माध्य लौटाता है, या खाली होने पर `0`:

``` dart
Number.average([1, 2, 3, 4]); // 2.5
```

<div id="method-number-between"></div>

#### `Number.between()`

`true` लौटाता है जब मान `min` और `max` के बीच हो (inclusive):

``` dart
Number.between(5, min: 0, max: 10);  // true
Number.between(15, min: 0, max: 10); // false
```

<div id="method-number-ceil"></div>

#### `Number.ceil()`

दिए गए precision तक मान की छत लौटाता है:

``` dart
Number.ceil(1.2);    // 2.0
Number.ceil(1.234, 2); // 1.24
```

<div id="method-number-clamp"></div>

#### `Number.clamp()`

`min` और `max` के बीच एक मान को clamp करता है (inclusive):

``` dart
Number.clamp(5, 0, 10);   // 5
Number.clamp(15, 0, 10);  // 10
Number.clamp(-1, 0, 10);  // 0
```

<div id="method-number-currency"></div>

#### `Number.currency()`

संख्या को मुद्रा मान के रूप में format करता है। कॉन्फ़िगर किए गए locale और currency पर डिफ़ॉल्ट:

``` dart
Number.currency(1234.56);                            // '$1,234.56'
Number.currency(1234.56, currency: 'EUR');           // '€1,234.56'
Number.currency(1234.56, locale: 'de_DE', currency: 'EUR');
// '1.234,56 €'
```

<div id="method-number-defaultcurrency"></div>

#### `Number.defaultCurrency()`

formatting methods द्वारा उपयोग किया जाने वाला डिफ़ॉल्ट currency code लौटाता है:

``` dart
Number.defaultCurrency(); // 'USD'
```

<div id="method-number-defaultlocale"></div>

#### `Number.defaultLocale()`

formatting methods द्वारा उपयोग किया जाने वाला डिफ़ॉल्ट locale लौटाता है:

``` dart
Number.defaultLocale(); // 'en_US'
```

<div id="method-number-degrees"></div>

#### `Number.degrees()`

radians को degrees में परिवर्तित करता है:

``` dart
Number.degrees(3.14159);  // ~180.0
```

<div id="method-number-duration"></div>

#### `Number.duration()`

सेकंड की संख्या को human-readable duration के रूप में format करता है। short form `'1h 2m 5s'` लौटाता है; long form `'1:02:05'` लौटाता है:

``` dart
Number.duration(3661);                  // '1h 1m 1s'
Number.duration(3661, short: false);    // '1:01:01'
Number.duration(45);                    // '45s'
```

<div id="method-number-filesize"></div>

#### `Number.fileSize()`

byte count को human-readable file size के रूप में format करता है:

``` dart
Number.fileSize(1024);                       // '1 KB'
Number.fileSize(1500, maxPrecision: 1);      // '1.5 KB'
Number.fileSize(1024 * 1024 * 1024);         // '1 GB'
```

<div id="method-number-floor"></div>

#### `Number.floor()`

दिए गए precision तक मान का फर्श लौटाता है:

``` dart
Number.floor(1.7);      // 1.0
Number.floor(1.234, 2); // 1.23
```

<div id="method-number-forhumans"></div>

#### `Number.forHumans()`

संख्या का human-readable रूप लौटाता है (`1 thousand`, `1 million`):

``` dart
Number.forHumans(1500);                  // '2 thousand'
Number.forHumans(1500, maxPrecision: 1); // '1.5 thousand'
Number.forHumans(2_500_000);             // '3 million'
```

<div id="method-number-format"></div>

#### `Number.format()`

locale-aware separators के साथ संख्या format करता है:

``` dart
Number.format(1234567);                          // '1,234,567'
Number.format(1234.5, precision: 2);             // '1,234.50'
Number.format(1234567, locale: 'de_DE');         // '1.234.567'
```

<div id="method-number-gcd"></div>

#### `Number.gcd()`

दो पूर्णांकों का महत्तम समापवर्तक:

``` dart
Number.gcd(12, 18); // 6
```

<div id="method-number-lcm"></div>

#### `Number.lcm()`

दो पूर्णांकों का लघुत्तम समापवर्त्य:

``` dart
Number.lcm(4, 6); // 12
```

<div id="method-number-lerp"></div>

#### `Number.lerp()`

`t` (`0..1`) द्वारा `a` और `b` के बीच रैखिक interpolation:

``` dart
Number.lerp(0, 100, 0.25); // 25.0
```

<div id="method-number-max"></div>

#### `Number.max()`

iterable में सबसे बड़ा मान लौटाता है:

``` dart
Number.max([3, 1, 4, 1, 5, 9, 2, 6]); // 9
```

<div id="method-number-median"></div>

#### `Number.median()`

मानों की माध्यिका लौटाता है, या खाली होने पर `0`:

``` dart
Number.median([1, 3, 5]);    // 3.0
Number.median([1, 2, 3, 4]); // 2.5
```

<div id="method-number-min"></div>

#### `Number.min()`

iterable में सबसे छोटा मान लौटाता है:

``` dart
Number.min([3, 1, 4, 1, 5, 9, 2, 6]); // 1
```

<div id="method-number-ordinal"></div>

#### `Number.ordinal()`

संख्या का ordinal रूप लौटाता है (`1st`, `2nd`, `3rd`, `4th`):

``` dart
Number.ordinal(1);   // '1st'
Number.ordinal(2);   // '2nd'
Number.ordinal(21);  // '21st'
Number.ordinal(112); // '112th'
```

<div id="method-number-pairs"></div>

#### `Number.pairs()`

`by` द्वारा step करते हुए `to` तक एक range से pairs (sub-ranges) जनरेट करता है:

``` dart
Number.pairs(25, 10);             // [[0, 9], [10, 19], [20, 25]]
Number.pairs(25, 10, offset: 0);  // [[0, 10], [10, 20], [20, 25]]
```

<div id="method-number-parsefloat"></div>

#### `Number.parseFloat()`

locale-aware string को `double` के रूप में parse करता है। विफलता पर `null` लौटाता है:

``` dart
Number.parseFloat('1,234.56');                    // 1234.56
Number.parseFloat('1.234,56', locale: 'de_DE');   // 1234.56
Number.parseFloat('not a number');                // null
```

<div id="method-number-parseint"></div>

#### `Number.parseInt()`

locale-aware string को `int` के रूप में parse करता है। विफलता पर `null` लौटाता है:

``` dart
Number.parseInt('1,234');                    // 1234
Number.parseInt('1.234', locale: 'de_DE');   // 1234
```

<div id="method-number-percentage"></div>

#### `Number.percentage()`

संख्या को percentage के रूप में format करता है। संख्या को पूरा percentage मान माना जाता है:

``` dart
Number.percentage(10);                  // '10%'
Number.percentage(10.5, precision: 1);  // '10.5%'
```

<div id="method-number-radians"></div>

#### `Number.radians()`

degrees को radians में परिवर्तित करता है:

``` dart
Number.radians(180); // ~3.14159
```

<div id="method-number-random"></div>

#### `Number.random()`

`[min, max]` (inclusive) में एक random integer लौटाता है। निर्धारणवादी sequence के लिए `seed` पास करें:

``` dart
Number.random(min: 1, max: 100);              // 1..100 में कोई भी int
Number.random(min: 0, max: 9, seed: 42);      // निर्धारणवादी
```

<div id="method-number-range"></div>

#### `Number.range()`

`start` (inclusive) से `end` (inclusive) तक, `step` द्वारा stepping करते हुए integers की सूची जनरेट करता है:

``` dart
Number.range(1, 5);              // [1, 2, 3, 4, 5]
Number.range(0, 10, step: 2);    // [0, 2, 4, 6, 8, 10]
Number.range(5, 1, step: -1);    // [5, 4, 3, 2, 1]
```

<div id="method-number-round"></div>

#### `Number.round()`

दिए गए precision तक मान को round करता है:

``` dart
Number.round(1.5);      // 2.0
Number.round(1.234, 2); // 1.23
```

<div id="method-number-scale"></div>

#### `Number.scale()`

एक मान को एक range से दूसरे में re-map करता है:

``` dart
Number.scale(0.5, fromMin: 0, fromMax: 1, toMin: 0, toMax: 100); // 50.0
Number.scale(75, fromMin: 0, fromMax: 100, toMin: -1, toMax: 1); // 0.5
```

<div id="method-number-spell"></div>

#### `Number.spell()`

संख्या को अंग्रेजी में spell out करता है:

``` dart
Number.spell(0);           // 'zero'
Number.spell(123);         // 'one hundred twenty-three'
Number.spell(1_234_567);   // 'one million two hundred thirty-four thousand five hundred sixty-seven'
```

<div id="method-number-spellordinal"></div>

#### `Number.spellOrdinal()`

संख्या का ordinal रूप अंग्रेजी में spell out करता है:

``` dart
Number.spellOrdinal(1);   // 'first'
Number.spellOrdinal(2);   // 'second'
Number.spellOrdinal(21);  // 'twenty-first'
```

<div id="method-number-sum"></div>

#### `Number.sum()`

मानों का योग लौटाता है:

``` dart
Number.sum([1, 2, 3]); // 6
```

<div id="method-number-tobytes"></div>

#### `Number.toBytes()`

file size को bytes में परिवर्तित करता है। [`fileSize`](#method-number-filesize) का inverse। parseable string या `unit` के साथ संख्या पास करें:

``` dart
Number.toBytes('1.5 KB');     // 1536
Number.toBytes(1.5, 'KB');    // 1536
Number.toBytes(1, 'GB');      // 1073741824
Number.toBytes('not bytes');  // null
```

<div id="method-number-trim"></div>

#### `Number.trim()`

मान के decimal भाग से trailing zeros हटाता है:

``` dart
Number.trim(12.0);    // '12'
Number.trim(12.30);   // '12.3'
Number.trim(12.345);  // '12.345'
```

<div id="method-number-usecurrency"></div>

#### `Number.useCurrency()`

formatting methods द्वारा उपयोग किया जाने वाला डिफ़ॉल्ट currency code सेट करता है:

``` dart
Number.useCurrency('EUR');
Number.currency(99); // '€99.00'
```

<div id="method-number-uselocale"></div>

#### `Number.useLocale()`

formatting methods द्वारा उपयोग किया जाने वाला डिफ़ॉल्ट locale सेट करता है:

``` dart
Number.useLocale('de_DE');
Number.format(1234567); // '1.234.567'
```

<div id="objects"></div>

## Objects

`Obj` **dot-notation** paths का उपयोग करके maps के साथ काम करने के लिए utilities प्रदान करता है। List elements को integer index द्वारा संबोधित किया जा सकता है (उदा. `'users.0.name'`)।

<div id="method-obj-add"></div>

#### `Obj.add()`

`key` पर मान केवल तब जोड़ता है जब वहाँ कोई मान वर्तमान में नहीं है:

``` dart
final user = {'name': 'Anna'};
Obj.add(user, 'role', 'admin');
// user: {'name': 'Anna', 'role': 'admin'}

Obj.add(user, 'name', 'Brad'); // no-op — name पहले से सेट है
```

<div id="method-obj-deepequals"></div>

#### `Obj.deepEquals()`

`true` लौटाता है जब दो मान structurally equal हों (`Map`s और `List`s की recursive comparison):

``` dart
Obj.deepEquals({'a': [1, 2]}, {'a': [1, 2]}); // true
Obj.deepEquals({'a': 1}, {'a': 2});            // false
```

<div id="method-obj-divide"></div>

#### `Obj.divide()`

map को `[keys, values]` जोड़े में विभाजित करता है:

``` dart
Obj.divide({'name': 'Desk', 'price': 100});
// [['name', 'price'], ['Desk', 100]]
```

<div id="method-obj-dot"></div>

#### `Obj.dot()`

नेस्टेड map को dot-paths से keyed single-level map में flatten करता है:

``` dart
Obj.dot({'a': {'b': 1, 'c': 2}});
// {'a.b': 1, 'a.c': 2}
```

<div id="method-obj-except"></div>

#### `Obj.except()`

दिए गए keys वाली प्रविष्टियों को छोड़कर सभी प्रविष्टियों वाला नया map लौटाता है:

``` dart
Obj.except({'name': 'Anna', 'role': 'admin', 'age': 30}, ['age']);
// {'name': 'Anna', 'role': 'admin'}
```

<div id="method-obj-exists"></div>

#### `Obj.exists()`

`true` लौटाता है जब map में top level पर दी गई key हो (dot-notation traversal नहीं):

``` dart
Obj.exists({'a.b': 1}, 'a.b'); // true
Obj.exists({'a': {'b': 1}}, 'a.b'); // false (traversal के लिए `has` उपयोग करें)
```

<div id="method-obj-flip"></div>

#### `Obj.flip()`

keys और values बदलता है। values collide होने पर अंतिम प्रविष्टि जीतती है:

``` dart
Obj.flip({'one': 1, 'two': 2}); // {1: 'one', 2: 'two'}
```

<div id="method-obj-forget"></div>

#### `Obj.forget()`

map से एक key (dot notation) हटाता है। chaining के लिए map लौटाता है:

``` dart
final user = {'profile': {'name': 'Anna', 'email': 'a@b'}};
Obj.forget(user, 'profile.email');
// user: {'profile': {'name': 'Anna'}}
```

<div id="method-obj-get"></div>

#### `Obj.get()`

dot notation का उपयोग करके `key` पर मान लौटाता है, या path missing होने पर default मान। literal dots वाली top-level keys traversal पर प्राथमिकता लेती हैं:

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

`key` पर मान `bool` में coerce करके लौटाता है, या default मान। Non-zero होने पर Numbers truthy हैं; strings `'true'`/`'false'`/`'1'`/`'0'`/`'yes'`/`'no'`/`'on'`/`'off'` पहचानते हैं (case-insensitive):

``` dart
Obj.getBool({'flag': 'yes'}, 'flag');     // true
Obj.getBool({'flag': 0}, 'flag');         // false
Obj.getBool({}, 'flag', false);           // false
```

<div id="method-obj-getdouble"></div>

#### `Obj.getDouble()`

`key` पर मान `double` में coerce करके लौटाता है, या default मान:

``` dart
Obj.getDouble({'price': '9.99'}, 'price'); // 9.99
Obj.getDouble({'price': 10}, 'price');     // 10.0
```

<div id="method-obj-getint"></div>

#### `Obj.getInt()`

`key` पर मान `int` में coerce करके लौटाता है, या default मान:

``` dart
Obj.getInt({'count': '42'}, 'count');     // 42
Obj.getInt({'count': 3.7}, 'count');      // 3 (truncated)
Obj.getInt({}, 'count', 0);               // 0
```

<div id="method-obj-getlist"></div>

#### `Obj.getList()`

`key` पर मान लौटाता है जब यह `List` हो, अन्यथा default मान:

``` dart
Obj.getList({'items': [1, 2, 3]}, 'items'); // [1, 2, 3]
Obj.getList({'items': 'oops'}, 'items', []); // []
```

<div id="method-obj-getmap"></div>

#### `Obj.getMap()`

`key` पर मान लौटाता है जब यह `Map` हो, अन्यथा default मान:

``` dart
Obj.getMap({'meta': {'a': 1}}, 'meta'); // {'a': 1}
Obj.getMap({'meta': null}, 'meta', {}); // {}
```

<div id="method-obj-getstring"></div>

#### `Obj.getString()`

`key` पर मान `String` में coerce करके लौटाता है, या default मान:

``` dart
Obj.getString({'name': 'Anna'}, 'name'); // 'Anna'
Obj.getString({'count': 42}, 'count');   // '42'
Obj.getString({}, 'name', 'Guest');      // 'Guest'
```

<div id="method-obj-has"></div>

#### `Obj.has()`

`true` लौटाता है जब map में dot notation का उपयोग करके `key` पर मान हो:

``` dart
final user = {'profile': {'name': 'Anna'}};
Obj.has(user, 'profile.name');  // true
Obj.has(user, 'profile.email'); // false
```

<div id="method-obj-hasall"></div>

#### `Obj.hasAll()`

`true` लौटाता है जब दी गई सभी keys map में resolve हों:

``` dart
Obj.hasAll({'a': 1, 'b': 2}, ['a', 'b']);      // true
Obj.hasAll({'a': 1, 'b': 2}, ['a', 'c']);      // false
```

<div id="method-obj-hasany"></div>

#### `Obj.hasAny()`

`true` लौटाता है जब दी गई किसी भी key map में resolve हो:

``` dart
Obj.hasAny({'a': 1}, ['a', 'b']); // true
Obj.hasAny({'a': 1}, ['x', 'y']); // false
```

<div id="method-obj-mapkeys"></div>

#### `Obj.mapKeys()`

`fn` द्वारा transformed प्रत्येक key के साथ नया map लौटाता है:

``` dart
Obj.mapKeys({'firstName': 'Anna'}, (k) => k.toLowerCase());
// {'firstname': 'Anna'}
```

<div id="method-obj-mapvalues"></div>

#### `Obj.mapValues()`

`fn` द्वारा transformed प्रत्येक value के साथ नया map लौटाता है:

``` dart
Obj.mapValues({'a': 1, 'b': 2}, (v) => v * 10);
// {'a': 10, 'b': 20}
```

<div id="method-obj-merge"></div>

#### `Obj.merge()`

`source` को `target` में recursively merge करता है। key collision पर, `source` जीतता है; nested maps merge होते हैं:

``` dart
Obj.merge(
  {'a': {'x': 1}, 'b': 2},
  {'a': {'y': 9}, 'b': 22},
);
// {'a': {'x': 1, 'y': 9}, 'b': 22}
```

<div id="method-obj-only"></div>

#### `Obj.only()`

केवल दिए गए keys वाली प्रविष्टियों वाला नया map लौटाता है:

``` dart
Obj.only({'name': 'Anna', 'role': 'admin', 'age': 30}, ['name', 'role']);
// {'name': 'Anna', 'role': 'admin'}
```

<div id="method-obj-prependkeyswith"></div>

#### `Obj.prependKeysWith()`

map में हर top-level key को दिए गए prefix से prefix करता है:

``` dart
Obj.prependKeysWith({'name': 'Anna', 'age': 30}, 'user_');
// {'user_name': 'Anna', 'user_age': 30}
```

<div id="method-obj-pull"></div>

#### `Obj.pull()`

`key` पर मान लौटाता है और map से हटाता है:

``` dart
final user = {'name': 'Anna', 'temp': 'token'};
final temp = Obj.pull(user, 'temp'); // 'token'
// user: {'name': 'Anna'}
```

<div id="method-obj-query"></div>

#### `Obj.query()`

map को URL query string के रूप में encode करता है:

``` dart
Obj.query({'name': 'Anna', 'tags': ['a', 'b']});
// 'name=Anna&tags%5B0%5D=a&tags%5B1%5D=b'

Obj.query({'filter': {'role': 'admin'}});
// 'filter%5Brole%5D=admin'
```

<div id="method-obj-set"></div>

#### `Obj.set()`

`key` पर मान सेट करता है, path के साथ nested maps बनाता है। map को in place mutate करता है और chaining के लिए लौटाता है:

``` dart
final user = <String, dynamic>{};
Obj.set(user, 'profile.email', 'a@b');
// user: {'profile': {'email': 'a@b'}}
```

<div id="method-obj-undot"></div>

#### `Obj.undot()`

dot-keyed map को वापस nested structure में inflate करता है ([`dot`](#method-obj-dot) का inverse):

``` dart
Obj.undot({'a.b': 1, 'a.c': 2});
// {'a': {'b': 1, 'c': 2}}
```

<div id="method-obj-wherenotempty"></div>

#### `Obj.whereNotEmpty()`

`null` या खाली (खाली `String`, `Iterable`, या `Map`) values वाली प्रविष्टियाँ हटाकर नया map लौटाता है:

``` dart
Obj.whereNotEmpty({'name': 'Anna', 'tags': [], 'bio': ''});
// {'name': 'Anna'}
```

<div id="method-obj-wherenotnull"></div>

#### `Obj.whereNotNull()`

`null` values वाली प्रविष्टियाँ हटाकर नया map लौटाता है:

``` dart
Obj.whereNotNull({'name': 'Anna', 'email': null});
// {'name': 'Anna'}
```

<div id="miscellaneous"></div>

## विविध

ये global functions `helper.dart` में रहते हैं और जहाँ भी आप `nylo_framework` import करते हैं वहाँ उपलब्ध हैं।

<div id="method-api"></div>

#### `api()`

`NyApiService` के माध्यम से API requests भेजने के लिए Convenience helper। पूर्ण options के लिए [Networking](/docs/{{ $version }}/networking) पेज देखें:

``` dart
await api<ApiService>((request) =>
  request.get('https://jsonplaceholder.typicode.com/posts'));
```

<div id="method-datatomodel"></div>

#### `dataToModel()`

raw data (आमतौर पर एक JSON `Map`) को `config/decoders.dart` में registered model में परिवर्तित करता है। type parameter आवश्यक है:

``` dart
final user = dataToModel<User>(data: {'name': 'Anna', 'age': 30});
```

<div id="method-dump"></div>

#### `dump()`

console पर एक मान dump करता है। `alwaysPrint: true` न होने पर app के `APP_DEBUG` flag का सम्मान करता है:

``` dart
dump('Hello World');
dump(user, tag: 'current user');
dump(payload, alwaysPrint: true);
```

<div id="method-event"></div>

#### `event()`

`Nylo.events()` का उपयोग करके दिए गए type का event fire करता है। पूर्ण usage के लिए [Events](/docs/{{ $version }}/events) देखें:

``` dart
await event<LoginEvent>(data: {'user': 'Anna'});
```

<div id="method-getasset"></div>

#### `getAsset()`

`/assets` directory में एक asset के लिए पूरा path लौटाता है:

``` dart
final path = getAsset('videos/welcome.mp4');
// 'assets/videos/welcome.mp4'
```

<div id="method-getenv"></div>

#### `getEnv()`

आपके generated env (`env.g.dart`) से एक मान लौटाता है। key सेट न होने पर default लौटाता है:

``` dart
final apiUrl = getEnv('API_URL');
final debug = getEnv('APP_DEBUG', defaultValue: false);
```

<div id="method-getimageasset"></div>

#### `getImageAsset()`

`/assets/images/` में एक image के लिए पूरा path लौटाता है:

``` dart
Image.asset(getImageAsset('logo.png'));
```

<div id="method-loadjson"></div>

#### `loadJson()`

आपके assets bundle से JSON file load करता है। Results डिफ़ॉल्ट रूप से cached हैं:

``` dart
final config = await loadJson<Map<String, dynamic>>('config/app.json');
```

<div id="method-match"></div>

#### `match()`

cases के `Map` के विरुद्ध एक मान match करता है और corresponding मान लौटाता है। कोई case match न होने और `defaultValue` न होने पर throws:

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

> **नोट:** यह एक `Map`-आधारित मान lookup है — [`Str.match`](#method-str-match) से अलग, जो regex matching करता है।

<div id="method-now"></div>

#### `now()`

वर्तमान `DateTime` लौटाता है:

``` dart
final timestamp = now();
```

<div id="method-nyhexcolor"></div>

#### `nyHexColor()`

hex color string को `Color` में परिवर्तित करता है। 6- या 8-digit values स्वीकार करता है, leading `#` के साथ या बिना:

``` dart
final brand = nyHexColor('#FF6B35');
final translucent = nyHexColor('80FF6B35'); // 8-digit (alpha first)
```

<div id="method-print-helpers"></div>

#### `printDebug()`, `printError()`, `printInfo()`, `printWarning()`, `printSuccess()`, `printVerbose()`, `printAlert()`, `printEmergency()`

Severity-tagged console loggers। output formatting और `alwaysPrint` flag के लिए [Logging](/docs/{{ $version }}/logging) पेज देखें:

``` dart
printInfo('User signed in');
printError('Payment failed', context: {'order_id': 42});
printDebug({'state': state});
```

<div id="method-badge-helpers"></div>

#### `setBadgeNumber()`, `clearBadgeNumber()`

app icon badge count सेट या clear करता है। platform setup details के लिए [Local Notifications](/docs/{{ $version }}/local-notifications) देखें:

``` dart
await setBadgeNumber(3);
await clearBadgeNumber();
```

<div id="method-shownextlog"></div>

#### `showNextLog()`

अगले `NyLogger` log को `APP_DEBUG` के `false` होने पर भी प्रदर्शित करने के लिए बाध्य करता है। release builds में one-off diagnostics के लिए उपयोगी:

``` dart
showNextLog();
NyLogger.info('This will print regardless of APP_DEBUG');
```

<div id="method-sleep"></div>

#### `sleep()`

दिए गए duration के लिए execution में देरी करता है। पहला argument seconds है; optional दूसरा argument microseconds जोड़ता है:

``` dart
await sleep(2);              // 2 सेकंड
await sleep(0, 500_000);     // 500 मिलीसेकंड
```

<div id="method-trans"></div>

#### `trans()`

key द्वारा translated string लौटाता है। setup के लिए [Localization](/docs/{{ $version }}/localization) देखें:

``` dart
final greeting = trans('home.welcome');
final personalized = trans('home.hello', arguments: {'name': 'Anna'});
```
