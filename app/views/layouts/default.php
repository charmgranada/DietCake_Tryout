<!DOCTYPE html>
<html lang='en'>
  <head>
    <meta charset='utf-8'>
    <title>Message Board <?php eh($title) ?></title>
    <link rel='icon' href='/bootstrap/img/favicon.png' type='image/x-icon'/>
    <link href='/bootstrap/css/bootstrap.min.css' rel='stylesheet'>
    <style>
      body {
        padding-top: 60px;
        background-image: url(/bootstrap/img/bg.jpg);
        background-size: 30%;
        background-attachment: fixed;
        background-position: center;
      }
    </style>
  </head>

  <body>

    <div class='navbar navbar-fixed-top'>
      <div class='navbar-inner'>
        <div class='container'>
          <img class='logo' src='/bootstrap/img/favicon.png'>
          <font class='brand' href='#'>Message Board</font>
          <?php if($user = user_logged_in()): ?>
            <div style='float:right'>
              <select style='width:auto;' class='btn btn-small btn-info' 
              onchange='location = this.options[this.selectedIndex].value;'>
                <option selected='selected' hidden>
                  <?php echo $user->lastname. ', ' .$user->firstname; ?>
                </option>
                <option value='<?php eh(url('thread/index'))?>'>
                    Home
                </option>
                <option value='<?php eh(url('user/edit_account_info'))?>'>
                    Edit Account Info
                </option>    
                <option value='<?php eh(url('user/logout'))?>'>
                    Logout
                </option>  
              </select>
            </div>       
          <?php endif;?>
        </div>
      </div>
    </div>

    <div class='container'>

      <?php echo $_content_ ?>

    </div>

    <script>
    console.log(<?php eh(round(microtime(true) - TIME_START, 3)) ?> + 'sec');
    </script>

  </body>
</html>
