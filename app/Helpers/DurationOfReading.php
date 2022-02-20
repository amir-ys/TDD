<?php

namespace App\Helpers;

class DurationOfReading
{
    private int $wordPerSeconds  = 1;
    private int $wordLength;
    private int|float $duration;
    public function __construct($text)
    {
        $this->wordLength = count(explode(' ' , $text));
        $this->duration = $this->wordLength * $this->wordPerSeconds ;
    }

    public function getDurationPerSeconds(): float|int
    {
        return $this->duration;
    }

    public function getDurationPerMinutes(): float|int
    {
        return $this->duration / 60;
    }

}
