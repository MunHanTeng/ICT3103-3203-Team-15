<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>CMS Main</title>
        <link href="../css/bootstrap.min.css" rel="stylesheet" />
        <link href="../css/style.css" rel="stylesheet" />
        <link href="../images/gv32x32.ico" rel="shortcut icon" />

    </head>
    <body>
        <script src="../js/jquery.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/scripts.js"></script>
        <script>
            function goBack() 
            {
                window.history.back();
            }
        </script>
        
        <?php
            session_start();
            //include 'cmsheader.inc';
            include_once '../dbconnect.php';
            echo"</br>";
        
            //Doing of Editing/updating Promo    
            if (isset($_POST['id'])) 
            {
                $sql = "UPDATE promotion SET promotionInfo_title='" . $_POST['pname'] . "', promotionInfo_Description='" . $_POST['pdesc'] . "',"
                        . "promotionInfo_TnC='" . $_POST['pTnC'] . "' WHERE promotion_id='" . $_POST['id'] . "'";
                $result = $MySQLiconn->query($sql);
                echo "Your database has been updated</b>";
                echo '<script language="javascript">';
                echo 'alert("Successfully Updated Promotion"); location.href="cmspromo.php"';
                echo '</script>';
            }
        
            //Dislaying data to be edited. 
            elseif (isset($_GET['id'])) 
            {
                $id = $_GET['id'];
                $sql = "SELECT * FROM promotion where promotion_id = $id";
                $result = $MySQLiconn->query($sql);
                while ($row = mysqli_fetch_array($result)) 
                {
                    $image = $row['2'];
                    //header('Content-type : image/jpeg');
                    echo "
                        <form method='POST' action='cmspprocess.php'>
                            <input type='hidden' name='id' value='" . $row['0'] . "'>

                            <div align = 'center'>
                            <h3>Update Promotion</h3></br>
                            <table border='1'>
                                <tr>
                                    <td>
                                        <h4>Promotion Name : </h4>
                                    </td>
                                    <td>
                                        <input type='text' name='pname' class='form-control' required style='width: 500px;' value='" . $row['1'] . "' class='input' required>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td>
                                        <h4>Image : </h4>
                                    </td>
                                    <td>
                                        <img style='width: 50%' src='data:image/jpeg;base64," . base64_encode($row['2']) . "'>              
                                    </td>
                                </tr>


                                <tr>
                                    <td>
                                        <h4>Promotion <br /> Description : </h4>
                                    </td>
                                    <td>
                                        <textarea style='color: black' class='form-control' required name='pdesc' cols='61' rows='5'>" . $row['3'] . "</textarea>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <h4>Promotion <br /> Terms And Condition : </h4>
                                    </td>
                                    <td>
                                        <textarea style='color: black' class='form-control' required name='pTnC' cols='61' rows='5'>" . $row['4'] . "</textarea>
                                    </td>
                                </tr>

                            </table>
                            <input type='submit' value='Edit' class='btn btn-default'><button onclick='goBack()' class='btn btn-default'>Back</button>
                            </div>
                        </form>
                    ";
                }
            }



            //Doing of Adding Promo
            elseif (isset($_POST['pname'])) 
            {

                $image = file_get_contents($_FILES['ppict']['tmp_name']);
                $image = mysqli_real_escape_string($MySQLiconn, $image);

                echo $_POST['pTnC'];
                $sql = "insert into promotion (promotionInfo_title,promotionInfo_description,promotionInfo_TnC,promotionInfo_image) values('" . $_POST['pname'] . "','" . $_POST['pdesc'] . "','" . $_POST['pTnC'] . "','$image ')";
                $result = $MySQLiconn->query($sql);
                echo"<h4>Your result has been added</h4></br>";
                echo '<script language="javascript">';
                echo 'alert("Successfully Added Promotion"); location.href="cmspromo.php"';
                echo '</script>';
                echo"</br><button onclick='goBack()'>Back</button>";
    
                $ssql = "select promotion_id from promotion where promotionInfo_title = '" . $_POST['pname'] . "' ";
                $sresult = $MySQLiconn->query($ssql);
                $srow = mysqli_fetch_array($sresult);

                $cinema = $_POST['cinema'];
                $countcinema = count($cinema);
                for ($x = 0; $x < $countcinema; $x++) {

                echo "</br>";
                $csql = "insert into promotioncinema (promotion_id,cinema_id) values('" . $srow['0'] . "','" . $cinema[$x] . "')";
                $cresult = $MySQLiconn->query($csql);
            };
        };
    ?>
    </body>
</html>