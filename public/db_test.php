<?php
// Database Connection Test
echo "<h2>Database Connection Test</h2>";

// Test PDO MySQL connection
try {
    $host = '127.0.0.1';
    $dbname = 'siterians_clubhive_v2';
    $username = 'root';
    $password = 'daron_server#123';
    
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ <strong>PDO MySQL Connection: SUCCESS</strong><br>";
    
    // Test a simple query
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM sessions");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "✅ <strong>Query Test: SUCCESS</strong> - Found {$result['count']} sessions<br>";
    
} catch (PDOException $e) {
    echo "❌ <strong>PDO MySQL Connection: FAILED</strong><br>";
    echo "Error: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// Test MySQLi connection
try {
    $mysqli = new mysqli('127.0.0.1', 'root', 'daron_server#123', 'siterians_clubhive_v2');
    
    if ($mysqli->connect_error) {
        throw new Exception("Connection failed: " . $mysqli->connect_error);
    }
    
    echo "✅ <strong>MySQLi Connection: SUCCESS</strong><br>";
    
    // Test a simple query
    $result = $mysqli->query("SELECT COUNT(*) as count FROM sessions");
    $row = $result->fetch_assoc();
    echo "✅ <strong>MySQLi Query Test: SUCCESS</strong> - Found {$row['count']} sessions<br>";
    
    $mysqli->close();
    
} catch (Exception $e) {
    echo "❌ <strong>MySQLi Connection: FAILED</strong><br>";
    echo "Error: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// Show PHP extensions
echo "<h3>PHP Extensions Status:</h3>";
echo "PDO: " . (extension_loaded('pdo') ? '✅ Loaded' : '❌ Not Loaded') . "<br>";
echo "PDO MySQL: " . (extension_loaded('pdo_mysql') ? '✅ Loaded' : '❌ Not Loaded') . "<br>";
echo "MySQLi: " . (extension_loaded('mysqli') ? '✅ Loaded' : '❌ Not Loaded') . "<br>";

if (extension_loaded('pdo')) {
    echo "Available PDO drivers: " . implode(', ', PDO::getAvailableDrivers()) . "<br>";
}
?>
