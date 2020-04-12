<?php
declare(strict_types=1);

namespace paid_api\user_files_drop;

class ShareFile
{
    private $email;
    private $expiryDate;
    private $downloadOnce;

    public function __construct(Email $email, ExpiryDate $expiryDate,int $downloadOnce)
    {
        $this->email = $email->value();
        $this->expiryDate = $expiryDate->value();
        $this->downloadOnce = $downloadOnce;
    }

    /**
     * @return int
     */
    public function email(): string
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function expiryDate(): string
    {
        return $this->expiryDate();
    }

    public function downloadOnce(){
        return $this->downloadOnce;
    }

}
