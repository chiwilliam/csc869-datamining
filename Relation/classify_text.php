<?php

	function findAllMatches($vect, $entities){

		// echo "findAllMatches<p/>";

		$allMatches = array();

		$index = 0;
		while($index < count($vect)){

			$match = findMatch($vect, $index, $entities);

			if(count($match) > 0 ){
				// Found a match
				$allMatches[] = $match;
				$index = $match[0] + $match[1] + 1;
			}else{
				// No more matches
				break;
			}

		}

		return $allMatches;
	}


    function findMatch($vect, $startIndex, $entities){

    	// echo "findMatch, " . "$startIndex" . "<p/>";

    	// print_r($entities);
    	// echo "<p/>";


		$found = FALSE;
		for($x = $startIndex; $x < count($vect); ++$x){

			// print("x=$x;<p/>");

			for($y = 0; $y < count($entities); ++$y){

				$modelEntity = $entities[$y];

				// print_r($modelEntity);
				// echo "<p/>";

				for($z = 0; $z < count($modelEntity); ++$z){

					// echo "($z) $modelEntity[$y]<p/>";

					if(empty($modelEntity[$z])){
						// echo 'continue<p/>';
						continue;
					}

					if($x + $z < count($vect)){
						if($vect[$x + $z] != $modelEntity[$z]){
							// echo 'No match I<p/>';
							$found = FALSE;
							break;
						}else{
							// echo 'Assign TRUE<p/>';
							$found = TRUE;
						}
					}else{
						// echo 'End of Line<p/>';
						$found = FALSE;
						break;
					}
				}

				if($found == TRUE){

					// print("Match in $x $z - 1 <p/>");
					return array($x, $z - 1);
				}
			}
		}

		return array();

    }

    require_once ($_SERVER['DOCUMENT_ROOT']."/CSC869Project/Relation/utils.php");
    require_once ($_SERVER['DOCUMENT_ROOT']."/CSC869Project/Relation/Classify.php");


	/*
	$stemmer = new PorterStemmer();

	echo "colorectal=" . $stemmer->Stem("colorectal.");

	echo "lungs=" . $stemmer->Stem("lungs");

	echo "<p/>";
	*/


	///////////////////////
	/// Obtain entities ///
	///////////////////////


    require_once ($_SERVER['DOCUMENT_ROOT']."/CSC869Project/Relation/cache_entity.php");

   	$CANCERS = obtainEntity('cancers');
   	$FOODS   = obtainEntity('foods');

	//////////////////////
	/// Parse abstract ///
	//////////////////////

	$relationshipText = $_POST["abstractText"];

	$abstractText = trim($relationshipText);

	$abstractText = strip_punctuation($abstractText);

	$abstractAsVect  = preg_split ("/\s+/", $abstractText );

	applyStemmer(&$abstractAsVect);


	// print ("Abstract: ");
	// print_r($abstractAsVect);
	// echo "<p/>";


	////////////////////////
	/// Find All matches ///
	////////////////////////


	$diseaseMatches = findAllMatches($abstractAsVect, $CANCERS);
	$foodMatches = findAllMatches($abstractAsVect, $FOODS);

	// print ("foodMatches: ");
	// print_r($foodMatches);
    // echo "<p/>";

	// print ("diseaseMatches: ");
	// print_r($diseaseMatches);
    // echo "<p/>";

	$totalWeight = 0;
	$relationCount = 0;

	////////////////////////////////////
	/// Case: (food + rel + disease) ///
	////////////////////////////////////

	// echo "Case: (food + rel + disease)<p/>";

	$foodIndex    = 0;
	$diseaseIndex = 0;

	while($foodIndex < count($foodMatches)){

		$food    = $foodMatches[$foodIndex];
		while($diseaseIndex < count($diseaseMatches)){

			$disease = $diseaseMatches[$diseaseIndex];
			// disease comes afer the food
			if($food[0] >= $disease[0]){
				++$diseaseIndex;
			}else{
				break;
			}
		}


		if($diseaseIndex >= count($diseaseMatches)){

			// Assume there is a dummy food at the end of the file
			$disease = array(count($abstractAsVect) - 1, 0);

		}

		// Get the nearest food to the diease

		while($foodIndex < count($foodMatches)){

			if($foodIndex + 1 >= count($foodMatches)){

				break;
			}

			if($foodMatches[$foodIndex + 1][0] < $disease[0]){
				++$foodIndex;
				$food = $foodMatches[$foodIndex];
			}else{
				break;
			}
		}

		// Find boundaries of the relation
		$startBoundary = $food[0] + $food[1] + 1;
		$endBoundary = $disease[0] - 1;


		$potentialRelation = array_slice($abstractAsVect, $startBoundary + 1,
		$endBoundary  - ($startBoundary));

		// print ("Potential Rel: ");
		// print_r($potentialRelation);
		// echo "<p/>";

		// print (implode(' ', $potentialRelation));
		// echo "<p/>";

		// include ($_SERVER['DOCUMENT_ROOT']."/CSC869Project/Relation/Classify.php");

		$weight = classify(implode(' ', $potentialRelation));

		// echo "weight = $weight<p/>";

		$totalWeight += $weight;
		++$relationCount;

		++$foodIndex;

	}

	////////////////////////////////////
	/// Case: (disease + rel + food) ///
	////////////////////////////////////

	// echo "(disease + rel + food)<p/>";

	$foodIndex    = 0;
	$diseaseIndex = 0;

	while($diseaseIndex < count($diseaseMatches)){

		$disease = $diseaseMatches[$diseaseIndex];

		// Find the next Food

		while($foodIndex < count($foodMatches)){

			$food = $foodMatches[$foodIndex];
			// food comes afer the disease
			if($disease[0] >= $food[0]){
				++$foodIndex;
			}else{
				break;
			}
		}

		if($foodIndex >= count($foodMatches)){

			// Assume there is a dummy food at the end of the file
			$food = array(count($abstractAsVect) - 1, 0);

		}

		// Get the nearest disease

		while($diseaseIndex < count($diseaseMatches)){

			if($diseaseIndex + 1 >= count($diseaseMatches)){

				break;
			}

			if($diseaseMatches[$diseaseIndex + 1][0] < $food[0]){
				++$diseaseIndex;
				$disease = $diseaseMatches[$diseaseIndex];
			}else{
				break;
			}
		}

		// Find boundaries of the relation
		$startBoundary = $disease[0] + $disease[1] + 1;
		$endBoundary = $food[0] - 1;

		$potentialRelation = array_slice($abstractAsVect, $startBoundary + 1,
		$endBoundary  - ($startBoundary));

		// print ("Potential Rel: ");
		// print_r($potentialRelation);
		// echo "<p/>";

		// print (implode(' ', $potentialRelation));
		// echo "<p/>";

		// include ($_SERVER['DOCUMENT_ROOT']."/CSC869Project/Relation/Classify.php");

		$weight = classify(implode(' ', $potentialRelation));

		// echo "weight = $weight<p/>";

		$totalWeight += $weight;
		++$relationCount;

		++$diseaseIndex;

	}

	// echo "totalWeight mean =" .  ($totalWeight / $relationCount) . "<p/>";


	if( $relationCount == 0){

		$message = "No  relationship";

	}else{

		$mean = ($totalWeight / $relationCount);
		if(abs($mean) < 0.05){
			$message = "No  relationship";
		}else if(abs($mean) < .25){
			$message = "No clonclusive relationship";
		}else if(abs($mean) < .75){
			$message = "" . (($mean > 0)? "positive":"negative") ." relationship";
		}else{
			$message = "Strong " . (($mean > 0)? "positive":"negative") ." relationship";
		}
	}


    include ($_SERVER['DOCUMENT_ROOT']."/CSC869Project/Relation/Relation.php");

	//echo "totalWeight = $totalWeight<p/>";



?>