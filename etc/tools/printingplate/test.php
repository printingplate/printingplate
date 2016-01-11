<?php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;
use PrintingPlate\Cli\InitCommand;

$application = new Application();
$application->add(new InitCommand());
$application->run();
