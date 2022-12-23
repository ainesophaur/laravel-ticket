<?php

namespace Coderflex\LaravelTicket\Exceptions;

class InvalidConfiguration extends \Exception
{
    /**
     * @param class-string $clz
     * @param class-string $expect
     * @return void
     */
    public static function modelIsNotValid($clz, $expect):self
    {
        return new self("{$clz} does not extend {$expect}");
    }
}
