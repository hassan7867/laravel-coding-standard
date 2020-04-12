<?php
declare(strict_types=1);

namespace paid_api\user_files_drop\files;

class FileSize
{
    public const MAX_SIZE = 500000000;
    private $size;

    public function __construct(int $size)
    {
        if ($size > self::MAX_SIZE) {
            throw new \InvalidArgumentException("File size cannot be greater than " . self::MAX_SIZE . " Bytes", 051);
        }
        $this->size = $size;
    }

    public function value()
    {
        return $this->size;
    }
}
