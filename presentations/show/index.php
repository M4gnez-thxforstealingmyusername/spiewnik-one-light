<?php
    require_once "../../config.php";
?>

<pre id="display"></pre>

<script>
    var songs = <?php echo json_encode(Song::getTextList($_GET["songs"] ?? "")) ?>

    var slides = [];

    var currentSlide = 0;

    songs.forEach(song => {
        let text = song.text;
        text = text.replaceAll(/\]\n/g, "]")
        text = text.replaceAll(/\n\[/g, "[")
        slides = slides.concat(text.split(/\[.+\]/));
    });

    slides.push("");

    document.addEventListener("keydown", (e) => {
        if(e.key == "ArrowRight")
            nextSlide();

        if(e.key == "ArrowLeft")
            previousSlide();

        if(e.key == "m")
            toggleTheme();

        if(e.key == "r")
            resetSlide();

        if(e.key == "Escape")
            window.close();
    });

    function changeSlide() {
        display.textContent = slides[currentSlide];
    }

    function nextSlide() {
        if(currentSlide < slides.length - 1)
            currentSlide++;

        changeSlide();
    }

    function previousSlide() {
        if(currentSlide > 0)
            currentSlide--;

        changeSlide();
    }

    function resetSlide() {
        currentSlide = 0;

        changeSlide();
    }

    function toggleTheme() {
        if(display.style.backgroundColor == "black") {
            display.style.backgroundColor = "white";
            display.style.color = "black";
        } else {
            display.style.backgroundColor = "black";
            display.style.color = "white";
        }
    }

</script>