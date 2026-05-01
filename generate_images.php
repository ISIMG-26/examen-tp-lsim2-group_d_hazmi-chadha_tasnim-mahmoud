<?php
$uploadDir = 'uploads/';

// Create uploads folder if it doesn't exist
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$products = [
    'monstera.jpg' => '🌿 Monstera',
    'sansevieria.jpg' => '🌿 Sansevieria',
    'cactus.jpg' => '🌵 Cactus',
    'ficus.jpg' => '🌿 Ficus',
    'aloe.jpg' => '🌿 Aloe Vera',
    'calathea.jpg' => '🌿 Calathea',
    'pilea.jpg' => '🌿 Pilea',
    'orchidee.jpg' => '🌸 Orchidée'
];

foreach ($products as $filename => $text) {
    // Create image
    $img = imagecreatetruecolor(300, 200);
    
    // Colors
    $green = imagecolorallocate($img, 76, 175, 80);
    $white = imagecolorallocate($img, 255, 255, 255);
    $dark_green = imagecolorallocate($img, 46, 125, 50);
    
    // Fill background
    imagefill($img, 0, 0, $green);
    
    // Add a border
    imagerectangle($img, 0, 0, 299, 199, $dark_green);
    
    // Add text (using imagestring - built-in)
    imagestring($img, 5, 50, 80, $text, $white);
    imagestring($img, 3, 90, 110, "GreenShop", $white);
    
    // Save image
    imagejpeg($img, $uploadDir . $filename, 80);
    imagedestroy($img);
    
    echo "✅ Created: $filename<br>";
}

echo "<br>🎉 All images created! Go to <a href='shop.php'>shop.php</a> to see them.";
?>