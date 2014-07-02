<?php $title = "User Registration" ?>
<form method="post" class="well" style="box-shadow: 0px 0px 10px black"
    action="<?php eh(url("")); ?>">
    <b>User Registration</b>
    <table align="center" cellpadding=1>
        <tr>
            <td colspan=2><center><b>Account Info</b></td>
        </tr>
        <tr>
            <td><p align='right'>Username: </td>
            <td><input type="text" name="uname" value="<?php eh(Param::get("uname")); ?>"></td>
        </tr>
        <?= //VALIDATES USERNAME
            isset($errors['uname'])
            ? " <tr><td colspan=2>" . $errors['uname'] . "</td></tr>"
            : ""; ?>
        <tr>
            <td><p align='right'>Password: </td>
            <td><input type="password" name="pword" value="<?php eh(Param::get("pword")); ?>"></td>
        </tr>
        <?= //VALIDATES PASSWORD
            isset($errors['pword'])
            ? " <tr><td colspan=2>" . $errors['pword'] . "</td></tr>"
            : ""; ?>
        <tr>
            <td><p align='right'>Confirm Password: </td>
            <td><input type="password" name="cpword" value="<?php eh(Param::get("cpword")); ?>"></td>
        </tr>
        <?= //VALIDATES CONFIRM PASSWORD
            isset($errors['cpword'])
            ? " <tr><td colspan=2>" . $errors['cpword'] . "</td></tr>"
            : ""; ?>
        <?= //CHECKS IF PASSWORDS ARE THE SAME
            isset($errors['pass'])
            ? " <tr><td colspan=2>" . $errors['pass'] . "</td></tr>"
            : ""; ?>
        <tr>
            <td colspan=2><center><b>Personal Info</b></td>
        </tr>
        <tr>
            <td><p align='right'>First Name: </td>
            <td><input type="text" name="fname" value="<?php eh(Param::get("fname")); ?>"></td>
        </tr>
        <?= //VALIDATES FIRST NAME
            isset($errors['fname'])
            ? " <tr><td colspan=2>" . $errors['fname'] . "</td></tr>"
            : ""; ?>
        <tr>
            <td><p align='right'>Middle Name: </td>
            <td><input type="text" name="mname" value="<?php eh(Param::get("mname")); ?>"></td>
        </tr>
        <?= //VALIDATES MIDDLE NAME
            isset($errors['mname'])
            ? " <tr><td colspan=2>" . $errors['mname'] . "</td></tr>"
            : ""; ?>
        <tr>
            <td><p align='right'>Last Name: </td>
            <td><input type="text" name="lname" value="<?php eh(Param::get("lname")); ?>"></td>
        </tr>
        <?= //VALIDATES LAST NAME
            isset($errors['lname'])
            ? " <tr><td colspan=2>" . $errors['lname'] . "</td></tr>"
            : ""; ?>
        <tr>
            <td><p align='right'>Contact Number: </td>
            <td><input type="text" name="cnum" value="<?php eh(Param::get("cnum")); ?>"></td>
        </tr>
        <?= //VALIDATES CONTACT NUMBER
            isset($errors['cnum'])
            ? " <tr><td colspan=2>" . $errors['cnum'] . "</td></tr>"
            : ""; ?>
        <tr>
            <td><p align='right'>Email Address: </td>
            <td><input type="text" name="email_add" value="<?php eh(Param::get("email_add")); ?>"></td>
        </tr>
        <?= //VALIDATES LAST NAME
            isset($errors['email_add'])
            ? " <tr><td colspan=2>" . $errors['email_add'] . "</td></tr>"
            : ""; ?>
        <tr>
            <td><p align='right'>Home Address: </td>
            <td><textarea name="home_add"><?php eh(Param::get("home_add")); ?></textarea></td>
        </tr>
        <?= //VALIDATES HOME ADDRESS
            isset($errors['home_add'])
            ? " <tr><td colspan=2>" . $errors['home_add'] . "</td></tr>"
            : ""; ?>
        <tr>
            <td colspan=2><center>
                <button class="btn btn-primary">Submit</button>
            </td>
        </tr>
   </table>

<?= (isset($dataPassed["uname"])) 
    ? "<center>" . $status
    : ""; ?>
</form>