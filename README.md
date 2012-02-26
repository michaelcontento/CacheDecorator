# [CacheDecorator][]

[![Build Status](https://secure.travis-ci.org/michaelcontento/CacheDecorator.png)](http://travis-ci.org/michaelcontento/CacheDecorator)

## About

Very simple caching decorator for nearly every PHP object. Everything is routed
to your original object (read: all magic methods like `__set`, `__get`, 
`__isset`, etc. are implemented) but all consecutive calls to `__get` and 
`__call` are cached. 

## Example

    <?php

    // Will run for 7.5 million years (every time!)
    $dt = new DeepThought();
    echo $dt->answerToTheUltimateQuestionOfLifeTheUniverseAndEverything();
    
    // Should be less than 7.5 million years ;)
    $fastDt = new CacheDecorator\Decorator(
        $dt, 
        new CacheDecorator\Engine\MemoryCache()
    );
    echo $fastDt->answerToTheUltimateQuestionOfLifeTheUniverseAndEverything();

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
