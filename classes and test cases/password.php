<?php

namespace paid_api\notepad;
class Password
{
    private $password;
    public const MIN_LENGTH = 5;

    public function __construct(\NonEmptyString $password)
    {
        if (strlen($password->value()) < self::MIN_LENGTH) {
            throw new \InvalidArgumentException("Password cannot be less than " . self::MIN_LENGTH . " characters", 1312);
        }
        $this->password = $password->value();
    }

    public function value()
    {
        return $this->password;
    }
}
