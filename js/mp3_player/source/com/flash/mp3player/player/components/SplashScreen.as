package com.flash.mp3player.player.components
{
	import com.flash.modules.text.Text;
	
	import flash.display.Sprite;
	import flash.display.DisplayObjectContainer;

	public class SplashScreen extends Sprite
	{
		//properties
		private var spBg:	Sprite;
		private var tfText:	Text;
		
		//constructor
		public function SplashScreen()
		{
			super();
		}
		
		//methods
		public function Init(parentContainer:DisplayObjectContainer):void
		{
			this.cacheAsBitmap = true;
			
			this.spBg = new Sprite();
			this.spBg.graphics.beginFill(0xffffff, 0.5);
			this.spBg.graphics.drawRect(0, 0, parentContainer.stage.stageWidth,
				parentContainer.stage.stageHeight);
			this.addChild(this.spBg);
			
			this.tfText = new Text("tfLoading", "Loading playlist...", "Tahoma", 11, 0x000000, "none",
				"dynamic", false, false, false, false, "center");
			this.tfText.mouseEnabled = false;
			this.tfText.width = parentContainer.stage.stageWidth;
			this.tfText.height = this.tfText.textHeight + 5;
			this.tfText.y = (parentContainer.stage.stageHeight - this.tfText.height) / 2;
			this.addChild(this.tfText);
		}		
	}
}