/**
* GNU
**/
package com.flash.modules.utils
{	
	import flash.display.Bitmap;
	import flash.display.BitmapData;
	import flash.display.DisplayObjectContainer;
	import flash.geom.Matrix;
	import flash.geom.Rectangle;
	
	public class UBitmap
	{
		// methods
		public static function Resize(target:DisplayObjectContainer, sliceRect:Rectangle,
			newWidth:uint, newHeight:uint):Bitmap
		{
			var newBD:BitmapData = new BitmapData(newWidth, newHeight, true, 0x000000);
			
			var cols:Array = [0, sliceRect.left, sliceRect.right, target.width];
			var rows:Array = [0, sliceRect.top, sliceRect.bottom, target.height];
			
			var dCols:Array = [0, sliceRect.left, newWidth - target.width + sliceRect.right, newWidth];
			var dRows:Array = [0, sliceRect.top, newHeight - target.height + sliceRect.bottom, newHeight];
	
			var origin:Rectangle;
			var draw:Rectangle;
			var mat:Matrix = new Matrix();
			
			for(var cx:uint = 0; cx < 3; cx++) 
			{
				for(var cy:uint = 0; cy < 3; cy++) 
				{
					origin = new Rectangle(cols[cx], rows[cy], cols[cx + 1] - cols[cx], rows[cy + 1] - rows[cy]);
					draw = new Rectangle(dCols[cx], dRows[cy], dCols[cx + 1] - dCols[cx], dRows[cy + 1] - dRows[cy]);
						
					mat.identity();
					mat.a = draw.width / origin.width;
					mat.d = draw.height / origin.height;
					mat.tx = draw.x - origin.x * mat.a;
					mat.ty = draw.y - origin.y * mat.d;
					newBD.draw(target, mat, null, null, draw, true);
				}
			}
			return new Bitmap(newBD);
		}
	}
}