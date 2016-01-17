<?php

namespace PrintingPlate\Project;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

use PrintingPlate\Project\Stylesheet;

/**
 * @todo Validate permissions on startup
 */
class Setup
{

  protected $version = '0.1';

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

  public $previousConfig = [];

  public function save()
  {

    /**
     * Set automatically configurable properties
     */
    $this->autoConfig();

    /**
     * Write .env file in project root
     */
    $this->writeEnvFile();

    /**
     * Set project file paths
     */
    $this->setProjectFilePaths();
    
    /**
     * Generate WP Theme stylesheet
     */
    $this->writeWpThemeStylesheet();

    /**
     * Generate Gulp config/index.js
     */
    $this->writeGulpConfig();
    
    /**
     * Save .pp file in project root
     */
    $this->writeInstallFile();
    
    /**
     * All clear, proceed
     */
    return true;

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

  private function writeEnvFile()
  {

    $config = $this->getEnvConfigVars();

    $dest = PP_APP_ROOT.'/.env';

    $template = $this->getTemplatePath('env');

    $confFile = new \PrintingPlate\Project\ConfFile($config, $template, $dest);
    $confFile->write();

  }

  private function setProjectFilePaths()
  {

    $currentShortname = (empty($this->previousConfig['projectShortName'])) ?  'printingplate' : $this->previousConfig['projectShortName'];

    if ($currentShortname == $this->config['projectShortName'])
    {
      return true;
    }

    $process = new Process("cd ".PP_APP_ROOT."/app/themes && mv {$currentShortname} {$this->config['projectShortName']}");
    $process->run();

    if (!$process->isSuccessful()) {
      throw new ProcessFailedException($process);
      return false;
    }

    return true;

  }

  private function writeWpThemeStylesheet()
  {

    $config = $this->getProjectConfigVars();

    $shortName = $this->config['projectShortName'];

    $dest = PP_APP_ROOT."/app/themes/{$shortName}/style.css";

    $template = $this->getTemplatePath('style');

    $confFile = new \PrintingPlate\Project\ConfFile($config, $template, $dest);
    $confFile->write();

  }

  private function writeGulpConfig()
  {

    $config = $this->getProjectConfigVars();

    $dest = PP_APP_ROOT.'/gulpfile.js/config/index.js';

    $template = $this->getTemplatePath('gulp-index');

    $confFile = new \PrintingPlate\Project\ConfFile($config, $template, $dest);
    $confFile->write();

  }

  private function writeInstallFile()
  {

    $config = array_merge(['pp_version' => $this->version], $this->getProjectConfigVars());

    $dest = PP_APP_ROOT.'/.pp';

    $template = $this->getTemplatePath('pp');

    $confFile = new \PrintingPlate\Project\ConfFile($config, $template, $dest);
    $confFile->write();

  }

  private function createSalt($length = 64)
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

  private function getTemplatePath($name)
  {
    return dirname(dirname(dirname(__FILE__))).'/templates/'.$name.'.mustache';
  }

  private function getProjectConfigVars()
  {

    $projectConfigVars = [];

    foreach ($this->config as $key => $value)
    {
      if (substr($key, 0, 7) == 'project')
      {
        $projectConfigVars[$key] = $value;
      }
    }

    return $projectConfigVars;

  }

  private function getEnvConfigVars()
  {

    $envConfigVars = [];

    foreach ($this->config as $key => $value)
    {
      if (substr($key, 0, 3) == 'env')
      {
        $envConfigVars[$key] = $value;
      }
    }

    return $envConfigVars;

  }

  public function loadConfig()
  {

    $filePath = PP_APP_ROOT.'/.pp';

    // Read file into an array of lines with auto-detected line endings
    $autodetect = ini_get('auto_detect_line_endings');
    ini_set('auto_detect_line_endings', '1');
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    ini_set('auto_detect_line_endings', $autodetect);

    $currentVars = [];
    if (!empty($lines))
    {
      foreach ($lines as $line)
      {
        list($key, $value) = explode('=', $line);
        $currentVars[$key] = $value;
      }
    }

    if (!empty($currentVars))
    {
      foreach ($this->config as $configKey)
      {
        if (substr($configKey, 0, 7) == 'project')
        {
          $configKeyUpper = strtoupper($configKey);
          if (!empty($currentVars[$configKeyUpper]))
          {
            $this->config[$configKey] = $currentVars[$configKeyUpper];
          }
        }
      }
    }

    $this->previousConfig = $this->config;

  }


  // public static function checkRequirements()
  // {
  //   if (!is_writable())
  //   {

  //   }
  // }
  
}
