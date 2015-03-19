// <?php

// require_once '../../inc/init.php';

// $ethnicity = array(
//             "Asian",
//             "Native American",
//             "Hispanic / Latin",
//             "Middle Eastern",
//             "Indian",
//             "White Master Race",
//             "Black / nigga",
//             "Pacific Islander (just fucking Pacific)",
//             "Other",
//             "Other2, for those not in Other");

// $smokes = array(
//             "Yes",
//             "Sometimes",
//             "When drinking",
//             "Trying to quit",
//             "No");

// $drinks = array(
//             "Very often",
//             "Often",
//             "Socially",
//             "Rarely",
//             "Desperately",
//             "Not at all");

// $drugs = array(
//             "Often",
//             "Sometimes",
//             "Never");

// $parties = array(
//             "Often",
//             "Sometimes",
//             "Never");

// $studies = array(
//             "Very Often",
//             "Often",
//             "Sometimes",
//             "Rarely",
//             "Never");
// $degree = array(
//             "Accounting, Business & Finance",
//             "Agriculture and Horticulture",
//             "Archaeology",
//             "Architecture, Building & Planning",
//             "Art and design",
//             "Biology",
//             "Chemistry",
//             "Communication and Media",
//             "Computing & IT",
//             "Dentistry",
//             "Earth Sciences",
//             "Economics",
//             "Education",
//             "Engineering",
//             "English Language",
//             "English Literature",
//             "Fashion and textiles",
//             "Geography",
//             "Health and Medicine",
//             "History",
//             "Hospitality & Catering",
//             "Languages",
//             "Law",
//             "Management",
//             "Marketing",
//             "Mathematics",
//             "Music",
//             "Nursing",
//             "Pharmacology",
//             "Philosophy",
//             "Physics",
//             "Politics",
//             "Psychology and Counselling",
//             "Social Work",
//             "Sociology",
//             "Sports & Leisure",
//             "Theatre & Dramatic Arts",
//             "Theology & Religion",
//             "Travel and Tourism",
//             "Veterinary Medicine");

// $offspring = array(
//             "Has a kid",
//             "Has kids",
//             "Does not have kids",
//             "Eats kids");

// $pets = array(
//             "None",
//             "Dog(s)",
//             "Cat(s)",
//             "Arachnide(s)",
//             "Snake(s)",
//             "Bird(s)",
//             "Rodent(s)",
//             "Little brother(s)"
//             );

// $signs = array(
//             "Leo",
//             "Aquarius",
//             "Cancer",
//             "Taurus",
//             "Scorpio",
//             "Virgo",
//             "Pisces",
//             "Aries",
//             "Gemini",
//             "Libra",
//             "Sagittarius",
//             "Capricorn");

// $arr = array("map_sign" => $signs, "map_pet"=>$pets, "map_offspring" => $offspring, "map_degree" => $degree, "map_studies" => $studies, "map_parties" => $parties, "map_drugs" => $drugs, "map_drinks" => $drinks, "map_tobacco" => $smokes, "map_ethnicity" => $ethnicity);

// foreach ($arr as $colName => $values)
// {
//       foreach ($values as $key => $value) 
//       {
//             $key++;
//             $stmt = $con->prepare("UPDATE rfiltersmap SET $colName = '$value' WHERE filter_value = $key");
//             if(!$stmt->execute())
//             {
//                   echo "FUCK ";
//                   echo $colName . " $key <br>";
//             }      
//       }
// }
