/**
 * Dynamic form to calculate an estimation of the price of the stay
 */

$(function () {

    // Get json data
    var file = "content/json/country_" + getUrlValue("id") + ".json";

    $.getJSON(file, function (json) {

        data = json['features'][0]['properties']['prices'];

        console.log(data);

        $('#nbCinema').change(compute);
        $('#gymYes').change(compute);
        $('#gymNo').change(compute);
        $('#transportYes').change(compute);
        $('#transportNo').change(compute);
        $('#internetYes').change(compute);
        $('#internetNo').change(compute);
        $('#nbBeer').change(compute);
        $('#durationStay').change(compute);

        function compute() {
            var nbCinema    = $('#nbCinema').val();
            var nbFastFood  = $('#nbFastFood').val();
            var nbFastFood  = $('#nbFastFood').val();
            var fitness     = $('#gymYes').prop("checked") ? 1 : 0;
            var transport   = $('#transportYes').prop("checked") ? 1 : 0;
            var internet    = $('#internetYes').prop("checked") ? 1 : 0;
            var nbBeer      = $('#nbBeer').val();
            var duration    = $('#durationStay').val();

            $('#map').text(fitness);
        }

    });
});

function getUrlValue(s){
    var searchString = window.location.search.substring(1);
    var array = searchString.split('&');
    for(var i = 0; i < array.length; i++){
        var keyValue = array[i].split('=');
        if(keyValue[0] == s){
            return keyValue[1];
        }
    }
}