<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function index()
    {
    	$posts = $this->getDoctrine()->getRepository(Post::class)->findAll();

        return $this->render('home/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    public function displayPost($slug){
		$post = $this->getDoctrine()->getRepository(Post::class)->find($slug);

		if(!$post) {
			return $this->redirectToRoute('home');
		}

		return $this->render('home/single.html.twig', [
			'post' => $post,
		]);
	}

    public function userPosts($id){
    	$user = $this->getDoctrine()->getRepository(User::class)->find($id);
		$posts = $this->getDoctrine()->getRepository(Post::class)->findBy([
			'user' => $user
		]);

		return $this->render('home/index.html.twig', [
			'posts' => $posts,
		]);
	}
}
