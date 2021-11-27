<?php 
  class Database {

    private $db_user = 'xkeprt03';
    private $db_pass = 'otoj5epu';
    private $db;
    private $init_error = 'ok';


    function __construct() {
      try {
        $this->db = new PDO("mysql:host=localhost;dbname=".$this->db_user.";port=/var/run/mysql/mysql.sock",
                            $this->db_user,$this->db_pass);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      }
      catch (PDOException $e) {
        $this->db = null;
        $this->init_error = "Database connection failed.".$e->getMessage();
      }
    }

    public function get_pdo() {
      return $this->db;
    }

    public function get_init_err() {
      return $this->init_error;
    }

    public function role_in_subject($args){
      $response = array();
      $response["status"] = "ok";
      if(isset($this->db)){
        try{
          if(!isset($args["subject_ID"])){
            if(isset($args["category_ID"])){
              $given = "category";
              $stmt = $this->db->prepare("SELECT subject_ID FROM category WHERE category_ID=:category_ID");
            }
            else if(isset($args["question_ID"])){
              $given = "question";
              $stmt = $this->db->prepare("SELECT subject_ID FROM category INNER JOIN questions ON questions.category_ID=category.category_ID WHERE question_ID=:question_ID");
            }
            else $response["status"] = "Not given ID to subject or category or question";

            if($response["status"] == "ok"){
              $stmt->bindParam(":".$given."_ID", $args[$given."_ID"]);
              $stmt->execute();
              if($row = $stmt->fetch()) $args["subject_ID"] = $row["subject_ID"];
              else                      $response["status"] = "There is no subject to given ".$given;
            }

          }
          if($response["status"] == "ok"){
            $teacher_stmt = $this->db->prepare("SELECT * FROM teach WHERE subject_ID=:subject_ID AND login=:login");
            $student_stmt = $this->db->prepare("SELECT * FROM study WHERE subject_ID=:subject_ID AND login=:login");
            $student_stmt->bindParam(":login", $args["login"]);
            $student_stmt->bindParam(":subject_ID", $args["subject_ID"]);
            $student_stmt->execute();

            $teacher_stmt->bindParam(":login", $args["login"]);
            $teacher_stmt->bindParam(":subject_ID", $args["subject_ID"]);
            $teacher_stmt->execute();

            $role = 0;
            if($teacher_stmt->fetch()) $role += 1;
            if($student_stmt->fetch()) $role += 2;
            switch($role){
              case 0:  $response["role"] = null; break;
              case 1:  $response["role"] = True; break;
              case 2:  $response["role"] = False; break;
              default: $response["status"] = "Neither teacher\student\unaffiliated"; break;
            }
          }
        }
        catch(PDOException $e){
          $response["status"] = "Database error: ".$e->getMessage();
        }
      }
      else $response["status"] = "Database connection not initialized";
      return $response;
    }

    public function insert_user($args){
      $response = array();
      $response["status"] = "ok"; 
      if(isset($this->db)){
        try{
          $this->db->beginTransaction();
          $statement = $this->db->prepare("INSERT INTO users (login,password,role) VALUES(:login,:pwd,:role)");
          $statement->execute($args);
          $this->db->commit();
        }
        catch(PDOException $e){
          $this->db->rollback();
          $response["status"] = "Database error: ".$e->getMessage();
        }
      }
      else $response["status"] = "Database connection not initialized";
      return $response;
    }

    public function delete_user($args){
      return json_encode(array("status"=>"Not implemented due to me wanting to sleep and this is too much to think about..."));
      $response = array();
      $response["status"] = "ok";
      if(isset($this->db)){
        try{
          $this->db->beginTransaction();
          $statement = $this->db->prepare("DELETE FROM users WHERE login=:login");
          $statement->execute($args);
          $this->db->commit();
        }
        catch(PDOException $e){
          $this->db->rollback();
          $response["status"] = "Database error: ".$e->getMessage();
        }
      }
      else $response["status"] = "Database connection not initialized";
      return $response;
    }

    public function list_users($args){
      $response = array();
      $response["status"] = "ok";
      if(isset($this->db)){
        try{
          $statement = $this->db->prepare("SELECT login,role FROM users");
          $statement->execute();

          $response["statement"] = $statement;
        }
        catch(PDOException $e){
          $response["status"] = "Database error: ".$e->getMessage();
        }
      }
      else $response["status"] = "Database connection not initialized";
      return $response;
    }

    public function find_user($args){
      $response = array();
      $response["status"] = "ok";
      if(isset($this->db)){
        try{
          $statement = $this->db->prepare("SELECT * FROM users WHERE login=:login");
          $statement->bindParam(":login", $args['login']);
          $statement->execute();

          $response["statement"] = $statement;
        }
        catch(PDOException $e){
          $response["status"] = "Database error: ".$e->getMessage();
        }
      }
      else $response["status"] = "Database connection not initialized";
      return $response;
    }

    public function change_password($args){
      $response = array();
      $response["status"] = "ok";
      if(isset($this->db)){
        try{
          $this->db->beginTransaction();
          $statement = $this->db->prepare("UPDATE users SET password=:pwd WHERE login=:login");
          $statement->execute($args);

          $response["statement"] = $statement;

          $this->db->commit();
        }
        catch(PDOException $e){
          $this->db->rollback();
          $response["status"] = "Database error: ".$e->getMessage();
        }
      }
      else $response["status"] = "Database connection not initialized";
      return $response;
    }

    public function list_subjects($args, $table="teach"){
      $response = array();
      $response["status"] = "ok";
      $tmp = 0;
      if($args["unapproved"] = true) $tmp += 1;
      if($args["approved"]   = true) $tmp += 2;
      if($args["undecided"]  = true) $tmp += 4;
      if(isset($this->db)){
        try{
          switch($tmp){
            case 0: $response["status"] = "Empty request"; break;
            case 1: $statement = $this->db->prepare("SELECT * FROM ".$table." INNER JOIN subjects ON ".$table.".subject_ID=subjects.subject_ID WHERE ".$table.".approved=False");       break;
            case 2: $statement = $this->db->prepare("SELECT * FROM ".$table." INNER JOIN subjects ON ".$table.".subject_ID=subjects.subject_ID WHERE ".$table.".approved=True");        break;
            case 3: $statement = $this->db->prepare("SELECT * FROM ".$table." INNER JOIN subjects ON ".$table.".subject_ID=subjects.subject_ID WHERE ".$table.".approved is not NULL"); break;
            case 4: $statement = $this->db->prepare("SELECT * FROM ".$table." INNER JOIN subjects ON ".$table.".subject_ID=subjects.subject_ID WHERE ".$table.".approved is NULL");     break;
            case 5: $statement = $this->db->prepare("SELECT * FROM ".$table." INNER JOIN subjects ON ".$table.".subject_ID=subjects.subject_ID WHERE ".$table.".approved!=True");       break;
            case 6: $statement = $this->db->prepare("SELECT * FROM ".$table." INNER JOIN subjects ON ".$table.".subject_ID=subjects.subject_ID WHERE ".$table.".approved!=False");      break;
            case 7: $statement = $this->db->prepare("SELECT * FROM ".$table." INNER JOIN subjects ON ".$table.".subject_ID=subjects.subject_ID"); break;
            default: $response["status"] = "Undefined combination"; break;
          }
          if($response["status"] == "ok"){
            $statement->execute();
            $response["statement"] = $statement;
          }
        }
        catch(PDOException $e){
          $response["status"] = "Database error: ".$e->getMessage();
        }
      }
      else $response["status"] = "Database connection not initialized";
      return $response;
    }

    public function approve_subject($args){
      $response = array();
      $response["status"] = "ok";
      if(isset($this->db)){
        try{
          $this->db->beginTransaction();
          $statement = $this->db->prepare("UPDATE teach SET approved=:approved WHERE subject_ID=:subject_ID and login=:login");
          $statement->execute($args);

          $this->db->commit();
        }
        catch(PDOException $e){
          $this->db->rollback();
          $response["status"] = "Database error: ".$e->getMessage();
        }
      }
      else $response["status"] = "Database connection not initialized";
      return $response;
    }

    public function approve_student($args){
      $response = array();
      $response["status"] = "ok";
      if(isset($this->db)){
        try{
          $this->db->beginTransaction();
          $statement = $this->db->prepare("UPDATE study set approved=:approved WHERE subject_ID=:subject_ID and login=:login");
          $statement->execute($args);
          $this->db->commit();
        }
        catch(PDOException $e){
          $this->db->rollback();
          $response["status"] = "Database error: ".$e->getMessage();
        }
      }
      else $response["status"] = "Database connection not initialized";
      return $response;
    }

    public function mark_answers($args){
      $response = array();
      $response["status"] = "ok";
      if(isset($this->db)){
        try{
          $this->db->beginTransaction();
          $statement = $this->db->prepare("UPDATE answers SET correct=:correct WHERE question_ID=:question_ID and login=:login");
          $statement->execute($args);
          $statement = $this->db->prepare("UPDATE study SET points=points+(SELECT COUNT(rating_login) FROM answers INNER JOIN answer_ratings ON answers.question_ID=answer_ratings.question_ID AND answers.login=answer_ratings.login)");
          $statement->execute();
          $this->db->commit();
        }
        catch(PDOException $e){
          $this->db->rollback();
          $response["status"] = "Database error: ".$e->getMessage();
        }
      }
      else $response["status"] = "Database connection not initialized";
      return $response;
    }

    public function final_answer($args){
      $response = array();
      $response["status"] = "ok";
      if(isset($this->db)){
        try{
          $this->db->beginTransaction();
          $statement = $this->db->prepare("UPDATE questions SET answer=:answer WHERE question_ID=:question_ID AND category_ID=:category_ID");
          $statement->execute($args);
          $this->db->commit();
        }
        catch(PDOException $e){
          $this->db->rollback();
          $response["status"] = "Database error: ".$e->getMessage();
        }
      }
      else $response["status"] = "Database connection not initialized";
      return $response;
    }

    public function create_category($args){
      $response = array();
      $response["status"] = "ok";
      if(isset($this->db)){
        try{
          $this->db->beginTransaction();
          $statement = $this->db->prepare("INSERT INTO category (subject_ID,brief) VALUES(:subject_ID,:brief)");
          $statement->execute($args);
          $this->db->commit();
        }
        catch(PDOException $e){
          $this->db->rollback();
          $response["status"] = "Database error: ".$e->getMessage();
        }
      }
      else $response["status"] = "Database connection not initialized";
      return $response;
    }

    public function write_answer($args){
      $response = array();
      $response["status"] = "ok";
      if(isset($this->db)){
        try{
          $this->db->beginTransaction();
          $statement = $this->db->prepare("INSERT INTO answers (login,answer,question_ID) VALUES(:login,:answer,:question_ID)");
          $statement->execute($args);
          $this->db->commit();
        }
        catch(PDOException $e){
          $this->db->rollback();
          $response["status"] = "Database error: ".$e->getMessage();
        }
      }
      else $response["status"] = "Database connection not initialized";
      return $response;
    }

    public function react($args){
      $response = array();
      $response["status"] = "ok";
      if(isset($this->db)){
        try{
          $this->db->beginTransaction();
          $statement = $this->db->prepare("INSERT INTO reactions (question_ID,answer_login,text) VALUES(:question_ID,:answer_login,:reaction)");
          $statement->execute($args);
          $this->db->commit();
        }
        catch(PDOException $e){
          $this->db->rollback();
          $response["status"] = "Database error: ".$e->getMessage();
        }
      }
      else $response["status"] = "Database connection not initialized";
      return $response;
    }

    public function vote($args){
      $response = array();
      $response["status"] = "ok";
      if(isset($this->db)){
        try{
          $this->db->beginTransaction();
          $statement = $this->db->prepare("INSERT INTO answer_ratings (question_ID,answer_login,rating_login) VALUES(:question_ID,:answer_login,:rating_login)");
          $statement->execute($args);
          $this->db->commit();
        }
        catch(PDOException $e){
          $this->db->rollback();
          $response["status"] = "Database error: ".$e->getMessage();
        }
      }
      else $response["status"] = "Database connection not initialized";
      return $response;
    }

    public function ask_question($args){
      $response = array();
      $response["status"] = "ok";
      if(isset($this->db)){
        try{
          $this->db->beginTransaction();
          $statement = $this->db->prepare("INSERT INTO questions (category_ID,brief,full_question) VALUES(:category_ID,:brief,:full_question)");
          $statement->execute($args);
          $this->db->commit();
        }
        catch(PDOException $e){
          $this->db->rollback();
          $response["status"] = "Database error: ".$e->getMessage();
        }
      }
      else $response["status"] = "Database connection not initialized";
      return $response;
    }

    public function create_course($args){
      $response = array();
      $response["status"] = "ok";
      if(isset($this->db)){
        try{
          $this->db->beginTransaction();
          $statement = $this->db->prepare("INSERT INTO subjects (subject_ID,subject_name) VALUES(:subject_ID,:subject_name)");
          $statement->bindParam(":subject_ID", $args["subject_ID"]);
          $statement->bindParam(":subject_name", $args["subject_name"]);
          $statement->execute();
          $statement = $this->db->prepare("INSERT INTO teach (login,subject_ID) VALUES(:login,:subject_ID)");
          $statement->bindParam(":login", $args["login"]);
          $statement->bindParam(":subject_ID", $args["subject_ID"]);
          $statement->execute();
          $this->db->commit();
        }
        catch(PDOException $e){
          $this->db->rollback();
          $response["status"] = "Database error: ".$e->getMessage();
        }
      }
      else $response["status"] = "Database connection not initialized";
      return $response;
    }

    public function sign_up_as_student($args){
      $response = array();
      $response["status"] = "ok";
      if(isset($this->db)){
        try{
          $this->db->beginTransaction();
          $statement = $this->db->prepare("INSERT INTO study (login,subject_ID) VALUES(:login,:subject_ID)");
          $statement->execute($args);
          $this->db->commit();
        }
        catch(PDOException $e){
          $this->db->rollback();
          $response["status"] = "Database error: ".$e->getMessage();
        }
      }
      else $response["status"] = "Database connection not initialized";
      return $response;
    }

    public function list_categories($args){
      $response = array();
      $response["status"] = "ok";
      if(isset($this->db)){
        try{
          $this->db->beginTransaction();
          $parent   = $this->db->prepare("SELECT subject_name FROM subjects WHERE subject_ID=:subject_ID");
          $children = $this->db->prepare("SELECT * FROM category WHERE subject_ID=:subject_ID");
          $parent->execute($args);
          $children->execute($args);

          $response["statement_parent"]   = $parent;
          $response["statement_children"] = $children;

          $this->db->commit();
        }
        catch(PDOException $e){
          $this->db->rollback();
          $response["status"] = "Database error: ".$e->getMessage();
        }
      }
      else $response["status"] = "Database connection not initialized";
      return $response;
    }

    public function list_questions($args){
      $response = array();
      $response["status"] = "ok";
      if(isset($this->db)){
        try{
          $this->db->beginTransaction();
          $parent   = $this->db->prepare("SELECT brief FROM category WHERE category_ID=:category_ID");
          $children = $this->db->prepare("SELECT * FROM questions WHERE category_ID=:category_ID");
          $parent->execute($args);
          $children->execute($args);

          $response["statement_parent"]   = $parent;
          $response["statement_children"] = $children;

          $this->db->commit();
        }
        catch(PDOException $e){
          $this->db->rollback();
          $response["status"] = "Database error: ".$e->getMessage();
        }
      }
      else $response["status"] = "Database connection not initialized";
      return $response;
    }

    public function list_answers($args){
      $response = array();
      $response["status"] = "ok";
      if(isset($this->db)){
        try{
          $this->db->beginTransaction();
          $parent   = $this->db->prepare("SELECT brief,full_question,answer FROM questions WHERE question_ID=:question_ID");
          $children = $this->db->prepare("SELECT * FROM answers WHERE question_ID=:question_ID");
          $parent->execute($args);
          $children->execute($args);

          $response["statement_parent"]   = $parent;
          $response["statement_children"] = $children;

          $this->db->commit();
        }
        catch(PDOException $e){
          $this->db->rollback();
          $response["status"] = "Database error: ".$e->getMessage();
        }
      }
      else $response["status"] = "Database connection not initialized";
      return $response;
    }

    public function list_reactions($args){
      $response = array();
      $response["status"] = "ok";
      if(isset($this->db)){
        try{
          $this->db->beginTransaction();
          $parent   = $this->db->prepare("SELECT answer FROM answers WHERE question_ID=:question_ID AND login=:login");
          $children = $this->db->prepare("SELECT * FROM reactions WHERE question_ID=:question_ID AND login=:login");
          $parent->execute($args);
          $children->execute($args);

          $response["statement_parent"]   = $parent;
          $response["statement_children"] = $children;

          $this->db->commit();
        }
        catch(PDOException $e){
          $this->db->rollback();
          $response["status"] = "Database error: ".$e->getMessage();
        }
      }
      else $response["status"] = "Database connection not initialized";
      return $response;
    }

    public function list_votes($args){
      $response = array();
      $response["status"] = "ok";
      if(isset($this->db)){
        try{
          $statement = $this->db->prepare("SELECT subject_ID,login,points FROM study ORDER BY points");
          $statement->execute();

          $response["statement"] = $statement;
        }
        catch(PDOException $e){
          $response["status"] = "Database error: ".$e->getMessage();
        }
      }
      else $response["status"] = "Database connection not initialized";
      return $response;
    }

  }
