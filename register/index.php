<?php
require "./../config.php";

headerComponent("Utwórz konto");

if(User::ping())
    header("Location: " . SERVER_ROOT);

$result = -1;

if(isset($_POST["login"]) && isset($_POST["displayName"]) && isset($_POST["password"]) && isset($_POST["cityId"])) {
    $result = User::register($_POST["login"], $_POST["displayName"], $_POST["password"],$_POST["cityId"]);
    if($result == 1)
        header("Location: " . SERVER_ROOT);
}

?>
    <form method="post">
        <?php echo $result == 0 ? "<p>Nie udało się utworzyć konta, spróbuj ponownie później</p>" : "" ?>
        <input type="text" name="login" placeholder="Login..." require>
        <?php echo $result == 2 ? "<small>Podany login jest już zajęty</small>" : "" ?>
        <input type="text" name="displayName" placeholder="Nazwa użytkownika..." require>
        <input type="password" name="password" placeholder="Hasło..." require>
        <select name="cityId" require>
            <?php
                foreach(City::getAll() as $city) {
                    ?>
                        <option value="<?php echo $city["id"] ?>"><?php echo $city["cityName"] ?></option>
                    <?php
                }
            ?>
        </select>
        <!--TODO:Regulamin i oświadczenie o plikach cookie-->
        <input type="checkbox" required> Oświadczam, że przeczytałem(am) i akceptuję <a href="">Regulamin</a> oraz <a href="">Oświadczenie o plikach cookie</a>.
        <input type="submit">
    </form>
<?php

footerComponent();
?>