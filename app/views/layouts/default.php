<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>DietCake <?php eh($title) ?></title>

    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px;
      }
    </style>
  </head>

  <body>

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <?php if(user_logged_in()===true): ?>
            <a class="brand" href="#">Welcome <?= $_SESSION['uname']?></a>   
            <a href='<?php eh(url('user/logout'))?>'>
              <button style='float:right;' class="btn btn-primary">
                Logout</button></a>         
          <?php else: ?>
            <a class="brand" href="#">DietCake ni Romz</a>
          <?php endif;?>
        </div>
      </div>
    </div>

    <div class="container">

      <?php echo $_content_ ?>

    </div>

    <script>
    console.log(<?php eh(round(microtime(true) - TIME_START, 3)) ?> + 'sec');
    </script>

  </body>
</html>
