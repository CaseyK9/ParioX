$("#loginform").submit(function(e) {
    e.preventDefault(); // avoid to execute the actual submit of the form.
    $.ajax({
        type: "POST",
        url: "lib/auth.php",
        data: $(this).serialize(), // serializes the form's elements.
        success: function(data)
        {
            clean = data.trim();
            if (clean == "OK"){
                setTimeout(function() {
                    window.location.href = "/admin";
                }, 500);
                $('#submit_div').notify("Login successful!", "success");
            }else{
                $('#submit_div').notify("Wrong username and/or password.", "error");
            }
        }
    });


});