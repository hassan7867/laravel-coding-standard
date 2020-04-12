<?php
declare(strict_types=1);

use Tests\TestCase;
use paid_api\user_files_drop\files\FileSize;

class FileSizeTest extends TestCase
{

    /**
     * @dataProvider invalidData
     */

    public function test_file_size_should_not_accept_file_size_grater_than_500000000_bytes(int $size)
    {
        $this->expectExceptionCode(051);
        $this->expectExceptionMessage("File size cannot be greater than " . FileSize::MAX_SIZE . " Bytes");
        new FileSize($size);
    }

    public function invalidData()
    {
        return [
            'file size of 5000000050 Bytes' => [5000000050],
            'file size of 5000000001 Bytes' => [5000000001],

        ];
    }

    /**
     * @dataProvider validData
     */

    public function test_file_size_should_accept_valid_file_size($size)
    {
        $size = new FileSize($size);
        $this->assertInstanceOf(FileSize::class, $size);
        $this->assertEquals(FileSize::class, get_class($size));
    }

    public function validData()
    {
        return [
            'file size of 500000000 KB' => [500000000],
            'file size of 50000000 KB' => [20000000],
        ];
    }

}
