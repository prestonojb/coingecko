@extends('layouts.app')

@section('meta')
    <title>Landing | {{ config('app.name') }}</title>
@endsection

@section('content')
<div class="container">
    <h1>To the moon 🚀</h1>

    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('/') }}">Coins</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="javascript:void(0)">Favourites</a>
        </li>
    </ul>

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
                    var $tr = "<tr>" +
                                "<td><button class='btn p-0 favouriteButton " + favourited + "' data-id='" + value.id + "'><span class='iconify' data-icon='ant-design:star-filled' data-inline='false'></span></button></td>" +
                                "<td>" + value.name + "</td>" +
                                "<td>" + value.symbol + "</td>" +
                                "<td>" + value.current_price + "</td>" +
                                "<td>" + value.total_volume + "</td>" +
                                "<td id='" + sparklineContainerId + "'" + ">Loading...</td>" +
                              "</tr>";

                    $pricesTable.append( $tr );

                    // Generate sparkline
                    var sparklineData = value.sparkline_in_7d.price;
                    var $sparklineContainer = $('#' + sparklineContainerId);
                    $sparklineContainer.sparkline(sparklineData);
                }
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
                    console.log('hi');
                },
                error: function() {
                    console.log('bye');
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