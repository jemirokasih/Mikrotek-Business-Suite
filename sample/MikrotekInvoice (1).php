<!DOCTYPE html>
<html lang="<?php _trans('cldr'); ?>">
<head>
    <meta charset="utf-8">
    <title><?php _trans('invoice'); ?></title>
    <link rel="stylesheet"
          href="<?php echo base_url(); ?>assets/<?php echo get_setting('system_theme', 'invoiceplane'); ?>/css/templates.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/core/css/custom-pdf.css">
</head>
<body>
<div class="boxclient">
    To:
    <h1><?php _htmlsc($invoice->client_name); ?></h1>
    <p>
        <?php
        if ($invoice->client_address_1) {
            echo htmlsc($invoice->client_address_1) . '<br>';
        }
        if ($invoice->client_address_2) {
            echo htmlsc($invoice->client_address_2) . '<br>';
        }

        $city_state = array_filter([$invoice->client_city, $invoice->client_state]);
        if (!empty($city_state)) {
            echo htmlsc(implode(', ', $city_state)) . '<br>';
        }

        $country_zip = array_filter([
            $invoice->client_country ? get_country_name(trans('cldr'), $invoice->client_country) : null,
            $invoice->client_zip,
        ]);
        if (!empty($country_zip)) {
            echo htmlsc(implode(' - ', $country_zip)) . '<br>';
        }

        if (!empty($custom_fields['invoice']['UP'])) {
            echo 'UP: Mr/s. ' . htmlsc($custom_fields['invoice']['UP']) . '<br>';
        }
        ?>
    </p>
</div>

<div class="boxdetail">
    <table>
        <tr>
            <td class="titletable"><?php echo ($invoice->is_proforma == 1 ? 'Proforma Invoice Number' : 'Invoice Number'); ?></td>
            <td class="desctable"><?php _htmlsc($invoice->invoice_number); ?></td>
        </tr>
        <tr>
            <td class="titletable"><?php echo ($invoice->is_proforma == 1 ? 'Proforma Invoice Date' : 'Invoice Date'); ?></td>
            <td class="desctable"><?php echo date_from_mysql($invoice->invoice_date_created, true); ?></td>
        </tr>
        <tr>
            <td class="titletable">Due Date</td>
            <td class="desctable"><?php echo date_from_mysql($invoice->invoice_date_due, true); ?></td>
        </tr>
        <?php if (!empty($invoice->invoice_reference_number)) : ?>
            <tr>
                <td class="titletable">Ref. Number</td>
                <td class="desctable"><?php _htmlsc($invoice->invoice_reference_number); ?></td>
            </tr>
        <?php endif; ?>
        <?php if (!empty($custom_fields['invoice']['PO or Contract Number'])) : ?>    
            <tr>
                <td class="titletable">PO / Contract No.</td>
                <td class="desctable"><?php _htmlsc($custom_fields['invoice']['PO or Contract Number']); ?></td>
            </tr>
        <?php endif; ?>   
    </table>
</div>

<div style="width:100%; text-align:center;">
    <?php if (!empty($custom_fields['invoice']['Subject'])) : ?>
        <b>Subject:</b> <?php _htmlsc($custom_fields['invoice']['Subject']); ?><br><br>
    <?php else : ?>
        <br><br>
    <?php endif; ?>
