<?php
require "../config.php";

headerComponent("Kreator pieśni");

if(!User::ping()) {
    accessDeniedComponent("nie zalogowano");
    footerComponent();
    exit();
}

if($_SESSION["authorizationLevel"] < 2) {
    accessDeniedComponent("poziom uprawnień za niski");
    footerComponent();
    exit();
}

if(isset($_POST["title"])) {
    $text = "";
    $chords = "";

    $verseNames = $_POST["verseName"];
    $textVerses = $_POST["text"];
    $chordVerses = $_POST["chords"];

    for($i = 0; $i < count($verseNames); $i++) {
        $text .= "[" . $verseNames[$i] . "]\n";
        $text .= $textVerses[$i] . "\n";
        $chords .= "[" . $verseNames[$i] . "]\n";
        $chords .= $chordVerses[$i] . "\n";
    }

    Song::add($_POST["title"], $_SESSION["id"], $text, $chords);

    header("Location: " . SERVER_ROOT . "/song/?id=". Song::getTop()[0]["id"]);
}

?>
    <form method="post">
        <input type="text" name="title" required placeholder="Tytuł...">
        <!--TODO: link do instrukcji, rozdział dodawanie pieśni-->
        <a href="">Pomoc</a>
        <div id="verses">
            <div class="verse">
                <input type="text" name="verseName[]" placeholder="Nazwa zwrotki..." required>
                <textarea name="text[]" placeholder="Tekst..." required></textarea>
                <textarea name="chords[]" placeholder="Akordy..."></textarea>
            </div>
        </div>
        <button id="addVerse">Dodaj zwrotkę</button>

        <input type="submit">
    </form>
<?php
footerComponent();
?>

<script>
    addVerse.addEventListener("click", (e) => {
        e.preventDefault();

        const verse = document.createElement("div");
        verse.classList = "verse";

        const verseName = document.createElement("input");
        verseName.type = "text";
        verseName.name = "verseName[]";
        verseName.placeholder = "Nazwa zwrotki...";
        verseName.toggleAttribute("required");

        const text = document.createElement("textarea");
        text.name = "text[]";
        text.placeholder = "Tekst...";
        text.toggleAttribute("required");

        const chord = document.createElement("textarea");
        chord.name = "chords[]";
        chord.placeholder = "Akordy...";

        verse.appendChild(verseName);
        verse.appendChild(text);
        verse.appendChild(chord);

        verses.appendChild(verse);

        verse.addEventListener("contextmenu", (e) => {
            e.preventDefault();

            const deleteButton = document.createElement("button");
            deleteButton.textContent = "Usuń zwrotkę";

            verse.appendChild(deleteButton);

            deleteButton.addEventListener("click", (e) => {
                e.preventDefault();

                verse.remove();
            });

        })
    });
</script>