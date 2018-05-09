<!doctype html>
<html lang="en">
<head>
  <title><?php $title ?></title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1,shrink-to-fit=no">
	<!-- Bootstrap CSS -->	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
	<script src="https://use.fontawesome.com/c51205b6e8.js"></script>
  <link href="https://fonts.googleapis.com/css?family=Lora|Merriweather" rel="stylesheet">
  <link href="../public/styles/style.css" rel="stylesheet">

</head>
<body>
<div class="main-content">
<!-- Navigation Bar -->
  <nav class="navbar navbar-toggleable-md" style="background: #3EC4AC;margin-bottom:20px;">
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <!-- <a class="navbar-brand mr-auto" href="profile.php"><img src="../../images/logo_full.png" height="80px" alt=""></a> -->
    <a class="navbar-brand mr-auto" href="/php-m6/private/admin.php"><img src="../public/images/logo_full.png" height="80px" alt=""></a>
    <ul class="navbar-nav" style="margin-right: 40px;">
      <li class="nav-item signin">
        <a class="nav-link" href="/php-m6/private/??.php" style="font-size: 20px;">Block/Unblock User &nbsp;<i class="fa fa-briefcase fa-2x" aria-hidden="true"></i></a>
      </li>
    </ul>
    <ul class="navbar-nav" style="margin-right: 40px;">
      <li class="nav-item signin">
        <!-- <a class="nav-link" href="/Simple-bulkmailer-master/index.html" style="font-size: 20px;">Bulk Email &nbsp;<i class="fa fa-envelope-o fa-2x" aria-hidden="true"></i></a> -->
        <a class="nav-link" href="sendmail.php" style="font-size: 20px;">Bulk Mailing &nbsp;<i class="fa fa-envelope-o fa-2x" aria-hidden="true"></i></a>
      </li>
    </ul>
    <ul class="navbar-nav" style="margin-right: 40px;">
      <li class="nav-item dropdown signin">
        <a class="nav-link dropdown-toggle" href = "#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 20px;">Profile&nbsp;<i class="fa fa-user-circle fa-2x" aria-hidden="true"></i></a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <!-- <a class="dropdown-item" href="profile.php?msg=editProfile">Edit Profile</a> -->
          <a class="dropdown-item" href="admin.php?msg=editProfile">Edit Profile</a>
          <a class="dropdown-item" href="logout.php">Logout</a>
        </div>
      </li>
    </ul>
    <form class="form-inline" name="search-form" method="get" action="/php-m6/public/modules/user/profile.php">
      <div class="input-group">
        <input class="form-control" type="text" placeholder="Search Users" required name="keywords">
        <button class="btn btn-outline-success" type="submit" style="background-color:white;"><i class="fa fa-search" aria-hidden="true"></i></button>
      </div>
    </form>
  </nav>
