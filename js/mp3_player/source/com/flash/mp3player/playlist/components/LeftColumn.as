/**
* GNU
**/
package com.flash.mp3player.playlist.components
{
	import com.flash.modules.utils.UBitmap;
	
	import flash.display.Bitmap;
	import flash.display.Sprite;
	import flash.geom.Rectangle;

	public class LeftColumn extends Sprite
	{
		//properties
		private var spBgAsset:	ColumnBgAsset;
		private var spBg:		Sprite;
		
		//constructor
		public function LeftColumn()
		{
			super();
		}
		
		//methods
		public function Init(columnWidth:uint, columnHeight:uint):void
		{
			this.cacheAsBitmap = true;
			this.mouseEnabled = false;
			
			this.spBgAsset = new ColumnBgAsset();
			
			var bmp:Bitmap = UBitmap.Resize(this.spBgAsset, new Rectangle(6, 7, 98, 170),
				columnWidth, columnHeight);
				
			this.spBg = new Sprite();
			this.spBg.addChild(bmp);
			this.addChild(this.spBg);
		}		
	}
}