/**
* GNU
**/
package com.flash.mp3player.player.events
{
	import flash.events.Event;

	public class SeekEvent extends Event
	{
		//static constants
		public static const SEEK:	String = "seek";
		
		//properties
		public var seek:			Number;
		
		//constructor
		public function SeekEvent(seek:Number)
		{
			super(SeekEvent.SEEK, true);
			this.seek = seek;
		}
		
	}
}