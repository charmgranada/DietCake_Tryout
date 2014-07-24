<?php    
class User extends AppModel
{
    const USERS_TABLE = 'users';
    const PASS_MIN_LENGTH = 8;
    const PASS_MAX_LENGTH = 16;
    
    public $validation = array(
        'username' => array(
            'length' => array(
                'validate_between', MIN_LENGTH, MAX_LENGTH
            ),
            'format' => array(
                'is_valid_username', 'Username is invalid'
            ),
        ),
        'password' => array(
            'length' => array(
                'validate_between', self::PASS_MIN_LENGTH, self::PASS_MAX_LENGTH
            ),
        ),
    );

    /**
     *LOGIN A REGISTERED USER
     *@throws ValidationException
     *@throws RecordNotFoundException
     */
    public function authenticate()
    {
        if (!$this->validate()) {
            throw new ValidationException('Invalid Username/Password');
        }
        $db = DB::conn();
        $query = 'SELECT * FROM ' .self::USERS_TABLE. ' WHERE username = ? AND password = ?';
        $where_params = array($this->username, sha1($this->password));
        $row = $db->row($query, $where_params);
        if (!$row) {
            throw new RecordNotFoundException('Username/Password is incorrect');
        }        
        return new self ($row);
    }

    /**
     *SEARCH FOR A USER WITH THE GIVEN USERNAME
     *@param $username, $limit
     */
    public static function search($username, $limit)
    {
        $username = mysql_real_escape_string($username);
        $users_found = array();
        $db = DB::conn();
        $query = 'SELECT * FROM ' .self::USERS_TABLE. ' WHERE username LIKE \'%' .$username. '%\' 
        ORDER BY username LIMIT ' .$limit;
        $users = $db->rows($query); 
        foreach ($users as $user) {
            $users_found[] = new self($user);
        }
        return $users_found;
    }

    /**
     *GET NUMBER OF USERS FOUND
     *@param $username
     */
    public static function countFound($username)
    {
        $db = DB::conn();
        $query = 'SELECT COUNT(*) FROM ' .self::USERS_TABLE. ' WHERE username LIKE \'%' .$username. '%\'';
        $count = $db->value($query); 
        return $count;
    }

    /**
     *RETURNS ALL INFO OF A USER
     *@param $id
     */
    public static function get($id)
    {
        $db = DB::conn();
        $query = 'SELECT * FROM ' .self::USERS_TABLE. ' WHERE user_id = ?';
        $where_params = array($id);
        $row = $db->row($query, $where_params); 
        return new self ($row);
    }

    /**
     *REGISTERS A NEW USER
     *@param PersonalInfo $personal_info
     *@throws ExistingUserException
     *@throws ValidationException
     */
    public function register(PersonalInfo $personal_info)
    {
        $db = DB::conn();
        $where = 'username = ? OR email_add = ?';
        $where_params = array($this->username, $personal_info->email_add);
        $row = $db->search(self::USERS_TABLE, $where, $where_params);
        if (!$this->validate() || !$personal_info->validate()) {
            throw new ValidationException(notice('Validation Error', 'error'));
        }
        if ($row) {
            throw new ExistingUserException(notice('Username/Email Address has already been used', 'error'));
        }
        $where_params = array(
            'username' => $this->username,
            'password' => sha1($this->password),
            'firstname' => $personal_info->firstname,
            'lastname' => $personal_info->lastname,
            'email_add' => $personal_info->email_add
        );
        $db->insert(self::USERS_TABLE, $where_params);
    }

    /**
     *UPDATES A USER'S ACCOUNT INFO
     *@param PersonalInfo $personal_info
     *@throws ExistingUserException
     *@throws ValidationException
     */
    public function update(PersonalInfo $personal_info)
    {
        $db = DB::conn();
        $where = '(username = ? OR email_add = ?) AND user_id != ?';
        $where_params = array($this->username, $personal_info->email_add, $this->user_id);
        $row = $db->search(self::USERS_TABLE, $where, $where_params);
        if (!$this->validate() || !$personal_info->validate()) {
            throw new ValidationException(notice('Validation Error', 'error'));
        }
        if ($row) {
            throw new ExistingUserException(notice('Username/Email Address has already been used', 'error'));
        }
        $set_params = array(
            'username' => $this->username,
            'password' => sha1($this->password),
            'firstname' => $personal_info->firstname,
            'lastname' => $personal_info->lastname,
            'email_add' => $personal_info->email_add
        );
        $where_params = array('user_id' => $this->user_id);
        $db->update(self::USERS_TABLE, $set_params, $where_params);
    }
}
