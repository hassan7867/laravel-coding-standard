<?php
declare(strict_types=1);

namespace paid_api\user_files_drop\files;

class Extension
{
    private const ALLOWED_EXTENSIONS = ['mp4', 'avi', 'flv', 'wmv', 'mov', 'mp3', 'pdf', 'jpeg', 'jpg', 'png', 'gif', 'pdf', 'txt', 'docx', 'zip', 'json', 'xlsx'];
    private $extension;

    public function __construct(\NonEmptyString $extension)
    {
        if (!in_array($extension->value(), self::ALLOWED_EXTENSIONS)) {
            throw new \InvalidArgumentException("File extension " . $extension->value() . " is not allowed!", 777);
        }
        $this->extension = $extension->value();
    }

    public function value(){
        return $this->extension;
    }
}
