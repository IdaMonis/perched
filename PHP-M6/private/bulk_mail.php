<?php

require '../vendor/autoload.php';
require '../classes/mailclass.php';
require_once '../vendor/swiftmailer/swiftmailer/lib/swift_required.php';

if(isset($_POST['submit'])){
  $subject = $_POST['subject'];
  $sendto = $_POST['email'];
  $body = $_POST['message'];  
  $file = $_FILES["file"];
  $file_name = $file["name"];
  $file_tmp = $file["tmp_name"];
  $file_ext = explode(".", $file_name);
  $file_ext = strtolower(end($file_ext));
  $allowed = array("txt", "pdf", "jpg" , "png" , "xlsx" , "docx");
  $target_dir = null;
  if(in_array($file_ext, $allowed)){
    $target_dir = "attachement/" . $file_name;
    move_uploaded_file($file_tmp,$target_dir);
  }
  $mailClient = new MailClass();
  $swiftmail = $mailClient->sendMail($subject,$sendto,$body,$target_dir);
}
?>

<form class="form-horizontal" action="bulk_mail.php" method="post" enctype="multipart/form-data">
<fieldset>
<!-- Form Name -->
<legend>Form Name</legend>
<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="textinput">Add Subject:</label>  
  <div class="col-md-4">
  <input id="textinput" name="subject" type="text" placeholder="add subject" class="form-control input-md">
  <span class="help-block">help</span>  
  </div>
</div>
<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="textinput">Send To:</label>  
  <div class="col-md-4">
  <input type="email" id="textinput" name="email" type="text" placeholder="add recipient email" class="form-control input-md">
  <span class="help-block">help</span>  
  </div>
</div>
<!-- Textarea -->
<div class="form-group">
  <label class="col-md-4 control-label" for="textarea">Message</label>
  <div class="col-md-4">                     
    <textarea class="form-control" id="textarea" name="message">default text</textarea>
  </div>
</div>
<!-- File Button --> 
<div class="form-group">
  <label class="col-md-4 control-label" for="filebutton">Attach file</label>
  <div class="col-md-4">
    <input name="file" class="input-file" type="file">
  </div>
</div>
<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="singlebutton">Click to Send Mail</label>
  <div class="col-md-4">
    <button type="submit" name="submit" class="btn btn-primary">Send</button>
  </div>
</div>
</fieldset>
</form>
</body>
</html>