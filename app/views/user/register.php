<?php $title = "User Registration" ?>
<form method="post" class="well" action="<?php eh(url("")); ?>">
    <b>User Registration</b>
    <table align="center" cellpadding=1>
        <tr>
            <td colspan=2><center><b>Account Info</b></td>
        </tr>
        <tr>
            <td><p align='right'>Username: </td>
            <td><input type="text" name="uname" value="<?php eh(Param::get("uname")); ?>"></td>
        </tr>
        <?php //VALIDATES USERNAME
            echo (isset($errors['uname'])
            ? " <tr><td colspan=2>" . $errors['uname'] . "</td></tr>"
            : ""); ?>
        <tr>
            <td><p align='right'>Password: </td>
            <td><input type="password" name="pword" value="<?php eh(Param::get("pword")); ?>"></td>
        </tr>
        <?php //VALIDATES PASSWORD
            echo (isset($errors['pword'])
            ? " <tr><td colspan=2>" . $errors['pword'] . "</td></tr>"
            : ""); ?>
        <tr>
            <td><p align='right'>Confirm Password: </td>
            <td><input type="password" name="cpword" value="<?php eh(Param::get("cpword")); ?>"></td>
        </tr>
        <?php //VALIDATES CONFIRM PASSWORD
            echo (isset($errors['cpword'])
            ? " <tr><td colspan=2>" . $errors['cpword'] . "</td></tr>"
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
            <td><input type="text" name="fname" value="<?php eh(Param::get("fname")); ?>"></td>
        </tr>
        <?php //VALIDATES FIRST NAME
            echo (isset($errors['fname'])
            ? " <tr><td colspan=2>" . $errors['fname'] . "</td></tr>"
            : ""); ?>
        <tr>
            <td><p align='right'>Middle Name: </td>
            <td><input type="text" name="mname" value="<?php eh(Param::get("mname")); ?>"></td>
        </tr>
        <?php //VALIDATES MIDDLE NAME
            echo (isset($errors['mname'])
            ? " <tr><td colspan=2>" . $errors['mname'] . "</td></tr>"
            : ""); ?>
        <tr>
            <td><p align='right'>Last Name: </td>
            <td><input type="text" name="lname" value="<?php eh(Param::get("lname")); ?>"></td>
        </tr>
        <?php //VALIDATES LAST NAME
            echo (isset($errors['lname'])
            ? " <tr><td colspan=2>" . $errors['lname'] . "</td></tr>"
            : ""); ?>
        <tr>
            <td><p align='right'>Contact Number: </td>
            <td><input type="text" name="cnum" value="<?php eh(Param::get("cnum")); ?>"></td>
        </tr>
        <?php //VALIDATES CONTACT NUMBER
            echo (isset($errors['cnum'])
            ? " <tr><td colspan=2>" . $errors['cnum'] . "</td></tr>"
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
            <td><p align='right'>Home Address: </td>
            <td><textarea name="home_add"><?php eh(Param::get("home_add")); ?></textarea></td>
        </tr>
        <?php //VALIDATES HOME ADDRESS
            echo (isset($errors['home_add'])
            ? " <tr><td colspan=2>" . $errors['home_add'] . "</td></tr>"
            : ""); ?>
        <tr>
            <td colspan=2><center>
                <button type='submit' class="btn btn-primary">Submit</button>
                <?php echo (isset($form["uname"]) ? "<br/>" . $status : ""); ?>
            </td>
        </tr>
   </table>
    <a href="<?php eh(url('user/index'))?>">
        &larr; Back to Login
    </a>
</form>
