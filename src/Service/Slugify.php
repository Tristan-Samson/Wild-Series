<?php

namespace App\Service;

class Slugify 
{
    public function generate(string $input) : string
    {
        $input = trim($input);
        $input = str_replace("à", "a", $input);
        $input = str_replace("é", "e", $input);
        $input = str_replace("è", "e", $input);
        $input = str_replace("ç", "c", $input);
        $input = str_replace("ù", "u", $input);
        $input = str_replace(",", "", $input);
        $input = str_replace(".", "", $input);
        $input = str_replace("/", "", $input);
        $input = str_replace("\\", "", $input);
        $input = str_replace("?", "", $input);
        $input = str_replace("!", "", $input);
        $input = str_replace(";", "", $input);
        $input = str_replace(":", "", $input);
        $input = str_replace("'", "", $input);
        $input = strtolower($input);
        return preg_replace('/[^\w]+/', '-',  $input);
    }
}