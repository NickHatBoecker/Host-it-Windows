<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\VirtualHost;
use AppBundle\Form\Type\VirtualHostType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="host_it_list_virtual_hosts")
     */
    public function indexAction()
    {
        $virtualHostHelper = $this->get('nhb_hostit_virtual_host_helper');

        return $this->render('AppBundle::index.html.twig', array(
            'virtualHosts' => $virtualHostHelper->getVirtualHosts(),
        ));
    }

    /**
     * @Route("/add/", name="host_it_add_virtual_host")
     */
    public function addAction(Request $request)
    {
        $virtualHost = new VirtualHost();
        $form = $this->createForm(new VirtualHostType(), $virtualHost);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $virtualHostHelper = $this->get('nhb_hostit_virtual_host_helper');

            if ($virtualHostHelper->addVirtualHost($virtualHost) === false) {
                $alertType = 'danger';
                $alertText = 'Virtual Host could not be added.';
                foreach ($virtualHostHelper->getErrors() as $error) {
                    $alertText .= ' '.$error;
                }
            } else {
                $alertText = 'Virtual Host successfully added. Restart Apache!';
                $alertType = 'success';
            }

            $this->addFlash(
                'alert',
                array(
                    'text' => $alertText,
                    'type' => $alertType,
                )
            );

            return $this->redirectToRoute('host_it_list_virtual_hosts');
        }

        return $this->render('AppBundle::add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/edit/{serverName}/", name="host_it_edit_virtual_host")
     */
    public function editAction(Request $request, $serverName)
    {
        $virtualHostHelper = $this->get('nhb_hostit_virtual_host_helper');
        $virtualHost = $virtualHostHelper->getVirtualHostByServerName($serverName);
        $form = $this->createForm(new VirtualHostType(), $virtualHost);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $oldVirtualHost = $virtualHostHelper->getVirtualHostByServerName($serverName);
            if ($virtualHostHelper->removeVirtualHost($oldVirtualHost) === false) {
                // Remove virtualHost
                $alertType = 'danger';
                $alertText = 'Virtual Host could not be updated.';
                foreach ($virtualHostHelper->getErrors() as $error) {
                    $alertText .= ' '.$error;
                }
            } else {
                // Create virtualHost
                if ($virtualHostHelper->addVirtualHost($virtualHost) === false) {
                    $alertType = 'danger';
                    $alertText = 'Virtual Host could not be updated.';
                    foreach ($virtualHostHelper->getErrors() as $error) {
                        $alertText .= ' '.$error;
                    }
                } else {
                    $alertText = 'Virtual Host successfully updated. Restart Apache!';
                    $alertType = 'success';
                }
            }

            $this->addFlash(
                'alert',
                array(
                    'text' => $alertText,
                    'type' => $alertType,
                )
            );

            return $this->redirectToRoute('host_it_list_virtual_hosts');
        }

        return $this->render('AppBundle::add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/remove/{serverName}/{deleteFiles}/", name="host_it_remove_virtual_host")
     */
    public function removeAction($serverName, $deleteFiles)
    {
        $virtualHostHelper = $this->get('nhb_hostit_virtual_host_helper');
        $virtualHost = $virtualHostHelper->getVirtualHostByServerName($serverName);

        if ($virtualHostHelper->removeVirtualHost($virtualHost, $deleteFiles) === false) {
            $alertType = 'danger';
            $alertText = 'Virtual Host could not be removed.';
            foreach ($virtualHostHelper->getErrors() as $error) {
                $alertText .= ' '.$error;
            }
        } else {
            $alertText = 'Virtual Host successfully removed. Restart Apache!';
            $alertType = 'success';
        }

        $this->addFlash(
            'alert',
            array(
                'text' => $alertText,
                'type' => $alertType,
            )
        );

        return $this->redirectToRoute('host_it_list_virtual_hosts');
    }

    /**
     * @Route("/clear/", name="host_it_clear_virtual_hosts")
     */
    public function clearAction()
    {
        $virtualHostHelper = $this->get('nhb_hostit_virtual_host_helper');

        $removedHosts = $virtualHostHelper->clearVirtualHosts();
        if (!$removedHosts) {
            $alertText = 'No virtual hosts to clear.';
            $alertType = 'info';
        } else {
            $alertText = 'Virtual Hosts '.implode(', ', $removedHosts).' cleared.';
            $alertType = 'success';
        }

        $this->addFlash(
            'alert',
            array(
                'text' => $alertText,
                'type' => $alertType,
            )
        );

        return $this->redirectToRoute('host_it_list_virtual_hosts');
    }
}
