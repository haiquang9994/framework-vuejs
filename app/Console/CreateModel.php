<?php
namespace App\Console;

use Symfony\Component\Console\Output\OutputInterface;

class CreateModel
{
    public function __invoke($name, OutputInterface $output)
    {
        touch(ROOT_PATH);
        $output->writeln("Create Model ".$name);
    }
}
