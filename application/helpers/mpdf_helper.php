<?php

if ( ! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/*
 * InvoicePlane
 *
 * @author      InvoicePlane Developers & Contributors
 * @copyright   Copyright (c) 2012 - 2018 InvoicePlane.com
 * @license     https://invoiceplane.com/license.txt
 * @link        https://invoiceplane.com
 *
 * eInvoicing add-ons by Verony
 */
/**
 * Sanitize PDF footer content to prevent remote resource fetching (SSRF) during rendering.
 *
 * @param string|null $footer
 *
 * @return string
 */
function sanitize_pdf_footer_content(?string $footer): string
{
    if (empty($footer)) {
        return '';
    }

    $normalized = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $footer);
    $allowedTags = ['b', 'strong', 'i', 'em', 'u', 'p', 'br', 'small', 'span', 'div', 'img', 'table', 'tr', 'td', 'th', 'tbody', 'thead', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'hr'];
    $allowedAttributes = ['style', 'class', 'src', 'width', 'height', 'align', 'valign', 'border', 'cellpadding', 'cellspacing', 'colspan', 'rowspan'];

    $previousInternalErrors = libxml_use_internal_errors(true);
    $dom                    = new DOMDocument('1.0', 'UTF-8');

    $dom->loadHTML('<?xml encoding="utf-8"?><div id="ip-footer-wrapper">' . $normalized . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

    $cleanNode = static function (DOMNode $node) use (&$cleanNode, $allowedTags, $allowedAttributes): void {
        /** @var DOMNode $child */
        foreach (iterator_to_array($node->childNodes) as $child) {
            if ($child instanceof DOMElement) {
                $tagName = mb_strtolower($child->tagName);

                if ( ! in_array($tagName, $allowedTags, true)) {
                    $node->removeChild($child);
                    continue;
                }

                if ($child->hasAttributes()) {
                    $attributesToRemove = [];
                    foreach ($child->attributes as $attr) {
                        $attrName = mb_strtolower($attr->nodeName);
                        if ($attrName === 'src' && preg_match('/^\s*javascript:/i', $attr->nodeValue)) {
                            $attributesToRemove[] = $attr->nodeName;
                        } elseif ( ! in_array($attrName, $allowedAttributes, true)) {
                            $attributesToRemove[] = $attr->nodeName;
                        }
                    }
                    foreach ($attributesToRemove as $attrName) {
                        $child->removeAttribute($attrName);
                    }
                }

                $cleanNode($child);
            }
        }
    };

    $container = $dom->getElementById('ip-footer-wrapper');

    if ($container !== null) {
        $cleanNode($container);
    }

    $sanitized = '';

    if ($container !== null) {
        foreach ($container->childNodes as $child) {
            $sanitized .= $dom->saveHTML($child);
        }
    }

    libxml_clear_errors();
    libxml_use_internal_errors($previousInternalErrors);

    $trimmed = trim($sanitized);

    return $trimmed === '' ? '' : $trimmed;
}

/**
 * Sanitize PDF header content to prevent remote resource fetching (SSRF) during rendering.
 *
 * @param string|null $header
 *
 * @return string
 */
function sanitize_pdf_header_content(?string $header): string
{
    return sanitize_pdf_footer_content($header);
}

/**
 * Create a PDF.
 *
 * @param      $html
 * @param      $filename
 * @param bool $stream           (show or download)
 * @param bool $embed_xml        (eInvoicing)
 * @param null $associated_files (eInvoicing)
 *
 * @return string
 *
 * @throws \Mpdf\MpdfException
 */
function pdf_create(
    $html,
    string $filename,
    bool $stream = true,
    $password = null,
    $isInvoice = null,
    $is_guest = null,
    bool $embed_xml = false,
    ?array $associated_files = []
) {
    $CI = & get_instance();

    // Get the invoice from the archive if available
    $invoice_array = [];

    // mPDF loading
    $mpdf = new \Mpdf\Mpdf([
        'tempDir' => UPLOADS_TEMP_MPDF_FOLDER,
        'whitelistStreamWrappers' => ['http', 'https'],
    ]);

    // mPDF configuration
    $mpdf->useAdobeCJK      = true;
    $mpdf->autoScriptToLang = true;
    $mpdf->autoVietnamese   = true;
    $mpdf->autoArabic       = true;
    $mpdf->autoLangToFont   = true;

    if (IP_DEBUG) {
        // Enable image error logging
        $mpdf->showImageErrors = true;
    }

    // eInvoicing: Include (embedded) XML if enabled for the client
    if ($embed_xml) {
        $CI->load->helper('e-invoice');
        // mpdf only creates PDF/A-1b files and cannot create the required PDF/A-3b files!
        $mpdf->pdf_version = '1.7';
        $mpdf->PDFA        = true;
        $mpdf->PDFAauto    = true;
        $mpdf->SetAssociatedFiles($associated_files);
        $mpdf->SetAdditionalXmpRdf(include_rdf($associated_files[0]['name']));
    }

    // Set a password if set for the voucher
    if ( ! empty($password)) {
        $mpdf->SetProtection(['copy', 'print'], $password, $password);
    }

    // Check if the archive folder is available
    if ( ! is_dir(UPLOADS_ARCHIVE_FOLDER) || is_link(UPLOADS_ARCHIVE_FOLDER) && ( ! mkdir(UPLOADS_ARCHIVE_FOLDER, '0777') && ! is_dir(UPLOADS_ARCHIVE_FOLDER))) {
        throw new \RuntimeException(sprintf('Directory "%s" was not created', UPLOADS_ARCHIVE_FOLDER));
    }

    $invoiceHeader = sanitize_pdf_header_content(get_setting('pdf_invoice_header'));
    $quoteHeader   = sanitize_pdf_header_content(get_setting('pdf_quote_header'));
    $invoiceFooter = sanitize_pdf_footer_content(get_setting('pdf_invoice_footer'));
    $quoteFooter   = sanitize_pdf_footer_content(get_setting('pdf_quote_footer'));

    // Set the default header that shall always be available for mPDF
    $mpdf->DefHTMLHeaderByName('defaultHeader', '');
    $mpdf->DefHTMLHeaderByName('html_header', '');
    $mpdf->DefHTMLHeaderByName('header', '');

    // Set the header if voucher is invoice and if set in settings
    if ($isInvoice && $invoiceHeader !== '') {
        if (is_object($isInvoice) && !empty($isInvoice->is_proforma)) {
            $invoiceHeader = strtr($invoiceHeader, [
                '#INVOICE' => '#PROFORMA INVOICE',
                '#Invoice' => '#PROFORMA INVOICE',
                '#invoice' => '#PROFORMA INVOICE',
                'INVOICE'  => 'PROFORMA INVOICE',
                'Invoice'  => 'PROFORMA INVOICE',
                'invoice'  => 'PROFORMA INVOICE',
            ]);
        }
        $mpdf->setAutoTopMargin = 'stretch';
        $mpdf->DefHTMLHeaderByName('header', '<div id="header">' . $invoiceHeader . '</div>');
        $mpdf->DefHTMLHeaderByName('defaultHeader', '<div id="header">' . $invoiceHeader . '</div>');
        $mpdf->DefHTMLHeaderByName('html_header', '<div id="header">' . $invoiceHeader . '</div>');
        $mpdf->SetHTMLHeaderByName('header');
        $mpdf->SetHTMLHeaderByName('html_header');
        $mpdf->SetHTMLHeaderByName('defaultHeader');
    }

    // Set the header if voucher is quote and if set in settings
    if ( ! $isInvoice && $quoteHeader !== '') {
        $mpdf->setAutoTopMargin = 'stretch';
        $mpdf->DefHTMLHeaderByName('header', '<div id="header">' . $quoteHeader . '</div>');
        $mpdf->DefHTMLHeaderByName('defaultHeader', '<div id="header">' . $quoteHeader . '</div>');
        $mpdf->DefHTMLHeaderByName('html_header', '<div id="header">' . $quoteHeader . '</div>');
        $mpdf->SetHTMLHeaderByName('header');
        $mpdf->SetHTMLHeaderByName('html_header');
        $mpdf->SetHTMLHeaderByName('defaultHeader');
    }

    //Set the default footer that shall always be available for mPDF
    $mpdf->DefHTMLFooterByName('defaultFooter', '');

    // Define common footer names to prevent "Undefined array key" errors in PHP 8.3+
    // These footer names may be referenced by CSS @page directives or <sethtmlpagefooter> tags in templates
    $mpdf->DefHTMLFooterByName('html_footer', '');
    $mpdf->DefHTMLFooterByName('footer', '');
    $mpdf->DefHTMLFooterByName('footerWithPageNumbers', '');

    // Set the footer if voucher is invoice and if set in settings
    if ($isInvoice && $invoiceFooter !== '') {
        $mpdf->setAutoBottomMargin = 'stretch';
        $mpdf->DefHTMLFooterByName('footerWithPageNumbers', '<div id="footer">' . $invoiceFooter . '</div><div><p align="center">' . str_replace('_', ' ', $filename) . ' - ' . trans('page') . ' {PAGENO} / {nbpg}</p></div>');
        $mpdf->DefHTMLFooterByName('footer', '<div id="footer">' . $invoiceFooter . '</div>');
        $mpdf->DefHTMLFooterByName('defaultFooter', '<div id="footer">' . $invoiceFooter . '</div>');
        $mpdf->DefHTMLFooterByName('html_footer', '<div id="footer">' . $invoiceFooter . '</div>');
    }

    // Set the footer if voucher is quote and if set in settings
    if ( ! $isInvoice && $quoteFooter !== '') {
        $mpdf->setAutoBottomMargin = 'stretch';
        $mpdf->DefHTMLFooterByName('footerWithPageNumbers', '<div id="footer">' . $quoteFooter . '</div><div><p align="center">' . str_replace('_', ' ', $filename) . ' - ' . trans('page') . ' {PAGENO} / {nbpg}</p></div>');
        $mpdf->DefHTMLFooterByName('footer', '<div id="footer">' . $quoteFooter . '</div>');
        $mpdf->DefHTMLFooterByName('defaultFooter', '<div id="footer">' . $quoteFooter . '</div>');
        $mpdf->DefHTMLFooterByName('html_footer', '<div id="footer">' . $quoteFooter . '</div>');
    }

    // Watermark (eInvoicing++ PDFA and PDFX do not permit transparency, so mPDF does not allow Watermarks!)
    if ( ! $embed_xml && get_setting('pdf_watermark')) {
        $mpdf->showWatermarkText = true;
    }

    $mpdf->SetHTMLFooterByName('defaultFooter');

    try {
        $mpdf->WriteHTML((string) $html);
    } catch (Exception $e) {
        log_message('error', $e->getMessage());
        show_error($e->getMessage());
    }

    if ($isInvoice) {
        $pdfFiles = glob(UPLOADS_ARCHIVE_FOLDER . '*' . $filename . '.pdf');

        foreach ($pdfFiles as $file) {
            $invoice_array[] = $file;
        }

        if ($invoice_array !== [] && null !== $is_guest) {
            rsort($invoice_array);

            if ($stream) {
                return $mpdf->Output($filename . '.pdf', 'I');
            }

            return $invoice_array[0];
        }

        $archived_file = UPLOADS_ARCHIVE_FOLDER . date('Y-m-d') . '_' . $filename . '.pdf';
        $mpdf->Output($archived_file, 'F');

        if ($stream) {
            return $mpdf->Output($filename . '.pdf', 'I');
        }

        return $archived_file;
    }

    // If $stream is true (default) the PDF will be displayed directly in the browser
    // otherwise will be returned as a download
    if ($stream) {
        return $mpdf->Output($filename . '.pdf', 'I');
    }

    $mpdf->Output(UPLOADS_TEMP_FOLDER . $filename . '.pdf', 'F');

    return UPLOADS_TEMP_FOLDER . $filename . '.pdf';
}
