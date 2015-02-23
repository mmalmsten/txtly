function loadPosts(pageNumber, pagename, current, currentget){
    $.ajaxSetup({
        beforeSend: function() {
            $('#loadnow').fadeOut(); 
            $('#loadposts').fadeIn(); 
        },
        complete: function() {
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
            $("#loadnextpage").append(response);
        }
    });
}