<?php

class ModelError
{
    public $message, $wizardPage;

    public function __construct($message, $wizardPage = null)
    {
        $this->message = $message;
        $this->wizardPage = $wizardPage;
    }
}