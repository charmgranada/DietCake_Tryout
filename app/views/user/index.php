<?php $title = 'User Login'; ?>
<center>
    <form name='login_module' class='well' method='POST' action='<?php eh(url(''));?>'>
        <table>
            <tr>
                <th><center>
                User Login</th>
            </tr>
            <tr>
                <td><center>
                <input type='text' name='username' 
                placeholder='Username'
                value="<?php eh(Param::get('username')); ?>"><br/>
                <input type='password' name='password'
                placeholder='Password'
                value="<?php eh(Param::get('password')); ?>"></td>
            </tr>
            <tr>
                <td><center>
                <input type='hidden' name='page_next' value='home'>
                <button type="submit" class="btn btn-primary">
                Login</button> Click <a href='<?php eh(url('user/registration')) ?>'>here</a> to register</td>
            </tr>
        </table>
        <?= $status; ?>
    </form>
</center>