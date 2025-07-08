<?php
require "./../config.php";

headerComponent("Utwórz konto");

if(User::ping())
    header("Location: " . SERVER_ROOT);

$result;

if(isset($_POST["login"]) && isset($_POST["password"])) {
    $result = User::login($_POST["login"], $_POST["password"]);
    if($result)
        header("Location: " . SERVER_ROOT);
}

?>
    <form method="post" class="details basicForm userForm">
        <h1>Logowanie</h1>
        <?php echo isset($result) && $result == false ? "<p>Nie udało się zalogować, spróbuj ponownie</p>" : "" ?>
        <input type="text" autocomplete="off" name="login" placeholder="Login..." required value="<?php echo $_POST["login"] ?? "" ?>">
        <input type="password" name="password" placeholder="Hasło..." require>
        <input type="submit" value="Zaloguj">
    </form>
<?php

footerComponent();
?>