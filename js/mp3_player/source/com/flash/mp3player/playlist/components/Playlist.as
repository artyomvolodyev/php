/**
* GNU
**/
package com.flash.mp3player.playlist.components
{
	import com.flash.mp3player.common.vo.SongVO;
	
	import flash.display.Sprite;
	import flash.geom.Rectangle;

	public class Playlist extends Sprite
	{
		//constants
		private const border:			uint = 8;
		
		//properties
		private var songsContainer:		Sprite;
		
		private var spBg:				PlaylistBg;
		private var scroller:			Scroller;
		private var songItem:			SongItem;
		
		//constructor
		public function Playlist()
		{
			super();
		}
		
		//methods
		public function Init(playlistWidth:uint, playlistHeight:uint, arSongs:Array):void
		{					
			trace(playlistHeight);
			this.spBg = new PlaylistBg();
			this.spBg.Init(playlistWidth, playlistHeight);
			this.addChild(this.spBg);
			
			this.songsContainer = new Sprite();
			this.songsContainer.x = this.border;
			this.songsContainer.y = this.border;
			this.addChild(this.songsContainer);
			
			var initY:uint = 0;
			var songItemWidth:uint = playlistWidth - 2 * this.border;
			if(arSongs.length * SongItem.ITEM_HEIGHT >= (playlistHeight - 2 * this.border - 1))
			{
				
				this.scroller = new Scroller();
				songItemWidth -= this.scroller.width;
			}		
			
			for(var i:uint = 0; i < arSongs.length; i++ )
			{
				this.songItem = new SongItem();
				this.songItem.Init(songItemWidth, arSongs[i]);
				this.songItem.y = initY;
				this.songsContainer.addChild(this.songItem);
				initY += this.songItem.height;
			}
			
			if(arSongs.length * SongItem.ITEM_HEIGHT >= (playlistHeight - 2 * this.border - 1))
			{ 
				this.songsContainer.scrollRect = new Rectangle(0, 0,
					playlistWidth, playlistHeight - 2 * this.border - 1);
				this.scroller.Init(this.songsContainer);
				this.scroller.x = songItemWidth + this.border + 1; 
				this.scroller.y = this.border;
				this.addChild(this.scroller);
			}
		}
		
		public function SelectSongItem(songItemUID:String):void
		{
			for(var i:uint; i < this.songsContainer.numChildren; i++)
			{
				var songItem:SongItem = this.songsContainer.getChildAt(i) as SongItem;
				if(songItem.name == songItemUID)
				{
					songItem.Select();
				}
				else
				{
					songItem.Deselect();
				}
			}
		}
		
		public function UpdateSongItem(songVO:SongVO):void
		{
			for(var i:uint; i < this.songsContainer.numChildren; i++)
			{
				var songItem:SongItem = this.songsContainer.getChildAt(i) as SongItem;
				if(songItem.name == songVO.uid)
				{
					songItem.Update(songVO);
				}
			}
		}		
	}
}