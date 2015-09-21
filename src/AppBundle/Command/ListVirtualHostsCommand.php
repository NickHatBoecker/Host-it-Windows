<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

class ListVirtualHostsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        parent::configure();

        $this->setName('nhb:host-it:list-virtual-hosts');
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $table = new Table($output);
        $table->setHeaders(array('#', 'Server Name', 'Document Root'));

        $virtualHostHelper = $this->getContainer()->get('nhb_hostit_virtual_host_helper');

        $virtualHosts = $virtualHostHelper->getVirtualHosts();
        foreach ($virtualHosts as $key => $virtualHost) {
            $key++;
            $table->addRow(array(
                $key,
                escapeshellcmd($virtualHost->getServerName()),
                escapeshellcmd($virtualHost->getDocumentRoot())
            ));
        }

        $table->render();
    }
}
