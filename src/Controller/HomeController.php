<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'banner_class' => 'banner-home',
            'banner_title' => "Homeopathy and Craniosacral Therapy",
            'banner_subtitle' => "Christian Brombach"
        ]);
    }
}
