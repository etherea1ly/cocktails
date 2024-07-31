<?php
$data = [
    [
        'title' => 'Page 1',
        'header' => 'Welcome to Page 1',
        'content' => 'This is the content of page 1.',
        'footer' => 'Footer of Page 1'
    ],
    [
        'title' => 'Page 2',
        'header' => 'Welcome to Page 2',
        'content' => 'This is the content of page 2.',
        'footer' => 'Footer of Page 2'
    ],
];

function generateHtmlFile($template, $data, $outputFile) {
    ob_start();
    extract($data);
    include $template;
    $html = ob_get_clean();
    
    file_put_contents($outputFile, $html);
}

foreach ($data as $index => $pageData) {
    $outputFile = "page" . ($index + 1) . ".html";
    generateHtmlFile('shablon.php', $pageData, $outputFile);
    echo "Generated $outputFile\n";
}