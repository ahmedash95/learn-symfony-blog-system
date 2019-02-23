<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Contracts\Cache\CacheInterface;

class HomeController extends AbstractController
{
    public function index()
    {
        $posts = $this->getDoctrine()->getRepository(Post::class)->findAll();

        return $this->render('home/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    public function displayPost(AdapterInterface $cache,$slug)
    {
        $post = $this->getDoctrine()->getRepository(Post::class)->find($slug);

        if (!$post) {
            return $this->redirectToRoute('home');
        }

        $cacheKey = Post::cacheKey.'.'.$post->getId();
        $views = $cache->getItem($cacheKey);
		$views->set(intval($views->get()) + 1);
		$cache->save($views);

        return $this->render('home/single.html.twig', [
            'post' => $post,
			'views' => $views->get()
        ]);
    }

    public function userPosts($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $posts = $this->getDoctrine()->getRepository(Post::class)->findBy([
            'user' => $user,
        ]);

        return $this->render('home/index.html.twig', [
            'posts' => $posts,
        ]);
    }
}
