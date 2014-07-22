<?php $title = "Edit '{$thread->title}' Thread" ?>
<h1>Edit '<?= $thread->title; ?>' thread</h1>
<?php // Checks for validation errors in the inputs passed
if ($thread->hasError()): ?>
    <div class='alert alert-block'>
        <h4 class='alert-heading'>Validation error!</h4>        
        <?php // ERRORS FOR TITLE LENGTH VALIDATION //
        if (!empty($thread->validation_errors['title']['length'])): ?>
            <div>
                <em>Title</em> must be between
                <?php eh($thread->validation['title']['length'][1]) ?> and
                <?php eh($thread->validation['title']['length'][2]) ?> characters in length.
            </div>
        <?php endif ?>
          
        <?php // ERRORS FOR DESCRIPTION LENGTH VALIDATION //
        if (!empty($thread->validation_errors['description']['length'])): ?>
            <div>
                <em>Description</em> must be between
                <?php eh($thread->validation['description']['length'][1]) ?> and
                <?php eh($thread->validation['description']['length'][2]) ?> characters in length.
            </div>
        <?php endif ?>

        <?php  // ERRORS FOR TOO LONG CHARACTERS WITH NO SPACES AND CAN'T FIT THE SCREEN ANYMORE //
        if (!empty($thread->validation_errors['description']['format'])): ?>
            <div>
                <em>Description</em> must have spaces to fit the screen.
            </div>        
        <?php endif ?>
    </div>
<?php endif ?>

<form class='well' method='post' action='<?php eh(url('')) ?>'>
    <label>Title</label>
    <input type='text' class='span2' name='title' value='<?php eh($thread->title) ?>'>

    <label>Description</label>
    <textarea name='description'><?php eh($thread->description) ?></textarea>
    <br />

    <button type='submit' class='btn btn-primary'>Save Changes</button><br/><br/>
    <a href='<?php eh(url('thread/index')) ?>'>
        &larr; Back to All Threads
    </a>
</form>
