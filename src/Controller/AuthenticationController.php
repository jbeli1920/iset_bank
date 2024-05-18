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
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


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
            } else {
                if ($utilisateur_a_connecter->get_email_confirme() == 0) return $this->redirect("http://localhost:8000/authentication/verif_email/" . $utilisateur_a_connecter->getEmail());
                return new Response("Yes");
            }
            
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
            $utilisateur->setCodeConfirmationEmail($this->generate_code());
            $utilisateur->set_code_changement_password("none");
            $utilisateur->set_email_confirme(0);
            $form = $this->createFormBuilder($utilisateur)
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('email', TextType::class)
            ->add('mot_de_passe', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Inscrivez-vous'])
            ->getForm();
          
           $errors = array("nom" => "", "prenom" => "", "email" => "", "password" => "");

            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                $data = $form->getData();
                if (strlen($data->getNom()) < 3 || strlen($data->getNom()) > 10) {
                    $errors["nom"] = "Nom doit etre entre 3 et 10 caractères";
                }
                if (strlen($data->getPrenom()) < 3 || strlen($data->getPrenom()) > 10) {
                    $errors["prenom"] = "Prenom doit etre entre 3 et 10 caractères";
                }
                if (!$this->email_is_valid($data->getEmail())) {
                    $errors["email"] = "Email est invalide";
                }
                if (!$this->password_is_valid($data->getMotDePasse())) {
                    $errors["password"] = "Le mot de passe doit avoir au moins 8 caractères et contenir un chiffre et un symbole";
                }
                if (strlen($errors["email"]) > 0 || strlen($errors["password"]) > 0 || strlen($errors["nom"]) > 0 || strlen($errors["prenom"]) > 0) return $this->render("authentication/signup.html.twig", [
                    'form' => $form->createView(),
                    'errors' => $errors,
                ]);

                $emailExiste = $this->getDoctrine()->getRepository(Utilisateur::class)
                ->findOneBy(array("email" => $data->getEmail()));
                if ($emailExiste) return $this->render("authentication/signup.html.twig", [
                    "form" => $form->createView(),
                    'errors' => $errors,
                    "signup_error" => "Cet email deja existe"
                    ]);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($data);
                $entityManager->flush();
                return $this->redirect("http://localhost:8000/authentication/verif_email/" . $data->getEmail());
            }
            else return $this->render("authentication/signup.html.twig", [
                "form" => $form->createView(),
                'errors' => $errors,
            ]);
        }
          /**
         * @Route("/authentication/reset_password")
         */
        public function reset_password(Request $request, MailerInterface $mailer)
        {
            $utilisateur = new Utilisateur();

            $form = $this->createFormBuilder($utilisateur)
                ->add('email', TextType::class)
                ->add('save', SubmitType::class, ['label' => 'Envoyer lien de recuperation'])
                ->getForm();

            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                // verify if email is valid
                if (!$this->email_is_valid($utilisateur->getemail())) {
                    return $this->render("authentication/reset-password.html.twig", [
                        'form' => $form->createView(),
                        "email_invalide" => "email est invalide"
                    ]);
                }
                // verify if email exists
                $entityManager = $this->getDoctrine()->getManager();
                $utilisateur_pour_recuperer = $entityManager
                    ->getRepository(Utilisateur::class)
                    ->findOneBy(array('email'=> $utilisateur->getemail()));
                if (!$utilisateur_pour_recuperer) return $this->render("authentication/reset-password.html.twig", [
                        'form' => $form->createView(),
                        "email_introuvable" => "Nous n'avons trouvé aucun compte avec cette adresse e-mail."
                    ]);
                // create a new recovery code
                $code = $this->generate_code();
                $utilisateur_pour_recuperer->set_code_changement_password($code);
                $entityManager->flush();
                // send the reset email

                $template = $this->renderView('authentication/reset-template.html.twig', [
                'code' => $code
                ]);

                $mailer->send($this->create_recover_email($utilisateur->getemail(), $template));

                return $this->render("authentication/reset-password.html.twig", [
                        'form' => $form->createView(),
                        "succee" => "Un lien de récupération a été envoyé à votre adresse e-mail."
                    ]);
                // return the page with success message
            }
            return $this->render("authentication/reset-password.html.twig", [
                'form' => $form->createView(),
            ]);
        }

          /**
         * @Route("/recuperer-password/{code}")
         */
        public function verif(string $code, Request $request)
        {

            // check if code is correct

            $utilisateur = new Utilisateur();

            $form = $this->createFormBuilder($utilisateur)
                ->add('mot_de_passe', TextType::class)
                ->add('save', SubmitType::class, ['label' => 'Modifier mot de passe'])
                ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                // modify user password
            }

            return $this->render("authentication/recuperer-password.html.twig", [
                "form" => $form->createView()
            ]);
        }

        /**
         * @Route("/authentication/verif_email/{email}")
         */
        public function send_verif_email(string $email, MailerInterface $mailer)
        {
            $utilisateur = new Utilisateur();
            $utilisateur->setEmail($email);
            
            // verify if email is valid
            $utilisateur = $this->getDoctrine()
            ->getRepository(Utilisateur::class)
            ->findOneBy(array('email'=> $utilisateur->getEmail()));
            if (!$utilisateur) return $this->render("authentication/verification.html.twig", [
                "email" => $email,
                "erreur_email" => "Il n'y a aucun compte créé avec cette adresse e-mail."
            ]);

            if ($utilisateur->get_email_confirme() != 0) return $this->render("authentication/verification.html.twig", [
                "email" => $email,
                "erreur_email" => "Cet email est deja verifié"
            ]);
            $verification_code = $utilisateur->getCodeConfirmationEmail();
            $template = $this->renderView('authentication/verification-template.html.twig', [
                'email' => $email,
                'code' => $verification_code
            ]);
            // send email to user
            $mailer->send($this->create_verification_email($email, $template));

            return $this->render("authentication/verification.html.twig", [
                "email" => $email
            ]);
        }

        /**
         * @Route("/verifier-email/{code}")
         */
        public function verifier_email(string $code) {
            // check if the code is correct
            $utilisateur = new Utilisateur();
            
            // verify if email is valid
            $entityManager = $this->getDoctrine()->getManager();
            $utilisateur = $entityManager
            ->getRepository(Utilisateur::class)
            ->findOneBy(array('code_confirmation_email'=> $code));
            if (!$utilisateur) return $this->render("authentication/verifier-email.html.twig", [
                "erreur" => "Le lien de confirmation de email est incorrect."
            ]);

            // set the email as verified in the database
            $utilisateur->set_email_confirme(1);
            $utilisateur->setCodeConfirmationEmail("");
            $entityManager->flush();
            // return success message
            return $this->render("authentication/verifier-email.html.twig");
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

        private static function create_verification_email(string $email, $template) {

            $email = (new Email())
            ->from("no-reply@demomailtrap.com")
            ->to($email)
            ->subject('Verifier votre email ISET BANK')
            ->html($template);
            return $email;
        }

        private static function create_recover_email(string $email, $template) {

            $email = (new Email())
            ->from("no-reply@demomailtrap.com")
            ->to($email)
            ->subject('Recuperer votre compte ISET BANK ')
            ->html($template);
            return $email;
        }

        private static function generate_code() {
            return substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 12);
        }


}