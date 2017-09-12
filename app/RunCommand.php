<?php

namespace Zipator;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RunCommand extends Command
{

	protected function configure()
	{
		$this->setName('zipator:run')
			->setDescription('Run zipator.')
			->addOption('input', 'i', InputOption::VALUE_REQUIRED, 'Path to input directory.')
			->addOption('output', 'o', InputOption::VALUE_REQUIRED, 'Path to output directory.')
			->addOption('size', 's', InputOption::VALUE_REQUIRED, 'Max archive size in bytes.')
			->addOption('extension', 'e', InputOption::VALUE_REQUIRED, 'Archive only files with given file extension.');
	}



	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$io = new SymfonyStyle($input, $output);

		// input
		$inputDir = $input->getOption('input');
		if($inputDir !== null) {
			$inputDir = realpath($inputDir);
		} else {
			$io->error('Set path to input directory');
			exit(1);
		}


		// output
		$outputDir = $input->getOption('output');


		// max size
		$maxSize = $input->getOption('size');
		if($maxSize !== null) {
			if($maxSize < 1) {
				$io->error("Max size must be whole number greater than zero.");
				exit(1);
			}
		}

		// extension
		$extension = $input->getOption('extension');


		$zipator = new Zipator();
		$count = $zipator->zip($inputDir, $outputDir, $maxSize, $extension);

		$io->title('Success! Total number of created zip archives: ' . $count);

		return 0;
	}
}
