<?php
$servername = "localhost";
$username = "dbuser";
$password = "dbpass";
$database = "phpdbtest";
$users_table = "Users";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PHP DB Test</title>
</head>
<body>

<?php echo "v1\n"; ?>
<hr>
<?php

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>
<hr>
<?php
// Create database
$sql = "CREATE DATABASE IF NOT EXISTS $database";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}

$conn->close();
echo "<hr>Disconnected";
?>
<hr>
<?php
// Create connection
$conn = new mysqli($servername, $username, $password, $database);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully<hr>";
}

// sql to create table
$sql = "CREATE TABLE IF NOT EXISTS $database.$users_table (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(30) NOT NULL,
password VARCHAR(50) NOT NULL
)";
// echo "$sql";

if ($conn->query($sql) === TRUE) {
    echo "Table Users created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}
?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (! empty($_POST["username"]) && ! empty($_POST["password"])) {
        echo "<hr>";
        $newusername = $_POST["username"];
        $newpassword = $_POST["password"];
        echo "New user " . $newusername . " with password " . $newpassword;

        $stmt = $conn->prepare("INSERT INTO $database.$users_table (username, password) VALUES ( ?, ?)");
        $stmt->bind_param("ss", $newusername, $newpassword);
        $stmt->execute();

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
    }
}
?>

<hr>
<h3>Users List</h3>
<?php
$sql = "SELECT id, username, password FROM $users_table";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    echo "<ul>";
    while($row = $result->fetch_assoc()) {
        echo "<li>id: " . $row["id"]. " - Name: " . $row["username"]. " " . $row["password"]. "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>0 results</p>";
}
?>

<hr>

<?php
$conn->close();
echo "Disconnected";
echo "<hr>";
?>

    <form action="db-test.php" method="post">
        Username: <input type="text" name="username">
        Password: <input type="text" name="password">
        <input type="submit" value="Submit">
    </form>
</body>
</html>
