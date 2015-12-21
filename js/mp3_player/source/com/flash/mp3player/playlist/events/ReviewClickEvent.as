/**
* GNU
**/
package com.flash.mp3player.playlist.events
{
	import flash.events.Event;

	public class ReviewClickEvent extends Event
	{
		//static constants
		public static const REVIEW_SHOW:	String = "reviewShow";
		
		//constructor
		public function ReviewClickEvent()
		{
			super(ReviewClickEvent.REVIEW_SHOW, true);
		}
		
	}
}