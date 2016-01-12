<?php

namespace PrintingPlate\Project;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Setup
{

  # Project details
  public $config = [
    'projectName',
    'projectShortName',
    'projectUrl',
    'projectAuthor',
    'projectAuthorUrl',
    'projectDescription',
    'projectVersion',
    'envName',
    'envDbName',
    'envDbUser',
    'envDbPass',
    'envDbHost',
    'envDebug',
    'envSaveQueries',
    'envHome',
    'envSiteUrl',
    'envWpCache',
    'envDisableCron',
    'envDisallowFileEdit',
    'envAutomaticUpdaterDisabled',
    'envAuthKey',
    'envSecureAuthKey',
    'envLoggedInKey',
    'envNonceKey',
    'envAuthSalt',
    'envSecureAuthSalt',
    'envLoggedInSalt',
    'envNonceSalt'
  ];

  # Templates
  private $templates = [
    'env' => '../../../templates/env.mustache',
    'style' => '../../../templates/style.mustache'
  ];

  public function save()
  {

    $this->autoConfig();

    try {
      $this->writeEnvironmentFile();
    } catch(\Exception $e) {
      echo "{$e->getMessage()}\n";
      return false;
    }

    try {
      $this->setProjectFilePaths();
    } catch (\Exception $e) {
      echo $e->getMessage();
      return false;
    }

    try {
      $this->generateWpThemeStylesheet();
    } catch(\Exception $e) {
      echo $e->getMessage();
      return false;
    }

    return true;

  }

  private function writeEnvironmentFile()
  {

    $envFile = PP_APP_ROOT.'/.env';

    $fp = @fopen($envFile, 'w');
    
    if (!$fp)
    {
      throw new \Exception("Could not write to {$envFile} - Please check file permissions.");
    }
    
    $mustacheEngine = new \Mustache_Engine;
    $template = $mustacheEngine->loadTemplate(file_get_contents(__DIR__.$this->templates['env']));

    $envFileContents = $template->render($this->config);

    fwrite($fp, $envFileContents);
    
    fclose($fp);

  }

  private function autoConfig()
  {
    
    $this->config['envSiteUrl'] = rtrim($this->config['envHome'], '/').'/wp';

    $saltKeys = [
      'envAuthKey',
      'envSecureAuthKey',
      'envLoggedInKey',
      'envNonceKey',
      'envAuthSalt',
      'envSecureAuthSalt',
      'envLoggedInSalt',
      'envNonceSalt'
    ];

    foreach ($saltKeys as $key)
    {
      $this->config[$key] = $this->createSalt(64);
    }

  }

  private function setProjectFilePaths()
  {

    if('printingplate' == $this->config['projectShortName'])
    {
      return true;
    }

    $process = new Process('cd '.PP_APP_ROOT.'/app/themes && mv printingplate '.$this->config['projectShortName']);
    $process->run();

    if (!$process->isSuccessful()) {
      throw new ProcessFailedException($process);
      return false;
    }

    return true;

  }

  private function generateWpThemeStylesheet()
  {

    $styleSheetFile = PP_APP_ROOT.'/app/themes/'.$this->config['projectShortName'].'/style.css';

    $fp = @fopen($styleSheetFile, 'w');  
    
    if (!$fp)
    {
      throw new \Exception("Could not write to {$styleSheetFile} - Please check file permissions.");
    }

    $mustacheEngine = new \Mustache_Engine;
    $template = $mustacheEngine->loadTemplate(file_get_contents(__DIR__.$this->templates['style']));

    $styleSheetFileContents = $template->render($this->config);

    fwrite($fp, $styleSheetFileContents);
    
    fclose($fp);

  }

  protected function createSalt($length = 64)
  {
    # We leave out the = character to avoid issues with in the .env file
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789`-~!@#$%^&*()_+,./<>?;:[]{}\|';

    $str = '';
    $max = strlen($chars) - 1;

    for ($i=0; $i < $length; $i++)
    {
      $str .= $chars[mt_rand(0, $max)];
    }

    return $str;

  }
  
}
