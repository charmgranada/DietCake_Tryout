<?php
    class ThreadController extends AppController
    {
        public function index()
        {
            check_user_logged_out();
            // TODO: Get all threads
            $threads = Thread::getAll();
            $this->set(get_defined_vars());
        }
        public function create()
        {
            check_user_logged_out();
	        $thread = new Thread;
	        $comment = new Comment;
	        $page = Param::get('page_next', 'create');
	        switch ($page) {
	            case 'create':
	                break;
	            case 'create_end':
	                $thread->title = Param::get('title');
	                $comment->username = Param::get('username');
	                $comment->body = Param::get('body');
	                try {
	                    $thread->create($comment);
	                } catch (ValidationException $e) {
	                    $page = 'create';
	                }
	                break;
	            default:
	                throw new NotFoundException("{$page} is not found");
	                break;
	        }
	        $this->set(get_defined_vars());
	        $this->render($page);
        }


    }
