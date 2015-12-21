function recreateProduct(container, product) {
    var $container = $(container).addClass('fpd-recreation');
    if(product.isArray || product.length >= 1) { //its a product with multiple views
        for(var i=0; i < product.length; ++i) {
            $container.append('<div id="Tees'+i+'"></div>');
            _createSingleProduct($container.children('div:last'), product[i]);
        }
    } else { //its a product with one view
        $container.append('<div id="Tees0"></div>');
        _createSingleProduct($container.children('div:last'), product);
    }
}

//converts hex colors ro rgb
var _HexToR = function(h) {return parseInt((_cutHex(h)).substring(0,2),16)};
var _HexToG = function(h) {return parseInt((_cutHex(h)).substring(2,4),16)};
var _HexToB = function(h) {return parseInt((_cutHex(h)).substring(4,6),16)};
var _cutHex = function(h) {return (h.charAt(0)=="#") ? h.substring(1,7):h};

function _createSingleProduct($productContainer, product) {
    autoIncZIndex = 1;
    console.log('_createSingleProduct');
    console.log(product);
    console.log(product.elements);
    //loop through all elements
    for(var i=0; i < product.elements.length; ++i) {
        var element = product.elements[i];
        var elementParameters = product.elements[i].parameters;

        if(parseInt(elementParameters.z) == -1){
            console.log('auto inc z-index');
            elementParameters.z = autoIncZIndex;
            autoIncZIndex++;
        }

        //elementParameters.x = elementParameters.x - xOffset;
        //elementParameters.y = elementParameters.y - yOffset;

        if(elementParameters.text) {  //create text
            console.log('create text');
            console.log(element);
            /*elementParameters.x = elementParameters.x -480+250/2;
             elementParameters.y = elementParameters.y-300+300/2;*/
            $productContainer.append('<span>'+elementParameters.text+'</span>')
                .children('span:last').each(function () {
                    /*console.log($(this));
                     console.log('text w1: '+$(this).width());
                     console.log('text h1: '+$(this).height());*/
                    //elementParameters.x = (500/960)*elementParameters.x - $(this).width()/2;
                    //elementParameters.y = (300/300)*elementParameters.y - $(this).height()/2;

                    /*$(this).css({
                     'transform': 'scale('+parseFloat(elementParameters.scale)+')'
                     });*/

                    /*console.log((this));

                     console.log('text w3: '+ this.getBoundingClientRect().width);
                     console.log('text h3: '+ this.getBoundingClientRect().height);
                     console.log(elementParameters.x);
                     console.log(elementParameters.y);*/
                    elementParameters.x = elementParameters.x - ((960-500)/2) - ($(this).width()/2);
                    elementParameters.y = elementParameters.y - ((300-300)/2) - ($(this).height()/2);
                    console.log('res x: '+elementParameters.x);
                    console.log('res y: '+elementParameters.y);

                    $(this).css({
                        left: elementParameters.x,
                        top: elementParameters.y,
                        'z-index': elementParameters.z,
                        color: elementParameters.currentColor,
                        'fontFamily': elementParameters.font,
                        'fontSize': elementParameters.textSize/*,
                         'transform': 'scale('+parseFloat(elementParameters.scale)+')'*/
                    });
                });
            _rotateElementAndScale($productContainer.children('span:last'), parseFloat(elementParameters.degree), parseFloat(elementParameters.scale));
        }else if(elementParameters.currentColor) { //create canvas
            console.log('create canvas');
            console.log(element);/*
             elementParameters.x = elementParameters.x -480+250/2;
             elementParameters.y = elementParameters.y-300+300/2;*/
            var image = new Image();
            image.src = element.source;
            $(image).data('params', elementParameters);
            image.onload = function() {
                var canvas = document.createElement('canvas');
                var canvasContext = canvas.getContext('2d');
                var params = $(this).data('params');

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

                // overwrite original image
                canvasContext.putImageData(imageData, 0, 0);
                $productContainer.append(canvas);
                if('width' in params){
                    canvasWidth = params.width;
                }else{
                    canvasWidth = this.width;
                }
                if('height' in params){
                    canvasHeight = params.height;
                }else{
                    canvasHeight = this.height;
                }

                //params.x = (500/960)*params.x - canvasWidth/2;
                //params.y = (300/300)*params.y - canvasHeight/2;

                params.x = params.x - ((960-500)/2) - (canvasWidth/2);
                params.y = params.y - ((300-300)/2) - (canvasHeight/2);

                //scale = parseFloat(params.scale);

                $(canvas).width(canvasWidth).height(canvasHeight).css({
                    left: params.x,
                    top: params.y,
                    'z-index': params.z
                });
                _rotateElementAndScale($productContainer.children('canvas:last'), params.degree, 1);
            };
        } else { //create just an image
            console.log('just an image');
            console.log(element);
            /*elementParameters.x = elementParameters.x -480+250/2;
             elementParameters.y = elementParameters.y-300+300/2;*/


            var img = new Image();
            img.src = element.source;
            $(img).data('params', elementParameters);

            if('width' in elementParameters){
                imgWidth = elementParameters.width;
                imgWidth = parseFloat(elementParameters.scale) * imgWidth;
                img.width = imgWidth;
            }

            if('height' in elementParameters){
                imgHeight = elementParameters.height;
                imgHeight = parseFloat(elementParameters.scale) * imgHeight;
                img.height = imgHeight;
            }

            img.onload = function() {
                var params = $(this).data('params');
                console.log($(this));
                console.log(this.width);
                console.log(this.height);
                console.log(params.x);
                console.log(params.y);
                params.x = params.x - ((960-500)/2) - (this.width/2);
                params.y = params.y - ((300-300)/2) - (this.height/2);
                console.log('res x: '+params.x);
                console.log('res y: '+params.y);
                $(this).css({
                    left: params.x,
                    top: params.y,
                    'z-index': params.z/*,
                     'transform': 'scale('+parseFloat(elementParameters.scale)+')'*/
                });
            };

            //console.log('imgWidth: '+imgWidth);
            //console.log('imgHeight: '+imgHeight);
            $productContainer.append(img);

            _rotateElementAndScale($productContainer.children('img:last'), elementParameters.degree, 1);
        }
    }
}

function _rotateElementAndScale(elem, degree, scale) {
    elem.css('-moz-transform', 'rotate('+degree+'deg) scale('+scale+')');
    elem.css('-webkit-transform', 'rotate('+degree+'deg) scale('+scale+')');
    elem.css('-o-transform', 'rotate('+degree+'deg) scale('+scale+')');
    elem.css('-ms-transform', 'rotate('+degree+'deg) scale('+scale+')');
}