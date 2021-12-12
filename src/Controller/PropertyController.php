<?php

namespace App\Controller;

use App\Entity\Property;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{
    /**
     * @Route("/properties", name="property.index")
     */
    public function index(Request $request, ManagerRegistry $doctrine, PaginatorInterface $paginator): Response
    {
        /* Methode 1
        $entityManager = $doctrine->getManager();
        
        $property = new Property();
        $property->setTitle('Mon deuxième bien')
        ->setPrice(3000)
        ->setRooms(3)
        ->setDescription('une petite description')
        ->setSurface(800)
        ->setFloor(5)
        ->setCity('Paris')
        ->setAddress('11 rue du test')
        ->setPostalCode('75011')
        ->setSold(false);

        $entityManager->persist($property);
        $entityManager->flush();*/

        /* Methode 2 */
        $propertyLink = 'property';
        $repository = $doctrine->getRepository(Property::class);
        $properties = $repository->findAll();
        //$properties = $repository->findOneBy(['surface' => 60]);
        
        //configurer la pagination + twig.yaml
        $properties = $paginator->paginate(
            $properties, /* requete contenant les données à paginer*/
            $request->query->getInt('page', 1)/*numéro de la page en cours*/, 
            10 /*nombre de résultats par page*/
        );
        /** creer des slug
         * composer require cocur/slugify
         */
        return $this->render('properties/index.html.twig', [
            'properties' => $properties,
            'actif' => $propertyLink,
        ]);
    }

    /**
     * @Route("/property/{slug}-{id}", name="property.show", requirements={"slug":"[a-z0-9\-]*"})
     */
    public function show($id, $slug, ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Property::class);
        $property = $repository->findOneBy(['id' => $id]);
        $propertyLink = 'property';
        //dd($property);
        return $this->render('properties/show.html.twig', [
            'property' => $property,
            'actif' => $propertyLink,
        ]);        
    }
}
