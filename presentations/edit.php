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

if(!isset($_POST["customID"])) {

    $_POST["customID"] = [];
    $_POST["customText"] = [];

    foreach(json_decode($presentation["custom"], true) as $element) {
        $_POST["customID"][] = $element["id"];
        $_POST["customText"][] = $element["custom"];
    }

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
    $customTexts = [];

    for($i = 0; $i < count($_POST["customID"] ?? []); $i++)
    $customTexts[$_POST["customID"][$i]] = [ "id" => $_POST["customID"][$i], "custom" => $_POST["customText"][$i]];

    Presentation::update($id, $_POST["title"] ?? "Prezentacja bez nazwy", implode(",", $_POST["songs"]), $customTexts, isset($_POST["isPermanent"]));
    header("Location: " . SERVER_ROOT . "/presentation/?id=" . $id );
}

?>
<div class="customTextInput" id="customTextInput">
    <div class="spacerHalf"></div>
    <textarea placeholder="Własny tekst..." rows="8" cols="32" id="customTextInputArea"></textarea>
    <div class="stack centered">
        <button onclick="addCustom()">Zapisz</button>
        <button onclick="cancelCustomText(event)">Anuluj</button>
    </div>
</div>
<form method="post"  class="details basicForm">
    <h1>Edycja prezentacji</h1>
    <a target="blank" href="https://github.com/M4gnez-thxforstealingmyusername/spiewnik-one-light/blob/main/instrukcja.md#edycja-prezentacji">Pomoc</a>
    <div class="spacerHalf"></div>
    <input type="text" autocomplete="off" name="title" value="<?php echo $_POST["title"] ?? $presentation["title"] ?>" required placeholder="Tytuł..." maxlength="50">
    <div class="spacerHalf"></div>

    <h2 class="noMargin">Kolejność pieśni:</h2>

    <ol id="songList"></ol>

    <!--<input type="submit" value="Odśwież listę pieśni" name="refresh">-->
    <button id="addSongButton">Dodaj kolejną pieśń</button>
    <div class="spacerHalf"></div>

    <div id="songSelectionHolder">
        <input type="text" autocomplete="off" id="search" placeholder="Szukaj...">
        <button onclick="prepareCustomText(event)">Własna</button>
        <div id="songSelection"></div>
    </div>

    <div class="spacer"></div>

    <div class="stack">
        <input type="checkbox" name="isPermanent" <?php echo isset($_GET["isPermanent"]) ? "checked" : "" ?>> Stała prezentacja
    </div>

    <input type="submit" value="Zapisz">
    <button onclick="openPresentation(event)">Uruchom</button>
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

    var customTexts = [];

    const savedSongs = <?php

    if(!isset($_POST["songs"]))
        $_POST["songs"] = $presentation["songs"] ?? "";

    $_POST["songs"] = is_array($_POST["songs"]) ? $_POST["songs"] : explode(",", $_POST["songs"]);

    if(isset($_POST["songs"])) {

        $normalSongs = [];
        $customTexts = [];

        for($i = 0; $i < count($_POST["customID"] ?? []); $i++)
            $customTexts[$_POST["customID"][$i]] = [ "id" => $_POST["customID"][$i], "custom" => $_POST["customText"][$i]];

        foreach ($_POST["songs"] as $element) {
            if($element[0] != "C")
            $normalSongs[] = $element;
        }

        $songList = [];

        if(count($normalSongs ?? []) != 0)
            $songList = Song::getList(implode(",", $normalSongs));

        $songListAssoc = [];

        foreach($songList as $element) 
        $songListAssoc[$element["id"]] = $element;

        $readyList = [];

        foreach ($_POST["songs"] as $element) {
            if($element[0] == "C")
                $readyList[] = $customTexts[substr($element, 1)];
            else
                $readyList[] = $songListAssoc[$element];
        }

        echo json_encode($readyList ?? []);
    }
    else echo "[]";
    ?>

    var order = <?php echo $order ?>;

    load();

    function load() {
        if(savedSongs)
            savedSongs.forEach(song => {
                if(!song.custom)
                    addSong(song);
                else
                    addCustom(song);
            })
    }

    addSongButton.addEventListener("click", (e) => {
        e.preventDefault();

        refreshList(songs);

        songSelectionHolder.style.display = "block";
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

        function openPresentation(event) {
        event.preventDefault();

        let songListElements = Array.from(document.querySelectorAll("input[name='songs[]']"));
        let songIds = [];

        songListElements.forEach(element => {
            songIds.push(element.value);
        })

        window.open("<?php echo SERVER_ROOT ?>/presentations/show/?songs=" + songIds.join(",") + "&custom=" + JSON.stringify(customTexts), 'blank', 'width=1920,height=1080,fullscreen=yes,toolbar=no,scrollbars=no,resizable=no,location=no,directories=no,status=no');
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

                songSelectionHolder.style.display = "none";
    }

    function prepareCustomText(e) {
        e.preventDefault()

        customTextInput.style.display = "block";
    }

    function cancelCustomText(e) {
        e.preventDefault()

        customTextInput.style.display = "none";
    }

    function addCustom(song = null) {
        let custom;
        if(!song)
            custom = customTextInputArea.value;
        else
            custom = song.custom;

        const li = document.createElement("li");
        li.setAttribute("order", order++);
        li.classList = "songListElement";
        li.textContent = custom.substring(0, 50);
        li.style.fontStyle = "italic";

        const id = document.createElement("input");
        id.type = "hidden";
        id.name = "songs[]";

        let currentId;

        if(customTexts.length == 0)
            currentId = 1
        else
            currentId = (customTexts[customTexts.length-1][0]+1)

        id.value = "C" + currentId;

        const customId = document.createElement("input");
        customId.type = "hidden";
        customId.name = "customID[]";
        customId.value = currentId;

        const customTextHidden = document.createElement("input");
        customTextHidden.type = "hidden";
        customTextHidden.name = "customText[]";
        customTextHidden.value = custom;


        li.appendChild(id);
        li.appendChild(customId);
        li.appendChild(customTextHidden);

        customTexts.push([currentId, custom])

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

                customTexts = customTexts.filter(item => item[0] !== parseInt(id.value.substring(1)));

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

        songSelectionHolder.style.display = "none";
        customTextInput.style.display = "none";
    }
</script>
