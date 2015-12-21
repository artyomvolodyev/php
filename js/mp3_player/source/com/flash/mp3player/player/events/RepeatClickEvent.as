/**
* GNU
**/
package com.flash.mp3player.player.events
{
	import flash.events.Event;

	public class RepeatClickEvent extends Event
	{
		//static properties
		public static const REPEAT:	String = "repeat";
		
		//properties
		public var repeat:			Boolean;
		
		//constructor
		public function RepeatClickEvent(repeat:Boolean)
		{
			super(RepeatClickEvent.REPEAT, true);
			this.repeat = repeat;
		}		
	}
}