<?php
/**
 * Administrator page for sending of bulk mail to users
 */

$title = "Perched Administrator - Bulk Mailing";
session_start();
require_once("includes/security.php");
require_once "includes/header-admin.php";
require_once "../vendor/autoload.php";
include_once "includes/autoload.php"; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

error_reporting(E_STRICT | E_ALL);

if(isset($_POST['submit'])){
    $mail = new PHPMailer();
    //Enable SMTP debugging.
    $mail->SMTPDebug = 0;
    //Set PHPMailer to use SMTP.
    $mail->isSMTP();
    //Set SMTP host name
    $mail->Host = "in-v3.mailjet.com";
    //Set this to true if SMTP host requires authentication
    $mail->SMTPAuth = true;
    //SMTP connection will not close after each email sent, reduces SMTP overhead
    $mail->SMTPKeepAlive = true; 
    //Provide username and password
    $mail->Username = "895833ed5aaec5c37ca13f9047dabdb3";
    $mail->Password = "9c5b4d6bfa7a45a93d0b0602dc556ee6";
    //If SMTP requires TLS encryption then set it
    $mail->SMTPSecure = "tls";
    //Set TCP port to connect to
    $mail->Port = 587;
    // $mail->Port = 25;
    $mail->From = "czephyr1@gmail.com";
    $mail->FromName = "Chingwei";
    $mail->isHTML(true);
    $mail->Subject = $_POST['subject'];;
    $mail->Body = $_POST['message'];

    // Mail attachment processing
    $uploadFile = NULL;
    $uploadPath = NULL;
    $attach_exceed = FALSE;

    if ($_FILES['attachFile']['error'] == 0) {
        $uploadDir = 'uploads/sent_attached/';
        $uploadFile = 'sentmail_' . $_SESSION['userID'] . '_' . date('Ymd_His') . '.' . 
        pathinfo($_FILES['attachFile']['name'], PATHINFO_EXTENSION);
        $uploadPath = $uploadDir . $uploadFile;

        $mail->AddAttachment($_FILES['attachFile']['tmp_name'], $_FILES['attachFile']['name']);
        // if ($_FILES['attachFile']['size'] < 1048576 && $_FILES['attachFile']['size'] > 0) {
    } else {
        //The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.
        if ($_FILES['attachFile']['error'] == 2) {
            $attach_exceed = TRUE;
        }
    }

    if ($attach_exceed == TRUE) {
      echo '<p style="color:red;"><i><b> Error: Mail Attachment must be less than or equal to 1MB.</b></i></p>';
    } else {
        // Get all users' email from database
        $result = MailManager::getAllUserMailAddress();
        
        if (!(empty($result))) {
            $upload_status = FALSE;
            foreach($result as $row) {                               
    	          $mail->addAddress($row['email'], $row['fullname']);           

                if (!$mail->send()) {
                    echo "<p>Mailer Error (" . str_replace("@", "&#64;", $row["email"]) . ') ' . $mail->ErrorInfo . '</p>';
                    break; //Abandon sending
                } else {
                  //Save/upload copy of attachment to folder (if there is attachment)
                    try {
                        if ($uploadFile == NULL) {
                            MailManager::mailSent(
                              $_SESSION['userID'],
                              $row['email'], 
                              $_POST['subject'], 
                              $_POST['message'], 
                              NULL
                            );
                        } else {
                            if ($upload_status == FALSE) {
                                if (move_uploaded_file($_FILES['attachFile']['tmp_name'], $uploadPath)) {
                                    $upload_status = TRUE;
                                }
                            } 
                        
                            MailManager::mailSent(
                              $_SESSION['userID'],
                              $row['email'], 
                              $_POST['subject'], 
                              $_POST['message'], 
                              $uploadFile
                            );
                        }
                    } catch (Exception $e) {
                        print "Error!: " . $e->getMessage() . "<br>";
                    }
                    echo "<b>Message sent to :" . $row['fullname'] . ' (' . str_replace("@", "&#64;", $row['email']) . ')</b><br />';      
                }       	      
    	      }  
    	      // Clear all addresses and attachments for next loop
    	      $mail->clearAddresses();
    	      $mail->clearAttachments();
        } else {
            echo '<p><i>No users in the database.</i></p>';
        }   
        $mail->SmtpClose();
    }
}
?>

<div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="text-center rounded" style="background:linear-gradient(orange, white);padding:20px;">
            <h4>Bulk Mailing to ALL users</h4>
        </div>
      <form class="form-horizontal" action="sendmail.php" method="post" enctype="multipart/form-data">
          <!-- Text input-->
          <div class="form-group">
              <label for="textinput">Subject:</label>  
              <input id="textinput" name="subject" type="text" placeholder="add subject" class="form-control input-md">
          </div>
          <!-- Textarea -->
          <div class="form-group">
              <label for="textarea">Message</label>
              <textarea class="form-control" id="textarea" name="message" placeholder="add message text"></textarea>
          </div>
          <!-- File Button -->
          <!-- 
          <div class="form-group">
              <label for="filebutton">Attach file</label>
              <input name="uploaded_file" class="input-file" type="file">
          </div>
          -->
          <div class="form-group" id="attachFileContainer">
            <!-- MAX_FILE_SIZE must precede the file input field, imposed a limit of 1MB -->
            <input type="hidden" name="MAX_FILE_SIZE" value="1048576">
            <label for="attachFile" style="padding:5px 10px;color:#3EC4AC;border-radius:5px;">Attach Document:
            <br><i class="fa fa-file fa-lg" aria-hidden="true"></i></label>
            <input type="file" id="attachFile" name="attachFile" style="width:0;">
            <span id="uploadFilename" style="font-size:10px;"></span>
            <span id="errorMsg" style="font-size:10px;color:red;"></span>
            <div style="font-size:10px;margin-bottom:10px;font-style:italic;">*File must be less than 1 MB.<br> 
               <!-- *Allowed file types: txt pdf doc docx xls xlsx ppt pptx jpg png bmp gif jpeg zip -->
            </div>
        </div>
          <!-- Button -->
          <div class="form-group">
              <input type="reset" class="btn btn-gray" value="Clear">
              <input type="submit" name="submit" class="btn btn-green" value="Send">
          </div>
      </form>
    </div>
  </div>
</div>

<?php include "includes/footer.php"; ?>

<script>

  var x = document.getElementsByTagName("textarea");

  for (var i = 0; i < x.length; i++) {
    x[i].setAttribute('style', 'height:' + (x[i].scrollHeight) + 'px;width:100%;');
    x[i].addEventListener("input", OnInput, false);
  }

  function OnInput() {
    this.style.height = 'auto';
    this.style.height = (this.scrollHeight) + 'px';
  }

</script>

</body>
</html>
