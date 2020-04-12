<?php
declare(strict_types=1);

use Tests\TestCase;
use paid_api\user_files_drop\files\FileName;


class FileNameTest extends TestCase
{
    /**
     * @dataProvider invalidData
     */
    public function test_file_name_should_not_be_greater_than_500_and_less_than_2(string $name, string $expectedMsg, int $expectedCode)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode($expectedCode);
        $this->expectExceptionMessage($expectedMsg);
        $name = new FileName(new NonEmptyString($name));
    }

    public function invalidData()
    {
        return [
            '1 string length' => ['a', "File name character length should be between" . FileName::MIN_LENGTH . "-" . FileName::MAX_LENGTH, 444],
            '501 string length' => [str_repeat('a', 501), "File name character length should be between" . FileName::MIN_LENGTH . "-" . FileName::MAX_LENGTH, 444]
        ];
    }

    /**
     * @dataProvider validData
     */

    public function test_file_name_should_accept_valid_length_string(string $fileName)
    {
        $fileName = new FileName(new NonEmptyString($fileName));
        $this->assertInstanceOf(FileName::class, $fileName);
        $this->assertEquals(FileName::class, get_class($fileName));
    }

    public function validData()
    {
        return [
            'receipt name of 500 characters length' => [str_repeat('a', 500)],
            'receipt name of 2 characters length' => ['aa'],
            'receipt name of 50 characters length' => [str_repeat('a', 50)],
        ];
    }

}
