<?
//дебаг
function d($value, $title = false){
    echo '<pre>' . PHP_EOL;
    $back_trace = debug_backtrace();
    echo 'line <b>'.$back_trace[0]['line'].'</b> in '.$back_trace[0]['file'].PHP_EOL.'</br>'.PHP_EOL;
    if ($title) echo $title . PHP_EOL;
    print_r($value);
    echo '</pre>'.PHP_EOL;
}
function dd($value, $title = false){
	d($value, $title);
	die();
}
function add2Log($data){
    define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/log.txt");
    AddMessage2Log($data);
}
