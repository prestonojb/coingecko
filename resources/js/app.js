require('./bootstrap');


var $currencySelect = $('#currencySelect');

$.ajax({
    method: "get",
    url: "https://api.coingecko.com/api/v3/exchange_rates",
    success: function(data){
        var exchange_rates = data.rates;
        var unitBtcToUsd = exchange_rates.usd.value;
        $.each(exchange_rates, function(key, value) {
            var rateToUsd = (value.value)/unitBtcToUsd;

            // Select USD by default
            if(value.name == 'US Dollar') {
                var option = "<option data-currency-symbol='" + value.unit + "' data-rate-to-usd='" + rateToUsd + "' selected>" + value.name + " (" + value.unit + ")" + "</option>";
            } else {
                var option = "<option data-currency-symbol='" + value.unit + "' data-rate-to-usd='" + rateToUsd + "'>" + value.name + " (" + value.unit + ")" + "</option>";
            }

            var type = value.type;
            $currencySelect.find('optgroup[label=' + type +']').append( $(option) );
        });
    },
});

$currencySelect.change(function(){
    var rateToUsd = $(this).find(":selected").data('rate-to-usd');
    var currencySymbol = $(this).find(":selected").data('currency-symbol');

    // Switch price
    $('td.currentPrice').each(function(key, currentPrice) {
        var priceInUsd = $(currentPrice).data('price-in-usd');
        var priceInSelectedCurrency = parseFloat(priceInUsd * rateToUsd).toFixed(8);

        $(currentPrice).find('.value').text(priceInSelectedCurrency);
        $('.currencySymbol').text(currencySymbol);
    });

    // Switch volume
    $('td.currentVolume').each(function(key, currentVolume) {
        var volumeInUsd = $(currentVolume).data('volume-in-usd');
        var volumeInSelectedCurrency = parseFloat(volumeInUsd * rateToUsd).toFixed(8);

        $(currentVolume).find('.value').text(volumeInSelectedCurrency);
        $('.currencySymbol').text(currencySymbol);
    });
});
