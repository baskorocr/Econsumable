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
                margin: 0;
                padding: 0;
            }
        }

        body {
            font-weight: bold;
            font-family: "Calibri", sans-serif;
        }

        #invoice {
            padding: 1mm;
            margin: 0 auto;
            width: 58mm;
            background: #FFF;
        }

        #invoice h1 {
            font-size: 1.3em;
        }

        #invoice h2 {
            font-size: 10.0pt;
            line-height: 107%;
        }

        #invoice p {
            font-size: 9.5pt;
            line-height: 107%;
        }

        #invoice .info {
            display: block;
            margin-left: 0;
            font-size: 10.0pt;
        }

        #invoice table {
            width: 100%;
            border-collapse: collapse;
        }

        #invoice .tabletitle {
            font-size: 9.0pt;
            background: #EEE;
        }

        #invoice .service {
            border-bottom: 1px dashed #999;
        }

        #invoice .item {
            width: 35mm;
            font-size: 9.0pt;
        }

        #invoice .itemtext {
            font-size: 9.0pt;
        }

        #invoice #legalcopy {
            margin-top: 4mm;
        }

        .tableitem {
            font-size: 9.0pt;
        }

        #legalcopy p {
            font-size: 9.0pt;
        }
    </style>

    <script>
        window.console = window.console || function(t) {};

        // Auto print when page loads
        window.onload = function() {
            window.print();
        };
    </script>

</head>

<body translate="no">
    @foreach ($orders as $index => $order)
        <div id="invoice">

            <center id="top">
                <div class="info">
                    <h2>E-CONSUMABLE</h2>
                    PT Dharma Polimetal Tbk
                </div>
            </center>

            <div id="mid">
                <div class="info">
                    <p>
                        Document : {{ $order->noOrder }}<br>
                        By &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $order->user->name }}<br>
                        NPK &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $order->user->npk }}<br>
                        Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                        {{ $order->mstrApprs[$index]->created_at }}<br>
                        No Pro &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                        {{ $order->mstrApprs[0]->sapFails[0]->matdoc_gi }}<br>
                    </p>
                </div>
            </div>

            <div id="bot">

                <div id="table">
                    <table>
                        <tr class="tabletitle">
                            <td class="item">
                                <h2>Item</h2>
                            </td>
                            <td class="qty">
                                <h2>Qty</h2>
                            </td>
                            <td class="uom">
                                <h2>UOM</h2>
                            </td>
                        </tr>
                        @foreach ($order->mstrApprs as $appr)
                            <tr class="service">
                                <td class="tableitem" style="padding-right:10px;">{{ $appr->consumable->Cb_desc }}</td>
                                <td class="tableitem">{{ $appr->jumlah }}</td>
                                <td class="tableitem">{{ $appr->consumable->Cb_type }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>

                <center style="margin-top:15px">
                    <img src="data:image/png;base64,{!! DNS2D::getBarcodePNG($order->noOrder, 'QRCODE', 4, 4) !!}" alt="QR Code">
                </center>

                <div id="legalcopy">
                    <p class="legal">
                        Document for: <strong>{{ $order->mstrApprs[$index]->lineFrom }}</strong><br>
                        Approved By: <strong>{{ $order->mstrApprs[$index]->dept->name }}</strong><br>
                        Print Count: <strong>{{ $index + 1 }}</strong>
                    </p>
                </div>

            </div>
        </div>
        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>

</html>
