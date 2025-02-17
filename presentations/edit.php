<?php
require "./../config.php";

headerComponent("Edycja prezentacji");

$id = $_GET["id"] ?? 0;

$presentation = Presentation::getOne($id);

if(!$presentation) {
    ?>
        <div class="details">
            <h1>Nie znaleziono prezentacji</h1>
            <p>Podana prezentacja nie istnieje</p>
        </div>
    <?php
    footerComponent();
    exit();
}

if(!User::ping()) {
    accessDeniedComponent("nie zalogowano");
    footerComponent();
    exit();
}

if($_SESSION["authorizationLevel"] < 2 || ($_SESSION["authorizationLevel"] < 1 && $presentation["userId"] != $_SESSION["id"])) {
    accessDeniedComponent("poziom uprawnień za niski");
    footerComponent();
    exit();
}

$order = 0;

if(!isset($_POST["refresh"]) && isset($_POST["songs"])) {
    Presentation::update($id, $_POST["title"] ?? "Prezentacja bez nazwy", implode(",", $_POST["songs"]), isset($_POST["isPermanent"]));
    header("Location: " . SERVER_ROOT . "/presentation/?id=" . $id );
}

?>
<form method="post"  class="details basicForm">
    <h1>Edytuj #<?php echo $presentation["id"] ?>: <?php echo $presentation["title"] ?></h1>
    <input type="text" autocomplete="off" name="title" value="<?php echo $_POST["title"] ?? $presentation["title"] ?>" required placeholder="Tytuł...">
    <a href="https://github.com/M4gnez-thxforstealingmyusername/spiewnik-one-light/blob/main/instrukcja.md#edycja-prezentacji">Pomoc</a>
    <input type="submit" value="Odśwież listę prezentacji" name="refresh">

    <ol id="songList"></ol>

    <button id="addSongButton">Dodaj kolejną pieśń</button>

    <div id="songSelection">
        <input type="text" autocomplete="off" id="search" placeholder="Szukaj...">
    </div>

    <div class="stack">
        <input type="checkbox" name="isPermanent" <?php echo isset($_GET["isPermanent"]) ? "checked" : "" ?>> Stała prezentacja
    </div>

    <input type="submit">
</form>
<?php

footerComponent();
?>

<script>
    const songs = <?php
        $songTitles = Song::getTitles();
        Sorter::sort($songTitles);
        echo json_encode($songTitles);
        ?>;

    const savedSongs = <?php echo json_encode(Song::getList(implode(",", $_POST["songs"] ?? explode(",", $presentation["songs"])))) ?>

    var order = <?php echo $order ?>;

    load();

    function load() {
        if(savedSongs)
            savedSongs.forEach(song => addSong(song));
    }

    addSongButton.addEventListener("click", (e) => {
        e.preventDefault();

        refreshList(songs);

        songSelection.style.display = "block";
    });

    search.addEventListener("input", (e) => {
        refreshList(songs.filter(x => x.title.toLowerCase().includes(search.value.toLowerCase())));
    });

    function reorder(newOrder) {
        let songListElements = newOrder ?? Array.from(document.getElementsByClassName("songListElement"));

        order = 0;

        songList.innerHTML = "";

        songListElements.forEach(songListElement => {
            songListElement.setAttribute("order", order++);
            songList.appendChild(songListElement);
        });
    }

    function moveDown(orderNumber) {
        if(orderNumber + 1 < order) {
            let songListElements = Array.from(document.getElementsByClassName("songListElement"));

            let temp = songListElements[orderNumber];
            songListElements[orderNumber] = songListElements[orderNumber + 1];
            songListElements[orderNumber + 1] = temp;

            reorder(songListElements);
        }

    }

    function moveUp(orderNumber) {
        if(orderNumber - 1 >= 0) {
            let songListElements = Array.from(document.getElementsByClassName("songListElement"));

            let temp = songListElements[orderNumber];
            songListElements[orderNumber] = songListElements[orderNumber - 1];
            songListElements[orderNumber - 1] = temp;

            reorder(songListElements);
        }

    }

    function refreshList(songArray) {
        Array.from(document.getElementsByClassName("songSelectionElement")).forEach(element => element.remove())

        songArray.forEach(song => {
            const songSelectionElement = document.createElement("p");
            songSelectionElement.classList = "songSelectionElement";
            songSelectionElement.textContent = song.title;

            songSelection.appendChild(songSelectionElement);

            songSelectionElement.addEventListener("click", (e) => {
                addSong(song);
            });
        });
    }

    function addSong(song) {
        const li = document.createElement("li");
                li.setAttribute("order", order++);
                li.classList = "songListElement";
                li.textContent = song.title;

                const id = document.createElement("input");
                id.type = "hidden";
                id.name = "songs[]";
                id.value = song.id;

                li.appendChild(id);

                li.addEventListener("contextmenu", (e) => {
                    e.preventDefault();

                    const menu = document.createElement("div");

                    if(li.innerHTML.includes("div"))
                        return;

                    const up = document.createElement("button");
                    up.textContent = "Przenieś w górę"

                    up.addEventListener("click", (e) => {
                        e.preventDefault();

                        moveUp(parseInt(li.getAttribute("order")));

                        menu.remove();
                    });

                    const down = document.createElement("button");
                    down.textContent = "Przenieś w dół"

                    down.addEventListener("click", (e) => {
                        e.preventDefault();

                        moveDown(parseInt(li.getAttribute("order")));

                        menu.remove();
                    });

                    const remove = document.createElement("button");
                    remove.textContent = "Usuń"

                    remove.addEventListener("click", (e) => {
                        e.preventDefault();

                        li.remove();

                        reorder();
                    });

                    const cancel = document.createElement("button");
                    cancel.textContent = "Anuluj"

                    cancel.addEventListener("click", (e) => {
                        e.preventDefault();

                        menu.remove();
                    });

                    menu.appendChild(up);
                    menu.appendChild(down);
                    menu.appendChild(remove);
                    menu.appendChild(cancel);

                    li.appendChild(menu);
                });

                songList.appendChild(li);

                songSelection.style.display = "none";
    }
</script>
