@extends('layouts.app')

@section('meta')
    <title>Landing | {{ config('app.name') }}</title>
@endsection

@section('content')
<div class="container">
    <h1>To the moon 🚀</h1>

    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link active" href="#">Coins</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" @auth href="{{ route('favourites') }}"
                                @else href="{{ route('login') }}" @endauth>Favourites</a>
        </li>
    </ul>

    <div class="form-group">
        <label for="">Currency</label>
        <select class="custom-select" id="currencySelect">
            <optgroup label="fiat"></optgroup>
            <optgroup label="crypto"></optgroup>
        </select>
    </div>

    <div class="table-responsive">
        <table class="table" id="pricesTable">
            <tr>
                <th></th>
                <th>Name</th>
                <th>Symbol</th>
                <th>Price</th>
                <th>Volume</th>
                <th>Last 7 days</th>
            </tr>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-sparklines/2.1.2/jquery.sparkline.min.js"></script>
<script>
$(function(){
    var $pricesTable = $('#pricesTable');
    @auth
        var favouriteCoinIds = "{{ json_encode(auth()->user()->favouriteCoinIds()) }}";
    @else
        var favouriteCoinIds = [];
    @endauth

    $.ajax({
        method: "get",
        url: "https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&sparkline=true",
        success: function(coins){

            $.each(coins, function(key, value) {
                var sparklineContainerId = value.id + "_sparkline";
                var coinIsFavourited = favouriteCoinIds.includes(value.id);

                if(coinIsFavourited) {
                    var favourited = 'favourited';
                } else {
                    var favourited = '';
                }

                var $tr = "<tr data-id='" + value.id + "'>" +
                            "<td><button class='btn p-0 favouriteButton " + favourited + "' data-id='" + value.id + "'><span class='iconify' data-icon='ant-design:star-filled' data-inline='false'></span></button></td>" +
                            "<td>" + value.name + "</td>" +
                            "<td>" + value.symbol + "</td>" +
                            "<td class='currentPrice' data-price-in-usd='" + value.current_price + "'><span class='currencySymbol'>$</span><span class='value'>" + value.current_price + "</span></td>" +
                            "<td class='currentVolume' data-volume-in-usd='" + value.total_volume + "'>" + "<span class='currencySymbol'>$</span><span class='value'>" + value.total_volume + "</span></td>" +
                            "<td id='" + sparklineContainerId + "'" + ">Loading...</td>" +
                          "</tr>";
                $pricesTable.append( $tr );

                // Generate sparkline
                var sparklineData = value.sparkline_in_7d.price;
                var $sparklineContainer = $('#' + sparklineContainerId);
                $sparklineContainer.sparkline(sparklineData);

            });
            // $('#pricesTable').DataTable();
        },
        error: function(){
            alert('CoinGecko API unavailable, please try again later.');
        }
    });

    @auth
        $('body').on('click', '.favouriteButton', function(){
            var coin_id = $(this).data('id');
            $(this).toggleClass('favourited');
            $.ajax({
                method: "post",
                data: {
                    "_token": "{{ csrf_token() }}",
                    coin_id: coin_id
                },
                url: "/coins/favourite",
                success: function(status) {
                    // Toggle favourite on back-end
                    if(status == 'OK') {
                        $(this).toggleClass('favourited');
                    }
                },
                error: function() {
                },
            });
        });
    @else
        $('body').on('click', '.favouriteButton', function(){
            window.location.href = "/login";
        });
    @endauth
});
</script>
@endsection
