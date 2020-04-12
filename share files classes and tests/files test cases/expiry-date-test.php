<?php

use paid_api\user_files_drop\ExpiryDate;
use Tests\TestCase;

class  expiryDateTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @dataProvider invalidData
     */

    public function test_expiry_date_should_not_less_than_today_date($expiryDate, $expectedMessage, $code)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionCode($code);
        $this->expectExceptionMessage($expectedMessage);
        new ExpiryDate($expiryDate);
    }

    public function invalidData()
    {
        return [
            'Today Date Before Expiry Date' => ['2020-03-05', "expiry date should not be less than equal to today date", 1502],
            'Expiry Date Equal to Today Date' => [date('Y-m-d'),"expiry date should not be less than equal to today date", 1502],
        ];
    }

    /**
     * @dataProvider validData
     */

    public function test_must_initialize_with_valid_expiry_date($expiryDate)
    {
        $employeeExperience = new ExpiryDate(strval($expiryDate));
        $this->assertInstanceOf(ExpiryDate::class, $employeeExperience);
        $this->assertEquals(ExpiryDate::class, get_class($employeeExperience));
    }

    public function validData()
    {
        return [
            'Expiry Date After Today Date' => [date('Y-m-d', strtotime("+1 day"))],
        ];
    }

    /**
     * @dataProvider invalidData
     */
    public function tearDown(): void
    {
    }
}
