<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class AuthenticationController extends AbstractController
{
         /**
         * @Route("/authentication/login")
         */
    public function login(Request $request, ValidatorInterface $validator)
    {
        $utilisateur = new Utilisateur();

        $form = $this->createFormBuilder($utilisateur)
            ->add('email', TextType::class)
            ->add('mot_de_passe', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Se connecter'])
            ->getForm();

        $form->handleRequest($request);

        $errors = array("email" => "", "password" => "");

        if ($form->isSubmitted()) {
            $errors = array("email" => "", "password" => "");
            $utilisateur = $form->getData();

            if (!$this->email_is_valid($utilisateur->getEmail())) {
                $errors["email"] = "Email est invalide";
            }
            if (!$this->password_is_valid($utilisateur->getMotDePasse())) {
                $errors["password"] = "Le mot de passe doit avoir au moins 8 caractères et contenir un chiffre et un symbole.";
            }
            

            if (strlen($errors["email"]) > 0 || strlen($errors["password"]) > 0) return $this->render("authentication/signin.html.twig", [
            'form' => $form->createView(),
            'errors' => $errors
            ]);

            $utilisateur_a_connecter = $this->getDoctrine()
            ->getRepository(Utilisateur::class)
            ->findOneBy(array('email'=> $utilisateur->getEmail(), 'mot_de_passe'=> $utilisateur->getMotDePasse()));
            if (!$utilisateur_a_connecter) {
                return $this->render("authentication/signin.html.twig", [
            'form' => $form->createView(),
            'errors' => $errors,
            'login_error' => "Email out mot de passe incorrect"
        ]);
            } else return new Response("yes");
            
        }

        return $this->render("authentication/signin.html.twig", [
            'form' => $form->createView(),
            'errors' => $errors
        ]);
    }
        /**
         * @Route("/authentication/signup")
         */
        public function signup(Request $request)
        {
            $utilisateur = new Utilisateur();
            $utilisateur->setNom("");
            $utilisateur->setPrenom("");
            $utilisateur->setEmail("");
            $utilisateur->setMotDePasse("");
            $utilisateur->setProfil("client");
            $utilisateur->setCodeConfirmationEmail(strval(random_int(1000,9999)));
            $utilisateur->set_email_confirme(0);
            $form = $this->createFormBuilder($utilisateur)
            ->add('Nom', TextType::class)
            ->add('Prenom', TextType::class)

            ->add('Email', TextType::class)
            ->add('mot_de_passe', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Inscrivez-vous'])
            ->getForm();
          
           
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                $data = $form->getData();
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($data);
                $entityManager->flush();
                return new Response("yes");
            }
            else return $this->render("authentication/signup.html.twig", [
                "form" => $form->createView(),
            ]);
        }
          /**
         * @Route("/authentication/reset_password")
         */
        public function reset_password()
        {
            return $this->render("");
        }
        /**
         * @Route("/authentication/verif_email")
         */
        public function verif_email()
        {
            return $this->render("");
        }
    
        private static function email_is_valid(string $email) {
            $regex = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
            if (preg_match($regex, $email)) {
                return true;
            } else {
                return false;
            }
        }

        private static function password_is_valid(string $password) {
            return strlen($password) >= 8 && preg_match('/[0-9]/', $password) && preg_match('/[\W_]/', $password);
        }

}









