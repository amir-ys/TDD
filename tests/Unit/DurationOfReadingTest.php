<?php

namespace Tests\Unit;

use App\Helpers\DurationOfReading;
use PHPUnit\Framework\TestCase;

class DurationOfReadingTest extends TestCase
{
    public function test_can_get_duration_of_reading_text()
    {
        $text = 'this is a simple text for duration of reading'; // text count == 9
        $dor = new DurationOfReading($text);

        $this->assertEquals(9 , $dor->getDurationPerSeconds());
        $this->assertEquals(9 / 60 , $dor->getDurationPerMinutes());

    }
}
