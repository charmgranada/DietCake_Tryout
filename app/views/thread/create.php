<?php $title = 'Create a Thread' ?>
<h1>Create a thread</h1>
<?php // Checks for validation errors in the inputs passed
if ($thread->hasError()): ?>
    <div class='alert alert-block'>
        <h4 class='alert-heading'>Validation error!</h4>
        
        <?php if (!$thread->validation_errors['title']['length']): ?>
        <div>
            <em>Title</em> must be between
            <?php eh($thread->validation['title']['length'][1]) ?> and
            <?php eh($thread->validation['title']['length'][2]) ?> characters in length.
        </div>
        <?php endif ?>
          
        <?php // ERRORS FOR DESCRIPTION LENGTH VALIDATION //
        if (!$thread->validation_errors['description']['length']): ?>
            <div>
                <em>Description</em> must be between
                <?php eh($thread->validation['description']['length'][1]) ?> and
                <?php eh($thread->validation['description']['length'][2]) ?> characters in length.
            </div>
        <?php endif ?>
        
        <?php if (!$thread->validation_errors['description']['format']): ?>            
            <div>
                <em>Description</em> must have spaces to fit the screen.
            </div>        
        <?php endif ?>
    </div>
<?php endif ?>

<form class='well' method='post' action='<?php eh(url('')) ?>'>
    <label>Title</label>
    <input style='width=100%;' type='text' class='span2' name='title' value='<?php eh($thread_title) ?>'>

    <label>Description</label>
    <textarea style='width=100%;' name='description'><?php eh($description) ?></textarea><br/>
    
    <button type='submit' class='btn btn-primary'>Submit</button><br/><br/>
    <a href='<?php eh(url('thread/index'))?>'>
        &larr; Back to All Threads
    </a>
</form>
