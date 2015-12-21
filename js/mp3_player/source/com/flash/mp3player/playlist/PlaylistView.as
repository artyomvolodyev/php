/**
* GNU
**/
package com.flash.mp3player.playlist
{
	import com.flash.mp3player.common.CommonModel;
	import com.flash.mp3player.common.vo.SongVO;
	import com.flash.mp3player.playlist.components.AlbumCover;
	import com.flash.mp3player.playlist.components.LeftColumn;
	import com.flash.mp3player.playlist.components.Logo;
	import com.flash.mp3player.playlist.components.Playlist;
	import com.flash.mp3player.playlist.components.ReviewButton;
	
	import flash.display.Loader;
	import flash.display.Sprite;
	
	public class PlaylistView extends Sprite
	{
		//constants
		private const border:uint = 5;
		
		//properties
		private var spBg:				LeftColumn;
		private var cover:				AlbumCover;
		private var btnReview:			ReviewButton;
		private var logo:				Logo;
		private var playlist:			Playlist;
		
		//constructor
		public function PlaylistView()
		{
			super();
		}
		
		//methods	
		public function DrawPlaylist(arSongs:Array, viewPositionY:uint, coverWidth:uint = 0,
			showCover:Boolean = true, showButton:Boolean = true, showLogo:Boolean = true,
			logoImageURL:String = "", btnText:String = ""):void
		{
			var width:uint = Math.max(this.stage.stageWidth, 
				CommonModel.MIN_APP_WIDTH);
			var height:uint = Math.max(this.stage.stageHeight, 
				CommonModel.MIN_APP_HEIGHT);
			
			if(showCover == false && showButton == false && showLogo == false)
			{
				coverWidth = 0;
			}
			else
			{
				this.spBg = new LeftColumn();
				this.spBg.Init(coverWidth, height - viewPositionY);
				this.addChild(this.spBg);
			}
			
			
			this.cover = new AlbumCover();
			if(showCover)
			{				
				this.cover.Init(coverWidth - 2 * this.border);
				this.cover.x = this.border;
				this.cover.y = this.border;
				this.addChild(this.cover);
			}
			
			this.btnReview = new ReviewButton();
			if(showButton)
			{				
				this.btnReview.Init(btnText, coverWidth - 2 * this.border);
				this.btnReview.x = this.border;
				this.addChild(this.btnReview);
			}		
			
			this.logo = new Logo();
			if(showLogo)
			{				
				this.logo.Init(logoImageURL, coverWidth - 2 * this.border);
				this.logo.x = this.border;
				this.addChild(this.logo);
			}						
			
			this.playlist = new Playlist();
			this.playlist.Init(width - coverWidth + 1, height - viewPositionY, arSongs);
			this.playlist.x = coverWidth - 1;
			this.addChild(this.playlist);
			
			this.PositionElements();
		}
		
		public function UpdatePlaylist(songVO:SongVO):void
		{
			if(this.playlist && songVO) this.playlist.UpdateSongItem(songVO);
		}
		
		public function AttachCover(image:Loader):void
		{
			this.cover.AttachCover(image);
			this.PositionElements();
		}
		
		public function ShowCover(imageUID:String):void
		{
			this.cover.ShowCover(imageUID);
			this.PositionElements();
		} 
		
		public function SelectSongItem(songItemUID:String):void
		{
			this.playlist.SelectSongItem(songItemUID);
		}
		
		private function PositionElements():void
		{ 
			this.btnReview.y = this.cover.y + this.cover.height + this.border;
			this.logo.y = this.btnReview.y + this.btnReview.height + this.border;
		}
	}
}