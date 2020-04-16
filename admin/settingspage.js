$(document).ready(function() {

    $('#directlinking_0').change(function() {
        $('#directlinking_0').attr("disabled", true);
        setInterval(function() {
            $('#directlinking_0').removeAttr("disabled");
        }, 5000);
        if(this.checked) {
            var check = 1;





        }else {
            var check = 0;
        }
        $.ajax({
            type: "POST",
            url: "settings.php",
            data: {directlinking: check},
            success: function(data)
            {
                clean = data.trim();
                if (clean == "OK") {
                    $('#directlinking_0').notify("Setting saved!", "success")



                }else {
                    $('#directlinking_0').notify("Could not save setting, does the database work?", "error")
                }
            }
        });

    });
});