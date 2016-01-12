<?php

namespace PrintingPlate\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Process\Exception\ProcessFailedException;
use PrintingPlate\Project\Setup;

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
			'projectName' => [
				'prompt' => 'Project full name',
				'default' => 'PrintingPlate'
			],
			'projectShortName' => [
				'prompt' => 'Project short name',
				'default' => 'printingplate',
				'validate' => '[^a-zA-Z0-9\-]'
			],
			'projectUrl' => [
				'prompt' => 'Project URL',
				'default' => 'http://printingplate.co'
			],
			'projectAuthor' => [
				'prompt' => 'Project author name',
				'default' => 'Clark Kent'
			],
			'projectAuthorEmail' => [
				'prompt' => 'Project author email',
				'default' => 'office@dailyplanet.com'
			],
			'projectAuthorUrl' => [
				'prompt' => 'Project author URL',
				'default' => 'http://dailyplanet.com/'
			],
			'projectVersion' => [
				'prompt' => 'Project version',
				'default' => '1.0'
			],
			'envName' => [
				'prompt' => 'Environment name',
				'default' => 'dev'
			],
			'envDbHost' => [
				'prompt' => 'Database hostname',
				'default' => 'localhost'
			],
			'envDbName' => [
				'prompt' => 'Database name',
				'default' => 'printingplate'
			],
			'envDbUser' => [
				'prompt' => 'Database user',
				'default' => 'root'
			],
			'envDbPass' => [
				'prompt' => 'Database password',
				'default' => ''
			],
			'envDebug' => [
				'prompt' => 'Activate debug mode?',
				'default' => true
			],
			'envSaveQueries' => [
				'prompt' => 'Activate save queries mode?',
				'default' => true
			],
			'envHome' => [
				'prompt' => 'Project home URL',
				'default' => 'http://loc.printingplate.co'
			],
			'envWpCache' => [
				'prompt' => 'Activate built-in WP cache?',
				'default' => false
			],
			'envDisableWpCron' => [
				'prompt' => 'Disable built-in WP cron?',
				'default' => true
			],
			'envDisallowFileEdit' => [
				'prompt' => 'Disallow file edits from WP admin?',
				'default' => true
			],
			'envAutomaticUpdaterDisabled' => [
				'prompt' => 'Disable built-in WP auto-updates?',
				'default' => true
			]
			
		];

		$this->welcome();

		$setup = $this->createSetupWithInput($questions);

		if(!$setup->save())
		{
			$this->output->writeln("\n<fg=red>Sorry, could not save project information. Please check the error above.</>");
		}
		else
		{
			$this->output->writeln("\n<fg=green>");
			$this->output->writeln("------------------------");
			$this->output->writeln("\nSucceeded!\n");
			$this->output->writeln("{$setup->projectName} has been setup at {$setup->envHome}");
			$this->output->writeln("\n------------------------");
			$this->output->writeln("</>\n");	
			
		}

	}

	private function welcome()
	{
		
		$logoFilePath = PP_APP_ROOT.'/assets/pp-logo.txt';
		
		$logo = file_get_contents($logoFilePath);
		
		$this->output->writeln("\n{$logo}\n");

		$this->output->writeln("{$this->welcome}\n");
	}

	private function createSetupWithInput($questions)
	{

		$setup = new \PrintingPlate\Project\Setup;
		
		if(empty($questions))
		{
			return false;
		}

		$helper = $this->getHelper('question');

	  foreach($questions as $label => $question)
		{
			if("?" == substr($question['prompt'], -1))
			{

				$hint = ($question['default'] == true) ? 'Y/n' : 'y/N';

				$setup->config[$label] = $helper->ask(
					$this->input,
					$this->output,
					new ConfirmationQuestion("<fg=yellow>{$question['prompt']} ({$hint}):</> ",
						$question['default'],
						'/^(y|Y|1)/i'
					)
				);
			}
			else
			{
				$setup->config[$label] = $helper->ask(
					$this->input,
					$this->output,
					new Question("<fg=yellow>{$question['prompt']} ({$question['default']}):</> ",
						$question['default']
					)
				);
			}
			
		}

		return $setup;

	}
}
