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

    Song::add($_POST["title"], $_SESSION["id"], $_POST["description"], $text, $chords);

    header("Location: " . SERVER_ROOT . "/song/?id=". Song::getTop()[0]["id"]);
}

?>
    <form method="post" class="details basicForm">
        <h1>Tworzenie pieśni</h1>
        <a href="https://github.com/M4gnez-thxforstealingmyusername/spiewnik-one-light/blob/main/instrukcja.md#Dodawanie-prezentacji">Pomoc</a>
        <input type="text" autocomplete="off" name="title" required placeholder="Tytuł..." maxlength="50">
        <textarea name="description" class="description" placeholder="Opis, linki, etc..."></textarea>
        <div class="spacerHalf"></div>
        <h2>Tekst pieśni:</h2>
        <div id="verses">
            <div class="verse">
                <input type="text" autocomplete="off" name="verseName[]" placeholder="Nazwa zwrotki..." required>
                <div class="verseContainer">
                    <textarea name="text[]" placeholder="Tekst..." required rows="8" cols="32"></textarea>
                    <textarea name="chords[]" placeholder="Akordy..." rows="8" cols="32"></textarea>
                </div>
            </div>
        </div>
        <button id="addVerse">Dodaj zwrotkę</button>

        <input type="submit" value="Zapisz">
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
        text.rows = "8";
        text.cols = "32";
        text.placeholder = "Tekst...";
        text.toggleAttribute("required");

        const chord = document.createElement("textarea");
        chord.name = "chords[]";
        chord.rows = "8";
        chord.cols = "32";
        chord.placeholder = "Akordy...";

        const verseContainer = document.createElement("div");
        verseContainer.classList = "verseContainer";

        verseContainer.appendChild(text);
        verseContainer.appendChild(chord);

        verse.appendChild(verseName);
        verse.appendChild(verseContainer);

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