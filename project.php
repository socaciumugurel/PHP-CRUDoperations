<?php
  require 'inc/functions.php';

  $pageTitle = "Project | Time Tracker";
  $page = "projects";

  if(isset($_GET['id'])){
    list($project_id, $existing_title, $category) = get_project(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT))[0];
  }

  if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $project_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $title=trim(filter_input(INPUT_POST,'title',FILTER_SANITIZE_STRING));
    $category=trim(filter_input(INPUT_POST,'category',FILTER_SANITIZE_STRING));
     
     if(empty($title) || empty($category)){
       $error_message="Campuri invalide, title sau category";
 }

     else if(addProjects($title, $category, $project_id)) {
        header('location:project_list.php');
        die();
      }
      else{
        $error_message="Project already exists";
      }
    }
  

  include 'inc/header.php';
?>

<div class="section page">
    <div class="col-container page-container">
        <div class="col col-70-md col-60-lg col-center">
            <h1 class="actions-header">

            <?php 
              if(empty($project_id))
              {
                echo "Add ";
              }
              else {echo "Update ";}
                        ?>Project</h1>
            <?php
              if(isset($error_message)){
                echo "<p class='message'>".$error_message."</p>";
              }
            ?>
            <form class="form-container form-add" method="post" action="project.php">
                <table>
                    <tr>
                        <th><label for="title">Title<span class="required">*</span></label></th>
                        <td><input type="text" id="title" name="title" value=<?php if(isset($existing_title)){echo "'" . $existing_title . "'";} ?> ></td>
                    </tr>
                    <tr>
                        <th><label for="category">Category<span class="required">*</span></label></th>
                        <td><select id="category" name="category">
                                <option value="">Select One</option>
                                <option value="Billable" 
                                <?php 
                                  if(isset($category) && $category == "Billable"){
                                    echo 'selected';
                                  }
                                 ?>
                                 >Billable</option>
                                <option value="Charity"
                                <?php 
                                  if(isset($category) && $category == "Charity"){
                                    echo 'selected';
                                  }
                                 ?>>Charity</option>
                                <option value="Personal"
                                <?php 
                                  if(isset($category) && $category == "Personal"){
                                    echo 'selected';
                                  }
                                 ?>>Personal</option>
                        </select></td>
                    </tr>
                </table>
                <?php 
                  if(!empty($project_id)){
                    echo "<input type='hidden' name='id' value='" . $project_id . "' />";
                  }
                ?>
                <input class="button button--primary button--topic-php" type="submit" value="Submit" />
            </form>
        </div>
    </div>
</div>

<?php include "inc/footer.php"; ?>
