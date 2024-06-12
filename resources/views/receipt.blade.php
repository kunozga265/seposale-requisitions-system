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
            font-size: 13px;
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
            /*width: 100%;*/
            border-collapse: collapse;
            font-size: 14px;
        }
        td,th{
            border: 1px solid;
            /*padding: 14px 6px;*/
            text-align: left;
        }

        .heading{
            font-family: 'Rubik', sans-serif;
            font-size: 14px;
            padding-bottom: 0px;
            margin-bottom: 0px;
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

        .text-center{
            text-align: center;
        }

        tr td:first-child{
            width: 150px;
            /*background-color: chocolate;*/
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
        <div style="font-size: 25px; font-weight: bold; margin-top:12px">RECEIPT: <span style="color:red; font-size: 25px; font-weight: bold; ">#{{$code}}</span></div>

    </div>

    {{--<div style="text-align: center; font-size: 16px; font-weight: normal">Code: {{$receipt->code}}</div>--}}


    <table>
        <tr >
            <td class="b-0" colspan="2">
                <p class="heading">Customer Details</p>
            </td>
        </tr>
        <tr>
            <td class="b-0">Name:</td>
            <td class="b-0">{{$receipt->client->name}}</td>
        </tr>
        <tr>
            <td class="b-0">Phone Number:</td>
            <td class="b-0">{{$receipt->client->phone_number}}</td>
        </tr>
        <tr>
            <td class="b-0">Email:</td>
            <td class="b-0">{{$receipt->client->email}}</td>
        </tr>
        <tr>
            <td class="b-0">Address:</td>
            <td class="b-0">{{$receipt->client->address}}</td>
        </tr>
        <tr>
            <td class="b-0"></td>
        </tr>

        <tr >
            <td class="b-0" colspan="2">
                <p class="heading">Payment Details</p>
            </td>
        </tr>
        <tr>
            <td class="b-0">Method of Payment:</td>
            <td class="b-0">{{$receipt->paymentMethod->name}}</td>
        </tr>
        @if($receipt->reference != "")
            <tr>
                <td class="b-0">Reference:</td>
                <td class="b-0">{{$receipt->reference}}</td>
            </tr>
        @endif
        <tr>
            <td class="b-0">Invoice Number:</td>
            <td class="b-0">{{(new \App\Http\Controllers\AppController())->getZeroedNumber($receipt->sale->invoice->code)}}</td>
        </tr>
    </table>

    <div class="text-center">
        <div>Amount Received:</div>
        <div style="background-color: black; padding: 12px; margin: 4px 0; color: white; font-size: 22px; font-weight: bold">
            MK{{number_format($receipt->amount,2)}}
        </div>
        <div class="font-bold" style="text-transform: capitalize">
            {{$total_in_words}} Only
        </div>
    </div>

    <div style="margin-top: 24px">
        <div>Yours sincerely,</div>
        <div class="font-bold">{{$receipt->sale->user->fullName()}}</div>
    </div>



</div>


</body>
</html>
