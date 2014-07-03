<?php $title = 'HOME' ?>
HELLO <?= $_SESSION['uname'] ?> !!!!!!!!

<a href='<?= eh(url('user/logout')) ?>'><button class="btn btn-primary">Logout</button></a>