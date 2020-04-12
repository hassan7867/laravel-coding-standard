<?php
declare(strict_types=1);
use paid_api\user_files_drop\Email;
use Tests\TestCase;

class EmailTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @dataProvider invalidData
     */

    public function test_email_should_not_accept_invalid_email(string $string, string $expectedMessge, int $expectedCode)
    {
        $this->expectExceptionMessage($expectedMessge);
        $this->expectExceptionCode($expectedCode);
        new Email(new NonEmptyString($string));
    }

    public function invalidData()
    {
        return [
            'email in special characters' => ['%*!$#', 'email should be valid!', 1502],
            'email without @' => ['abcgmail.com', 'email should be valid!', 1502],
            'email without dot' => ['abc@gmailcom', 'email should be valid!', 1502],
        ];
    }

    /**
     * @dataProvider validData
     */
    public function test_email_should_accept_valid_email_format(string $string)
    {
        $email = new Email(new NonEmptyString($string));
        $this->assertInstanceOf(Email::class, $email);
        $this->assertEquals(Email::class, get_class($email));
    }

    public function validData()
    {
        return [
            'email With Alphabets' => ['abc@gmail.com'],
            'email With Alphabets And Numbers' => ['abc123@gmail.com'],
            'email With Numbers' => ['123@gmail.com'],
            'email With Hyphen' => ['abc-90@gmail.com'],
            'email With Underscore' => ['abc_90@gmail.com'],
            'email With Dots' => ['hassan.abbas@abc.com'],
        ];
    }

    public function tearDown(): void
    {
    }
}
