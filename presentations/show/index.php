<?php
    require_once "../../config.php";
?>

<link rel="stylesheet" href="<?php echo SERVER_ROOT ?>/show.css">

<pre id="display"></pre>
<script>
    var songs = <?php echo json_encode(Song::getTextList($_GET["songs"] ?? "", json_decode($_GET["custom"] ?? "[]"))) ?>

    console.log(songs);
    console.log("test");

    var slides = [];

    var currentSlide = 0;

    display.style.backgroundColor = "black";
    display.style.color = "white";

    songs.forEach(song => {
        let text = song.text;
        text = text.replaceAll(/\]\n/g, "]")
        text = text.replaceAll(/\n\[/g, "[")
        slides = slides.concat(text.split(/\[.+\]/));
    });

    slides.push("");

    document.addEventListener("click", (e) => {
        e.preventDefault();

        nextSlide();
    });

    document.addEventListener("contextmenu", (e) => {
        e.preventDefault();

        previousSlide();
    });

    document.addEventListener("keydown", (e) => {
        switch(e.key) {
            case "ArrowRight":
                nextSlide();
                break;
            case "ArrowLeft":
                previousSlide();
                break;
            case "m":
                toggleTheme();
                break;
            case "r":
                resetSlide();
                break;
            case "h":
                hideText();
                break;
            case "Escape":
                window.close();
                break;
        }
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

    function hideText() {
        if(display.style.backgroundColor == "black" && display.style.color === "white") {
            display.style.color = "black";
        } else if(display.style.backgroundColor == "black" && display.style.color !== "white") {
            display.style.color = "white";
        } else if(display.style.backgroundColor == "white" && display.style.color === "white") {
            display.style.color = "black";
        } else if(display.style.backgroundColor == "white" && display.style.color !== "white") {
            display.style.color = "white";
        }
    }

</script>