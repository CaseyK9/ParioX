<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$step1done = false;
$step2done = false;
$account = false;
$configfile = '../inc/config.php';


// Create config file if it does not exist yet.
if(!is_file($configfile)) {
// Config doesn't exist yet. New installation!
    file_put_contents($configfile, "");
}

require_once('../inc/config.php');




$configcontent = file_get_contents($configfile);

if(strpos($configcontent, '$config_db_ip') !== false){
    include('../lib/global.php');
$step1done = true;

}



if($step1done) {
    if ($stmt = $conn->prepare('SELECT site_name FROM settings')) {
        $stmt->execute();
        $stmt->store_result();
    }

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($site_name);
        $stmt->fetch();
        if (!empty($site_name)) {
            $step2done = true;
        }

    }
    $stmt->close();
}
if($step2done) {
    if ($stmts2 = $conn->prepare('SELECT id FROM accounts')) {
        $stmts2->execute();
        $stmts2->store_result();
    }

    if ($stmts2->num_rows > 0) {
            $account = true;
    }
}

?>
<html>
<head>

</head>
<link href="../inc/css/bootstrap.css" rel="stylesheet">
<link href="../inc/css/animate.css" rel="stylesheet">
<style>
    body {
        margin-top:40px;
    }
    .stepwizard-step p {
        margin-top: 10px;
    }
    .stepwizard-row {
        display: table-row;
    }
    .stepwizard {
        display: table;
        width: 50%;
        position: relative;
    }
    .stepwizard-step button[disabled] {
        opacity: 1 !important;
        filter: alpha(opacity=100) !important;
    }
    .stepwizard-row:before {
        top: 14px;
        bottom: 0;
        position: absolute;
        content: " ";
        width: 100%;
        height: 1px;
        background-color: #ccc;
        z-order: 0;
    }
    .stepwizard-step {
        display: table-cell;
        text-align: center;
        position: relative;
    }
    .btn-circle {
        width: 30px;
        height: 30px;
        text-align: center;
        padding: 6px 0;
        font-size: 12px;
        line-height: 1.428571429;
        border-radius: 15px;
    }

    notifyjs-happyblue-base {
        white-space: nowrap;
        background-color: lightblue;
        padding: 5px;
    }
    .notifyjs-happyblue-superblue {
        color: white;
        background-color: blue;
    }
    .loader {
        border: 8px solid #f3f3f3; /* Light grey */
        border-top: 8px solid #3498db; /* Blue */
        border-radius: 50%;
        width: 30px;
        height: 30px;
        animation: spin 2s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
<body>

<div class="container">

    <div class="stepwizard col-md-offset-3">
        <div class="stepwizard-row setup-panel">
            <div class="stepwizard-step">
                <a href="#step-1" id="stap1click" type="button" class="btn btn-primary btn-circle">1</a>
                <p>Step 1</p>
            </div>
            <div class="stepwizard-step">
                <a href="#step-2" type="button" id="stap2click" class="btn btn-default btn-circle" disabled="disabled">2</a>
                <p>Step 2</p>
            </div>
            <div class="stepwizard-step">
                <a href="#step-3" type="button" id="stap3click" class="btn btn-default btn-circle" disabled="disabled">3</a>
                <p>Step 3</p>
            </div>
        </div>
    </div>


        <div class="row setup-content" id="step-1">
            <div class="col-xs-6 col-md-offset-3">
                <div class="col-md-12">
                    <form id="step1form" method="post" name="step1form">
                    <h3> Step 1</h3>
                    <h4>Database configuration</h4>
                    <div class="form-group">
                        <label class="control-label">Database host</label>
                        <input  name="db_host" maxlength="100" type="text" class="form-control" placeholder="localhost" required="required"/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Database port</label>
                        <input type="number" name="db_port" maxlength="100" type="text" class="form-control" value="3306" required="required"/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Username</label>
                        <input name="db_user" maxlength="100" type="text" class="form-control" placeholder="root" required="required"/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">password</label>
                        <input name="db_pass" type="text" class="form-control" placeholder="" />
                    </div>
                    <div class="form-group">
                        <label class="control-label">Database name</label>
                        <input name="db_name" type="text" class="form-control" placeholder="" value="Pariox" />
                        <small id="helptext1" class="form-text text-muted">ParioX will automatically create a database and insert tables. <br> You can leave this on the default name, unless you have another instance of ParioX running OR if your host doesn't allow you to create a database via SQL!!! In that case, create one manually(in your Control Panel) and enter it's name in the above field. Case Sensitive!.<br> Or change it anyway. I'm a text label, not a cop. </small>
                    </div>
			<div class="form-group">
                        <label class="control-label">Full domain name</label>
                        <input name="domain" type="text" class="form-control" placeholder="" />
			 <small id="helptext1" class="form-text text-muted">The full domain of your pariox instance. If you installed it on a subdomain, include that too! (eg. images.yoursite.com) NO TRAILING SLASH!</small>

                    </div>

                    <div>
                        <button type="submit" id="checkknop" class="btn btn-primary">Check connection</button> <div style="display: inline-block;" id="loadwheel" class="loader"></div></div>
                    </form>
                </div>
            </div>
            <button type="submit" id="createknop" class="btn btn-primary">Save settings & Create database</button><div style="display: inline-block;" id="loadwheel2" class="loader"></div></div><br>
        <button class="btn btn-primary btn-lg pull-right" id="naarstap2" type="button" >Next</button>



        <div class="row setup-content" id="step-2">
            <div class="col-xs-6 col-md-offset-3">
                <div class="col-md-12">
                    <h3> Step 2</h3>
                    <form id="step2form" method="post" name="step2form">
                    <div class="form-group">
                        <small id="helptext1" class="form-text text-muted">These settings are just to setup the default template. Custom templates aren't supported yet, so you'll have to do with this one for now <br> this text can be changed later.</small>
                        <label class="control-label">Site name</label>
                        <input maxlength="48" type="text" id="site_name" name="site_name" required="required" class="form-control" placeholder="Ferrets R Cool" />
                        <small id="helptext1" class="form-text text-muted">This is the name that will show up under your copyright notice, header and tab title.</small>

                    </div>
                    <div class="form-group">
                        <label class="control-label">Header title</label>
                        <input maxlength="100" type="text" id="site_header" name="site_header" required="required" class="form-control" placeholder="My really cool picture site"  />
                        <small id="helptext1" class="form-text text-muted">This will be the text header on the main index page.</small>

                    </div>
                        <div class="form-group">
                            <label class="control-label">Header subtext</label>
                            <input type="text" required="required" id="site_subtext" name="site_subtext" class="form-control" placeholder="lorum ipsum bla bla bla"  />
                            <small id="helptext1" class="form-text text-muted">This will be the text under the header on your main site.</small>

                        </div>
                    </form>
                </div>
            </div>

        </div>
    <button class="btn btn-primary btn-lg pull-right" id="stap2save" type="button">Save settings</button><div style="display: inline-block;" id="loadwheel3" class="loader"></div>
<button class="btn btn-primary btn-lg pull-right" id="naarstap3" type="button">Next</button>


<div class="row setup-content" id="step-3">
    <div class="col-xs-6 col-md-offset-3">
        <div class="col-md-12">
            <h3> Step 2</h3>
            <form id="step3form" method="post" name="step2form">
                <div class="form-group">
                    <small id="helptext1" class="form-text text-muted">These settings are just to setup the default template. Custom templates aren't supported yet, so you'll have to do with this one for now <br> this text can be changed later.</small>
                    <label class="control-label">username</label>
                    <input maxlength="48" type="text" id="username" name="username" required="required" class="form-control"  />
                    <small id="helptext1" class="form-text text-muted">This will be your username.</small>

                </div>
                <div class="form-group">
                    <label class="control-label">Password</label>
                    <input maxlength="100" type="text" id="password" name="password" required="required" class="form-control"  />
                    <small id="helptext1" class="form-text text-muted">This will be your password</small>

                </div>
                <button class="btn btn-primary btn-lg pull-right" id="finish" type="submit">Finish</button>
            </form>
        </div>
    </div>

</div>



</div>
<div class="modal" id="donemodal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Installation Complete!</h5>


            </div>
            <div class="modal-body">
                <p>Please delete the installation folder ( yoursite.com<b>/install</b> ) manually! this will be fixed in the future.</p>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
</body>
<script src="../inc/js/jquery.js"></script>
<script src="../inc/js/bootstrap.js"></script>
<script src="../inc/js/notify.js"></script>
<script>
    $('#naarstap3').hide();
    $(document).ready(function () {
        $('#loadwheel').hide();
        $('#loadwheel2').hide();
        $('#loadwheel3').hide();
        $('#createknop').hide();



        var navListItems = $('div.setup-panel div a'),
            allWells = $('.setup-content'),
            allNextBtn = $('.nextBtn');

        allWells.hide();

        navListItems.click(function (e) {
            e.preventDefault();
            var $target = $($(this).attr('href')),
                $item = $(this);

            if (!$item.hasClass('disabled')) {
                navListItems.removeClass('btn-primary').addClass('btn-default');
                $item.addClass('btn-primary');
                allWells.hide();
                $target.show();
                $target.find('input:eq(0)').focus();
            }
        });

        allNextBtn.click(function () {



            $(".form-group").removeClass("has-error");
            for (var i = 0; i < curInputs.length; i++) {
                if (!curInputs[i].validity.valid) {
                    isValid = false;
                    $(curInputs[i]).closest(".form-group").addClass("has-error");
                }
            }

            if (isValid)
                nextStepWizard.removeAttr('disabled').trigger('click');
        });

        $('div.setup-panel div a.btn-primary').trigger('click');
    });
</script>

<script>
    $(document).ready(function () {
      $('#nextknop1').hide();
      $('#naarstap2').hide();
      $('#stap2save').hide();
    });





    $("#step1form").submit(function(e) {
    $('#loadwheel').fadeIn(200);
        e.preventDefault(); // avoid to execute the actual submit of the form.


        $.ajax({
            type: "POST",
            url: "inc/dbCheck.php",
            data: $(this).serialize(), // serializes the form's elements.
            success: function(data)
            {
                $('#loadwheel').fadeOut(200);
                // show response from the php script.
                schoon = data.trim();
                if (schoon === "Ok"){
                    $('#checkknop').notify("All good!", { position:"right", autohide: true, className: 'success' });
                    $('#checkknop').attr('class','btn btn-success');

                    $('#createknop').fadeIn(200);
                  //  $('#checkknop').fadeOut(1000);
                    //$('#createknop').fadeIn(1000);
                }else {
                    $('#checkknop').attr('class','btn btn-danger');
                    $.notify.addStyle('happyblue', {
                        html: "<div><span data-notify-text/>" + schoon + "</div>",
                        classes: {
                            base: {
                                "white-space": "nowrap",
                                "background-color": "lightblue",
                                "padding": "5px"
                            },
                            superblue: {
                                "color": "#B94A48",
                                "background-color": "#F2DEDE",
                                "border-color": "#EED3D7",
                            }
                        }
                    });



                    $('#checkknop').notify('Something went wrong, sorry.', {
                        style: 'happyblue',
                        className: 'superblue',
                        autoHideDelay: 20000,
                        showAnimation: "fadeIn",
                        hideAnimation: "fadeOut",
                        hideDuration: 700,
                        arrowShow: false,
                        position: "right"
                    });
                    $('body,html').animate({ scrollTop: $('body').height() }, 800);
                }
            }
        });


    });

    $("#createknop").click( function() {
    $('#loadwheel2').fadeIn(200);
        $.ajax({
            type: "POST",
            url: "inc/config_init.php",
            data: $('#step1form').serialize(), // serializes the form's elements.
            success: function(data)
            {
                clean = data.trim();
                console.log(clean);
                if (clean === "ok"){
                    $('#loadwheel2').fadeOut(200);
                    $("#createknop").prop("disabled", true);
                    $("#createknop").attr('class','btn btn-success');
                    $("#checkknop").prop("disabled", true);
                    $("#checkknop").attr('class','btn btn-success');
                    $('#naarstap3').hide();
                    $('#naarstap2').show();

                }else{
                    $('#createknop').notify('Something went wrong, sorry.', {
                        style: 'happyblue',
                        className: 'superblue',
                        autoHideDelay: 20000,
                        showAnimation: "fadeIn",
                        hideAnimation: "fadeOut",
                        hideDuration: 700,
                        arrowShow: false,
                        position: "right"
                    });
                }
            }
        });

    });

    $('#naarstap2').click(function(){
        $('#naarstap2').hide();
        $('#stap2click').click();
        $('#naarstap3').hide();
        $('#stap2save').show();
    });

    $('#stap2click').click(function(){
        $('#naarstap2').hide();
        $('#naarstap3').hide();

    });

    $('#stap1click').click(function(){
        $('#naarstap2').show();
    });


    $('#naarstap3').click(function(){
        $('#stap3click').click();
        $('#naarstap2').hide();
        $('#naarstap3').hide();
    });

    $('#stap3click').click(function(){
        $('#naarstap3').hide();
        $('#naarstap2').hide();
    });
    $('#stap2save').click(function() {
        $.ajax({
            type: "POST",
            url: "inc/config_site.php",
            data: $('#step2form').serialize(), // serializes the form's elements.
            success: function(data)
            {

                $('#naarstap3').fadeIn(200);
                $('#stap2save').attr('class','btn btn-success').prop("disabled", true);
            }
        });
    });



    $("#step3form").submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "inc/save_cred.php",
            data: $('#step3form').serialize(), // serializes the form's elements.
            success: function(data)
            {
                clean = data.trim();

                if (clean == "ok") {

                    $('#donemodal').modal('show');
                    $('#finish').attr('class', 'btn btn-success').prop("disabled", true);
                }else {
                    $.notify("Something's gone really bad and you should contact the author", "error");
                }
            }
        });

    });


