
$(function(){
  // Get Canvas element
  var canvas= $("canvas")[0];
  // Create new Image object
  var image= new Image;
  // Draw Image on Canvas when it load and add some text
  image.onload= function(){
    // Set Canvas
    canvas.width = image.width;
    canvas.height = image.height;
    // Get Canvas context and draw image
    var ctx = canvas.getContext("2d");
    ctx.drawImage(image, 0, 0);
    // Add text to canvas
    ctx.shadowColor = "#fff";
    ctx.shadowOffsetX = 0;
    ctx.shadowOffsetY = 0;
    ctx.shadowBlur = 10;
    ctx.fillStyle= "#EDEBA1";
    ctx.font= "24px Tahoma";
    ctx.fillText("@mitgux", 30, 265);
  };
  // Set image src
  image.src= "img/tree.png";

  // Convert DataURL to Blob object
  function dataURLtoBlob(dataURL) {
    // Decode the dataURL    
    var binary = atob(dataURL.split(',')[1]);
    // Create 8-bit unsigned array
    var array = [];
    for(var i = 0; i < binary.length; i++) {
        array.push(binary.charCodeAt(i));
    }
    // Return our Blob object
    return new Blob([new Uint8Array(array)], {type: 'image/png'});
  }
  
  // Send IT
  $("#upCanvas").live("click", function(){
    $("#upCanvas").html("<img src='img/load.gif' alt='load'>&nbsp;&nbsp;&nbsp;Uploading ..");
    // Convert Canvas DataURL
    var dataURL= canvas.toDataURL();

    // Get Our File
    var file= dataURLtoBlob(dataURL);
    
    // Create new form data
    var fd = new FormData();
    
    // Append our image
    fd.append("imageNameHere", file);

    $.ajax({
       url: "uploadFile.php",
       type: "POST",
       data: fd,
       processData: false,
       contentType: false,
    }).done(function(respond){
      $("#upCanvas").html("Upload This Canvas");
        $(".return-data").html("Uploaded Canvas image link: <a href="+respond+">"+respond+"</a>").hide().fadeIn("fast");
    });
  });

});