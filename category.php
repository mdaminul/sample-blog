		<?php include('config.php');?>
		
		<?php 
			if(!isset($_REQUEST['id'])) {
				header("location: index.php");
			}
			else {
				$cat_id = $_REQUEST['id'];
			}
		
		?>
		
		<?php include('header.php') ;?>
			
			
			<?php
			
			/* ===================== Pagination Code Starts ================== */
			$adjacents = 2;
			
			$statement = $db->prepare("SELECT * FROM tbl_post where post_cat=? ORDER BY post_id DESC");
			$statement->execute(array($cat_id));
			$total_pages = $statement->rowCount();
							
			
			$targetpage = $_SERVER['PHP_SELF'];   //your file name  (the name of this file)
			$limit = 2;                                 //how many items to show per page
			$page = @$_GET['page'];
			if($page) 
				$start = ($page - 1) * $limit;          //first item to display on this page
			else
				$start = 0;
			
						
			$statement = $db->prepare("SELECT * FROM tbl_post where post_cat=? ORDER BY post_id DESC LIMIT $start, $limit");
			$statement->execute(array($cat_id));
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			
			
			if ($page == 0) $page = 1;                  //if no page var is given, default to 1.
			$prev = $page - 1;                          //previous page is page - 1
			$next = $page + 1;                          //next page is page + 1
			$lastpage = ceil($total_pages/$limit);      //lastpage is = total pages / items per page, rounded up.
			$lpm1 = $lastpage - 1;   
			$pagination = "";
			if($lastpage > 1)
			{   
				$pagination .= "<div class=\"pagination\">";
				if ($page > 1) 
					$pagination.= "<a href=\"$targetpage?id=$cat_id&page=$prev\">&#171; previous</a>";
				else
					$pagination.= "<span class=\"disabled\">&#171; previous</span>";    
				if ($lastpage < 7 + ($adjacents * 2))   //not enough pages to bother breaking it up
				{   
					for ($counter = 1; $counter <= $lastpage; $counter++)
					{
						if ($counter == $page)
							$pagination.= "<span class=\"current\">$counter</span>";
						else
							$pagination.= "<a href=\"$targetpage?id=$cat_id&page=$counter\">$counter</a>";                 
					}
				}
				elseif($lastpage > 5 + ($adjacents * 2))    //enough pages to hide some
				{
					if($page < 1 + ($adjacents * 2))        
					{
						for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
						{
							if ($counter == $page)
								$pagination.= "<span class=\"current\">$counter</span>";
							else
								$pagination.= "<a href=\"$targetpage?id=$cat_id&page=$counter\">$counter</a>";                 
						}
						$pagination.= "...";
						$pagination.= "<a href=\"$targetpage?id=$cat_id&page=$lpm1\">$lpm1</a>";
						$pagination.= "<a href=\"$targetpage?id=$cat_id&page=$lastpage\">$lastpage</a>";       
					}
					elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
					{
						$pagination.= "<a href=\"$targetpage?id=$cat_id&page=1\">1</a>";
						$pagination.= "<a href=\"$targetpage?id=$cat_id&page=2\">2</a>";
						$pagination.= "...";
						for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
						{
							if ($counter == $page)
								$pagination.= "<span class=\"current\">$counter</span>";
							else
								$pagination.= "<a href=\"$targetpage?id=$cat_id&page=$counter\">$counter</a>";                 
						}
						$pagination.= "...";
						$pagination.= "<a href=\"$targetpage?id=$cat_id&page=$lpm1\">$lpm1</a>";
						$pagination.= "<a href=\"$targetpage?id=$cat_id&page=$lastpage\">$lastpage</a>";       
					}
					else
					{
						$pagination.= "<a href=\"$targetpage?id=$cat_id&page=1\">1</a>";
						$pagination.= "<a href=\"$targetpage?id=$cat_id&page=2\">2</a>";
						$pagination.= "...";
						for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
						{
							if ($counter == $page)
								$pagination.= "<span class=\"current\">$counter</span>";
							else
								$pagination.= "<a href=\"$targetpage?id=$cat_id&page=$counter\">$counter</a>";                 
						}
					}
				}
				if ($page < $counter - 1) 
					$pagination.= "<a href=\"$targetpage?id=$cat_id&page=$next\">next &#187;</a>";
				else
					$pagination.= "<span class=\"disabled\">next &#187;</span>";
				$pagination.= "</div>\n";       
			}
			/* ===================== Pagination Code Ends ================== */	
				foreach($result as $row)
					{
						?>
						<div class="post">
							<h2><?php echo $row['post_title'];?></h2>
							<div>
								<span class="date">
									<?php
										$post_date = $row['post_date'];
										$day = substr($post_date,0,2);
										$month = substr($post_date,3,2);
										$year = substr($post_date,6,4);
										if($month =='01') {$month = 'Jan';}
										if($month =='02') {$month = 'Feb';}
										if($month =='03') {$month = 'Mar';}
										if($month =='04') {$month = 'Apr';}
										if($month =='05') {$month = 'May';}
										if($month =='06') {$month = 'Jun';}
										if($month =='07') {$month = 'Jul';}
										if($month =='08') {$month = 'Aug';}
										if($month =='09') {$month = 'Sep';}
										if($month =='10') {$month = 'Oct';}
										if($month =='11') {$month = 'Nov';}
										if($month =='12') {$month = 'Dec';}
										
										echo $month." ".$day." ".$year;
									?>
								</span>
								<span class="categories">
								Tags:&nbsp;
									<?php 
										$arr = explode(",",$row['post_tag']);
										$arr_lenght = count($arr);
										$k=0;
										for($j=0;$j<$arr_lenght;$j++)
											{
												$statement1 = $db->prepare("select * from tbl_tag where tag_id=?");
												$statement1->execute(array($arr[$j]));
												$result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
												foreach($result1 as $row1)
													{
														$arr1[$k] = $row1['tag_name'];
													}
												$k++;
											}
											$post_tags = implode(", ",$arr1);
														
										echo $post_tags;

									?>
								</span>
							</div>
							<div class="description">
								<p><img src="uploads/<?php echo $row['post_image'] ;?>" alt="" width="240" height="180" />
								<?php
									//for limit words
									$post_limits = explode(" ",$row['post_des']);
									$post_limits_words = implode(" ",array_splice($post_limits,0,200));
									//
									echo $post_limits_words;
								?> 
								</p>
							</div>
							<p class="comments">Comments - <a href="#">17</a>   <span>|</span>   <a href="index2.php?id=<?php echo $row['post_id'];?>">Continue Reading</a></p>
						</div>
						<?php
					}
			?>
	<div class="pagination">
		<?php 
			echo $pagination; 
		?>
	</div>
		
<?php include('footer.php') ;?>