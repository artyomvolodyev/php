/**
* GNU
**/
package com.flash.mp3player.player.components
{
	import com.flash.mp3player.player.events.MuteClickEvent;
	
	import flash.display.Sprite;
	import flash.events.MouseEvent;

	public class MuteButton extends Sprite
	{		
		//properties
		private var spMuteOn:	MuteOnAsset;
		private var spMuteOff:	MuteOffAsset;
		
		//constructor
		public function MuteButton()
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
			
			this.spMuteOn = new MuteOnAsset()
			this.spMuteOn.visible = false;
			this.addChild(this.spMuteOn);
			
			this.spMuteOff = new MuteOffAsset();
			this.addChild(this.spMuteOff);
		}
		
		public function Mute():void
		{
			this.spMuteOn.visible = true;
			this.spMuteOff.visible = false;
		}
		
		public function Unmute():void
		{
			this.spMuteOn.visible = false;
			this.spMuteOff.visible = true;
		}
		
		private function ClickHandler(evt:MouseEvent):void
		{		
			var mute:Boolean = false;
			if(this.spMuteOff.visible)
			{
				mute = true;
			}	
			this.dispatchEvent(new MuteClickEvent(mute));		
		}		
	}
}