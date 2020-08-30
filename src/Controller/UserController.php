<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserCreateType;
use App\Form\UserPasswordUpdateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/** 
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/create-account", name="user_account_create")
     */
    public function createAccount(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();

        $form = $this->createForm(UserCreateType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            // avant d'enregistrer en BDD on va encoder le mot de passe
            // je recupère dans le formulaire la valeur du champs password
            // ce champs contient le mot de passe non encrypté
            $plainPassword = $form->get('password')->getData();
            // je demande au service d'encodage de crypter mon mot de passe
            $encodedPassword = $passwordEncoder->encodePassword($user, $plainPassword); 
            // je remplace dans mon objet $user le mot de passe non crypté par celui qui est crypté
            $user->setPassword($encodedPassword);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();

            // on pourrai envoyer un evenement custom du genre "app.create_account"
            // puis on pourra creer un service qui ecoute cet evenement et envoi 
            // un email de validation du compte

            $this->addFlash("success", "Votre compte a bien été créé ! Merci de vous authentifier.");
            return $this->redirectToRoute('app_login');
        }
        
        return $this->render(
            'user/create-account.html.twig',
            [
                "form" => $form->createView()
            ]
        );
    }

    /**
     * @Route("/change-password", name="user_password_change")
     * @IsGranted("ROLE_USER")
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $passwordEncoder) 
    {
        // je demande à Symfony de me donner l'utilisateur actuelement connecté grace a la methode suivante :
        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(UserPasswordUpdateType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            // je recupère la valeur du champs (non mappé) 'newPassword' pour la traiter (encoder)
            // je recupère dans mon formulaire le champs qui contient le nouveau mot de passe 
            $plainPassword = $form->get('newPassword')->getData();
            // je demande au service d'encodage de crypter mon mot de passe
            $encodedPassword = $passwordEncoder->encodePassword($user, $plainPassword); 
            // je remplace dans mon objet $user l'ancien mot de passe par celui qui est crypté
            $user->setPassword($encodedPassword);

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("success", "Le mot de passe à bien été modifié");
            return $this->redirectToRoute("homepage");
        }
        
        return $this->render(
            'user/change-password.html.twig',
            [
                "form" => $form->createView()
            ]
        );
    }
}
