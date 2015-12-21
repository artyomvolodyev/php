/**
* GNU
**/
package com.flash.mp3player.player.components
{
	import com.flash.mp3player.player.events.SeekEvent;
	
	import flash.display.Sprite;
	import flash.events.MouseEvent;
	import flash.geom.Rectangle;

	public class ProgressSlider extends Sprite
	{
		//properties
		private var spProgressBg:			PlayingProgressBg;
		private var spSongLoadingProgress:	SongLoadingProgress;
		private var spSongPlayingProgress:	SongPlayingProgress;
		private var seekPointer:			SeekPointerAsset;
		
		//constructor
		public function ProgressSlider()
		{
			super();
		}
		
		//methods
		public function Init(bgWidth:uint):void
		{
			this.cacheAsBitmap = true;		
			
			this.spProgressBg = new PlayingProgressBg();
			this.spProgressBg.Init(bgWidth);
			this.addChild(this.spProgressBg);
			
			this.spSongLoadingProgress = new SongLoadingProgress();
			this.spSongLoadingProgress.Init();
			this.spSongLoadingProgress.x = 1;
			this.spSongLoadingProgress.y = 2;
			this.spSongLoadingProgress.addEventListener(MouseEvent.CLICK, this.SeekClickHandler);
			this.addChild(this.spSongLoadingProgress);
			
			this.spSongPlayingProgress = new SongPlayingProgress();
			this.spSongPlayingProgress.Init();
			this.spSongPlayingProgress.x = 1;
			this.spSongPlayingProgress.y = 2;
			this.addChild(this.spSongPlayingProgress);
			
			this.seekPointer = new SeekPointerAsset();	
			this.seekPointer.useHandCursor = true;
			this.seekPointer.buttonMode = true;
			this.seekPointer.y = Math.round( 
				(this.spProgressBg.height - this.seekPointer.height) / 2 );
			this.seekPointer.addEventListener(MouseEvent.MOUSE_DOWN, this.PointerDownHandler);
			this.addChild(this.seekPointer);
		}
		
		public function ShowLoadingProgress(percentage:Number):void
		{
			this.spSongLoadingProgress.Draw(percentage * (this.spProgressBg.width - 2));
		}
		
		public function ShowPlayingProgress(percentage:Number):void
		{
			if(percentage > 1) percentage = 1;
			var playingProgress:uint = percentage * (this.spProgressBg.width - 2);
			this.spSongPlayingProgress.Draw(playingProgress);
			
			this.seekPointer.x = Math.round(
				percentage * (this.spProgressBg.width - this.seekPointer.width) );
		}
		
		public function ShowProgressBar(show:Boolean):void
		{
			this.spSongLoadingProgress.useHandCursor = show;
			this.spSongLoadingProgress.buttonMode = show;
			this.spSongLoadingProgress.mouseEnabled = show;
			
			this.spSongPlayingProgress.visible = show;
			
			this.seekPointer.visible = show;
		}
		
		private function PointerDownHandler(evt:MouseEvent):void
		{
			this.stage.addEventListener(MouseEvent.MOUSE_MOVE, this.PointerMoveHandler);
			this.stage.addEventListener(MouseEvent.MOUSE_UP, this.PointerUpHandler);
			var bounds:Rectangle = new Rectangle(0, this.seekPointer.y,
				this.spProgressBg.width - this.seekPointer.width, 0);
			this.seekPointer.startDrag(false, bounds);
		}
		
		private function PointerUpHandler(evt:MouseEvent):void
		{
			this.seekPointer.stopDrag();
			this.stage.removeEventListener(MouseEvent.MOUSE_MOVE, this.PointerMoveHandler);
			this.stage.removeEventListener(MouseEvent.MOUSE_UP, this.PointerUpHandler);
		}
		
		private function PointerMoveHandler(evt:MouseEvent):void
		{
			var seek:Number = this.seekPointer.x / (this.spProgressBg.width - this.seekPointer.width);
			this.dispatchEvent(new SeekEvent(seek));
		}
		
		private function SeekClickHandler(evt:MouseEvent):void
		{
			if(this.spSongLoadingProgress.mouseEnabled)
			{
				var seek:Number = this.spProgressBg.mouseX / this.spProgressBg.width;
				this.dispatchEvent(new SeekEvent(seek));
			}
		}
	}
}