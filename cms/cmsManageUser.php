<html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>CMS Manage User</title>
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/style.css" rel="stylesheet">
        <link href="../images/gv32x32.ico" rel="shortcut icon" />
        
        <script type="text/javascript">
            function delete_id(id)
            {
                if(confirm('Are you sure you want to delete this record ?'))
                {
                    window.location.href='cmsManageUser.php?delete_id='+id;
                }
            }
            
            function update_id(id)
            {
                window.location.href='cmsupdateuser.php?update_id='+id;
            }
            
            function onload()
            {
                
                if (window.location.href.indexOf("update_id") > -1)
                {
                   document.getElementById("UserEditForm").style.display="block";
                }
                else 
                {
                    document.getElementById("UserEditForm").style.display="none";
                }
            }
            
        </script>
        
    </head>
    
    <body onload="onload()">
        <script src="../js/jquery.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/scripts.js"></script>
        <?php
            session_start();
            if(!isset($_SESSION['user']))
            {
                header("Location: index.php");
            }
            include_once ("../dbconnect.php");
            $reclimit = 5; //Set Record Limit
     
            if(isset($_GET['page'])){
                $page = $_GET['page'];
            } else {
                $page = 1;
            }
 
            //Setting the page limit
            $start = (($page-1) * $reclimit);
            $sql = "SELECT * FROM user_list";
            $records = $MySQLiconn->query($sql);
            $total = $records->num_rows; //Display Num of Row
            $tpages = ceil($total / $reclimit);
            
            echo '<script language="javascript">';
            echo 'document.getElementById("UserEditForm").style.display="none"';
            echo '</script>';
            
            $rec = "SELECT user_id, username, user_email, user_role, phone FROM user_list LIMIT $start, $reclimit";
            $records = $MySQLiconn->query($rec);
            
            //execute the SQL query and return records
            $resultUser = $MySQLiconn->query("SELECT * FROM user_list WHERE user_id=".$_SESSION['user']);
            $userRow = $resultUser->fetch_array();
            $resultCount = $MySQLiconn->query("select count(*) from user_list");   
            
            if(isset($_GET['delete_id']))
            {
                $sql_query=$MySQLiconn->query("DELETE FROM user_list WHERE user_id=".$_GET['delete_id']);
                mysqli_query($MySQLiconn, $sql_query);
                echo ("<SCRIPT LANGUAGE='JavaScript'>
                        window.alert('Succesfully Deleted')
                        window.location.href='cmsManageUser.php';
                        </SCRIPT>");

            }
        ?>
        
        <?php include 'cmsheader.inc';?>
        
        <h1>User Page</h1>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
                <form method="post">
                    <table border="1" class="summary">
                        <thead>
                            <tr>
                                <th class="col-md-1"><h4>Action</h4></th>
                                <!-- <th class="col-md-2"><h4>Cinema ID</h4></th> -->
                                <th class="col-md-3"><h4>User Name</h4></th>
                                <th class="col-md-2"><h4>User Email</h4></th>
                                <th class="col-md-2"><h4>User Phone</h4></th>
                                <th class="col-md-3"><h4>User Role</h4></th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <?php
                                $row2 = mysqli_fetch_row($resultCount);
                                $num = $row2[0];
                            
                                if ($num == 0)
                                {
                                    echo 
                                    "<tr><td colspan='8'><p class='centerText'>No Result to display</p></td></tr>";
                                }
                                else 
                                {
                                    while($row = $records->fetch_array())
                                    {
                            ?>                 
                                        <tr class='success'>
                                            <td class='col-md-1'><a href='javascript:delete_id(<?php echo $row[0]; ?>)'>Delete</a><br /><br /><a href='javascript:update_id(<?php echo $row[0]; ?>)'>Update</a></td>
                                            <td class='col-md-2'><p><?php echo $row[1]; ?></p></td><!-- User Name -->
                                            <td class='col-md-3'><p><?php echo $row[2]; ?></p></td> <!-- User Email -->
                                            <td class='col-md-2'><p><?php echo $row[4]; ?></p></td> <!-- User Phone -->
                                            <td class='col-md-3'><p><?php echo $row[3]; ?></p></td> <!-- User Role -->
                                        </tr>
                                    <?php
                                    }
                                }
                            ?>
                                
                            <tr>
                                <td colspan='10'>
                                    <!-- <button type="submit" name="delete" class="btn btn-primary" <?php if ($num <= 1) echo 'disabled="disabled"' ?>>delete</button> -->
                                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-plus-sign yellow">Add</span></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
            
            <!-- modal contact form -->
            <div id="myModal" class="modal fade" aria-labelledby="myModalLabel" aria-hidden="true" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn btn-primary buttonTopClose" data-dismiss="modal" aria-hidden="true">X</button>
                            <h4 class="modal-title custom_align" id="Heading">Manage User</h4>
                        </div>

                        <div class="modal-body">
                            <?php include("cmsAddUser.php");?>
                        </div> 
                    </div> <!-- /.modal-content --> 
                </div> <!-- /.modal-dialog --> 
            </div>
                
            <ul class="pagination">
            <?php
                for($i=1;$i<=$tpages;$i++) 
                {
                    echo "<li><a href=cmsManageUser.php?page=".$i.">".$i."</a></li>";
                }
            ?>
            </ul>
            
            <div><?php include 'cmsfooter.inc';?></div>
    </div>
       
</body>
</html>