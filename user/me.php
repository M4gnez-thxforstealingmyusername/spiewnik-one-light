<?php
require "./../config.php";

$id = $_GET["id"] ?? 0;

session_start();

$user = User::getPrivate();

headerComponent(isset($user["displayName"]) ? "Moje Konto" : "Nie zalogowano");

if(!$user) 
    accessDeniedComponent("nie zalogowano");
else {
?>
    <h1>#<?php echo $user["id"] ?>: <?php echo $user["displayName"] ?></h1>
    <p>Login: <?php echo $user["login"] ?></p>
    <p>Miasto: <?php echo City::getName($user["cityId"]) ?></p>
    <p>Data dołączenia: <?php echo $user["joinDate"] ?></p>
    <p>Poziom uprawnień: <?php echo $user["authorizationLevel"] ?></p>
<?php
}

footerComponent();
?>