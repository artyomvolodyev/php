/**
* GNU
**/
package com.flash.mp3player.playlist.components
{
	import flash.display.DisplayObject;
	import flash.display.Graphics;
	import flash.display.Loader;
	import flash.display.Sprite;

	public class AlbumCover extends Sprite
	{		
		//properties
		private var coverWidth:			uint;
		private var borderThickness:	uint = 1;
		private var spImageContainer:	Sprite;
		private var spBorder:			Sprite;
		
		//constructor
		public function AlbumCover()
		{
			super();
		}
		
		//methods
		public function Init(coverWidth:uint):void
		{
			this.coverWidth = coverWidth;
			
			this.spImageContainer = new Sprite();
			this.addChild(this.spImageContainer);
			
			this.spBorder = new Sprite();
			this.addChild(this.spBorder);
		}
		
		public function AttachCover(image:Loader):void
		{
			var w:uint = this.coverWidth - this.borderThickness * 2;
			
			if(image.width > w)
			{
				image.width = w;
				image.scaleY  = image.scaleX;
			}
			this.spImageContainer.x = Math.round( (this.coverWidth - image.width) / 2 );	
					
			this.spImageContainer.addChild(image);
			
			this.spBorder.graphics.clear();
			this.DrawBorder(this.spBorder.graphics);
		}
		
		public function ShowCover(imageUID:String):void
		{
			for(var i:uint = 0; i < this.spImageContainer.numChildren; i++ )
			{
				var mcCoverImage:DisplayObject = this.spImageContainer.getChildAt(i);
				mcCoverImage.visible = (mcCoverImage.name == imageUID);
			}
		}	
		
		private function DrawBorder(gr:Graphics):void
		{
			gr.beginFill(0x000000, 0);
			gr.lineStyle(this.borderThickness, 0xcfcfcf, 0.3);
			gr.drawRect(this.spImageContainer.x, 0,
				this.spImageContainer.width, this.spImageContainer.height);
			gr.endFill();
		}	
	}
}