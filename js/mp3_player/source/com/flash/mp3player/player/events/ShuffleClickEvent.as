/**
* GNU
**/
package com.flash.mp3player.player.events
{
	import flash.events.Event;

	public class ShuffleClickEvent extends Event
	{
		//static constants
		public static const SHUFFLE:String = "shuffle";
		
		//constructor
		public function ShuffleClickEvent()
		{
			super(ShuffleClickEvent.SHUFFLE, true);
		}
		
	}
}