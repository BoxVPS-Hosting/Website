<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "test";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT title, version, paragraph FROM test_table";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            echo "title: " . $row["title"]. " - version: " . $row["version"]. " " . $row["paragraph"]. "<br>";
        }
    } else {
        echo "0 results";
    }
    $conn->close();


    echo "<div>";

    echo "</div>";
?>

<h1>Test</h1>