/**
 * GNU
 **/
package com.flash.mp3player.player.components
{
import com.flash.modules.text.Text;
import com.flash.modules.utils.UBitmap;

import flash.display.Bitmap;
import flash.display.DisplayObjectContainer;
import flash.display.Sprite;
import flash.events.MouseEvent;
import flash.external.ExternalInterface;
import flash.geom.Rectangle;
import flash.system.System;

public class CodeButton extends Sprite
{
	//constants
	private const border : uint = 10;

	//properties
	private var spBtnBg : CodeBtnBgAsset;
	private var spBtn : CodeBtnAsset;

	private var spPanel : Sprite;
	private var spPanelBg : CodePanelBgAsset;
	private var spPanelView : CodePanelAsset;
	private var tfCode : Text;
	private var spBtnAsset : ColumnButtonAsset;
	private var spBtnCopy : Sprite;

	//constructor
	public function CodeButton()
	{
		super();
	}

	//methods
	public function Init() : void
	{
		this.cacheAsBitmap = true;
		this.useHandCursor = true;
		this.buttonMode = true;

		this.addEventListener(MouseEvent.MOUSE_OVER, this.OverHandler);
		this.addEventListener(MouseEvent.MOUSE_OUT, this.OutHandler);

		this.spBtnBg = new CodeBtnBgAsset();
		this.spBtnBg.visible = false;
		this.addChild(this.spBtnBg);

		this.spBtn = new CodeBtnAsset();
		this.spBtn.x = (this.spBtnBg.width - this.spBtn.width) / 2;
		this.spBtn.y = (this.spBtnBg.height - this.spBtn.height) / 2;
		this.addChild(this.spBtn);
	}

	public function ShowCode(parentContainer : DisplayObjectContainer) : void
	{
		this.spPanel = new Sprite();
		this.spPanel.addEventListener(MouseEvent.CLICK, this.HidePanelHandler);

		this.spPanelBg = new CodePanelBgAsset();
		this.spPanelBg.width = parentContainer.stage.stageWidth;
		this.spPanelBg.height = parentContainer.stage.stageHeight;
		this.spPanel.addChild(this.spPanelBg);

		this.spPanelView = new CodePanelAsset();
		this.spPanelView.alpha = 0.8;
		this.spPanelView.width = parentContainer.stage.stageWidth;
		this.spPanelView.height = parentContainer.stage.stageHeight;
		this.spPanel.addChild(this.spPanelView);

		var tfCopy : Text = new Text("tfCopy", "Copy to clipboard", "Tahoma", 11, 0xffffff,
				"none", "dynamic", false, false, false, false, "center");
		tfCopy.mouseEnabled = false;
		tfCopy.width = tfCopy.textWidth + 10;
		tfCopy.height = tfCopy.textHeight + 7;

		this.spBtnAsset = new ColumnButtonAsset();

		var bmp : Bitmap = UBitmap.Resize(this.spBtnAsset, new Rectangle(4, 12, 93, 7),
				tfCopy.width, tfCopy.height);

		this.spBtnCopy = new Sprite();
		this.spBtnCopy.useHandCursor = true;
		this.spBtnCopy.buttonMode = true;
		this.spBtnCopy.addEventListener(MouseEvent.CLICK, this.CopyHandler);
		this.spBtnCopy.addChild(bmp);
		this.spBtnCopy.addChild(tfCopy);

		var codeString : String = "<embed src='" + this.root.loaderInfo.url + "'" + "\n" +
				"type='application/x-shockwave-flash'" + "\n" +
				"width='" + parentContainer.stage.stageWidth + "' height='" +
				parentContainer.stage.stageHeight + "'" + "\n" +
				"bgcolor='#333333'" + "\n" +
				"allowFullScreen='false'> " + "\n" +
				"</embed>";

		this.tfCode = new Text("tfCode", codeString, "Tahoma", 11, 0xffffff, "none", "dynamic",
				false, true, true, true);

		if((this.tfCode.textHeight + this.spBtnCopy.height + this.border) < this.spPanelView.height - 2 * this.border)
		{
			this.tfCode.x = this.border;
			this.tfCode.y = this.border;
			this.tfCode.width = this.spPanelView.width - 2 * this.border;
			this.tfCode.height = this.tfCode.textHeight + 5;
			this.spBtnCopy.x = this.border;
			this.spBtnCopy.y = this.tfCode.y + this.tfCode.height + this.border;
		}
		else
		{
			//this.tfCode.width = this.spPanelView.width - 3 * this.border - this.spBtnCopy.width;
			//this.tfCode.height = this.spPanelView.height - 2* this.border;
			this.tfCode.visible = false;

			this.spBtnCopy.x = this.spPanel.width - this.spBtnCopy.width - this.border;
			this.spBtnCopy.y = this.border;
		}

		this.spPanel.addChild(this.tfCode);

		this.spPanel.addChild(this.spBtnCopy);

		parentContainer.addChild(this.spPanel);
	}

	public function ShowInfo(parentContainer : DisplayObjectContainer, message : String) : void
	{
		this.spPanel = new Sprite();
		this.spPanel.addEventListener(MouseEvent.CLICK, this.HidePanelHandler);

		this.spPanelBg = new CodePanelBgAsset();
		this.spPanelBg.width = parentContainer.stage.stageWidth;
		this.spPanelBg.height = parentContainer.stage.stageHeight;
		this.spPanel.addChild(this.spPanelBg);

		this.spPanelView = new CodePanelAsset();
		this.spPanelView.alpha = 0.8;
		this.spPanelView.width = parentContainer.stage.stageWidth;
		this.spPanelView.height = parentContainer.stage.stageHeight;
		this.spPanel.addChild(this.spPanelView);

		this.tfCode = new Text("tfCode", "", "Tahoma", 11, 0xffffff, "none", "dynamic",
				false, false, false, false);
		this.tfCode.htmlText = message;
		this.tfCode.width = this.tfCode.textWidth + 5;
		this.tfCode.height = this.tfCode.textHeight + 5;
		this.tfCode.x = (this.spPanelBg.width - this.tfCode.width) / 2;
		this.tfCode.y = (this.spPanelBg.height - this.tfCode.height) / 2;
		this.spPanel.addChild(this.tfCode);

		parentContainer.addChild(this.spPanel);
	}

	private function HidePanelHandler(evt : MouseEvent) : void
	{
		DisplayObjectContainer(evt.currentTarget).parent.removeChild(this.spPanel);
	}

	private function OverHandler(evt : MouseEvent) : void
	{
		this.spBtnBg.visible = true;
	}

	private function OutHandler(evt : MouseEvent) : void
	{
		this.spBtnBg.visible = false;
	}

	private function CopyHandler(evt : MouseEvent) : void
	{
		System.setClipboard(this.tfCode.text);
	}
}
}