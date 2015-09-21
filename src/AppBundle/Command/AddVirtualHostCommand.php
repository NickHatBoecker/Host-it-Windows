<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Entity\VirtualHost;

class AddVirtualHostCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        parent::configure();

        $this->setName('nhb:host-it:add-virtual-host')
            ->addArgument(
                'serverName',
                InputArgument::REQUIRED,
                'Servername?'
            )
            ->addArgument(
                'documentRoot',
                InputArgument::REQUIRED,
                'DocumentRoot?'
            );
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return integer 0 if everything went fine, or an error code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $virtualHost = new VirtualHost();
        $virtualHost->setServerName($input->getArgument('serverName'));
        $virtualHost->setDocumentRoot($input->getArgument('documentRoot'));

        $virtualHostHelper = $this->getContainer()->get('nhb_hostit_virtual_host_helper');
        $virtualHostHelper->addVirtualHost($virtualHost);

        $output->writeln('<info>Virtual Host added.</info>');

        return 0;
    }
}
