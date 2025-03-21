<?

if (!function_exists('dd')) {
    function dd($str){
        global $USER;

        if ($USER->isAdmin())
        {
            echo "<pre>";
            print_r($str);
            echo "</pre>";
            exit;
        }
    }
}

if (!function_exists('vd')) {
    function vd($str){
        global $USER;

        if ($USER->isAdmin())
        {
            echo "<pre>";
            print_r($str);
            echo "</pre>";
        }
    }
}

function savelog($data){
    $log = date('Y-m-d H:i:s') . ' Запись в лог';
    $log .= print_r($data, true);
    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/log.txt', $log . PHP_EOL, FILE_APPEND);
}

?>