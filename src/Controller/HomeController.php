<?php

namespace App\Controller;

use App\Application\Scheduler\Scheduler;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @var Scheduler
     */
    private $scheduler;

    /**
     * HomeController constructor.
     * @param Scheduler $scheduler
     */
    public function __construct(Scheduler $scheduler)
    {
        $this->scheduler = $scheduler;
    }

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

    /**
     * @Route("/appointment/new", name="new_appointment", methods={"POST"});
     * @param Request $request
     * @return mixed
     */
    public function newAppointment(Request $request)
    {
        $response = $this->scheduler->newAppointment(json_decode($request->getContent(), true)['data']);

        return new JsonResponse(array('data' => $response));
    }
}
