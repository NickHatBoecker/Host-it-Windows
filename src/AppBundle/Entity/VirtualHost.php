<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VirtualHost
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class VirtualHost
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="documentRoot", type="string", length=255)
     */
    private $documentRoot;

    /**
     * @var string
     *
     * @ORM\Column(name="serverName", type="string", length=255)
     */
    private $serverName;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

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
}
