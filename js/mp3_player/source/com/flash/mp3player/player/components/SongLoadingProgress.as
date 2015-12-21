/**
* GNU
**/
package com.flash.mp3player.player.components
{
	import flash.display.Sprite;

	public class SongLoadingProgress extends Sprite
	{
		//properties
		private var spProgress:	Sprite;
		
		//constructor
		public function SongLoadingProgress()
		{
			super();
		}
		
		//methods
		public function Init():void
		{
			this.cacheAsBitmap = true;
			this.useHandCursor = true;
			this.buttonMode = true;
			
			this.spProgress = new Sprite();
			this.addChild(this.spProgress);
		}
		
		public function Draw(progressWidth:uint):void
		{
			this.spProgress.graphics.clear();
			this.spProgress.graphics.lineStyle(1, 0xffffff, 0.6);
			this.spProgress.graphics.beginFill(0x333333, 0.75);
			this.spProgress.graphics.drawRoundRect(0, 0, progressWidth, 3, 2, 2);
		}
	}
}