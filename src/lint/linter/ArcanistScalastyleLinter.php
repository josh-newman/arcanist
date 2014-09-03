<?php

final class ArcanistScalastyleLinter extends ArcanistExternalLinter {

  private $jarPath;
  private $configPath;

  public function getInfoURI() {
    return 'http://www.scalastyle.org/';
  }

  public function getInfoDescription() {
    return 'Scalastyle linter for Scala code';
  }

  public function getLinterName() {
    return 'scalastyle';
  }

  public function getLinterConfigurationName() {
    return 'scalastyle';
  }

  public function getDefaultBinary() {
    return 'java';
  }

  public function getInstallInstructions() {
    return 'See http://www.scalastyle.org/command-line.html';
  }

  protected function getMandatoryFlags() {
    return array('-jar', $this->jarPath, '--config', $this->configPath);
  }

  protected function getDefaultFlags() {
    return array();
  }

  protected function parseLinterOutput($path, $err, $stdout, $stderr) {
    return array();
  }

  public function getLinterConfigurationOptions() {
    $options = array(
      'jar' => array(
        'type' => 'optional string | list<string>',
        'help' => pht(
          'Specify a string (or list of strings) identifying the Scalastyle '.
          'JAR file.')
      ),
      'config' => array(
        'type' => 'optional string | list<string>',
        'help' => pht(
          'Specify a string (or list of strings) identifying the Scalastyle '.
          'config XML file.')
      ),
    );

    return $options + parent::getLinterConfigurationOptions();
  }

  public function setLinterConfigurationValue($key, $value) {
    switch ($key) {
      case 'jar':
        $working_copy = $this->getEngine()->getWorkingCopy();
        $root = $working_copy->getProjectRoot();

        foreach ((array)$value as $path) {
          if (Filesystem::pathExists($path)) {
            $this->jarPath = $path;
            return;
          }

          $path = Filesystem::resolvePath($path, $root);

          if (Filesystem::pathExists($path)) {
            $this->jarPath = $path;
            return;
          }
        }

        throw new Exception(
          pht('None of the configured Scalastyle JARs can be located.'));

      case 'config':
        $working_copy = $this->getEngine()->getWorkingCopy();
        $root = $working_copy->getProjectRoot();

        foreach ((array)$value as $path) {
          if (Filesystem::pathExists($path)) {
            $this->configPath = $path;
            return;
          }

          $path = Filesystem::resolvePath($path, $root);

          if (Filesystem::pathExists($path)) {
            $this->configPath = $path;
            return;
          }
        }

        throw new Exception(
          pht('None of the configured Scalastyle configs can be located.'));
    }

    return parent::setLinterConfigurationValue($key, $value);
  }

}
