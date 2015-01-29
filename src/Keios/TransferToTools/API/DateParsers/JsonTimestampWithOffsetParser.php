<?php namespace Keios\TransferToTools\API\DateParsers;

use Keios\TransferToTools\API\PregDateParser;

class JsonTimestampWithOffsetParser extends PregDateParser
{
    protected $pattern = '/^\/Date\((\d{10})(\d{3})([+-]\d{4})\)\/$/';
    protected $format = 'U.u.O';
    protected $mask = '%2$s.%3$s.%4$s';
}