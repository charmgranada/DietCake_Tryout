<?php
    class ThreadController extends AppController
    {
        const THREADS_PER_PAGE = 10;
        /**
         *VIEW ALL THREADS
         */        
        public function index()
        {
            check_user_logged_out();
            $cur_page = Param::get('pn');
            $num_rows = Thread::getNumRows();
            $url = "thread/index";
            $pagination = pagination($url, $num_rows, $cur_page, self::THREADS_PER_PAGE);
            // TODO: Get all threads
            $threads = Thread::getAll($pagination['limit']);
            $this->set(get_defined_vars());
        }

        /**
         *CREATE A NEW THREAD
         *@throws PageNotFoundException
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
                        $thread->createNew($comment);
                    } catch (ValidationException $e) {
                        $page = 'create';
                    }
                    break;
                default:
                    throw new PagePageNotFoundException("{$page} is not found");
                    break;
            }
            $this->set(get_defined_vars());
            $this->render($page);
        }

        /**
         *EDIT A THREAD
         *@throws PageNotFoundException
         */
        public function edit()
        {            
            $thread = Thread::get(Param::get('thread_id'));
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
                        $thread->setTitle($comment);
                    } catch (ValidationException $e) {
                        $page = 'edit';
                    }
                    break;
                default:
                    throw new PagePageNotFoundException("{$page} is not found");
                    break;
            }
            $this->set(get_defined_vars());
            $this->render($page);
        }
        
        /**
         *DELETE A THREAD
         */
        public function delete()
        {
            check_user_logged_out();
            $thread = Thread::get(Param::get('thread_id'));
            $thread_title = $thread->title;
            $thread->delete();  
            $this->set(get_defined_vars());
        }
    }
