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
            src: url({{storage_path() . "/fonts/Exo2-Bold.ttf"}}) format("ttf");
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
    <img style="width: 100%" src="{{storage_path() . "/images/banner.png"}}" alt="">
    <div style="padding: 0 20px">
        <div style="margin: 30px 0">
            <div style="float: right">
                <div style=" margin-left: 12px; padding:0; text-transform: capitalize">
                    {{$date}}
                </div>
            </div>
            <div style="font-size: 25px; font-weight: normal; margin-top:0px">Supplier Voucher: <span
                    style="color:red; font-size: 25px; font-weight: normal; ">#{{$code}}</span></div>
            {{-- <div>{{ $supplier_voucher->site?->name }} Site Record</div> --}}


        </div>


        <p class="heading">General Details</p>
        <table class="details">

            <tr>
                <td class="">Name:</td>
                <td class="" colspan="3">{{$supplier_voucher->contact->name}}</td>
            </tr>
            @if($supplier_voucher->contact->phone_number != null)
                <tr>
                    <td class="">Phone Number:</td>
                    <td class="">{{$supplier_voucher->contact->phone_number}}</td>

                </tr>
            @endif
            @if($supplier_voucher->contact->email != null)
                <tr>
                    <td class="">Email:</td>
                    <td class="" style="text-transform: lowercase">{{$supplier_voucher->contact->email}}</td>
                </tr>
            @endif
            <tr>
                <td class="">Site:</td>
                <td class="">{{$supplier_voucher->site?->name}}</td>
            </tr>

        </table>

        <p class="heading">Summary</p>
        <table class="summary">
            <thead>
                <tr>
                    <th class="shade">Details</th>
                    <th style="text-align: center" class="shade text-center">Quantity</th>
                    <th style="text-align: right" class="shade text-right">Unit Cost</th>
                    <th class="shade" style="text-align: right">Amount</th>
                </tr>
            </thead>
            <tbody>

                <tr>
                     <td style="text-transform: none">{{$supplier_voucher->details}}</td>
                    <td style="text-transform: none; text-align: center">{{$supplier_voucher->readable_quantity}}</td>
                    <td style="text-transform: none; text-align: right">{{number_format($supplier_voucher->unit_cost)}}</td>
                    <td style="text-align: right">{{number_format($supplier_voucher->amount, 2)}}</td>

                </tr>

                {{-- <tr class="total">
                    <td colspan="1">Total</td>
                    <td>{{number_format($supplier_voucher->amount,2)}}</td>
                </tr> --}}
                <tr>
                    <td colspan="4" class="total-in-words">
                        {{$total_in_words}} Only
                    </td>
                </tr>
            </tbody>
        </table>



    </div>

    <div style="page-break-after: always"></div>
    <img style="width: 100%" src="{{storage_path() . "/images/our-products.jpg"}}" alt="">


</body>

</html>