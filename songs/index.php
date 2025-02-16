<?php
require "./../config.php";

headerComponent("Pieśni");

$page = $_GET["page"] ?? 1;
$search = $_GET["search"] ?? "";

?>
<div class="details">
<form>
    <input type="search" name="search" placeholder="Szukaj..." autocomplete="off">
    <input type="hidden" name="page" value="1">
</form>
<a class="highlight" href="<?php echo SERVER_ROOT ?>/songs/new.php">Nowa</a>

<div class="searchList">
<?php
foreach(Song::getPage($page, $search) as $song) {
    songComponent($song["id"], $song["title"], $song["userId"], $song["uploadDate"]);
}
?>
</div>

<div class="stack">
    <a class="highlight" href="<?php echo SERVER_ROOT . "/songs/?search=$search&page=" . ($page - 1 > 0 ? $page - 1 : 1) ?>">Poprzednia</a>
    <a class="highlight" href="<?php echo SERVER_ROOT . "/songs/?search=$search&page=" . ($page + 1) ?>">Następna</a>
</div>

</div>
<?php

footerComponent();
?>