/**
* GNU
**/
package com.flash.mp3player.playlist
{	
	import com.flash.mp3player.common.CommonModel;
	import com.flash.mp3player.common.vo.SongVO;
	import com.flash.mp3player.playlist.description.SettingsDesc;
	import com.flash.mp3player.playlist.events.*;
	import com.flash.mp3player.playlist.loader.*;
	
	import flash.net.URLRequest;
	import flash.net.navigateToURL;
	
	public class PlaylistController
	{
		//properties
		private var imageLoader:		ImageLoader;
		
		private var playlistModel:		PlaylistModel;
		private var playlistView:		PlaylistView;
		
		//constructor
		public function PlaylistController() {}
		
		//methods
		public function Init(playlistModel:PlaylistModel, playlistView:PlaylistView):void
		{
			this.playlistModel = playlistModel;
			
			this.playlistView = playlistView;
			this.playlistView.addEventListener(SongClickEvent.SONG_PLAY, this.SongPlayHandler);
			this.playlistView.addEventListener(ReviewClickEvent.REVIEW_SHOW, this.ReviewHandler);
			this.playlistView.addEventListener(LogoClickEvent.GOTOURL, this.LogoHandler);
		}
		
		public function ApplySettings(xml:XML):void
		{
			this.playlistModel.settingsDesc = new SettingsDesc(xml.settings[0]);
			this.playlistModel.arSongs = new Array();
			var i:uint = 1;
			for each(var song:XML in xml.song)
			{
				var songVO:SongVO = new SongVO();
				if(String(song.title).length != 0) songVO.title = String(song.title);
				if(String(song.artist).length != 0) songVO.artist = String(song.artist);
				if(String(song.length).length != 0) songVO.length = Number(song.length);	
				songVO.url = this.playlistModel.settingsDesc.musicFolder + String(song.fileName);
				songVO.uid = i.toString();
				if(String(song.cover).length != 0)
				{
					songVO.cover = this.playlistModel.settingsDesc.picFolder + String(song.cover);
				}
				i++;
				this.playlistModel.arSongs.push(songVO);		
			}
				this.playlistView.dispatchEvent(new PlaylistEvent());
				
				if(this.playlistModel.settingsDesc.showPlaylist)
				{
					this.playlistView.DrawPlaylist(this.playlistModel.arSongs,
						this.playlistModel.viewPositionY,
						this.playlistModel.settingsDesc.coverDesc.width,
						this.playlistModel.settingsDesc.coverDesc.show,
						this.playlistModel.settingsDesc.btnDesc.show,
						this.playlistModel.settingsDesc.logoDesc.show,
						this.playlistModel.settingsDesc.picFolder + this.playlistModel.settingsDesc.logoDesc.imageURL,
						this.playlistModel.settingsDesc.btnDesc.text);
				
					if(this.playlistModel.settingsDesc.coverDesc.show == true)
					{
						this.LoadDefaultCover(this.playlistModel.settingsDesc.picFolder +
						this.playlistModel.settingsDesc.coverDesc.imageURL);
					}
				}								
		}
		
		private function LoadDefaultCover(defaultCoverURL:String):void
		{
			this.imageLoader = new ImageLoader();
			this.imageLoader.Load(defaultCoverURL);
			this.imageLoader.addEventListener(ImageEvent.IMAGE_LOADED, this.ImageLoadedHandler);
		}
		
		private function ImageLoadedHandler(evt:ImageEvent):void
		{
			if(evt.image.name != '0' )
			{
				for(var i:uint = 0; i < this.playlistModel.arSongs.length; i++ )
				{
					var songVO:SongVO = this.playlistModel.arSongs[i];
					if(songVO.uid == evt.image.name)
					{
						songVO.coverLoaded = true;
					}
				}
			}
			
			this.playlistView.AttachCover(evt.image);
		}
		
		public function SongPlayHandler(evt:SongClickEvent):void
		{
			if(evt.songVO == null) return;
			
			this.playlistModel.currentSongUID = evt.songVO.uid; 
			
			if(this.playlistModel.settingsDesc.showPlaylist)
			{
				this.playlistView.SelectSongItem(evt.songVO.uid);
			
				if(this.playlistModel.settingsDesc.coverDesc.show)
				{
					if(evt.songVO.cover != "")
					{
						if(!evt.songVO.coverLoaded)
						{
							this.imageLoader.Load(evt.songVO.cover,	evt.songVO.uid);
						}	
						else
						{
							this.playlistView.ShowCover(evt.songVO.uid);
						}
					}
				}
			}				
		}
		
		public function UpdatePlaylist(songVO:SongVO):void
		{
			if(this.playlistView) this.playlistView.UpdatePlaylist(songVO);
		}
		
		public function GetFirstSong():SongVO
		{
			return this.playlistModel.arSongs[0];
		}
		
		public function SelectFirstSong():void
		{
			this.SongPlayHandler(new SongClickEvent(this.playlistModel.arSongs[0]));
		}
		
		public function GetNextSong():SongVO
		{
			if(CommonModel.GetInst().shuffle)
			{
				return this.GetRandomSong();
			}
			else
			{
				var nextSong:SongVO = null;
				if(this.playlistModel.currentSongUID == 
					this.playlistModel.arSongs.length.toString())
				{
					if(CommonModel.GetInst().repeate)
						nextSong = this.playlistModel.arSongs[0];
				}
				else
				{
					nextSong = this.playlistModel.arSongs[
						this.playlistModel.currentSongUID];
				}
				
				this.SongPlayHandler(new SongClickEvent(nextSong));
					 				
				return nextSong;
			}
		}
		
		public function GetPrevSong():SongVO
		{
			if(CommonModel.GetInst().shuffle)
			{
				return this.GetRandomSong();
			}
			else
			{
				var prevSong:SongVO = null;
				if(this.playlistModel.currentSongUID == '1')
				{
					if(CommonModel.GetInst().repeate)
						prevSong = this.playlistModel.arSongs[					
							this.playlistModel.arSongs.length - 1];	
				}
				else
				{
					prevSong = this.playlistModel.arSongs[
						int(this.playlistModel.currentSongUID) - 2];
				}
				this.SongPlayHandler(new SongClickEvent(prevSong));
					
				return prevSong;
			}
		}
		
		private function GetRandomSong():SongVO
		{
			var newSongIndex:uint = Math.random() * this.playlistModel.arSongs.length;
			if(newSongIndex.toString() == this.playlistModel.currentSongUID)
			{
				if(this.playlistModel.currentSongUID == 
					this.playlistModel.arSongs.length.toString())
				{
					newSongIndex = 0;	
				}
				else if(this.playlistModel.currentSongUID == '1')
				{
					newSongIndex = this.playlistModel.arSongs.length - 1;
				}
				else
				{
					newSongIndex = int(this.playlistModel.currentSongUID) - 2;
				}
			}
			this.SongPlayHandler(new SongClickEvent(this.playlistModel.arSongs[newSongIndex]));
					 
			return this.playlistModel.arSongs[newSongIndex];
		}
		
		private function ReviewHandler(evt:ReviewClickEvent):void
		{
			try
			{
				navigateToURL(new URLRequest(this.playlistModel.settingsDesc.btnDesc.href), "_blank");
			}
			catch(e:SecurityError)
			{
				throw new Error(e.message);
			}
		}
		
		private function LogoHandler(evt:LogoClickEvent):void
		{
			try
			{
				navigateToURL(new URLRequest(this.playlistModel.settingsDesc.logoDesc.href), "_blank");
			}
			catch(e:SecurityError)
			{
				throw new Error(e.message);
			}
		}
	}
}