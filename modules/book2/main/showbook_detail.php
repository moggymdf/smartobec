<?php
session_start();
include '../../../database_connect.php'; 
include 'function.php'; 

$userlogin=$_SESSION['login_user_id'];
if(isset($_SESSION['role_id'])){
$user_role_id=mysqli_real_escape_string($connect,$_SESSION['role_id']);
}else {$user_role_id=""; 
?>
<script langquage='javascript'>
window.location="?option=book2&task=main/roleperson";
</script>
<?php
}

//header("content-type:text/javascript;charset=utf-8");   
//เช็ค SESSION ผู้เข้าระบบ
//$userlogin=$_SESSION['login_user_id'];
//บทบาทที่เข้ามาด้วย
//หาสิทธิ์ของผู้ใช้งาน
/*    $sql_roleuser="select * from book2_roleuser where person_id=? order by sa_node_id ASC";
    $query_roleuser = $connect->prepare($sql_roleuser);
    $query_roleuser->bind_param("s", $userlogin);
    $query_roleuser->execute();
    $result_qroleuser=$query_roleuser->get_result();
        While ($result_roleuser = mysqli_fetch_array($result_qroleuser))
       {
            $roleuser_sa_node_id=$result_roleuser['sa_node_id'];
       }
*/
//ตรวจสอบการ นำเข้าข้อมูล
if(isset($_GET['id'])){
$booksendid=mysqli_real_escape_string($connect,$_GET['id']);
}else {$booksendid=""; header("location:../../../index.php");
}
$roleid_person=$_SESSION["roleid_person"];
//หาชื่อบทบาทของบุคลากร
    $sql_role_person="select * from book2_roleperson where id=?  ";
    $query_role_person = $connect->prepare($sql_role_person);
    $query_role_person->bind_param("i", $roleid_person);
    $query_role_person->execute();
    $result_qrole_person=$query_role_person->get_result();

    While ($result_role_person = mysqli_fetch_array($result_qrole_person))
   {
        $role_id=$result_role_person['role_id']; 
        $level_dep=$result_role_person['level_dep'];  
        $look_dep_subdep=$result_role_person['look_dep_subdep'];  
   
        if($level_dep=='2' || $level_dep=='4' || $level_dep>='6' && $level_dep<='12' ){
            $searchorg="book2_department";
        }else{
            $searchorg="book2_subdepartment";            
        }
//หาชื่อบทบาท
    $sql_role="select * from book2_role  where id=?  ";
    $query_role = $connect->prepare($sql_role);
    $query_role->bind_param("i", $role_id);
    $query_role->execute();
    $result_qrole=$query_role->get_result();
    
    While ($result_role = mysqli_fetch_array($result_qrole))
   {
        $role_id=$result_role['id'];
        $name_role=$result_role['name'];
   }
        
//หาชื่อหน่วยงาน
    $sql_dep="select * from $searchorg  where id=?  ";
    $query_dep = $connect->prepare($sql_dep);
    $query_dep->bind_param("i", $look_dep_subdep);
    $query_dep->execute();
    $result_qdep=$query_dep->get_result();
    
    While ($result_dep = mysqli_fetch_array($result_qdep))
   {
        $name_predepart=$result_dep['nameprecis'];
        $name_depart=$result_dep['name'];
   }

}


        //แสดงหนังสือที่ส่ง
            $sql_booksendst="select * from book2_system where id=? ";
            $query_booksendst = $connect->prepare($sql_booksendst);
            $query_booksendst->bind_param("s", $booksendid);
            $query_booksendst->execute();
            $result_qbooksendst=$query_booksendst->get_result();

         While ($result_booksendst = mysqli_fetch_array($result_qbooksendst))
           {
                $getref_id=$result_booksendst['book_refid'];
                $receiver_status=$result_booksendst['receiver_status'];                
                $book2system_booktable=$result_booksendst['book_table']; 
            }

        if($book2system_booktable=="PP"){
           //แสดงหนังสือรอส่ง
            $sql_booksend="select * from book2_receive where book_refid=? ";
            $query_booksend = $connect->prepare($sql_booksend);
            $query_booksend->bind_param("s", $getref_id);
            $query_booksend->execute();
            $result_qbooksend=$query_booksend->get_result();

         While ($result_booksend = mysqli_fetch_array($result_qbooksend))
           {
                $booksend_no=$result_booksend['book_no'];
                $booksend_date=$result_booksend['book_date'];
                $booksend_subject=$result_booksend['book_subject'];
                $booksend_for=$result_booksend['book_for'];
                $booksend_detail=$result_booksend['book_detail'];
                $booksend_comment=$result_booksend['book_comment'];
                $booksend_from=$result_booksend['book_from'];
                $booksend_level=$result_booksend['book_level'];
                $booksend_secret=$result_booksend['book_secret'];
            }
            
            $sa_node_name=$booksend_from;
            $sanodefrom_nameprecis=$booksend_from;

            }else{
        //แสดงหนังสือรอส่ง
            $sql_booksend="select * from book2_send where book_refid=? ";
            $query_booksend = $connect->prepare($sql_booksend);
            $query_booksend->bind_param("s", $getref_id);
            $query_booksend->execute();
            $result_qbooksend=$query_booksend->get_result();

         While ($result_booksend = mysqli_fetch_array($result_qbooksend))
           {
                $booksend_no=$result_booksend['book_num'];
                $booksend_date=datesql2show($result_booksend['book_date']);
                $booksend_subject=$result_booksend['book_subject'];
                $booksend_for=$result_booksend['book_for'];
                $booksend_detail=$result_booksend['book_detail'];
                $booksend_comment=$result_booksend['book_comment'];
                $booksend_from=$result_booksend['book_fromdepartment'];
                $booksend_level=$result_booksend['book_level'];
                $booksend_secret=$result_booksend['book_secret'];
            }
            //หาชื่อหน่วยงาน
                $sql_sanode="select * from book2_department where id=? ";
                $query_sanode = $connect->prepare($sql_sanode);
                $query_sanode->bind_param("s", $booksend_from);
                $query_sanode->execute();
                $result_qsanode=$query_sanode->get_result();
                    While ($result_sanode = mysqli_fetch_array($result_qsanode))
                   {
                            $sa_node_name=$result_sanode['name'];
                   }
            
            }    
            
            
            //ถ้าส่งคืนให้แสดงหน่วยงานที่ส่งคืน
            $book2systemrt_returncomment="";
         if($receiver_status=="12"){
             //ค้นหาเหตุผลการส่งคืน
             $sql_booksystemrt="select receiver_comment,sender_department from book2_system where id=?  ";
             $query_booksystemrt = $connect->prepare($sql_booksystemrt);
             $query_booksystemrt->bind_param("s",$booksendid);
             $query_booksystemrt->execute();
             $result_qbooksystemrt=$query_booksystemrt->get_result();
                 While ($result_booksystemrt = mysqli_fetch_array($result_qbooksystemrt))
            {
                 $book2systemrt_returncomment=$result_booksystemrt['receiver_comment']; 
                 $book2systemrt_sender_department=$result_booksystemrt['sender_department']; 
            }
    //หาชื่อหน่วยงานจากโหนด
        $sql_sanodefrom="select * from book2_department where id=?";
        $query_sanodefrom = $connect->prepare($sql_sanodefrom);
        $query_sanodefrom->bind_param("s", $book2systemrt_sender_department);
        $query_sanodefrom->execute();
        $result_qsanodefrom=$query_sanodefrom->get_result();
        While ($result_sanodefrom = mysqli_fetch_array($result_qsanodefrom))
       {    
            $sanodefrom_name=$result_sanodefrom['name'];
            $sanodefrom_nameprecis=$result_sanodefrom['nameprecis'];
        }

            //ถ้าส่งคืนให้แสดงหน่วยงานที่ส่งมาคืน
            $name_depart=$sanodefrom_name;
             $showreturn_depart=" <font color=red>[ส่งคืนโดย ".$sanodefrom_nameprecis."]</font>";
             }else{$showreturn_depart="";}



