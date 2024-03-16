<?php

namespace Fulll\Domain;

class Location
{
    /**
     * @var string $id an unique identifier for the location
     */
    private string $id;
    /**
     * @var float $longitude the longitude of the location
     */
    private float $longitude;
    /**
     * @var float $latitude the latitude of the location
     */
    private float $latitude;
    /**
     * @var string $name the name of the location
     */
    private string $name;

    public function __construct(float $longitude = 0, float $latitude = 0, string $name = '')
    {
        $this->id = uniqid();
        $this->longitude = $longitude;
        $this->latitude = $latitude;
        $this->name = $name;
    }

    public function getId():string
    {
        return $this->id;
    }

    public function getLongitude():float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude):self
    {
        $this->longitude = $longitude;
        return $this;
    }

    public function getLatitude():float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude):self
    {
        $this->latitude = $latitude;
        return $this;
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