/**
* GNU
**/
package com.flash.mp3player.player.events
{
	import flash.events.Event;

	public class FirstSongStartEvent extends Event
	{
		//static properties
		public static const START:	String = "start";
		
		//constructor
		public function FirstSongStartEvent()
		{
			super(FirstSongStartEvent.START, true);
		}		
	}
}