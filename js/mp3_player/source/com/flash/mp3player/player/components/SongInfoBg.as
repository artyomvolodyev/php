/**
* GNU
**/
package com.flash.mp3player.player.components
{
	import com.flash.modules.utils.UBitmap;
	
	import flash.display.Bitmap;
	import flash.display.Sprite;
	import flash.geom.Rectangle;

	public class SongInfoBg extends Sprite
	{
		//properties
		private var spSongInfoBgAsset:	SongInfoBgAsset;
		private var spSongInfoBg:		Sprite;
		
		//constructor
		public function SongInfoBg()
		{
			super();
		}
		
		//methods
		public function Init(infoWidth:uint):void
		{
			this.cacheAsBitmap = true;
			this.mouseEnabled = false;
			
			this.spSongInfoBgAsset = new SongInfoBgAsset();
			
			var bd:Bitmap = UBitmap.Resize(this.spSongInfoBgAsset, new Rectangle(13, 4, 366, 12),
				infoWidth, this.spSongInfoBgAsset.height);
				
			this.spSongInfoBg = new Sprite();			
			this.spSongInfoBg.addChild(bd);
			this.addChild(this.spSongInfoBg);
		}		
	}
}