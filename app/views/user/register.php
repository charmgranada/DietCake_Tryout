<?php $title = "User Registration" ?>
<form method="post" class="well" action="<?php eh(url('')); ?>">
    <b>User Registration</b>
    <table align="center" cellpadding=1>
        <tr>
            <td colspan=2><center><b>Account Info</b></td>
        </tr>
        <tr>
            <td><p align='right'>Username: </td>
            <td><input type="text" name="username" value="<?php eh(Param::get("username")); ?>"></td>
        </tr>
        <?php //VALIDATES USERNAME
            echo (isset($errors['username'])
            ? " <tr><td colspan=2>" . $errors['username'] . "</td></tr>"
            : ""); ?>
        <tr>
            <td><p align='right'>Password: </td>
            <td><input type="password" name="password" value="<?php eh(Param::get("password")); ?>"></td>
        </tr>
        <?php //VALIDATES PASSWORD
            echo (isset($errors['password'])
            ? " <tr><td colspan=2>" . $errors['password'] . "</td></tr>"
            : ""); ?>
        <tr>
            <td><p align='right'>Confirm Password: </td>
            <td><input type="password" name="confirm_password" value="<?php eh(Param::get("confirm_password")); ?>"></td>
        </tr>
        <?php //VALIDATES CONFIRM PASSWORD
            echo (isset($errors['confirm_password'])
            ? " <tr><td colspan=2>" . $errors['confirm_password'] . "</td></tr>"
            : ""); ?>
        <?php //CHECKS IF PASSWORDS ARE THE SAME
            echo (isset($errors['pass'])
            ? " <tr><td colspan=2>" . $errors['pass'] . "</td></tr>"
            : ""); ?>
        <tr>
            <td colspan=2><center><b>Personal Info</b></td>
        </tr>
        <tr>
            <td><p align='right'>First Name: </td>
            <td><input type="text" name="firstname" value="<?php eh(Param::get("firstname")); ?>"></td>
        </tr>
        <?php //VALIDATES FIRST NAME
            echo (isset($errors['firstname'])
            ? " <tr><td colspan=2>" . $errors['firstname'] . "</td></tr>"
            : ""); ?>
        <tr>
            <td><p align='right'>Last Name: </td>
            <td><input type="text" name="lastname" value="<?php eh(Param::get("lastname")); ?>"></td>
        </tr>
        <?php //VALIDATES LAST NAME
            echo (isset($errors['lastname'])
            ? " <tr><td colspan=2>" . $errors['lastname'] . "</td></tr>"
            : ""); ?>
        <tr>
            <td><p align='right'>Email Address: </td>
            <td><input type="text" name="email_add" value="<?php eh(Param::get("email_add")); ?>"></td>
        </tr>
        <?php //VALIDATES LAST NAME
            echo (isset($errors['email_add'])
            ? " <tr><td colspan=2>" . $errors['email_add'] . "</td></tr>"
            : ""); ?>
        <tr>
            <td colspan=2><center>
                <button type='submit' class="btn btn-primary">Submit</button>
                <?php echo ($status) ? "<br/>" . $status : ""; ?>
            </td>
        </tr>
   </table>
    <a href="<?php eh(url('user/index'))?>">
        &larr; Back to Login
    </a>
</form>
