<?php

namespace App\Tests\Entity;

use App\Entity\Measurement;
use PHPUnit\Framework\TestCase;

class MeasurementTest extends TestCase
{
    public function dataGetFahrenheit(): array
    {
        return [
            ['0', 32],
            ['-100', -148],
            ['100', 212],
            ['43', 109.4],
            ['20.5', 68.9],
            ['-3', 26.6],
            ['-8.5', 16.7],
            ['-0.9', 30.38],
            ['77', 170.6],
            ['54', 129.2]
        ];
    }

//    public function testGetFahrenheit(): void
//    {
//        $measurement = new Measurement();
//
//        $measurement->setTemperature(0);
//        $this->assertEquals(32, $measurement->getTemperatureFahrenheit());
//
//        $measurement->setTemperature(-100);
//        $this->assertEquals(-148, $measurement->getTemperatureFahrenheit());
//
//        $measurement->setTemperature(100);
//        $this->assertEquals(212, $measurement->getTemperatureFahrenheit());
//    }

    /**
     * @dataProvider dataGetFahrenheit
     */
    public function testGetFahrenheit($celsius, $expectedFahrenheit): void
    {
        $measurement = new Measurement();

        $measurement->setTemperature($celsius);
        $this->assertEquals($expectedFahrenheit, $measurement->getTemperatureFahrenheit());
    }
}
