/**
* GNU
**/
package com.flash.mp3player.player.components
{
	import com.flash.mp3player.player.events.PlayPauseClickEvent;
	
	import flash.display.Sprite;
	import flash.events.MouseEvent;
	
	public class PlayPauseButton extends Sprite
	{
		//properties
		private var spBg:		ButtonBgAsset;
		private var spPlay:		PlayButtonAsset;		
		private var spPause:	PauseButtonAsset;
		
		//constructor
		public function PlayPauseButton()
		{
			super();
		}
		
		//methods
		public function Init():void
		{
			this.cacheAsBitmap = true;
			this.useHandCursor = true;
			this.buttonMode = true;
			this.addEventListener(MouseEvent.CLICK, this.ClickHandler);
			this.addEventListener(MouseEvent.MOUSE_OVER, this.OverHandler);
			this.addEventListener(MouseEvent.MOUSE_OUT, this.OutHandler);
			
			this.spBg = new ButtonBgAsset();
			this.spBg.visible = false;
			this.addChild(this.spBg);	
			
			this.spPlay = new PlayButtonAsset();
			this.spPlay.x = (this.spBg.width - this.spPlay.width) / 2;
			this.spPlay.y = (this.spBg.height - this.spPlay.height) / 2;
			this.addChild(this.spPlay);
			
			this.spPause = new PauseButtonAsset();
			this.spPause.visible = false;		
			this.spPause.x = (this.spBg.width - this.spPause.width) / 2;
			this.spPause.y = (this.spBg.height - this.spPause.height) / 2;			
			this.addChild(this.spPause);
		}
		
		public function Play():void
		{
			this.spPlay.visible = false;
			this.spPause.visible = true;
		}
		
		public function Pause():void
		{
			this.spPlay.visible = true;
			this.spPause.visible = false;
		}
		
		private function ClickHandler(evt:MouseEvent):void
		{
			this.dispatchEvent(new PlayPauseClickEvent());	
		}
		
		private function OverHandler(evt:MouseEvent):void
		{
			this.spBg.visible = true;
		}
		
		private function OutHandler(evt:MouseEvent):void
		{
			this.spBg.visible = false;
		}
	}
}