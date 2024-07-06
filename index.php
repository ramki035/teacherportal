<?php 
if(isset($_SESSION['userid']) && isset($_SESSION['username']))
{
    header('Location: teacherportal.php');

    exit();
}

include 'includes/css_links.php';?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
  </head>
<style type="text/css">
  body{
    background-color: grey;
    background: url(includes/img/bg.jpg);
  }
  .form-group{
    text-align: left;
  }
</style>
<body>
    <main class="d-flex align-items-center min-vh-100 py-3 py-md-0">
    <div class="container">
      <center>
        <div class="col-md-3"> 
        </div>
        <div class="col-md-6"> 
            <div class="card login-card" style="margin-top:10%;background-color: white;">
                <div class="row no-gutters">
                        <div class="col-md-12">
                            <div class="card-body">
                                <div class="brand-wrapper" style="margin-bottom: 0px;">
                                    <p style="color: black;font-family: fantasy;text-align:center;font-size: 24px;margin-bottom: 0px;">Login</p>
                                    <img src="includes/img/login_logo_1.png" style="width: 35%">
                                </div>
                                <!-- <p class="text-center login-card-description" style="font-size:14px;color:black;">Login into your account</p>               -->
                                <div class="form-group">
                                    <label for="username" class="inputlabel">Username</label>
                                    <input type="text"name="username" id="username" class="form-control"  autocomplete="off" placeholder="Username" oninput="input_validation(this.id)">
                                </div>
                                <div id="username_error" class="error-div"></div>
                                <div class="form-group mb-0">
                                    <label for="password" class="inputlabel">Password</label>
                                    <input type="password" name="password" id="password" class="form-control"  autocomplete="off" placeholder="***********" onkeypress="return password_validation(this.id)" onclick="input_validation(this.id)" min=4 max=8>
                                    <div id="password_error" class="error-div"></div>
                                    
                                </div>
                                <span class="showhidearea text-right">
                                    <div id="showpass">
                                            <button  style="font-size: 14px;text-align: right;color: green;border: none;background: none;" onclick="showpasswordtext()"><i class="fa fa-eye"></i>&nbsp;&nbsp;Show password</button>    
                                    </div>
                                    <div id="hidepass">
                                        <button style="font-size: 14px;text-align: right;color: red;border: none;background: none;" onclick="hidepasswordtext()"><i class="fa fa-eye-slash"></i>&nbsp;&nbsp;Hide password</button>    
                                    </div>                 
                                </span>
                                <div class="mt-3">
                                    <button class="btn btn-success mb-4" onclick="login()">Login</button>
                                </div>
                                <div>
                                    Don't have an account? <a href="#" onclick="showsignupcard(true);">Create  here</a>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card signup-card" style="margin-top:10%;background-color: white;display: none;">
                <div class="row no-gutters">
                        <div class="col-md-12">
                            <div class="card-body">
                                <div class="brand-wrapper" style="margin-bottom: 0px;">
                                    <!-- <p style="color: black;font-family: fantasy;text-align:center;font-size: 24px;margin-bottom: 0px;">Signup</p> -->
                                    <img src="includes/img/login_logo_1.png" style="width: 35%">
                                </div>
                                <p class="text-center signup-card-description" style="font-size:14px;color:black;">Create account</p>              
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" name="teacher_name" id="teacher_name" class="form-control"  autocomplete="off"> 
                                </div>   
                                 <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" name="teacher_username" id="teacher_username" class="form-control"  autocomplete="off"> 
                                </div>                 
                                 <div class="form-group">
                                    <label>Password</label>
                                    <input type="text" name="teacher_pass" id="teacher_pass" class="form-control"  autocomplete="off"> 
                                </div>   
                                <div class="mb-4"><button class="btn btn-info"onclick="createuser()">Signup</button></div>
                                <div>Already have an account? <a href="#" onclick="showsignupcard(false);"> Login here</a></div>
                                
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-6"></div>
        </center>
    </div>
    </main>
