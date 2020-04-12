<?php
declare(strict_types=1);

namespace paid_api\user_files_drop;

class Email
{
    private $email;

    public function __construct(\NonEmptyString $email)
    {
        if (!filter_var($email->value(), FILTER_VALIDATE_EMAIL)) {

            throw new \InvalidArgumentException('email should be valid!', 1502);
        }

        $this->email = $email->value();
    }


    /**
     * @return string
     */
    public function value(): string
    {
        return $this->email;
    }
}
