<?php $title = 'Edit Account Info' ?>
<form method='post' class='well' action='<?php eh(url('')); ?>'>
    <b>Edit Account Info</b>
    <table align='center' cellpadding=1>
        <tr>
            <td colspan=2><center><b>Account Info</b></td>
        </tr>
        <tr>
            <td><p align='right'>Username: </td>
            <td><input type='text' name='username' value='<?php eh($_SESSION['username']); ?>'></td>
        </tr>
        <?php //VALIDATES USERNAME
            if (isset($errors['username'])) {
                echo ' <tr><td colspan=2>' . $errors['username'] . '</td></tr>';
            }
        ?>
        <tr>
            <td><p align='right'>Password: </td>
            <td><input type='password' name='password' value='<?php eh(Param::get('password')); ?>'></td>
        </tr>
        <?php //VALIDATES PASSWORD
            if (isset($errors['password'])) {
                echo ' <tr><td colspan=2>' . $errors['password'] . '</td></tr>';
            }
        ?>
        <tr>
            <td><p align='right'>Confirm Password: </td>
            <td><input type='password' name='confirm_password' value='<?php eh(Param::get('confirm_password')); ?>'></td>
        </tr>
        <?php //VALIDATES CONFIRM PASSWORD
            if (isset($errors['confirm_password'])) {
                echo ' <tr><td colspan=2>' . $errors['confirm_password'] . '</td></tr>';
            }
        ?>
        <?php //CHECKS IF PASSWORDS ARE THE SAME
            if (isset($errors['pass'])) {
                echo ' <tr><td colspan=2>' . $errors['pass'] . '</td></tr>';
            }
        ?>
        <tr>
            <td colspan=2><center><b>Personal Info</b></td>
        </tr>
        <tr>
            <td><p align='right'>First Name: </td>
            <td><input type='text' name='firstname' value='<?php eh($user->firstname); ?>'></td>
        </tr>
        <?php //VALIDATES FIRST NAME
            if (isset($errors['firstname'])) {
                echo ' <tr><td colspan=2>' . $errors['firstname'] . '</td></tr>';
            }
        ?>
        <tr>
            <td><p align='right'>Last Name: </td>
            <td><input type='text' name='lastname' value='<?php eh($user->lastname); ?>'></td>
        </tr>
        <?php //VALIDATES LAST NAME
            if (isset($errors['lastname'])) {
                echo ' <tr><td colspan=2>' . $errors['lastname'] . '</td></tr>';
            }
        ?>
        <tr>
            <td><p align='right'>Email Address: </td>
            <td><input type='text' name='email_add' value='<?php eh($user->email_add); ?>'></td>
        </tr>
        <?php //VALIDATES LAST NAME
            if (isset($errors['email_add'])) {
                echo ' <tr><td colspan=2>' . $errors['email_add'] . '</td></tr>';
            }
        ?>
        <tr>
            <td colspan=2><center>
                <button type='submit' class='btn btn-primary'>Save Changes</button>
                <?php 
                    if ($status) {
                        echo '<br/>' . $status; 
                    }
                ?>
            </td>
        </tr>
   </table>
    <a href='<?php eh(url('thread/index'))?>'>
        &larr; Back to Home
    </a>
</form>
