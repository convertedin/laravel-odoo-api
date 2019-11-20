<?php

/**
 * Get configuration array data.
 *
 * @return array
 */
function laravelOdooApiConfig()
{
    $defaults = [
        'api-suffix' => 'xmlrpc/2',
        'encoding' => 'utf-8'
    ];

    $configuration = [];

    if (function_exists('config_path')) {
        if (file_exists(config_path('laravel-odoo-api.php'))) {
            $configuration = include(config_path('laravel-odoo-api.php'));

        }
    }

    return array_merge($defaults , $configuration);
}

/**
 * Add Character to a given string if char no exists.
 * By default is concatenated either prefix and suffix.
 *
 * @param $text
 * @param $char
 * @param bool $prefix
 * @param bool $suffix
 * @return string
 */
function laravelOdooApiAddCharacter($text, $char, $prefix = true, $suffix = true)
{
    if ($prefix && substr($text, 0, 1) !== $char)
        $text = $char . $text;

    if ($suffix && substr($text, -1, 1) !== $char)
        $text = $text . $char;

    return $text;
}

/**
 * Remove Character to a given string if char exists.
 * By default is removed from both side.
 *
 * @param $text
 * @param $char
 * @param bool $prefix
 * @param bool $suffix
 * @return string
 */
function laravelOdooApiRemoveCharacter($text, $char, $prefix = true, $suffix = true)
{
    if ($prefix && substr($text, 0, 1) === $char)
        $text = substr($text,1);

    if ($suffix && substr($text, -1, 1) === $char)
        $text = substr($text,0,-1);

    return $text;
}