/**
* GNU
**/
package com.flash.mp3player.playlist.components
{	
	import com.flash.mp3player.playlist.events.LogoClickEvent;
	
	import flash.display.Loader;
	import flash.display.Sprite;
	import flash.events.Event;
	import flash.events.IOErrorEvent;
	import flash.events.MouseEvent;
	import flash.net.URLRequest;

	public class Logo extends Sprite
	{
		//properties
		private var loader:			Loader;
		private var loaderWidth:	uint;
		
		//constructor
		public function Logo()
		{
			super();
		}
		
		//methods
		public function Init(url:String, loaderWidth:uint):void
		{			
			this.loaderWidth = loaderWidth;
			
			this.cacheAsBitmap = true;
			this.useHandCursor = true;
			this.buttonMode = true;
			this.addEventListener(MouseEvent.CLICK, this.ClickHandler);
			
			if(url != "")
			{
				var request:URLRequest = new URLRequest(url);
				this.loader = new Loader();
				this.loader.load(request);
				this.loader.contentLoaderInfo.addEventListener(Event.COMPLETE, this.LoadCompleteHandler);
				this.loader.contentLoaderInfo.addEventListener(IOErrorEvent.IO_ERROR, this.IOErrorHandler);
				this.addChild(this.loader);
			}			
		}
		
		private function ClickHandler(evt:MouseEvent):void
		{
			this.dispatchEvent(new LogoClickEvent());
		}
		
		private function LoadCompleteHandler(evt:Event):void
		{
			if(this.loader.width > this.loaderWidth)
			{
				this.loader.width = this.loaderWidth;
				this.loader.scaleY = this.loader.scaleX;
			}
			this.loader.x = (this.loaderWidth - this.loader.width) / 2;
		}
		
		private function IOErrorHandler(evt:IOErrorEvent):void
		{
			throw new Error("Logo image can't be read!");
		}		
	}
}