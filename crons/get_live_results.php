<?php
// *	*	*	*	*	/usr/bin/php -f /home/totorat/sar/crons/get_live_results.php >> /home/totorat/sar/runtime/logs/get_live_results.log 2>&1

include_once(__DIR__ . DIRECTORY_SEPARATOR . 'api.php');

set_time_limit(100);


for ($x = 1; $x <= 12; $x++) {
    sleep(5);

    $url = 'http://212.38.167.37/reslive/getreslive2.aspx';

    echo "\r\n------------------------------------------------------------------------------------------\r\n" .
        "\r\n" . "\r\n" . 'Read url: ' . $url . "\r\n";

    $xml = apiReadUrlXml(
        $url,
        [
            'local' => 'en-GB'
        ]
    );

    if (!is_string($xml) && $xml === false) {
        die('Error receiving xml from url:' . $url);
    }
    echo 'Success. String length: ' . strlen($xml) . "\r\n";
    echo substr($xml, 0, 200) . "\r\n";

    echo 'Parsing xml...' . "\r\n";

    $xml = apiParseXml($xml);

    if ($xml['status'] === false) {
        die('Error parsing xml from url:' . $url . ' with errors:' . var_export($xml['errors'], true));
    }

    echo 'Parsing Success.' . "\r\n";

    $file_path = PATH_RUNTIME_LIVE . DIRECTORY_SEPARATOR . 'array_live_' . time() . '.php';

    $xml = var_export(obj2array($xml['result']), true);
    echo substr($xml, 0, 200) . "\r\n";

    echo 'Writing to file.' . $file_path . "\r\n";

    file_put_contents(
        $file_path,
        '<? return ' . $xml . ';'
    );

    if (file_exists($file_path)) {
        echo 'Success. File size: ' . filesize($file_path) . "\r\n";
    } else {
        echo 'Error writing file. ' . "\r\n";
    }

}

echo 'Deleting old files.' . "\r\n";
$count =0;
if (is_dir(PATH_RUNTIME_LIVE)) {
    foreach (new DirectoryIterator(PATH_RUNTIME_LIVE) as $fileInfo) {
        if ($fileInfo->isDot()) {
            continue;
        }
        if (time() - $fileInfo->getCTime() >= 10*60) { // last 10 min
            $count++;
            unlink($fileInfo->getRealPath());
        }
    }
}
echo 'Deleted: ' . $count . "\r\n";
