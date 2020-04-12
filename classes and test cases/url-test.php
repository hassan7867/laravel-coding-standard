<?php
declare(strict_types=1);

use Tests\TestCase;
use paid_api\notepad\Url;

class UrlTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @dataProvider invalidData
     */

    public function test_notepad_url_should_consist_of_valid_var_char_characters_length($url, $expectedMessage, $expectedCode)
    {
        $this->expectExceptionCode($expectedCode);
        $this->expectExceptionMessage($expectedMessage);
        new Url(new \NonEmptyString($url));
    }

    public function invalidData()
    {
        return [
            'Url with characters length of 256' => [str_repeat('a',256), "Incorrect url", 2312],
            'Url with characters 1/2.' => ['1/2.', "Incorrect url", 2312],
            'Url with characters  -1120.q@qw!' => ['-1120.q@qw!', "Incorrect url", 2312],
            'Url with characters  abc.' => ['abc.', "Incorrect url", 2312],
            'Url with characters  www/http/12' => ['www/http/12', "Incorrect url", 2312],
        ];
    }

    /**
     * @dataProvider validData
     */

    public function test_notepad_url_should_be_a_valid_url($url)
    {
        $url = new Url(new \NonEmptyString($url));
        $this->assertInstanceOf(Url::class, $url);
        $this->assertEquals(Url::class, get_class($url));
    }

    public function validData()
    {
        return [
            'Url with characters 12aBc12' => ['12aBc12'],
            'Url with characters aAs123asd' => ['aAs123asd'],
            'Url with characters 123abC' => ['123abC'],
            'Url with characters aBc123' => ['aBc123'],
            'Url with characters 0123' => ['0123'],
            'Url with characters abC' => ['abC'],
            'Url with characters having 100 length' => [str_repeat('A',100)],
            'Url with characters having 255 length' => [str_repeat('B',255)]
        ];
    }

    public function tearDown(): void
    {
    }
}
