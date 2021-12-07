<?php

namespace App\Controller;

use App\Entity\Property;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{
    /**
     * @Route("/properties", name="property.index")
     */
    public function index(ManagerRegistry $doctrine): Response
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

        $repository = $doctrine->getRepository(Property::class);
        $properties = $repository->findAll();
        //$properties = $repository->findOneBy(['surface' => 60]);
        //dd($properties);

        return $this->render('properties/index.html.twig', [
            'properties' => $properties,
        ]);
    }
}
