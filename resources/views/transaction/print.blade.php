<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <title>E-CONSUMABLE:: Print Invoice</title>

    <style>
        @media print {
            .page-break {
                display: block;
                page-break-before: always;
            }
        }

        body {
            font-weight: bold;
            font-family: "Calibri", sans-serif;
        }

        #invoice {
            padding: 2mm;
            margin: 0 auto;
            width: 58mm;
            background: #FFF;
        }

        #invoice ::selection {
            background: #f31544;
            color: #FFF;
        }

        #invoice ::moz-selection {
            background: #f31544;
            color: #FFF;
        }

        #invoice h1 {
            font-size: 1.5em;
            /* color: #222; */
        }

        #invoice h2 {
            line-height: 107%;
            font-size: 11.0pt;
        }

        #invoice h3 {
            font-size: 1.2em;
            font-weight: 300;
            line-height: 2em;
        }

        #invoice p {
            line-height: 107%;
            font-size: 11.0pt;
            font-family: "Calibri", sans-serif;
        }

        #invoice #top,
        #invoice #mid,
        #invoice #bot {

            margin-top: 5mm;
            /* Targets all id with 'col-' */
            /* border-bottom: 1px solid #EEE; */
        }

        #invoice #top {
            min-height: 50px;
        }

        #invoice #mid {
            min-height: 80px;
        }

        #invoice #bot {
            min-height: 50px;
        }


        #invoice .info {
            display: block;
            margin-left: 0;
            font-size: 11.0pt;
        }

        #invoice .title {
            float: right;
        }

        #invoice .title p {
            text-align: right;
        }

        #invoice table {
            width: 100%;
            border-collapse: collapse;
        }

        #invoice .tabletitle {
            font-size: 10.0pt;
            /* background: #EEE; */
        }

        #invoice .service {
            border-spacing: 5em;
            /* border-bottom: 1px solid #EEE; */
        }

        #invoice .item {
            width: 40mm;
            font-size: 10.0pt;
        }

        #invoice .itemtext {
            font-size: 10.0pt;
        }

        #invoice #legalcopy {
            margin-top: 5mm;
        }

        .tableitem {
            font-size: 10.0pt;
        }

        #legalcopy p {
            font-size: 10.0pt;
        }
    </style>

    <script>
        window.console = window.console || function(t) {};
    </script>



    <script>
        if (document.location.search.match(/type=embed/gi)) {
            window.parent.postMessage("resize", "*");
        }
    </script>


</head>

<body translate="no">
    @foreach ($orders as $index => $order)
        <div id="invoice">

            <center id="top">
                <div class="info">
                    ELMR<br>
                    PT Dharma Polimetal Tbk
                </div><!--End Info-->
            </center><!--End InvoiceTop-->

            <div id="mid">
                <div class="info">
                    <p>
                        Document : {{ $order->noOrder }}</br>
                        By &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;:
                        {{ $order->user->name }}
                        </br>
                        NPK &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;:
                        {{ $order->user->npk }}</br>
                        Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                        {{ $order->mstrApprs[$index]->created_at }}</br>
                        No Pro &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                        {{ $order->mstrApprs[$index]->sapFails[0]->matdoc_gi }}</br>
                    </p>
                </div>
            </div><!--End Invoice Mid-->

            <div id="bot">

                <div id="table">
                    <table>

                        <tr class="tabletitle">
                            <td class="item"></td>
                            <td class="qty">Qty</td>
                            <td class="uom">UOM</td>

                        </tr>
                        @foreach ($order->mstrApprs as $appr)
                            <tr class="service">
                                <td class="tableitem" style="padding-right:10px;">{{ $appr->consumable->Cb_desc }}</td>
                                <td class="tableitem">{{ $appr->jumlah }}</td>
                                <td class="tableitem">{{ $appr->consumable->Cb_type }}</td>

                            </tr>
                        @endforeach



                    </table>
                </div><!--End Table-->
                <center style="margin-top:15px">
                    <img src="data:image/png;base64,{!! DNS2D::getBarcodePNG($order->noOrder, 'QRCODE', 2, 2) !!}" alt="QR Code">
                </center>
                <div id="legalcopy">
                    <p class="legal">
                        Document for : <strong>{{ $order->mstrApprs[$index]->lineFrom }}</strong><br>
                        Approved By &nbsp; : <strong>{{ $order->mstrApprs[$index]->dept->name }}</strong><br>
                        Print Count &nbsp;&nbsp;&nbsp;&nbsp; : <strong>
                            {{ $index + 1 }}
                        </strong>
                    </p>
                </div>

            </div><!--End InvoiceBot-->
        </div><!--End Invoice-->
    @endforeach


    <script src="http://e-lmr.dharmap.com/template/assets/js/app-invoice-print.js"></script>
</body>

</html>
