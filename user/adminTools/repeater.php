<?php

    $servername = "sql302.epizy.com";
    $username = "epiz_33621546";
    $password = "Ie6QKsoKZLvL4p2";
    $dbname = "epiz_33621546_spiewnik";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error)
        die("Connection failed: " . $conn->connect_error);


$result = $conn->query("Select * from song where id_song = " . $_GET["id"])->fetch_assoc();



?>

<a href="http://spiewnik.epizy.com/lubiemuzyke/repeater.php?id=<?php echo $_GET["id"] + 1 ?>">następna</a>

<br>

<?php



echo $result["title"] ?? "Pieśń nie istnieje";

echo "<br><br>";

echo "<pre>" . $result["text"] . "</pre>";