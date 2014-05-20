/**
 * Dynamic form to calculate an estimation of the price of the stay
 */

$(function () {

    // Get json data
    var file = "content/json/country_" + getUrlValue("id") + ".json";

    $.getJSON(file, function (json) {

        prices = json['features'][0]['properties']['prices'];

        $('#nbCinema').change(compute);
        $('#nbFastfood').change(compute);
        $('#nbBeer').change(compute);
        $('#gymYes').change(compute);
        $('#gymNo').change(compute);
        $('#transportYes').change(compute);
        $('#transportNo').change(compute);
        $('#internetYes').change(compute);
        $('#internetNo').change(compute);
        $('#durationStay').change(compute);

        function compute() {
            var inputs = {
                "nbCinema"  : parseInt($('#nbCinema').val()),
                "nbFastfood": parseInt($('#nbFastfood').val()),
                "nbBeer"    : parseInt($('#nbBeer').val()),
                "fitness"   : $('#gymYes').prop("checked") ? 1 : 0,
                "transport" : $('#transportYes').prop("checked") ? 1 : 0,
                "internet"  : $('#internetYes').prop("checked") ? 1 : 0,
                "duration"  : parseFloat($('#durationStay').val())
            }

            var categories = {
                "food"          :   inputs["nbFastfood"]  * parseFloat(prices['fastfood']) +
                                    inputs["nbBeer"]      * parseFloat(prices['beer']),
                "entertainment" :   inputs["nbCinema"]    * parseFloat(prices['cinema']) +
                                    inputs["fitness"]     * parseFloat(prices['fitness']),
                "various"       :   inputs["internet"]    * parseFloat(prices['internet']),
                "housing"       :   parseFloat(prices['rent'])
            }

            var result = 0;

            $.each(categories, function (k, v) { result += v; });

            result = !isNaN(result * inputs['duration']) ? result * inputs['duration'] : 0;

            result = Math.round(result).toFixed(2);

            // console.log(prices);
            // console.log(categories);
            // console.log(result);

            $('#result').text("Total: " + result + "$");
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