<?php

namespace App\Tests\Controller;

use App\Service\StripeService;
use App\Tests\CartTestSetup;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\PaymentIntent;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CartControllerTest extends CartTestSetup
{
    // Unit test of '/cart' route with 'GET' method
    public function testIndexRendersCartPage()
    {
        // Form and form factory mocking
        $formView = $this->createMock(FormView::class);
        $form = $this->createMock(FormInterface::class);
        $form->method('handleRequest')->willReturnSelf();
        $form->method('createView')->willReturn($formView);
        $form->method('isSubmitted')->willReturn(false);

        $formFactory = $this->createMock(FormFactoryInterface::class);
        $formFactory->method('createNamed')->willReturn($form);

        // Entity Manager mocking
        $em = $this->createMock(EntityManagerInterface::class); 

        // Twig mocking
        $this->twig->expects($this->once())
            ->method('render')
            ->with(
                'cart/index.html.twig',
                $this->callback(function ($vars) {
                    return isset($vars['totalPrice'])
                        && $vars['totalPrice'] === $this->sweat->getPrice()
                        && isset($vars['allCartForms']);
                })
            )
            ->willReturn('rendered-template');

        $request = new Request();
        $response = $this->controller->index($request, $em, $this->cartRepository, $formFactory);

        // Assertions
        $this->assertInstanceOf(Response::class, $response);
        $this->assertStringContainsString('rendered-template', $response->getContent());
    }

    // Unit test of '/cart' route with POST method
    public function testIndexRemovesCartProduct()
    {
        // Form mocking
        $form = $this->createMock(FormInterface::class);
        $form->method('isSubmitted')->willReturn(true);
        $form->method('isValid')->willReturn(true);
        $form->method('getData')->willReturn($this->cart);

        $formFactory = $this->createMock(FormFactoryInterface::class);
        $formFactory->method('createNamed')->willReturn($form);

        // Entity Manager mocking
        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())
            ->method('remove')
            ->with($this->cart);
        $em->expects($this->once())->method('flush');

        // Method execution
        $request = new Request();
        $response = $this->controller->index($request, $em, $this->cartRepository, $formFactory);

        // Assertions
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertSame('app_cart', $response->headers->get('Location'));
        $this->assertNotEmpty($this->controller->flashes);
    }


    // Unit test of '/cart-payment' route
    public function testCartPaymentReturnsClientSecret(): void
    {   
        // Stripe Service mocking
        $paymentIntent = $this->getMockBuilder(PaymentIntent::class)
            ->disableOriginalConstructor()
            ->getMock();
        $paymentIntent->method('__get')->with('client_secret')->willReturn('secret_123');
        $stripeService = $this->createMock(StripeService::class);
        $stripeService->method('createPaymentIntent')
            ->with($this->amount)
            ->willReturn($paymentIntent);
    
        // Method execution
        $response = $this->controller->cartPayment($this->cartRepository, $stripeService);

        // Assertions
        $this->assertInstanceOf(JsonResponse::class, $response);
        
        $data = json_decode($response->getContent(), true);
        $this->assertEquals('secret_123', $data['clientSecret']);
    }

    // Unit test of '/cart-payment-confirmed' route
    public function testCartPaymentConfirmed(): void
    {   
        // Entity Manager mocking
        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())
            ->method('remove')
            ->with($this->cart);
        $em->expects($this->once())->method('flush');

        // Method execution
        $response = $this->controller->cartPaymentConfirmed($this->cartRepository, $em);

        // Assertions
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(['success' => true], json_decode($response->getContent(), true));
        $this->assertCount(1, $this->controller->flashes);
        $this->assertSame('success', $this->controller->flashes[0][0]);
        $this->assertStringContainsString('paiement est validé', $this->controller->flashes[0][1]);
    }
}

?>