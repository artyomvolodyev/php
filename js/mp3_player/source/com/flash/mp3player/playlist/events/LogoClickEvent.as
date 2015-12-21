/**
* GNU
**/
package com.flash.mp3player.playlist.events
{
	import flash.events.Event;

	public class LogoClickEvent extends Event
	{
		//static constants
		public static const GOTOURL:	String = "gotoURL";
		
		//constructor
		public function LogoClickEvent()
		{
			super(LogoClickEvent.GOTOURL, true);
		}		
	}
}