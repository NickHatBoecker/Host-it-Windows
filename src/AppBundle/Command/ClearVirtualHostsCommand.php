<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClearVirtualHostsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        parent::configure();

        $this->setName('nhb:host-it:clear-virtual-hosts');
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $virtualHostHelper = $this->getContainer()->get('nhb_hostit_virtual_host_helper');
        $removedHosts = $virtualHostHelper->clearVirtualHosts();

        if (!$removedHosts) {
            $output->writeln('<info>No virtual hosts to clear.</info>');
        } else {
            $output->writeln(sprintf(
                '<info>Virtual Hosts %s cleared. Please restart apache.</info>',
                implode(', ', $removedHosts)
            ));
        }
    }
}
