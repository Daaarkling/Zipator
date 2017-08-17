<?php

use Composer\Autoload\ClassLoader;
use Nette\Caching\Storages\FileStorage;
use Nette\Loaders\RobotLoader;
use Symfony\Component\Console\Application;
use Zipator\RunCommand;


/** @var ClassLoader $composer */
$composer = require __DIR__ . '/vendor/autoload.php';

$loader = new RobotLoader;
$loader->addDirectory(__DIR__ . '/app')
	->setCacheStorage(new FileStorage(__DIR__ . '/temp'))
	->register();

$app = new Application();
$app->add(new RunCommand());
$app->run();