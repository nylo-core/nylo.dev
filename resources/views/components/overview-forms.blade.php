@props(['latestVersionOfNylo'])

<small class="font-[sora] text-gray-500">{{ __('Step 1') }}</small>
<p class="mb-1 font-medium dark:text-white">{{ __('Create a Form') }}</p>

<x-code-highlighter language="bash" class="mb-5" title="terminal">
metro make:form RegisterForm
</x-code-highlighter>

<small class="font-[sora] text-gray-500">{{ __('Step 2') }}</small>
<p class="mb-1 font-medium dark:text-white">{{ __('Modify your form') }}</p>

<x-code-highlighter language="dart" class="mb-5" title="app/forms/register_form.dart">
class RegisterForm extends NyFormWidget {

    RegisterForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

    // Add your fields here
    @override
    fields() => [
        Field.capitalizeWords("name",
            label: "Name",
            validator: FormValidator.notEmpty(),
        ),
        Field.email("email_address",
            label: "Email",
            validator: FormValidator.email()
        ),
        Field.password("password",
            label: "Password",
            validator: FormValidator.password(),
        ),
    ];

    static NyFormActions get actions => const NyFormActions("RegisterForm");
}
</x-code-highlighter>

<small class="font-[sora] text-gray-500">{{ __('Step 3') }}</small>
<p class="mb-1 font-medium dark:text-white">{{ __('Use your form in a widget') }}</p>

<x-code-highlighter language="dart" title="register_page.dart">
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: RegisterForm(
      submitButton: Button.primary(text: "Submit"),
      onSubmit: (data) {
        printInfo(data);
      },
    ),
  );
}
</x-code-highlighter>
                        <p class="text-[18px] text-[#979DA2] mt-2" style="letter-spacing: -0.02em;">{{ __('Manage, validate and submit data all in one place with Nylo Forms.') }}</p>
                        <a href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'forms']) }}" target="_BLANK" class="inline-flex self-center text-[#6C7379]">
                            {{ __('Learn more') }} <img src="{{ asset('images/upper_right_arrow.png') }}" class="h-[20px] w-[20px] self-center">
                        </a>

                        <div class="grid grid-cols-2 gap-5 mt-5">
                            @foreach([
                                [
                                    'title' => __('Validation'),
                                    'link' => 'form-validation'
                                ],
                                [
                                    'title' => __('Managing Data'),
                                    'link' => 'managing-form-data'
                                ],
                                [
                                    'title' => __('Field Styling'),
                                    'link' => 'field-styling'
                                ],
                                [
                                    'title' => __('Initial Data'),
                                    'link' => 'initial-data'
                                ],
                                ] as $item)
                                <a class="bg-[#f9f9f9] font-medium border border-slate-200 hover hover:bg-[#ffffff] py-2 rounded-lg text-center transition-all" href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'forms']) }}#{{ $item['link'] }}" target="_BLANK">{{ $item['title'] }}</a>
                            @endforeach
                        </div>
