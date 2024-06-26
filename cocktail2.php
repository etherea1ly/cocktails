<?php
function searchCocktailByName($name) {
    $url = "https://www.thecocktaildb.com/api/json/v1/1/search.php?s=" . urlencode($name);
    $response = file_get_contents($url);
    return json_decode($response, true);
}

function fileExists($filename) {
    return file_exists($filename);
}

function generateHtml($cocktail) {
    $name = $cocktail['strDrink'];
    $filename = $name . ".html";

    if (fileExists($filename)) {
        echo "Коктейль \"$name\" - файл уже существует\n";
        return;
    }

    $html = "<html><head><title>$name</title></head><body>";
    $html .= "<h1>$name</h1>";
    $html .= "<img src='" . $cocktail['strDrinkThumb'] . "' alt='$name'>";
    $html .= "<p><strong>Description:</strong> " . $cocktail['strInstructions'] . "</p>";
    $html .= "<p><strong>Glass:</strong> " . $cocktail['strGlass'] . "</p>";
    $html .= "<p><strong>Alcoholic:</strong> " . $cocktail['strAlcoholic'] . "</p>";
    $html .= "<h2>Ingredients</h2><ul>";

    for ($i = 1; $i <= 15; $i++) {
        $ingredient = $cocktail['strIngredient' . $i];
        $measure = $cocktail['strMeasure' . $i];
        if ($ingredient) {
            $html .= "<li>" . $ingredient . ($measure ? " - $measure" : "") . "</li>";
        }
    }

    $html .= "</ul></body></html>";

    file_put_contents($filename, $html);
    echo "Коктейль \"$name\" - создан файл\n";
}

// Main script
if ($argc < 2) {
    echo "Usage: php cocktail.php {name}\n";
    exit(1);
}

$name = $argv[1];
$response = searchCocktailByName($name);

if (isset($response['drinks']) && count($response['drinks']) > 0) {
    foreach ($response['drinks'] as $cocktail) {
        generateHtml($cocktail);
    }
} else {
    echo "Ничего не нашли\n";
}