//$book_status="6";    //กรณีออกเลยแล้วรอส่ง 


?>

<html>
    <head>
        
    </head>
 
   <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

<!-- Your Page Content Here -->

    <!-- ใส่เนื้อหาตรงนี้ -->
        <!-- Main content -->
        <section class="content">

            <div class="box container">
                <div class="box-header">
                  <h3 class="box-title">รายละเอียดหนังสือราชการ<?php echo $book2system_booktable; ?></h3>
                  <div class="box-tools pull-right">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>                      
                  </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body  container">
                
                <!-- Form รับค่า หนังสือ -->    
                    
                    <div class="row" style="padding-bottom: 5px;">
                        <div class="form-group">
                            <label for="bookno" class="col-sm-2" style="width: 120px" > เลขที่หนังสือ</label>
                            <div  class="col-sm-3 text-left" >
                                <?php echo $booksend_no;?>
                            </div>
                             <label for="bookdate" class="col-sm-2" style="width: 80px" > ลงวันที่</label>
                              <div  class="col-sm-3 text-left" >
                              <?php echo $booksend_date;?>
                            </div>
                       </div>
                </div>
    
                 <div class="row" style="padding-bottom: 5px;">
                        <div class="form-group">
                            <label for="membooklevel" class="col-sm-2" style="width: 120px" >
                                ชั้นความเร็ว</label>
                            <div  class="col-sm-6 text-left">
