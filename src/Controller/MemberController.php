<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\TwigFilter;


class MemberController extends AbstractController
{
    #[Route('/member', name: 'app_member')]
    public function index(UserRepository $repository): Response
    {
        $users = $repository->findAll();
        return $this->render('member/index.html.twig', [
            'users' => $users,
        ]);
    }
    #[Route('/member/show', name: 'app_show')]
    public function show(UserRepository $repository) : Response
    {
        
        
        return $this->render('member/show.html.twig');
    }
}  

