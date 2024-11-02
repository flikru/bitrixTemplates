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

?>