/**
* GNU
**/
package com.flash.mp3player.player.events
{
	import com.flash.mp3player.common.vo.SongVO;
	
	import flash.events.Event;

	public class UpdateEvent extends Event
	{
		//static constants
		public static const UPDATE:	String = "update";
		
		//properties
		public var songVO:SongVO;
		
		//constructor
		public function UpdateEvent(songVO:SongVO)
		{
			super(UpdateEvent.UPDATE, true);
			this.songVO = songVO;
		}		
	}
}