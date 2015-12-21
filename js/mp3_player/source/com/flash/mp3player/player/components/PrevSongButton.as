/**
* GNU
**/
package com.flash.mp3player.player.components
{
	import com.flash.mp3player.player.events.PrevClickEvent;
	
	import flash.display.Sprite;
	import flash.events.MouseEvent;

	public class PrevSongButton extends Sprite
	{
		//properties
		private var spBg:	ButtonBgAsset;
		private var spPrev:	PrevButtonAsset;
		
		//constructor
		public function PrevSongButton()
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
						
			this.spPrev = new PrevButtonAsset();
			this.spPrev.x = (this.spBg.width - this.spPrev.width) / 2;
			this.spPrev.y = (this.spBg.height - this.spPrev.height) / 2;
			this.addChild(this.spPrev);
		}
		
		private function ClickHandler(evt:MouseEvent):void
		{
			this.dispatchEvent(new PrevClickEvent());
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