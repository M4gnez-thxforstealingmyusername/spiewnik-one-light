<?php
require "./../config.php";

$id = $_GET["id"] ?? 0;

$presentation = Presentation::getOne($id);

headerComponent("Usuń " . $presentation["title"] ?? "...");

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

if(!$presentation) {
    ?>
        <div class="details">
            <h1>Nie znaleziono prezentacji</h1>
            <p>Podana prezentacja nie istnieje</p>
        </div>
    <?php
    footerComponent();
    exit();
}


if(isset($_POST["confirm"])) {
    Presentation::delete($id);

    header("Location: " . SERVER_ROOT . "/presentations");
}

?>
<div class="details">
    <h1>Czy na pewno chcesz usunąć "<?php echo $presentation['title'] ?>", tej operacji nie można cofnąć!</h1>
    <form method="post">
        <input type="submit" value="Usuń" name="confirm">
    </form>
</div>

<?php

footerComponent();
?>