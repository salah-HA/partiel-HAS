<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UsersType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class UserController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function index(Request $request, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //$entityManager->persist($user);
            $entityManager->flush();
        }
        return $this->render('user/profile.html.twig', [
            'user' => $user, 'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/register", name="register", methods="GET|POST")
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new Users();
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest(($request));

        if ($form->isSubmitted()&& $form->isValid()){
            $entityManager = $this-> getDoctrine()->getManager();
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }
        return $this->render('user/register.html.twig',[
            'form'=> $form->createView()
        ]);
    }
}
