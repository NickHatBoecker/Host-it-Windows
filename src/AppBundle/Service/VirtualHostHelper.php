<?php

namespace AppBundle\Service;

use AppBundle\Entity\VirtualHost;

class VirtualHostHelper
{
    private $virtualHostConfigPath;
    private $hostsPath;

    /**
     * @param string $virtualHostConfigPath
     * @param string $hostsPath
     */
    public function __construct($virtualHostConfigPath, $hostsPath)
    {
        $this->virtualHostConfigPath = $virtualHostConfigPath;
        $this->hostsPath = $hostsPath;
    }

    public function addVirtualHost(VirtualHost $virtualHost)
    {
        // Open configuration file in append mode
        $config = fopen($this->virtualHostConfigPath, 'a') or die('Unable to open file '.$this->virtualHostConfigPath);

        fwrite($config, $this->getVirtualHostEntry($virtualHost));
        fclose($config);

        // Open hosts file in append mode
        $hosts = fopen($this->hostsPath, 'a') or die('Unable to open file '.$this->hostsPath);

        fwrite($hosts, "127.0.0.1 ".$virtualHost->getServerName().PHP_EOL);
        fclose($hosts);
    }

    /**
     * @param VirtualHost $virtualHost
     *
     * @return string
     */
    public function getVirtualHostEntry(VirtualHost $virtualHost)
    {
        $entry = "<VirtualHost %s>".PHP_EOL.
                 "\tDocumentRoot \"%s\"".PHP_EOL.
                 "\tServerName %s".PHP_EOL.
                 "\t<Directory \"%s\">".PHP_EOL.
                 "\t\tOptions FollowSymLinks Indexes".PHP_EOL.
                 "\t\tAllowOverride All".PHP_EOL.
                 "\t\tRequire all granted".PHP_EOL.
                 "\t</Directory>".PHP_EOL.
                 "</VirtualHost>".PHP_EOL;

        return sprintf(
            $entry,
            '127.0.0.1',
            $virtualHost->getDocumentRoot(),
            $virtualHost->getServerName(),
            $virtualHost->getDocumentRoot()
        );
    }
}
