<?php

namespace App\Controller;

use App\Entity\Sweat;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class ProductsController extends AbstractController
{
    #[Route('/produits', name: 'app_all_products')]
    #[IsGranted('ROLE_USER')]
    public function index(ManagerRegistry $manager): Response
    {
        $sweats = $manager->getRepository(Sweat::class)->findAll();
        return $this->render('products/all_products.html.twig', [
            'sweats' => $sweats,
        ]);
    }
}
