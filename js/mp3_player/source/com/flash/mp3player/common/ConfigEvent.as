/**
* GNU
**/
package com.flash.mp3player.common
{
	import flash.events.Event;

	public class ConfigEvent extends Event
	{
		//constants
		public static const CONFIG_LOADED:String = "configLoaded";
		
		//properties
		public var result:	Boolean;
		public var xml:		XML;
		
		//constructor
		public function ConfigEvent(result:Boolean, xml:XML)
		{
			super(ConfigEvent.CONFIG_LOADED);
			this.result = result;
			this.xml = xml;
		}		
	}
}