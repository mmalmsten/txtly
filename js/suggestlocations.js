// Suggest search result or location results
function findSuggestions(str, url) {
    if (str.length == 0) { 
        document.getElementById(url + "result").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById(url + "result").innerHTML = xmlhttp.responseText;
            }
        }
        var lat = document.getElementById("lat").value;
        var lng = document.getElementById("lng").value;
        xmlhttp.open("GET", "functions/" + url + ".php?searchFor=" + str + "&lat=" + lat + "&lng=" + lng, true);
        xmlhttp.send();
    }
}