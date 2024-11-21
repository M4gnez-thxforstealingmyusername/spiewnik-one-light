class SongService {
    async get() {
        var returnData;

        await $.get("/spiewnik-one-light/php-scripts/song/get.php", (data) => {returnData = data});

        return returnData;
    }
}