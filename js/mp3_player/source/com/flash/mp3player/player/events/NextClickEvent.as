/**
* GNU
**/
package com.flash.mp3player.player.events
{
	import flash.events.Event;

	public class NextClickEvent extends Event
	{
		//static constants
		public static const NEXT:		String = "nextClick";
		
		//constructor
		public function NextClickEvent()
		{
			super(NextClickEvent.NEXT, true);
		}
		
	}
}