<title>Pieśni</title>
<?php

include("../common/header.php");
include("../common/nav.php");
?>

<main>
    <input type="text" id="search" placeholder="szukaj..." autocomplete="off">
    <div id="presentationContainer"></div>
</main>

<?php
include("../common/footer.php");
?>
<script src="../js-scripts/PresentationService.js"></script>
<script>
    var presentations;
    var displayPresentations;

    getPresentations();

    search.addEventListener("input", e => {
        displayPresentations = presentations.filter(presentation => 
            presentation.title.toLowerCase().includes(search.value.toLowerCase()) || presentation.id.toString().includes(search.value)
        )

        console.log(displayPresentations);
        populate();
    })

    function populate() {
        presentationContainer.innerHTML = "";

        displayPresentations.forEach(presentation => {
            let presentationLinkElement = document.createElement("a");
            presentationLinkElement.classList.add("presentation");
            presentationLinkElement.textContent = `${presentation.id}. ${presentation.title || "Bez tytułu"} z dnia ${presentation.uploadDate.slice(0, 10)}`;

            presentationLinkElement.href = `./search.php?id=${presentation.id}`;

            presentationContainer.appendChild(presentationLinkElement);
        });
    }

    async function getPresentations() {
        await new PresentationService().get().then(data => {
            presentations = JSON.parse(data);
            displayPresentations = presentations;
            populate();
            console.log(displayPresentations);
        })
    }
</script>