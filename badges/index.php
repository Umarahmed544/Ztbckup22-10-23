<?php
// header("Location: install.php?shop=" . $_GET['shop']);
// exit();?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css" >
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css">
</head>
<body>
    <div class="container">
    <?php
    include_once("inc/functions.php");
    include_once("inc/mysql_connect.php");
    include_once("header.php");
    include_once("Dashboard.php");
    
    ?>
        

    <!-- <select id="SelExample" >
            <option value="0">Delhi</option>
            <option value="1">Hyderabad</option>
            <option value="2">Vizag</option>
            <option value="3">Kochi</option>
            <option value="4">Anantapur</option>
            <option value="5">Dharmavaram</option>
            <option value="6">Bengaluru</option>
            <option value="7">Lucknow</option>
            <option value="8">Madurai</option>
          </select>

<button id="but_read">Selected Value</button>

<span id="result"></span> -->

    </div>

    <script src="./assets/js/jquery-3.4.1.min.js" ></script>
    <script src="./assets/js/popper.min.js"></script>
    <script src="./assets/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        // function filterFunction() {
        // var input, filter, ul, li, a, i;
        // input = document.getElementById("someInput");
        // filter = input.value.toUpperCase();
        // div = document.getElementById("dropDiv");
        // a = div.getElementsByTagName("option");
        // for (i = 0; i < a.length; i++) {
        // if (a[i].innerHTML.toUpperCase().indexOf(filter) > -1) {
        // a[i].style.display = "";
        // } else {
        // a[i].style.display = "none";
        // }
        // }
        // }




        $(document).ready(function(){
//  productCollection
            // Initialize select2
            $("#productCollection").select2();
            $("#productCollection").select2("val", "4");
            $('#productCollection option:selected').text('Vizag');
            // Read selected option
            // $('#but_read').click(function(){
            // var username = $('#SelExample option:selected').text();
            // var userid = $('#SelExample').val();

            // $('#result').text("id : " + userid + ", name : " + username);
            // });
        });
    </script>
    
</body>
</html>

