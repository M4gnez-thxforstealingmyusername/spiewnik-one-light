<?php
require "../../config.php";

headerComponent("Kreator prezentacji na żywo");

$order = 0;

?>
<form method="post">
    <!--TODO: link do instrukcji, rozdział dodawanie prezentacji-->
    <a href="">Pomoc</a>
    <input type="submit" value="Odśwież listę prezentacji" name="refresh">

    <ol id="songList"></ol>

    <button id="addSongButton">Dodaj kolejną pieśń</button>

    <div id="songSelection">
        <input type="text" id="search" placeholder="Szukaj...">
    </div>

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

    const savedSongs = <?php echo json_encode(Song::getList(implode(",", $_POST["songs"] ?? []))) ?>

    var order = <?php echo $order ?>;

    load();

    function openPresentation(event) {
        event.preventDefault();

        let songListElements = Array.from(document.querySelectorAll("input[type=hidden]"));
        let songIds = [];

        songListElements.forEach(element => {
            songIds.push(element.value);
        })

        window.open("<?php echo SERVER_ROOT ?>/presentations/show/?songs=" + songIds.join(","), 'blank', 'width=1920,height=1080,fullscreen=yes,toolbar=no,scrollbars=no,resizable=no,location=no,directories=no,status=no');
    }

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
