<?php 
if(isset($_GET['id'])){
$edit_requested_id=$_GET['id'];
}else{
    header("Location: view-popular-vehicle-local");
}

if(isset($_POST['cus_update_user'])){
    try{
		$title = htmlentities(trim($_POST['title']));
		$description = htmlspecialchars(trim($_POST['description']));
		$every40hours = htmlspecialchars(trim($_POST['every40hours']));
		$every80hours = htmlspecialchars(trim($_POST['every80hours']));
		$extra_hours = htmlspecialchars(trim($_POST['extra_hours']));
		$extra_kms = htmlspecialchars(trim($_POST['extra_kms']));
		
		if(empty($title) || empty($description) || empty($every40hours) || empty($every80hours)){
		    $_SESSION['add_user_error_msg'] = "<div class='alert alert-danger'><button class='close' data-dismiss='alert'>&times;</button>Please Fill all the Fileds!</div>";
		}else{
		    
		    if($_FILES["result_file"]["size"]>10){
		    $target_dir = "../assets/files/local_taxi/";
            $target_file = $target_dir . basename($_FILES["result_file"]["name"]);
            $uploadOk = 1;
            $FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                if($FileType == 'jpg' || $FileType == 'jpeg' || $FileType == 'png' || $FileType == 'gif'){
                    $file_name = time().".".$FileType;
                    $target_file_new = $target_dir . $file_name;
                    if (move_uploaded_file($_FILES["result_file"]["tmp_name"], $target_file_new)){
                        
                    $stmt = $reg_user->runQuery("UPDATE local_taxi SET title='$title',description='$description',every40hours='$every40hours',every80hours='$every80hours',extra_hours='$extra_hours',extra_kms='$extra_kms',image='$file_name' WHERE id='$edit_requested_id'");
                    $stmt->execute();
            		$_SESSION['add_user_error_msg'] = "<div class='alert alert-success'><button class='close' data-dismiss='alert'>&times;</button><strong>Popilar Vehicle!</strong> Updated Successfully.</div>";
            		header("Location: edit-popular-vehicle-local?id=".$edit_requested_id);
            		exit();
                    } else {
                        $_SESSION['add_user_error_msg'] = "<div class='alert alert-danger'><button class='close' data-dismiss='alert'>&times;</button> Sorry, there was an error uploading your file.</div>";
                    }
                    
                }else {
                    $_SESSION['add_user_error_msg'] = "<div class='alert alert-danger'><button class='close' data-dismiss='alert'>&times;</button> Selected file is not a PDF file.</div>";
                }
		    }else{
		    $stmt = $reg_user->runQuery("UPDATE local_taxi SET title='$title',description='$description',every40hours='$every40hours',every80hours='$every80hours',extra_hours='$extra_hours',extra_kms='$extra_kms' WHERE id='$edit_requested_id'");
            $stmt->execute();
    		$_SESSION['add_user_error_msg'] = "<div class='alert alert-success'><button class='close' data-dismiss='alert'>&times;</button><strong>Popilar Vehicle!</strong> Updated Successfully.</div>";
    		header("Location: edit-popular-vehicle-local?id=".$edit_requested_id);
    		exit();
		    }
		}
		
		}catch(PDOException $ex){
			$_SESSION['add_user_error_msg'] = "<div class='alert alert-danger'><button class='close' data-dismiss='alert'>&times;</button>".$ex->getMessage()."</div>";
		}
}

