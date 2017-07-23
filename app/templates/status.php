<?php include 'header.php';
if ($status) {
    if (isset($_SESSION['login']) && $_SESSION['login'] == $status->getUser()->getLogin()) {
        ?>
	<form action="/statuses/<?php echo $status->getId(); ?>" method="POST">
		<input type="hidden" name="_method" value="DELETE">
		<input type="submit" value="Delete">
	</form>

	<?php 
    }
}
?>
<p><?php echo $status->getUser()->getLogin(); ?></p>
<p><?php echo $status->getDate()->format('Y-m-d').' at '.$status->getDate()->format('H:i:s'); ?></p>
<p><?php echo $status->getContent(); ?></p>

<?php include 'footer.php';
