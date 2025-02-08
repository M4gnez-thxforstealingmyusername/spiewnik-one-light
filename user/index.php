<?php
require "./../config.php";

$id = $_GET["id"] ?? 0;

$user = User::getOne($id);

headerComponent($user["displayName"] ?? "Nie znaleziono użytkownika");

if(!$user) {
?>
    <h1>Nie znaleziono użytkownika</h1>
    <p>Podany użytkownik nie istnieje</p>
<?php
}
else {
?>
    <h1>#<?php echo $user["id"] ?>: <?php echo $user["displayName"] ?></h1>
    <p>Miasto: <?php echo City::getName($user["cityId"]) ?></p>
    <p>Data dołączenia: <?php echo $user["joinDate"] ?></p>
    <p>Poziom uprawnień: <?php echo $user["authorizationLevel"] ?></p>
<?php
}

footerComponent();
?>