<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Size;
use App\Entity\Sweat;
use App\Entity\SweatVariant;
use App\Form\AddCartType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function detailedProduct(ManagerRegistry $manager, int $id, Request $request, EntityManagerInterface $em): Response
    {
        $sweat = $manager->getRepository(Sweat::class)->find($id);
        
        if (!$sweat) {
            throw new NotFoundHttpException('Le produit demandé est introuvable.');
        }

        $form = $this->createForm(AddCartType::class, ['size' => 'm']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cart = new Cart();
            $sizeValue = $form->get('size')->getData();
            $size = $manager->getRepository(Size::class)->findOneBy(['size' => $sizeValue]);
            $sweatVariant = $manager->getRepository(SweatVariant::class)->findOneBy(['sweat' => $id, 'size' => $size->getId()]);
            $cart->setSweatVariant($sweatVariant);
            $cart->setCustomer($this->getUser());
            $em->persist($cart);
            $em->flush();
            $this->addFlash('success',"Le sweat {$sweat->getName()} de taille ". strtoupper($sizeValue) ." a bien été ajouté au panier");
            return $this->redirectToRoute('app_detailed_product', ['id' => $id]);
        }
        
        return $this->render('products/detailed_product.html.twig', [
            'sweat' => $sweat,
            'addCartForm' => $form,
        ]);
    }
}
