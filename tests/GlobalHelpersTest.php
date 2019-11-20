<?php

use PHPUnit\Framework\TestCase;

class GlobalHelpersTest extends TestCase
{
    /** @test */
    public function remove_char_from_string()
    {
        $text = 'http://myurl.com/';

        $this->assertEquals('http://myurl.com',laravelOdooApiRemoveCharacter($text,'/'));
    }
}