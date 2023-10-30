<?php

namespace App\Controllers;

use App\Services\YouTubeService;
use Pmguru\Framework\Controllers\AbstractController;
use Pmguru\Framework\Http\Response;

class HomeController extends AbstractController
{
	
	public function __construct(
		private readonly YouTubeService $youTubeService,
	)
	{
	}
	
	public function index()
	: Response
	{
		return $this->render( 'home.html.twig', [
			'youTubeChannel' => $this->youTubeService->getChannelUrl(),
		] );
	}
	
}