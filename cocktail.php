<?php
function searchCocktailByName($name) {
    $url = "https://www.thecocktaildb.com/api/json/v1/1/search.php?s=" . urlencode($name);
    $response = file_get_contents($url);
    return json_decode($response, true);
}

function generateOrUpdateHtml($cocktails, $filename) {
    $htmlContent = "";

    foreach ($cocktails as $cocktail) {
        $name = $cocktail['strDrink'];
        $htmlContent .= "<div style='margin-bottom: 20px;'>";
        $htmlContent .= "<h2>$name</h2>";
        $htmlContent .= "<img src='" . $cocktail['strDrinkThumb'] . "' alt='$name' style='width: 200px;'>";
        $htmlContent .= "<p><strong>Description:</strong> " . $cocktail['strInstructions'] . "</p>";
        $htmlContent .= "<p><strong>Glass:</strong> " . $cocktail['strGlass'] . "</p>";
        $htmlContent .= "<p><strong>Alcoholic:</strong> " . $cocktail['strAlcoholic'] . "</p>";
        $htmlContent .= "<h3>Ingredients</h3><ul>";

        for ($i = 1; $i <= 15; $i++) {
            $ingredient = $cocktail['strIngredient' . $i];
            $measure = $cocktail['strMeasure' . $i];
            if ($ingredient) {
                $htmlContent .= "<li>" . $ingredient . ($measure ? " - $measure" : "") . "</li>";
            }
        }

        $htmlContent .= "</ul></div>";
    }

    if (file_exists($filename)) {
        $existingContent = file_get_contents($filename);
        $updatedContent = str_replace("</body></html>", $htmlContent . "</body></html>", $existingContent);
        file_put_contents($filename, $updatedContent);
    } else {
        $html = "<html><head><title>Cocktail Search Results</title></head><body>";
        $html .= "<h1>Cocktail Search Results</h1>";
        $html .= $htmlContent;
        $html .= "</body></html>";
        file_put_contents($filename, $html);
    }

    echo "Файл \"$filename\" был создан или дополнен информацией о коктейлях\n";
}

if ($argc < 2) {
    echo "Usage: php cocktail.php {name}\n";
    exit(1);
}

$name = $argv[1];
$response = searchCocktailByName($name);

if (isset($response['drinks']) && count($response['drinks']) > 0) {
    $filename = "cocktails.html";
    generateOrUpdateHtml($response['drinks'], $filename);
} else {
    echo "Ничего не нашли\n";
}
