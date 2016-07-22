<?php
class Framework{

    const AMBIENT_PRODUCTION = 'production';

    const AMBIENT_HOMOLOGATION = 'homologation';

    const AMBIENT_DEVELOPMENT = 'development';

    private $version;

    private $reportingError;

    private $displayError;

    private $config;

    public function getVersion(){
        return $this->version;
    }

    public function setVersion($version){
        $this->version = $version;
    }

    public function getReportingError()
    {
        return $this->reportingError;
    }

    public function setReportingError($reportingError)
    {
        $this->reportingError = $reportingError;
    }

    public function getDisplayError()
    {
        return $this->displayError;
    }

    public function setDisplayError($displayError)
    {
        $this->displayError = $displayError;
    }

    private function setConfig($config){
        $this->config = $config;
    }

    public function __construct($ambient){
        $config = $this->getConfig('global');
        $this->setConfig($config[$ambient]);
        $this->setVersion($this->config['version']);
        $this->setReportingError($this->config['reportingError']);
        $this->setDisplayError($this->config['displayError']);
    }

    private function getConfig($config){
        $fileContent = file_get_contents(__DIR__."/../config/{$config}.json");
        return json_decode($fileContent, true);
    }
}
?>