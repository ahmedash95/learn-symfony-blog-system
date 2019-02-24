<?php
namespace App\Events;

use App\Entity\Post;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PostUpdateListener implements EventSubscriberInterface
{
	/**
	 * @var AdapterInterface
	 */
	private $cache;

	/**
	 * PostUpdateListener constructor.
	 * @param AdapterInterface $cache
	 */
	public function __construct(AdapterInterface $cache)
	{

		$this->cache = $cache;
	}

	public static function getSubscribedEvents()
	{
		return [
			PostUpdatedEvent::NAME => 'updatePostCache'
		];
	}

	public function updatePostCache(PostUpdatedEvent $event){
		$cachedPost = $this->cache->getItem(Post::cacheContentKey.$event->post->getId());
		$cachedPost->set($event->post);
		$this->cache->save($cachedPost);
	}
}