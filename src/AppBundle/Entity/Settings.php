<?php

namespace AppBundle\Entity;

/**
 * Settings
 */
class Settings
{
    /**
     * @var string
     */
    private $virtualHostConfigPath;

    /**
     * @var string
     */
    private $hostsPath;


    /**
     * Set virtualHostConfigPath
     *
     * @param string $virtualHostConfigPath
     *
     * @return Settings
     */
    public function setVirtualHostConfigPath($virtualHostConfigPath)
    {
        $this->virtualHostConfigPath = $virtualHostConfigPath;

        return $this;
    }

    /**
     * Get virtualHostConfigPath
     *
     * @return string
     */
    public function getVirtualHostConfigPath()
    {
        return $this->virtualHostConfigPath;
    }


    /**
     * Set hostsPath
     *
     * @param string $hostsPath
     *
     * @return Settings
     */
    public function setHostsPath($hostsPath)
    {
        $this->hostsPath = $hostsPath;

        return $this;
    }

    /**
     * Get hostsPath
     *
     * @return string
     */
    public function getHostsPath()
    {
        return $this->hostsPath;
    }
}
