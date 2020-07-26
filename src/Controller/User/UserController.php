<?php

namespace App\Controller\User;

use App\Form\User\EditUserType;
use App\Security\EmailVerifier;
use App\Service\Email\EmailConfirmationServiceInterface;
use App\Service\User\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class UserController extends AbstractController
{
    private $userService;
    private $emailVerifier;
    private $emailConfirmationService;

    public function __construct(UserServiceInterface $userService, 
                                EmailVerifier $emailVerifier, 
                                EmailConfirmationServiceInterface $emailConfirmationService)
    {
        $this->userService = $userService;
        $this->emailVerifier = $emailVerifier;
        $this->emailConfirmationService = $emailConfirmationService;
    }
    /**
     * @Route("/user/profile", name="user_profile")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function profile()
    {
        $user = $this->userService->currentUser();
        $isVerified = $user->isVerified();
        return $this->render('user/profile.html.twig', [
            'user' => $user,
            'isVerified' => $isVerified
        ]);
    }

    /**
     * @Route("/user/edit/profile", name="user_edit_profile")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function editUser(Request $request)
    {
        $user = $this->userService->currentUser();
        $oldEmail = $user->getEmail();
        
        $form = $this->createForm(EditUserType::class, $user);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $newEmail = $form->get('email')->getData();
            if($oldEmail !== $newEmail) {
                $user->setIsVerified(false);
                $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                $this->emailConfirmationService->emailContent($user));
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_profile');
        }

        return $this->render('user/edit.html.twig', [
            'editUserForm' => $form->createView(),
        ]);
        
    }    
}
