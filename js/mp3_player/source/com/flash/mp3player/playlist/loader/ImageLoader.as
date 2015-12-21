/**
* GNU
**/
package com.flash.mp3player.playlist.loader
{
	import com.flash.mp3player.playlist.events.ImageEvent;
	
	import flash.display.Loader;
	import flash.events.Event;
	import flash.events.EventDispatcher;
	import flash.events.IOErrorEvent;
	import flash.net.URLRequest; 

	public class ImageLoader extends EventDispatcher
	{
		//constructor
		public function ImageLoader()
		{
			super();
		}
		
		//methods
		public function Load(url:String, uid:String = '0'):void
		{
			var loader:Loader = new Loader();
			loader.name = uid;
			
			loader.contentLoaderInfo.addEventListener(Event.COMPLETE, this.LoadCompleteHandler);
			loader.contentLoaderInfo.addEventListener(IOErrorEvent.IO_ERROR, this.IOErrorHandler);
			
			loader.load(new URLRequest(url));
		}
		
		private function LoadCompleteHandler(evt:Event):void
		{
			var loader:Loader = Loader(evt.target.loader);
			this.dispatchEvent(new ImageEvent(loader));
		}
		
		private function IOErrorHandler(evt:IOErrorEvent):void
		{
			throw new Error("Error occured during image loading.");
		}		
	}
}