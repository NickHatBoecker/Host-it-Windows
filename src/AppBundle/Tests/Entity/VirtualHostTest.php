<?php

namespace AppBundle\Tests\Service;

use AppBundle\Entity\VirtualHost;

class VirtualHostTest extends \PHPUnit_Framework_TestCase
{
    public function testValidateDocumentRoot()
    {
        $virtualHost = new VirtualHost();
        $virtualHost->setDocumentRoot('C:\EasyPHP\data\localweb');
        $virtualHost->validateDocumentRoot();

        $this->assertEquals('C:/EasyPHP/data/localweb', $virtualHost->getDocumentRoot());
    }

    public function testGetVirtualHostConfigEntry()
    {
        $virtualHost = new VirtualHost();
        $virtualHost->setServerName('test.core');
        $virtualHost->setDocumentRoot('/var/www/test.core');

        $result = $virtualHost->getVirtualHostConfigEntry();

        $expected = "<VirtualHost 127.0.0.1>".PHP_EOL.
                 "\tDocumentRoot \"/var/www/test.core\"".PHP_EOL.
                 "\tServerName test.core".PHP_EOL.
                 "\t<Directory \"/var/www/test.core\">".PHP_EOL.
                 "\t\tOptions FollowSymLinks Indexes".PHP_EOL.
                 "\t\tAllowOverride All".PHP_EOL.
                 "\t\tRequire all granted".PHP_EOL.
                 "\t</Directory>".PHP_EOL.
                 "</VirtualHost>".PHP_EOL;
        $this->assertEquals($expected, $result);
    }

    public function testGetHostsEntry()
    {
        $virtualHost = new VirtualHost();
        $virtualHost->setServerName('test.core');
        $virtualHost->setDocumentRoot('/var/www/test.core');

        $this->assertEquals('127.0.0.1 test.core'.PHP_EOL, $virtualHost->getHostsEntry());
    }
}
