/**
* GNU
**/
package com.flash.mp3player.player.components
{
	import com.flash.modules.utils.UBitmap;
	
	import flash.display.Bitmap;
	import flash.display.Sprite;
	import flash.geom.Rectangle;

	public class PlayerBg extends Sprite
	{
		//properties
		private var spBgAsset:	PlayerBgAsset;
		private var spBg:		Sprite;
		
		//constructor
		public function PlayerBg()
		{
			super();
		}
		
		//methods
		public function Init(bgWidth:uint):void
		{
			this.cacheAsBitmap = true;
			this.mouseEnabled = false;
						
			this.spBgAsset = new PlayerBgAsset();
			
			var bd:Bitmap = UBitmap.Resize(this.spBgAsset, new Rectangle(17, 17, 362, 32),
				bgWidth, this.spBgAsset.height);
				
			this.spBg = new Sprite();
			this.spBg.addChild(bd);
			this.addChild(this.spBg);
		}		
	}
}