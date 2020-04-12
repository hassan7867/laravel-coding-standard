<?php

namespace paid_api\user_files_drop;

interface FileInterface
{
    public function email(): string;

    public function expiryDate(): string;

    public function fileName(): string;

    public function fileSize(): string;

}

?>
