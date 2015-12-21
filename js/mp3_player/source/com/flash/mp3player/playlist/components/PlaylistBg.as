/**
* GNU
**/
package com.flash.mp3player.playlist.components
{
	import com.flash.modules.utils.UBitmap;
	
	import flash.display.Bitmap;
	import flash.display.Sprite;
	import flash.geom.Rectangle;

	public class PlaylistBg extends Sprite
	{
		//properties
		private var spBgAsset:	PlaylistBgAsset;
		private var spBg:		Sprite;
		
		//constructor
		public function PlaylistBg()
		{
			super();
		}
		
		//methods
		public function Init(playlistWidth:uint, playlistHeight:uint):void
		{
			this.cacheAsBitmap = true;
			this.mouseEnabled = false;
			
			this.spBgAsset = new PlaylistBgAsset();
			
			var bmp:Bitmap = UBitmap.Resize(this.spBgAsset, new Rectangle(13, 9, 265, 164),
				playlistWidth, playlistHeight);
				
			this.spBg = new Sprite();
			this.spBg.addChild(bmp);
			this.addChild(this.spBg);
		}		
	}
}