<?php
require "./../config.php";

$id = $_GET["id"] ?? 0;

$song = Song::getOne($id);

headerComponent($song["title"] ?? "Nie znaleziono tekstu");

?>
    <div class="details">
<?php

if(!$song) {
?>
    <h1>Nie znaleziono tekstu</h1>
    <p>Podana pieśń nie istnieje</p>
<?php
}
else {

    $verseNameTags = [];

    preg_match_all("/\[.+\]/", $song["text"], $verseNameTags);

    $splitTextList = preg_split("/\[.+\](\n|\r\n)/", $song["text"]);
    $splitTexts = preg_replace("/\n$/", "", $splitTextList);
    array_shift($splitTexts);

    $splitChordList = preg_split("/\[.+\](\n|\r\n)/", $song["chord"]);
    $splitChords = preg_replace("/\n$/", "", $splitChordList);
    array_shift($splitChords);

?>
    <h1>#<?php echo $song["id"] ?>: <?php echo $song["title"] ?></h1>
    <p>Dodał(a): <a href="<?php echo SERVER_ROOT ?>/user/?id=<?php echo $song["userId"] ?>"><?php echo User::getName($song["userId"]) ?></a></p>
    <p>Data dodania: <?php echo $song["uploadDate"] ?></p>

    <div class="songGrid">
        <pre class="songColumn"><?php for($i = 0; $i < count($verseNameTags[0]); $i++) { ?><h2><?php echo $verseNameTags[0][$i] ?></h2><?php echo $splitTexts[$i] ?><br><br><?php } ?></pre>
        <pre class="songColumn"><?php for($i = 0; $i < count($verseNameTags[0]); $i++) { ?><h2><?php echo $verseNameTags[0][$i] ?></h2><?php echo $splitChords[$i] ?? "nie podano" ?><br><br><?php } ?></pre>
    </div>

    <div class="stack">
        <a class="highlight" href="<?php echo SERVER_ROOT ?>/songs/edit.php?id=<?php echo $song["id"] ?>">Edytuj</a>
        <a class="highlight" href="<?php echo SERVER_ROOT ?>/songs/delete.php?id=<?php echo $song["id"] ?>">Usuń</a>
    </div>

    </div>
<?php
}

footerComponent();
?>