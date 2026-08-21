<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kwitansi <?php echo html_escape($receipt->receipt_number); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #222;
            line-height: 1.5;
        }
        .kwitansi-box {
            border: 2px solid #000;
            padding: 20px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 13px;
        }
        table.details {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.details td {
            padding: 8px 4px;
            vertical-align: top;
        }
        .terbilang-box {
            background-color: #f0f0f0;
            border: 1px dashed #666;
            padding: 8px 12px;
            font-style: italic;
            font-weight: bold;
        }
        .amount-box {
            font-size: 18px;
            font-weight: bold;
            border: 2px solid #000;
            padding: 6px 15px;
            display: inline-block;
            background: #eef9f1;
        }
        .footer-table {
            width: 100%;
            margin-top: 30px;
        }
        .footer-table td {
            vertical-align: bottom;
        }
        .signature-box {
            text-align: center;
            width: 220px;
            float: right;
        }
        .signature-space {
            height: 60px;
        }
    </style>
</head>
<body>

<div class="kwitansi-box">
    <div class="header">
        <h1>KWITANSI PEMBAYARAN</h1>
        <p>No: <strong><?php echo html_escape($receipt->receipt_number); ?></strong></p>
    </div>

    <table class="details">
        <tr>
            <td style="width: 25%;"><strong>Telah Diterima Dari</strong></td>
            <td style="width: 3%;">:</td>
            <td style="width: 72%;"><strong><?php echo html_escape($receipt->client_name); ?></strong></td>
        </tr>
        <tr>
            <td><strong>Uang Sejumlah</strong></td>
            <td>:</td>
            <td>
                <div class="terbilang-box">
                    # <?php echo $terbilang; ?> #
                </div>
            </td>
        </tr>
        <tr>
            <td><strong>Untuk Pembayaran</strong></td>
            <td>:</td>
            <td>
                <?php echo nl2br(html_escape($receipt->receipt_notes ?: '-')); ?>
                <?php if ($receipt->invoice_number) : ?>
                    <br><span style="font-size: 11px; color: #555;">(Faktur Terkait: #<?php echo html_escape($receipt->invoice_number); ?>)</span>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td><strong>Metode Pembayaran</strong></td>
            <td>:</td>
            <td><?php echo html_escape($receipt->payment_method_name ?: 'Tunai / Transfer'); ?></td>
        </tr>
    </table>

    <table class="footer-table">
        <tr>
            <td style="width: 50%;">
                <p style="margin-bottom: 5px;">Terbilang Nominal:</p>
                <div class="amount-box">
                    <?php echo format_currency($receipt->receipt_amount); ?>
                </div>
            </td>
            <td style="width: 50%;">
                <div class="signature-box">
                    <p style="margin: 0;"><?php echo html_escape($receipt->company_city ?: 'Jakarta'); ?>, <?php echo date_from_mysql($receipt->receipt_date); ?></p>
                    <p style="margin: 2px 0 0 0; font-weight: bold;"><?php echo html_escape($receipt->company_name ?: get_setting('custom_title')); ?></p>
                    <div class="signature-space"></div>
                    <p style="margin: 0; text-decoration: underline; font-weight: bold;">
                        ( <?php echo html_escape($receipt->user_name ?: 'Kasir / Manager'); ?> )
                    </p>
                </div>
            </td>
        </tr>
    </table>
</div>

</body>
</html>
