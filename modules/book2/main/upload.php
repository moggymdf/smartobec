<?php
$mysql_hostname = "localhost";
$mysql_user = "root";
$mysql_password = "";
$mysql_database = "t6";
$con = mysql_connect($mysql_hostname, $mysql_user, $mysql_password) or die("Opps some thing went wrong");
mysql_select_db($mysql_database, $con) or die("����������ͼԴ��Ҵ");
?>


<?php
if($_POST['image_form_submit'] == 1)
{
	$images_arr = array();
	foreach($_FILES['fileup']['name'] as $key=>$val){
		$image_name = $_FILES['fileup']['name'][$key];
		$tmp_name 	= $_FILES['fileup']['tmp_name'][$key];
		$size 		= $_FILES['fileup']['size'][$key];
		$type 		= $_FILES['fileup']['type'][$key];
		$error 		= $_FILES['fileup']['error'][$key];
		
		############ Remove comments if you want to upload and stored images into the "uploads/" folder #############
		
		$target_dir = "uploads/";
		$target_file = $target_dir.$_FILES['fileup']['name'][$key];
		if(move_uploaded_file($_FILES['fileup']['tmp_name'][$key],$target_file)){
			$images_arr[] = $target_file;
		//������Ұҹ������
		mysql_query("INSERT INTO test(name)VALUES('$image_name')");
		}
		
		//display images without stored
		//$extra_info = getimagesize($_FILES['images']['tmp_name'][$key]);
    	//$images_arr[] = "data:" . $extra_info["mime"] . ";base64," . base64_encode(file_get_contents($_FILES['images']['tmp_name'][$key]));
	}
	
	//Generate images view
	//if(!empty($images_arr)){ $count=0;
	//	foreach($images_arr as $image_src){ $count++?>
			<ul class="reorder_ul reorder-photos-list">
<?php
			$strSQL = "SELECT * FROM test";
			$objQuery = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
while($objResult = mysql_fetch_array($objQuery))
{

//		echo   $count.". ".$image_src[$count]."<br>";
?>	
		<?php echo $objResult["name"]."<BR>";

}
?>

		<?php// echo $images_arr ; echo $_FILES['image_src']['tmp_name'][$count];?>
<!--            	<li id="image_li_<?php echo $count; ?>" class="ui-sortable-handle">
                	<a href="javascript:void(0);" style="float:none;" class="image_link"><img src="<?php echo $image_src; ?>" alt=""></a>
               	</li>
-->          	</ul>
	<?php
	// }
	//}
}
?>