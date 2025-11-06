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

        table.summary td, table.summary th {
            border: 1px solid;
            padding: 8px;
            text-align: left;
        }

        table.summary th {
            background-color: rgb(217, 217, 217);
            text-transform: none;
        }

        table.summary .total td{
            font-weight: bold;
            text-align: right;
        }
        table.summary .total-in-words{
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
        <div style="font-size: 25px; font-weight: normal; margin-top:0px">Credit Voucher: <span
                style="color:red; font-size: 25px; font-weight: normal; ">#{{$code}}</span></div>
        <div>Delivery #: {{$credit_voucher->delivery->formattedCode()}}</div>


    </div>


    <p class="heading">General Details</p>
    <table class="details">

        <tr>
            <td class="">Name:</td>
            <td class="" colspan="3">{{$credit_voucher->contact->name}}</td>
        </tr>
        @if($credit_voucher->contact->phone_number != null)
            <tr>
                <td class="">Phone Number:</td>
                <td class="">{{$credit_voucher->contact->phone_number}}</td>

            </tr>
        @endif
        @if($credit_voucher->contact->email != null)
            <tr>
                <td class="">Email:</td>
                <td class="" style="text-transform: lowercase">{{$credit_voucher->contact->email}}</td>
            </tr>
        @endif
        <tr>
            <td class="">Site Location:</td>
            <td class="">{{$credit_voucher->sale?->location}}</td>
        </tr> 

    </table>

    <p class="heading">Summary</p>
    <table class="summary">
        <thead>
        <tr>
            <th class="shade">Details</th>
            {{-- <th class="shade">Location</th> --}}
            <th class="shade" style="text-align: right">Amount</th>
        </tr>
        </thead>
        <tbody>
      
            <tr>
                <td style="text-transform: none">{{$credit_voucher->requestFormItem->product_name}}</td>
                {{-- <td style="text-transform: none">{{$credit_voucher->delivery->location}}</td> --}}
                <td style="text-align: right">{{number_format($credit_voucher->amount,2)}}</td>
            </tr>
     
        {{-- <tr class="total">
            <td colspan="1">Total</td>
            <td>{{number_format($credit_voucher->amount,2)}}</td>
        </tr> --}}
        <tr>
            <td colspan="2" class="total-in-words">
                {{$total_in_words}} Only
            </td>
        </tr>
        </tbody>
    </table>



</div>

<div style="page-break-after: always"></div>
<img style="width: 100%" src="{{storage_path()."/images/our-products.jpg"}}" alt="">


</body>
</html>
