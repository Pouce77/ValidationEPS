<?php

namespace App\Controller;

use App\Entity\Activity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ActivityController extends AbstractController
{
    #[Route('/activity/{id}', name: 'app_activity')]
    public function index(Activity $activity): Response
    {
        return $this->render('activity/index.html.twig', [
            'activity' => $activity,
        ]);
    }
}
