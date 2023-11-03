<?php

return new class {
	
	public function up()
	: void
	{
		echo get_class( $this ) . ' method UP' . PHP_EOL;
	}
	
	public function down()
	: void
	{
		echo get_class( $this ) . ' method DOWN' . PHP_EOL;
	}
	
};