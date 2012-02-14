<?php

spl_autoload_register(function($class) {
    $file = __DIR__ . "/src/" . str_replace("/", "_", $class) . ".php";
    if (file_exists($file)) {
        require $file;
    }
});
