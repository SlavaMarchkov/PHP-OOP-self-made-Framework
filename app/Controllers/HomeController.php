<?php

namespace App\Controllers;

use App\Services\YouTubeService;
use Pmguru\Framework\Http\Response;

class HomeController
{
	
	public function __construct(
		private readonly YouTubeService $youTubeService,
	)
	{
	}
	
	public function index()
	: Response
	{
		$content = '<h1>Hello, World!</h1>';
		$content .= '<a href="' . $this->youTubeService->getChannelUrl() . '">YouTube Channel</a>';
		return new Response( $content );
	}
	
}