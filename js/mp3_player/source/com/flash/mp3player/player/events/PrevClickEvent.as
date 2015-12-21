/**
* GNU
**/
package com.flash.mp3player.player.events
{
	import flash.events.Event;

	public class PrevClickEvent extends Event
	{
		//static constants
		public static const PREV:		String = "prevClick";
		
		//constructor
		public function PrevClickEvent()
		{
			super(PrevClickEvent.PREV, true);
		}
		
	}
}