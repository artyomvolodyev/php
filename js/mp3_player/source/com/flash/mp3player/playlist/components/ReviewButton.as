/**
* GNU
**/
package com.flash.mp3player.playlist.components
{
	import com.flash.modules.text.Text;
	import com.flash.modules.utils.UBitmap;
	import com.flash.mp3player.playlist.events.ReviewClickEvent;
	
	import flash.display.Bitmap;
	import flash.display.Sprite;
	import flash.events.MouseEvent;
	import flash.geom.Rectangle;

	public class ReviewButton extends Sprite
	{		
		//constants
		private const border:	uint = 3;
		
		//properties
		private var spBtnAsset:	ColumnButtonAsset;
		private var spBtn:		Sprite;
		private var tfText:		Text;
		
		//constructor
		public function ReviewButton()
		{
			super();
		}
		
		//methods
		public function Init(text:String, btnWidth:uint):void
		{
			this.cacheAsBitmap = true;
			this.useHandCursor = true;
			this.buttonMode = true;
			this.addEventListener(MouseEvent.CLICK, this.ClickHandler);
			
			this.spBtnAsset = new ColumnButtonAsset();
	
			this.spBtn = new Sprite();
			this.addChild(this.spBtn);
			
			this.tfText = new Text("tfText", text, "Tahoma", 10, 0xffffff, "left", "dynamic",
				false, false, true, true, 'center');
			this.tfText.mouseEnabled = false;
			this.tfText.x = this.border;
			this.tfText.y = this.border;
			this.tfText.width = btnWidth - 2 * this.border;
			this.tfText.height = this.tfText.textHeight + 4;
			this.addChild(this.tfText);
			
			var bmp:Bitmap = UBitmap.Resize(this.spBtnAsset, new Rectangle(4, 12, 93, 7),
				btnWidth, this.tfText.height + 2 * this.border);
				
			this.spBtn.addChild(bmp);
		}
		
		private function ClickHandler(evt:MouseEvent):void
		{
			this.dispatchEvent(new ReviewClickEvent());
		}		
	}
}