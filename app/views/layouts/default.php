<!DOCTYPE html>
<html lang='en'>
  <head>
    <meta charset='utf-8'>
    <title>Message Board <?php eh($title) ?></title>
    <link rel='icon' href='/bootstrap/img/favicon.png' type='image/x-icon'/>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js"></script>
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
    <script type="text/javascript">
      function Ajax()
        {
          var
            $http,
            $self = arguments.callee;

          if (window.XMLHttpRequest) {
            $http = new XMLHttpRequest();
          } else if (window.ActiveXObject) {
            try {
              $http = new ActiveXObject('Msxml2.XMLHTTP');
            } catch(e) {
              $http = new ActiveXObject('Microsoft.XMLHTTP');
            }
          }

          if ($http) {
            $http.onreadystatechange = function()
            {
              if (/4|^complete$/.test($http.readyState)) {
                var responseText = $http.responseText;
                document.getElementById('autoreload').innerHTML = $(responseText).find('#autoreload').html();
                setTimeout(function(){$self();}, 2000);
              }
            };
            $http.open('GET', document.URL, true);
            $http.send(null);
          }

        } 

      setTimeout(function() {Ajax();}, 2000);
    </script>
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
