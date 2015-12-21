/**
* GNU
**/
package com.flash.mp3player.player.events
{
	import flash.events.Event;

	public class VolumeEvent extends Event
	{
		//static properties
		public static const CHANGE_VOLUME:	String = "changeVolume";
		
		//properties
		public var volume:					Number;
		
		//constructor
		public function VolumeEvent(volume:Number)
		{
			super(VolumeEvent.CHANGE_VOLUME, true);
			this.volume = volume;
		}
		
	}
}