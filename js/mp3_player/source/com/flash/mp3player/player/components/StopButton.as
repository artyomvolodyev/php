/**
* GNU
**/
package com.flash.mp3player.player.components
{
	import com.flash.mp3player.player.events.StopClickEvent;
	
	import flash.display.Sprite;
	import flash.events.MouseEvent;
	
	public class StopButton extends Sprite
	{
		//properties
		private var spBg:	ButtonBgAsset;
		private var spStop:	StopAsset;
		
		//constructor
		public function StopButton()
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
			
			this.spStop = new StopAsset();
			this.spStop.x = (this.spBg.width - this.spStop.width) / 2;
			this.spStop.y = (this.spBg.height - this.spStop.height) / 2; 
			this.addChild(this.spStop);
		}
		
		private function ClickHandler(evt:MouseEvent):void
		{
			this.dispatchEvent(new StopClickEvent());
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