</body>
</html>
<script type="text/javascript">
  $("#hidepass").hide(); // hide hide password div

  //check if element is empty or not
    function input_validation(id)
    {
            val = $("#"+id).val();
            if(parseInt(val.length) > 0)
            {             
                error_div_empty(id); 
            }else if(parseInt(val.length) < 1){
                error_div_show(id);
            }
        }
    //check if password length is not exceed than 8
    function password_validation(id)
    {
            val = $("#"+id).val();
            if(parseInt(val.length) > 0){
                error_div_empty(id);
            }
            if(parseInt(val.length) > 7)
            {                 
                return false;
            }
    }
    function showpasswordtext()
    {
        var temp = document.getElementById("password"); 
        if (temp.type === "password") { 
            temp.type = "text"; 
        } 
        else { 
            temp.type = "password"; 
        } 
        $("#showpass").hide();
        $("#hidepass").show();
    }
     function hidepasswordtext()
    {
         var temp = document.getElementById("password"); 
        if (temp.type === "text") { 
            temp.type = "password"; 
        } 
        else { 
            temp.type = "text"; 
        } 
        $("#showpass").show();
        $("#hidepass").hide();
    }
       function login()
    {
        var username = $("#username").val();
        var password = $("#password").val();
        if((username =="" || username ==" " )&& (password  ==" " || password ==""))
        {
            error_div_show("username");
            error_div_show("password");              
        }else if(username =="" || username ==" ")
        {
            error_div_show("username")
            

        }else if(password =="" || password ==" "){
            error_div_show("password")
        }else if(username!="" && password!="" && username!=" " && password!=" ")
        {
            $.post('backend.php',{
            mode: "verifyuser",
            username: username,
            password: password
            },function (response)
            {
                data = JSON.parse(response);
                if(data['err'])
                {
                    Swal.fire({
                      title: data['err'],
                      icon : 'warning',                          
                      showCancelButton: false,
                      showConfirmButton: true
                    })
                    return;
                }

                Swal.fire({
                    title: 'verified',
                    icon:'success',
                    timer: 2000,
                    showCancelButton: false,
                    showConfirmButton: false
                }); 
                setTimeout(()=>{
                    window.location.href = "teacherportal.php";
                },400);
            });
        }       
    }
    //Show error div if input filed value is empty
    function error_div_show(id)
    {
        $('#'+id+'_error').empty();
        $("#"+id).css("border-color", "red");
        // $('#'+id+'_error').append('<p class="error-message"style="margin-top:-1px;font-size: 14px;color: red;">'+id+' is required *</p>');
    }
    //Hide error div if input filed get value
    function error_div_empty(id)
    {
        $('#'+id+'_error').empty();
        $("#"+id).css("border-color", " ");
    }
    function showsignupcard(signup){
        if(signup){
            $(".login-card").hide();
            $(".signup-card").show();
        }else{
            $(".signup-card").hide();
            $(".login-card").show();
        }
    }
    function createuser() {
        senddata={};
        fields = ["teacher_name","teacher_pass","teacher_username"];
        for(i=0;i<fields.length;i++)
        {
            senddata[fields[i]] = $(`#${fields[i]}`).val();
            if(!senddata[fields[i]] || senddata[fields[i]]==" ")
            {
                Swal.fire({
                    title: 'Please fill all the values',
                    icon:'danger',
                    timer: 2000,
                    showCancelButton: false,
                    showConfirmButton: true
                });
                return;
            }

        }
        console.log(senddata);
        $.post("backend.php",
        {
            mode:"createuser",
            data:JSON.stringify(senddata)
        },function (res) {
            data = JSON.parse(res);
            if(data['err']){
                Swal.fire({
                    title: data['err'],
                    icon:'danger',
                    timer: 2000,
                    showCancelButton: false,
                    showConfirmButton: true
                });
                return;
            }

            Swal.fire({
                title: 'Created successfully',
                icon:'success',
                timer: 2000,
                showCancelButton: false,
                showConfirmButton: false
            }); 
            setTimeout(()=>{
                location.reload();
            },400);

        })
    }
</script>

<?php include 'includes/css_scripts.php';?>