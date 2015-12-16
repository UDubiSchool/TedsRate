<?php 
$num_rec_per_page=5;

mysql_connect('tedsrate.ovid.u.washington.edu', 'root', 'dongh3d3long');
mysql_select_db('artifactRating2');
if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; }; 
$start_from = ($page-1) * $num_rec_per_page; 
$sql = "SELECT * FROM userProfile LIMIT $start_from, $num_rec_per_page"; 
$rs_result = mysql_query ($sql); //run the query
?> 
<table>
<tr><td>UserID</td><td>EmailID</td></tr>
<?php 
while ($row = mysql_fetch_assoc($rs_result)) { 
?> 
            <tr>
            <td><?php echo $row['userID']; ?></td>
            <td><?php echo $row['email']; ?></td>            
            </tr>
<?php 
}; 
?> 
</table>
<?php 
$sql = "SELECT * FROM userProfile"; 
$rs_result = mysql_query($sql); //run the query
$total_records = mysql_num_rows($rs_result);  //count number of records
$total_pages = ceil($total_records / $num_rec_per_page); 

echo "<a href='pagination_sample.php?page=1'>".'|<'."</a> "; // Goto 1st page  

for ($i=1; $i<=$total_pages; $i++) { 
            echo "<a href='pagination_sample.php?page=".$i."'>".$i."</a> "; 
}; 
echo "<a href='pagination_sample.php?page=$total_pages'>".'>|'."</a> "; // Goto last page
?>
