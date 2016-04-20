<?php

if (! function_exists('thmAsset')) {

    function thmAsset($file,$type = false,$current = false)
    {
        $theme = app('themes');
        return $theme->asset($file,$type,$current);
    }
}

if (! function_exists('thmElixir')) {

    function thmElixir($file,$type = false,$current = false)
    {
        $theme = app('themes');
        return $theme->elixir($file,$type,$current);
    }
}
