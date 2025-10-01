<?php
$file = __DIR__ . '/../resources/views/edit-profile.blade.php';
$lines = file($file);
$stack = [];

foreach ($lines as $index => $line) {
    $num = $index + 1;
    // strip blade comments
    $plain = $line;
    if (preg_match('/@if\b/', $plain)) {
        $stack[] = [
            'type' => 'if',
            'line' => $num,
            'text' => trim($line)
        ];
    }
    if (preg_match('/@endif\b/', $plain)) {
        if (count($stack) > 0) {
            // pop last if
            array_pop($stack);
        } else {
            echo "Unmatched @endif at line {$num}\n";
        }
    }
}

if (count($stack) > 0) {
    echo "Unclosed directives (remaining on stack):\n";
    foreach ($stack as $s) {
        echo "  {$s['type']} at line {$s['line']}: {$s['text']}\n";
    }
} else {
    echo "No unclosed @if blocks found.\n";
}

// Also print counts for quick reference
$content = file_get_contents($file);
$counts = [
    "@if" => preg_match_all('/@if\b/', $content),
    "@else" => preg_match_all('/@else\b/', $content),
    "@endif" => preg_match_all('/@endif\b/', $content),
    "@section" => preg_match_all('/@section\b/', $content),
    "@endsection" => preg_match_all('/@endsection\b/', $content),
];

echo "\nDirective counts:\n";
foreach ($counts as $k => $v) {
    echo "  {$k}: {$v}\n";
}
