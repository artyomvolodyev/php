/**
* GNU
**/
package com.flash.modules.utils
{
	public class UDateTime
	{
		//methods
		public static function ConvertToMinutesStr(length:uint):String
		{
			var minutes:uint = Math.floor(length / 60);
			var seconds:uint = length - minutes * 60;
			var stMinutes:String;
			if(minutes < 10)
			{
				stMinutes = "0" + minutes;
			}
			else
			{
				stMinutes = String(minutes);
			}
			var stSeconds:String;
			if(seconds < 10)
			{
				stSeconds = "0" + seconds;
			}
			else
			{
				stSeconds = String(seconds);
			}
			return stMinutes + ":" + stSeconds;
		}
	}
}