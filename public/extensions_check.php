<?php
// PHP Extensions Check for Laravel
echo "<h2>Laravel Required Extensions Check</h2>";

$required_extensions = [
    'pdo' => 'PDO',
    'pdo_mysql' => 'PDO MySQL',
    'mysqli' => 'MySQLi',
    'fileinfo' => 'Fileinfo',
    'openssl' => 'OpenSSL',
    'mbstring' => 'Multibyte String',
    'tokenizer' => 'Tokenizer',
    'xml' => 'XML',
    'ctype' => 'Character Type',
    'json' => 'JSON',
    'bcmath' => 'BCMath',
    'curl' => 'cURL',
    'zip' => 'ZIP'
];

echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr><th>Extension</th><th>Status</th><th>Required</th></tr>";

foreach ($required_extensions as $ext => $name) {
    $loaded = extension_loaded($ext);
    $status = $loaded ? '✅ Loaded' : '❌ Missing';
    $required = in_array($ext, ['pdo', 'pdo_mysql', 'fileinfo', 'openssl', 'mbstring']) ? 'Critical' : 'Recommended';
    
    echo "<tr>";
    echo "<td><strong>$name</strong> ($ext)</td>";
    echo "<td>$status</td>";
    echo "<td>$required</td>";
    echo "</tr>";
}

echo "</table>";

echo "<hr>";
echo "<h3>PHP Version: " . phpversion() . "</h3>";
echo "<h3>PHP Configuration File: " . php_ini_loaded_file() . "</h3>";

// Test finfo class specifically
echo "<hr>";
echo "<h3>Fileinfo Test:</h3>";
if (class_exists('finfo')) {
    echo "✅ <strong>finfo class is available</strong><br>";
    try {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        echo "✅ <strong>finfo object created successfully</strong><br>";
    } catch (Exception $e) {
        echo "❌ <strong>Error creating finfo object:</strong> " . $e->getMessage() . "<br>";
    }
} else {
    echo "❌ <strong>finfo class is NOT available</strong><br>";
    echo "💡 <strong>Solution:</strong> Enable 'extension=fileinfo' in php.ini<br>";
}
?>
