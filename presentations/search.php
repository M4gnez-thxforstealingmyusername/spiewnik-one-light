<title>Śpiewnik</title>
<?php

include("../common/header.php");
include("../common/nav.php");

require_once("../php-scripts/config/conn.php");
if(isset($_GET["id"])) {

    $sql = "SELECT *, (SELECT username FROM `user` WHERE id = userId) as username FROM `presentation` WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_GET["id"]);
    $stmt->execute();

    $result = $stmt->get_result();

    $presentation;

    if($result->num_rows > 0) {
        $presentation = mysqli_fetch_assoc($result);
    }

    $ids = json_decode($presentation["code"]);

    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $sql = "SELECT id, title FROM `song` WHERE id in (" . $placeholders . ") ";
    $stmt = $conn->prepare($sql);

    $types = str_repeat('i', count($ids));
    $stmt->bind_param($types, ...$ids);
    $stmt->execute();

    $result = $stmt->get_result();

    $song;

    if($result->num_rows > 0) {
        $songs = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }


}
?>

<main>
    <?php if(isset($presentation)) { ?>
    <h1><?php echo $presentation["id"] . ". " . $presentation["title"] ?></h1>
    <h3><?php echo "Dodane przez " . $presentation["username"] . " " . $presentation["uploadDate"] ?>, </h3>
    <ol>

        <?php
            if(isset($presentation))

            foreach($songs as $song) {
                echo "<a href='../songs/search.php?id=" . $song["id"] . "'><li>" . $song["title"] . "</li></a>";
            }
        ?>
    </ol>
    <?php } else {?>
        <h2>Nie odnaleziono prezentacji</h2>
    <?php } ?>
</main>

<?php
include("../common/footer.php");
?>