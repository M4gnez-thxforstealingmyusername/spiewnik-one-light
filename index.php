<?php
require "config.php";

headerComponent("Śpiewnik");
?>
<div class="panel">
    <h2>Ostatnie pieśni</h2>
    <ol>
    <?php
        foreach(Song::getTop() as $song) {
            linkListComponent("/song?id=" . $song["id"], $song["title"]);
        }
    ?>
    </ol>
</div>
<div class="panel">
    <h2>Ostatnie prezentacje</h2>
    <ol>
    <?php
        foreach(Presentation::getTop() as $presentation) {
            linkListComponent("/song?id=" . $presentation["id"], $presentation["title"]);
        }
    ?>
    </ol>
</div>
<?php
footerComponent();
?>