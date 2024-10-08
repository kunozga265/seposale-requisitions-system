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
            font-family: 'Lato Font',sans-serif;
        }

        @font-face {
            font-family: 'Exo Font';
            font-weight: bold;
            src: url("public/fonts/Exo2-Bold.ttf") format("ttf");
        }

        @font-face {
            font-family: 'Lato Font';
            src: url("public/fonts/Lato-Regular.ttf") format("ttf");
        }

        table{
            margin: 12px 0;
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        td,th{
            border: 1px solid;
            padding: 4px;
            text-align: left;
        }

        .heading{
            font-size: 16px;
            padding-bottom: 8px;
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



    </style>
</head>
<body>
<p style="text-align: right; font-size: 12px">Generated on {{$date}} at {{$time}}</p>
<img style="width: 100%" src="{{storage_path()."/images/banner.png"}}" alt="">
<div style="text-align: center; font-size: 25px; font-weight: normal; margin-top:12px">{{$type}}</div>
<div style="text-align: center; font-size: 16px; font-weight: normal">Code: {{$requestForm->code}}</div>


<table>
    <p class="heading">General Details</p>
    <tr>
        <td>Status</td>
        <td>{{$statusMessage}}</td>
    </tr>
    <!-- <tr>
        <td>Request Code</td>
        <td>{{$requestForm->code}}</td>
    </tr> -->
    @if($requestForm->type!="FUEL")
        <tr>
            <td>Person Collecting</td>
            <td>{{$requestForm->personCollectingAdvance}}</td>
        </tr>
        <tr>
            <td>Purpose</td>
            <td>{{$requestForm->purpose}}</td>
        </tr>
    @endif
    <!-- @if($requestForm->type!="VEHICLE_MAINTENANCE")
        <tr>
            <td>Project Name</td>
            <td>{{$requestForm->project?$requestForm->project->name:""}}</td>
        </tr>
    @endif
    @if($requestForm->type!="VEHICLE_MAINTENANCE")
        <tr>
            <td>Project Client</td>
            <td>{{$requestForm->project?$requestForm->project->client:""}}</td>
        </tr>
    @endif
    @if($requestForm->type!="VEHICLE_MAINTENANCE")
        <tr>
            <td>Project Site</td>
            <td>{{$requestForm->project?$requestForm->project->site:""}}</td>
        </tr>
    @endif -->
    @if($requestForm->type=="VEHICLE_MAINTENANCE")
        <tr>
            <td>Assessed By</td>
            <td>{{$requestForm->assessedBy}}</td>
        </tr>
    @endif
    @if($requestForm->type=="VEHICLE_MAINTENANCE" || $requestForm->type=="FUEL")
        <tr>
            <td>Vehicle Registration Number</td>
            <td>{{$requestForm->vehicle->vehicleRegistrationNumber}}</td>
        </tr>
    @endif
    @if($requestForm->type=="FUEL")
        <tr>
            <td>Mileage</td>
            <td>{{number_format($requestForm->mileage,2)}}</td>
        </tr>
    @endif
    @if($requestForm->type=="FUEL")
        <tr>
            <td>Driver Name</td>
            <td>{{$requestForm->driverName}}</td>
        </tr>
    @endif
    @if($requestForm->type=="FUEL")
        <tr>
            <td>Fuel Requested (In Litres)</td>
            <td>{{number_format($requestForm->fuelRequestedLitres,2)}}</td>
        </tr>
    @endif
    @if($requestForm->type=="FUEL")
        <tr>
            <td>Fuel Requested (Money Equivalent)</td>
            <td>{{number_format($requestForm->fuelRequestedMoney,2)}}</td>
        </tr>
    @endif
    @if($requestForm->type=="FUEL")
        <tr>
            <td>Purpose</td>
            <td>{{$requestForm->purpose}}</td>
        </tr>
    @endif
</table>

@if($requestForm->type=="FUEL")
    <table>
        <p class="heading">Last Refill Details</p>
        <tr>
            <td>Date</td>
            <td>{{$requestForm->lastRefillDate?date("j F Y H:i",$requestForm->lastRefillDate):""}}</td>
        </tr>
        <tr>
            <td>Fuel Received</td>
            <td>{{$requestForm->lastRefillFuelReceived?number_format($requestForm->lastRefillFuelReceived,2):""}}</td>
        </tr>
        <tr>
            <td>Mileage Covered</td>
            <td>{{$requestForm->lastRefillMileageCovered?number_format($requestForm->lastRefillMileageCovered,2):""}}</td>
        </tr>
    </table>
@endif

@if($requestForm->type!="FUEL")

    <table>
        <p class="heading">Breakdown</p>
        <thead>
        <tr>
            <th>Details</th>
            @if($requestForm->type=="MATERIALS")
                <th>Units</th>
            @endif
            <th style="text-align: right">Quantity</th>
            <th style="text-align: right">Unit Cost</th>
            <th style="text-align: right">Total Cost</th>
        </tr>
        </thead>
        <tbody>
        @foreach(json_decode($requestForm->information) as $info)
            <tr>
                <td>{{$info->details}}</td>
                @if($requestForm->type=="MATERIALS")
                    <td>{{$info->units ?? ""}}</td>
                @endif
                <td style="text-align: right">{{number_format($info->quantity,2)}}</td>
                <td style="text-align: right">{{number_format($info->unitCost,2)}}</td>
                <td style="text-align: right">{{number_format($info->totalCost,2)}}</td>
            </tr>
        @endforeach
            <tr>
                <td style="border: 0"></td>
                @if($requestForm->type=="MATERIALS")
                    <td style="border: 0"></td>
                @endif
                <td style="text-align: right; border: 0"></td>
                <td style="text-align: right; font-weight: bolder; border: 0">Total</td>
                <td style="text-align: right" >{{number_format($requestForm->total,2)}}</td>
            </tr>
        </tbody>
    </table>
    <!-- <p style="font-size: 12px">I accept the advances listed above and I acknowledge that I must return the full amount or account for it on a company expense form within 3 days of returning to Geoserve from this assignment</p> -->
@endif

<div class="">
    <div style="float: right">
        @if($requestForm->dateInitiated || $requestForm->dateReconciled)
            <div class="section">
                <table>
                    <tr >
                        <td style="background-color: #000000; color: white; border: 1px solid black" colspan="2">
                            Finance
                        </td>
                    </tr>
                    @if($requestForm->dateInitiated)
                        <tr>
                            <td style="font-weight: bold">Initiated Date</td>
                            <td>{{\Carbon\Carbon::createFromTimestamp($requestForm->dateInitiated,'Africa/Lusaka')->format('j F Y H:i')}}</td>
                        </tr>
                    @endif
                    @if($requestForm->dateReconciled)
                        <tr>
                            <td style="font-weight: bold">Reconciled Date</td>
                            <td>{{\Carbon\Carbon::createFromTimestamp($requestForm->dateReconciled,'Africa/Lusaka')->format('j F Y H:i')}}</td>
                        </tr>
                    @endif
                </table>
            </div>
        @endif
    </div>
    <div style="float: left" >
        <div class="section">
            <table>
                <tr >
                    <td style="background-color: #000000; color: white; border: 1px solid black" colspan="2">Requested By</td>
                </tr>
                <tr>
                    <td style="font-weight: bold; width: 60px">Name</td>
                    <td>{{$requestForm->user->firstName}} {{$requestForm->user->middleName}} {{$requestForm->user->lastName}}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold; width: 60px">Position</td>
                    <td>{{$requestForm->user->position->title}}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold; width: 60px">Date</td>
                    <td>{{\Carbon\Carbon::createFromTimestamp($requestForm->dateRequested,'Africa/Lusaka')->format('j F Y H:i')}}</td>
                </tr>
            </table>
        </div>
        @foreach(json_decode($requestForm->stages) as $stage)
            @if($stage->status)
                <div class="section">
                    <table>
                        <tr >
                            <td style="background-color: #000000; color: white; border: 1px solid black" colspan="2">
                                Authorised By
                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold; width: 60px">Name</td>
                            <td>{{$stage->name}}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold; width: 60px">Position</td>
                            <td>{{ $stage->positionTitle }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold; width: 60px">Date</td>
                            <td>{{\Carbon\Carbon::createFromTimestamp($stage->date,'Africa/Lusaka')->format('j F Y H:i')}}</td>
                        </tr>
                    </table>
                </div>
            @endif
        @endforeach
        @if($requestForm->approvalBy)
            <div class="section">
                <table>
                    <tr >
                        <td style="background-color: #000000; color: white; border: 1px solid black" colspan="2">
                            Approved By
                        </td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; width: 60px">Name</td>
                        <td>{{$requestForm->approvalBy->firstName}} {{$requestForm->approvalBy->middleName}} {{$requestForm->approvalBy->lastName}}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; width: 60px">Position</td>
                        <td>{{$requestForm->approvalBy->position->title}}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; width: 60px">Date</td>
                        <td>{{\Carbon\Carbon::createFromTimestamp($requestForm->approvedDate,'Africa/Lusaka')->format('j F Y H:i')}}</td>
                    </tr>
                </table>
            </div>
        @endif
    </div>
</div>


</body>
</html>
