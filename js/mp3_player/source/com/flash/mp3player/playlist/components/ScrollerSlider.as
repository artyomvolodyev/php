/**
* GNU
**/
package com.flash.mp3player.playlist.components
{
	import com.flash.modules.utils.UBitmap;
	
	import flash.display.Sprite;
	import flash.display.Bitmap;
	import flash.geom.Rectangle;

	public class ScrollerSlider extends Sprite
	{
		//properties
		private var spBgAsset:	ScrollerSliderAsset;
		private var spBg:		Sprite;
		
		//constructor
		public function ScrollerSlider()
		{
			super();
		}
		
		//methods
		public function Init(sliderHeight:uint):void
		{
			this.cacheAsBitmap = true;
			this.useHandCursor = true;
			this.buttonMode = true;
			
			this.spBgAsset = new ScrollerSliderAsset();
			
			var bmp:Bitmap = UBitmap.Resize(this.spBgAsset, new Rectangle(2, 7, 9, 4),
				this.spBgAsset.width, sliderHeight);
				
			this.spBg = new Sprite();
			this.spBg.addChild(bmp);
			this.addChild(this.spBg);
		}
	}
}