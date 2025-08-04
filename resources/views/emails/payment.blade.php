<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> Thông tin đơn hàng </title>
<meta name="robots" content="noindex,nofollow" />
<meta name="viewport" content="width=device-width; initial-scale=1.0;" />

<style type="text/css">
    @import url(https://fonts.googleapis.com/css?family=Open+Sans:400,700);

    body {
        margin: 0;
        padding: 0;
        background: #e1e1e1;
    }

    div,
    p,
    a,
    li,
    td {
        -webkit-text-size-adjust: none;
    }

    .ReadMsgBody {
        width: 100%;
        background-color: #ffffff;
    }

    .ExternalClass {
        width: 100%;
        background-color: #ffffff;
    }

    body {
        width: 100%;
        height: 100%;
        background-color: #e1e1e1;
        margin: 0;
        padding: 0;
        -webkit-font-smoothing: antialiased;
    }

    html {
        width: 100%;
    }

    p {
        padding: 0 !important;
        margin-top: 0 !important;
        margin-right: 0 !important;
        margin-bottom: 0 !important;
        margin-left: 0 !important;
    }

    .visibleMobile {
        display: none;
    }

    .hiddenMobile {
        display: block;
    }

    @media only screen and (max-width: 600px) {
        body {
            width: auto !important;
        }

        table[class=fullTable] {
            width: 96% !important;
            clear: both;
        }

        table[class=fullPadding] {
            width: 85% !important;
            clear: both;
        }

        table[class=col] {
            width: 45% !important;
        }

        .erase {
            display: none;
        }
    }

    @media only screen and (max-width: 420px) {
        table[class=fullTable] {
            width: 100% !important;
            clear: both;
        }

        table[class=fullPadding] {
            width: 85% !important;
            clear: both;
        }

        table[class=col] {
            width: 100% !important;
            clear: both;
        }

        table[class=col] td {
            text-align: left !important;
        }

        .erase {
            display: none;
            font-size: 0;
            max-height: 0;
            line-height: 0;
            padding: 0;
        }

        .visibleMobile {
            display: block !important;
        }

        .hiddenMobile {
            display: none !important;
        }
    }
</style>


<table align="center" bgcolor="#dcf0f8" border="0" cellpadding="0" cellspacing="0"
    style="margin:0;padding:0;background-color:#f2f2f2;width:100%important;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px"
    width="100%">
    <tbody>
        <tr>
            <td align="center"
                style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal"
                valign="top">
                <table border="0" cellpadding="0" cellspacing="0" style="margin-top:15px" width="600">
                    <tbody>
                        <tr>
                            <td align="center" valign="bottom">
                                <table cellpadding="0" cellspacing="0"
                                    style="border-bottom:3px solid #0d0d0d;padding-bottom:10px;background-color:#fff"
                                    width="100%">
                                    <tbody>
                                        <tr>
                                            <td bgcolor="#FFFFFF" style="padding: 0px; text-align: center;"
                                                valign="top" width="100%"><img src="{{ url($config['logo_email']) }}"
                                                    style="width: 150px; height: 115px; object-fit:contain" /> &nbsp;
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr style="background:#fff">
                            <td align="left" height="auto" style="padding:15px" width="600">
                                <table width="100%">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                    <tbody>
                                                        <tr>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <h1
                                                    style="font-size:17px;font-weight:bold;color:#444;padding:0 0 5px 0;margin:0">
                                                    Cảm ơn&nbsp;{{ ucfirst(@$data['name']) }} đã đặt hàng trên DMC&nbsp;
                                                </h1>

                                                <p
                                                    style="margin: 4px 0px; font-family: Arial, Helvetica, sans-serif; color: rgb(68, 68, 68); line-height: 18px;">
                                                    Thông tin đơn hàng của bạn</p>
                                                <h3
                                                    style="font-size: 13px; font-weight: bold; text-transform: uppercase; margin: 20px 0px 0px; border-bottom: 1px solid rgb(221, 221, 221);">
                                                    <font color="#00849d">Thông tin đơn hàng</font><span
                                                        style="color: rgb(243, 156, 18);">&nbsp;{{ $data['order'] }}</span>
                                                    <span
                                                        style="color: rgb(119, 119, 119); font-size: 12px; text-transform: none; font-weight: normal;">{{ date('H:i:s d/m/Y', strtotime($data['dataOrder']['created_at'])) }}</span>
                                                </h3>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td
                                                style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px">
                                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th align="left"
                                                                style="padding:6px 9px 0px 9px;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;font-weight:bold"
                                                                width="50%">Sản phẩm:</th>
                                                            <th 
                                                                align="center"
                                                                style="padding:6px 9px 0px 9px;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;font-weight:bold">
                                                                Số lượng
                                                            </th>
                                                            <th
                                                                align="right"
                                                                style="padding:6px 9px 0px 9px;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;font-weight:bold">
                                                                Tạm tính</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($data['order_items'] as $item)
                                                            <tr>
                                                                <td style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #333;  line-height: 18px;  vertical-align: top; padding:10px 0;"
                                                                    class="article">
                                                                    {{ @$item->product->name }}
                                                                    <br />
                                                                    @php
                                                                        $options = json_decode($item->options, true);
                                                                        $sizeLabel = $options['size']['label'];
                                                                        $colorLabel = $options['color']['label'];
                                                                    @endphp
                                                                    <span style="font-size: 10px">Size:
                                                                        {{ $sizeLabel }}, Màu sắc:
                                                                        {{ $colorLabel }}</span>
                                                                </td>
                                                                <td style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #646a6e;  line-height: 18px;  vertical-align: top; padding:10px 0;"
                                                                    align="center">{{ @$item->quantity }}</td>
                                                                <td style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #1e2b33;  line-height: 18px;  vertical-align: top; padding:10px 0;"
                                                                    align="right">
                                                                    {{ number_format(@$item->total, 0, ',', '.') }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <table width="100%" border="0" cellpadding="0" cellspacing="0"
                                                    align="right" class="fullPadding">
                                                    <tbody>
                                                        <tr>
                                                            <td
                                                                style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #646a6e; line-height: 22px; vertical-align: top; text-align:right; ">
                                                                Tạm tính
                                                            </td>
                                                            <td style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #646a6e; line-height: 22px; vertical-align: top; text-align:right; white-space:nowrap;"
                                                                width="80">
                                                                {!! number_format(@$data['dataOrder']['total'], 0, ',', '.') !!}

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td
                                                                style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #646a6e; line-height: 22px; vertical-align: top; text-align:right; ">
                                                                Phí ship
                                                            </td>
                                                            <td
                                                                style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #646a6e; line-height: 22px; vertical-align: top; text-align:right; ">
                                                                {!! number_format(@$data['dataOrder']['total_shipping'], 0, ',', '.') !!}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td
                                                                style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #646a6e; line-height: 22px; vertical-align: top; text-align:right; ">
                                                                Giảm giá
                                                            </td>
                                                            <td
                                                                style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #646a6e; line-height: 22px; vertical-align: top; text-align:right; ">
                                                                {!! number_format(@$data['dataOrder']['total_discount'], 0, ',', '.') !!}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td
                                                                style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #000; line-height: 22px; vertical-align: top; text-align:right; ">
                                                                <strong>Thành tiền:</strong>
                                                            </td>
                                                            <td
                                                                style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #000; line-height: 22px; vertical-align: top; text-align:right; ">
                                                                <strong> {!! number_format(@$data['dataOrder']['total_price'], 0, ',', '.')  !!}
                                                                </strong>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td
                                                style="font-size: 11px; font-family: 'Open Sans', sans-serif; color: #5b5b5b; line-height: 1; vertical-align: top; ">
                                                <strong>Thông tin hoá đơn</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="100%" height="10"></td>
                                        </tr>
                                        <tr>
                                            <td
                                                style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #5b5b5b; line-height: 20px; vertical-align: top;">
                                                {!! $data['dataOrder']['address'] !!}
                                                @if (isset($ward))
                                                    - {!! @$data['ward']->name !!}
                                                @endif
                                                @if (isset($district))
                                                    - {!! @$data['district']->name !!}
                                                @endif
                                                @if (isset($province))
                                                    - {!! @$data['province']->name !!}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr class="visibleMobile">
                                            <td height="20"></td>
                                        </tr>
                                        <tr>
                                            <td
                                                style="font-size: 11px; font-family: 'Open Sans', sans-serif; color: #5b5b5b; line-height: 1; vertical-align: top; ">
                                                <strong>Phương thức thanh toán</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="100%" height="10"></td>
                                        </tr>

                                        <tr>
                                            <td
                                                style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #5b5b5b; line-height: 20px; vertical-align: top; ">
                                                @php
                                                    if ($data['dataOrder']['payment_method'] == 'cod') {
                                                        echo 'Thanh toán khi nhận hàng';
                                                    } elseif ($data['dataOrder']['payment_method'] == 'payoo') {
                                                        echo 'Thanh toán qua cổng thanh toán Payoo';
                                                    }
                                                @endphp
                                            </td>

                                        </tr>

                                    </tbody>
                                </table>
                            </td>
                        </tr>

                    </tbody>
                </table>

            </td>

        </tr>

    </tbody>
</table>
