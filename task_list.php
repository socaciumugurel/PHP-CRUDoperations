<?php
require 'inc/functions.php';

$page = "tasks";
$pageTitle = "Task List | Time Tracker";

if(isset($_POST['delete'])){
  if(delete_task(filter_input(INPUT_POST, 'delete', FILTER_SANITIZE_NUMBER_INT))){
    header('location:task_list.php?msg=Task+deleted');
    exit;
  } else {
    header('location:task_list.php?msg=Could+not+delete+the+task');
    exit;
  }
}


if(isset($_GET['msg'])){
$error_message = filter_input(INPUT_GET, 'msg', FILTER_SANITIZE_STRING);
}



include 'inc/header.php';
?>
<div class="section catalog random">

    <div class="col-container page-container">
        <div class="col col-70-md col-60-lg col-center">

            <h1 class="actions-header">Task List</h1>
            <div class="actions-item">
                <a class="actions-link" href="task.php">
                    <span class="actions-icon">
                        <svg viewbox="0 0 64 64"><use xlink:href="#task_icon"></use></svg>
                    </span>
                Add Task</a>
            </div>
             <?php
              if(isset($error_message)){
                echo "<p class='message'>".$error_message."</p>";
              }
            ?>

            <div class="form-container">
              <ul class="items">
                <?php
foreach(get_task_list() as $item){
  echo "<li><a href='task.php?id=" . $item['task_id'] . "'>" . $item['title'] . "</a>";
  echo "<form action='task_list.php' method='POST' onsubmit=\"return confirm('Are you sure you want to delete this task?')\" ><input type='hidden' name='delete' value='" . $item['task_id'] . "'> ";
  echo "<input type='submit' class='button--delete' value='Delete' >";

  echo "</form>";


  echo "</li>";
}

                 ?>
              </ul>
            </div>

        </div>
    </div>
</div>

<?php include("inc/footer.php"); ?>


