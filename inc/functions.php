<?php
//application functions


function get_projects_list(){
  include "connection.php";
  try{
    return  $db->query("select * from projects")->fetchAll();
  }
  
  catch(Exception $e){
    echo "Eroare:  ".$e->getMessage()." </br>";
    return false;
  }
}


function get_project($filter){
  include "connection.php";
  $sql = "SELECT project_id, title, category FROM projects WHERE project_id = ?";
  try{
    $result = $db->prepare($sql);
    $result->bindValue(1, $filter, PDO::PARAM_STR);
    $result->execute();
  }catch(Exception $e){
    echo "Error!! get_project function";
  }
  return $result->fetchAll();
}

function get_task($filter){
  include "connection.php";
  $sql = "SELECT task_id, title, date, time, project_id FROM tasks WHERE task_id = ?";
  try{
    $result = $db->prepare($sql);
    $result->bindValue(1, $filter, PDO::PARAM_STR);
    $result->execute();
  }catch(Exception $e){
    echo "Error: get_task function";
  }
  return $result->fetch();
}

function delete_task($filter){
  include "connection.php";
  $sql = "DELETE FROM tasks WHERE task_id = ?";
  try{
    $result = $db->prepare($sql);
    $result->bindValue(1, $filter, PDO::PARAM_STR);
    $result->execute();
  }catch(Exception $e){
    echo "Error: get_task function";
    return false;
  }
  if($result->rowCount() > 0){
    return true;
  }else{
    return false;
}
}

function delete_project($filter){
  include "connection.php";
  $sql = "DELETE FROM projects WHERE project_id = ? AND project_id NOT IN ( SELECT project_id FROM tasks)";
  try{
    $result = $db->prepare($sql);
    $result->bindValue(1, $filter, PDO::PARAM_STR);
    $result->execute();
  }catch(Exception $e){
    echo "Error: get_task function";
    return false;
  }if($result->rowCount() > 0){
    return true;
  }else{
    return false;
}
}



function get_task_list($filter = null){
  include "connection.php";
  $sql="select tasks.*,projects.title as project from tasks
  inner join projects on tasks.project_id=projects.project_id";
  $orderBy = " ORDER BY tasks.date DESC";
  if($filter){
    $orderBy = " ORDER BY projects.project_id ASC, date DESC";
  }
  $where = " ";

  if(is_array($filter)){
    switch($filter[0]){
      case 'project':
        $where = ' WHERE projects.project_id = ?';
        break;
      case 'category':
        $where = ' WHERE projects.category = ?';
        break;
      case 'date':
          $where =' WHERE date >= ? AND date <= ?';
        break;

    }
  }

  try{
    $result = $db->prepare($sql . $where . $orderBy);
    if (is_array($filter)){
      if($filter[0] == 'project')
        $result->bindValue(1, $filter[1], PDO::PARAM_INT);
      if($filter[0] == 'category')
        $result->bindValue(1, $filter[1], PDO::PARAM_STR);
      if($filter[0] == 'date'){
        $result->bindValue(1, $filter[1], PDO::PARAM_STR);
        $result->bindValue(2, $filter[2], PDO::PARAM_STR);

      }
    }
    $result->execute();
    }
  catch(Exception $e){
    echo "Eroare " . $e->getMessage()." </br>";
    return false;
  }
  return $result->fetchAll(PDO::FETCH_ASSOC);
}


function addProjects($title,$category, $project_id = null){
  include "connection.php";
  if($project_id){
    $sql = "UPDATE projects SET title = ?, category = ? WHERE project_id = ?";
  } else{
    $sql="INSERT INTO projects(title,category) values(?,?)";
  }
  try{
    $results=$db->prepare($sql);
    $results->bindValue(1,$title,PDO::PARAM_STR);
    $results->bindValue(2,$category,PDO::PARAM_STR);
    if($project_id){
      $results->bindValue(3,$project_id,PDO::PARAM_INT);
    }
    $results->execute();
  }
  catch(Exception $e){
    echo "Error: " . $e->getMessage();
    return false;
  }
  return true;
};

function add_task($project_id, $title, $date, $time, $task_id = null){
  include "connection.php";

  $sql_date = explode("/", $date);
  $date = $sql_date[2] . "-" .$sql_date[0] . "-" . $sql_date[1];
  if(!empty($task_id)){
    $sql = "UPDATE tasks SET project_id = ?, title = ?, date = ?, time = ? WHERE task_id = ?";
  }else{
  $sql="INSERT INTO tasks(project_id, title, date, time) values(?,?,?,?)";}
  try{

    //prepares a statement to be excuted and it returns a PDOStatement obj.
    $results=$db->prepare($sql); 


    $results->bindValue(1,$project_id,PDO::PARAM_INT);
    $results->bindValue(2,$title,PDO::PARAM_STR);
    $results->bindValue(3,$date,PDO::PARAM_STR);
    $results->bindValue(4,$time,PDO::PARAM_INT);
    if(!empty($task_id)){
      $results->bindParam(5,$task_id,PDO::PARAM_INT);
    }
    if($results->execute() > 0){
      return true;
    } else {
      return false;
    } ;
  }
  catch(Exception $e){
    echo "Error: " . $e->getMessage();
    return false;
  }
}