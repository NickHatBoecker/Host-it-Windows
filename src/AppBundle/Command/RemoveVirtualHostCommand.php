<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class RemoveVirtualHostCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        parent::configure();

        $this->setName('nhb:host-it:remove-virtual-host');
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $question = new Question('Please enter the name of the server: ');
        $serverName = $helper->ask($input, $output, $question);

        if (!$serverName) {
            $output->writeln('<error>Please provide needed parameters.</error>');

            return;
        }

        $question = new Question('Delete all files in document root (N)? ');
        $deleteFiles = false;
        if (strtolower($helper->ask($input, $output, $question)) == 'y') {
            $deleteFiles = true;
        }

        $virtualHostHelper = $this->getContainer()->get('nhb_hostit_virtual_host_helper');

        $virtualHost = $virtualHostHelper->getVirtualHostByServerName($serverName);

        if ($virtualHostHelper->removeVirtualHost($virtualHost, $deleteFiles) === false) {
            foreach ($virtualHostHelper->getErrors() as $error) {
                $output->writeln('<error>'.escapeshellarg($error).'</error>');
            }
            $output->writeln('<error>Virtual Host could not be removed.</error>');

            return;
        }

        if ($deleteFiles) {
            $output->writeln('<info>All files deleted in "'.$virtualHost->getDocumentRoot().'".</info>');
        }

        $output->writeln('<info>Virtual Host '.$virtualHost->getServerName().' removed. Please restart apache.</info>');
    }
}
