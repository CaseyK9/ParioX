function download_sxcu(key){
 var keyy = key;
    document.getElementById('download_iframe').src = "users.php?key=" + keyy;



}
function reset_authkey(id) {
    $.ajax({
        type: "POST",
        url: "users.php",
        data: {resetauthkey: id},
        success: function (data) {
            clean = data.trim();
            if (clean == "ok") {
                $.notify("Authkey reset! Make sure you install the new .sxcu file.", "success");
            } else {
                $.notify("unable to reset the auth key.", "error");
            }
        }
    });
}