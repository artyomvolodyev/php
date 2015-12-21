/**
* GNU
**/
package com.flash.mp3player.player.events
{
	import flash.events.Event;

	public class MuteClickEvent extends Event
	{
		//static properties
		public static const MUTE:	String = "mute";
		
		//properties
		public var mute:			Boolean;
		
		//constructor
		public function MuteClickEvent(mute:Boolean)
		{
			super(MuteClickEvent.MUTE, true);
			this.mute = mute;
		}		
	}
}