<?php
// Font Loading Test
echo "<h2>Font Loading Test</h2>";

// Test Bunny Fonts access
$font_url = "https://fonts.bunny.net/css?family=poppins:400,500,600";

echo "<h3>Testing Bunny Fonts Access:</h3>";
echo "<p>URL: <a href='$font_url' target='_blank'>$font_url</a></p>";

// Test with cURL
if (function_exists('curl_init')) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $font_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($response && $http_code == 200) {
        echo "✅ <strong>Bunny Fonts accessible via cURL</strong><br>";
        echo "Response length: " . strlen($response) . " bytes<br>";
        echo "<details><summary>Font CSS Content (first 500 chars)</summary>";
        echo "<pre>" . htmlspecialchars(substr($response, 0, 500)) . "...</pre>";
        echo "</details>";
    } else {
        echo "❌ <strong>Bunny Fonts NOT accessible via cURL</strong><br>";
        echo "HTTP Code: $http_code<br>";
        echo "Error: $error<br>";
    }
} else {
    echo "❌ cURL not available<br>";
}

echo "<hr>";

// Test with file_get_contents
echo "<h3>Testing with file_get_contents:</h3>";
$context = stream_context_create([
    'http' => [
        'timeout' => 10,
        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
    ]
]);

$font_css = @file_get_contents($font_url, false, $context);
if ($font_css) {
    echo "✅ <strong>Bunny Fonts accessible via file_get_contents</strong><br>";
    echo "Response length: " . strlen($font_css) . " bytes<br>";
} else {
    echo "❌ <strong>Bunny Fonts NOT accessible via file_get_contents</strong><br>";
}

echo "<hr>";

// Test font rendering
echo "<h3>Font Rendering Test:</h3>";
echo "<link href='$font_url' rel='stylesheet'>";
echo "<div style='font-family: Poppins, sans-serif; font-size: 24px; margin: 20px 0;'>";
echo "This text should use Poppins font from Bunny Fonts";
echo "</div>";

echo "<div style='font-family: Arial, sans-serif; font-size: 24px; margin: 20px 0;'>";
echo "This text uses Arial as fallback";
echo "</div>";

// Check browser console for errors
echo "<script>";
echo "console.log('Font test page loaded');";
echo "document.fonts.ready.then(() => {";
echo "  console.log('Fonts loaded:', document.fonts.size);";
echo "  document.fonts.forEach(font => console.log('Font:', font.family, font.style, font.weight));";
echo "});";
echo "</script>";
?>