</script>

<script>
        $('#loadwheel3').fadeIn(200);




</script>


<?php



if ($step1done == true){
    ?>
    <script>
        $(document).ready(function () {
            $("#createknop").prop("disabled", true);
            $("#createknop").attr('class', 'btn btn-success');
            $("#checkknop").prop("disabled", true);
            $("#checkknop").notify("You already configured the database", "warn");
            $.notify("A database has already been configured!", "warn");
            $.notify("A database has already been configured!", "warn");
            $.notify("A database has already been configured!", "warn");
            $.notify("A database has already been configured!", "error");
            $.notify("Step 1 has been skipped since a database has already been created", "info");
            $("#checkknop").attr('class', 'btn btn-success');
            $('#nextbutton').show();
            $('#stap2click').click();
            $('#stap2save').show();
        })
    </script>
<?php
}


if ($step2done == true){
?>
<script>
    $(document).ready(function () {
        $("#createknop").prop("disabled", true);
        $("#createknop").attr('class', 'btn btn-success');
        $("#checkknop").prop("disabled", true);
        $("#checkknop").notify("You already configured the site settings!", "warn");

        $.notify("Step 2 has been skipped since these settings have already been set", "info");
        $("#checkknop").attr('class', 'btn btn-success');
        $('#nextbutton').show();
        $('#naarstap3').show();
        $('#stap3click').click();
        $('#stap2save').attr('class','btn btn-success').prop("disabled", true).hide();



    })
</script>
<?php
}
if($account){
    ?>
    <script>
        $(document).ready(function () {
            $.notify("All steps have been completed, stop trying to break things and delete the installation :D", "success");
            $('#donemodal').modal('show');
        });

    </script>

<?php
}


?>
</html>