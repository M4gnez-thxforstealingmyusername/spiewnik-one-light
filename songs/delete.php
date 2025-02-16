<?php
require "./../config.php";

$id = $_GET["id"] ?? 0;

$song = Song::getOne($id);

headerComponent("Usuń " . $song["title"] ?? "...");

if(!isset($_SESSION["id"])) {
    accessDeniedComponent("nie zalogowano");
    footerComponent();
    exit();
}

if($_SESSION["authorizationLevel"] < 3) {
    accessDeniedComponent("poziom uprawnień za niski");
    footerComponent();
    exit();
}

?>
    <div class="details">
<?php

if(!$song) {
    ?>
        <h1>Nie znaleziono prezentacji</h1>
        <p>Podana prezentacja nie istnieje</p>
    <?php
    footerComponent();
    exit();
}


if(isset($_POST["confirm"])) {
    Song::delete($id);

    header("Location: " . SERVER_ROOT . "/songs");
}

?>

<h1>Czy na pewno chcesz usunąć "<?php echo $song['title'] ?>", tej operacji nie można cofnąć!</h1>
<form method="post">
    <input type="submit" value="Usuń" name="confirm">
</form>

</div>
<?php

footerComponent();
?>