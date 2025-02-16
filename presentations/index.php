<?php
require "./../config.php";

headerComponent("Prezentacje");

$page = $_GET["page"] ?? 1;
$search = $_GET["search"] ?? "";

?>
<form>
    <input type="search" name="search" placeholder="Szukaj..." autocomplete="off">
    <input type="hidden" name="page" value="1">
</form>
<a href="<?php echo SERVER_ROOT ?>/presentations/new.php">Nowa</a>
<a href="<?php echo SERVER_ROOT ?>/presentations/live">Prezentacje na żywo</a>
<?php

foreach(Presentation::getPage($page, $search) as $presentation) {
    presentationComponent($presentation["id"], $presentation["title"], $presentation["userId"], $presentation["songs"], $presentation["uploadDate"]);
}

?>
<div>
    <a href="<?php echo SERVER_ROOT . "/presentations/?search=$search&page=" . ($page - 1 > 0 ? $page - 1 : 1) ?>">Poprzednia</a>
    <a href="<?php echo SERVER_ROOT . "/presentations/?search=$search&page=" . ($page + 1) ?>">Następna</a>
</div>
<?php

footerComponent();
?>