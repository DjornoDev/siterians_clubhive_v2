<?php
// PHP Info Test File
// Copy this to your Windows Server to check PHP configuration

echo "<h2>PHP Version and Extensions</h2>";
echo "PHP Version: " . phpversion() . "<br><br>";

echo "<h3>PDO Extensions:</h3>";
if (extension_loaded('pdo')) {
    echo "✅ PDO extension is loaded<br>";
    
    if (extension_loaded('pdo_mysql')) {
        echo "✅ PDO MySQL extension is loaded<br>";
    } else {
        echo "❌ PDO MySQL extension is NOT loaded<br>";
    }
    
    echo "Available PDO drivers: " . implode(', ', PDO::getAvailableDrivers()) . "<br>";
} else {
    echo "❌ PDO extension is NOT loaded<br>";
}

echo "<h3>MySQL Extensions:</h3>";
if (extension_loaded('mysqli')) {
    echo "✅ MySQLi extension is loaded<br>";
} else {
    echo "❌ MySQLi extension is NOT loaded<br>";
}

echo "<h3>All Loaded Extensions:</h3>";
$extensions = get_loaded_extensions();
sort($extensions);
foreach ($extensions as $ext) {
    echo $ext . "<br>";
}

echo "<hr>";
echo "<h3>Full PHP Info:</h3>";
phpinfo();
?>
