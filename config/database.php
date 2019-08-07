<?php
  $query = 'CREATE TABLE IF NOT EXISTS user(
           id INT AUTO_INCREMENT PRIMARY KEY,
           username VARCHAR(60) NOT NULL,
           password VARCHAR(150) NOT NULL,
           first_name VARCHAR(60) NOT NULL,
           last_name VARCHAR(60) NOT NULL,
           image_name VARCHAR(250) NOT NULL,
           bio text NOT NULL,
           dob text NOT NULL,
           acc_type VARCHAR(60) NOT NULL
       )';
 if(!mysqli_query($con,$query)){
 	die('connection error');
 }

 $query = 'CREATE TABLE IF NOT EXISTS drops(
           id INT AUTO_INCREMENT PRIMARY KEY,
           user_id INT NOT NULL,
           message VARCHAR(160) NOT NULL,
           date_create VARCHAR(20) NOT NULL,
           redrops INT NOT NULL,
           replies INT NOT NULL,
           is_reply INT NOT NULL,
           is_redrop INT NOT NULL,
           location VARCHAR(100) NOT NULL,
           parent_id INT NOT NULL,
           FOREIGN KEY (user_id) REFERENCES user(id),
           FOREIGN KEY (parent_id) REFERENCES drops(id)
       )';
       if(!mysqli_query($con,$query)){
       	die('connection error');
       }

 $query =  'CREATE TABLE IF NOT EXISTS followers(
           id INT AUTO_INCREMENT PRIMARY KEY,
           user_id INT NOT NULL,
           followers_id INT NOT NULL,
           FOREIGN KEY (user_id) REFERENCES user(id)
       )';  

        if(!mysqli_query($con,$query)){
       	  die('connection error');
       }

 $query =  'CREATE TABLE IF NOT EXISTS following(
           id INT AUTO_INCREMENT PRIMARY KEY,
           user_id INT NOT NULL,
           following_id INT NOT NULL,
           FOREIGN KEY (user_id) REFERENCES user(id)
       )';  

        if(!mysqli_query($con,$query)){
       	die('connection error');
       } 
 $query = 'CREATE TABLE IF NOT EXISTS files(
           id INT AUTO_INCREMENT PRIMARY KEY,
           drop_id INT NOT NULL,
           name VARCHAR(60) NOT NULL,
           type VARCHAR(10) NOT NULL,
           FOREIGN KEY (drop_id) REFERENCES drops(id)
       )';
       if(!mysqli_query($con,$query)){
        die('connection error');
       }
$query = 'CREATE TABLE IF NOT EXISTS activity(
           id INT AUTO_INCREMENT PRIMARY KEY,
           user_id INT NOT NULL,
           message VARCHAR(120) NOT NULL,
           user_id2 INT NOT NULL,
           status INT NOT NULL
         )';

          if(!mysqli_query($con,$query)){
        die('connection error');
       }
     
   $query = 'CREATE TABLE IF NOT EXISTS hashtags(
              id INT AUTO_INCREMENT PRIMARY KEY,
              hashtag_name VARCHAR(50) NOT NULL,
              drops INT NOT NULL DEFAULT 1,
              UNIQUE (hashtag_name)
            )';
  mysqli_query($con,$query);
?>