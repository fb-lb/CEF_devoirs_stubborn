<?php

namespace App\Controller;

use App\Entity\Sweat;
use App\Entity\SweatVariant;
use App\Form\AddSweatType;
use App\Repository\SizeRepository;
use App\Repository\SweatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class BackOfficeController extends AbstractController
{
    #[Route('/back-office', name: 'app_back_office')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(Request $request, EntityManagerInterface $em, SizeRepository $sizeRepository, SweatRepository $sweatRepository, FormFactoryInterface $formFactory): Response
    {
        // Add sweat form
        $sweat = new Sweat();
        $sizes = $sizeRepository->findAll();

        foreach ($sizes as $size) {
            $variant = new SweatVariant();
            $variant->setSize($size);
            $variant->setStock(0);
            $sweat->addSweatVariant($variant);
        }

        $addSweatForm = $this->createForm(AddSweatType::class, $sweat, ['with_add' => true]);
        $addSweatForm->handleRequest($request);

        if ($addSweatForm->isSubmitted() && $addSweatForm->isValid()) {
            /** @var \Symfony\Component\Form\SubmitButton $addButton */
                $addButton = $addSweatForm->get('add');
                if ($addButton->isClicked()) {
                    $image = $addSweatForm->get('file')->getData();
                    if ($image) {
                        $newImageFileName = uniqid() . '.' . $image->guessExtension();
                        $image->move(
                            $this->getParameter('images_directory_path') . $this->getParameter('images_directory'),
                            $newImageFileName
                        );
                        $newImagePathName = $this->getParameter('images_directory') . $newImageFileName;
                        $sweat->setFileName($newImagePathName);
                    }
                    $em->persist($sweat);
                    $em->flush();
                    $this->addFlash('success', 'Nouveau Sweat ajouté à la base de données');
                    return $this->redirectToRoute('app_back_office');
                }
        }

        // Manage sweat forms
        $manageSweatForms = [];
        $sweats = $sweatRepository->findAll();
        foreach ($sweats as $sweat) {
            $manageSweatForm = $formFactory->createNamed(
                'form_sweat_' . $sweat->getId(),
                AddSweatType::class,
                $sweat, [
                'with_update' => true,
                'with_delete' => true,
                ],
                'sweat_' . $sweat->getId()
            );
            $manageSweatForm->handleRequest($request);
            $manageSweatForms[$sweat->getId()] = $manageSweatForm;
            $manageSweatFormsViews[$sweat->getId()] = $manageSweatForm->createView();
        }

        foreach ($manageSweatForms as $id => $form) {
            if ($form->isSubmitted() && $form->isValid()) {
                $sweat = $form->getData();
                
                /** @var \Symfony\Component\Form\SubmitButton $updateButton */
                $updateButton = $form->get('update');
                if ($updateButton->isClicked()) {
                    $newImage = $form->get('file')->getData();
                    if ($newImage) {
                        $currentImagePath = $this->getParameter('images_directory_path') . $sweat->getFileName();
                        if (file_exists($currentImagePath)) {
                            unlink($currentImagePath);
                        }
                        $newImageFileName = uniqid() . '.' . $newImage->guessExtension();
                        $newImage->move(
                            $this->getParameter('images_directory_path') . $this->getParameter('images_directory'),
                            $newImageFileName
                        );
                        $newImagePathName = $this->getParameter('images_directory') . $newImageFileName;
                        $sweat->setFileName($newImagePathName);
                    }
                    $em->flush();
                    $this->addFlash('success', 'Modifications du sweat ' . $sweat->getName() . ' prises en comptes');
                }

                /** @var \Symfony\Component\Form\SubmitButton $deleteButton */
                $deleteButton = $form->get('delete');
                if ($deleteButton->isClicked()) {
                    $imagePath = $this->getParameter('images_directory_path') . $sweat->getFileName();
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                    $em->remove($sweat);
                    $em->flush();
                    $this->addFlash('success', 'Le sweat ' . $sweat->getName() . ' a bien été supprimé');
                }

                return $this->redirectToRoute('app_back_office');
            }
        }

        return $this->render('back_office/index.html.twig', [
            'addSweatForm' => $addSweatForm,
            'manageSweatForms' => $manageSweatFormsViews ?? []
        ]);
    }
}
