<?php
declare(strict_types=1);

use Tests\TestCase;
use paid_api\notepad\Password;

class PasswordTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @dataProvider invalidData
     */

    public function test_notepad_password_should_not_be_less_than_5_characters($password, $expectedMessage, $expectedCode)
    {
        $this->expectExceptionCode($expectedCode);
        $this->expectExceptionMessage($expectedMessage);
        new Password(new \NonEmptyString($password));
    }

    public function invalidData()
    {
        return [
            'Character length of 1' => ['1', "Password cannot be less than " . Password::MIN_LENGTH . " characters", 1312],
            'Character length of  4' => ['1234', "Password cannot be less than " . Password::MIN_LENGTH . " characters", 1312]
        ];
    }

    /**
     * @dataProvider validData
     */

    public function test_notepad_password_should_be_of_valid_characters_length($password)
    {
        $password = new Password(new \NonEmptyString($password));
        $this->assertInstanceOf(Password::class, $password);
        $this->assertEquals(Password::class, get_class($password));
    }

    public function validData()
    {
        return [
            'Character length of 5' => ['12345'],
            'Character length of 7' => ['1234567']
        ];
    }

    public function tearDown(): void
    {
    }
}
