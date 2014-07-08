<?php
    class ThreadController extends AppController
    {
        /**
         *VIEW ALL THREADS
         */        
        public function index()
        {
            check_user_logged_out();
            // TODO: Get all threads
            $threads = Thread::getAll();
            $this->set(get_defined_vars());
        }

        /**
         *CREATE A NEW THREAD
         *@throws NotFoundException
         */
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
                    $thread->user_id = $_SESSION['user_id'];
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

        /**
         *EDIT A THREAD
         *@throws NotFoundException
         */
        public function edit(){            
            $thread_id = Param::get('thread_id');
            $thread = Thread::get($thread_id);
            $thread_title = $thread->title;
            $comment = new Comment;
            $page = Param::get('page_next', 'edit');
            switch ($page) {
                case 'edit':
                    break;
                case 'edit_end':
                    $thread->title = Param::get('title');
                    $thread->user_id = $_SESSION['user_id'];
                    $comment->username = Param::get('username');
                    $comment->body = Param::get('body');
                    try {
                        $thread->edit($comment);
                    } catch (ValidationException $e) {
                        $page = 'edit';
                    }
                    break;
                default:
                    throw new NotFoundException("{$page} is not found");
                    break;
            }
            $this->set(get_defined_vars());
            $this->render($page);
        }
        
        /**
         *DELETE A THREAD
         */
        public function delete(){
            check_user_logged_out();
            $thread = Thread::get(Param::get('thread_id'));
            $thread_title = $thread->title;
            $thread->delete();  
            $this->set(get_defined_vars());
        }
    }
