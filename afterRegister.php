<?php
    require_once 'loader.php';
    Loader::register('lib','RobThree\\Auth');
    use \RobThree\Auth\TwoFactorAuth;
    $tfa = new TwoFactorAuth('ICT3203');
    include_once 'dbconnect.php';
   
    //echo $_SESSION['dummy_id'];
        
?>


<!doctype html>
<html>
    <head>
        <title>Golden Village</title>
        <link href="css/ticketcollection.css" rel="stylesheet">
        <link href="css/bootstrap.min.css" rel="stylesheet">

    </head>
    
    <body>
    <?php  include 'header.inc'; ?>
        <form id="msform" method="POST" action="processUserAcc.php">
        <!-- progressbar -->
        <ul id="progressbar">
            <li>Register User</li>
            <li class="active">Scan QR Code</li>
            <li>Enter OTP</li>
        </ul>
        
        <!-- fieldsets Page 1 -->
        <fieldset>
            <h2 class="fs-title">Scan the QR Code</h2>
            <h3 class="fs-subtitle">This is the first step</h3>
            <?php
            $id = $_SESSION['dummy_id'];
            $user_result = mysqli_query($MySQLiconn, "SELECT dummy_otpSecret FROM dummy_table WHERE dummy_id='$id'");
            $userResult = mysqli_fetch_assoc($user_result); 
                echo 'Please scan the following QR code and click next<br><img src="' . $tfa->getQRCodeImageAsDataUri('Movie Account Authentication', $userResult['dummy_otpSecret']) . '"><br>';
            ?>
            <input type="button" name="next" class="next action-button" value="Next" />
        </fieldset>
       
        <!-- fieldsets Page 2 -->
        <fieldset>
            <h2 class="fs-title">Scan QR Code</h2>
            <h3 class="fs-subtitle">This is the second step</h3>
            <br>Please enter the OTP Code generated from your Google Authenticator and submit to verify
            <br /><br />
            <input type="text" style="width: 20%" class="form-control" id="otpcode" name="otpcode">
            <br />
            <button type="submit" name="submit" class="btn action-button">Submit</button>
            <input type="button" name="previous" class="previous action-button" value="Previous" />
        </fieldset>
        
        <!-- fieldsets Page 3 -->
        <fieldset>    
            <h2 class="fs-title">Enter OTP</h2>
            <h3 class="fs-subtitle">This is the second step</h3>
            <br>Please enter the OTP Code generated from your Google Authenticator and submit to verify
            <br /><br />
            <input type="text" style="width: 100%" class="form-control" id="otpcode" name="otpcode">
            <br />
            <button type="submit" name="submit" class="btn action-button">Submit</button>
            <input type="button" name="previous" class="previous action-button" value="Previous" />
        </fieldset>
    </form>
</body>

<!-- jQuery --> 
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<!-- jQuery easing plugin --> 
<script src="js/jquery.easing.min.js" type="text/javascript"></script> 
   
<script>
    $(function() 
    {
        //jQuery time
        var current_fs, next_fs, previous_fs; //fieldsets
        var left, opacity, scale; //fieldset properties which we will animate
        var animating; //flag to prevent quick multi-click glitches

        $(".next").click(function()
        {
            if(animating) return false;
            animating = true;
	
            current_fs = $(this).parent();
            next_fs = $(this).parent().next().next();
	
            //activate next step on progressbar using the index of next_fs
            $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
	
            //show the next fieldset
            next_fs.show(); 
            //hide the current fieldset with style
            current_fs.animate({opacity: 0}, 
            {
		step: function(now, mx) 
                {
			//as the opacity of current_fs reduces to 0 - stored in "now"
			//1. scale current_fs down to 80%
			scale = 1 - (1 - now) * 0.2;
			//2. bring next_fs from the right(50%)
			left = (now * 50)+"%";
			//3. increase opacity of next_fs to 1 as it moves in
			opacity = 1 - now;
			current_fs.css({'transform': 'scale('+scale+')'});
			next_fs.css({'left': left, 'opacity': opacity});
		}, 
		duration: 800, 
		complete: function()
                {
			current_fs.hide();
			animating = false;
		}, 
		//this comes from the custom easing plugin
		easing: 'easeInOutBack'
            });
        });

        $(".previous").click(function()
        {
            if(animating) return false;
            animating = true;
	
            current_fs = $(this).parent();
            previous_fs = $(this).parent().prev().prev();
	
            //de-activate current step on progressbar
            $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");
	
            //show the previous fieldset
            previous_fs.show(); 
            //hide the current fieldset with style
            current_fs.animate({opacity: 0}, 
            {
		step: function(now, mx) 
                {
			//as the opacity of current_fs reduces to 0 - stored in "now"
			//1. scale previous_fs from 80% to 100%
			scale = 0.8 + (1 - now) * 0.2;
			//2. take current_fs to the right(50%) - from 0%
			left = ((1-now) * 50)+"%";
			//3. increase opacity of previous_fs to 1 as it moves in
			opacity = 1 - now;
			current_fs.css({'left': left});
			previous_fs.css({'transform': 'scale('+scale+')', 'opacity': opacity});
		}, 
		duration: 800, 
		complete: function()
                {
			current_fs.hide();
			animating = false;
		}, 
		//this comes from the custom easing plugin
		easing: 'easeInOutBack'
            });
        });

        $(".submit").click(function()
        {
            $_SESSION['enteredOTP'] = document.getElementById("otpcode");
            location.href = "processUserAcc.php";
        })

    });
</script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-36251023-1']);
  _gaq.push(['_setDomainName', 'jqueryscript.net']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>
</html>
