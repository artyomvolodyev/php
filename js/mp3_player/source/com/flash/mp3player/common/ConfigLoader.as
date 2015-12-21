/**
* GNU
**/
package com.flash.mp3player.common
{
	import com.flash.mp3player.playlist.description.SettingsDesc;
	
	import flash.events.Event;
	import flash.events.EventDispatcher;
	import flash.events.IOErrorEvent;
	import flash.net.URLLoader;
	import flash.net.URLLoaderDataFormat;
	import flash.net.URLRequest;
	import flash.net.URLRequestMethod;

	public class ConfigLoader extends EventDispatcher
	{
		//properties
		public var settingsDesc:	SettingsDesc;
		public var arSongs:			Array;
		
		private var musicHolder:		String = "";
		
		//constructor
		public function ConfigLoader()
		{
			super();
		}
		
		//methods
		public function Load(url:String):void
		{
			var request:URLRequest = new URLRequest(url);
			request.method = URLRequestMethod.POST;
			var urlLoader:URLLoader = new URLLoader(request);
			urlLoader.dataFormat = URLLoaderDataFormat.TEXT;
			urlLoader.load(request);
			urlLoader.addEventListener(Event.COMPLETE, this.CompleteHandler);
			urlLoader.addEventListener(IOErrorEvent.IO_ERROR, this.IOErrorHandler);
		}
		
		private function CompleteHandler(evt:Event):void
		{
			var result:		Boolean;
			var urlLoader:	URLLoader = evt.target as URLLoader;
			var configXML:	XML;
			if(urlLoader.data != null)
			{
				result = true;
				configXML = new XML(unescape(urlLoader.data));
			}
			else
			{
				throw new Error("XML is empty!");
			}
			
			this.dispatchEvent(new ConfigEvent(result, configXML));
		}
		
		private function IOErrorHandler(evt:IOErrorEvent):void
		{
			throw new Error("XML file can't be opened!");
		}		
	}
}