<?php

namespace App\Controller\User;

use App\Service\User\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }
    /**
     * @Route("/", name="app_home")
     */
    public function index()
    {
        $isVerified = $this->userService->isVerified();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'isVerified' => $isVerified
        ]);
    }
}
