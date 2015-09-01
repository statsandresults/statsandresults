<?php
// 0	0	*	*	0	/usr/bin/php -f /home/totorat/sar/crons/get_sports.php >> /home/totorat/sar/runtime/logs/get_sports.log 2>&1

include_once (__DIR__.DIRECTORY_SEPARATOR.'api.php');

$url = 'http://212.38.167.37/resultsproxy/getresultsxml3.aspx';

echo "\r\n------------------------------------------------------------------------------------------\r\n".
    "\r\n"."\r\n".'Read url: '.$url."\r\n";

$xml = apiReadUrlXml(
    $url,
    [
        'loc' => 'en-GB',
        'action' => 'GETSPORTS'
    ]
);

if(!is_string($xml) && $xml === false )
{
    die('Error receiving xml from url:'.$url);
}
echo 'Success. String length: '.strlen($xml)."\r\n";
echo substr($xml, 0, 256)."\r\n";

echo 'Parsing xml...'."\r\n";

$xml = apiParseXml($xml);

if($xml['status']===false)
{
    die('Error parsing xml from url:'.$url.' with errors:'.var_export($xml['errors'], true));
}

echo 'Parsing Success.'."\r\n";

$file_path = PATH_RUNTIME_SPORTS.DIRECTORY_SEPARATOR.'array_sport_'.time().'.php';

$xml = var_export(obj2array($xml['result']), true);
echo substr($xml, 0, 256)."\r\n";

echo 'Writing to file.'.$file_path."\r\n";

file_put_contents(
    $file_path,
    '<? return '.$xml.';'
);

if(file_exists($file_path))
{
    echo 'Success. File size: '.filesize($file_path)."\r\n";
} else {
    echo 'Error writing file. '."\r\n";
}

