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
    <form method="post" class="details basicForm userForm">
        <?php echo $result == 0 ? "<p>Nie udało się utworzyć konta, spróbuj ponownie później</p>" : "" ?>
        <input type="text" autocomplete="off" name="login" placeholder="Login..." require>
        <?php echo $result == 2 ? "<small>Podany login jest już zajęty</small>" : "" ?>
        <input type="text" autocomplete="off" name="displayName" placeholder="Nazwa użytkownika..." require>
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
        <a href="https://github.com/M4gnez-thxforstealingmyusername/spiewnik-one-light/blob/main/instrukcja.md#Tworzenie-konta">Pomoc</a>
        <div class="agreement">
            <input type="checkbox" required> Oświadczam, że przeczytałem(am) i akceptuję <a href="https://github.com/M4gnez-thxforstealingmyusername/spiewnik-one-light/blob/main/regulamin.md">Regulamin</a> oraz <a href="https://github.com/M4gnez-thxforstealingmyusername/spiewnik-one-light/blob/main/oświadczenieOPlikachCookie.md">Oświadczenie o plikach cookie</a>.
        </div>

        <input type="submit" value="Utwórz">
    </form>
<?php

footerComponent();
?>