
<style>
*{ margin:0 ;
padding:0;}
.bdr{border:solid 1px #00CC33 !important;}
</style>
<div align="center" style="width:100%; height:auto; margin-bottom:10px;">
    <div align="center" style="width:1000px; height:auto;">
    	
        <div style="width:998px; height:262px; background-image:url(<?=IMAGES_URL?>backimg.png); background-repeat:repeat-x; border:#cfcfcf solid 4px;">
        
        	<div class="headingfind" style="height:115px; width:998px;">
            FIND & FUND YOUR <br />
			NEXT FAVORITE TEE !
            </div>
            
            <div class="subdetils" style="height:48px; width:998px;">
            The Best Place to discover, support and promote t-shirt designs <br />
            
            creates by a passionate community of artists. <br/>
            </div>
            
            <div class="subline" style="height:28px; width:998px;">
            Watch our vide to learn more <img src="<?=IMAGES_URL?>bluearrow.png" />
            </div>
            
            <div class="createtee" style="background-image:url(<?=IMAGES_URL?>startbluebtn.png); background-repeat:no-repeat;">
            	<span style="padding-right:15px;">
                <b>Create a tee</b>
               </span>
            </div>
        
        </div>
        
        
        
        <div style="width:1000px; height:93px;">
        	<div style="width:1000px; height:10px;">
            </div>
            
            <div class="dicvrsupprt" align="center" style="width:340px; height:83px; background-image:url(<?=IMAGES_URL?>discvrsupprt.png); background-repeat:no-repeat;">
        		<b> Discover | Support </b>
            </div>
        </div>
        
      
      
       <div style="margin:0 0 0 18px;  display:inline-block;"> 
       
    <!--   <div style="width:10px; height:200px; float:left; border:#003 solid 2px;">
       </div>
        -->
   		 <?php
		  $k=0;
	     for($i=0; $i<4; $i++)
		 {
			
		?>
      
        <div style="width:305px; height:400px; margin-right:20px; float:left; margin-bottom:30px; border:#F00 solid 1px;">
        	
            <div style="width:287px; padding:8px; height:330px; margin-right:20px;">
            	
                <div style="display:inline-block; width:282px; height:285px;">
                	<img  src="<?=IMAGES_URL?>var.png"/>
                </div>
                
                
                
                <div  align="left" style="padding:10px 0 0 0; ">
                	<label class="picname">
                    	<b>RAINBOW GIRL</b>
                    </label>
                    <br />
                    <label class="by"> by  </label>
                    <label class="authorname">
                    	hanib
                    </label>
                </div>
              
              </div>  
              
              
             <div style="width:305px; height:70px; margin-right:20px; margin-bottom:30px;">
          		 
                  <div   align="left" style="  background-color:#e4e3e3; border:#cfcfcf solid 5px;">
                                 	<div style="width:200px; height:20px; margin-bottom:5px; margin-left:8px;">
                                    	<label style="font-family:'Arial'; font-size:17px; color:#0facfd">
                                        	<b>$20.00</b>
                                        </label>
                                        <label style="font-family:'Arial'; font-size:14px; color:#606060; font-weight:normal;">
                                        	63 of 50 reserved
                                        </label>
                                    </div>
                                
                                <?php
								$w=100;
                                ?>    
                               <div style="width:100%;height:8px;border:1px solid #319ebc ;border-radius:8px; ">
                           		<div align="center" style="height:8px; width:<?=$w?>%; border-radius:8px;border:0px solid #319ebc ; background-color:#319ebc; ">
                                </div>
                              </div>
                                    
                                    <div style="margin-top:5px; margin-left:8px;">
                                    	<div style="width:13px; height:14px; float:left;">
                                    	   <img src="<?=IMAGES_URL?>timerclock.png" />
                                        </div>
                                    	<label style="font-family:'Arial'; font-size:10px; color:#606060; font-weight:normal;">
                                        	&nbsp;2 days and 20 hours left
                                        </label>
                                    </div>
                                
                                    
           </div>
                
            </div>
            
       </div>
            
        <?php
		
           $k++;
           if($k%3==0)
           {
               ?>
                  <br/>
               <?php
           }
		}
		?>
           </div>
        
    </div>
</div>