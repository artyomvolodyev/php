<?php

/*
odes�l�n� pomoc� metody POST a v�z�no funkc� getProduct() */

?>

<!DOCTYPE HTML>
    <html>
    <head>
    
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <title>Designs</title>
    
    <!-- Style sheets -->
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="css/jquery.fancyProductDesigner-fonts.css" />
	<style type="text/css">
		.fpd-recreation {
			width: 575px;
			background: white;
			position: relative;
			margin: 0 auto;
		}
		
		.fpd-recreation div {
			position: relative;
			height: 570px;
            /*margin-left: -42px;*/
		}
		
		.fpd-recreation img, .fpd-recreation canvas, .fpd-recreation span {
			position: absolute;
		}
	</style>
    
    <!-- vlo�en� js  -->
	<script src="js/jquery.min.js" type="text/javascript"></script>
          <script type="text/javascript" src="jquery-1.6.2.js"></script>
    <script type="text/javascript" src="html2canvas.js"></script>   
	<script type="text/javascript">

		jQuery(document).ready(function() {
			
			//p��jem metody $_POST			
			recreateProduct('#recreation-container', <?php echo stripslashes($_POST['recreation_product']); ?>);

			function recreateProduct(container, product) {

				//konverze
				var _HexToR = function(h) {return parseInt((_cutHex(h)).substring(0,2),16)};
				var _HexToG = function(h) {return parseInt((_cutHex(h)).substring(2,4),16)};
				var _HexToB = function(h) {return parseInt((_cutHex(h)).substring(4,6),16)};
				var _cutHex = function(h) {return (h.charAt(0)=="#") ? h.substring(1,7):h};
				
				var $container = $(container).addClass('fpd-recreation');

				if(product.length && product.length > 1) {
					for(var i=0; i < product.length; ++i) {
						$container.append('<div></div>');
						_createSingleProduct($container.children('div:last'), product[i]);
					}
				}
				else {
					_createSingleProduct($container, product);
				}
				
				function _createSingleProduct($productContainer, product) {
					for(var i=0; i < product.elements.length; ++i) {
					
						var element = product.elements[i],
							elementParameters = product.elements[i].parameters;
	
						//vytvo�en� canvas
						if(elementParameters.text) {
							
							$productContainer.append('<span>'+elementParameters.text+'</span>')
							.children('span:last').css({left: elementParameters.x, top: elementParameters.y, 'z-index': elementParameters.z, 
														color: elementParameters.currentColor, 'fontFamily': elementParameters.font, 'fontSize': elementParameters.textSize});
														
						}
						//vytvo�en� textu
						else if(elementParameters.currentColor) {
							alert(element.source);
							var image = new Image();
							image.src = element.source;
							$(image).data('params', elementParameters);
							image.onload = function() {
								var canvas = document.createElement('canvas'), canvasContext = canvas.getContext('2d'),
									params = $(this).data('params');
								canvas.width = this.width;
								canvas.height = this.height;
								canvasContext.drawImage(this, 0, 0);
								var imageData = canvasContext.getImageData(0, 0, canvas.width, canvas.height);
							    var data = imageData.data;
							    for (var j = 0; j < data.length; j += 4) {
							        data[j] = _HexToR(params.currentColor);
							        data[j + 1] = _HexToG(params.currentColor);
							        data[j + 2] = _HexToB(params.currentColor);
							    }
							    // p�eps�n� p�vodn�ho textu
							    canvasContext.putImageData(imageData, 0, 0);
								$productContainer.append(canvas);
								$(canvas).width(params.width).height(params.height).css({left: params.x, top: params.y, 'z-index': params.z});
							}
							
						}
						//vytvo�en� obr�zku
						else {
							$productContainer.append('<img src="'+element.source+'" width='+elementParameters.width+' height='+elementParameters.height+' />')
							.children('img:last').css({left: elementParameters.x, top: elementParameters.y, 'z-index': elementParameters.z});
							
						}
						
						//stupe� rotace
						if(elementParameters.degree) {
						
							var lastElement = $productContainer.children(':last');
							lastElement.css('-moz-transform', 'rotate('+elementParameters.degree+'deg)');
					        lastElement.css('-webkit-transform', 'rotate('+elementParameters.degree+'deg)');
					        lastElement.css('-o-transform', 'rotate('+elementParameters.degree+'deg)');
					        lastElement.css('-ms-transform', 'rotate('+elementParameters.degree+'deg)');
					        
						}
						
					}
				};
				
				
			};
	
		});
    </script>

              <script type="text/javascript">
      $(document).ready(function() {
        html2canvas( [ '#recreation-container' ], {
          onrendered: function(canvas) {
            document.body.appendChild(canvas);
          }
        });
      });
    </script>
    </head>
    
    <body>
    	<div class="container">
    		<div id="recreation-container">
    		</div>  
    	</div>
        
    </body>
</html>