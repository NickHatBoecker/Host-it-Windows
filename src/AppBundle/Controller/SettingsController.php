<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Settings;

class SettingsController extends Controller
{
    /**
     * @Route("/settings/", name="host_it_settings")
     */
    public function indexAction(Request $request)
    {
        $settings = new Settings();
        $settings->setVirtualHostConfigPath($this->container->getParameter('virtual_host_config_path'));
        $settings->setHostsPath($this->container->getParameter('hosts_path'));

        $form = $this->createFormBuilder($settings)
            ->add('virtualHostConfigPath', 'text')
            ->add('hostsPath', 'text')
            ->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            if ($this->updateSettings($settings) === false) {
                $alertText = 'Settings could not be saved.';
                $alertType = 'danger';
            } else {
                $alertText = 'Settings saved.';
                $alertType = 'success';
            }

            $this->addFlash(
                'alert',
                array(
                    'text' => $alertText,
                    'type' => $alertType,
                )
            );
        }

        return $this->render('AppBundle::settings.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @param Settings $settings
     */
    private function updateSettings($settings)
    {
        $parametersPath = $this->container->getParameter('kernel.root_dir').'/config/parameters.yml';
        $parameters = file_get_contents($parametersPath);
        if ($parameters === false) {
            return false;
        }

        $virtualHostConfigPath = "virtual_host_config_path: '".addslashes($settings->getVirtualHostConfigPath())."'";
        $hostsPath = "hosts_path: '".addslashes($settings->getHostsPath())."'";

        $parameters = preg_replace('/virtual_host_config_path: \'(.*?)\'/s', $virtualHostConfigPath, $parameters);
        $parameters = preg_replace('/hosts_path: \'(.*?)\'/s', $hostsPath, $parameters);

        file_put_contents($parametersPath, $parameters);
    }
}
