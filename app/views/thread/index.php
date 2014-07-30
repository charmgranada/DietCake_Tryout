<?php $title = 'Board Exercise in PHP DietCake' ?>

<div class='well' style='float:right; width:230px;' id='autoreload'>
    <center><h2>My Info</h2></center>
    <b>Username: </b><?php eh($user->username)?> <br/>
    <b>Full Name: </b><?php eh($user->firstname. " " .$user->lastname)?> <br/>
    <b>Email Address: </b><?php eh($user->email_add)?> <br/>
</div>

<form method='get' action='<?php eh(url('')) ?>'>
    <select name='search_by' style='float:left;margin-right:10px;width:auto;' 
    class='btn btn-small btn-inverse' onchange='this.form.submit()'>
        <?php
            foreach ($search_options as $search_option) {
                $selected = ($search_option == $search_by) ? 'selected' : '';
                echo "<option value='{$search_option}' {$selected}>{$search_option} Search</option>";
            }
        ?>
    </select>
    <input type='text' style='float:left;margin-right:10px;' name='search_item' 
    placeholder='<?php eh($placeholder)?>' value='<?php eh($search_item)?>'>
    <button type='submit' class='btn btn-primary'>Submit</button>
    <?php if ($search_by == 'Thread'): ?>
        <div class='well-small' style='margin-left:-10px;'>
            <select name='filter_by' style='width:auto;' class='btn btn-small btn-inverse' 
            onchange='this.form.submit()'>
                <?php
                    foreach ($filter_options as $filter_option) {
                        $selected = ($filter_option == $filter_by) ? 'selected' : '';
                        echo "<option {$selected}>{$filter_option}</option>";
                    }
                ?>
            </select>
            <select name='order_by' style='width:auto;' class='btn btn-small btn-inverse' 
            onchange='this.form.submit()'>
                <?php
                    foreach ($order_options as $order_option) {
                        $selected = ($order_option == $order_by) ? 'selected' : '';
                        echo "<option {$selected}>{$order_option}</option>";
                    }
                ?>
            </select>
        </div>
    <?php endif; ?>
</form>

<div id='autoreload'>
    <?php if ($search_by == Thread::SEARCH_BY_THREAD): ?>
    <div class='alert' style='width:600px;'>
        <?php if($num_rows): ?>
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
        <?php elseif ($num_rows == 0 && $search_item): ?>
            <h1>There are 0 thread(s) found</h1>
        <?php endif; ?>

        <center>
            <?php foreach ($threads as $thread): ?>
                <div class = 'well' style='border:1px dashed #bbb;width:70%;margin:5px;'>
                    <p align='left' style='font-weight:bold;'>
                    	<a href='<?php eh(url('comment/view', array('thread_id' => $thread->thread_id))) ?>'>
                            <?php eh($thread->title) ?>
                        </a>
                    </p>

                    <font size=2><?php eh($thread->description) ?></font><br/>

                    <p align='right'>
                        <font size=1>
                            <?php eh($thread->comment_ctr) ?> comment(s)
                            &nbsp;|&nbsp; <?php eh($thread->countLikes()) ?> 
                            <a href='<?php echo url('thread/addLikeDislike', array(
                                'thread_id' => $thread->thread_id, 
                                'home_page' => url(), 
                                'like_status' => 1)) ?>'><img src='/bootstrap/img/like.png' width='12px'></a>
                            &nbsp;|&nbsp; <?php eh($thread->countDislikes()) ?> 
                            <a href='<?php echo url('thread/addLikeDislike', array(
                                'thread_id' => $thread->thread_id, 
                                'home_page' => url(), 
                                'like_status' => 0)) ?>'><img src='/bootstrap/img/dislike.png' width='12px'></a>
                        </font>
                        <?php // THE EDIT AND DELETE CONTROLS FOR THE THREAD OF THE USER WHO CREATED IT
                        if($thread->user_id == $_SESSION['user_id']): ?>
                            <br/>
                            <a href='<?php eh(url('thread/edit', 
                                array('thread_id' => $thread->thread_id)))?>'><button 
                                class='btn btn-success btn-small'>Edit Info</button></a>
                            <a href='<?php eh(url('thread/delete', 
                                array('thread_id' => $thread->thread_id)))?>'><button 
                                class='btn btn-danger btn-small'>Delete Thread</button></a>
                        <?php endif; ?>
                    </p>

                    <p align='left' style='font-size:9px;font-style:italic;line-height:5px;'>
                        <?php 
                            if ($thread->updated != $thread->created) {
                                echo "Updated: " . date('F d, Y h:ia', strtotime($thread->updated));
                            } 
                        ?>
                        <font style='float:right;'>
                            Created: <?php eh(date('F d, Y h:ia', strtotime($thread->created))) ?>
                        </font> <br/> <br/>
                        Posted by: <?php eh($thread->username) ?>
                    </p>
                </div>
            <?php endforeach; 
            echo $pagination['controls']; ?>
        </center>
        <br/>
        <a class='btn btn-large btn-primary' href='<?php eh(url('thread/create')) ?>'>Create a new thread</a>
        <br/><br/>
    </div>
    <?php elseif ($search_by == Thread::SEARCH_BY_USER): ?>
    <div class='alert alert-info' style='width:600px;'>
        <?php if ($num_rows): ?>
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
        <?php elseif ($num_rows == 0 && $search_item): ?>
            <h1>There are 0 user(s) found</h1>
        <?php endif; ?>

        <center>
            <?php foreach ($users_found as $user): ?>
                <div class = 'well' style='color:#555555;border:1px dashed #bbb;width:70%;margin:5px;'>
                    <p align='left' style='font-weight:bold;'>
                        <?php eh($user->username) ?>
                    </p>
                    <b>Fullname: </b><?php eh($user->firstname. " " .$user->lastname) ?> <br/>
                    <b>Email Address: </b><?php eh($user->email_add) ?>
                </div>
            <?php endforeach; 
            echo $pagination['controls']; ?>
        </center>
        <br/>
    </div>
    <?php endif; ?>
</div>