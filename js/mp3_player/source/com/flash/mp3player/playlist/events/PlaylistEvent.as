/**
* GNU
**/
package com.flash.mp3player.playlist.events
{
	import flash.events.Event;

	public class PlaylistEvent extends Event
	{
		//constants
		public static const PLAYLIST_LOADED:String = "playlistLoaded";
	
		//constructor
		public function PlaylistEvent()
		{
			super(PlaylistEvent.PLAYLIST_LOADED, true);
		}		
	}
}
