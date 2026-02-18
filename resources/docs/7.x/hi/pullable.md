# Pullable

---

<a name="section-1"></a>
- [परिचय](#introduction "परिचय")
- [बुनियादी उपयोग](#basic-usage "बुनियादी उपयोग")
- [कंस्ट्रक्टर](#constructors "कंस्ट्रक्टर")
- [PullableConfig](#pullable-config "PullableConfig")
- [हेडर स्टाइल](#header-styles "हेडर स्टाइल")
- [और लोड करने के लिए ऊपर खींचें](#pull-up "और लोड करने के लिए ऊपर खींचें")
- [कस्टम हेडर और फ़ुटर](#custom-headers "कस्टम हेडर और फ़ुटर")
- [कंट्रोलर](#controller "कंट्रोलर")
- [एक्सटेंशन मेथड](#extension-method "एक्सटेंशन मेथड")
- [CollectionView एकीकरण](#collection-view "CollectionView एकीकरण")
- [उदाहरण](#examples "उदाहरण")

<div id="introduction"></div>

## परिचय

**Pullable** विजेट किसी भी स्क्रॉल करने योग्य कंटेंट में पुल-टू-रिफ्रेश और लोड-मोर कार्यक्षमता जोड़ता है। यह आपके चाइल्ड विजेट को जेस्चर-ड्रिवन रिफ्रेश और पेजिनेशन व्यवहार से लपेटता है, कई हेडर एनिमेशन स्टाइल का समर्थन करता है।

`pull_to_refresh_flutter3` पैकेज पर निर्मित, Pullable सामान्य कॉन्फ़िगरेशन के लिए नामित कंस्ट्रक्टर के साथ एक साफ API प्रदान करता है।

``` dart
Pullable(
  onRefresh: () async {
    // Fetch fresh data
    await fetchData();
  },
  child: ListView(
    children: items.map((item) => ListTile(title: Text(item))).toList(),
  ),
)
```

<div id="basic-usage"></div>

## बुनियादी उपयोग

किसी भी स्क्रॉल करने योग्य विजेट को `Pullable` से लपेटें:

``` dart
Pullable(
  onRefresh: () async {
    await loadLatestPosts();
  },
  child: ListView.builder(
    itemCount: posts.length,
    itemBuilder: (context, index) => PostCard(post: posts[index]),
  ),
)
```

जब उपयोगकर्ता सूची को नीचे खींचता है, तो `onRefresh` कॉलबैक ट्रिगर होता है। कॉलबैक पूरा होने पर रिफ्रेश इंडिकेटर स्वचालित रूप से समाप्त हो जाता है।

<div id="constructors"></div>

## कंस्ट्रक्टर

`Pullable` सामान्य कॉन्फ़िगरेशन के लिए नामित कंस्ट्रक्टर प्रदान करता है:

| कंस्ट्रक्टर | हेडर स्टाइल | विवरण |
|-------------|-------------|-------------|
| `Pullable()` | Water Drop | डिफ़ॉल्ट कंस्ट्रक्टर |
| `Pullable.classicHeader()` | Classic | क्लासिक पुल-टू-रिफ्रेश स्टाइल |
| `Pullable.waterDropHeader()` | Water Drop | वॉटर ड्रॉप एनिमेशन |
| `Pullable.materialClassicHeader()` | Material Classic | मटीरियल डिज़ाइन क्लासिक स्टाइल |
| `Pullable.waterDropMaterialHeader()` | Water Drop Material | मटीरियल वॉटर ड्रॉप स्टाइल |
| `Pullable.bezierHeader()` | Bezier | बेज़ियर कर्व एनिमेशन |
| `Pullable.noBounce()` | कॉन्फ़िगर करने योग्य | `ClampingScrollPhysics` के साथ कम बाउंस |
| `Pullable.custom()` | कस्टम विजेट | अपने स्वयं के हेडर/फ़ुटर विजेट उपयोग करें |
| `Pullable.builder()` | कॉन्फ़िगर करने योग्य | पूर्ण `PullableConfig` नियंत्रण |

### उदाहरण

``` dart
// Classic header
Pullable.classicHeader(
  onRefresh: () async => await refreshData(),
  child: myListView,
)

// Material header
Pullable.materialClassicHeader(
  onRefresh: () async => await refreshData(),
  child: myListView,
)

// No bounce effect
Pullable.noBounce(
  onRefresh: () async => await refreshData(),
  headerType: PullableHeaderType.classic,
  child: myListView,
)

// Custom header widget
Pullable.custom(
  customHeader: MyCustomRefreshHeader(),
  onRefresh: () async => await refreshData(),
  child: myListView,
)
```

<div id="pullable-config"></div>

## PullableConfig

बारीक नियंत्रण के लिए, `Pullable.builder()` कंस्ट्रक्टर के साथ `PullableConfig` का उपयोग करें:

``` dart
Pullable.builder(
  config: PullableConfig(
    enablePullDown: true,
    enablePullUp: true,
    headerType: PullableHeaderType.materialClassic,
    onRefresh: () async => await refreshData(),
    onLoading: () async => await loadMoreData(),
    refreshCompleteDelay: Duration(milliseconds: 500),
    loadCompleteDelay: Duration(milliseconds: 300),
    physics: BouncingScrollPhysics(),
  ),
  child: myListView,
)
```

### सभी कॉन्फ़िगरेशन विकल्प

| प्रॉपर्टी | टाइप | डिफ़ॉल्ट | विवरण |
|----------|------|---------|-------------|
| `enablePullDown` | `bool` | `true` | पुल-डाउन-टू-रिफ्रेश सक्षम करें |
| `enablePullUp` | `bool` | `false` | पुल-अप-टू-लोड-मोर सक्षम करें |
| `physics` | `ScrollPhysics?` | null | कस्टम स्क्रॉल फ़िज़िक्स |
| `onRefresh` | `Future<void> Function()?` | null | रिफ्रेश कॉलबैक |
| `onLoading` | `Future<void> Function()?` | null | लोड-मोर कॉलबैक |
| `headerType` | `PullableHeaderType` | `waterDrop` | हेडर एनिमेशन स्टाइल |
| `customHeader` | `Widget?` | null | कस्टम हेडर विजेट |
| `customFooter` | `Widget?` | null | कस्टम फ़ुटर विजेट |
| `refreshCompleteDelay` | `Duration` | `Duration.zero` | रिफ्रेश पूरा होने से पहले विलंब |
| `loadCompleteDelay` | `Duration` | `Duration.zero` | लोड पूरा होने से पहले विलंब |
| `enableOverScroll` | `bool` | `true` | ओवर-स्क्रॉल इफेक्ट की अनुमति दें |
| `cacheExtent` | `double?` | null | स्क्रॉल कैश एक्सटेंट |
| `semanticChildCount` | `int?` | null | सिमेंटिक चिल्ड्रन काउंट |
| `dragStartBehavior` | `DragStartBehavior` | `start` | ड्रैग जेस्चर कैसे शुरू होते हैं |

<div id="header-styles"></div>

## हेडर स्टाइल

पाँच बिल्ट-इन हेडर एनिमेशन में से चुनें:

``` dart
enum PullableHeaderType {
  classic,           // Classic pull indicator
  waterDrop,         // Water drop animation (default)
  materialClassic,   // Material Design classic
  waterDropMaterial,  // Material water drop
  bezier,            // Bezier curve animation
}
```

कंस्ट्रक्टर या कॉन्फ़िग के माध्यम से स्टाइल सेट करें:

``` dart
// Via named constructor
Pullable.bezierHeader(
  onRefresh: () async => await refreshData(),
  child: myListView,
)

// Via config
Pullable.builder(
  config: PullableConfig(
    headerType: PullableHeaderType.bezier,
    onRefresh: () async => await refreshData(),
  ),
  child: myListView,
)
```

<div id="pull-up"></div>

## और लोड करने के लिए ऊपर खींचें

पुल-अप लोडिंग के साथ पेजिनेशन सक्षम करें:

``` dart
Pullable.builder(
  config: PullableConfig(
    enablePullDown: true,
    enablePullUp: true,
    onRefresh: () async {
      // Reset to page 1
      page = 1;
      items = await fetchItems(page: page);
      setState(() {});
    },
    onLoading: () async {
      // Load next page
      page++;
      List<Item> more = await fetchItems(page: page);
      items.addAll(more);
      setState(() {});
    },
  ),
  child: ListView.builder(
    itemCount: items.length,
    itemBuilder: (context, index) => ItemTile(item: items[index]),
  ),
)
```

<div id="custom-headers"></div>

## कस्टम हेडर और फ़ुटर

अपने स्वयं के हेडर और फ़ुटर विजेट प्रदान करें:

``` dart
Pullable.custom(
  customHeader: Container(
    height: 60,
    alignment: Alignment.center,
    child: CircularProgressIndicator(),
  ),
  customFooter: Container(
    height: 40,
    alignment: Alignment.center,
    child: Text("Loading more..."),
  ),
  enablePullUp: true,
  onRefresh: () async => await refreshData(),
  onLoading: () async => await loadMore(),
  child: myListView,
)
```

<div id="controller"></div>

## कंट्रोलर

प्रोग्रामेटिक नियंत्रण के लिए `RefreshController` का उपयोग करें:

``` dart
final RefreshController _controller = RefreshController();

Pullable(
  controller: _controller,
  onRefresh: () async => await refreshData(),
  child: myListView,
)

// Trigger refresh programmatically
_controller.triggerRefresh();

// Trigger loading programmatically
_controller.triggerLoading();

// Check state
bool refreshing = _controller.isRefreshing;
bool loading = _controller.isLoading;
```

### RefreshController पर एक्सटेंशन मेथड

| मेथड/गेटर | रिटर्न टाइप | विवरण |
|---------------|-------------|-------------|
| `triggerRefresh()` | `void` | मैन्युअल रूप से रिफ्रेश ट्रिगर करें |
| `triggerLoading()` | `void` | मैन्युअल रूप से लोड-मोर ट्रिगर करें |
| `isRefreshing` | `bool` | रिफ्रेश सक्रिय है या नहीं |
| `isLoading` | `bool` | लोडिंग सक्रिय है या नहीं |

<div id="extension-method"></div>

## एक्सटेंशन मेथड

`.pullable()` एक्सटेंशन का उपयोग करके किसी भी विजेट को पुल-टू-रिफ्रेश से लपेटा जा सकता है:

``` dart
ListView(
  children: items.map((item) => ListTile(title: Text(item.name))).toList(),
).pullable(
  onRefresh: () async {
    await fetchItems();
  },
)
```

कस्टम कॉन्फ़िग के साथ:

``` dart
myListView.pullable(
  onRefresh: () async => await refreshData(),
  pullableConfig: PullableConfig(
    headerType: PullableHeaderType.classic,
    enablePullUp: true,
    onLoading: () async => await loadMore(),
  ),
)
```

<div id="collection-view"></div>

## CollectionView एकीकरण

`CollectionView` बिल्ट-इन पेजिनेशन के साथ pullable वेरिएंट प्रदान करता है:

### CollectionView.pullable

``` dart
CollectionView<User>.pullable(
  data: (iteration) async => api.getUsers(page: iteration),
  builder: (context, item) => UserTile(user: item.data),
  onRefresh: () => print('Refreshed!'),
  headerStyle: 'WaterDropHeader',
)
```

### CollectionView.pullableSeparated

``` dart
CollectionView<User>.pullableSeparated(
  data: (iteration) async => api.getUsers(page: iteration),
  builder: (context, item) => UserTile(user: item.data),
  separatorBuilder: (context, index) => Divider(),
)
```

### CollectionView.pullableGrid

``` dart
CollectionView<Product>.pullableGrid(
  data: (iteration) async => api.getProducts(page: iteration),
  builder: (context, item) => ProductCard(product: item.data),
  crossAxisCount: 2,
  mainAxisSpacing: 8,
  crossAxisSpacing: 8,
)
```

### Pullable-विशिष्ट पैरामीटर

| पैरामीटर | टाइप | विवरण |
|-----------|------|-------------|
| `data` | `Function(int iteration)` | पेजिनेटेड डेटा कॉलबैक (iteration 1 से शुरू होता है) |
| `onRefresh` | `Function()?` | रिफ्रेश के बाद कॉलबैक |
| `beforeRefresh` | `Function()?` | रिफ्रेश शुरू होने से पहले हुक |
| `afterRefresh` | `Function(dynamic)?` | डेटा के साथ रिफ्रेश के बाद हुक |
| `headerStyle` | `String?` | हेडर टाइप का नाम (उदा., `'WaterDropHeader'`, `'ClassicHeader'`) |
| `footerLoadingIcon` | `Widget?` | फ़ुटर के लिए कस्टम लोडिंग इंडिकेटर |

<div id="examples"></div>

## उदाहरण

### रिफ्रेश के साथ पेजिनेटेड सूची

``` dart
class _PostListState extends NyState<PostListPage> {
  List<Post> posts = [];
  int page = 1;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Pullable.builder(
        config: PullableConfig(
          enablePullDown: true,
          enablePullUp: true,
          headerType: PullableHeaderType.materialClassic,
          onRefresh: () async {
            page = 1;
            posts = await api<PostApiService>((request) => request.getPosts(page: page));
            setState(() {});
          },
          onLoading: () async {
            page++;
            List<Post> more = await api<PostApiService>((request) => request.getPosts(page: page));
            posts.addAll(more);
            setState(() {});
          },
        ),
        child: ListView.builder(
          itemCount: posts.length,
          itemBuilder: (context, index) => PostCard(post: posts[index]),
        ),
      ),
    );
  }
}
```

### एक्सटेंशन के साथ सरल रिफ्रेश

``` dart
ListView(
  children: notifications
    .map((n) => ListTile(
      title: Text(n.title),
      subtitle: Text(n.body),
    ))
    .toList(),
).pullable(
  onRefresh: () async {
    notifications = await fetchNotifications();
    setState(() {});
  },
)
```
