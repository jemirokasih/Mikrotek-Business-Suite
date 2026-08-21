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
 */

/**
 * Return a formated amount as a currency based on the system settings, e.g. 1.234,56 €.
 *
 * @param $amount
 */
function format_currency($amount): string
{
    $CI                        = & get_instance();
    $currency_symbol           = $CI->mdl_settings->setting('currency_symbol');
    $currency_symbol_placement = $CI->mdl_settings->setting('currency_symbol_placement');
    $thousands_separator       = $CI->mdl_settings->setting('thousands_separator');
    $decimal_point             = $CI->mdl_settings->setting('decimal_point');
    $decimals                  = $decimal_point ? (int) $CI->mdl_settings->setting('tax_rate_decimal_places') : 0;
    $amount                    = (float) (is_numeric($amount) ? $amount : standardize_amount($amount)); // prevent null format

    if ($currency_symbol_placement == 'before') {
        return $currency_symbol . number_format($amount, $decimals, $decimal_point, $thousands_separator);
    }

    if ($currency_symbol_placement == 'afterspace') {
        return number_format($amount, $decimals, $decimal_point, $thousands_separator) . '&nbsp;' . $currency_symbol;
    }

    return number_format($amount, $decimals, $decimal_point, $thousands_separator) . $currency_symbol;
}

/**
 * Return a formated amount based on the system settings, e.g. 1.234,56.
 *
 *
 * @return null|string
 */
function format_amount($amount = null)
{
    if ($amount) {
        $CI                  = & get_instance();
        $thousands_separator = $CI->mdl_settings->setting('thousands_separator');
        $decimal_point       = $CI->mdl_settings->setting('decimal_point');
        $decimals            = $decimal_point ? (int) $CI->mdl_settings->setting('tax_rate_decimal_places') : 0;
        $amount              = (float) (is_numeric($amount) ? $amount : standardize_amount($amount));

        return number_format($amount, $decimals, $decimal_point, $thousands_separator);
    }
}

/**
 * Return a formated amount as a quantity based on the system settings, e.g. 1.234,56.
 *
 *
 * @return null|string
 */
function format_quantity($amount = null)
{
    if ($amount) {
        $CI                  = & get_instance();
        $thousands_separator = $CI->mdl_settings->setting('thousands_separator');
        $decimal_point       = $CI->mdl_settings->setting('decimal_point');
        $decimals            = $decimal_point ? (int) $CI->mdl_settings->setting('default_item_decimals') : 0;
        $amount              = is_numeric($amount) ? $amount : standardize_amount($amount);

        return number_format($amount, $decimals, $decimal_point, $thousands_separator);
    }
}

/**
 * Return a standardized amount for database based on the system settings, e.g. 1234.56.
 *
 * @param $amount
 */
function standardize_amount($amount): float|int|string|array|false|null
{
    if ($amount && ! is_numeric($amount)) {
        $CI                  = & get_instance();
        $thousands_separator = $CI->mdl_settings->setting('thousands_separator');
        $decimal_point       = $CI->mdl_settings->setting('decimal_point');

        if ($thousands_separator == '.' && ! mb_substr_count($amount, ',') && mb_substr_count($amount, '.') > 1) {
            $amount[mb_strrpos($amount, '.')] = ','; // Replace last position of dot to comma
        }

        if ($thousands_separator) {
            $amount = strtr($amount, [$thousands_separator => '', $decimal_point => '.']);
        } else {
            $amount = strtr($amount, [$decimal_point => '.']);
        }
    }

    return $amount;
}

/**
 * Return amount converted to standard Indonesian words (Terbilang Bahasa Indonesia).
 * Example: 1500000 -> "Satu Juta Lima Ratus Ribu Rupiah"
 * Example: 1000 -> "Seribu Rupiah"
 * Example: 2500.50 -> "Dua Ribu Lima Ratus Rupiah Lima Puluh Sen"
 *
 * @param float|int|string|null $amount
 * @return string
 */
function in_words($amount): string
{
    $val = (float) standardize_amount($amount);
    if ($val == 0) {
        return 'Nol Rupiah';
    }

    $int_part = (int) floor(abs($val));
    $decimal_part = (int) round((abs($val) - $int_part) * 100);

    $terbilang_int = trim(preg_replace('/\s+/', ' ', terbilang_satuan($int_part)));
    
    $result = $terbilang_int . ' Rupiah';

    if ($decimal_part > 0) {
        $terbilang_dec = trim(preg_replace('/\s+/', ' ', terbilang_satuan($decimal_part)));
        $result .= ' ' . $terbilang_dec . ' Sen';
    }

    return ucwords(strtolower($result));
}

function terbilang($amount): string
{
    return in_words($amount);
}

function terbilang_satuan($number): string
{
    $number = (float) $number;
    $words = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];

    if ($number < 12) {
        return ' ' . $words[(int) $number];
    }
    if ($number < 20) {
        return terbilang_satuan($number - 10) . ' Belas';
    }
    if ($number < 100) {
        return terbilang_satuan((int) ($number / 10)) . ' Puluh' . terbilang_satuan((int) $number % 10);
    }
    if ($number < 200) {
        return ' Seratus' . terbilang_satuan($number - 100);
    }
    if ($number < 1000) {
        return terbilang_satuan((int) ($number / 100)) . ' Ratus' . terbilang_satuan((int) $number % 100);
    }
    if ($number < 2000) {
        return ' Seribu' . terbilang_satuan($number - 1000);
    }
    if ($number < 1000000) {
        return terbilang_satuan((int) ($number / 1000)) . ' Ribu' . terbilang_satuan((int) $number % 1000);
    }
    if ($number < 1000000000) {
        return terbilang_satuan((int) ($number / 1000000)) . ' Juta' . terbilang_satuan((int) $number % 1000000);
    }
    if ($number < 1000000000000) {
        return terbilang_satuan((int) ($number / 1000000000)) . ' Miliar' . terbilang_satuan(fmod($number, 1000000000));
    }
    if ($number < 1000000000000000) {
        return terbilang_satuan((int) ($number / 1000000000000)) . ' Triliun' . terbilang_satuan(fmod($number, 1000000000000));
    }

    return '';
}
