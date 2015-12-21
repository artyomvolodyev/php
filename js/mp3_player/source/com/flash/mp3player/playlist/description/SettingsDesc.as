/**
* GNU
**/
package com.flash.mp3player.playlist.description
{
	public class SettingsDesc
	{
		//properties
		public var musicFolder:		String = "";
		public var picFolder:		String = "";
		public var showPlaylist:	Boolean = true;
		public var coverDesc:		CoverDesc;
		public var logoDesc:		LogoDesc;
		public var btnDesc:			ButtonDesc;
		
		//constructor
		public function SettingsDesc(settingsXML:XML)
		{
			this.musicFolder = String(settingsXML.musicFolder);
			this.picFolder = String(settingsXML.picturesFolder);
			if(String(settingsXML.showPlaylist).length != 0
				&& String(settingsXML.showPlaylist) != "true")
			{
				this.showPlaylist = false;
			}
			
			this.coverDesc = new CoverDesc(settingsXML.defaultCover[0]);
			this.logoDesc = new LogoDesc(settingsXML.logo[0]);
			this.btnDesc = new ButtonDesc(settingsXML.button[0]);
		}
	}
}