/*

Insert user
  INSERT INTO users (login,password,role) VALUES(:login,:pwd,:role)

Delete user
  DELETE FROM study WHERE login=:login
  DELETE FROM users WHERE login=:login
  ===Cascade remove all mentions===

List users
  SELECT login,role FROM users

Find user
  SELECT * FROM users WHERE login=:login

Change password
  UPDATE users SET password=:pwd WHERE login=:login

List subjects
  SELECT * FROM teach INNER JOIN subjects WHERE teach.approved={change based on request}

Approve subject
  UPDATE teach SET approved=:approved WHERE subject_ID=:subject_ID and login=:login

Approve student 
  UPDATE study set approved=:approved WHERE subject_ID=:subject_ID and login=:login

Create category
  INSERT INTO category (subject_ID,brief) VALUES(:subject_ID,:brief)

Mark answers
  UPDATE answers SET correct=:correct WHERE question_ID=:question_ID and login=:login
  UPDATE study SET points=points+(SELECT COUNT(rating_login) FROM answers INNER JOIN answer_ratings ON answers.question_ID=answer_ratings.question_ID AND answers.login=answer_ratings.login)

Final answer
  UPDATE questions SET answer=:answer WHERE question_ID=:question_ID AND category_ID=:category_ID

Write answer
  INSERT INTO answers (login,answer) VALUES(:login,:answer)

React
  INSERT INTO reactions (question_ID,answer_login,text) VALUES(:question_ID,:answer_login,:reaction)

Vote
  INSERT INTO answer_ratings (question_ID,answer_login,rating_login) VALUES(:question_ID,:answer_login,:rating_login)

Ask question
  INSERT INTO questions (category_ID,brief,full_question) VALUES(:category_ID,:brief,:full_question)

Create course
  INSERT INTO subjects (subject_ID,subject_name) VALUES(:subject_ID,:subject_name)
  INSERT INTO teach (login,subject_ID) VALUES(:login,:subject_ID)

Sign up as student
  INSERT INTO study (login,subject_ID) VALUES(:login,:subject_ID)

List categories
  SELECT subject_name FROM subjects WHERE subject_ID=:subject_ID
  SELECT * FROM category WHERE subject_ID=:subject_ID

List questions
  SELECT brief FROM category WHERE category_ID=:category_ID
  SELECT * FROM questions WHERE category_ID=:category_ID

List answers
  SELECT brief,full_question,answer FROM questions WHERE question_ID=:question_ID
  SELECT * FROM answers WHERE question_ID=:question_ID

List reactions
  SELECT answer FROM answers WHERE question_ID=:question_ID AND login=:login
  SELECT * FROM reactions WHERE question_ID=:question_ID AND login=:login

List_votes
  SELECT subject_ID,login,points FROM study ORDER BY points
*/




?>