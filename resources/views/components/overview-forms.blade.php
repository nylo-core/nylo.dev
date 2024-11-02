@props(['latestVersionOfNylo'])

<small class="font-[sora] text-gray-500">Step 1</small>
<p class="mb-1 font-medium">Create a Form</p>

<x-code-highlighter language="bash" class="mb-5" title="terminal">
metro make:form RegisterForm
</x-code-highlighter>

<small class="font-[sora] text-gray-500">Step 2</small>
<p class="mb-1 font-medium">Modify your form</p>

<x-code-highlighter language="dart" class="mb-5" title="app/forms/register_form.dart">
class RegisterForm extends NyFormData {

    RegisterForm({String? name}) : super(name ?? "login");

    // Add your fields here
    @override
    fields() => [
        Field.capitalizeWords("Name",
            validator: FormValidator.notEmpty(),
        ),
        Field.email("Email",
            validator: FormValidator.email()
        ),
        Field.password("Password",
            validator: FormValidator.password(),
        ),
    ];
}
</x-code-highlighter>

<small class="font-[sora] text-gray-500">Step 3</small>
<p class="mb-1 font-medium">Use your form in a widget</p>

<x-code-highlighter language="dart" title="app/forms/register_form.dart">
AdvertForm form = AdvertForm();

@override
Widget build(BuildContext context) {
  return Scaffold(
    body: NyForm.list(form, children: [
        Button.primary("Submit", submitForm: (form, (data) {
            // Handle your form data here
            printInfo(data);
        }))
    ])
  );
}
</x-code-highlighter>
                        <p class="text-[18px] text-[#979DA2] mt-2" style="letter-spacing: -0.02em;">Manage, validate and submit data all in one place with Nylo Forms.</p>
                        <a href="{{ route('landing.docs', ['version' => $latestVersionOfNylo, 'page' => 'forms']) }}" target="_BLANK" class="inline-flex self-center text-[#6C7379]">
                            Learn more <img src="{{ asset('images/upper_right_arrow.png') }}" class="h-[20px] w-[20px] self-center">
                        </a>

                        <div class="grid grid-cols-2 gap-5 mt-5">
                            @foreach([
                                [
                                    'title' => 'Validation',
                                    'link' => 'form-validation'
                                ],
                                [
                                    'title' => 'Casts',
                                    'link' => 'form-casts'
                                ],
                                [
                                    'title' => 'Styling',
                                    'link' => 'form-style'
                                ],
                                [
                                    'title' => 'Dummy Data',
                                    'link' => 'form-dummy-data'
                                ],
                                ] as $item)
                                <a class="bg-[#f9f9f9] font-medium border border-slate-200 hover hover:bg-[#ffffff] py-2 rounded-lg text-center transition-all" href="{{ route('landing.docs', ['version' => $latestVersionOfNylo, 'page' => 'forms']) }}#{{ $item['link'] }}" target="_BLANK">{{ $item['title'] }}</a>
                            @endforeach
                        </div>
