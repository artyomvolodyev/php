/**
* GNU
**/
package com.flash.mp3player.playlist.description
{
	internal class ButtonDesc
	{
		//properties
		public var show:	Boolean = true;
		public var text:	String = "";
		public var href:	String = "";
		
		//constructor
		public function ButtonDesc(btnXML:XML)
		{
			if(String(btnXML.show).length != 0
				&& String(btnXML.show) != "true")
			{
				this.show = false;
			}
			if(String(btnXML.text).length != 0)
			{
				this.text = String(btnXML.text); 
			}
			if(String(btnXML.href).length != 0)
			{
				this.href = String(btnXML.href);
			}
		}
	}
}