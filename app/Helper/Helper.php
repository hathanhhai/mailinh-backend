<?php


namespace App\Helper;


use Illuminate\Support\Str;

class Helper
{
    public static function convertObjectName($name,$low = false)
    {
        $word = ucfirst(strtolower($name));
        $prefixs = explode('-', $word);
        $method_name = "";
        foreach ($prefixs as $prefix) {
            $method_name .= ucfirst($prefix);
        }
        if($low){
            $method_name =   strtolower(substr($method_name,0,1)).substr($method_name,1,strlen($method_name));
        }
        return $method_name;
    }
    public static function generateFileName($image){
        $fileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        return Str::slug($fileName).'-'.rand(0, 2).'.'.$image->extension();

    }

    public static function returnExtension($url){
        return pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);;
    }
    public static function saveImage($inPath, $outPath)
    { //Download images from remote server
        $in = fopen($inPath, "rb");
        $out = fopen($outPath, "wb");
        while ($chunk = fread($in, 8192)) {
            fwrite($out, $chunk, 8192);
        }
        fclose($in);
        fclose($out);
    }
}
