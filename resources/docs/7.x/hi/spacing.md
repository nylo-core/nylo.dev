# Spacing

---

<a name="section-1"></a>
- [परिचय](#introduction "परिचय")
- [प्रीसेट साइज़](#preset-sizes "प्रीसेट साइज़")
- [कस्टम स्पेसिंग](#custom-spacing "कस्टम स्पेसिंग")
- [स्लिवर्स के साथ उपयोग](#slivers "स्लिवर्स के साथ उपयोग")


<div id="introduction"></div>

## परिचय

**Spacing** विजेट UI एलिमेंट्स के बीच सुसंगत स्पेसिंग जोड़ने का एक साफ, पठनीय तरीका प्रदान करता है। अपने कोड में मैन्युअली `SizedBox` इंस्टेंस बनाने के बजाय, आप सिमैंटिक प्रीसेट्स या कस्टम वैल्यू के साथ `Spacing` का उपयोग कर सकते हैं।

``` dart
// Instead of this:
SizedBox(height: 16),

// Write this:
Spacing.md,
```

<div id="preset-sizes"></div>

## प्रीसेट साइज़

`Spacing` सामान्य स्पेसिंग वैल्यू के लिए बिल्ट-इन प्रीसेट्स के साथ आता है। ये आपके ऐप में सुसंगत स्पेसिंग बनाए रखने में मदद करते हैं।

### वर्टिकल स्पेसिंग प्रीसेट्स

इन्हें `Column` विजेट्स में या जहाँ भी आपको वर्टिकल स्पेस चाहिए वहाँ उपयोग करें:

| प्रीसेट | साइज़ | उपयोग |
|--------|------|-------|
| `Spacing.zero` | 0px | सशर्त स्पेसिंग |
| `Spacing.xs` | 4px | अतिरिक्त छोटा |
| `Spacing.sm` | 8px | छोटा |
| `Spacing.md` | 16px | मध्यम |
| `Spacing.lg` | 24px | बड़ा |
| `Spacing.xl` | 32px | अतिरिक्त बड़ा |

``` dart
Column(
  children: [
    Text("Title"),
    Spacing.sm,
    Text("Subtitle"),
    Spacing.lg,
    Text("Body content"),
    Spacing.xl,
    ElevatedButton(
      onPressed: () {},
      child: Text("Action"),
    ),
  ],
)
```

### हॉरिज़ॉन्टल स्पेसिंग प्रीसेट्स

इन्हें `Row` विजेट्स में या जहाँ भी आपको हॉरिज़ॉन्टल स्पेस चाहिए वहाँ उपयोग करें:

| प्रीसेट | साइज़ | उपयोग |
|--------|------|-------|
| `Spacing.xsHorizontal` | 4px | अतिरिक्त छोटा |
| `Spacing.smHorizontal` | 8px | छोटा |
| `Spacing.mdHorizontal` | 16px | मध्यम |
| `Spacing.lgHorizontal` | 24px | बड़ा |
| `Spacing.xlHorizontal` | 32px | अतिरिक्त बड़ा |

``` dart
Row(
  children: [
    Icon(Icons.star),
    Spacing.smHorizontal,
    Text("Rating"),
    Spacing.lgHorizontal,
    Text("5.0"),
  ],
)
```

<div id="custom-spacing"></div>

## कस्टम स्पेसिंग

जब प्रीसेट आपकी आवश्यकताओं को पूरा नहीं करते, तो कस्टम स्पेसिंग बनाएँ:

### वर्टिकल स्पेसिंग

``` dart
Spacing.vertical(12) // 12 logical pixels of vertical space
```

### हॉरिज़ॉन्टल स्पेसिंग

``` dart
Spacing.horizontal(20) // 20 logical pixels of horizontal space
```

### दोनों डाइमेंशन

``` dart
Spacing(width: 10, height: 20) // Custom width and height
```

### उदाहरण

``` dart
Column(
  children: [
    Text("Header"),
    Spacing.vertical(40), // Custom 40px gap
    Card(
      child: Padding(
        padding: EdgeInsets.all(16),
        child: Row(
          children: [
            Icon(Icons.info),
            Spacing.horizontal(12), // Custom 12px gap
            Expanded(child: Text("Information text")),
          ],
        ),
      ),
    ),
  ],
)
```

<div id="slivers"></div>

## स्लिवर्स के साथ उपयोग

`CustomScrollView` और स्लिवर्स के साथ काम करते समय, `asSliver()` मेथड का उपयोग करें:

``` dart
CustomScrollView(
  slivers: [
    SliverAppBar(
      title: Text("My App"),
    ),
    Spacing.lg.asSliver(), // Converts to SliverToBoxAdapter
    SliverList(
      delegate: SliverChildBuilderDelegate(
        (context, index) => ListTile(title: Text("Item $index")),
        childCount: 10,
      ),
    ),
    Spacing.xl.asSliver(),
    SliverToBoxAdapter(
      child: Text("Footer"),
    ),
  ],
)
```

`asSliver()` मेथड `Spacing` विजेट को `SliverToBoxAdapter` में रैप करता है, जिससे यह स्लिवर-आधारित लेआउट के साथ संगत हो जाता है।

## सशर्त स्पेसिंग

सशर्त स्पेसिंग के लिए `Spacing.zero` का उपयोग करें:

``` dart
Column(
  children: [
    Text("Always visible"),
    showExtraSpace ? Spacing.lg : Spacing.zero,
    Text("Content below"),
  ],
)
```
