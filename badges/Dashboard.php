<!-- <form class="m-5 p-5 card shadow bg-body rounded" action="save.php"  id="save_badges" method="post">
    <small><a href="badges-page.php" class="float-right" >Assigned Badges</a></small>
<center><h1>Add New Badges</h1></center>

<div class="form-row d-flex">
    <label for="badges" class="form-label offset-3 col-3 p-2">Title -</label>
    <input type="text" class="form-control col-3" placeholder="badges" name="badges_title">
</div>
<div class="form-row ">
    <label for="Collections" class="form-label offset-3 col-3 p-2">Select Collections-</label>

    <div class="col">
        <button class="btn btn-dark btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown primary</button>
        <div class="dropdown-menu p-0" id="dropDiv">
            <input id="someInput" class="form-control" type="text" placeholder="Search" aria-label="Search" onkeyup='filterFunction()' >
            <select class="custom-select" id="productCollection" name="productCollection" multiple><?php
			// $custom_collections = shopify_call($token, $shop_url,"/admin/api/2022-10/custom_collections.json", array(), 'GET');
			// $custom_collections = json_decode($custom_collections['response'], JSON_PRETTY_PRINT);

			// foreach($custom_collections as $custom_collection){
			// 	foreach($custom_collection as $key => $value){
			// 		?>
			// 		<option value="<?php echo $value['id'];?>"><?php echo $value['title'];?></option>
			// 		<?php
			// 	}
			// }
			// $smart_collections = shopify_call($token, $shop_url,"/admin/api/2022-10/smart_collections.json", array(), 'GET');
			// $smart_collections = json_decode($smart_collections['response'], JSON_PRETTY_PRINT);

			// foreach($smart_collections as $smart_collection){
			// 	foreach($smart_collection as $key => $value){
					?>
					<option value="<?php echo $value['id'];?>"><?php echo $value['title'];?></option>
					<?php
			// 	}
			// }
			?><select>
        </div>
    </div>
</div>

<div class="form-row ">
    <label for="badges_link" class="form-label offset-3 col-3 p-2">Link -</label>
    <input type="text" class="form-control col-3" placeholder="badges link" name="badges_link">
</div>


<center><button type="submit" class="btn btn-success btn-sm " style="width:50px " name="save_badges">Save</button></center>
</form> -->


    <center style="
    padding: 10px;"><h3>Add New Badges</h3></center>
   <small><a href="badges-page.php" class="float-right" >Assigned Badges</a></small>
    <form  action="save.php"  id="save_badges" method="post">
    
        <div class="form-group">
            <label for="badges" class="form-label">Title</label>
            <input type="text" class="form-control" placeholder="badges" name="badges_title">
        </div>
        <div class="form-group">
            <label for="badges_link" class="form-label">Link </label>
            <input type="text" class="form-control" placeholder="badges link" name="badges_link">
        </div>
        <div class="form-group" class="a">
            <label for="Collections" class="form-label">Select Collections</label>
            <!-- <input id="someInput" class="form-control" type="text" placeholder="Search" aria-label="Search" onkeyup='filterFunction()' > -->
            <select class="form-control" id="productCollection" name="productCollection" >
                <option>asd </option>
                <!-- <option><div><input id="someInput" class="form-control" type="text" placeholder="Search" aria-label="Search" onkeyup='filterFunction()' ></div> </option> -->
                <?php
                $custom_collections = shopify_call($token, $shop_url,"/admin/api/2022-10/custom_collections.json", array(), 'GET');
                $custom_collections = json_decode($custom_collections['response'], JSON_PRETTY_PRINT);

                foreach($custom_collections as $custom_collection){
                    foreach($custom_collection as $key => $value){
                        ?>
                        <option value="<?php echo $value['id'];?>"><?php echo $value['title'];?></option>
                        <?php
                    }
                }
                $smart_collections = shopify_call($token, $shop_url,"/admin/api/2022-10/smart_collections.json", array(), 'GET');
                $smart_collections = json_decode($smart_collections['response'], JSON_PRETTY_PRINT);

                foreach($smart_collections as $smart_collection){
                    foreach($smart_collection as $key => $value){
                        ?>
                        <option value="<?php echo $value['id'];?>"><?php echo $value['title'];?></option>
                        <?php
                    }
                }
            ?><select>
        </div>

        <button type="submit" class="btn btn-success" name="save_badges">Save</button>
    </form>



