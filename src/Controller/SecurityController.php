<?php

namespace App\Controller;

use App\Entity\Property;
use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    /**
     * @Route ("/login", name="login")
     */

    public function login(AuthenticationUtils $authenticationUtils)
    {
        // serializable: prendre des données texte et les transférer en objet
        // authentiactionUtil un composant(objet) de symfony qui fait l'authentification
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', [
            'error' => $error,
            'lastUsername' => $lastUsername
        ]);
    }

    /**
     * @Route ("/signup", name="signup")
     */

    public function newUser(ManagerRegistry $doctrine, Request $request)
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            $user->setPassword(
                $this->hasher->hashPassword($user, $form->get("password")->getData())
            );
            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash("success", "Inscription réussie !");
            return $this->redirectToRoute('admin.index');
        }
        //renvoyer le formulaire sur le fichier Twig
        return $this->render('security/signup.html.twig', [
            'form' => $form->createView()
        ]);

    }
}