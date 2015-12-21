/**
* GNU
**/
package com.flash.mp3player.player.components
{
	import flash.display.Sprite;

	public class InfoBtn extends Sprite
	{
		//properties
		private var spBg:	InfoBtnAsset;
		
		//constructor
		public function InfoBtn()
		{
			super();
		}
		
		//methods
		public function Init():void
		{
			this.cacheAsBitmap = true;
			this.useHandCursor = true;
			this.buttonMode = true;
			
			this.spBg = new InfoBtnAsset();
			this.addChild(this.spBg);
		}
	}
}