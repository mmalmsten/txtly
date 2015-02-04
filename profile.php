<?php

  require 'functions/header.php';

  if (!isset($_SESSION['user'])) {
      $_SESSION['error'] = 'What are you doing!? Stop that.';
      header('Location: form.php');
      die;
  }

  require 'functions/link.php';
  $link = mysqli_connect($tablehost, $tableuser, $tablepass, $tabletable);
  mysqli_connect_errno();

  $postResult = mysqli_query($link, "SELECT user FROM users WHERE user = '".CURRENT."'");
  $row = mysqli_fetch_assoc($postResult);
  
  if (!$row) {
    print "
    <div class='feed col-md-6 col-sm-9'><h1>
    How terrible! <br>
    <small>It seems like there's no existing user called <strong>@".CURRENT."</strong>.
    The user might have been removed, but maybe the search field can lead you in the right direction?</small>
    </h1></div>
  <div class='col-md-3 visible-md visible-lg'>
    ";
  }

  else{
?>

  <div class="col-sm-9">
    <div id="loadnextpage"></div>
    <button class="btn btn-xs btn-primary" id="loadnow">Load more posts</button>
    <div id="loadposts">Loading posts...</div>
  </div>

<?php } require 'functions/footer.php'; ?>

<script>
    $(document).ready(function(){
      //alert("GO!");
      $.ajaxSetup({
          beforeSend: function() {
            $('#loadposts').fadeIn(); 
        },
          complete: function() {
            $('#loadposts').fadeOut(); 
          }
      });
      $.ajax({
          url: 'functions/follow.php',
          type: 'post',
          data: {
          "pagename": "<?php print PAGENAME ?>",
          "current": "<?php print CURRENT ?>",
          "currentget": "<?php print CURRENTGET ?>",
          },
          success: function(response) { 
            //alert(response);
            //console.log(response);
          $("#followerbox").html(response);
        }
      });
      
      $("#followBtn").click(function() {
        followBtn = $('input#followBtn').val();
        //alert("GO!");
        $.ajaxSetup({
            beforeSend: function() {
              if (followBtn == "unfollow") {
                $('input#followBtn').val("follow");
              } else{
                $('input#followBtn').val("unfollow");
              }
          },
            complete: function() {
            }
        });
        $.ajax({
            url: 'functions/follow.php',
            type: 'post',
            data: {
            "followuser": followBtn,
            "pagename": "<?php print PAGENAME ?>",
            "current": "<?php print CURRENT ?>",
            "currentget": "<?php print CURRENTGET ?>",
            },
            success: function(response) { 
              //alert(response);
              //console.log(response);
            $("#followerbox").html(response);
          }
        });
      });
    });
    $(document).ready(function(){
      $("#updateuserbtn").click(function() {
        alert("GO!");
        $.ajaxSetup({
            beforeSend: function() {
              //$('#loadposts').fadeIn(); 
          },
            complete: function() {
              //$('#loadposts').fadeOut(); 
            }
        });
        $.ajax({
            url: 'functions/updateuser.php',
            type: 'post',
            data: {
            "name": $('input#name').val(),
            "email": $('input#email').val(),
            "user": $('input#user').val(),
            "myuser": "<?php print MYUSER ?>",
            "description": $('textarea#description').val(),
            "pwd": $('input#pwd').val(),
            "pwd1": $('input#pwd1').val()
            },
            success: function(response) { 
              alert(response);
              console.log(response);
            $("#updateuser").html(response);
          }
        });
      });
    });
</script>