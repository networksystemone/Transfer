<?php namespace Keios\TransferToTools\API\Contracts;

interface DateFormatParserInterface
{
    /**
     * @param $string
     *
     * @return DateTime
     */
    public function parse($string);

}