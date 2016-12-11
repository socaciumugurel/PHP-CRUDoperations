<?php
require 'inc/functions.php';

$page = "reports";
$pageTitle = "Reports | Time Tracker";
$filter = 'all';

if(isset($_GET['filter'])){
    $filter = explode(":", filter_input(INPUT_GET, 'filter', FILTER_SANITIZE_STRING));
}

include 'inc/header.php';
?>
<div class="col-container page-container">
    <div class="col col-70-md col-60-lg col-center">
        <div class="col-container">
            <h1 class='actions-header'>Reports: <?php

                        if(!is_array($filter)){
                            echo " All tasks by project";
                        }if($filter[0] == 'project' ){
                            echo '<br>Project: ' . $filter[2];
                        
                            
                        }
                        else{
                            switch($filter[0]){
                                case 'project':
                                    $project = get_project($filter[1]);
                                    echo $project[0];
                                    break;
                                case 'category':
                                    echo $filter[1];
                                    break;
                                case 'date':
                                    echo date('D, d M Y', strtotime($filter[1])) . " - " . date('D, d M Y', strtotime($filter[2]));
                                    break;
                            }
                        }
                    ?>

            </h1>
            <form class='form-container form-report' action="reports.php" method="get">
                <label for="filter">Filter</labe>
                <select id="filter" name="filter">
                    <option value=''>Show All</option>
                    <optgroup label="Project"><?php 

                        foreach(get_projects_list() as $item){
                            echo '<option value="project:' . $item['project_id'] . ':' . $item['title'] . '">' . $item['title'] . '</option>';
                        }
                    ?>
                    </optgroup>
                    <optgroup label="Category">
                        <option value="category:Billable">Billable</option>
                        <option value="category:Charity">Charity</option>
                        <option value="category:Personal">Personal</option>
                    </optgroup>
                    <optgroup label="Date">
                        <option value="date:<?= date('Y-m-d', strtotime('-2 Sunday')) . ':' . date('Y-m-d', strtotime('-1 Saturday'));
                        ?>" >Last Week</option>
                        <option value="date:<?= date('Y-m-d', strtotime('-1 Sunday')) . ':' . date('Y-m-d');
                        ?>" >This Week</option>
                        <option value="date:<?= date('Y-m-d', strtotime('first day of last month')) . ':' . date('Y-m-d', strtotime('last day of last month'));
                        ?>" >Last Month</option>
                        <option value="date:<?= date('Y-m-d', strtotime('first day of this month')) . ':' . date('Y-m-d');
                        ?>" >This Month</option>
                    

                </select>

                <input class="button" type="submit" value="Run">
            </form>
        </div>
        <div class="section page">
            <div class="wrapper">
                <table>

                <?php 
                    $total = $project_id  = 0;
                    $task = get_task_list($filter);

                    foreach($task as $item){

                        if($project_id != $item['project_id']){
                            $project_id = $item['project_id'];
                            echo "<thead>";
                            echo "<tr>";
                            echo "<th>" . $item['project'] . "</th>";
                            echo "<th>Date</th>";
                            echo "<th>Time</th>";
                            echo "</tr>";
                            echo "</thead>";
                         
                        }
                     
                        $total += $item['time'];
                        echo "<tr>";
                        echo "<td>" . $item['title'] . "</td>";
                        echo "<td>" . $item['date'] . "</td>";
                        echo "<td> " . $item['time'] . "</td> </tr>";

                    }
                 ?>
                    <tr>
                        <th class='grand-total-label' colspan='2'>Grand Total</th>
                        <th class='grand-total-number'><?= $total ?></th>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include "inc/footer.php"; ?>

