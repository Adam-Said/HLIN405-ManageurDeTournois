<?php  if (count($teamnotif) > 0) : ?>

<div class="error">

    <?php foreach ($teamnotifs as $teamnotif) : ?>

      <p><?php echo $teamnotif ?></p>

    <?php endforeach ?>

</div>

<?php  endif ?>