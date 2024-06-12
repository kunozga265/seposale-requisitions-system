<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        *{
            font-family: 'Inter',sans-serif;
            text-transform: uppercase;
            font-size: 11px;
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

        table{
            margin: 12px 0;
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        td,th{
            border: 1px solid;
            padding: 14px 6px;
            text-align: left;
        }

        .heading{
            font-family: 'Rubik', sans-serif;
            font-size: 12px;
            padding-bottom: 8px;
            font-weight: bold;
        }

        .grid{
            display: flex;
            justify-content: space-between;
            /*grid-template-columns:repeat(2,minmax(0,1fr))*/
        }

        /*    .grid > div{
                width: 50%;
            }*/

        .section{
            width: 340px;
        }

        .flex{
            display: flex;
        }

        .justify-between{
            justify-content: space-between;
        }

        td.spacer{
            padding: 8px;
        }

        td.shade{
            background-color: #f2f2f2;
            /*font-size: 10px;*/
            /*text-transform: none;*/
            min-width: 150px;
        }

        th.shade{
            background-color: rgb(217, 217, 217);

        }

        .b-0{
            border: none;
        }

        .bt-1{
            border-top: 1px solid black;
        }

        .font-bold{
            font-weight: bold;
        }

        .font-bolder{
            font-weight: bolder;
        }


    </style>
</head>
<body>
{{--<p style="text-align: right; font-size: 12px">Generated on {{$date}} at {{$time}}</p>--}}
<img style="width: 100%" src="{{storage_path()."/images/banner.png"}}" alt="">
<div style="padding: 0 20px">
    <div style="margin: 30px 0">
        <div style="float: right">
            <div style="font-size: 11px; margin-left: 12px; border: 1px solid black; padding: 14px 40px; text-transform: uppercase">
                {{$date}}
            </div>
        </div>
        <div style="font-size: 25px; font-weight: bold; margin-top:12px">Quotation: <span style="color:red; font-size: 25px; font-weight: bold; ">#{{$code}}</span></div>

    </div>

    {{--<div style="text-align: center; font-size: 16px; font-weight: normal">Code: {{$quotation->code}}</div>--}}


    <table>
        <p class="heading">Customer Details</p>
        <tr>
            <td class="b-0 bt-1 font-bold">Name</td>
            <td class="b-0 bt-1 shade" colspan="3">{{$quotation->client->name}}</td>
        </tr>
        <tr>
            <td class="b-0 spacer"></td>
            <td class="b-0 spacer"></td>
        </tr>
        <tr>
            <td class="b-0 font-bold">Phone Number</td>
            <td class="b-0 shade">{{$quotation->client->phone_number}}</td>

            <td class="b-0 font-bold">Email</td>
            <td class="b-0 shade" style="text-transform: lowercase">{{$quotation->client->email}}</td>
        </tr>
        <tr>
            <td class="b-0 spacer"></td>
            <td class="b-0 spacer"></td>
        </tr>
        <tr>

        </tr>

        <tr>
            <td class="b-0 font-bold">Address</td>
            <td class="b-0 shade">{{$quotation->client->address}}</td>
            <td class="b-0 font-bold">Location</td>
            <td class="b-0 shade">{{$quotation->location}}</td>
        </tr>
        <tr>
            <td class="b-0 spacer"></td>
            <td class="b-0 spacer"></td>
        </tr>
        <tr>

        </tr>

    </table>


    <table>
        <p class="heading">Products and Services</p>
        <thead>
        <tr>
            <th class="shade">Details</th>
            <th class="shade" style="text-align: center">Units</th>
            <th class="shade" style="text-align: center">Quantity</th>
            <th class="shade" style="text-align: right">Unit Cost</th>
            <th class="shade" style="text-align: right">Total Cost</th>
        </tr>
        </thead>
        <tbody>
        @foreach(json_decode($quotation->information) as $info)
            <tr>
                <td style="text-transform: uppercase">{{$info->details}}</td>
                <td style="text-align: center">{{$info->units ?? ""}}</td>
                <td style="text-align: center">{{number_format($info->quantity,2)}}</td>
                <td style="text-align: right">{{number_format($info->unitCost,2)}}</td>
                <td style="text-align: right">{{number_format($info->totalCost,2)}}</td>
            </tr>
        @endforeach
        <tr>
            <td style="text-align: right; font-weight: bolder" colspan="4">Total</td>
            <td style="text-align: right" >{{number_format($quotation->total,2)}}</td>
        </tr>
        <tr>
            <td colspan="5" style="text-align: center;" class="font-bold">
                {{$total_in_words}} ONLY
            </td>
        </tr>
        </tbody>
    </table>

    <div style="margin-top: 24px">
        <div>PREPARED BY</div>
        <div class="font-bold">{{$quotation->user->fullName()}}</div>
    </div>

    <table>
        <tr class="">
            <td class="b-0" style="width: 45px">
                <img style="width: 40px" src="{{storage_path()."/images/nb.png"}}" alt="">
            </td>
            <td class="b-0">
                <div style="font-size: 8px">National Bank Acc #</div>
                <div style="font-size: 20px; font-weight: normal">1008405545</div>
                <div style="font-size: 8px">Gateway Mall Branch</div>
            </td>
            <td class="b-0" style="width: 45px">
                <img style="width: 40px" src="{{storage_path()."/images/std.png"}}" alt="">
            </td>
            <td class="b-0">
                <div style="font-size: 8px">Standard Bank Acc #</div>
                <div style="font-size: 20px; font-weight: normal">9100006110794</div>
                <div style="font-size: 8px">Gateway Mall Branch</div>
            </td>
        </tr>
    </table>


</div>

<div style="page-break-after: always"></div>
<img style="width: 100%" src="{{storage_path()."/images/our-products.jpg"}}" alt="">

</body>
</html>
