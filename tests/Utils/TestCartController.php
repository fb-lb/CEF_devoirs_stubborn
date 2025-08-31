<?php

namespace App\Tests\Utils;

use App\Controller\CartController;
use App\Entity\Customer;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class TestCartController extends CartController {
    public array $flashes = [];
    private Environment $twig;
    private Customer $user;
    public function __construct($twig, $user) {
        $this->twig = $twig;
        $this->user = $user;
    }
    public function getUser(): ?Customer
    {
        return $this->user;
    }
    public function addFlash(string $type, mixed $message): void {
        $this->flashes[] = [$type, $message];
    }
    protected function render(string $view, array $parameters = [], ?Response $response = null): Response
    {
        return new Response($this->twig->render($view, $parameters));
    }
    public function getParameter(string $name): string
    {
        return 'pk_test_123';
    }
    protected function redirectToRoute(string $route, array $parameters = [], int $status = 302): RedirectResponse {
        return new RedirectResponse($route, $status);
    }
}

?>