/**
* GNU
**/
package com.flash.mp3player.player
{
	import com.flash.mp3player.common.vo.SongVO;
	
	public class PlayerModel
	{
		//properties
		public var curSong:			SongVO;
		public var curPosition:		uint = 0;
		public var curVolume:		Number = 1;
		public var muted: 			Boolean = false;
		public var isPlaying:		Boolean = false;
		public var isRepeated:		Boolean = false;
		
		//constructor
		public function PlayerModel() {}
	}
}