</div>
<main style="width:100%">
    <table class="item-table">
        <thead>
        <tr>
            <th class="item-name"><?php _trans('item'); ?></th>
            
            <th class="item-amount text-right"><?php _trans('qty'); ?></th>
            <th class="item-price text-right">
                <?php _trans('price'); 
                    if ($custom_fields['invoice']['Currency']) :
                        echo "(".$custom_fields['invoice']['Currency'].")";
                    else:
                        echo "(Rp)";
                    endif;
                ?>
                

                
   
            </th>
            <?php if ($show_item_discounts) : ?>
                <th class="item-discount text-right">
                    <?php _trans('discount'); 
                        if ($custom_fields['invoice']['Currency']) :
                            echo "(".$custom_fields['invoice']['Currency'].")";
                        else:
                            echo "(Rp)";
                        endif;
                    ?>
                </th>
            <?php endif; ?>
            <th class="item-total text-right">
                <?php _trans('total'); 
                    if ($custom_fields['invoice']['Currency']) :
                        echo "(".$custom_fields['invoice']['Currency'].")";
                    else:
                        echo "(Rp)";
                    endif;
                ?>
            </th>
        </tr>
        </thead>
        <tbody>

        <?php
        foreach ($items as $item) { ?>
            <tr>
                <td width="50%">
                    <b><?php _htmlsc($item->item_name); ?></b><br>
                    <small><?php echo nl2br(htmlsc($item->item_description)); ?></small>
                </td>
                <!--<td><?php echo nl2br(htmlsc($item->item_description)); ?></td>-->
                <td width="10%" class="text-right">
                    <?php echo format_amount($item->item_quantity); ?>
                    <?php if ($item->item_product_unit) : ?>
                        <br>
                        <small><?php _htmlsc($item->item_product_unit); ?></small>
                    <?php endif; ?>
                </td>
                <td class="text-right">
                    <?php 
                        

                        echo format_currency($item->item_price); 
                    ?>
                </td>
                <?php if ($show_item_discounts) : ?>
                    <td class="text-right">
                        <?php 
                            
                            echo format_currency($item->item_discount); 
                        ?>
                    </td>
                <?php endif; ?>
                <td class="text-right">
                    <?php
                        
                        echo format_currency($item->item_total); 
                    ?>
                </td>
            </tr>
        <?php } ?>

        </tbody>
        <tbody class="invoice-sums">

        <tr>
            <td <?php echo($show_item_discounts ? 'colspan="4"' : 'colspan="3"'); ?> class="text-right">
                <?php _trans('subtotal'); ?>
            </td>
            <td class="text-right"><?php echo format_currency($invoice->invoice_item_subtotal); ?></td>
        </tr>

        <?php if ($invoice->invoice_item_tax_total > 0) { ?>
            <tr>
                <td <?php echo($show_item_discounts ? 'colspan="4"' : 'colspan="3"'); ?> class="text-right">
                    <?php _trans('item_tax'); ?>
                </td>
                <td class="text-right">
                    <?php echo format_currency($invoice->invoice_item_tax_total); ?>
                </td>
            </tr>
        <?php } ?>

        <?php foreach ($invoice_tax_rates as $invoice_tax_rate) : ?>
            <tr>
                <td <?php echo($show_item_discounts ? 'colspan="4"' : 'colspan="3"'); ?> class="text-right">
                    <?php echo htmlsc($invoice_tax_rate->invoice_tax_rate_name) . ' (' . format_amount($invoice_tax_rate->invoice_tax_rate_percent) . '%)'; ?>
                </td>
                <td class="text-right">
                    <?php echo format_currency($invoice_tax_rate->invoice_tax_rate_amount); ?>
                </td>
            </tr>
        <?php endforeach ?>

        <?php if ($invoice->invoice_discount_percent != '0.00') : ?>
            <tr>
                <td <?php echo($show_item_discounts ? 'colspan="4"' : 'colspan="3"'); ?> class="text-right">
                    <?php _trans('discount'); ?>
                </td>
                <td class="text-right">
                    <?php echo format_amount($invoice->invoice_discount_percent); ?>%
                </td>
            </tr>
        <?php endif; ?>
        <?php if ($invoice->invoice_discount_amount != '0.00') : ?>
            <tr>
                <td <?php echo($show_item_discounts ? 'colspan="4"' : 'colspan="3"'); ?> class="text-right">
                    <?php _trans('discount'); ?>
                </td>
                <td class="text-right">
                    <?php echo format_currency($invoice->invoice_discount_amount); ?>
                </td>
            </tr>
        <?php endif; ?>

        <tr>
            <td <?php echo($show_item_discounts ? 'colspan="4"' : 'colspan="3"'); ?> class="text-right">
                <b><?php _trans('total'); ?></b>
            </td>
            <td class="text-right">
                <b><?php echo format_currency($invoice->invoice_total); ?></b>
            </td>
        </tr>
        <!--
        <tr>
            <td <?php echo($show_item_discounts ? 'colspan="4"' : 'colspan="3"'); ?> class="text-right">
                <?php _trans('paid'); ?>
            </td>
            <td class="text-right">
                <?php echo format_currency($invoice->invoice_paid); ?>
            </td>
        </tr>
        <tr>
            <td <?php echo($show_item_discounts ? 'colspan="4"' : 'colspan="3"'); ?> class="text-right">
                <b><?php _trans('balance'); ?></b>
            </td>
            <td class="text-right">
                <b><?php echo format_currency($invoice->invoice_balance); ?></b>
            </td>
        </tr>-->
        </tbody>
    </table>
</main>
<div>
    <?php echo "<b><i>In Words: " . $custom_fields['invoice']['In Words'] . "</i></b><br><br>"?>
</div>
<div class="boxnotes">
    <h1>Notes:<h1>
    <!-- Cek Jasa Atau Barang -->

    <?php if ($invoice->notes) : ?>
        <?php echo nl2br(htmlsc($invoice->notes)); ?>
    <?php else: ?>
        -
    <?php endif; ?>

</div>

<?php   if ($custom_fields['invoice']['Personal Bank Account']) : ?>
    <table class="boxrek" style="text-align:center">
    <tr>
        <td >
            Bank BCA<br>
            No. 5465248596<br>
            Jemiro Kasih<br>
            KCP Depok Taman Melati
            </td>
        <!--<td >
            Bank OCBC NISP<br>
            No. 722810034123<br>
            Jemiro Kasih<br>
            Cabang Plaza Permata
            </td>-->
        <!--<td >
            Bank BSI<br>
            No. 7203253127<br>
            Jemiro Kasih<br>
            Cabang KC Kalibata Jakarta
            </td>-->
    </tr>
    </table>
<?php   else: ?>
    <table class="boxrek">
    <tr>
        <td >
            Bank BCA<br>
            No. 5780784204<br>
            PT Mikrotek Zemiro Indonesia<br>
            Cabang Ruko Kalimas</p>
            </td>
        <td >
            Bank OCBC NISP<br>
            No. 722800012345<br>
            PT Mikrotek Zemiro Indonesia<br>
            Cabang Plaza Permata
            </td>
        <!--<td >
            Bank BSI<br>
            No. 7203688727<br>
            PT Mikrotek Zemiro Indonesia<br>
            Cabang KC Kalibata Jakarta
            </td>-->
    </tr>
    </table>
<?php   endif; ?>

    <table class="boxsign">
        <tr>
            <td style="width:70%;">
                Warm Regards,<br>
                <?php   if ($custom_fields['invoice']['Remove Digital Sign']) : ?>
                    <br><br><br><br><br><br><br>
                    Jemiro Kasih, S.T., M.M.S.I.<br>
                    Director<br>
                    &nbsp;
                <?php   else: ?>
                    <img style="max-width:200px; margin-top:-20px;margin-bottom:-50px;" src="<?php echo base_url()?>/uploads/digital-sign.png">
                    <br>
                    Jemiro Kasih, S.T., M.M.S.I.<br>
                    Director<br>
                    &nbsp;
                <?php   endif; ?>
            </td>

            <td >
                
            </td>
        </tr>
    </table>
</body>
</html>