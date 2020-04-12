<?php
declare(strict_types=1);

namespace paid_api\user_files_drop\files;

class FileName
{
    private $name;
    public const MIN_LENGTH = 2;
    public const MAX_LENGTH = 500;

    public function __construct(\NonEmptyString $name)
    {
        if (strlen($name->value()) > self::MAX_LENGTH || strlen($name->value()) < self::MIN_LENGTH) {
            throw new \InvalidArgumentException("File name character length should be between" . self::MIN_LENGTH . "-" . self::MAX_LENGTH, 444);
        }
        $this->name = $name->value();
    }

    public function value()
    {
        return $this->name;
    }
}
