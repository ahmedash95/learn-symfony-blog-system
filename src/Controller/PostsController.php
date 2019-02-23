<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class PostsController extends AbstractController
{
    public function index(UserInterface $user)
    {
		$posts = $user->getPosts();

        return $this->render('posts/index.html.twig', [
			'posts' => $posts
        ]);
    }

    public function create(){
    	return $this->render('posts/create.html.twig');
	}

	public function store(Request $request,UserInterface $user){
		$submittedToken = $request->request->get('token');
		if (!$this->isCsrfTokenValid('save_post', $submittedToken)) {
			die('invalid Token');
		}

    	$post = new Post();
    	$post->setTitle($request->request->get('title'));
    	$post->setContent($request->request->get('content'));
    	$post->setIsPublished(true);
    	$post->setUser($user);

    	$em = $this->getDoctrine()->getManager();
    	$em->persist($post);
    	$em->flush();

    	return $this->redirectToRoute('posts');
	}

	public function edit(UserInterface $user, $id){
    	$post = $this->getDoctrine()->getRepository(Post::class)->findOneBy([
    		'id' => $id,
			'user' => $user
		]);

    	return $this->render('posts/edit.html.twig',[
    		'post' => $post,
		]);
	}

	public function update(Request $request,$id){
    	$submittedToken = $request->request->get('token');
		if (!$this->isCsrfTokenValid('update_post', $submittedToken)) {
			die('invalid Token');
		}

		$post = $this->getDoctrine()->getRepository(Post::class)->find($id);
		$post->setTitle($request->request->get('title'));
		$post->setContent($request->request->get('content'));
		$post->setIsPublished(true);

		$em = $this->getDoctrine()->getManager();
		$em->persist($post);
		$em->flush();

		return $this->redirectToRoute('posts');
	}

	public function delete(Request$request,UserInterface $user,$id){
		$submittedToken = $request->request->get('token');
		if (!$this->isCsrfTokenValid('delete_post', $submittedToken)) {
			die('invalid Token');
		}

    	$post = $this->getDoctrine()->getRepository(Post::class)->findOneBy([
			'id' => $id,
			'user' => $user
		]);

    	$em = $this->getDoctrine()->getManager();
    	$em->remove($post);
		$em->flush();

    	return $this->redirectToRoute('posts');
	}
}
