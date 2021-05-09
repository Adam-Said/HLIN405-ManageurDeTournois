<?php  if (count($errors) > 0) : ?>
  <div class="error">
  	<?php foreach ($errors as $error) : ?>
  	  <p><?php echo $error ?></p>
  	<?php endforeach ?>
  </div>
<?php  endif ?>

<?php  if (count($notif) > 0) : ?>
  <div class="error">
  	<?php foreach ($notifs as $notif) : ?>
  	  <p><?php echo $notif ?></p>
  	<?php endforeach ?>
  </div>
<?php  endif ?>