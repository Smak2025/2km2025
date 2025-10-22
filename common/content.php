<?php

namespace common;

abstract class AContent
{
    abstract public function create_content();
    protected bool $is_opened = false;
    public function is_opened(): bool
    {
        return $this->is_opened;
    }
    public function __construct()
    {
        session_start();

    }
}