include 'include/header.php';
$stmt_user = $reg_user->runQuery("SELECT * FROM local_taxi WHERE id='$edit_requested_id'");
$stmt_user->execute();
$row_user = $stmt_user->fetch(PDO::FETCH_ASSOC);
?>
<div class="site-content">
				<div class="content-area py-1">
					<div class="container-fluid">
						<h4>Update Popular Vehicle Details</h4>
						<ol class="breadcrumb no-bg mb-1">
							<li class="breadcrumb-item"><a href="view-popular-vehicle-one">Popular Vehicle List</a></li>
							<li class="breadcrumb-item active"><a href="#">Edit Popular Vehicle</a></li>
						</ol>
						<div class="box box-block bg-white">
						<div class="error-div"><?php if(isset($_SESSION['add_user_error_msg'])){ echo $_SESSION['add_user_error_msg']; unset($_SESSION['add_user_error_msg']); } ?></div>

							<form class="form-horizontal" method="post" name="edit_ad" enctype="multipart/form-data">
								<div class="row">
								
								<div class="col-sm-6">
								<div class="form-group">
									<label for="exampleInputEmail1"><b>Title*</b></label>
									<input type="text" class="form-control" name="title" id="title" placeholder="Enter the Title" value="<?php echo $row_user['title']; ?>" required="required"/>
									<small id="emailHelp" class="form-text text-muted">We'll never share your blogs headline with anyone else.</small>
								</div>
								<div class="form-group">
									<label for="exampleInputPassword1"><b>Description*</b></label>
									<textarea name="description" class="form-control" id="Description" required placeholder="Enter the Description"><?php echo $row_user['description']; ?></textarea>
								</div>
								
								<div class="form-group">
									<label for="exampleInputPassword1"><b>Extra Hour</b></label>
									<input type="text" class="form-control" name="extra_hours" id="extra_hours" placeholder="Enter the Extra Hour" value="<?php echo $row_user['extra_hours']; ?>" />
								</div>
								<div class="form-group">
									<label for="exampleInputPassword1"><b>Extra Kms</b></label>
									<input type="text" class="form-control" name="extra_kms" id="extra_kms" placeholder="Enter the Extra Kms" value="<?php echo $row_user['extra_kms']; ?>" />
								</div>
								</div>
							
								<div class="col-sm-6">
								<div class="form-group">
									<label for="exampleInputPassword1"><b>4 Hrs x 40 Kms*</b></label>
									<input type="text" class="form-control" name="every40hours" id="every40hours" placeholder="Enter the Seating Details" value="<?php echo $row_user['every40hours']; ?>" required="required"/>
								</div>
								<div class="form-group">
									<label for="exampleInputPassword1"><b>8Hrs x 80Kms*</b></label>
									<input type="text " class="form-control" name="every80hours" id="every80hours" placeholder="Enter the every80hours" value="<?php echo $row_user['every80hours']; ?>" required="required"/>
								</div>
								
								<div class="form-group">
									<label for="exampleInputFile"><b>Image (size: 330 X 225 px)</b></label>
									<input type="file" class="form-control-file" name="result_file" id="result_file" accept="image/*" aria-describedby="fileHelp"/>
									<small id="fileHelp" class="form-text text-muted">Here admin can upload the blogs picture.</small>
								</div>
								</div>
								<div class="col-md-12">
									<div class="pull-left">
									<input type="submit" class="btn btn-primary" value="Update Faculty" name="cus_update_user">
									</div>
									</div>
							</form>
							</div>
						</div>
					</div>
				</div>
				
		<script type="text/javascript" src="vendor/jquery/jquery-1.12.3.min.js"></script>
		<script type="text/javascript" src="vendor/tether/js/tether.min.js"></script>
		<script type="text/javascript" src="vendor/bootstrap4/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="vendor/detectmobilebrowser/detectmobilebrowser.js"></script>
		<script type="text/javascript" src="vendor/jscrollpane/jquery.mousewheel.js"></script>
		<script type="text/javascript" src="vendor/jscrollpane/mwheelIntent.js"></script>
		<script type="text/javascript" src="vendor/jscrollpane/jquery.jscrollpane.min.js"></script>
		<script type="text/javascript" src="vendor/jquery-fullscreen-plugin/jquery.fullscreen-min.js"></script>
		<script type="text/javascript" src="vendor/waves/waves.min.js"></script>
		<script type="text/javascript" src="vendor/switchery/dist/switchery.min.js"></script>
		<script type="text/javascript" src="vendor/bootstrap-inputmask/bootstrap-inputmask.min.js"></script>
		<script type="text/javascript" src="vendor/autoNumeric/autoNumeric-min.js"></script>
		<script type="text/javascript" src="vendor/dropify/dist/js/dropify.min.js"></script>
		<script type="text/javascript" src="vendor/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
		<script type="text/javascript" src="vendor/clockpicker/dist/jquery-clockpicker.min.js"></script>
		<script type="text/javascript" src="vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
		<!-- Neptune JS -->
		<script type="text/javascript" src="js/app.js"></script>
		<script type="text/javascript" src="js/demo.js"></script>
		<script type="text/javascript" src="js/forms-masks.js"></script>
		<script type="text/javascript" src="js/forms-upload.js"></script>
        </body>
		</html>