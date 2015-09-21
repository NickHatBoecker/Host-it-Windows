<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\VirtualHost;
use AppBundle\Form\Type\VirtualHostType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="host_it_add_virtual_host")
     */
    public function indexAction(Request $request)
    {
        $virtualHost = new VirtualHost();
        $form = $this->createForm(new VirtualHostType(), $virtualHost);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $virtualHostHelper = $this->get('nhb_hostit_virtual_host_helper');
            $virtualHostHelper->addVirtualHost($virtualHost);

            return $this->render('AppBundle::success.html.twig', array(
                'virtualHost' => $virtualHost,
            ));
        }

        return $this->render('AppBundle::index.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
