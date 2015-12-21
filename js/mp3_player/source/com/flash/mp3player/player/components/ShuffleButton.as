/**
* GNU
**/
package com.flash.mp3player.player.components
{
	import com.flash.mp3player.player.events.ShuffleClickEvent;
	
	import flash.display.Sprite;
	import flash.events.MouseEvent;

	public class ShuffleButton extends Sprite
	{
		//properties
		private var spBg:			ButtonBgAsset;
		private var spShuffleOff:	ShuffleOffAsset;
		private var spShuffleOn:	ShuffleOnAsset;
		
		//constructor
		public function ShuffleButton()
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
						
			this.spShuffleOff = new ShuffleOffAsset();
			this.spShuffleOff.x = (this.spBg.width - this.spShuffleOff.width) / 2;
			this.spShuffleOff.y = (this.spBg.height - this.spShuffleOff.height) / 2;
			this.addChild(this.spShuffleOff);
			
			this.spShuffleOn = new ShuffleOnAsset();
			this.spShuffleOn.visible = false;
			this.spShuffleOn.x = (this.spBg.width - this.spShuffleOn.width) / 2;
			this.spShuffleOn.y = (this.spBg.height - this.spShuffleOn.height) / 2; 			
			this.addChild(this.spShuffleOn);
		}
		
		private function ClickHandler(evt:MouseEvent):void
		{
			this.spShuffleOff.visible = !this.spShuffleOff.visible;
			this.spShuffleOn.visible = !this.spShuffleOn.visible;
			
			this.dispatchEvent(new ShuffleClickEvent());
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