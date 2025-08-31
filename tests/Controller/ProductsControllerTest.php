<?php

namespace App\Tests\Controller;
 
use App\Controller\ProductsController;
use App\Entity\Cart;
use App\Entity\Customer;
use App\Entity\Size;
use App\Entity\Sweat;
use App\Entity\SweatVariant;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class ProductsControllerTest extends TestCase
{
    private MockObject&FormFactoryInterface $formFactory;
    private Customer $user;
    private Sweat $sweat;
    private Size $size;
    private SweatVariant $sweatVariant;
    private Cart $cart;

    protected function setUp(): void 
    {
        $this->formFactory = $this->createMock(FormFactoryInterface::class);
        $this->user = new Customer();
        $this->sweat = new Sweat();
        $this->sweat->setName('SweatTest');
        $this->size = new Size();
        $this->size->setSize('m');
        $this->sweatVariant = new SweatVariant();
        $this->sweatVariant->setSweat($this->sweat);
        $this->sweatVariant->setSize($this->size);
        $this->cart = new Cart();
        $this->cart->setCustomer($this->user);
        $this->cart->setSweatVariant($this->sweatVariant);
    }

    // Unit test of '/produits/{id}' route with 'POST' method
    public function testAddingProductToCart(): void
    {
        // Repositories mocking
        $sweatRepo = $this->createMock(ObjectRepository::class);
        $sweatRepo->method('find')->willReturn($this->sweat);
        $sizeRepo = $this->createMock(ObjectRepository::class);
        $sizeRepo->method('findOneBy')->willReturn($this->size);
        $sweatVariantRepo = $this->createMock(ObjectRepository::class);
        $sweatVariantRepo->method('findOneBy')->willReturn($this->sweatVariant);
        $manager = $this->createMock(ManagerRegistry::class);
        $manager->method('getRepository')->willReturnMap([
            [Sweat::class, $sweatRepo],
            [Size::class, $sizeRepo],
            [SweatVariant::class, $sweatVariantRepo],
        ]);
        
        // Form and size field mocking
        $sizeField = $this->createMock(FormInterface::class);
        $sizeField->method('getData')->willReturn('m');
        $form = $this->createMock(FormInterface::class);
        $form->method('isSubmitted')->willReturn(true);
        $form->method('isValid')->willReturn(true);
        $form->method('get')
            ->with('size')
            ->willReturn($sizeField);
        
        $this->formFactory->method('create')->willReturn($form);

        // Entity manager mocking
        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())
            ->method('persist')
            ->with($this->cart);
        $em->expects($this->once())->method('flush');

        // Controller
        $controller = new class($form) extends ProductsController {
            public array $flashes = [];
            private $form;
            public function __construct($form) {
                $this->form = $form;
            }
            public function getUser(): ?Customer { return new Customer(); }
            protected function createForm($type, $data = null, array $options = []): FormInterface {
                return $this->form;
            }
            protected function addFlash(string $type, mixed $message): void { 
                $this->flashes[] = [$type, $message];
            }
            protected function redirectToRoute($name, array $params = [], int $status = 302): RedirectResponse {
                return new RedirectResponse("redirected");
            }
        };
        
        $response = $controller->detailedProduct($manager, 1, new Request(), $em);

        $this->assertSame(302, $response->getStatusCode());
        $this->assertContains(['success', "Le sweat SweatTest de taille M a bien été ajouté au panier"], $controller->flashes);
        $this->assertSame('redirected', $response->headers->get('Location'));
    }
}
?>