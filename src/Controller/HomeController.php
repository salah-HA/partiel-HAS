<?php

namespace App\Controller;

use App\Entity\Editor;
use App\Entity\Users;
use App\Entity\VideoGame;
use App\Form\UsersType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\User;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $videoGames= $this->getDoctrine()->getRepository(VideoGame::class)->findAll();
        $editors= $this->getDoctrine()->getRepository(Editor::class)->findAll();
        dump($videoGames);
        return $this->render('home/index.html.twig', [
            "videoGames"=>$videoGames,
            "editors"=>$editors,
        ]);
    }
    /**
     * @Route("/articles/{id}", name="articles")
     */
    public function showArticlesDetail(VideoGame $videoGame){
        return $this->render('detail/detail.html.twig',[
            'videoGame'=> $videoGame
        ]);
    }
    /**
     * @Route("/editor/{id}", name="editor")
     */
    public function showEditorDetail(Editor $editor){
        return $this->render('detail/detailEditor.html.twig',[
            'editor'=> $editor
        ]);
    }
}