<?php
//หาชื่อหน่วยงาน
    $sql_booklevel="select * from book2_level where id=? and status=1 ";
    $query_booklevel = $connect->prepare($sql_booklevel);
    $query_booklevel->bind_param("i", $booksend_level);
    $query_booklevel->execute();
    $result_qbooklevel=$query_booklevel->get_result();
   
    While ($result_booklevel = mysqli_fetch_array($result_qbooklevel))
   {
         echo $result_booklevel['book_level']; 
    }
?>
                                  </select>
                            </div>
                       </div>
                </div>
                 <div class="row" style="padding-bottom: 5px;">
                        <div class="form-group">
                            <label for="membooksecret" class="col-sm-2" style="width: 120px" >
                                 ชั้นความลับ</label>
                            <div  class="col-sm-6 text-left">
<?php
//หาชื่อหน่วยงาน
    $sql_booksecret="select * from book2_secret where id=? and status=1 ";
    $query_booksecret = $connect->prepare($sql_booksecret);
    $query_booksecret->bind_param("i", $booksend_secret);
    $query_booksecret->execute();
    $result_qbooksecret=$query_booksecret->get_result();
    While ($result_booksecret = mysqli_fetch_array($result_qbooksecret))
   {
            echo $result_booksecret['book_secret'];
    }
?>

                                  </select>
                            </div>
                       </div>
                </div>

                <div class="row" style="padding-bottom: 5px;">
                        <div class="form-group">
                            <label for="booksubject" class="col-sm-2" style="width: 120px" >
                                 เรื่อง</label>
                            <div  class="col-sm-6 text-left" >
                                <?php echo $booksend_subject;?>
                            </div>
                       </div>
                </div>

                <div class="row" style="padding-bottom: 5px;">
                        <div class="form-group">
                            <label for="bookfor" class="col-sm-2" style="width: 120px" >
                                 เรียน</label>
                            <div  class="col-sm-6 text-left" >
                                <?php echo $booksend_for;?>
                            </div>
                       </div>
                </div>

                  <div class="row" style="padding-bottom: 5px;">
                        <div class="form-group">
                            <label for="bookdetail" class="col-sm-2" style="width: 120px" >
                                 รายละเอียด</label>
                            <div  class="col-sm-6 text-left" >
                             <?php echo $booksend_detail;?>
                            </div>
                       </div>
                </div>
                    
                <div class="row" style="padding-bottom: 5px;">
                        <div class="form-group">
                            <label for="membookto" class="col-sm-1" style="width: 120px" >
                                 ถึง</label>

                                <div  class="col-sm-6 text-left  fom-control" >

                                <?php echo $name_depart;?>        

                                </div>

                        </div>
                </div>
                    
<?php

?>
                    
                    
                 <div class="row" style="padding-bottom: 5px;">
                        <div class="form-group">
                            <label for="membookfrom" class="col-sm-2" style="width: 120px" >
                                 จาก</label>
                            <div  class="col-sm-6 text-left" >
                                <?php echo $sa_node_name.$showreturn_depart;?>
                            </div>
                       </div>
                </div>
                   
                  <div class="row" style="padding-bottom: 5px;">
                        <div class="form-group">
                            <label for="bookcomment" class="col-sm-2" style="width: 120px" >
                                 หมายเหตุ</label>
                            <div  class="col-sm-6 text-left" >
                                <?php echo $booksend_comment;?>
                            </div>
                       </div>
                </div>
              
                  <div class="row" style="padding-bottom: 5px;">
                        <div class="form-group">
                            <label for="bookcomment" class="col-sm-2" style="width: 120px" >
                                 ไฟล์แนบ</label>
                            <div  class="col-sm-6 text-left" ><b>(แสดงเฉพาะไฟล์แนบที่ส่งเหมือนกันทุกหน่วยงาน)</b><BR>

<?php
//echo  $count.". ".$image_src[$count]."<br>";

    $sql_bookfile="SELECT * FROM book2_file where book_refid=? ";
    $query_bookfile = $connect->prepare($sql_bookfile);
    $query_bookfile->bind_param("s", $getref_id);
    $query_bookfile->execute();
    $result_qbookfile=$query_bookfile->get_result();

    $j=1;
    $myupload="uploadsmy";
    While ($result_bookfile = mysqli_fetch_array($result_qbookfile))
   {
        //echo $j.". ";
        //echo $result_booklevel["file_name"];   
        ?>
        <div  class="row container table-hover" style='margin-top:10px; '>
         <label><a href="<?php echo $myupload;?>/<?php echo $result_bookfile["file_path"];?>" target="_blank">
                <?php echo $j; ?>. <?php   echo $result_bookfile["file_name"];?>
                </a></label>
        
        </div>                       
        <?php
        $j++;
   }
 
?>




                            </div>
                       </div>
                </div>
                
