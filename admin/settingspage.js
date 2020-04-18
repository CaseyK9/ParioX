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





    $('#toggle_maxfoldersize').change(function() {
        $('#toggle_maxfoldersize').attr("disabled", true);
        setInterval(function() {
            $('#toggle_maxfoldersize').removeAttr("disabled");
        }, 5000);
        if(this.checked) {
            var toggle_maxfoldersize_check = 1;

        }else {
            var toggle_maxfoldersize_check = 0;
        }
        $.ajax({
            type: "POST",
            url: "settings.php",
            data: {enable_maxfoldersize: toggle_maxfoldersize_check,
            },
            success: function(data)
            {
                clean = data.trim();
                if (clean == "OK") {
                    $('#toggle_maxfoldersize').notify("Setting saved!", "success")

                }else {
                    $('#toggle_maxfoldersize').notify("Could not save setting, does the database work?", "error")
                }
            }
        });
    });

    $('#toggle_deleteafter').change(function() {
        $('#toggle_deleteafter').attr("disabled", true);
        setInterval(function() {
            $('#toggle_deleteafter').removeAttr("disabled");
        }, 5000);
        if(this.checked) {
            var toggle_deleteafter_check = 1;

        }else {
            var toggle_deleteafter_check = 0;
        }
        $.ajax({
            type: "POST",
            url: "settings.php",
            data: {enable_toggle_deleteafter: toggle_deleteafter_check,
            },
            success: function(data)
            {
                clean = data.trim();
                if (clean == "OK") {
                    $('#toggle_deleteafter').notify("Setting saved!", "success")

                }else {
                    $('#toggle_deleteafter').notify("Could not save setting, does the database work?", "error")
                }
            }
        });
    });

    function save_maxfoldersize(){
        var valuetosave  = $("#maxfoldersize_amount").val();
        $.ajax({
            type: "POST",
            url: "settings.php",
            data: {maxfoldersize_amountinmb: valuetosave,
            },
            success: function(data)
            {
                clean = data.trim();
                if (clean == "OK") {
                    $('#maxfoldersize_amount').notify("Setting saved!", "success")
                    $('#maxfoldersize_amount').attr("disabled", true);
                    setTimeout(function() {
                        $('#maxfoldersize_amount').removeAttr("disabled");
                    }, 5000);

                }else {
                    $('#maxfoldersize_amount').notify("Could not save setting, does the database work?", "error")
                }
            }
        });
    }

    function check_savemaxfoldersize(){

        var orig_value  = $("#maxfoldersize_amount").val();

        setTimeout(function() {
            var new_value = $("#maxfoldersize_amount").val();

            if (orig_value == new_value){
                save_maxfoldersize()
            } else {
                check_savemaxfoldersize()
            }
        }, 800);
    }

    $('#maxfoldersize_amount').change(function() {
        check_savemaxfoldersize();
    });



    function save_deleteafterxdays_amount(){
        var valuetosave  = $("#maxfoldersize_amount").val();
        $.ajax({
            type: "POST",
            url: "settings.php",
            data: {maxfoldersize_amountinmb: valuetosave,
            },
            success: function(data)
            {
                clean = data.trim();
                if (clean == "OK") {
                    $('#deleteafterxdays_amount').notify("Setting saved!", "success")
                    $('#deleteafterxdays_amount').attr("disabled", true);
                    setTimeout(function() {
                        $('#deleteafterxdays_amount').removeAttr("disabled");
                    }, 5000);




                }else {
                    $('#deleteafterxdays_amount').notify("Could not save setting, does the database work?", "error")
                }
            }
        });
    }

    function check_deleteafterxdays_amount(){

        var orig_value  = $("#deleteafterxdays_amount").val();

        setTimeout(function() {
            var new_value = $("#deleteafterxdays_amount").val();

            if (orig_value == new_value){
                save_deleteafterxdays_amount()
            } else {
                check_deleteafterxdays_amount()
            }
        }, 800);
    }

    $('#deleteafterxdays_amount').change(function() {
        check_deleteafterxdays_amount();
    });







});



