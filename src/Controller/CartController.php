<?php

namespace App\Controller;

use App\Form\RemoveCartType;
use App\Repository\CartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    #[IsGranted('ROLE_USER')]
    public function index(Request $request, EntityManagerInterface $em, CartRepository $cartRepository, FormFactoryInterface $formFactory): Response
    {
        $cartProducts = $cartRepository->findWithSweatAndSizeByCustomer($this->getUser());
        
        $allCartForms = [];
        $allCartFormsViews = [];
        $totalPrice = 0;
        foreach ($cartProducts as $cartProduct) {
            $form = $formFactory->createNamed(
                'form_cart_' . $cartProduct->getId(),
                RemoveCartType::class,
                $cartProduct
            );
            $form->handleRequest($request);
            $allCartForms[$cartProduct->getId()] = $form;
            $allCartFormsViews[$cartProduct->getId()] = $form->createView();
            $totalPrice += $cartProduct->getSweatVariant()->getSweat()->getPrice();
        }

        foreach ($allCartForms as $id => $form) {
            if ($form->isSubmitted() && $form->isValid()) {
                $cartProduct = $form->getData();
                $em->remove($cartProduct);
                $em->flush();
                $this->addFlash('success', "Le sweat " . $cartProduct->getSweatVariant()->getSweat()->getName() . " de taille " . strtoupper($cartProduct->getSweatVariant()->getSize()->getSize()) . " a bien été retiré du panier");
                return $this->redirectToRoute("app_cart");
            }
        }

        return $this->render('cart/index.html.twig', [
            'allCartForms' => $allCartFormsViews ?? [],
            'totalPrice' => $totalPrice,
            'publicKey' => $this->getParameter('stripe_public_key'),
        ]);
    }

    #[Route('/cart-payment', name:'app_cart_payment')]
    #[IsGranted('ROLE_USER')]
    public function cartPayment(CartRepository $cartRepository): JsonResponse
    {
        $cartProducts = $cartRepository->findWithSweatAndSizeByCustomer($this->getUser());
        $totalPrice = 0;
        foreach ($cartProducts as $cartProduct) {
            $totalPrice += $cartProduct->getSweatVariant()->getSweat()->getPrice();
        } 
        $amount = (int) round($totalPrice*100);

        Stripe::setApiKey($this->getParameter('stripe_secret_key'));
        $paymentIntent = PaymentIntent::create([
            'amount' => $amount, // montant en centimes
            'currency' => 'eur',
            'automatic_payment_methods' => ['enabled' => true]
        ]);

        return new JsonResponse([
            'clientSecret' => $paymentIntent->client_secret,
        ]);
    }

    #[Route('/cart-payment-confirmed', name:'app-cart-payment-confirmed')]
    #[IsGranted('ROLE_USER')]
    public function cartPaymentConfirmed(CartRepository $cartRepository, EntityManagerInterface $em): JsonResponse
    {
        $cartProducts = $cartRepository->findBy(['customer' => $this->getUser()]);
        foreach ($cartProducts as $cartProduct) {
            $em->remove($cartProduct);
            $em->flush();
            // Next step is to create an Order Entity to save the orders
        }
        $this->addFlash('success', "Le paiement est validé, nous vous remercions pour votre achat");
        return new JsonResponse([
            'success' => true
        ]);
    }
}
