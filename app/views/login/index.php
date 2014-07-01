<?php $title = 'User Login' ?>
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
                <input type='hidden' name='cur_page' value='login'>
                <button type="submit" class="btn btn-primary">
                Submit</button></td>
            </tr>
        </table>
    </form>
<?php 
    if(isset($username) || isset($password)){
        if(empty($username) || empty($password)){
            echo "Please fill up all fields";
        }else{
            echo $status;            
        }
    }
 ?>