<?php include 'header.php' ?>

<form action="/statuses" method="POST">
	<input type="hidden" name="_method" value="POST">
	<div class="row">
		<div class="input-field col s12">
			<input  placeholder="Enter your status" required id="message" name="message" class="validate">
		</div>
	</div>
	<button class="btn waves-effect waves-light" type="submit" name="action">Tweet!
		<i class="material-icons right">send</i>
	</button>
</form>


<form action="/statuses" method="GET">
    <select style="display: block;" name="orderBy">
      <option value="" disabled selected hidden>Order by</option>
      <option value="createdAt">Created At</option>
    </select>

    <select style="display: block;" name="order">
      <option value="" disabled selected hidden>Order</option>
      <option value="ASC">ASC</option>
      <option value="DESC">DESC</option>
    </select>

    <input placeholder="Number of status" id="limit" name="limit" type="number" class="validate" min=1>
    <button class="btn waves-effect waves-light" type="submit">Valide!
		<i class="material-icons right">send</i>
	</button>
</form>

<?php
if ($statuses!=null) {
    foreach ($statuses as $s) {
        ?>
	<div>
		<?php if (isset($_SESSION['login']) && $_SESSION['login'] == $s->getUser()->getLogin()) {
            ?>
		<form action="/statuses/<?php echo $s->getId(); ?>" method="POST">
			<input type="hidden" name="_method" value="DELETE">
			<input type="submit" value="Delete">
		</form>
		<?php 
        } ?>
		<p><?php echo $s->getUser()->getLogin(); ?></p>
		<p><?php echo $s->getDate()->format('Y-m-d').' at '.$s->getDate()->format('H:i:s'); ?></p>
		<p><?php echo $s->getContent(); ?></p>
		<p><a href="/statuses/<?php echo $s->getId(); ?>">See this status</a></p>
	</div>

	<?php

    }
} else {
    echo "<h3>No tweet in database.</h3>";
}

include 'footer.php' ?>