<?php
session_start();
require "library.php";
require "member.php";
// get library member account
$User = $_SESSION['Username'] ;	
			
	if(!empty($_SESSION['Username'])) { 		
			
	// borrow a book
	for($i = 0; $i < count($isbn); $i++){
	// add the ISBN, username and the date to the borrow table.
	$query = "INSERT INTO borrow VALUES ('$isbn', '$username', '$date')";
	mysql_query($query);
	// mark the book as borrowed in the book table.
	$query = "UPDATE books SET reserve='Y' WHERE ISBN='$isbn'";
	mysql_query($query);
	}
	
	else 
	{
	
	// reserve a book
	for($i = 0; $i < count($isbn); $i++){
	// add the ISBN, username and the date to the reservation table.
	$query = "INSERT INTO reservation VALUES ('$isbn', '$username', '$date')";
	mysql_query($query);
	// mark the book as reserved in the book table.
	$query = "UPDATE books SET reserve='Y' WHERE ISBN='$isbn'";
	mysql_query($query);
	}
	
	
} 

// If there is POST values create the specified sql query.
		// Unreserve books.
		if( isset( $_POST['unreserve'] ) ){
		
			$isbn = $_POST['unreserve'];
			$username = $User;
			
				for($i = 0; $i < count($isbn); $i++){
					// mark book as not reserved in the book table
					$query = "UPDATE books SET reserve='N' WHERE ISBN='$isbn[$i]'";
					mysql_query($query);
					// remove book from the reservation table
					$query = "DELETE FROM reservation WHERE ISBN='$isbn[$i]'";
					mysql_query($query);
				}
				
				// Show all the users reserved books.
				$query = "SELECT * FROM books b LEFT JOIN reservation r ON b.ISBN = r.ISBN WHERE r.username='$username'";
				$result = mysql_query($query);
				while($row = mysql_fetch_array($result)){
					echo '<br><table border=1>'."\n";
					echo "<tr><td>";
					echo "<li><p>BookName: ". $row['title'] ."<p>Author: ". $row['author'] . "<p>Year Released: " . $row['year'] . "<p><br><input type=\"checkbox\" name=\"unreserve[]\" value=\"" . $row['ISBN'] . "\" checked/><span>Un-Reserve</span><p></li>";
					echo '</table><p>';
				}
			
		}
		
		}else{
			
			// Show all the users reserved books.
			$username = $User;
			$query = "SELECT * FROM books b LEFT JOIN reservation r ON b.ISBN = r.ISBN WHERE r.username='$username'";
			$result = mysql_query($query);
			while($row = mysql_fetch_array($result)){
				echo '<br><table border=1>'."\n";
				echo "<tr><td>";
				echo "<li><p>BookName: ". $row['title'] ."<p>Author: ". $row['author'] . "<p>Year Released: " . $row['year'] . "<p><br><input type=\"checkbox\" name=\"unreserve[]\" value=\"" . $row['ISBN'] . "\" checked/><span>Un-Reserve</span><p></li>";
				echo '</table><p>';
			}
		} // end if post.
		
?>