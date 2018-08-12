<head>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/apps.css">
</head>
<body>
    <div class="row">
    <?php
        /* Attempt MySQL server connection. Assuming you are running MySQL
        server with default setting (user 'root' with no password) */
        $mysqli = new mysqli("localhost", "root", "", "applications");
        
        // Check connection
        if($mysqli === false){
            die("ERROR: Could not connect. " . $mysqli->connect_error);
        }
        
        // Attempt select query execution
        $sql = "SELECT * FROM applications";
        if($result = $mysqli->query($sql)){
            if($result->num_rows > 0){
                while($row = $result->fetch_array()){
                    echo '<div class="col-12 col-sm-12 col-md-6 col-lg-4">';
                    echo '<div class="card app-card" style="background: linear-gradient(145deg,' . $row["gradient1"] . ' 0%, ' . $row["gradient2"] . ' 100%);">';  
                    echo '<div class="card-body app-card-body">';
                    echo "<div class='top-strip'>";
                    echo "<div class='app-image'><img class='img-fluid' src=../../graphics/apps/{$row['icon']}><img></div>";
                    echo "<div class='top-text'>";
                    echo "<h1 class='app-name'>{$row['name']}</h1>";
                    echo "<h1 class='app-version'>Version {$row['version']}</h1></div></div>";
                    echo "<h1 class='app-description'>{$row['description']}</h1>";
                    echo "</div></div></div>";
                }
                // Free result set
                $result->free();
            } else{
                echo "No records matching your query were found.";
            }
        } else{
            echo "ERROR: Could not able to execute $sql. " . $mysqli->error;
        }
        
        // Close connection
        $mysqli->close();
    ?>
    </div>
</body>