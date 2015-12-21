/**
* GNU
**/
package com.flash.mp3player.playlist.description
{
	internal class CoverDesc
	{
		//properties
		public var show:		Boolean = true;
		public var width:		uint = 100;
		public var imageURL:	String = "";
		
		//constructor
		public function CoverDesc(coverXML:XML)
		{
			if(String(coverXML.show).length != 0
				&& String(coverXML.show) != "true")
			{
				this.show = false;
			}
			if(String(coverXML.width).length != 0)
			{
				this.width = uint(coverXML.width);
			}
			if(String(coverXML.imageURL).length != 0)
			{
				this.imageURL = String(coverXML.imageURL);
			}
		}
	}
}