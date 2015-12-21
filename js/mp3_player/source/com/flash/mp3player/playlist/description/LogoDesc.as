/**
* GNU
**/
package com.flash.mp3player.playlist.description
{
	internal class LogoDesc
	{
		//properties
		public var show:		Boolean = true;
		public var imageURL:	String = "";
		public var href:		String = "";
		
		//description
		public function LogoDesc(logoXML:XML)
		{
			if(String(logoXML.show).length != 0
				&& String(logoXML.show).toLocaleLowerCase() != "true")
			{
				this.show = false; 
			}
			if(String(logoXML.imageURL).length != 0)
			{
				this.imageURL = String(logoXML.imageURL);
			}
			if(String(logoXML.href).length != 0)
			{
				this.href = String(logoXML.href);
			}
		}
	}
}