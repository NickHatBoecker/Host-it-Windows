<?php

namespace AppBundle\Service;

use AppBundle\Entity\VirtualHost;

class VirtualHostHelper
{
    private $virtualHostConfigPath;
    private $hostsPath;

    private $errors;

    /**
     * @param string $virtualHostConfigPath
     * @param string $hostsPath
     */
    public function __construct($virtualHostConfigPath, $hostsPath)
    {
        $this->virtualHostConfigPath = $virtualHostConfigPath;
        $this->hostsPath = $hostsPath;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Add virtual host
     *
     * @param VirtualHost $virtualHost
     */
    public function addVirtualHost(VirtualHost $virtualHost)
    {
        if ($this->isVirtualHostAvailable($virtualHost)) {
            $this->errors[] = 'ServerName already exists.';
            return false;
        }

        // Open configuration file in append mode
        $config = fopen($this->virtualHostConfigPath, 'a');
        if (!$config) {
            $this->errors[] = 'Can not open virtual host config.';
            return false;
        }

        fwrite($config, $virtualHost->getVirtualHostConfigEntry());
        fclose($config);

        // Open hosts file in append mode
        $hosts = fopen($this->hostsPath, 'a');
        if (!$hosts) {
            $this->errors[] = 'Can not open hosts.';
            return false;
        }

        if ($virtualHost->getServerName()) {
            fwrite($hosts, $virtualHost->getHostsEntry());
        }
        fclose($hosts);
    }

    /**
     * Remove virtual host
     *
     * @param VirtualHost $virtualHost
     * @param bool $deleteFiles
     */
    public function removeVirtualHost(VirtualHost $virtualHost, $deleteFiles = false)
    {
        if (!$this->isVirtualHostAvailable($virtualHost)) {
            $this->errors[] = 'ServerName does not exist.';
            return false;
        }

        // Configuration
        $config = file_get_contents($this->virtualHostConfigPath);
        if ($config === false) {
            $this->errors[] = 'Can not open virtual host config.';
            return false;
        }

        $config = str_replace($virtualHost->getVirtualHostConfigEntry(), '', $config);
        file_put_contents($this->virtualHostConfigPath, $config);

        // Hosts
        $hosts = file_get_contents($this->hostsPath);
        if ($hosts === false) {
            $this->errors[] = 'Can not open hosts.';
            return false;
        }

        $hosts = str_replace($virtualHost->getHostsEntry(), '', $hosts);
        file_put_contents($this->hostsPath, $hosts);

        if ($deleteFiles) {
            // Remove documentRoot and all its files
            $this->removeDirectoryRecursively($virtualHost->getDocumentRoot());
        }
    }

    /**
     * Remove all virtual hosts where document root no longer exists
     *
     * @return array $removedHosts
     */
    public function clearVirtualHosts()
    {
        $removedHosts = array();

        $virtualHosts = $this->getVirtualHosts();
        foreach ($virtualHosts as $virtualHost) {
            if (!file_exists($virtualHost->getDocumentRoot())) {
                $this->removeVirtualHost($virtualHost);
                $removedHosts[] = $virtualHost->getServerName();
            }
        }

        return $removedHosts;
    }

    /**
     * @param string $serverName
     * @param string $documentRoot
     *
     * @return VirtualHost $virtualHost
     */
    public function createVirtualHost($documentRoot, $serverName)
    {
        $documentRoot = $this->clearString($documentRoot);
        $serverName = $this->clearString($serverName);

        $virtualHost = new VirtualHost();
        $virtualHost->setDocumentRoot($documentRoot);
        $virtualHost->setServerName($serverName);

        return $virtualHost;
    }

    /**
     * @param string $serverName
     *
     * @return VirtualHost
     */
    public function getVirtualHostByServerName($serverName)
    {
        foreach ($this->getVirtualHosts() as $virtualHost) {
            if ($virtualHost->getServerName() == $serverName) {
                return $virtualHost;
            }
        }

        return new VirtualHost();
    }

    /**
     * Get virtualHosts from configuration
     *
     * @return array $virtualHosts
     */
    public function getVirtualHosts()
    {
        $virtualHosts = array();

        // Get configuration
        $config = file_get_contents($this->virtualHostConfigPath);
        if ($config === false) {
            $this->errors[] = 'Can not open virtual host config.';
            return $virtualHosts;
        }

        foreach ($this->getVirtualHostEntries($config) as $virtualHostEntry) {
            // Get documentRoot and serverName
            preg_match('/DocumentRoot "(.*)".*?ServerName (.*?)</s', $virtualHostEntry, $matches);

            if (isset($matches[1]) && isset($matches[2])) {
                $virtualHosts[] = $this->createVirtualHost($matches[1], $matches[2]);
            }
        }

        return $virtualHosts;
    }

    /**
     * @param string $string
     *
     * @return string $string
     */
    private function clearString($string)
    {
        $string = str_replace("\r", "", $string);
        $string = str_replace("\n", "", $string);
        $string = str_replace("\t", "", $string);
        $string = trim($string);

        return $string;
    }

    /**
     * @param string $config
     *
     * @return array
     */
    private function getVirtualHostEntries($config)
    {
        // Get Array filled with virtualHost entries
        preg_match_all('/(<(VirtualHost)[^>]*>.*?<\/\2>)/s', $config, $virtualHostEntries);

        if (isset($virtualHostEntries[0])) {
            return $virtualHostEntries[0];
        }

        return array();
    }

    /**
     * Check if virtualHost exists with given serverName
     *
     * @param VirtualHost $virtualHost
     *
     * @return bool
     */
    private function isVirtualHostAvailable($virtualHost)
    {
        if (!$virtualHost->getServerName()) {
            return false;
        }

        foreach ($this->getVirtualHosts() as $virtualHostTemp) {
            if ($virtualHostTemp->getServerName() == $virtualHost->getServerName()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $directory
     *
     * @return bool
     */
    private function removeDirectoryRecursively($directory)
    {
        // Get all files in directory
        $files = array_diff(
            scandir($directory),
            array('.','..')
        );

        foreach ($files as $file) {
            $path = $directory.'/'.$file;

            if (is_dir($path)) {
                $this->removeDirectoryRecursively($path);
            } else {
                unlink($path);
            }
        }

        return rmdir($directory);
    }
}
