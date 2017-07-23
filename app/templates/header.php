<html>
<head>
  <title>UFramework</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/css/materialize.min.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/js/materialize.min.js"></script>
</head>
<body>
  <nav>
    <div class="nav-wrapper">
      <a href="/" class="brand-logo">UFramework</a>
      <ul id="nav-mobile" class="right hide-on-med-and-down">
        <li><a href="/statuses">List Tweets</a></li>
        <?php if (isset($_SESSION['is_authenticated'])) {
    ?>
        <li><a href="/logout">Logout</a></li>
        <?php 
} else {
    ?>
        <li><a href="/login">Login</a></li>
        <li><a href="/register">Register</a></li>
        <?php 
} ?>
      </ul>
    </div>
  </nav>

  <div class="container">
