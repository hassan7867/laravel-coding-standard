<?php

declare(strict_types=1);

namespace paid_api\user_files_drop;

use DateTimeImmutable;

class ExpiryDate
{
    private $expiryDate;
    private $todayDate;

    public function __construct($expiryDate)
    {
        $this->expiryDate = new DateTimeImmutable($expiryDate);
        $this->todayDate = new DateTimeImmutable();
        if ($this->expiryDate < $this->todayDate) {

            throw new \InvalidArgumentException('expiry date should not be less than equal to today date', 1502);
        }
        $this->expiryDate = $expiryDate;
    }

    /**
     * @return DateTimeImmutable
     */
    public function value()
    {
        return $this->expiryDate;
    }

}
