/**
* GNU
**/
package com.flash.mp3player.player.components
{
	import com.flash.mp3player.player.events.VolumeEvent;
	
	import flash.display.Sprite;
	import flash.events.MouseEvent;
	import flash.geom.Rectangle;

	public class VolumeSlider extends Sprite
	{
		//properties
		private var spBg:				VolumeBgAsset;
		private var spSlider:			VolumeSliderAsset;
		private var spPointer:			VolumePointerAsset;
		
		private var volume:				Number;
		
		//constructor
		public function VolumeSlider()
		{
			super();
		}
		
		//methods
		public function Init():void
		{
			this.spBg = new VolumeBgAsset();
			this.spBg.useHandCursor = true;
			this.spBg.buttonMode = true;
			this.spBg.addEventListener(MouseEvent.CLICK, this.SliderClickHandler);
			this.addChild(this.spBg);
	
			this.spSlider = new VolumeSliderAsset();
			this.spSlider.mouseEnabled = false;
			this.spSlider.x = 1;
			this.spSlider.y = 2;
			this.spSlider.width = this.spBg.width - 2 * this.spSlider.x;
			this.addChild(this.spSlider);
			
			this.spPointer = new VolumePointerAsset();
			this.spPointer.useHandCursor = true;
			this.spPointer.buttonMode = true;
			this.spPointer.x = this.spBg.width - this.spPointer.width;
			this.spPointer.y = Math.round((this.spBg.height - this.spPointer.height) / 2);
			this.spPointer.addEventListener(MouseEvent.MOUSE_DOWN, this.SliderDownHandler);
			this.addChild(this.spPointer);
		}
		
		public function SetVolume(volume:Number):void
		{
			this.spPointer.x = volume * (this.spBg.width - this.spPointer.width);
			this.spSlider.width = this.spPointer.x;
		}
		
		private function SliderClickHandler(evt:MouseEvent):void
		{
			this.spPointer.x = this.mouseX - this.spPointer.width / 2;
			if(this.spPointer.x + this.spPointer.width > this.spBg.width)
			{
				this.spPointer.x = this.spBg.width - this.spPointer.width;
			}
			this.spSlider.width = this.spPointer.x;
			this.volume = this.mouseX / (this.spBg.width - this.spPointer.width);
			this.dispatchEvent(new VolumeEvent(this.volume));
		}
		
		private function SliderDownHandler(evt:MouseEvent):void
		{
			this.stage.addEventListener(MouseEvent.MOUSE_MOVE, this.SliderMoveHandler);
			this.stage.addEventListener(MouseEvent.MOUSE_UP, this.SliderUpHandler);
			var bounds:Rectangle = new Rectangle(this.spBg.x, this.spPointer.y, this.spBg.width - this.spPointer.width, 0);
			this.spPointer.startDrag(false, bounds);
		}
		
		private function SliderMoveHandler(evt:MouseEvent):void
		{
			this.volume = this.spPointer.x / (this.spBg.width - this.spPointer.width);
			this.spSlider.width = this.spPointer.x;
			this.dispatchEvent(new VolumeEvent(this.volume)); 
		}
		
		private function SliderUpHandler(evt:MouseEvent):void
		{
			this.spPointer.stopDrag();
			this.stage.removeEventListener(MouseEvent.MOUSE_MOVE, this.SliderMoveHandler);
			this.stage.removeEventListener(MouseEvent.MOUSE_UP, this.SliderUpHandler);
		}
	}
}