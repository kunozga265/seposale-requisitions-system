<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        * {
            font-family: 'Inter', sans-serif;
            text-transform: none;
            font-size: 12px;
        }

        @font-face {
            font-family: 'Exo Font';
            font-weight: bold;
            src: url({{storage_path()."/fonts/Exo2-Bold.ttf"}}) format("ttf");
        }

        @font-face {
            font-family: 'Rubik';
            font-weight: bold;
            src: url({{storage_path("/fonts/Rubik-Bold.ttf")}}) format("ttf");
        }

        @font-face {
            font-family: 'Inter';
            font-weight: normal;
            src: url({{storage_path("/fonts/Inter-Regular.ttf")}}) format("ttf");
        }

        @font-face {
            font-family: 'Inter';
            font-weight: bold;
            src: url({{storage_path("/fonts/Inter-Bold.ttf")}}) format("ttf");
        }

        td, th {
            border: 1px solid;
            padding: 14px 6px;
            text-align: left;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        table.details {
            margin-left: -6px;
        }

        table.details td {
            padding: 2px 8px;
            border: none;

        }

        table.details tr td:first-child {
            width: 150px;
            margin-left: -6px;
        }

        table.details tr:nth-child(odd) {
            /*background-color: #f2f2f2;*/
        }

        table.summary td, table.summary th {
            border: 1px solid;
            padding: 8px;
            text-align: left;
        }

        table.summary th {
            background-color: rgb(217, 217, 217);
            text-transform: none;
        }

        table.summary .total td {
            font-weight: bold;
            text-align: right;
        }

        table.summary .total-in-words {
            font-weight: bold;
            text-align: center;
            text-transform: capitalize;
        }

        .heading {
            font-family: 'Rubik', sans-serif;
            font-size: 14px;
            /*padding-bottom: 8px;*/
            font-weight: bold;
            margin: 24px 0 8px;
        }

        .b-0 {
            border: none;
        }

        .font-bold {
            font-weight: bold;
        }


    </style>
</head>
<body>
{{--<p style="text-align: right; font-size: 12px">Generated on {{$date}} at {{$time}}</p>--}}
<img style="width: 100%" src="{{storage_path()."/images/banner.png"}}" alt="">
<div style="padding: 0 20px">
    <div style="margin: 30px 0 0">
        <div style="float: right">
            <div
                style=" margin-left: 12px; padding:0; text-transform: capitalize">
                {{$date}}
            </div>
        </div>
        <div style="font-size: 25px; font-weight: normal; margin-top:0px">Daily Report:
            <span
                style="color:red; font-size: 25px; font-weight: normal; ">#{{$code}}</span>
        </div>

    </div>

    {{--<div style="text-align: center; font-size: 16px; font-weight: normal">Code: {{$sale->code}}</div>--}}

    {{--    <table >--}}

    {{--        <tr>--}}
    {{--            <td class="b-0" style="vertical-align: top;">--}}
    <div class="heading" >Stock Report</div>
    <table class="summary">
        <thead>
        <tr>

            <th class="shade"></th>
            @foreach($inventories = json_decode($summary->opening_stock) as $inventory)
            <th class="shade">{{$inventory->name}}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>

            <tr>
                <td style="text-transform: none">Opening</td>
                @foreach($inventories = json_decode($summary->opening_stock) as $inventory)
                <td style="text-align: left">{{$inventory->availableStock}}</td>
                @endforeach
            </tr>

            @if(count(json_decode($summary->closing_stock)) != 0)
                <tr>
                    <td style="text-transform: none">Closing</td>
                    @foreach($inventories = json_decode($summary->closing_stock) as $inventory)
                        <td style="text-align: left">{{$inventory->availableStock}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td style="text-transform: none">Physical Count</td>
                    @foreach($inventories = json_decode($summary->closing_stock) as $inventory)
                        <td style="text-align: left">{{$inventory->availableStock + $inventory->uncollectedStock}}</td>
                    @endforeach
                </tr>
            @else

                <tr>
                    <td style="text-transform: none">Closing</td>
                    @foreach($summary->site->inventories as $inventory)
                        <td style="text-align: left">{{$inventory->available_stock}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td style="text-transform: none">Physical Count</td>
                    @foreach($summary->site->inventories as $inventory)
                        <td style="text-align: left">{{$inventory->available_stock + $inventory->uncollected_stock}}</td>
                    @endforeach
                </tr>
            @endif

        </tbody>
    </table>
    <div class="heading" >Sales</div>
    <table class="summary">
        <thead>
        <tr>
            <th class="shade">Code</th>
            <th class="shade">Client</th>
            <th class="shade">Product</th>
            <th class="shade" style="text-align: right">Quantity</th>
            <th class="shade" style="text-align: right">Amount</th>
{{--            <th class="shade" style="text-align: right">Balance</th>--}}
            <th class="shade">Payment Method</th>
{{--            <th class="shade">Collected</th>--}}
{{--            <th class="shade">Collection Status</th>--}}
        </tr>
        </thead>
        <tbody>
        @foreach($summary->sales as $sale)
            @foreach($sale->products as $product)
            <tr>
                <td style="text-transform: none">{{(new \App\Http\Controllers\AppController())->getZeroedNumber($sale->code)}}</td>
                <td style="text-align: left">{{$sale->client->name}}</td>
                <td style="text-align: left">{{$product->inventory->name}}</td>
                <td style="text-align: right">{{$product->quantity}}</td>
                <td style="text-align: right">{{number_format($product->amount,2)}}</td>
{{--                <td style="text-align: right">{{$product->balance}}</td>--}}
                @if($sale->paymentMethod != null)
                <td style="text-align: left">{{$sale->paymentMethod->name}}</td>
                @else
                    <td style="text-align: left">-</td>
                @endif
{{--                <td style="text-align: left">{{$product->collected}}</td>--}}
{{--                <td style="text-align: left">{{$product->getCollectionStatus()}}</td>--}}

            </tr>
        @endforeach
        @endforeach

        </tbody>
    </table>

    @if($sum != 0)
    <div style="text-align: right; margin-top: 12px">
        <div style="font-size: 18px; font-weight: normal">{{number_format($sum,2)}}</div>
        <div>Cash at hand</div>
    </div>
    @endif

    <div class="heading" >Collections</div>
    <table class="summary">
        <thead>
        <tr>
            <th class="shade">Code</th>
            <th class="shade">Client</th>
            <th class="shade">Product</th>
            <th class="shade" style="text-align: right">Quantity</th>
            <th class="shade" style="text-align: right">Balance</th>
        </tr>
        </thead>
        <tbody>
        @foreach($summary->collections as $collection)
            <tr>
                <td style="text-transform: none">{{(new \App\Http\Controllers\AppController())->getZeroedNumber($collection->siteSaleSummary->sale->code)}}</td>
                <td style="text-align: left">{{$collection->client->name}}</td>
                <td style="text-align: left">{{$collection->inventory->name}}</td>
                <td style="text-align: right">{{$collection->quantity}}</td>
                <td style="text-align: right">{{$collection->balance}}</td>
            </tr>
        @endforeach

        </tbody>
    </table>
</div>

</body>
</html>
