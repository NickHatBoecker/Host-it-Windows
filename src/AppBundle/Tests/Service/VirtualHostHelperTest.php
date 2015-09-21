<?php

namespace AppBundle\Tests\Service;

use AppBundle\Entity\VirtualHost;
use AppBundle\Service\VirtualHostHelper;

class VirtualHostHelperTest extends \PHPUnit_Framework_TestCase
{
    public function testGetVirtualHostEntry()
    {
        $virtualHost = new VirtualHost();
        $virtualHost->setServerName('test.core');
        $virtualHost->setDocumentRoot('/var/www/test.core');

        $helper = new VirtualHostHelper(null, null);
        $result = $helper->getVirtualHostEntry($virtualHost);

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
}
