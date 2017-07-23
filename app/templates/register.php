<?php include 'header.php' ?>

<h2>S'enregistrer maintenant</h2>

<?php if ($message) ?>
<div style="color: red; font-size: 24px;"><?= $message ?></div>

<form action="/register" method="post">
	Login:<br>
	<input type="text" name="login">
	<br>
	Password:<br>
	<input type="password" name="password">
	<br><br>
	<input type="submit" value="S'enregistrer">
</form>

<?php include 'footer.php' ?>
