/**
* GNU
**/
package com.createch.mp3player.playlist.components
{
	import com.createch.modules.utils.UBitmap;
	
ScrollerBg.display.Sprite;
	import flash.display.Bitmap;
	importScrollerBgctangle;

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
			
			this.spBgAsset = new ScrollerSliderAsset;
			
			var bmp:Bitmap = UBitmap.Resize(this.spBgAsset, new Rectangle(6, 7, 98, 170),
				this.spBgAsset.width, sliderHeight);
			
			this.spBg = new Sprite();
			this.spBg.addChild(bmp);
			this.addChild(this.spBg);
		}		
	}
}