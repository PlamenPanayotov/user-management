<?php

namespace App\Controller\User;

use App\Form\User\EditPasswordType;
use App\Service\User\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class SecurityController extends AbstractController
{
    private $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/user/edit/password", name="user_edit_password")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function editPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->userService->currentUser();
        
        $form = $this->createForm(EditPasswordType::class, $user);
        
        $form->handleRequest($request);
        $old_pwd = $form->get('oldPassword')->getData(); 
        if($form->isSubmitted() && $form->isValid()) {
            $checkPass = $passwordEncoder->isPasswordValid($user, $old_pwd);
            
            if($checkPass === true) {
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirectToRoute('user_profile');
            } else {
                $this->addFlash('error', 'Wrong old password');
                return $this->redirectToRoute('user_edit_password');
            }
        }
        return $this->render('user/edit_password.html.twig', [
            'editPasswordForm' => $form->createView(),
            'old' => $old_pwd
        ]);
    }
}
