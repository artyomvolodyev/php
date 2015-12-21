/**
* GNU
**/
package com.flash.mp3player.playlist
{
	import com.flash.mp3player.playlist.description.SettingsDesc;
		
	public class PlaylistModel
	{
		//properties
		public var viewPositionY:		uint = 0;
		public var settingsDesc:		SettingsDesc;
		public var arSongs:				Array;
		public var currentSongUID:		String = '0';
		
		//constructor
		public function PlaylistModel()
		{
			this.arSongs = new Array();
		}
	}
}