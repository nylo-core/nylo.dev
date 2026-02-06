<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class HeroCodeTabs extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public string $latestVersionOfNylo = '7.x')
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.hero-code-tabs');
    }
}
