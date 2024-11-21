<title>Pieśni</title>
<?php

include("../common/header.php");
include("../common/nav.php");
?>

<main>
    <input type="text" id="search" placeholder="szukaj..." autocomplete="off">
    <div id="songContainer"></div>
</main>

<?php
include("../common/footer.php");
?>
<script src="../js-scripts/SongService.js"></script>
<script>
    var songs;
    var displaySongs;

    getSongs();

    search.addEventListener("input", e => {
        displaySongs = songs.filter(song => 
            song.title.toLowerCase().includes(search.value.toLowerCase()) || song.id.toString().includes(search.value)
        )

        console.log(displaySongs);
        populate();
    })

    function populate() {
        songContainer.innerHTML = "";

        displaySongs.forEach(song => {
            let songLinkElement = document.createElement("a");
            songLinkElement.classList.add("song");
            songLinkElement.textContent = `${song.id}. ${song.title}`;

            songLinkElement.href = `./search.php?id=${song.id}`;

            songContainer.appendChild(songLinkElement);
        });
    }

    async function getSongs() {
        await new SongService().get().then(data => {
            songs = JSON.parse(data);
            displaySongs = songs;
            populate();
            console.log(displaySongs);
        })
    }
</script>