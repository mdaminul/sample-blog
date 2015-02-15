		</div>
		<div id="sidebar">
		    <div id="search">
				<input type="text" value="Search">
				<input class="search_submit" type="image" src="img/go.gif" alt="" />
			</div>
			<div class="list">
				<img src="img/title1.gif" alt="" width="186" height="36" />
				<ul>
				
					<?php
					
						$statement = $db->prepare("select * from tbl_cat order by cat_name ASC");
						$statement->execute();
						$result = $statement->fetchAll(PDO::FETCH_ASSOC);
						foreach($result as $row)
							{
								?>
								<li><a href="category.php?id=<?php echo $row['cat_id'] ;?>"><?php echo $row['cat_name'];?></a></li>
								<?php
							}
					?>
					
				</ul>
				<img src="img/title2.gif" alt="" width="180" height="34" />
				<ul>
				
					<?php
						$i=0;
						$statement = $db->prepare("select distinct(post_date) from tbl_post order by post_date ASC");
						$statement->execute();
						$result = $statement->fetchAll(PDO::FETCH_ASSOC);
						foreach($result as $row)
							{
								
								$year_month = substr($row['post_date'],3,7);
								$year_month_arr[$i] = $year_month;
								$i++;
								
							}
							$final_arr = array_unique($year_month_arr);
							$year_month_string = implode(",",$final_arr);
							$year_mon_arr = explode(",",$year_month_string);
							$year_mon_arr_len = count($year_mon_arr);
							for($j=0;$j<$year_mon_arr_len;$j++)
								{
									$month = substr($year_mon_arr[$j],0,2);
									$year = substr($year_mon_arr[$j],3,4);
									
										if($month =='01') {$month = 'January';}
										if($month =='02') {$month = 'February';}
										if($month =='03') {$month = 'March';}
										if($month =='04') {$month = 'April';}
										if($month =='05') {$month = 'May';}
										if($month =='06') {$month = 'June';}
										if($month =='07') {$month = 'July';}
										if($month =='08') {$month = 'Augest';}
										if($month =='09') {$month = 'September';}
										if($month =='10') {$month = 'October';}
										if($month =='11') {$month = 'November';}
										if($month =='12') {$month = 'December';}
										?>
										<li><a href="archives.php?date=<?php echo $year_mon_arr[$j];?>"><?php echo $month.' '.$year ;?></a></li>
										<?php
								}
					?>
					
				</ul>
			</div>
		</div>
	</div>
	<div id="footer">
		<p>
			<?php
				$id = 1;
				$statement = $db->prepare("select * from tbl_footer where id=?");
				$statement->execute(array($id));
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);
				foreach($result as $row)
					{
						echo $row['footer_text'];
					}
			?>
		</p>
	</div>
</body>
</html>
