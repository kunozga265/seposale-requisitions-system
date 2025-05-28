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

        td,
        th {
            border: 1px solid;
            padding: 14px 6px;
            text-align: left;
        }

        table {
            margin: 12px 0;
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
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

        table.summary td,
        table.summary th {
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
        <div style="margin: 30px 0">
            <div style="float: right">
                <div
                    style=" margin-left: 12px; padding:0; text-transform: capitalize">
                    {{$date}}
                </div>
            </div>
            <div style="font-size: 25px; font-weight: normal; margin-top:0px">Production Report: <span
                    style="color:red; font-size: 25px; font-weight: normal; ">#{{$code}}</span></div>
  <div>{{ $production->site->name}} One Stop Shop</div>


        </div>

        {{--<div style="text-align: center; font-size: 16px; font-weight: normal">Code: {{$invoice->code}}
    </div>--}}

     <p class="heading">Overview</p>
    <table class="summary">
        <thead>
            <tr>
                <th class="shade">Product</th>
                <th class="shade" style="text-align: right">Quantity</th>
            </tr>
        </thead>
        <tbody>
           @foreach($production->batches as $batch)
            <tr>
                <td style="text-transform: none">{{$batch->inventory->name}}</td>
                <td style="text-align: right">{{number_format($batch->quantity,2)}} {{ $batch->inventory->units }}{{ $batch->quantity == 1 ? "" : "s" }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p class="heading">Material Usage</p>
    <table class="summary">
        <!-- <thead>
            <tr>
                <th class="shade">Product</th>
                <th class="shade" style="text-align: right">Quantity</th>
            </tr>
        </thead> -->
        <tbody>
            @foreach($production->usages as $usage)
            <tr>
                <td style="text-transform: none">{{$usage->material->name}}</td>
                <td style="text-align: right">{{number_format($usage->quantity,2)}}  {{ $usage->material->units }}{{ $usage->quantity == 1 ? "" : "s" }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if ($production->damages->count() > 0)
      <p class="heading">Damages</p>
    <table class="summary">
        <!-- <thead>
            <tr>
                <th class="shade">Product</th>
                <th class="shade" style="text-align: right">Quantity</th>
            </tr>
        </thead> -->
        <tbody>
            @foreach($production->damages as $damage)
            <tr>
                <td style="text-transform: none">{{$damage->inventory->name}}</td>
                <td style="text-align: right">{{number_format($damage->quantity,2)}} {{ $damage->inventory->units }}{{ $damage->quantity == 1 ? "" : "s" }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
  

    <p class="heading">Stock Remaining</p>
    <table class="summary">
        <!-- <thead>
            <tr>
                <th class="shade">Product</th>
                <th class="shade" style="text-align: right">Quantity</th>
            </tr>
        </thead> -->
        <tbody>
            @foreach(json_decode($production->closing_stock) as $material)
            <tr>
                <td style="text-transform: none">{{$material->name}}</td>
                <td style="text-align: right">{{number_format($material->quantity,2)}} {{ $material->units }}{{ $material->quantity == 1 ? "" : "s" }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 24px">
        <div>Recorded By</div>
        <div class="font-bold">{{$production->user->fullName()}}</div>
    </div>

</body>

</html>