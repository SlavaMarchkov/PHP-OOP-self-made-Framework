<?php

namespace App\Controllers;

use App\Entities\Post;
use App\Services\PostService;
use Pmguru\Framework\Controllers\AbstractController;
use Pmguru\Framework\Http\Exceptions\NotFoundException;
use Pmguru\Framework\Http\Request;
use Pmguru\Framework\Http\Response;

class PostController extends AbstractController
{
	
	public function __construct(
		private PostService $service
	)
	{
	}
	
	public function show( int $id )
	: Response
	{
		try {
			$post = $this->service->findOrFail( $id );
			return $this->render( 'posts.html.twig', [
				'post' => $post
			] );
		} catch ( NotFoundException $e ) {
			return $this->render( '404.html.twig', [
				'message' => $e->getMessage(),
			] );
		}
	}
	
	public function create()
	: Response
	{
		return $this->render( 'create_post.html.twig' );
	}
	
	public function store()
	{
		$post = Post::create(
			$this->request->postData['title'],
			$this->request->postData['body'],
		);
		$post = $this->service->save( $post );
		dd( $post );
	}
	
}