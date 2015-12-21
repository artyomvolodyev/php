/**
* GNU
**/
package com.flash.mp3player.player.events
{
	import flash.events.Event;

	public class NextPrevEvent extends Event
	{
		//static constants
		public static const NEXT:	String = "next";
		public static const PREV:	String = "prev";
		
		//properties
		
		//constructor
		public function NextPrevEvent(type:String)
		{
			super(type, true);
		}
		
	}
}