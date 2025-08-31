<?php

namespace App\Tests;

use App\Entity\Cart;
use App\Entity\Customer;
use App\Entity\Size;
use App\Entity\Sweat;
use App\Entity\SweatVariant;
use App\Repository\CartRepository;
use App\Tests\Utils\TestCartController;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

class CartTestSetup extends TestCase
{
    protected Customer $user;
    protected Sweat $sweat;
    protected Size $size;
    protected SweatVariant $sweatVariant;
    protected Cart $cart;
    protected int $amount;
    protected MockObject&CartRepository $cartRepository;
    protected MockObject&Environment $twig;
    protected TestCartController $controller;

    protected function setUp(): void
    {
        // Entity/Data simulation
        $this->user = new Customer();
        $this->user->setEmail('test@test.com');

        $this->sweat = new Sweat();
        $this->sweat->setPrice(25);

        $this->size = new Size();
        $this->size->setSize('m');

        $this->sweatVariant = new SweatVariant();
        $this->sweatVariant->setSweat($this->sweat);
        $this->sweatVariant->setSize($this->size);

        $this->cart = new Cart();
        $this->cart->setSweatVariant($this->sweatVariant);
        $this->cart->setCustomer($this->user);

        $this->amount = (int) round($this->sweat->getPrice() * 100);

        // CartRepository mocking
        $this->cartRepository = $this->createMock(CartRepository::class);
        $this->cartRepository->method('findWithSweatAndSizeByCustomer')
            ->with($this->user)
            ->willReturn([$this->cart]);
        $this->cartRepository->method('findBy')
            ->with(['customer' => $this->user])
            ->willReturn([$this->cart]);
        
        //Twig mocking
        $this->twig = $this->createMock(Environment::class);

        // Controller
        $this->controller = new TestCartController($this->twig, $this->user);
    }
}

?>