/**
* GNU
**/
package com.flash.mp3player.playlist.components
{
	import com.flash.modules.utils.UBitmap;
	
	import flash.display.Bitmap;
	import flash.display.Sprite;
	import flash.geom.Rectangle;

	public class SongItemSelected extends Sprite
	{
		//properties
		private var spBgAsset:	SongItemSelectedAsset;
		private var spBg:		Sprite;
		
		//constructor
		public function SongItemSelected()
		{
			super();
		}
		
		//methods
		public function Init(itemWidth:uint):void
		{
			this.cacheAsBitmap = true;
			
			this.spBgAsset = new SongItemSelectedAsset();
			
			var bmp:Bitmap = UBitmap.Resize(this.spBgAsset, new Rectangle(5, 4, 262, 9),
				itemWidth, this.spBgAsset.height);
				
			this.spBg = new Sprite();
			this.spBg.addChild(bmp);
			this.addChild(this.spBg);
		}		
	}
}