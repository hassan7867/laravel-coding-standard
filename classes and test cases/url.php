<?php

namespace paid_api\notepad;
class Url
{
    private $url;

    public function __construct(\NonEmptyString $url)
    {
        if (!preg_match('/^[a-zA-Z0-9]{1,255}+$/', $url->value())) {
            throw new \InvalidArgumentException("Incorrect url", 2312);
        }
        $this->url = $url->value();
    }

    public function value()
    {
        return $this->url;
    }
}
