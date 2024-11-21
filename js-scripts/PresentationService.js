class PresentationService {
    async get() {
        var returnData;

        await $.get("/spiewnik-one-light/php-scripts/presentation/get.php", (data) => {returnData = data});

        return returnData;
    }
}