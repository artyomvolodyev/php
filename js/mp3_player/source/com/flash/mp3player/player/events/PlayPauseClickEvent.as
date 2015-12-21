/**
* GNU
**/
package com.flash.mp3player.player.events
{
	import flash.events.Event;

	public class PlayPauseClickEvent extends Event
	{
		//static constants
		public static const PLAY_PAUSE_CLICK:String = "playPauseClick";
		
		//constructor
		public function PlayPauseClickEvent()
		{
			super(PlayPauseClickEvent.PLAY_PAUSE_CLICK, true);
		}
		
	}
}