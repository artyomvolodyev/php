/**
* GNU
**/
package com.flash.mp3player.common.vo
{
	public class SongVO
	{
		//properties
		public var artist:			String = "";
		public var title:			String = "";
		public var length:			Number = 0;
		public var url:				String = "";
		public var uid:				String = "";
		public var cover:			String = "";
		public var coverLoaded:		Boolean = false;
		
		//constructor
		public function SongVO()
		{
		}
		
		// methods
		public function toString() : String
		{
			return "SongVO url = " + url;
		}
	}
}