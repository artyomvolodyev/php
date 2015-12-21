/**
* GNU
**/
package com.flash.mp3player.player.components
{	
	import flash.display.Sprite;

	public class SongPlayingProgress extends Sprite
	{
		//properties
		private var spProgress:		Sprite;
		
		//constructor
		public function SongPlayingProgress()
		{
			super();
		}
		
		//methods
		public function Init():void
		{
			this.cacheAsBitmap = true;
			this.mouseEnabled = false;
			
			this.spProgress = new Sprite();
			this.spProgress.mouseEnabled = false;
			this.addChild(this.spProgress);
		}
		
		public function Draw(progressWidth:uint):void
		{
			this.spProgress.graphics.clear();
			this.spProgress.graphics.lineStyle(1, 0x12B7E2);
			this.spProgress.graphics.beginFill(0x096077, 0.5);
			this.spProgress.graphics.drawRect(0, 0, progressWidth, 3);
		}
	}
}