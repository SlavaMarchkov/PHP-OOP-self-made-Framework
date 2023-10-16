<?php

namespace Pmguru\Framework\Tests;

class Pmguru
{
	
	public function __construct(
		private readonly Telegram $telegram,
		private readonly YouTube  $youTube
	)
	{
	}
	
	/**
	 * @return Telegram
	 */
	public function getTelegram()
	: Telegram
	{
		return $this->telegram;
	}
	
	/**
	 * @return YouTube
	 */
	public function getYouTube()
	: YouTube
	{
		return $this->youTube;
	}
	
}