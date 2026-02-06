<?php

namespace App\View\Components;

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

        // Create a DOM structure to hold the highlighted code
        $dom = [];
        for ($i = 0; $i < strlen($code); $i++) {
            $dom[$i] = [
                'char' => $code[$i],
                'classes' => [],
            ];
        }

        $patterns = [
            'dart' => [
                'string' => '/(&quot;.*?&quot;)|(&apos;.*?&apos;)/',
                'comment' => '/\/\/.*|\/\*[\s\S]*?\*\//',
                'keyword' => '/\b(void|var|final|const|class|extends|implements|for|if|else|while|do|switch|case|break|continue|return|in|is|as)\b(?![:.])/i',
                'boolean' => '/\b(true|false|null)\b(?![:.])/i',
                'function' => '/\b(\w+)(?=\()/',
                'variable' => '/\b[A-Z][a-zA-Z0-9]*\b/',
                'number' => '/\b\d+(?:\.\d+)?\b/',
            ],
            'bash' => [
                'string' => '/(&quot;.*?&quot;)|(&apos;.*?&apos;)/',
                'comment' => '/#.*/',
                'keyword' => '/\b(if|then|else|elif|fi|for|while|in|do|done|case|esac|function)\b/',
                'variable' => '/\$\w+/',
                'function' => '/\b(\w+)(?=\(\))/',
            ],
            'json' => [
                'string' => '/(&quot;.*?&quot;)/',
                'number' => '/\b\d+(?:\.\d+)?\b/',
                'boolean' => '/\b(true|false|null)\b/',
            ],
        ];

        // Priority map: lower number = higher priority
        $priorityMap = [
            'string' => 1,
            'comment' => 2,
            'keyword' => 3,
            'boolean' => 4,
            'function' => 5,
            'variable' => 6,
            'number' => 7,
        ];

        // Apply all patterns to the code
        foreach ($patterns[$this->language] as $class => $pattern) {
            preg_match_all(
                $pattern,
                $code,
                $matches,
                PREG_OFFSET_CAPTURE
            );

            foreach ($matches[0] as $match) {
                $start = $match[1];
                $length = strlen($match[0]);

                // Apply the class to each character in the match
                for ($i = $start; $i < $start + $length; $i++) {
                    if (isset($dom[$i])) {
                        $dom[$i]['classes'][$class] = $priorityMap[$class];
                    }
                }
            }
        }

        // Reconstruct the highlighted HTML
        $result = '';
        $currentClasses = [];

        for ($i = 0; $i < count($dom); $i++) {
            $char = $dom[$i]['char'];
            $classes = $dom[$i]['classes'];

            // Get the highest priority class (lowest number)
            $activeClass = null;
            $highestPriority = PHP_INT_MAX;

            foreach ($classes as $class => $priority) {
                if ($priority < $highestPriority) {
                    $highestPriority = $priority;
                    $activeClass = $class;
                }
            }

            // Check if we need to close a current span
            if (! empty($currentClasses) && $activeClass !== end($currentClasses)) {
                while (! empty($currentClasses)) {
                    $result .= '</span>';
                    array_pop($currentClasses);
                }
            }

            // Check if we need to open a new span
            if ($activeClass !== null && (empty($currentClasses) || $activeClass !== end($currentClasses))) {
                $result .= "<span class=\"$activeClass\">";
                $currentClasses[] = $activeClass;
            }

            // Add the character
            $result .= $char;
        }

        // Close any remaining spans
        while (! empty($currentClasses)) {
            $result .= '</span>';
            array_pop($currentClasses);
        }

        return $result;
    }
}
