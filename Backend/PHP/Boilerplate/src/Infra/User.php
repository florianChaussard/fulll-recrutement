<?php

namespace Fulll\Infra;
Class User{
    /** @var string an unique identifier for the user */
    private string $id;
    /** @var string $name User's name */
    private string $name;

    public function __construct(string $name = '')
    {
        $this->id = uniqid();
        $this->name = $name;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName():string
    {
        return $this->name;
    }
    public function setName(string $name):self
    {
        $this->name = $name;
        return $this;
    }

}