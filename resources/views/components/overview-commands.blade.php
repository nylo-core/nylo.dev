@props(['latestVersionOfNylo'])

<small class="font-[sora] text-gray-500">Step 1</small>
<p class="mb-1 font-medium">Create a Command</p>

<x-code-highlighter language="bash" class="mb-5" title="terminal">
metro make:command CurrentTime
</x-code-highlighter>

<small class="font-[sora] text-gray-500">Step 2</small>
<p class="mb-1 font-medium">Modify your command</p>

<x-code-highlighter language="dart" class="mb-5" title="app/commands/current_time.dart">
class _CurrentTimeCommand extends NyCustomCommand {
    _CurrentTimeCommand(super.arguments);

    @override
    CommandBuilder builder(CommandBuilder command) {
        command.addOption("format", defaultValue: "HH:mm:ss");
        return command;
    }

    @override
    Future<void> handle(CommandResult result) async {
        final format = result.getString("format");
        final DateFormat dateFormat = DateFormat(format);

        // Format the current time
        final formattedTime = dateFormat.format(DateTime.now());
        
        info("The current time is " + formattedTime);
    }
}
</x-code-highlighter>

<small class="font-[sora] text-gray-500">Step 3</small>
<p class="mb-1 font-medium">Run your command</p>

<x-code-highlighter language="bash" title="terminal">
metro app:current_time
</x-code-highlighter>
                        <p class="text-[18px] text-[#979DA2] mt-2" style="letter-spacing: -0.02em;">Create custom commands within your project</p> 
                        <a href="{{ route('landing.docs', ['version' => $latestVersionOfNylo, 'page' => 'metro#creating-custom-commands']) }}" target="_BLANK" class="inline-flex self-center text-[#6C7379]">
                            Learn more <img src="{{ asset('images/upper_right_arrow.png') }}" class="h-[20px] w-[20px] self-center">
                        </a>

                        <div class="grid grid-cols-2 gap-5 mt-5">
                            @foreach([
                                [
                                    'title' => 'Adding options',
                                    'link' => 'adding-options-to-custom-commands'
                                ],
                                [
                                    'title' => 'Adding flags',
                                    'link' => 'adding-flags-to-custom-commands'
                                ],
                                [
                                    'title' => 'Neworking commands',
                                    'link' => 'custom-command-helper-api'
                                ],
                                [
                                    'title' => 'Helper methods',
                                    'link' => 'custom-command-helper-methods'
                                ]
                                ] as $item)
                                <a class="bg-[#f9f9f9] font-medium border border-slate-200 hover hover:bg-[#ffffff] py-2 rounded-lg text-center transition-all" href="{{ route('landing.docs', ['version' => $latestVersionOfNylo, 'page' => 'metro']) }}#{{ $item['link'] }}" target="_BLANK">{{ $item['title'] }}</a>
                            @endforeach
                        </div>
