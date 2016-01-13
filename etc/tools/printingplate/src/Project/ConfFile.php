<?php

namespace PrintingPlate\Project;

class ConfFile
{

  private $config;
  private $template;
  private $dest;

  public function __construct(array $config, $template, $dest)
  {
    $this->config = $config;
    $this->template = $template;
    $this->dest = $dest;
  }

  public function write()
  {
    
    $fp = @fopen($this->dest, 'w');

    if (!$fp)
    {
      throw new \Exception("Could not write to {$this->dest} - Please check file permissions.");
    }

    $mustacheEngine = new \Mustache_Engine;
    
    $template = $mustacheEngine->loadTemplate(file_get_contents($this->template));

    $fileContents = $template->render($this->config);

    fwrite($fp, $fileContents);
    
    fclose($fp);

  }

}
