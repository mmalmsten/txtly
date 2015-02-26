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
  
  if (!$row) { ?>
      
    <div class='feed col-md-6 col-sm-9'>
      <div class="foundnothing">
        <h1>
          How terrible! <br>
          <small>It seems like there's no existing user called <strong>@<?php print CURRENT ?></strong>.
          The user might have been removed, but maybe the search field can lead you in the right direction?</small>
        </h1>
        <p><form class="form-inline" action="search.php" method="get">
          <button type="submit" name="search" class="btn btn-primary" value="<?php print $searchterm ?>">Sure, let's search</button>
        </form>
      </div>
    </div>

  <?php } else { ?>

  <div class="col-sm-9">
    <div id="loadnextpage"></div>
    <button class="btn btn-lg btn-primary" id="loadnow">Load more posts</button>
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
      //alert("GO!");
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
          "user": "<?php print CURRENT ?>",
          "action": "none",
          },
          success: function(response) { 
            //alert(response);
            //console.log(response);
          $("#updateuser").html(response);
        }
      });
      $("#updateuserbtn").click(function() {
        //alert("GO!");
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
            "user": "<?php print CURRENT ?>",
            "action": "update",
            },
            success: function(response) { 
              //alert(response);
              //console.log(response);
            $("#updateuser").html(response);
          }
        });
      });
    });
</script>