<?php

namespace App\Events;

use App\Models\Car;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels; 
use Illuminate\Broadcasting\InteractsWithSockets; 

class CarAdded
{
    use InteractsWithSockets, SerializesModels;

    public $car;

    public function __construct(Car $car)
    {
        $this->car = $car;
    }

    public function broadcastOn()
    {
        return new Channel('cars');
    }

    public function broadcastAs()
    {
        return 'car.added';
    }
}
