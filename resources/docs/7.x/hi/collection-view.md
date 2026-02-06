# CollectionView

---

<a name="section-1"></a>
- [परिचय](#introduction "परिचय")
- [बेसिक उपयोग](#basic-usage "बेसिक उपयोग")
- [CollectionItem हेल्पर](#collection-item "CollectionItem हेल्पर")
- वेरिएंट्स
    - [CollectionView](#collection-view-basic "CollectionView")
    - [CollectionView.separated](#collection-view-separated "CollectionView.separated")
    - [CollectionView.grid](#collection-view-grid "CollectionView.grid")
- पुल-टू-रिफ्रेश वेरिएंट्स
    - [CollectionView.pullable](#collection-view-pullable "CollectionView.pullable")
    - [CollectionView.pullableSeparated](#collection-view-pullable-separated "CollectionView.pullableSeparated")
    - [CollectionView.pullableGrid](#collection-view-pullable-grid "CollectionView.pullableGrid")
- [लोडिंग स्टाइल्स](#loading-styles "लोडिंग स्टाइल्स")
- [खाली स्थिति](#empty-state "खाली स्थिति")
- [डेटा सॉर्टिंग और ट्रांसफॉर्मिंग](#sorting-transforming "डेटा सॉर्टिंग और ट्रांसफॉर्मिंग")
- [स्टेट अपडेट करना](#updating-state "स्टेट अपडेट करना")
- [पैरामीटर्स रेफरेंस](#parameters "पैरामीटर्स रेफरेंस")


<div id="introduction"></div>

## परिचय

**CollectionView** विजेट आपके {{ config('app.name') }} प्रोजेक्ट्स में डेटा की लिस्ट दिखाने के लिए एक शक्तिशाली, टाइप-सेफ रैपर है। यह `ListView`, `ListView.separated`, और ग्रिड लेआउट के साथ काम करना सरल बनाता है और निम्नलिखित के लिए बिल्ट-इन सपोर्ट प्रदान करता है:

- ऑटोमैटिक लोडिंग स्टेट्स के साथ एसिंक डेटा लोडिंग
- पुल-टू-रिफ्रेश और पेजिनेशन
- पोजिशन हेल्पर्स के साथ टाइप-सेफ आइटम बिल्डर्स
- खाली स्थिति हैंडलिंग
- डेटा सॉर्टिंग और ट्रांसफॉर्मेशन

<div id="basic-usage"></div>

## बेसिक उपयोग

यहाँ आइटम्स की लिस्ट दिखाने का एक सरल उदाहरण है:

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: CollectionView<String>(
      data: () => ['Item 1', 'Item 2', 'Item 3'],
      builder: (context, item) {
        return ListTile(
          title: Text(item.data),
        );
      },
    ),
  );
}
```

API से एसिंक डेटा के साथ:

``` dart
CollectionView<Todo>(
  data: () async {
    return await api<ApiService>((request) =>
      request.get('https://jsonplaceholder.typicode.com/todos')
    );
  },
  builder: (context, item) {
    return ListTile(
      title: Text(item.data.title),
      subtitle: Text(item.data.completed ? 'Done' : 'Pending'),
    );
  },
)
```

<div id="collection-item"></div>

## CollectionItem हेल्पर

`builder` कॉलबैक एक `CollectionItem<T>` ऑब्जेक्ट प्राप्त करता है जो आपके डेटा को उपयोगी पोजिशन हेल्पर्स के साथ रैप करता है:

``` dart
CollectionView<String>(
  data: () => ['First', 'Second', 'Third', 'Fourth'],
  builder: (context, item) {
    return Container(
      color: item.isEven ? Colors.grey[100] : Colors.white,
      child: ListTile(
        title: Text('${item.data} (index: ${item.index})'),
        subtitle: Text('Progress: ${(item.progress * 100).toInt()}%'),
      ),
    );
  },
)
```

### CollectionItem प्रॉपर्टीज़

| प्रॉपर्टी | टाइप | विवरण |
|----------|------|-------------|
| `data` | `T` | वास्तविक आइटम डेटा |
| `index` | `int` | लिस्ट में वर्तमान इंडेक्स |
| `totalItems` | `int` | कुल आइटम्स की संख्या |
| `isFirst` | `bool` | यदि यह पहला आइटम है तो True |
| `isLast` | `bool` | यदि यह अंतिम आइटम है तो True |
| `isOdd` | `bool` | यदि इंडेक्स विषम है तो True |
| `isEven` | `bool` | यदि इंडेक्स सम है तो True |
| `progress` | `double` | लिस्ट में प्रगति (0.0 से 1.0) |

### CollectionItem मेथड्स

| मेथड | विवरण |
|--------|-------------|
| `isAt(int position)` | जाँचें कि आइटम विशिष्ट पोजिशन पर है या नहीं |
| `isInRange(int start, int end)` | जाँचें कि इंडेक्स रेंज के भीतर है या नहीं (इनक्लूसिव) |
| `isMultipleOf(int divisor)` | जाँचें कि इंडेक्स डिवाइज़र का गुणज है या नहीं |

<div id="collection-view-basic"></div>

## CollectionView

डिफ़ॉल्ट कंस्ट्रक्टर एक स्टैंडर्ड लिस्ट व्यू बनाता है:

``` dart
CollectionView<Map<String, dynamic>>(
  data: () async {
    return [
      {"title": "Clean Room"},
      {"title": "Go shopping"},
      {"title": "Buy groceries"},
    ];
  },
  builder: (context, item) {
    return ListTile(title: Text(item.data['title']));
  },
  spacing: 8.0, // Add spacing between items
  padding: EdgeInsets.all(16),
)
```

<div id="collection-view-separated"></div>

## CollectionView.separated

आइटम्स के बीच सेपरेटर्स के साथ एक लिस्ट बनाता है:

``` dart
CollectionView<User>.separated(
  data: () async => await fetchUsers(),
  builder: (context, item) {
    return ListTile(
      title: Text(item.data.name),
      subtitle: Text(item.data.email),
    );
  },
  separatorBuilder: (context, index) {
    return Divider(height: 1);
  },
)
```

<div id="collection-view-grid"></div>

## CollectionView.grid

स्टैगर्ड ग्रिड का उपयोग करके ग्रिड लेआउट बनाता है:

``` dart
CollectionView<Product>.grid(
  data: () async => await fetchProducts(),
  builder: (context, item) {
    return ProductCard(product: item.data);
  },
  crossAxisCount: 2,
  mainAxisSpacing: 8.0,
  crossAxisSpacing: 8.0,
  padding: EdgeInsets.all(16),
)
```

<div id="collection-view-pullable"></div>

## CollectionView.pullable

पुल-टू-रिफ्रेश और इनफिनिट स्क्रॉल पेजिनेशन के साथ एक लिस्ट बनाता है:

``` dart
CollectionView<Post>.pullable(
  data: (int iteration) async {
    // iteration 1 से शुरू होता है और हर लोड पर बढ़ता है
    return await api<ApiService>((request) =>
      request.get('/posts?page=$iteration')
    );
  },
  builder: (context, item) {
    return PostCard(post: item.data);
  },
  onRefresh: () {
    print('List was refreshed!');
  },
  headerStyle: 'WaterDropHeader', // पुल इंडिकेटर स्टाइल
)
```

### हेडर स्टाइल्स

`headerStyle` पैरामीटर स्वीकार करता है:
- `'WaterDropHeader'` (डिफ़ॉल्ट) - वॉटर ड्रॉप एनिमेशन
- `'ClassicHeader'` - क्लासिक पुल इंडिकेटर
- `'MaterialClassicHeader'` - मटीरियल डिज़ाइन स्टाइल
- `'WaterDropMaterialHeader'` - मटीरियल वॉटर ड्रॉप
- `'BezierHeader'` - बेज़ियर कर्व एनिमेशन

### पेजिनेशन कॉलबैक्स

| कॉलबैक | विवरण |
|----------|-------------|
| `beforeRefresh` | रिफ्रेश शुरू होने से पहले कॉल होता है |
| `onRefresh` | रिफ्रेश पूरा होने पर कॉल होता है |
| `afterRefresh` | डेटा लोड होने के बाद कॉल होता है, ट्रांसफॉर्मेशन के लिए डेटा प्राप्त करता है |

<div id="collection-view-pullable-separated"></div>

## CollectionView.pullableSeparated

पुल-टू-रिफ्रेश को सेपरेटेड लिस्ट के साथ जोड़ता है:

``` dart
CollectionView<Message>.pullableSeparated(
  data: (iteration) async => await fetchMessages(page: iteration),
  builder: (context, item) {
    return MessageTile(message: item.data);
  },
  separatorBuilder: (context, index) => Divider(),
)
```

<div id="collection-view-pullable-grid"></div>

## CollectionView.pullableGrid

पुल-टू-रिफ्रेश को ग्रिड लेआउट के साथ जोड़ता है:

``` dart
CollectionView<Photo>.pullableGrid(
  data: (iteration) async => await fetchPhotos(page: iteration),
  builder: (context, item) {
    return Image.network(item.data.url);
  },
  crossAxisCount: 3,
  mainAxisSpacing: 4,
  crossAxisSpacing: 4,
)
```

<div id="loading-styles"></div>

## लोडिंग स्टाइल्स

`loadingStyle` का उपयोग करके लोडिंग इंडिकेटर को कस्टमाइज़ करें:

``` dart
// कस्टम विजेट के साथ सामान्य लोडिंग
CollectionView<Item>(
  data: () async => await fetchItems(),
  builder: (context, item) => ItemTile(item: item.data),
  loadingStyle: LoadingStyle.normal(
    child: Text("Loading items..."),
  ),
)

// स्केलेटनाइज़र लोडिंग इफेक्ट
CollectionView<User>(
  data: () async => await fetchUsers(),
  builder: (context, item) => UserCard(user: item.data),
  loadingStyle: LoadingStyle.skeletonizer(
    child: UserCard(user: User.placeholder()),
    effect: SkeletonizerEffect.shimmer,
  ),
)
```

<div id="empty-state"></div>

## खाली स्थिति

लिस्ट खाली होने पर एक कस्टम विजेट दिखाएँ:

``` dart
CollectionView<Item>(
  data: () async => await fetchItems(),
  builder: (context, item) => ItemTile(item: item.data),
  empty: Center(
    child: Column(
      mainAxisAlignment: MainAxisAlignment.center,
      children: [
        Icon(Icons.inbox, size: 64, color: Colors.grey),
        SizedBox(height: 16),
        Text('No items found'),
      ],
    ),
  ),
)
```

<div id="sorting-transforming"></div>

## डेटा सॉर्टिंग और ट्रांसफॉर्मिंग

### सॉर्टिंग

दिखाने से पहले आइटम्स को सॉर्ट करें:

``` dart
CollectionView<Task>(
  data: () async => await fetchTasks(),
  builder: (context, item) => TaskTile(task: item.data),
  sort: (List<Task> items) {
    items.sort((a, b) => a.dueDate.compareTo(b.dueDate));
    return items;
  },
)
```

### ट्रांसफॉर्म

लोड होने के बाद डेटा को ट्रांसफॉर्म करें:

``` dart
CollectionView<User>(
  data: () async => await fetchUsers(),
  builder: (context, item) => UserTile(user: item.data),
  transform: (List<User> users) {
    // केवल सक्रिय यूज़र्स को फ़िल्टर करें
    return users.where((u) => u.isActive).toList();
  },
)
```

<div id="updating-state"></div>

## स्टेट अपडेट करना

आप `stateName` देकर CollectionView को अपडेट या रीसेट कर सकते हैं:

``` dart
CollectionView<Todo>(
  stateName: "my_todo_list",
  data: () async => await fetchTodos(),
  builder: (context, item) => TodoTile(todo: item.data),
)
```

### लिस्ट रीसेट करें

``` dart
// डेटा को शुरू से रीसेट और रीलोड करता है
CollectionView.stateReset("my_todo_list");
```

### एक आइटम हटाएँ

``` dart
// इंडेक्स 2 पर आइटम हटाएँ
CollectionView.removeFromIndex("my_todo_list", 2);
```

### सामान्य अपडेट ट्रिगर करें

``` dart
// ग्लोबल updateState हेल्पर का उपयोग करके
updateState("my_todo_list");
```

<div id="parameters"></div>

## पैरामीटर्स रेफरेंस

### सामान्य पैरामीटर्स

| पैरामीटर | टाइप | विवरण |
|-----------|------|-------------|
| `data` | `Function()` | `List<T>` या `Future<List<T>>` लौटाने वाला फ़ंक्शन |
| `builder` | `CollectionItemBuilder<T>` | प्रत्येक आइटम के लिए बिल्डर फ़ंक्शन |
| `empty` | `Widget?` | लिस्ट खाली होने पर दिखाया जाने वाला विजेट |
| `loadingStyle` | `LoadingStyle?` | लोडिंग इंडिकेटर कस्टमाइज़ करें |
| `header` | `Widget?` | लिस्ट के ऊपर हेडर विजेट |
| `stateName` | `String?` | स्टेट मैनेजमेंट के लिए नाम |
| `sort` | `Function(List<T>)?` | आइटम्स के लिए सॉर्ट फ़ंक्शन |
| `transform` | `Function(List<T>)?` | डेटा के लिए ट्रांसफॉर्म फ़ंक्शन |
| `spacing` | `double?` | आइटम्स के बीच स्पेसिंग |

### पुलेबल-विशिष्ट पैरामीटर्स

| पैरामीटर | टाइप | विवरण |
|-----------|------|-------------|
| `data` | `Function(int iteration)` | पेजिनेटेड डेटा फ़ंक्शन |
| `onRefresh` | `Function()?` | रिफ्रेश पूरा होने पर कॉलबैक |
| `beforeRefresh` | `Function()?` | रिफ्रेश से पहले कॉलबैक |
| `afterRefresh` | `Function(dynamic)?` | डेटा लोड होने के बाद कॉलबैक |
| `headerStyle` | `String?` | पुल इंडिकेटर स्टाइल |
| `footerLoadingIcon` | `Widget?` | पेजिनेशन के लिए कस्टम लोडिंग इंडिकेटर |

### ग्रिड-विशिष्ट पैरामीटर्स

| पैरामीटर | टाइप | विवरण |
|-----------|------|-------------|
| `crossAxisCount` | `int` | कॉलम्स की संख्या (डिफ़ॉल्ट: 2) |
| `mainAxisSpacing` | `double` | आइटम्स के बीच वर्टिकल स्पेसिंग |
| `crossAxisSpacing` | `double` | आइटम्स के बीच हॉरिज़ॉन्टल स्पेसिंग |

### ListView पैरामीटर्स

सभी स्टैंडर्ड `ListView` पैरामीटर्स भी सपोर्टेड हैं: `scrollDirection`, `reverse`, `controller`, `physics`, `shrinkWrap`, `padding`, `cacheExtent`, और अन्य।
