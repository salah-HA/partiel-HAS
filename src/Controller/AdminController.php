<?php

namespace App\Controller;

use App\Entity\Editor;
use App\Entity\Users;
use App\Entity\VideoGame;
use App\Form\EditorType;
use App\Form\UsersType;
use App\Form\VideoGameType;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Curl\User;
use phpDocumentor\Reflection\DocBlock\Tags\Property;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(Request $request, EntityManagerInterface $entityManager)
    {
        $admin = $this->getUser();
        $form = $this->createForm(UsersType::class, $admin);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //$entityManager->persist($user);
            $entityManager->flush();
        }
        return $this->render('admin/profile.html.twig', [
            'admin' => $admin, 'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/register-admin", name="register-admin", methods="GET|POST")
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
            $user->setRoles('ROLE_ADMIN');
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }
        return $this->render('admin/register.html.twig',[
            'form'=> $form->createView()
        ]);
    }
    /**
     * @Route("/create-article", name="create-article")
     * @IsGranted("ROLE_ADMIN")
     */
    public function createVideoGame(Request $request, EntityManagerInterface $entityManager){
        $videoGame = new VideoGame();
        $form = $this->createForm(VideoGameType::class, $videoGame);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($videoGame);
            $entityManager->flush();
        }
        return $this->render('admin/create-article.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/modify-article/{id}", name="modify-article")
     * @IsGranted("ROLE_ADMIN")
     */
    public function modifyVideoGame(Request $request, VideoGame $videoGame, EntityManagerInterface $entityManager){

        $form = $this->createForm(VideoGameType::class, $videoGame);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //$entityManager->persist($videoGame);
            $entityManager->flush();
        }
        return $this->render('admin/modify-article.html.twig', [
            'form' => $form->createView(),
            'article'=>$videoGame,
        ]);
    }
    /**
     * @Route("/delete-article/{id}", name="delete-article")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteVideoGame(Request $request, VideoGame $videoGame, EntityManagerInterface $entityManager){

        if($this->isCsrfTokenValid('delete'.$videoGame->getId(), $request->get('token1'))){
            $entityManager->remove($videoGame);
            $entityManager->flush();
        }
        return $this->redirectToRoute("home");
    }

    /**
     * @Route("/create-editor", name="create-editor")
     * @IsGranted("ROLE_ADMIN")
     */
    public function createEditor(Request $request, EntityManagerInterface $entityManager){
        $editor = new Editor();
        $form = $this->createForm(EditorType::class, $editor);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($editor);
            $entityManager->flush();
        }
        return $this->render('admin/create-editor.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/modify-editor/{id}", name="modify-editor")
     * @IsGranted("ROLE_ADMIN")
     */
    public function modifyEditor(Request $request, Editor $editor, EntityManagerInterface $entityManager){

        $form = $this->createForm(EditorType::class, $editor);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //$entityManager->persist($videoGame);
            $entityManager->flush();
        }
        return $this->render('admin/modify-editor.html.twig', [
            'form' => $form->createView(),
            'editor'=>$editor,
        ]);
    }
    /**
     * @Route("/delete-editor/{id}", name="delete-editor")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteEditor(Request $request, Editor $editor, EntityManagerInterface $entityManager){

        if($this->isCsrfTokenValid('delete'.$editor->getId(), $request->get('token2'))){
            $entityManager->remove($editor);
            $entityManager->flush();
        }
        return $this->redirectToRoute("home");
    }
    /**
     * @Route("/user-list", name="user-list")
     * @IsGranted("ROLE_ADMIN")
     */
    public function showUserDetail(){
        $users= $this->getDoctrine()->getRepository(Users::class)->findAll();
        return $this->render('admin/user-list.html.twig',[
            'users'=> $users
        ]);
    }
    /**
     * @Route("/create-user", name="create-user")
     * @IsGranted("ROLE_ADMIN")
     */
    public function createUser(Request $request, EntityManagerInterface $entityManager){
        $user = new Users();
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Utilisateur a bien etait créer');
        }
        return $this->render('user/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/modify-user/{id}", name="modify-user")
     * @IsGranted("ROLE_ADMIN")
     */
    public function modifyUser(Request $request, Users $user, EntityManagerInterface $entityManager){

        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //$entityManager->persist($videoGame);
            $entityManager->flush();
            $this->addFlash('modified', 'Utilisateur a bien etait modifier');
        }
        return $this->render('admin/modify-user.html.twig', [
            'form' => $form->createView(),
            'id'=>$user->getId(),
        ]);
    }
    /**
     * @Route("/delete-user/{id}", name="delete-user")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteUser(Request $request, Users $user, EntityManagerInterface $entityManager){

        if($this->isCsrfTokenValid('delete'.$user->getId(), $request->get('token3'))){
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('deleted', 'Utilisateur a bien etait modifier');
        }
        return $this->redirectToRoute("home");
    }

}
