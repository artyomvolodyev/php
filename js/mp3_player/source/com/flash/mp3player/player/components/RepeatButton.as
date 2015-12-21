/**
* GNU
**/
package com.flash.mp3player.player.components
{
	import com.flash.mp3player.player.events.RepeatClickEvent;
	
	import flash.display.Sprite;
	import flash.events.MouseEvent;

	public class RepeatButton extends Sprite
	{
		//properties
		private var spBg:			ButtonBgAsset;
		private var spRepeatOn:		RepeatOnAsset;
		private var spRepeatOff:	RepeatOffAsset;
		
		//constructor
		public function RepeatButton()
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
			
			this.spRepeatOn = new RepeatOnAsset();
			this.spRepeatOn.x = (this.spBg.width - this.spRepeatOn.width) / 2;
			this.spRepeatOn.y = (this.spBg.height - this.spRepeatOn.height) / 2;
			this.addChild(this.spRepeatOn);
			
			this.spRepeatOff = new RepeatOffAsset();
			this.spRepeatOff.visible = false;
			this.spRepeatOff.x = (this.spBg.width - this.spRepeatOff.width) / 2;
			this.spRepeatOff.y= (this.spBg.height - this.spRepeatOff.height) / 2;
			this.addChild(this.spRepeatOff);
		}	
		
		public function TurnOn():void
		{
			this.spRepeatOff.visible = false;
			this.spRepeatOn.visible = true;
		}
		
		public function TurnOff():void
		{
			this.spRepeatOff.visible = true;
			this.spRepeatOn.visible = false;
		} 
		
		private function ClickHandler(evt:MouseEvent):void
		{
			var repeat:Boolean = false;
			if(this.spRepeatOff.visible)
			{
				repeat = true;
			}	
			this.dispatchEvent(new RepeatClickEvent(repeat));
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