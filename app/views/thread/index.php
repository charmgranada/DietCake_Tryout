<?php $title = 'Board Exercise in PHP DietCake' ?>

<form id='filter_and_search' method='get' action='<?php eh(url('')) ?>'>
    <select name='search_by' style='float:left;margin-right:10px;width:auto;' 
    class='btn btn-small btn-inverse' onchange='this.form.submit()'>
        <option value='<?php eh($search_by); ?>'><?php eh($search_by) ?> Search</option>
        <?php
            foreach ($search_options as $search_option) {
                if ($search_option != $search_by) {
                    echo "<option value='{$search_option}'>{$search_option} Search</option>";
                }
            }
        ?>
    </select>
    <input type='text' style='float:left;margin-right:10px;' name='search_item' placeholder='<?php eh($placeholder)?>'
    value='<?php eh(Param::get('search_item'))?>'>
    <button type='submit' class='btn btn-primary'>Submit</button>
    <?php if ($search_by == 'Thread'): ?>
        <select name='filter_by' style='width:auto;' class='btn btn-small btn-inverse' onchange='this.form.submit()'>
            <option><?php eh($filter_by) ?></option>
            <?php
                foreach ($filter_options as $filter_option) {
                    if ($filter_option != $filter_by) {
                        echo "<option>{$filter_option}</option>";
                    }
                }
            ?>
        </select>
    <?php endif; ?>
</form>

<div class='well' style='float:right; width:230px;'>
    <center><h2>My Info</h2></center>
    <b>Username: </b><?php eh($user->username)?> <br/>
    <b>Full Name: </b><?php eh($user->firstname. " " .$user->lastname)?> <br/>
    <b>Email Address: </b><?php eh($user->email_add)?> <br/>
</div>

<?php if ($search_by == 'Thread'): ?>
    <div class='alert' style='width:600px;'>
        <?php if($num_rows > 0): ?>
            <p align='right' style='font-size:12px;line-height:12px;'>
                Total of 
                    <b>
                        <?php eh($num_rows); ?>
                    </b>
                <?php echo ($num_rows==1) ? 'thread' : 'threads'; ?><br/>
                Page 
                    <b>
                        <?php eh($pagination['cur_page']); ?>
                    </b> 
                of 
                    <b>
                        <?php eh($pagination['last_page']); ?>
                    </b>
            </p>
        <?php else: ?>
            <h1>There are 0 result found</h1>
        <?php endif; ?>

        <center>
            <?php foreach ($threads as $v): ?>
                <div class = 'alert' style='border:1px dashed #bbb;width:70%;margin:2px;'>
                    <p align='left' style='font-weight:bold;'>
                    	<a href='<?php eh(url('comment/view', array('thread_id' => $v->thread_id))) ?>'>
                            <?php eh($v->title) ?>
                        </a>
                    </p>

                    <font size=2><?php eh($v->description) ?></font>

                    <?php // THE EDIT AND DELETE CONTROLS FOR THE THREAD, ACCESSED ONLY BY THE USER WHO CREATED IT
                    if($v->user_id == $_SESSION['user_id']): ?>
                        <p align='right'>
                            <a href='<?php eh(url('thread/edit', array('thread_id' => $v->thread_id)))?>'><button 
                                class='btn btn-success btn-small'>Edit Info</button></a>
                            <a href='<?php eh(url('thread/delete', array('thread_id' => $v->thread_id)))?>'><button 
                                class='btn btn-danger btn-small'>Delete Thread</button></a>
                        </p>
                    <?php endif; ?>

                    <p align='left' style='font-size:10px;font-style:italic;'>
                        Posted by: <?php eh($v->username) ?>
                        <font style='float:right;'>
                            <?php eh(date_format(new DateTime($v->created),'F d, Y h:ia')) ?>
                        </font>
                    </p>
                </div>
            <?php endforeach; 
            echo $pagination['controls']; ?>
        </center>
        <br/>
        <a class='btn btn-large btn-primary' href='<?php eh(url('thread/create')) ?>'>Create a new thread</a>
        <br/><br/>
    </div>
<?php elseif ($search_by == 'User'): ?>
    <div class='well alert-info' style='width:600px;'>
        <?php if ($num_rows > 0): ?>
            <p align='right' style='font-size:12px;line-height:12px;'>
                Total of 
                    <b>
                        <?php eh($num_rows); ?>
                    </b>
                <?php echo ($num_rows==1) ? 'user' : 'users'; ?><br/>
                Page 
                    <b>
                        <?php eh($pagination['cur_page']); ?>
                    </b> 
                of 
                    <b>
                        <?php eh($pagination['last_page']); ?>
                    </b>
            </p>
        <?php else: ?>
            <h1>There are 0 result found</h1>
        <?php endif; ?>

        <center>
            <?php foreach ($users_found as $u): ?>
                <div class = 'well alert-info' style='color:#555555;border:1px dashed #bbb;width:70%;margin:2px;'>
                    <p align='left' style='font-weight:bold;'>
                        <?php eh($u->username) ?>
                    </p>
                    <b>Fullname: </b><?php eh($u->firstname. " " .$u->lastname) ?> <br/>
                    <b>Email Address: </b><?php eh($u->email_add) ?>
                </div>
            <?php endforeach; 
            echo $pagination['controls']; ?>
        </center>
    </div>
<?php endif; ?>