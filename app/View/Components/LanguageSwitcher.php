<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LanguageSwitcher extends Component
{
    /** @var array<string, array{name: string, native: string}> */
    public array $locales;

    public string $currentLocale;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->locales = config('localization.supported_locales');
        $this->currentLocale = app()->getLocale();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.language-switcher');
    }
}
