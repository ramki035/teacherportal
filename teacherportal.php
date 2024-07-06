<?php 
session_start();
if(!isset($_SESSION['userid']) || !isset($_SESSION['username']))
	header("Location: index.php");

include 'includes/css_links.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Navigation Header</title>
    <style>
    	body {
		    margin: 0;
		    font-family: Arial, sans-serif;
		}

		header {
		    background-color: #333;
		    color: #fff;
		}

		.navbar {
		    display: flex;
		    justify-content: space-between;
		    align-items: center;
		    padding: 10px 20px;
		}

		.nav-logo {
		    font-size: 1.5em;
		    color: #fff;
		    text-decoration: none;
		}

		.nav-menu {
		    list-style: none;
		    display: flex;
		    margin: 0;
		    padding: 0;
		}

		.nav-item {
		    margin-left: 20px;
		}

		.nav-link {
		    color: #fff;
		    text-decoration: none;
		    font-size: 1em;
		}

		.nav-link:hover {
		    text-decoration: underline;
		}

    </style>
</head>
<body>
    <header>
        <nav class="navbar">
            <a href="#" class="nav-logo">Hi, <?php echo $_SESSION['username']; ?></a>
            <ul class="nav-menu">
                <li class="nav-item"><a href="#" class="btn btn-sm btn-secondary" onclick="logoutportal()">Logout</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
    	<div class="row mt-3">
    		<div class="col-sm-12 text-right">
	    		<button class="btn btn-sm btn-success pull-right" onclick="openstdmodal()">Create Student</button>
	    	</div>
    	</div>
    	<div class="row mt-3" id="stdlistTable"></div>
    </div>
    <div class="modal slideInDown animated" id="CommonModal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" id='Commonmodal-dialog'>
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header" id="CommonModalHeader"> 
                <button type="button" class="close"data-dismiss="modal">&times;</button>
            </div> <!-- Modal body -->
            <div class="modal-body" id='CommonModalDiv'> </div> <!-- Modal footer -->
            <div class="modal-footer" id="CommonModalFooter"> <button class="btn btn-danger" data-dismiss="modal">Close</button> </div>
        </div>
    </div>
</div>
</body>
</html>
<script type="text/javascript">
	var stddataGlobal = [];
	getstdlist();
	function getstdlist() {
		$.post('backend.php',{
			mode:'getstdlist'
		},function(res){
			data = JSON.parse(res);
			if(data['err']){
				Swal.fire({
                    title: data['err'],
                    icon:'danger',
                    timer: 2000,
                    showCancelButton: true,
                    showConfirmButton: false
                });
                return;
			}
			stddataGlobal = data['data'];
			cont=`<div class='col-sm-12'>
				<table class='table table-bordered'><thead><th>Student</th><th>Subject</th><th>Mark</th><th>Action</th></thead><tbody>
				`;
				for(sid in data['data']){
					cont+=`<tr><td>${data['data'][sid].name}</td><td>${data['data'][sid].subject}</td><td>${data['data'][sid].marks}</td><td><button class='btn btn-sm btn-info' onclick='openstdmodal(${sid})'>Edit</button><button class='btn btn-sm btn-danger ml-3' onclick='delstd("${sid}")'>Delete</button></td></tr>`;
				}
				cont+=`</tbody>
				</div>`
			$("#stdlistTable").html(cont);
		})
	}
	function openstdmodal(sid)
	{
		if(!sid)
			sid="";

		fields = ["name","subject","marks"];
		cont=`<div class='row'><div class='col-sm-12'>`;

		for(i=0;i<fields.length;i++){
			val= "";
			dis = "";
			if(sid && stddataGlobal[sid] && stddataGlobal[sid][fields[i]])
				val = stddataGlobal[sid][fields[i]];

			if(sid && fields[i]!="marks"){
				dis="disabled";
			}
			cont+=`<div class='form-group'>
						<label>${fields[i].toUpperCase()}</label>
						<input type='text' class='form-control' autocomplete="off" id='std_${fields[i]}' value='${val}' ${dis}>
					</div>`;
		}

		cont+=`</div></div>`;
		header = `<label style='font-size:16px;' class='text-center'>${(sid) ? "Update Student" :"Create Student"}</label>`;
        footer = `<button class="btn btn-success" onclick="createstd('${sid}')">${(sid) ? "Update" :"Create"}</button> <button class="btn btn-danger ml-3" data-dismiss="modal">Close</button>`;

		$("#CommonModal").modal('show');
		$("#CommonModalDiv").html(cont);
		$("#CommonModalFooter").html(footer);
		$("#CommonModalHeader").html(header);

		
	}
	function createstd(sid)
	{
		if(!sid)
			sid="";

		stddata={};
		fields = ["name","subject","marks"];
		for(i=0;i<fields.length;i++)
        {
            stddata[fields[i]] = $(`#std_${fields[i]}`).val();
            if(!stddata[fields[i]] || stddata[fields[i]]==" ")
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

		$.post('backend.php',{
			mode:'createstd',
			stddata:JSON.stringify(stddata),
			sid:sid
		},function(res){
			data = JSON.parse(res);
			if(data['err']){
				Swal.fire({
                    title: data['err'],
                    icon:'danger',
                    timer: 2000,
                    showCancelButton: true,
                    showConfirmButton: false
                });
                return;
			}
			$("#CommonModal").modal('hide');
			getstdlist();
		})
	}
	function delstd(sid){
		if(!sid)
			return;
		
		$.post('backend.php',{
			mode:'deletestd',
			sid:sid
		},function(res){
			data = JSON.parse(res);
			if(data['err']){
				Swal.fire({
                    title: data['err'],
                    icon:'danger',
                    timer: 2000,
                    showCancelButton: true,
                    showConfirmButton: false
                });
                return;
			}
			getstdlist();
		})
	}
	function logoutportal() {
		$.post('backend.php',{
			mode:'logout'
		},function(res){
			data = JSON.parse(res);
			if(data['err']){
				Swal.fire({
                    title: data['err'],
                    icon:'danger',
                    timer: 2000,
                    showCancelButton: true,
                    showConfirmButton: false
                });
                return;
			}
			setTimeout(()=>{
				window.location.href='index.php';
			},400);
		})
	}
</script>
<?php include 'includes/css_scripts.php';?>