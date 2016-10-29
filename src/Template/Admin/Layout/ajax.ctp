<div class="flash" style="display: none; visibility: hidden; opacity: 0">
<?= $this->Flash->render('auth') ?>
<?= $this->Flash->render('backend') ?>
</div>
<?php
echo $this->fetch('content');