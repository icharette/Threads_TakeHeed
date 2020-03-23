<?php
//check if there has been something posted to the server to be processed
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
// need to process
 $artist = $_POST['a_name'];


 try {
        /**************************************
        * Create databases and                *
        * open connections                    *
        **************************************/

        // Create (connect to) SQLite database in file
        $file_db = new PDO('sqlite:../db/threads.db');
        // Set errormode to exceptions
        /* .. */
        $file_db->setAttribute(PDO::ATTR_ERRMODE,
                                PDO::ERRMODE_EXCEPTION);

      /*The data from the text box is potentially unsafe; 'tainted'. Use the quote() - puts quotes around things..
      It escapes a string for use as a query parameter.
      This is common practice to avoid malicious sql injection attacks.
      PDO::quote() places quotes around the input string (if required)
      and escapes special characters within the input string, using a quoting style appropriate to the underlying driver. */
      $artist_es =$file_db->quote($artist);

      // the file name with correct path
      // $imageWithPath= "images/".$fname;
      // $rnNum = rand(5,100);

//need to search if entry already exists here !!!!!!!
      $queryInsert ="INSERT INTO artCollection(artist)VALUES ($artist_es)";
      $file_db->exec($queryInsert);

      }

      catch(PDOException $e) {
        // Print PDOException message
        echo $e->getMessage();
      }
 if($_FILES)
  {
    //echo "file name: ".$_FILES['filename']['name'] . "<br />";
    //echo "path to file uploaded: ".$_FILES['filename']['tmp_name']. "<br />";
   $fname = $_FILES['filename']['name'];
   // move_uploaded_file($_FILES['filename']['tmp_name'], "images/".$fname);
    echo "done";

    exit;

  }//FILES
}//POST
?>

<!DOCTYPE html>
<html>
<head>
<title>Threads Insert</title>
<!-- get JQUERY -->
  <script src = "libs/jquery-3.4.1.js"></script>
<!--set some style properties::: -->
<link rel="stylesheet" type="text/css" href="css/galleryStyle.css">
</head>
<body>
  <!-- NEW for the result -->
<div id = "result"></div>

<div class= "formContainer">
<!--form done using more current tags... -->
<form id="insertGallery" action="" enctype ="multipart/form-data">
<!-- group the related elements in a form -->
<h3> SUBMIT AN ART WORK :::</h3>
<fieldset>
<p><label>Artist:</label><input type="text" size="24" maxlength = "40" name = "a_name" required></p>

<p class = "sub"><input type = "submit" name = "submit" value = "submit my info" id ="buttonS" /></p>
 </fieldset>
</form>
</div>
<script>
// here we put our JQUERY
$(document).ready (function(){
    $("#insertGallery").submit(function(event) {
       //stop submit the form, we will post it manually. PREVENT THE DEFAULT behaviour ...
      event.preventDefault();
     console.log("button clicked");
     let form = $('#insertGallery')[0];
     let data = new FormData(form);
    // Display the key/value pairs
    // to access the data in the formData Object ... (not this is ALL TEXT ... )
    // as key -value pairs
    //Object.entries() method in JavaScript returns an array consisting of
    //enumerable property [key, value] pairs of the object.
    for (let valuePairs of data.entries()) {
    console.log(valuePairs[0]+ ', ' + valuePairs[1]);
  }

  $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: "AJaxInsertForm.php",
            data: data,
            processData: false,//prevents from converting into a query string
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function (response) {
            //reponse is a STRING (not a JavaScript object -> so we need to convert)
            console.log("we had success!");
            console.log(response);
            //use the JSON .parse function to convert the JSON string into a Javascript object
            // let parsedJSON = JSON.parse(response);
            // console.log(parsedJSON);
            // displayResponse(parsedJSON);
           },
           error:function(){
          console.log("error occurred");
        }
      });
$( "#result" ).load( "viewResults.php" );
   });
   // validate and process form here
    function displayResponse(theResult){
      let container = $('<div>').addClass("outer");
      let title = $('<h3>');
      $(title).text("Results from user");
      $(title).appendTo(container);
      let contentContainer = $('<div>').addClass("content");
      for (let property in theResult) {
        console.log(property);
        if(property ==="fileName"){
          let img = $("<img>");
          $(img).attr('src','images/'+theResult[property]);

          $(img).appendTo(contentContainer);
        }
        else{
          let para = $('<p>');
          $(para).text(property+"::" +theResult[property]);
            $(para).appendTo(contentContainer);
        }

      }
      $(contentContainer).appendTo(container);
      $(container).appendTo("#result");
    }
});
</script>
</body>
</html>
