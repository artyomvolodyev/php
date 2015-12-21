/**
* GNU
**/
package com.flash.modules.text
{
	import flash.text.TextField;
	import flash.text.TextFieldAutoSize;
	import flash.text.TextFieldType;
	import flash.text.TextFormat;
	import flash.text.TextFormatAlign;
	import flash.text.AntiAliasType;

	public class Text extends TextField
	{
		//constructor
		public function Text(name:String, txt:String, font:String = "Tahoma", size:uint = 11,
			color:Object = 0xFFFFFF, autoSize:String = "none", type:String = "dynamic",
			bold:Boolean = false, selectable:Boolean = false, multiline:Boolean = false,
			wordwrap:Boolean = false, align:String = 'left')
		{
			super();
			this.name = name;
			var format:TextFormat = new TextFormat();
			format.size = size;
			format.font = font;
			format.bold = bold;
			format.color = color;
			format.align = align;
			with (this) {
				defaultTextFormat = format;
				type = type;
				autoSize = autoSize;
				antiAliasType = AntiAliasType.ADVANCED;
				selectable = selectable;
				multiline = multiline;
				wordWrap = wordwrap;
				border = border;
				borderColor = borderColor;
				background = background;
				backgroundColor = backgroundColor;
				appendText(txt);
			}
		}		
	}
}