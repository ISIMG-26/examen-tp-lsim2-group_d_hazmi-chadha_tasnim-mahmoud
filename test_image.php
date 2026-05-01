<?php
echo "<h2>Test des images</h2>";

$images = [
    'monstera.jpg',
    'sansevieria.jpg',
    'cactus.jpg',
    'ficus.jpg',
    'aloe.jpg',
    'calathea.jpg',
    'pilea.jpg',
    'orchidee.jpg'
];

echo "<div style='display: flex; flex-wrap: wrap; gap: 20px;'>";
foreach ($images as $img) {
    $path = "uploads/$img";
    echo "<div style='text-align: center;'>";
    echo "<img src='$path' width='150' height='150' style='object-fit: cover; border-radius: 10px;' onerror=\"this.src='https://placehold.co/150/red/white?text=ERREUR'\">";
    echo "<br>$img";
    echo "</div>";
}
echo "</div>";
?>