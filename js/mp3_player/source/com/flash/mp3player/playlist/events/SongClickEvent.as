/**
* GNU
**/
package com.flash.mp3player.playlist.events
{
	import com.flash.mp3player.common.vo.SongVO;
	
	import flash.events.Event;

	public class SongClickEvent extends Event
	{
		//static constants
		public static const SONG_PLAY:	String = "songPlay";
		
		//properties
		public var songVO:				SongVO; 
		
		//constructor
		public function SongClickEvent(songVO:SongVO)
		{
			super(SongClickEvent.SONG_PLAY, true);
			this.songVO = songVO;
		}		
	}
}