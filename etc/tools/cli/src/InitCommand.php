<?php

namespace PrintingPlate\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class InitCommand extends Command
{

	protected $input;
	protected $output;
	protected $welcome = "Welcome to the PrintingPlate setup. Let's start with some basic information.";

	protected function configure()
	{
		$this
			->setName('init')
			->setDescription('Initiate a fresh PrintingPlate project')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{

		$this->input = $input;
		$this->output = $output;

		$questions = [
			'name' => [
				'prompt' => 'Project full name',
				'default' => 'PrintingPlate'
			],
			'shortname' => [
				'prompt' => 'Project short name',
				'default' => 'printingplate',
				'validate' => '[^a-zA-Z0-9\-]'
			],
			'url' => [
				'prompt' => 'Project URL',
				'default' => 'http://printingplate.co'
			]
		];

		$this->welcome();

		$project = $this->getProjectInfoInput($questions);

	  $output->writeln('buhh');

	}

	private function welcome()
	{
		$logoFilePath = dirname(dirname(__FILE__)).'/templates/pp-logo.txt';
		$logo = file_get_contents($logoFilePath);
		$this->output->writeln("\n{$logo}\n");

		$this->output->writeln("{$this->welcome}\n");
	}

	private function getProjectInfoInput($questions)
	{
		if(empty($questions))
		{
			return [];
		}

		$helper = $this->getHelper('question');

	    $project = [];
		
		foreach($questions as $label => $question)
		{
			$project[$label] = $helper->ask(
				$this->input,
				$this->output,
				new Question("<info>{$question['prompt']} ({$question['default']}):</info> ",
					$question['default']
				)
			);
		}

		return $project;

	}
}
