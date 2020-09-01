<?php

namespace App\Http;

use App\Http\Subscriber\CorsSubscriber;
use App\Http\Subscriber\ErrorSubscriber;
use Pho\Http\Kernel as PhoKernel;

class Kernel extends PhoKernel
{
    public function stacks()
    {
    }

    public function events()
    {
        $this->subscribe(CorsSubscriber::class);
        $this->subscribe(ErrorSubscriber::class);
    }
}
