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
            if($row = $teacher_stmt->fetch()){
              $role += 1;
              $response["approved"] = $row["approved"];
            }
            if($row = $student_stmt->fetch()){
              $role += 2;
              $response["approved"] = $row["approved"];
            }
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
      $response = array();
      $response["status"] = "ok";
      if(isset($this->db)){
        try{
          $this->db->beginTransaction();
          $statement = $this->db->prepare("UPDATE users SET deleted=:deleted WHERE login=:login");
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
          $statement = $this->db->prepare("SELECT login,role,deleted FROM users");
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
          if(isset($args["pwd"])){
            $statement = $this->db->prepare("UPDATE users SET password=:pwd WHERE login=:login");
            $statement->bindParam(":login", $args["login"]);
            $statement->bindParam(":pwd", $args["pwd"]);
            $statement->execute();
          }
          if(isset($args["role"])){
            $statement = $this->db->prepare("UPDATE users SET role=:role WHERE login=:login");
            $statement->bindParam(":login", $args["login"]);
            $statement->bindParam(":role", $args["role"]);
            $statement->execute();
          }
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
      if($args["unapproved"]) $tmp += 1;
      if($args["approved"])   $tmp += 2;
      if($args["undecided"])  $tmp += 4;
      if(isset($this->db)){
        try{
          switch($tmp){
            case 0: $response["status"] = "Empty request"; break;
            case 1: $statement = $this->db->prepare("SELECT * FROM ".$table." INNER JOIN subjects ON ".$table.".subject_ID=subjects.subject_ID WHERE ".$table.".approved=0");       break;
            case 2: $statement = $this->db->prepare("SELECT * FROM ".$table." INNER JOIN subjects ON ".$table.".subject_ID=subjects.subject_ID WHERE ".$table.".approved=1");        break;
            case 3: $statement = $this->db->prepare("SELECT * FROM ".$table." INNER JOIN subjects ON ".$table.".subject_ID=subjects.subject_ID WHERE ".$table.".approved IS NOT NULL"); break;
            case 4: $statement = $this->db->prepare("SELECT * FROM ".$table." INNER JOIN subjects ON ".$table.".subject_ID=subjects.subject_ID WHERE ".$table.".approved IS NULL");     break;
            case 5: $statement = $this->db->prepare("SELECT * FROM ".$table." INNER JOIN subjects ON ".$table.".subject_ID=subjects.subject_ID WHERE ".$table.".approved IS NULL OR ".$table.".approved=0");   break;
            case 6: $statement = $this->db->prepare("SELECT * FROM ".$table." INNER JOIN subjects ON ".$table.".subject_ID=subjects.subject_ID WHERE ".$table.".approved IS NULL OR ".$table.".approved=1");  break;
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

          if($args["correct"]) $correct = 1;
          else                 $correct = 0;
          $statement->bindParam(":correct", $correct);
          $statement->bindParam(":question_ID", $args["question_ID"]);
          $statement->bindParam(":login", $args["login"]);

          $statement->execute();


          if($args["correct"]){
            
           
            $statement = $this->db->prepare("UPDATE study SET points=points+:points WHERE login=:login AND subject_ID=(SELECT subject_ID FROM category INNER JOIN questions ON questions.category_ID=category.category_ID WHERE question_ID=:question_ID)");

            $statement->bindParam(":login", $args["login"]);
            $statement->bindParam(":question_ID", $args["question_ID"]);
            $statement->bindParam(":points", $args["points"]);

            $statement->execute();

            /*
            $statement = $this->db->prepare("UPDATE study SET points=points+(SELECT COUNT(rating_login) FROM answers INNER JOIN answer_ratings ON answers.question_ID=answer_ratings.question_ID AND answers.login=answer_ratings.answer_login) WHERE login=:login AND (SELECT subject_ID FROM category INNER JOIN questions ON questions.category_ID=category.category_ID WHERE question_ID=:question_ID)");
            $statement->bindParam(":login", $args["login"]);
            $statement->bindParam(":question_ID", $args["question_ID"]);
            $statement->execute();
            */
            
          }
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
          $statement = $this->db->prepare("UPDATE questions SET answer=:answer WHERE question_ID=:question_ID");
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

          $statement = $this->db->prepare("SELECT category_ID FROM category WHERE subject_ID=:subject_ID AND brief=:brief");

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

    public function write_answer($args){
      $response = array();
      $response["status"] = "ok";
      if(isset($this->db)){
        try{
          $this->db->beginTransaction();
          $question_owner = $this->db->prepare("SELECT login FROM questions WHERE question_ID=:question_ID");
          $question_owner->bindParam(":question_ID", $args["question_ID"]);
          $question_owner->execute();
          if($row = $question_owner->fetch()){
            if($row["login"] != $args["login"]){
              $statement = $this->db->prepare("INSERT INTO answers (login,answer,question_ID) VALUES(:login,:answer,:question_ID)");
              $statement->bindParam(":question_ID", $args["question_ID"]);
              $statement->bindParam(":answer", $args["answer"]);
              $statement->bindParam(":login", $args["login"]);
              $statement->execute();
            }
            else $response["status"] = "your_questions";
          }
          else $response["status"] = "wrong_question_ID";
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
          $statement = $this->db->prepare("INSERT INTO reactions (question_ID,answer_login,text,reaction_login) VALUES(:question_ID,:answer_login,:reaction,:reaction_login)");
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
          $statement = $this->db->prepare("INSERT INTO questions (login,category_ID,brief,full_question) VALUES(:login,:category_ID,:brief,:full_question)");
          $statement->execute($args);

          $statement = $this->db->prepare("SELECT question_ID FROM questions WHERE category_ID=:category_ID AND brief=:brief AND full_question=:full_question AND login=:login");
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

    public function login_to_points($args){
      $response = array();
      $response["status"] = "ok";
      if(isset($this->db)){
        try{
          $this->db->beginTransaction();
          $statement  = $this->db->prepare("SELECT COUNT(*) FROM answer_ratings WHERE question_ID=:question_ID AND answer_login=:login");
          
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

    public function number_of_ratings($args){
      $response = array();
      $response["status"] = "ok";
      if(isset($this->db)){
        try{
          $this->db->beginTransaction();
          $statement  = $this->db->prepare("SELECT COUNT(*) FROM answer_ratings WHERE question_ID=:question_ID AND rating_login=:login");
          
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

    public function list_reactions($args){
      $response = array();
      $response["status"] = "ok";
      if(isset($this->db)){
        try{
          $this->db->beginTransaction();
          $parent   = $this->db->prepare("SELECT answer FROM answers WHERE question_ID=:question_ID AND login=:login");
          $children = $this->db->prepare("SELECT * FROM reactions WHERE question_ID=:question_ID AND answer_login=:login");
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

    public function list_points($args){
      $response = array();
      $response["status"] = "ok";
      if(isset($this->db)){
        try{
          $statement = $this->db->prepare("SELECT subject_ID,login,points FROM study ORDER BY subject_ID, points DESC");
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

    public function list_students_to_subjects($args){
      $response = array();
      $response["status"] = "ok";
      if(isset($this->db)){
        try{
          $statement = $this->db->prepare("SELECT login,approved FROM study WHERE subject_ID=:subject_ID");
          
          $statement->execute($args);

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
?>