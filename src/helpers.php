<?php

final class Tpm extends TeamWorkPm\Factory
{
    public static function auth(...$args)
    {
        if (count($args) === 3) {
            $format = $args[2];
            TeamWorkPm\Rest\Client::setFormat($format);
            unset($args[2]);
        }
        TeamWorkPm\Auth::set(...$args);
    }
}
