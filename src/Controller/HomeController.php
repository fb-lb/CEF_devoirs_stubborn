<?php

namespace App\Controller;

use App\Entity\Sweat;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ManagerRegistry $manager): Response
    {
        $sweats = $manager->getRepository(Sweat::class)->findBy(['top'=> true]);
        
        return $this->render('home/index.html.twig', [
            'sweats' => $sweats,
        ]);
    }
}
