/**
* GNU
**/
package com.flash.mp3player.playlist.components
{
	import flash.display.Sprite;
	import flash.events.Event;
	import flash.events.MouseEvent;
	import flash.geom.Rectangle;
	
	public class Scroller extends Sprite
	{
		//properties		
		private var spBg:						ScrollerBgAsset;
		private var spHit:						Sprite;
		private var spTopArrow:					ScrollerTopArrowAsset;
		private var spSlider:					ScrollerSlider;
		private var spBottomArrow:				ScrollerBotArrowAsset;
		
		private var target:						Sprite;
		private var targetHeight:				uint;		
		
		//constructor
		public function Scroller()
		{
			super();
			this.spBg = new ScrollerBgAsset();
			this.addChild(this.spBg);
		}
		
		//methods
		public function Init(target:Sprite):void
		{
			this.target = target;
			this.targetHeight = target.height;
			
			this.spBg.height = this.target.scrollRect.height;
			
			this.spTopArrow = new ScrollerTopArrowAsset();
			this.spTopArrow.useHandCursor = true;
			this.spTopArrow.buttonMode = true;
			this.spTopArrow.addEventListener(MouseEvent.MOUSE_DOWN, this.TopArrowPressHandler);
			this.spTopArrow.addEventListener(MouseEvent.MOUSE_UP, this.TopArrowReleaseHandler);
			this.addChild(this.spTopArrow);			
			
			this.spHit = new Sprite();
			this.spHit.graphics.beginFill(0x000000, 0);
			this.spHit.graphics.drawRect(0, 0, this.spBg.width, this.spBg.height - 2 * this.spTopArrow.height);
			this.spHit.y  = this.spTopArrow.y + this.spTopArrow.height;
			this.spHit.addEventListener(MouseEvent.CLICK, this.BgClickHandler);
			this.addChild(this.spHit);
			
			this.spSlider = new ScrollerSlider();
			this.spSlider.Init(this.spHit.height * this.target.scrollRect.height / this.targetHeight);
			this.spSlider.y = this.spHit.y;
			this.spSlider.addEventListener(MouseEvent.MOUSE_DOWN, this.SliderStartDragHandler);
			this.addChild(this.spSlider);
			
			this.spBottomArrow = new ScrollerBotArrowAsset();
			this.spBottomArrow.useHandCursor = true;
			this.spBottomArrow.buttonMode = true;
			this.spBottomArrow.y = this.spBg.height - this.spBottomArrow.height;
			this.spBottomArrow.addEventListener(MouseEvent.MOUSE_DOWN, this.BottomArrowPressHandler);
			this.spBottomArrow.addEventListener(MouseEvent.MOUSE_UP, this.BottomArrowReleaseHandler);
			this.addChild(this.spBottomArrow);
			
			this.PositionElements();
		}
			
		private function PositionElements():void
		{
			this.spHit.y = this.spTopArrow.height;
			
			this.spTopArrow.x = (this.spBg.width - this.spTopArrow.width) / 2;
			
			this.spSlider.x = (this.spBg.width - this.spSlider.width) / 2;
			this.spSlider.y = this.spHit.y;
			
			this.spBottomArrow.x = (this.spBg.width - this.spBottomArrow.width) / 2;
			this.spBottomArrow.y = this.spBg.height - this.spBottomArrow.height;			
		}
		
		private function SliderStartDragHandler(evt:MouseEvent):void
		{
			this.spSlider.startDrag(false, new Rectangle((this.spBg.width - this.spSlider.width) / 2,
				Math.round(this.spTopArrow.y + this.spTopArrow.height), 0,
				Math.round(this.spHit.height - this.spSlider.height)));
			this.stage.addEventListener(MouseEvent.MOUSE_MOVE, this.StartMoveScrollHandler);
			this.stage.addEventListener(MouseEvent.MOUSE_UP, this.StopSliderDragHandler);
		} 
		
		private function StartMoveScrollHandler(evt:MouseEvent):void
		{
			var rect:Rectangle = this.target.scrollRect;
			rect.y = Math.round((this.spSlider.y - this.spHit.y) /
				(this.spHit.height - this.spSlider.height) * (this.targetHeight - rect.height));
			this.target.scrollRect = rect;
		}
		
		private function StopSliderDragHandler(evt:MouseEvent):void
		{
			this.spSlider.stopDrag();
			this.stage.removeEventListener(MouseEvent.MOUSE_MOVE, this.StartMoveScrollHandler);
			this.stage.removeEventListener(MouseEvent.MOUSE_UP, this.StopSliderDragHandler);
		}
		
		private function TopArrowPressHandler(evt:MouseEvent):void
		{
			this.spTopArrow.addEventListener(Event.ENTER_FRAME, this.StartScrollUpHandler);
			this.parent.stage.addEventListener(MouseEvent.MOUSE_UP, this.TopArrowReleaseHandler);
		}
		
		private function TopArrowReleaseHandler(evt:MouseEvent):void
		{
			this.spTopArrow.removeEventListener(Event.ENTER_FRAME, this.StartScrollUpHandler);
			this.parent.stage.removeEventListener(MouseEvent.MOUSE_UP, this.TopArrowReleaseHandler);
		}
		
		private function StartScrollUpHandler(evt:Event):void
		{
			if (this.target.scrollRect.y > 0)
			{
				var rect:Rectangle = this.target.scrollRect;
				rect.y -= 5;
				if (rect.y <= 0)
				{
					rect.y = 0;
				}
				this.target.scrollRect = rect;
				this.spSlider.y = Math.round(this.spHit.y + rect.y *
					(this.spHit.height - this.spSlider.height) / (this.targetHeight - rect.height));
			}
		} 
		
		private function BgClickHandler(evt:MouseEvent):void
		{
			this.spSlider.y = this.mouseY - this.spSlider.height / 2;
			if (this.spSlider.y < this.spHit.y)
			{
				this.spSlider.y = this.spHit.y;
			}
			else if ((this.spSlider.y + this.spSlider.height) > (this.spHit.y + this.spHit.height))
			{
				this.spSlider.y = this.spHit.y + this.spHit.height - this.spSlider.height;
			}
			var rect:Rectangle = this.target.scrollRect;
			rect.y = Math.round((this.spSlider.y - this.spHit.y) /
				(this.spHit.height - this.spSlider.height) *
				(this.targetHeight - this.target.scrollRect.height));
			this.target.scrollRect = rect;
		}
		
		private function BottomArrowPressHandler(evt:MouseEvent):void
		{
			this.spBottomArrow.addEventListener(Event.ENTER_FRAME, this.StartScrollDownHandler);
			this.parent.stage.addEventListener(MouseEvent.MOUSE_UP, this.BottomArrowReleaseHandler);
		}
		
		private function BottomArrowReleaseHandler(evt:MouseEvent):void
		{
			this.spBottomArrow.removeEventListener(Event.ENTER_FRAME, this.StartScrollDownHandler);
			this.parent.stage.removeEventListener(MouseEvent.MOUSE_UP, this.BottomArrowReleaseHandler);
		}
		
		private function StartScrollDownHandler(evt:Event):void
		{
			if (this.target.scrollRect.y < (this.targetHeight - this.target.scrollRect.height))
			{
				var rect:Rectangle = this.target.scrollRect;
				rect.y += 5;
				if (rect.y >= this.targetHeight)
				{
					rect.y = this.targetHeight;
				}
				this.target.scrollRect = rect;
				this.spSlider.y = Math.round(this.spHit.y + rect.y *
					(this.spHit.height - this.spSlider.height) / (this.targetHeight - rect.height));
			}
		}		
	}
}