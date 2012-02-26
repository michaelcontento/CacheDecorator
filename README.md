# [CacheDecorator][]

[![Build Status](https://secure.travis-ci.org/michaelcontento/CacheDecorator.png)](http://travis-ci.org/michaelcontento/CacheDecorator)

## About

Very simple caching decorator for nearly every PHP object. Everything is routed
to your original object, but only once for each method and argument combination.

## Example

    <?php

    // Will run for 7.5 million years (every time!)
    $dt = new DeepThought();
    echo $dt->answerToTheUltimateQuestionOfLifeTheUniverseAndEverything();
    
    // Should be less than 7.5 million years ;)
    $cache = new CacheDecorator\Engine\MemoryCache();
    $fastDt = new CacheDecorator\Decorator($dt, $cache);
    echo $fastDt->answerToTheUltimateQuestionOfLifeTheUniverseAndEverything();

## TODOs

* Cache the output of method calls
* Implement caching for `__toString`
* Implement caching for `__invoke`
* Implement caching for `__set_state`
* Define and implement handling for `__clone` 
* Define and implement handling of `__destruct`
* Define and implement handling of `__sleep` and `__wakeup`

## Get the tests running

* Install [PHPUnit][]
    * `pear config-set auto_discover 1`
    * `pear install pear.phpunit.de/PHPUnit`
* Install [Composer][]
    * `wget http://getcomposer.org/composer.phar`
* Let [Composer][] install all dependencies
    * `php composer.phar install`
* And finally run [PHPUnit][]
    * `phpunit`

## License

    Copyright 2009-2012 Michael Contento <michaelcontento@gmail.com>

    Licensed under the Apache License, Version 2.0 (the "License");
    you may not use this file except in compliance with the License.
    You may obtain a copy of the License at

        http://www.apache.org/licenses/LICENSE-2.0

    Unless required by applicable law or agreed to in writing, software
    distributed under the License is distributed on an "AS IS" BASIS,
    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
    See the License for the specific language governing permissions and
    limitations under the License.

  [CacheDecorator]: https://github.com/michaelcontento/CacheDecorator
  [Composer]: https://github.com/composer/composer
  [PHPUnit]: https://github.com/sebastianbergmann/phpunit
