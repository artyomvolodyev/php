/**
* GNU
**/
package com.flash.mp3player.playlist
{
	import com.flash.mp3player.common.vo.SongVO;
	
	import flash.display.Sprite;
	
	public class PlaylistFacade
	{
		//static properties
		private static var instance:	PlaylistFacade;
		
		//properties
		private var playlistModel:		PlaylistModel;
		private var playlistView:		PlaylistView;
		private var playlistController:	PlaylistController;
		
		//constructor
		public function PlaylistFacade() {}
		
		//methods
		public static function GetInstance():PlaylistFacade
		{
			if(PlaylistFacade.instance == null)
			{
				PlaylistFacade.instance = new PlaylistFacade();
			}
			return PlaylistFacade.instance;
		}
		
		public function Init(viewPositionY:uint,
			parentContainer:Sprite):void
		{	
			this.playlistModel = new PlaylistModel();
			this.playlistModel.viewPositionY = viewPositionY;
			
			this.playlistView = new PlaylistView();
			this.playlistView.y = viewPositionY;
			parentContainer.addChild(this.playlistView);
			
			this.playlistController = new PlaylistController();
			this.playlistController.Init(this.playlistModel, this.playlistView);
		}
		
		public function ApplySettings(xml:XML):void
		{
			this.playlistController.ApplySettings(xml);
		}
		
		public function GetFirstSong():SongVO
		{
			return this.playlistController.GetFirstSong();
		}
		
		public function GetNextSong():SongVO
		{
			return this.playlistController.GetNextSong();			
		}
		
		public function GetPrevSong():SongVO
		{
			return this.playlistController.GetPrevSong();
		}
		
		public function SelectFirstSong():void
		{
			this.playlistController.SelectFirstSong();
		}
		
		public function UpdatePlaylist(songVO:SongVO):void
		{
			this.playlistController.UpdatePlaylist(songVO);
		}
	}
}