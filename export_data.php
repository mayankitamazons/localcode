<?php
include("config.php"); 
if(isset($_GET['export'])){
    if($_GET['export'] == 'true') {
        if ($_GET['type'] == 1) {
            $query = "SELECT t1.`name`, t1.`id`, t1.`mobile_number`, t2.total_date FROM users t1 
                        LEFT JOIN (SELECT SUM(LEFT(tt1.sum_date,2) * 3600 + SUBSTRING(tt1.sum_date, 4,2) * 60 + SUBSTRING(tt1.sum_date, 7,2)) total_date, user_id
                        FROM (SELECT TIMEDIFF(end_date, start_date) sum_date, user_id FROM `work_diary` WHERE MID(end_date, 1, 10) = CURDATE()) tt1 GROUP BY tt1.user_id) t2 ON t1.`id` = t2.`user_id`
                        Where t1.`user_roles` = 1 and t1.`isLocked` = 0 
                        GROUP BY t1.`id`
                        ORDER BY t1.`id` ASC ";
            $title_date = Date('Y-m-d');
        } else if ($_GET['type'] == 3) {
            $query = "SELECT t1.`name`, t1.`id`, t1.`mobile_number`, t2.total_date FROM users t1 
            LEFT JOIN (SELECT SUM(LEFT(tt1.sum_date,2) * 3600 + SUBSTRING(tt1.sum_date, 4,2) * 60 + SUBSTRING(tt1.sum_date, 7,2)) total_date, user_id
            FROM (SELECT TIMEDIFF(end_date, start_date) sum_date, user_id FROM `work_diary` WHERE MID(end_date, 1, 7) = MID(CURDATE(), 1, 7)) tt1 GROUP BY tt1.user_id) t2 ON t1.`id` = t2.`user_id`
            Where t1.`user_roles` = 1 and t1.`isLocked` = 0 
            GROUP BY t1.`id`
            ORDER BY t1.`id` ASC ";
            $title_date = Date('Y-m');
        } else if ($_GET['type'] == 2) {
            $query = "SELECT t1.`name`, t1.`id`, t1.`mobile_number`, t2.total_date FROM users t1 
            LEFT JOIN (SELECT SUM(LEFT(tt1.sum_date,2) * 3600 + SUBSTRING(tt1.sum_date, 4,2) * 60 + SUBSTRING(tt1.sum_date, 7,2)) total_date, user_id
            FROM (SELECT TIMEDIFF(end_date, start_date) sum_date, user_id FROM `work_diary` WHERE WEEK(MID(end_date, 1, 10)) = WEEK(CURDATE())) tt1 GROUP BY tt1.user_id) t2 ON t1.`id` = t2.`user_id`
            Where t1.`user_roles` = 1 and t1.`isLocked` = 0 
            GROUP BY t1.`id`
            ORDER BY t1.`id` ASC ";
            $title_date = Date('W').'weeks';
        }
        $delimiter = ",";
        $filename = "report_".$title_date.".csv";
        $f = fopen('php://memory', 'w');
        $fields = array('No', 'Name', 'Mobile Number', 'Total Times');
        fputcsv($f, $fields, $delimiter);
        $user = mysqli_query($conn, $query);
        $t_arr = array();
        $format = '%02d:%02d:%02d';
        while($row=mysqli_fetch_assoc($user)){
            $temp = $row['total_date'];
            if ($temp != NULL) {
                $t_hours = floor($temp/3600);
                $t_min = floor(($temp-$t_hours*3600)/60);
                $t_second = $temp - $t_hours*3600 - $t_min*60;
                $t_hours = floor($temp/3600);
                $t_arr[$row['id']] = sprintf($format, $t_hours, $t_min, $t_second);
            } else {
                $t_arr[$row['id']] = '';
            }
        }

        $res = mysqli_query($conn, $query);
        $i=1;
        while($row=mysqli_fetch_assoc($res)){
            $lineData = array($i, $row['name'], $row['mobile_number'], $t_arr[$row['id']]);
            fputcsv($f, $lineData, $delimiter);
        }
        fseek($f, 0);

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="'.$filename.'";');

        fpassthru($f);
    }
}
?>