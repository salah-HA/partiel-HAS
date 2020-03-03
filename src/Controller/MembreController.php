<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Membre;
use App\Entity\Users;
use App\Form\ArticleType;
use App\Form\MembreType;
use App\Form\UsersType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MembreController extends AbstractController
{
    /**
     * @Route("/membre", name="membre")
     */
    public function index()
    {
        return $this->render('membre/index.html.twig', [
            'controller_name' => 'MembreController',
        ]);
    }

    /**
     * @Route("/membre-register", name="member-register", methods="GET|POST")
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $member = new Membre();
        $form = $this->createForm(MembreType::class, $member);
        $form->handleRequest(($request));

        if ($form->isSubmitted()&& $form->isValid()){
            $entityManager = $this-> getDoctrine()->getManager();
            $password = $passwordEncoder->encodePassword($member, $member->getPassword());
            $member->setPassword($password);
            $entityManager->persist($member);
            $entityManager->flush();

            return $this->redirectToRoute('membre_index');
        }
        return $this->render('membre/register.html.twig',[
            'form'=> $form->createView()
        ]);
    }

    /**
     * @Route("/create-article", name="create-article")
     * @IsGranted("ROLE_MEMBRE")
     */
    public function createArticle(Request $request, EntityManagerInterface $entityManager){
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();
        }
        return $this->render('admin/create-article.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
