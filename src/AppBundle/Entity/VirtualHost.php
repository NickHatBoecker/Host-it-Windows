<?php

namespace AppBundle\Entity;

/**
 * VirtualHost
 */
class VirtualHost
{
    /**
     * @var string
     */
    private $documentRoot;

    /**
     * @var string
     */
    private $serverName;


    /**
     * Set documentRoot
     *
     * @param string $documentRoot
     *
     * @return VirtualHost
     */
    public function setDocumentRoot($documentRoot)
    {
        $this->documentRoot = $documentRoot;
        $this->validateDocumentRoot();

        return $this;
    }

    /**
     * Get documentRoot
     *
     * @return string
     */
    public function getDocumentRoot()
    {
        return $this->documentRoot;
    }

    /**
     * Set serverName
     *
     * @param string $serverName
     *
     * @return VirtualHost
     */
    public function setServerName($serverName)
    {
        $this->serverName = $serverName;

        return $this;
    }

    /**
     * Get serverName
     *
     * @return string
     */
    public function getServerName()
    {
        return $this->serverName;
    }

    /**
     * @param string $documentRoot
     *
     * @return string
     */
    public function validateDocumentRoot()
    {
        $this->documentRoot = preg_replace('/\\\/', '/', $this->documentRoot);
    }

    /**
     * @return string
     */
    public function getVirtualHostConfigEntry()
    {
        if (!$this->documentRoot || !$this->serverName) {
            return '';
        }

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
            addslashes($this->documentRoot),
            addslashes($this->serverName),
            addslashes($this->documentRoot)
        );
    }

    /**
     * @return string
     */
    public function getHostsEntry()
    {
        if (!$this->serverName) {
            return '';
        }

        return '127.0.0.1 '.addslashes($this->serverName).PHP_EOL;
    }
}
