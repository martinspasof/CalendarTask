<!DOCTYPE html>
<html>
<body>

<div style="margin: 10px;">
  <form action="" method="post">
    Months:<br>
    <select name="month" id="month">
      <?php
      for ($m=1; $m<=12; $m++) {
        $selected = isset($_POST['month']) && ($_POST['month']==$m) ? 'selected' : '';
        $month = date('F', mktime(0,0,0,$m, 1, date('Y'))); ?>
        <option <?= $selected ?> value="<?=$m?>"><?= $month ?></option>
      <?php } ?>
    </select>
    <br>
    Year:<br>
    <input type="text" name="year" value="<?= isset($_POST['year']) && !empty($_POST['year']) ? $_POST['year'] : '';  ?>" placeholder="enter a year">
    <br><br>
    <input type="submit" name="sbm" value="show dates">
  </form>
</div>
<?php if(isset($_POST['sbm'])){
  $month = $_POST['month'];
  $year = $_POST['year'];
  $month_name = date("F", strtotime("$year-$month-01"));

?>

<table cellspacing=0 cellpadding=5 frame='all' rules='all' style='border:#808080 1px solid;margin: 30px;'>
  <caption><?= $month_name.' '.$year ?></caption>

  <tr>
    <?php
    $week_days = array(1=>"Mon", 2=>"Tue",	3=>"Wed",	4=>"Thu",	5=>"Fri",	6=>"Sat",	7=>"Sun");
    for($d=1; $d<=7; $d++){?>
        <th><?= $week_days[$d]  ?></th>
        <?php
    }
    ?>
  </tr>

  <?php
  $count_in_search_month=cal_days_in_month(CAL_GREGORIAN,$month,$year);
  $first_day_in_search_month = date("D", strtotime("$year-$month-01"));
  $last_day_in_search_month = date("D", strtotime(date("Y-m-t", strtotime("$year-$month-01"))));


  $previous_month = ($month == '01') ? 12 : ($month-1);
  $previous_year = ($month == '01') ? ($year - 1) : $year;
  $number_day_from_week = array_search ($first_day_in_search_month, $week_days);
  $last_date_in_previous_month = date("t", strtotime(date("Y-m-t", strtotime("$previous_year-$previous_month-01"))));
  $start_date_from_previous_month = $last_date_in_previous_month - ($number_day_from_week-2);


  $tr_start = '<tr>';
  $tr_end = '</tr>';
  $style_color = "style='color:#949494'";
  $flag_previous_month = false;
  for($d=1; $d<=$start_date_from_previous_month; $d++) {
    if ($number_day_from_week > $d) {
      echo $flag_previous_month === FALSE ? $tr_start : '';
      $flag_previous_month = TRUE;
      ?>
      <td <?=$style_color?> ><?= $start_date_from_previous_month++; ?></td>
      <?php
      continue;
    }
  }
  $get_day_name = '';
  for($d=1; $d<=$count_in_search_month; $d++){
    $time=mktime(12, 0, 0, $month, $d, $year);
    if (date('m', $time)==$month){
      $get_day_name = date('D', $time);
      ?>
      <?php if((date('D', $time) == $first_day_in_search_month) ) {
        echo $flag_previous_month === true ? '' : $tr_start;
       } ?>
            <td><?= date('d', $time) ?></td>
      <?php
      $get_number_day_from_week = array_search ($get_day_name, $week_days);
      if(date('D', $time) == 'Sun'){
          echo $d==$count_in_search_month && $get_number_day_from_week < 7 ? $tr_start.$tr_end : $tr_end;
      } ?>
      <?php
    }

  }

  $get_number_days_from_next_month = 7 - $get_number_day_from_week;
  if($get_number_day_from_week < 7){
    for($d=1; $d<=$get_number_days_from_next_month; $d++){
       echo "<td $style_color >".'0'.$d."</td>";
    }
    echo $tr_end;

  }
  ?>
</table>

<?php } ?>

</body>
</html>