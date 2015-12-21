/**
* GNU
**/
package com.flash.mp3player.playlist.events
{
	import flash.display.Loader;
	import flash.events.Event;

	public class ImageEvent extends Event
	{
		//constants
		public static const IMAGE_LOADED:	String = "imageLoaded";
		
		//properties
		public var image:	Loader;
		
		//constructor
		public function ImageEvent(image:Loader)
		{
			super(ImageEvent.IMAGE_LOADED);
			this.image = image;
		}		
	}
}