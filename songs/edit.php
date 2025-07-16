<?php
require "../config.php";

headerComponent("Edycja pieśni");

$id = $_GET["id"] ?? 0;

$song = Song::getOne($id);

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

if($_SESSION["authorizationLevel"] < 3 || ($_SESSION["authorizationLevel"] < 2 && $presentation["userId"] != $_SESSION["id"])) {
    accessDeniedComponent("poziom uprawnień za niski");
    footerComponent();
    exit();
}

$id = $_GET["id"] ?? 0;

$song = Song::getOne($id);


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

    Song::update($id, $_POST["title"], $_POST["description"], $text, $chords);

    header("Location: " . SERVER_ROOT . "/song/?id=". $id);
}

?>
    <form method="post" class="details basicForm">
        <h1>Edycja pieśni</h1>
        <a target="blank" href="https://github.com/M4gnez-thxforstealingmyusername/spiewnik-one-light/blob/main/instrukcja.md#edycja-prezentacji">Pomoc</a>
        <input type="text" autocomplete="off" name="title" required placeholder="Tytuł..." value="<?php echo $song["title"] ?>" maxlength="50">
        <textarea name="description" class="description" placeholder="Opis, linki, etc..."><?php echo $song["description"] ?></textarea>
        <div class="spacerHalf"></div>
        <h2>Tekst pieśni:</h2>
        <div id="verses">
            <div class="verse">

            </div>
        </div>
        <button id="addVerseButton">Dodaj zwrotkę</button>

        <input type="submit" value="Zapisz">
    </form>
<?php
footerComponent();

?>

<script>
    var verseNames = <?php
        $oldVerseNames = [];
        $cleanOldVerseNames = [];

        preg_match_all("/\[.+\]/", $song["text"], $oldVerseNames);

        foreach($oldVerseNames as $oldVerseName) {
            $cleanText = str_replace("[", "", $oldVerseName);
            $cleanText = str_replace("]", "", $cleanText);
            array_push($cleanOldVerseNames, $cleanText);
        }

        echo json_encode($cleanOldVerseNames[0]);
    ?>

    var texts = <?php
        $splitTexts = preg_split("/\[.+\]\n/", $song["text"]);
        $newSplitTexts = preg_replace("/\n$/", "", $splitTexts);
        array_shift($newSplitTexts);

        echo json_encode($newSplitTexts);
    ?>

    var chords = <?php
        $splitChords = preg_split("/\[.+\]\n/", $song["chord"]);
        $newSplitChords = preg_replace("/\n$/", "", $splitChords);
        array_shift($newSplitChords);

        echo json_encode($newSplitChords);
    ?>

    for(i = 0; i < verseNames.length; i++) {
        addVerse(verseNames[i], texts[i], chords[i]);
    }

    addVerseButton.addEventListener("click", (e) => {
        e.preventDefault();

        addVerse("", "", "");
    });

    function addVerse(newVerseName, newText, newChords) {
        const verse = document.createElement("div");
        verse.classList = "verse";

        const verseName = document.createElement("input");
        verseName.type = "text";
        verseName.name = "verseName[]";
        verseName.placeholder = "Nazwa zwrotki...";
        verseName.toggleAttribute("required");
        verseName.value = newVerseName;

        const text = document.createElement("textarea");
        text.name = "text[]";
        text.placeholder = "Tekst...";
        text.rows = "8";
        text.cols = "32";
        text.toggleAttribute("required");
        text.textContent = newText;

        const chord = document.createElement("textarea");
        chord.name = "chords[]";
        chord.placeholder = "Akordy...";
        chord.rows = "8";
        chord.cols = "32";
        chord.textContent = newChords;

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
    }
</script>