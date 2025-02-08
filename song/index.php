<?php
require "./../config.php";

$id = $_GET["id"] ?? 0;

$song = Song::getOne($id);

headerComponent($song["title"] ?? "Nie znaleziono tekstu");

if(!$song) {
?>
    <h1>Nie znaleziono tekstu</h1>
    <p>Podana pieśń nie istnieje</p>
<?php
}
else {
?>
    <h1>#<?php echo $song["id"] ?>: <?php echo $song["title"] ?></h1>
    <p>Dodał(a): <a href="<?php echo SERVER_ROOT ?>/user/?id=<?php echo $song["userId"] ?>"><?php echo User::getName($song["userId"]) ?></a></p>
    <p>Data dodania: <?php echo $song["uploadDate"] ?></p>

    <div class="songGrid">
        <pre class="songColumn"><?php echo $song["text"] ?></pre>
        <pre class="songColumn"><?php echo $song["chord"] ?></pre>
    </div>

    <a href="<?php echo SERVER_ROOT ?>/songs/edit.php?id=<?php echo $song["id"] ?>">Edytuj</a>
    <a href="<?php echo SERVER_ROOT ?>/songs/delete.php?id=<?php echo $song["id"] ?>">Usuń</a>
<?php
}

footerComponent();
?>