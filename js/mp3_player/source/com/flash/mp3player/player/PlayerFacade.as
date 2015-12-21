/**
* GNU
**/
package com.flash.mp3player.player
{
	import com.flash.mp3player.common.vo.SongVO;
	import com.flash.mp3player.common.CommonModel;
	
	import flash.display.Sprite;
	
	public class PlayerFacade
	{
		//static properties
		private static var instance:	PlayerFacade;
		
		//properties
		private var playerModel:		PlayerModel;
		private var playerView:			PlayerView;
		private var playerController:	PlayerController;
		
		//constructor
		public function PlayerFacade() {}
		
		//methods
		public static function GetInstance():PlayerFacade
		{
			if(PlayerFacade.instance == null)
			{
				PlayerFacade.instance = new PlayerFacade();
			}
			return PlayerFacade.instance;
		}
		
		public function Init(parentContainer:Sprite):void
		{
			this.playerModel = new PlayerModel();
			
			var width:uint = Math.max(parentContainer.stage.stageWidth, 
				CommonModel.MIN_APP_WIDTH);
			
			this.playerView = new PlayerView();
			this.playerView.Init(width);
			parentContainer.addChild(this.playerView);
			
			this.playerController = new PlayerController();
			this.playerController.Init(this.playerModel, this.playerView);
		}
		
		public function ApplySettings(xml:XML):void
		{
			this.playerController.ApplySettings(xml);
		}
		
		public function ShowFirstSong(songVO:SongVO):void
		{
			this.playerController.ShowFirstSong(songVO);
		}
		
		public function PlaySong(songVO:SongVO):void
		{
			this.playerController.PlaySong(songVO);
		}
		
		public function GetHeight():uint
		{
			return this.playerView.GetHeight();
		}
		
		public function HideSplaashScreen():void
		{
			this.playerView.HideSplashScreen();

		}
	}
}