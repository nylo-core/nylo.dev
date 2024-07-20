<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CodeHighlighter extends Component
{
    public $language;
    public $highlightedCode;
    public $title;
    public $class;
    public $header;

    public function __construct($language = 'dart', $class = '', $title = '')
    {
        $this->language = $language;
        $this->title = $title;
        $this->class = $class;
    }

    public function render()
    {
        return view('components.code-highlighter');
    }

    public function highlightCode($code)
    {
        $code = htmlspecialchars($code);

        $patterns = [
            'dart' => [
                'keyword' => '/\b(void|var|final|const|class|extends|implements|for|if|else|while|do|switch|case|break|continue|return|in|is|as)\b(?![:.])/i',
                'boolean' => '/\b(true|false|null)\b(?![:.])/i',
                'string' => '/(&quot;.*?&quot;)|(&apos;.*?&apos;)/',
                'comment' => '/\/\/.*|\/\*[\s\S]*?\*\//',
                'function' => '/\b(\w+)(?=\()/',
                'variable' => '/\b[A-Z][a-zA-Z0-9]*\b/',
                'number' => '/\b\d+(?:\.\d+)?\b/',
            ],
            'bash' => [
                'keyword' => '/\b(if|then|else|elif|fi|for|while|in|do|done|case|esac|function)\b/',
                'string' => '/(&quot;.*?&quot;)|(&apos;.*?&apos;)/',
                'comment' => '/#.*/',
                'variable' => '/\$\w+/',
                'function' => '/\b(\w+)(?=\(\))/',
            ],
        ];

        foreach ($patterns[$this->language] as $class => $pattern) {
            $code = preg_replace_callback(
                $pattern,
                function ($matches) use ($class) {
                    return "<span class=\"$class\">{$matches[0]}</span>";
                },
                $code
            );
        }

        return $code;
    }

    // /**
    //  * Get the view / contents that represent the component.
    //  */
    // public function render(): View|Closure|string
    // {
    //     return view('components.code-highlighter');
    // }
}
