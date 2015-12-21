/**
* GNU
**/
package com.flash.mp3player.playlist.components
{
	import com.flash.modules.text.Text;
	import com.flash.modules.utils.UDateTime;
	import com.flash.mp3player.common.vo.SongVO;
	import com.flash.mp3player.playlist.events.SongClickEvent;
	
	import flash.display.Sprite;
	import flash.events.MouseEvent;
	import flash.text.*;

	public class SongItem extends Sprite
	{
		//static constants
		public static const ITEM_HEIGHT:	uint = 17;
		
		//properties
		private var spSongOut:				Sprite;
		private var spSongOver:				SongItemOver;
		private var spSongSelected:			SongItemSelected;
		
		private var tfLabel:				Text;
		private var tfTime:					Text;
		
		private var songVO:					SongVO;
		
		//constructor
		public function SongItem()
		{
			super();
		}
		
		//methods
		public function Init(songItemWidth:uint, songVO:SongVO):void
		{
			this.cacheAsBitmap = true;
			this.useHandCursor = true;
			this.buttonMode = true;
			this.doubleClickEnabled = true;
			
			this.songVO = songVO;
			this.name = String(this.songVO.uid);
			
			this.spSongOver = new SongItemOver();
			this.spSongOver.Init(songItemWidth);
			this.spSongOver.visible = false;
			this.addChild(this.spSongOver);
			
			this.spSongSelected = new SongItemSelected();
			this.spSongSelected.Init(songItemWidth);
			this.spSongSelected.visible = false;
			this.addChild(this.spSongSelected);
	
			this.spSongOut = new Sprite();			
			this.spSongOut.graphics.beginFill(0x000000, 0);
			this.spSongOut.graphics.drawRect(0, 0, songItemWidth, this.spSongOver.height);
			this.addChild(this.spSongOut);			
			
			var label:String = this.songVO.uid + ". " + this.songVO.artist + " - " +
				this.songVO.title;
			this.tfLabel = new Text("tfLabel", label);
			this.tfLabel.mouseEnabled = false;
			this.tfLabel.width = songItemWidth - 40; 
			this.tfLabel.height = this.tfLabel.textHeight + 5;
			this.tfLabel.x = 3;
			this.tfLabel.y = (this.spSongOver.height - this.tfLabel.height) / 2;
			this.addChild(this.tfLabel);
			
			this.tfTime = new Text("tfTime", UDateTime.ConvertToMinutesStr(this.songVO.length));
			if(this.tfTime.text == "00:00") this.tfTime.text = "--:--";
			this.tfTime.mouseEnabled = false;
			this.tfTime.width = this.tfTime.textWidth + 7;
			this.tfTime.height = this.tfTime.textHeight + 5;
			this.tfTime.x = songItemWidth - this.tfTime.width;
			this.tfTime.y = (this.spSongOver.height - this.tfTime.height) / 2;
			this.addChild(this.tfTime);			
			
			this.addEventListener(MouseEvent.CLICK, this.SongDoubleClickHandler);
			this.addEventListener(MouseEvent.MOUSE_OVER, this.MouseOverHandler);
			this.addEventListener(MouseEvent.MOUSE_OUT, this.MouseOutHandler);
		}
		
		public function Update(songVO:SongVO):void
		{
			this.songVO = songVO;
			
			this.tfLabel.text = this.songVO.uid + ". " + this.songVO.artist + " - " +
				this.songVO.title;
		}
		
		public function Select():void
		{
			this.spSongSelected.visible = true;
			this.spSongOut.visible = true;
			this.spSongOver.visible = false;
		}
		
		public function Deselect():void
		{
			this.spSongSelected.visible = false;
			
		}
		
		private function MouseOverHandler(evt:MouseEvent):void
		{
			if(!this.spSongSelected.visible)
			{
				this.spSongOver.visible = true;
				this.spSongOut.visible = false;
			}
		}
		
		private function MouseOutHandler(evt:MouseEvent):void
		{
			if(!this.spSongSelected.visible)
			{
				this.spSongOver.visible = false;
				this.spSongOut.visible = true;
			}			
		}
		
		private function SongDoubleClickHandler(evt:MouseEvent):void
		{
			this.dispatchEvent(new SongClickEvent(this.songVO));
		}		
	}
}