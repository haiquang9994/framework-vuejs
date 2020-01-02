<?php

namespace App\Console;

use Pho\Console\ConsoleKernel;

class Kernel extends ConsoleKernel
{
    public function commands()
    {
        $this->command('hello [name]', HelloCommand::class);
        $this->command('create_model [name]', CreateModel::class);
    }
}
