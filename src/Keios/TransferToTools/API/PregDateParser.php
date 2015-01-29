<?php namespace Keios\TransferToTools\API;

use Keios\TransferToTools\API\Contracts\DateFormatParserInterface;
use UnexpectedValueException;
use DateTime;

abstract class PregDateParser implements DateFormatParserInterface
{
    protected $pattern, $format, $mask;

    public function parse($string)
    {
        $string = (string)$string;

        $pattern = $this->pattern;
        $format = $this->format;
        $mask = $this->mask;

        $r = preg_match($pattern, $string, $matches);
        if (!$r) {
            throw new UnexpectedValueException('Preg Regex Pattern failed.');
        }
        $buffer = vsprintf($mask, $matches);
        $result = DateTime::createFromFormat($format, $buffer);
        if (!$result) {
            throw new UnexpectedValueException(sprintf('Failed To Create from Format "%s" for "%s".', $format, $buffer));
        }
        return $result;
    }

    /**
     * Build GMT .net JSON Date format from DateTime object
     * @param DateTime $dt
     * @return string
     */
    public static function buildGmt(DateTime $dt)
    {
        $offset = ($dt->getOffset() / 3600);

        $dt->modify($offset . ' hour');

        $zeroedGmtOffset = '0000';

        $sign = '+';

        $timestamp = $dt->format('U') * 1000;

        return '/Date(' . $timestamp . $sign . $zeroedGmtOffset . ')/';
    }

    /**
     * Build local .net JSON Date format from DateTime object
     * @param DateTime $dt
     * @return string
     */
    public static function build(DateTime $dt)
    {
        $offset = (string)($dt->getOffset() / 3600);
        $minus = false;

        if ($offset[0] === '-') {
            $minus = true;
            $offset = str_replace('-', '', $offset);
        }

        if (strlen($offset) === 1)
            $offset = '0' . $offset;

        $offset = $offset . '00';

        if ($minus)
            $sign = '-';
        else
            $sign = '+';

        $timestamp = $dt->format('U') * 1000;

        return '/Date(' . $timestamp . $sign . $offset . ')/';
    }
} 