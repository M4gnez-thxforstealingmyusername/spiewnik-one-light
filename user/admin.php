<?php
require "./../config.php";

$id = $_GET["id"] ?? 0;

session_start();

$admin = User::getPrivate();

headerComponent("Panel administratora");

if(!$admin) {
    accessDeniedComponent("nie zalogowano");
    footerComponent();
    exit();
}

if($_SESSION["authorizationLevel"] < 4) {
    accessDeniedComponent("brak uprawnień administratora");
    footerComponent();
    exit();
}
?>
    <h1>Panel administracji</h1>
    <div>
        <a href="<?php echo SERVER_ROOT . "/user/admin.php/?panel=users" ?>">Użytkownicy</a>
        <a href="<?php echo SERVER_ROOT . "/user/admin.php/?panel=clearPresentations" ?>">Oczyść prezentacje</a>
    </div>
<?php
switch($_GET["panel"] ?? ""){
    case "users":
        $cities = City::getAll();
            ?>
                <form method="get">
                    <input type="search" name="search" placeholder="Szukaj...">
                    <input type="hidden" name="panel" value="users">
                </form>
                <table>
            <?php
            foreach(User::getAdmin($_GET["search"] ?? "") as $user){
                ?>
                    <tr>
                        <td>
                            #<?php echo $user["id"] ?>
                        </td>
                        <td>
                            <?php echo $user["login"] ?>
                        </td>
                        <td>
                            <?php echo $user["displayName"] ?>
                        </td>
                        <td>
                            <?php echo $cities[$user["cityId"]]["cityName"] ?>
                        </td>
                        <td>
                            <form action="<?php echo SERVER_ROOT ?>/user/adminTools/setAuthorization.php" method="post">
                                <?php
                                if($user["authorizationLevel"] == 4)
                                    echo 4;
                                else {?>
                                    <select name="authorizationLevel">
                                        <option <?php echo $user["authorizationLevel"] == 0 ? "selected" : "" ?>>0</option>
                                        <option <?php echo $user["authorizationLevel"] == 1 ? "selected" : "" ?>>1</option>
                                        <option <?php echo $user["authorizationLevel"] == 2 ? "selected" : "" ?>>2</option>
                                        <option <?php echo $user["authorizationLevel"] == 3 ? "selected" : "" ?>>3</option>
                                    </select>
                                    <input type="hidden" name="id" value="<?php echo $user["id"] ?>">
                                    <input type="submit">
                                <?php } ?>
                            </form>
                        </td>
                    </tr>
                <?php
            }
            ?>
                </table>
            <?php
        break;
    case "clearPresentations":
        ?>
            <p>Prezentacji do usunięcia: <?php echo Presentation::countNotPermanent() ?></p>
            <form action="<?php echo SERVER_ROOT ?>/user/adminTools/clearPresentations.php" method="post">
                <button type="submit">Usuń</button>
            </form>
        <?php
        break;
}

footerComponent();
?>