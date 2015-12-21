<?php
//$albumId= getRequest()->getParam('albumId');
//echo "fdgg"; die;
$albumId=$_REQUEST['albumId'];
echo $albumId; 
?>
<config>
<settings>
<musicFolder>http://192.168.1.99/sites/nozyu/images/music/</musicFolder>
<picturesFolder>demos/mp3_player/pics/</picturesFolder>
<showPlaylist>true</showPlaylist>
<defaultCover>
<show>false</show>
<width>100</width>
<imageURL>cover.jpg</imageURL>
</defaultCover>
<logo>
<show>false</show>
<imageURL>logo.png</imageURL>
<href>http://www.spencer-tech.com</href>
</logo>
<button>
<show>false</show>
<text/>
<href/>
</button>
<showBtnHtml>false</showBtnHtml>
<showBtnInfo>false</showBtnInfo>
</settings>
<song>
<artist>Artist #1</artist>
<title>Title #1</title>
<length>253</length>
<fileName>134856624701 - Tumhi Ho Bandhu.mp3</fileName>
<cover>cover1.jpg</cover>
</song>
<song>
<artist>Artist #2</artist>
<title>Title #2</title>
<length>209</length>
<fileName>134856624702 - Cocktail - Daaru Desi [DM].mp3</fileName>
<cover>cover2.jpg</cover>
</song>
</config>