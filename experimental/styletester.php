<?php
    include "../config.php";
?>

<link rel="stylesheet" href="../theme.css">
<link rel="stylesheet" href="../style.css">

<div class="panel">
    <h1>Napis</h1>
    <button>Przycisk</button>
    <br>
    <input type="text" value="pole edycyjne">
    <br>
    <div class="agreement">
        <a>Tekst wyróżniony</a>
    </div>
</div>

<form>
    Podstawowy:
    <input type="color" id="primary">
    <br>
    Drugorzędny:
    <input type="color" id="secondary">
    <br>
    Tło:
    <input type="color" id="background">
    <br>
    Czcionka:
    <input type="color" id="font">
    <br>
    Wyróżnienie:
    <input type="color" id="emphasis">
</form>

<button id="exportButton">Eksportuj</button>

<script>

    primary.addEventListener("input", (e) => {
        document.documentElement.style.setProperty('--primary', primary.value);
    });

    secondary.addEventListener("input", (e) => {
        document.documentElement.style.setProperty('--secondary', secondary.value);
    });

    background.addEventListener("input", (e) => {
        document.documentElement.style.setProperty('--background', background.value);
    });

    font.addEventListener("input", (e) => {
        document.documentElement.style.setProperty('--font', font.value);
    });

    emphasis.addEventListener("input", (e) => {
        document.documentElement.style.setProperty('--emphasis', emphasis.value);
    });

    exportButton.addEventListener("click", (e) => {
        let inputs = Array.from(document.querySelectorAll("input[type='color']"));

        inputs.forEach(input => {
            document.write(`--${input.id}: ${input.value};<br>`);
        });
    })

</script>