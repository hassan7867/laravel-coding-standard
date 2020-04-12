<?php
declare(strict_types=1);

namespace paid_api\user_files_drop\files;

class USerFile
{
    private $size;
    private $name;
    private $extension;

    public function __construct(FileName $fileName, FileSize $fileSize, Extension $extension)
    {
        $this->size = $fileSize->value();
        $this->name = $fileName->value();
        $this->extension = $extension->value();
    }

    /**
     * @return mixed
     */
    public function extension()
    {
        return $this->extension;
    }

    /**
     * @return mixed
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function size(): int
    {
        return $this->size;
    }

}
