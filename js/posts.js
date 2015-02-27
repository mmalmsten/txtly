function loadPosts(pageNumber, pagename, current, currentget){
    $.ajaxSetup({
        beforeSend: function() {
            console.log("Start");
            $('#loadnow').fadeOut(); 
            $('#loadposts').fadeIn(); 
        },
        complete: function() {
            console.log("Stop");
            $('#loadposts').fadeOut(); 
            $('#loadnow').fadeIn(); 
        }
    });
    $.ajax({
        url: 'feed.php',
        type: 'post',
        data: {
            "pagenumber": pageNumber,
            "pagename": pagename,
            "current": current,
            "currentget": currentget,
        },
        success: function(response) { 
            console.log("response");
            $("#loadnextpage").append(response);
        }
    });
}