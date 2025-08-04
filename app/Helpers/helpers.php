<?php

function format_money($value)
{
    if ($value != 0) {
        return number_format($value, 0, ',', '.') . ' ₫';
    } else {
        return null;
    }
}

function remove_fomart_money($value)
{
    return preg_replace('/\D/', '', $value);
}
