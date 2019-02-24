<?php

namespace App\Events;


use App\Entity\Post;
use Symfony\Component\EventDispatcher\Event;

class PostUpdatedEvent extends Event
{
	const NAME = 'post.update';

	/**
	 * @var Post
	 */
	public $post;

	/**
	 * PostUpdatedEvent constructor.
	 * @param Post $post
	 */
	public function __construct(Post $post)
	{
		$this->post = $post;
	}
}