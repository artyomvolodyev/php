/**
* GNU
**/
package com.flash.mp3player.player.components
{
	import com.flash.mp3player.player.events.NextClickEvent;
	
	import flash.display.Sprite;
	import flash.events.MouseEvent;

	public class NextSongButton extends Sprite
	{
		//properties
		private var spBg:	ButtonBgAsset;
		private var spNext:	NextButtonAsset;
		
		//constructor
		public function NextSongButton()
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
						
			this.spNext = new NextButtonAsset();
			this.spNext.x = (this.spBg.width - this.spNext.width) / 2;
			this.spNext.y = (this.spBg.height - this.spNext.height) / 2;
			this.addChild(this.spNext);
		}
		
		private function ClickHandler(evt:MouseEvent):void
		{
			this.dispatchEvent(new NextClickEvent());
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