@props(['latestVersionOfNylo'])

<p class="mb-1">Create a state managed widget</p>

<x-code-highlighter language="bash" title="terminal" class="col-span-1 mb-5">
metro make:stateful_widget CartIcon
</x-code-highlighter>

<x-code-highlighter language="dart" title="resources/cart_icon_widget.dart" class="col-span-1 mb-5">
class _CartIconState extends NyState<CartIcon> {

    String? _cartValue;

    @override
    void stateUpdated(data) {
        _cartValue = data;
        // data is passed from the updateState method
        setState(() {});
    }

    @override
    Widget build(BuildContext context) {
        return Badge(
            child: Icon(Icons.shopping_cart),
            label: Text(_cartValue ?? "1"),
        );
    }
}
</x-code-highlighter>

<p>Now, you can update this widget from anywhere in your application</p>
<p class="mb-1">Use the <b>updateState</b> method like in the below code snippet</p>

<x-code-highlighter language="dart" title="another widget" class="col-span-1 mb-5">
Button.primary(text: "Add to cart",
    onPressed: () {
      updateState(CartIcon.state, data: "2");
    }
)
</x-code-highlighter>

<p>You can pass any object to the widget</p>

                        <p class="text-[18px] text-[#979DA2] mt-2" style="letter-spacing: -0.02em;">Powerful state management for widgets in your Flutter application.</p>
                        <a href="{{ route('landing.docs', ['version' => $latestVersionOfNylo, 'page' => 'state-management']) }}" target="_BLANK" class="inline-flex self-center text-[#6C7379]">
                            Learn more <img src="{{ asset('images/upper_right_arrow.png') }}" class="h-[20px] w-[20px] self-center">
                        </a>

                        <div class="grid grid-cols-2 gap-5 mt-5">
                            @foreach([
                                [
                                    'title' => 'Lifecycle',
                                    'link' => 'lifecycle'
                                ],
                                [
                                    'title' => 'State Actions',
                                    'link' => 'state-actions'
                                ],
                                ] as $item)
                                <a class="bg-[#f9f9f9] font-medium border border-slate-200 hover hover:bg-[#ffffff] py-2 rounded-lg text-center transition-all" href="{{ route('landing.docs', ['version' => $latestVersionOfNylo, 'page' => 'state-management']) }}#{{ $item['link'] }}" target="_BLANK">{{ $item['title'] }}</a>
                            @endforeach
                        </div>
