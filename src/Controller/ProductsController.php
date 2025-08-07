<?php

namespace App\Controller;

use App\Entity\Sweat;
use App\Form\AddCartType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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

    #[Route('/produit/{id}', name: 'app_detailed_product')]
    #[IsGranted('ROLE_USER')]
    public function detailedProduct(ManagerRegistry $manager, int $id): Response
    {
        $sweat = $manager->getRepository(Sweat::class)->find($id);
        
        if (!$sweat) {
            throw new NotFoundHttpException('Le produit demandé est introuvable.');
        }

        $form = $this->createForm(AddCartType::class, [
            'sweat' => $sweat->getId(),
            'size' => 'm'
        ]);
        
        return $this->render('products/detailed_product.html.twig', [
            'sweat' => $sweat,
            'addCartForm' => $form,
        ]);
    }
}
