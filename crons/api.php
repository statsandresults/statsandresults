<?

error_reporting(E_ALL & ~E_NOTICE);

set_time_limit(60);

define('PATH_RUNTIME', dirname(__DIR__).DIRECTORY_SEPARATOR.'runtime');
define('PATH_RUNTIME_SPORTS', PATH_RUNTIME.DIRECTORY_SEPARATOR.'cron'.DIRECTORY_SEPARATOR.'sports');
define('PATH_RUNTIME_LIVE', PATH_RUNTIME.DIRECTORY_SEPARATOR.'cron'.DIRECTORY_SEPARATOR.'live');

if(!is_dir(PATH_RUNTIME_SPORTS)) {
    mkdir(PATH_RUNTIME_SPORTS, 0777, true);
}
if(!is_dir(PATH_RUNTIME_LIVE)) {
    mkdir(PATH_RUNTIME_LIVE, 0777, true);
}

function str_replace_first($search, $replace, $subject)
{
    $pos = strpos($subject, $search);
    if ($pos !== false) {
        $subject = substr_replace($subject, $replace, $pos, strlen($search));
    }
    return $subject;
}

function apiReadUrlXml($url, $data=[])
{

/*    $url = $_REQUEST['url'];//'http://212.38.167.37/resultsproxy/getresultsxml3.aspx';

    $data = array(    'loc' => 'ru-RU',
    'action' => 'GETSPORTS'
    );*/

    if (!extension_loaded('curl')) {
        throw new \ErrorException('cURL library is not loaded');
    }


    $ch = curl_init();

    $user_agent = 'PHP-STATSANDRESULTS-PROXY/1.0.0';
    curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);

//    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
//    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_URL, $url . (empty($data) ? '' : '?' . http_build_query($data)));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_HTTPGET, true);

    $result = curl_exec($ch);
    curl_close($ch);

    return str_replace_first('<?xml version="1.0" encoding="utf-16"?>', '<?xml version="1.0" encoding="utf-8"?>', $result);

}

function apiParseXml($xml_string)
{
    $use_errors = libxml_use_internal_errors(true);

    $result = simplexml_load_string($xml_string);
    $errors = Array();
    foreach (libxml_get_errors() as $error) {
        $errors[] = $error->message;
    }
    libxml_clear_errors();
    libxml_use_internal_errors($use_errors);

    return [
        'result' => $result,
        'errors' => $errors,
        'status' => empty($errors)
    ];
}

function obj2array($res)
{
    if (is_object($res)) $res = get_object_vars($res);
    while (list($key, $value) = each($res)) {
        if (is_object($value) || is_array($value)) {
            $res[$key] = obj2array($value);
        }
    }
    return $res;
}


/*$result = str_replace('<?xml version="1.0" encoding="utf-16"?>', '<?xml version="1.0" encoding="utf-8"?>', $result);

$use_errors = libxml_use_internal_errors(true);

var_dump(simplexml_load_string($result));
foreach (libxml_get_errors() as $error) {
    echo "\t", $error->message;
}
libxml_clear_errors();
libxml_use_internal_errors($use_errors);*/

/*require_once 'Curl.php';

$curl = new Curl();
$url = 'http://212.38.167.37/resultsproxy/getresultsxml3.aspx';
//$curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
$curl_response = $curl->get($url, array(
    'loc' => 'ru-RU',
    'action' => 'GETSPORTS'
));

if (!$curl->error) {


    $response = $curl->response;
    var_dump($response);
}
*/
