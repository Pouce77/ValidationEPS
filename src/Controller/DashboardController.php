<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Form\ActivityType;
use App\Repository\ActivityRepository;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(ActivityRepository $activityRepository, StudentRepository $studentRepository,EntityManagerInterface $em,Request $request): Response
    {
        
        $activities=$activityRepository->findBy(['teacher' => $this->getUser()]);
        $form = $this->createForm(ActivityType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
    
            $activity = $form->getData();
            $activity->setTeacher($this->getUser());
            $em->persist($activity);
            $em->flush();
            return $this->redirectToRoute('app_dashboard');
        }

        $students=$studentRepository->findBy(['teacher' => $this->getUser()]);

        return $this->render('dashboard/index.html.twig', [
            'activities' => $activities,
            'form' => $form->createView(),
            'students' => $students
        ]);
    }
}
