<?

if (!function_exists('dd')) {
    function dd($str){
        global $USER;

        if ($USER->isAdmin())
        {
            echo "<pre style='background-color: #e5e5e5; border: 1px solid #000; border-radius: 5px; margin-bottom: 5px; padding: 5px'>";
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
            echo "<pre style='background-color: #e5e5e5; border: 1px solid #000; border-radius: 5px; margin-bottom: 5px; padding: 5px'>";
            print_r($str);
            echo "</pre>";
        }
    }
}


if (!function_exists('showPath')) {
    function showPath(){
        global $USER;
        if (!$USER->isAdmin()) return;
        $backtrace = debug_backtrace();
        $callerFile = $backtrace[0]['file'];
        echo "<pre>Path from script:<br>".$callerFile."</pre>";
    }
}

function savelog($data){
    $log = date('Y-m-d H:i:s') . ' Запись в лог';
    $log .= print_r($data, true);
    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/log.txt', $log . PHP_EOL, FILE_APPEND);
}

?>