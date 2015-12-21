/**
* GNU
**/
package com.flash.mp3player.player.components
{
	import com.flash.modules.utils.UBitmap;
	
	import flash.display.Sprite;
	import flash.display.Bitmap;
	import flash.geom.Rectangle;

	public class PlayingProgressBg extends Sprite
	{
		//properties
		private var spBgAsset:	PlayingProgressBgAsset;
		private var spBg:		Sprite;
		
		//constructor
		public function PlayingProgressBg()
		{
			super();
		}
		
		//methods
		public function Init(bgWidth:uint):void
		{
			this.cacheAsBitmap = true;
			this.mouseEnabled = false;
						
			this.spBgAsset = new PlayingProgressBgAsset()

			var bd:Bitmap = UBitmap.Resize(this.spBgAsset, new Rectangle(4, 2, 380, 4),
				bgWidth, this.spBgAsset.height);
			
			this.spBg = new Sprite();
			this.spBg.addChild(bd);
			this.addChild(this.spBg);
		}		
	}
}