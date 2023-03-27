<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Entity\User;
use App\Form\PublicateType;
use App\Repository\PublicationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
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
    public function show(PublicationRepository $repository) : Response
    {
        
        $publications= $repository->findAll();
        return $this->render('member/show.html.twig', [
            'publications' => $publications,

        ]);
    }
    #[Route('/member/profil', name: 'app_profil')]
    #[IsGranted('ROLE_USER')]
    public function profil(Request $request, EntityManagerInterface $manager) : Response
    {
        $publication = new Publication();
        $form = $this->createForm(PublicateType::class, $publication);
        $form ->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form['content']->getData();
            // Configurer la date de création et le créateur du commentaire (l'utilisateur connecté)
            $publication->setContent($data);
            $publication->setCreatedAt(new \DateTimeImmutable());
            $publication->setUser($this->getUser());

            $manager->persist($publication);
            $manager->flush();

            return $this->redirectToRoute('app_home');
        }    
        return $this->render('member/profil.html.twig', [
            'publication'=> $publication,
            'form'=> $form,
        ]);
    }
    #[Route('/member/edit', name:'app_edit')]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request,EntityManagerInterface $entityManager, UserRepository $repository) : Response
    {
        $user = $this->getUser();   
        $form = $this->createForm(EditType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('')
        }

    }
}  

