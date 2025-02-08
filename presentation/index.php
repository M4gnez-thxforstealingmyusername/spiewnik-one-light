<?php
require "./../config.php";

$id = $_GET["id"] ?? 0;

$presentation = Presentation::getOne($id);

headerComponent($presentation["title"] ?? "Nie znaleziono prezentacji");

if(!$presentation) {
?>
    <h1>Nie znaleziono prezentacji</h1>
    <p>Podana prezentacja nie istnieje</p>
<?php
}
else {
?>
    <h1>#<?php echo $presentation["id"] ?>: <?php echo $presentation["title"] ?></h1>
    <p>Dodał(a): <a href="<?php echo SERVER_ROOT ?>/user/?id=<?php echo $presentation["userId"] ?>"><?php echo User::getName($presentation["userId"]) ?></a></p>
    <p>Data dodania: <?php echo $presentation["uploadDate"] ?></p>
    <p>Stała prezentacja: <?php echo $presentation["isPermanent"] ? "TAK" : "NIE" ?></p>

    <ol>
        <?php
            foreach(Song::getList($presentation["songs"]) as $song) {
                ?>
                    <a href="<?php echo SERVER_ROOT ?>/song/?id=<?php echo $song["id"] ?>"><li><?php echo $song["title"] ?></li></a>
                <?php
            }
        ?>
    </ol>

    <a href="<?php echo SERVER_ROOT ?>/presentations/edit.php?id=<?php echo $song["id"] ?>">Edytuj</a>
    <a href="<?php echo SERVER_ROOT ?>/presentations/delete.php?id=<?php echo $song["id"] ?>">Usuń</a>
    <a href="<?php echo SERVER_ROOT ?>/presentations/show/?id=<?php echo $song["id"] ?>">Uruchom</a>
<?php
}

footerComponent();
?>