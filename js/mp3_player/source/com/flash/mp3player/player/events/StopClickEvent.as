/**
* GNU
**/
package com.flash.mp3player.player.events
{
	import flash.events.Event;

	public class StopClickEvent extends Event
	{
		//static constants
		public static const STOP:String = "stop";
		
		//constructor
		public function StopClickEvent()
		{
			super(StopClickEvent.STOP, true);
		}
		
	}
}