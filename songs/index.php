<?php
require "./../config.php";

headerComponent("Pieśni");

$page = $_GET["page"] ?? 1;
$search = $_GET["search"] ?? "";

?>
<form>
    <input type="search" name="search" placeholder="Szukaj..." autocomplete="off">
    <input type="hidden" name="page" value="1">
</form>
<a href="<?php echo SERVER_ROOT ?>/songs/new.php">Nowa</a>
<?php

foreach(Song::getPage($page, $search) as $song) {
    songComponent($song["id"], $song["title"], $song["userId"], $song["uploadDate"]);
}

?>
<div>
    <a href="<?php echo SERVER_ROOT . "/songs/?search=$search&page=" . ($page - 1 > 0 ? $page - 1 : 1) ?>">Poprzednia</a>
    <a href="<?php echo SERVER_ROOT . "/songs/?search=$search&page=" . ($page + 1) ?>">Następna</a>
</div>
<?php

footerComponent();
?>