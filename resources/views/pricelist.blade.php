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
            border: none;
            /* padding: 14px 6px; */
            text-align: left;
        }

        th {
            color: #fdc017;
            font-size: 18px
        }

        td {
            color: white;
            font-size: 14px
        }

        table {
            border-spacing: 15px;
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }


        table.details td {
            padding: 2px 8px;
            border: none;

        }

        td.product table {
            padding: 20px 0
        }


        td.product:first-child {
            padding-right: 20px;
        }

        td.product:last-child {
            padding-left: 20px;
        }

        tr.group {
            border-bottom: 1px solid #fdc01748
        }

        tr.group:last-child {
            border-bottom: none
        }

        tr.group:first-child td.product table {
            /* padding-top: 0; */
        }



        tr,
        th,
        td {
            width: 100%;
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
    <div style="position: absolute; left:110px; top: 480px; border: 0px solid yellow; width:480px;">
        <table>
            @foreach ($products as $group)
                <tr class="group">
                    @foreach ($group as $product)

                        <td class="product">
                            <table style=" ">
                                <tr>
                                    <th style="padding-bottom: 12px" colspan="2">{{ $product->name }}</th>
                                </tr>
                                @foreach ($product->variants as $variant)
                                    <tr>
                                        <td>
                                            <span>{{$variant->description}}</span>
                                        </td>
                                        <td style="text-align:right;">K{{number_format($variant->cost)}}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </td>
                    @endforeach

                </tr>
            @endforeach
        </table>
    </div>
    <img style="width: 100%" src="{{storage_path() . "/images/pricelist.jpg"}}" alt="">


</body>

</html>