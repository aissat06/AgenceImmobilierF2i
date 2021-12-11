<?php

namespace App\Controller;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPropertyController extends AbstractController
{
    public function __construct(PropertyRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/admin", name="admin.index")
     */
    public function index(): Response
    {
        $properties = $this->repository->findAll();
        return $this->render('admin/index.html.twig', [
            'properties' => $properties,
        ]);
    }

    /**
     * @Route("/admin/new", name="admin.new")
     */
    public function new( ManagerRegistry $doctrine, Request $request ): Response
    {
        $property = new Property();
        
        $form = $this->createForm(PropertyType::class, $property);

        $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid() ) {

            $em = $doctrine->getManager();
            $em->persist($property);
            $em->flush();
            $this->addFlash('success', 'la propriété a été bien créé !' );
            return $this->redirectToRoute('admin.index');
        }
        return $this->render('admin/form.html.twig', [
            'function' => 'create',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/edit/{id}", name="admin.edit")
     */
    public function edit( Property $property, ManagerRegistry $doctrine, Request $request ): Response
    {
        //$property = new Property();
        
        $form = $this->createForm(PropertyType::class, $property);

        $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid() ) 
        {

            $em = $doctrine->getManager();
            $em->persist($property);
            $em->flush();
            $this->addFlash('success', 'la propriété a été bien modifié !' );
            return $this->redirectToRoute('admin.index');
        }
        return $this->render('admin/form.html.twig', [
            'function' => 'edit',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/delete/{id}", name="admin.delete")
     */
    public function delete(Property $property, ManagerRegistry $doctrine, Request $request)
    {
        $em = $doctrine->getManager();
        if($this->isCsrfTokenValid('delete' . $property->getId(), $request->get('_token'))) 
        {
            $em->remove($property);
            $em->flush();
            $this->addFlash('success', 'la propriété a été bien supprimé !' );
        }
        return $this->redirectToRoute('admin.index');
    }
}
