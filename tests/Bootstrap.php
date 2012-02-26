<?php

if (!@include(__DIR__ . '/../vendor/.composer/autoload.php')) {
    echo "You must set up the project dependencies, run the following commands:"
        . "wget http://getcomposer.org/composer.phar"
        . " && php composer.phar install";
    exit 1;
}
