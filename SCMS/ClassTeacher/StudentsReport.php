<?php
session_start();

error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';
 ?>
<script language="javascript" type="text/javascript">
function f2()
{
window.close();
}
function f3()
{
window.print(); 
}
</script>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Students Report</title>
</head>
<body>

<div style="margin-left:50px;">
<?php  

$StudentID=intval($_GET['StudentID']);
$ret=mysqli_query($con,"SELECT tblfoodtracking.OrderCanclledByUser,tblfoodtracking.remark,tblfoodtracking.status FROM tblfoodtracking WHERE student.StudentID ='$StudentID'");
$cnt=1;


 ?>
<table border="1"  cellpadding="10" style="border-collapse: collapse; border-spacing:0; width: 100%; text-align: center;">
  <tr align="center">
   <th colspan="4" >Report of #<?php echo  $StudentID;?></th> 
  </tr>
  <tr>
    <tr>
                            <td>Cell</td>
                            <td>Nested Table
                            </td>
                          </tr>
                          <tr>
                            <td>Cell2</td>
                            <td>Nested Table2
                            </td>
                          </tr>
                          <tr>
                            <td>Cell</td>
                            <td>Nested Table
                            </td>
                          </tr>
                          <tr>
                            <td>Cell2</td>
                            <td>Nested Table2
                            </td>
                          </tr>
                          <tr>
                            <td>Cell2</td>
                            <td>Nested Table2
                            </td>
                          </tr>
  </tr>
</table>
     
     <p >
      <input name="Submit2" type="submit" class="txtbox4" value="Close" onClick="return f2();" style="cursor: pointer;"  />   <input name="Submit2" type="submit" class="txtbox4" value="Print" onClick="return f3();" style="cursor: pointer;"  /></p>
</div>

</body>
</html>

     