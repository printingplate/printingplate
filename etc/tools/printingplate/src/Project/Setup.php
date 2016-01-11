<?php

namespace PrintingPlate\Project;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Setup
{

  # Project details
  public $projectName;
  public $projectShortName;
  public $projectUrl;
  public $projectAuthor;
  public $projectAuthorUrl;
  public $projectDescription;
  public $projectVersion;

  # Environment settings
  public $envName;
  public $envDbName;
  public $envDbUser;
  public $envDbPass;
  public $envDbHost;
  public $envDebug;
  public $envSaveQueries;
  public $envHome;
  public $envSiteUrl;
  public $envWpCache;
  public $envDisableCron;
  public $envDisallowFileEdit;
  public $envAutoUpdateCore;
  public $envAutomaticUpdaterDisabled;

  # Map .env file constants names
  public $envConstantsNames = [
    'PP_ENV'                        => 'envName',
    'PP_DBNAME'                     => 'envDbName',
    'PP_DBUSER'                     => 'envDbUser',
    'PP_DBPASS'                     => 'envDbPass',
    'PP_DBHOST'                     => 'envDbHost',
    'PP_DEBUG'                      => 'envDebug',
    'PP_SAVEQUERIES'                => 'envSaveQueries',
    'PP_HOME'                       => 'envHome',
    // 'PP_SITEURL'                    => 'envSiteUrl',
    'PP_WP_CACHE'                   => 'envWpCache',
    'PP_DISABLE_WP_CRON'            => 'envDisableWpCron',
    'PP_DISALLOW_FILE_EDIT'         => 'envDisallowFileEdit',
    'PP_AUTOMATIC_UPDATER_DISABLED' => 'envAutomaticUpdaterDisabled'
  ];

  public function save()
  {

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

    // try {
    //   $this->generateWpThemeStylesheet();
    // } catch(\Exception $e) {
    //   echo $e->getMessage();
    //   return false;
    // }

    // $this->setProjectFilePaths();

    // $this->generateWpThemeStylesheet();

    return true;

  }

  private function writeEnvironmentFile()
  {

    $fp = @fopen(PP_APP_ROOT.'/.env', 'w');  
    
    if (!$fp)
    {
      throw new \Exception('Could not write to '.PP_APP_ROOT.'/.env - Please check file permissions.');
    }
    
    $envFileContents = '';

    foreach ($this->envConstantsNames as $constant => $property)
    {
      $envFileContents .= "{$constant}={$this->$property}\n";
    }

    # Save user from more input trouble
    $envFileContents .= "PP_SITEURL={$this->envHome}\n";
    
    fwrite($fp, $envFileContents);
    
    fclose($fp);

  }

  private function setProjectFilePaths()
  {

    if('printingplate' == $this->projectShortName)
    {
      return true;
    }

    $process = new Process('cd '.PP_APP_ROOT.'/app/themes && mv printingplate '.$this->projectShortName);
    $process->run();

    if (!$process->isSuccessful()) {
      throw new ProcessFailedException($process);
      return false;
    }

    return true;

  }
  
}
