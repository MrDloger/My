//дебаг
function dd($arr){
    echo '<pre>';
    $back_trace = debug_backtrace();
    echo 'line <b>'.$back_trace[0]['line'].'</b> in '.$back_trace[0]['file'].'</br>';
    print_r($arr);
    echo '</pre>';
}
function add2Log($data){
    define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/log.txt");
    AddMessage2Log($data);
}
