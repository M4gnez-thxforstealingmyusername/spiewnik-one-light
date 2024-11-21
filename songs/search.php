<title>Śpiewnik</title>
<?php

include("../common/header.php");
include("../common/nav.php");

require_once("../php-scripts/config/conn.php");
if(isset($_GET["id"])) {

    $sql = "SELECT *, (SELECT username FROM `user` WHERE id = userId) as username FROM `song` WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_GET["id"]);
    $stmt->execute();

    $result = $stmt->get_result();

    $song;

    if($result->num_rows > 0) {
        $song = mysqli_fetch_assoc($result);
    }

    $sql = "SELECT *, (SELECT username FROM `user` WHERE id = userId) as username FROM `chord` WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_GET["id"]);
    $stmt->execute();

    $result = $stmt->get_result();

    $chord;

    if($result->num_rows > 0) {
        $chord = mysqli_fetch_assoc($result);
    }

}
?>

<main>
    <?php if(isset($song)) { ?>
        <h1><?php echo $song["id"] . ". " . $song["title"] ?></h1>
        <td><h3>Dodane przez: <?php echo $song["username"] . ", " . $song["uploadDate"] ?></h3></td>
        <?php if(isset($chord)) { ?>
            <td><h3>Akordy dodał: <?php echo $chord["username"] ?></h3></td>
        <?php } ?>

        <table id="songDetail">
            <tr>
                <th><h2>Tekst</h2></th>
                <th><h2>Akordy</h2></th>
            </tr>
            <tr>
                <td><p><?php echo join("<br><br>", json_decode($song["lyrics"])) ?></p></td>
                <?php if(isset($chord)) { ?>
                    <td><p><?php echo join("<br><br>", json_decode($chord["chords"])) ?></p></td>
                <?php } else {?>
                    <td><p>Brak akordów</p></td>
                <?php } ?>
            </tr>
        </table>
    <?php } else {?>
        <h2>Nie odnaleziono pieśni</h2>
    <?php } ?>
</main>

<?php
include("../common/footer.php");
?>