<?php

namespace App\Models;

class Apartment
{
    private int $id;
    private string $poster;
    private string $title;
    private string $description;
    private string $address;
    private int $rooms;
    private string $dateFrom;
    private string $dateUntil;
    private float $price;
    private string $photo;

    public function __construct(int $id, string $poster, string $title, string $description, string $address, int $rooms, string $dateFrom, string $dateUntil, float $price, string $photo)
    {
        $this->id = $id;
        $this->poster = $poster;
        $this->title = $title;
        $this->description = $description;
        $this->address = $address;
        $this->rooms = $rooms;
        $this->dateFrom = $dateFrom;
        $this->dateUntil = $dateUntil;
        $this->price = $price;
        $this->photo = $photo;
    }
    public function getId(): int
    {
        return $this->id;
    }
    public function getPoster(): string
    {
        return $this->poster;
    }
    public function getTitle(): string
    {
        return $this->title;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function getAddress(): string
    {
        return $this->address;
    }
    public function getRooms(): int
    {
        return $this->rooms;
    }
    public function getDateUntil(): string
    {
        return $this->dateUntil;
    }
    public function getDateFrom(): string
    {
        return $this->dateFrom;
    }
    public function getPrice(): float
    {
        return $this->price;
    }
    public function getPhoto(): string
    {
        return $this->photo;
    }
}