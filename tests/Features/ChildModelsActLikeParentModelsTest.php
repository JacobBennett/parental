<?php

namespace Tightenco\Parental\Tests\Features;

use Tightenco\Parental\Tests\Models\Car;
use Tightenco\Parental\Tests\Models\Driver;
use Tightenco\Parental\Tests\Models\Passenger;
use Tightenco\Parental\Tests\Models\Trip;
use Tightenco\Parental\Tests\Models\Vehicle;
use Tightenco\Parental\Tests\TestCase;

class ChildModelsActLikeParentModelsTest extends TestCase
{
    /** @test */
    function test_vehicle_can_access_belongs_to_relationship_on_car_model()
    {
        $car = Car::create([
            'driver_id' => Driver::create(['name' => 'Joe'])->id,
        ]);

        $vehicle = Vehicle::find($car->id);

        $this->assertEquals($vehicle->driver->id, $car->driver->id);
    }

    /** @test */
    function test_vehicle_can_access_has_many_relationship_on_car_model()
    {
        $car = Car::create();

        $passengerA = Passenger::create(['name' => 'Jack', 'vehicle_id' => $car->id]);
        $passengerB = Passenger::create(['name' => 'Jill', 'vehicle_id' => $car->id]);

        $vehicle = Vehicle::find($car->id);

        $this->assertEquals($vehicle->passengers->pluck('id'), $car->passengers->pluck('id'));
    }

    /** @test */
    function test_vehicle_can_access_many_to_many_relationship_on_car_model()
    {
        $car = Car::create();

        $car->trips()->create([]);

        $vehicle = Vehicle::find($car->id);

        $this->assertEquals($vehicle->fresh()->trips->pluck('id'), $car->fresh()->trips->pluck('id'));
    }
}
