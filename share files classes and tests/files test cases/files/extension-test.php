<?php
declare(strict_types=1);

use Tests\TestCase;
use paid_api\user_files_drop\files\Extension;

class ExtensionTest extends TestCase
{

    /**
     * @dataProvider invalidData
     */

    public function test_extension_should_not_accept_the_invalid_extensions(string $extension)
    {
        $this->expectExceptionCode(777);
        $this->expectExceptionMessage("File extension " . $extension . " is not allowed!");
        new Extension(new NonEmptyString($extension));
    }

    public function invalidData()
    {
        return [
            ['any other string'],
            ['dat'],
            ['exe'],
            ['.pdf'],
            ['.jpg'],
            ['abc.jpg'],
        ];
    }

    /**
     * @dataProvider validData
     */

    public function test_extension_should_accept_allowed_extensions(string $extension)
    {
        $fileExtension = new Extension(new NonEmptyString($extension));
        $this->assertInstanceOf(Extension::class, $fileExtension);
        $this->assertEquals(Extension::class, get_class($fileExtension));
    }

    public function validData()
    {
        return [
            ['pdf'],
            ['jpg'],
            ['png'],
            ['jpeg'],
            ['txt'],
            ['xlsx'],
            ['docx'],
            ['gif'],
            ['zip'],
            ['mp4'],
            ['mp3'],
            ['wmv'],
            ['mov'],
            ['flv'],
            ['avi'],
        ];
    }

}
