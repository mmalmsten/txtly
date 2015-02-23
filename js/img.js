// Show "upload file" when clicking on profile picture
$(document).ready(function(){
  $(".showprofileimg").click(function(){
    $(".uploadprofileimg").slideDown("slow");
  });
  var width = $('.profileimg').width();
  $('.profileimg').css('height', width);
});
// Show "upload file" when clicking on profile header
$(document).ready(function(){
  $(".showheaderimg").click(function(){
    $(".uploadheaderimg").slideDown("slow");
  });
});