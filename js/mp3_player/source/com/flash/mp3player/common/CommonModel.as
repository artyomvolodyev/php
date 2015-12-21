/**
* GNU
**/
package com.flash.mp3player.common
{
	
	public class CommonModel
	{
		// static properties
		static private var inst:	CommonModel = null;
		
		// constants
		static public const MIN_APP_WIDTH:		uint = 320;
		static public const MIN_APP_HEIGHT:		uint = 140;
		
		// properties
		public var repeate:		Boolean = false;
		public var shuffle:		Boolean = false;
	
		// constructor
		public function CommonModel()
		{
		}
		
		// static methods
		static public function GetInst():CommonModel
		{
			if(CommonModel.inst == null) CommonModel.inst = new CommonModel();
			return CommonModel.inst;
		}
		
		// getters & setters
		
		// methods
	}
}