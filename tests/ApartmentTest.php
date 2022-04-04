<?php

namespace Tests;
use App\Models\Apartment;
use PHPUnit\Framework\TestCase;

class ApartmentTest extends TestCase
{
    public function testReturnsID()
    {
        $app = new Apartment(1, 'Olafs Ozolins', 'Penthouse', 'Nice apartment with a great view',
            'New Avenue 87th', 5, '2022-03-22', '2022-03-31', 25, '13737482748742.jpg');
        $this->assertSame(1, $app->getId());
    }
    public function testReturnsPoster()
    {
        $app = new Apartment(1, 'Olafs Ozolins', 'Penthouse', 'Nice apartment with a great view',
            'New Avenue 87th', 5, '2022-03-22', '2022-03-31', 25, '13737482748742.jpg');
        $this->assertSame('Olafs Ozolins', $app->getPoster());
    }
    public function testReturnsTitle()
    {
        $app = new Apartment(1, 'Olafs Ozolins', 'Penthouse', 'Nice apartment with a great view',
            'New Avenue 87th', 5, '2022-03-22', '2022-03-31', 25, '13737482748742.jpg');
        $this->assertSame('Penthouse', $app->getTitle());
    }
    public function testReturnsDescription()
    {
        $app = new Apartment(1, 'Olafs Ozolins', 'Penthouse', 'Nice apartment with a great view',
            'New Avenue 87th', 5, '2022-03-22', '2022-03-31', 25, '13737482748742.jpg');
        $this->assertSame('Nice apartment with a great view', $app->getDescription());
    }
    public function testReturnsAddress()
    {
        $app = new Apartment(1, 'Olafs Ozolins', 'Penthouse', 'Nice apartment with a great view',
            'New Avenue 87th', 5, '2022-03-22', '2022-03-31', 25, '13737482748742.jpg');
        $this->assertSame('New Avenue 87th', $app->getAddress());
    }
    public function testReturnsRooms()
    {
        $app = new Apartment(1, 'Olafs Ozolins', 'Penthouse', 'Nice apartment with a great view',
            'New Avenue 87th', 5, '2022-03-22', '2022-03-31', 25, '13737482748742.jpg');
        $this->assertSame(5, $app->getRooms());
    }
    public function testReturnsDateFrom()
    {
        $app = new Apartment(1, 'Olafs Ozolins', 'Penthouse', 'Nice apartment with a great view',
            'New Avenue 87th', 5, '2022-03-22', '2022-03-31', 25, '13737482748742.jpg');
        $this->assertSame('2022-03-22', $app->getDateFrom());
    }
    public function testReturnsDateUntil()
    {
        $app = new Apartment(1, 'Olafs Ozolins', 'Penthouse', 'Nice apartment with a great view',
            'New Avenue 87th', 5, '2022-03-22', '2022-03-31', 25, '13737482748742.jpg');
        $this->assertSame('2022-03-31', $app->getDateUntil());
    }
    public function testReturnsPrice()
    {
        $app = new Apartment(1, 'Olafs Ozolins', 'Penthouse', 'Nice apartment with a great view',
            'New Avenue 87th', 5, '2022-03-22', '2022-03-31', 25, '13737482748742.jpg');
        $this->assertSame(25.0, $app->getPrice());
    }
    public function testReturnsPhoto()
    {
        $app = new Apartment(1, 'Olafs Ozolins', 'Penthouse', 'Nice apartment with a great view',
            'New Avenue 87th', 5, '2022-03-22', '2022-03-31', 25, '13737482748742.jpg');
        $this->assertSame('13737482748742.jpg', $app->getPhoto());
    }
}