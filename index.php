<script>
	/* jshint ignore:start */
	<?php
		/* ======================================= */
		/* Create JavaScript object of franchises. */
		/* ======================================= */
		// Set variables.
		$js_data_object_name = 'franchisesObj';
		// Set the post query arguments.
		$post_query_args = array(
			'post_type' => 'franchises',
			'posts_per_page' => -1
		);
		// Call the WordPress post query.
		$post_query = new WP_Query($post_query_args);
		// Adds a field property and value to a JavaScript object.
		function acf_field_to_js($parent_object, $field_name, $field_value){
			if(gettype($field_value) === 'array'){
				// Build the parent object identifier.
				$array_parent_object = $parent_object . '["' . $field_name . '"]';
				// Create empty object for fields and values to be placed into.
				echo $array_parent_object . ' = {};' . "\n";
				// For each field in array.
				foreach($field_value as $array_field_name => $array_field_value){
					// Recursive function call.
					acf_field_to_js($array_parent_object, $array_field_name, $array_field_value);
				}
			}
			else{
				// Create a line of JavaScript to add the data to the object.
				echo $parent_object . '["' . $field_name . '"] = "' . htmlspecialchars($field_value) . '";' . "\n";
			}
		}
		// Initialize the data object.
		echo 'var ' . $js_data_object_name . ' = {};' . "\n";
		// If the post query returned any posts.
		if($post_query->have_posts()){
			// Loop through each queried post.
			while($post_query->have_posts()){
				// Set the current post in the loop.
				$post_query->the_post();
				// Build the current post object identifier.
				$js_post_object =  $js_data_object_name . '[' . get_the_ID() . ']';
				// Create the post object within the main data object.
				echo $js_post_object . ' = {};' . "\n";
				// Set the current post object id.
				echo $js_post_object . '["id"] = "' . get_the_ID() . '";' . "\n";
				// If the post has a title.
				if(get_the_title()){
					// Set the current post object title.
					echo $js_post_object . '["title"] = "' . htmlspecialchars(get_the_title()) . '";' . "\n";
				}
				// Get all advanced custom fields from the post.
				$acf_fields = get_fields();
				// For each field in array.
				foreach($acf_fields as $acf_field => $acf_value){
					// Add the field property and value to the JavaScript object.
					acf_field_to_js($js_post_object, $acf_field, $acf_value);
				}
			}
		}
	?>
	/* jshint ignore:end */
</script>