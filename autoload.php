<?php

// your autoloader
spl_autoload_register(function ($class) {
    $class = str_replace('_', '/', $class);
    $class = str_replace('\\', '/', $class);

    //var_dump($class);
    $filename = $class . '.php';
    $dir = array('../app/', '../src/', '../tests/', '../web/', 'app/', 'src/', 'tests/', 'web/');
    foreach ($dir as $d) {
        $file = $d . $filename;
        if (file_exists($file)) {
            require_once $file;
        }
    }
});
