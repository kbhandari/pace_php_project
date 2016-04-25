<?php
/**
 * Created by PhpStorm.
 * User: kbhandari
 * Date: 24/04/16
 * Time: 2:32 PM
 */



require_once("config.php");

require_once("header.php");

$category = array();
$category[] = "Housing";
$category[] = "Books";
$category[] = "Personal Stuff";
$category[] = "Barter";

$subCategory = array();
$subCategory['Housing'][] = "for sale";
$subCategory['Housing'][] = "for rent";
$subCategory['Books'][] = "for sale";
$subCategory['Books'][] = "for rent";
$subCategory['Personal Stuff'][] = "electronics";
$subCategory['Personal Stuff'][] = "furniture";
$subCategory['Personal Stuff'][] = "vehicle";
$subCategory['Personal Stuff'][] = "other";
$subCategory['Barter'][] = "electronics";
$subCategory['Barter'][] = "furniture";
$subCategory['Barter'][] = "vehicle";
$subCategory['Barter'][] = "other";



if(!isUserLoggedIn()) {
    header("Location: index.php"); die();
}

if(!empty($_POST)) {
    $category = $_POST['category'][0];
    $subCategory = $_POST['subCategory'][0];
    if ($category == 'Housing') {
        if ($subCategory == 'for sale') {
            header("Location: housingForSale.php"); die();
        } elseif ($subCategory == 'for rent') {
            header("Location: housingForRent.php"); die();
        }
    } elseif ($category == 'Books') {
        if ($subCategory == 'for sale') {
            header("Location: booksForSale.php"); die();
        } elseif ($subCategory == 'for rent') {
            header("Location: booksForRent.php"); die();
        }
    } elseif ($category == 'Personal Stuff') {
        if ($subCategory == 'electronics') {
            header("Location: personalForElec.php"); die();
        } elseif ($subCategory == 'furniture') {
            header("Location: personalForFurn.php"); die();
        } elseif ($subCategory == 'vehicle') {
            header("Location: personalForVehicle.php"); die();
        } elseif ($subCategory == 'other') {
            header("Location: personalForOther.php"); die();
        }
    } elseif ($category == 'Barter') {
        if ($subCategory == 'electronics') {
            header("Location: barterForElec.php"); die();
        } elseif ($subCategory == 'furniture') {
            header("Location: barterForFurn.php"); die();
        } elseif ($subCategory == 'vehicle') {
            header("Location: barterForVehicle.php"); die();
        } elseif ($subCategory == 'other') {
            header("Location: barterForOther.php"); die();
        }
    }
}

echo "
<body>
<div id='wrapper'>

    <div id='content'>
        <h2>My Account</h2>
        <div id='left-nav'>";

            include("left-nav.php");
echo "</div>";

?>

<form name='postAd' action='<?php $_SERVER['PHP_SELF'] ?>' method='post'>
<div class="postAd1">
    <select id="s1" name="category[]">
        <?php foreach($category as $sa) { ?>
            <option value="<?php echo $sa; ?>"><?php echo $sa; ?></option>
        <?php } ?>
    </select>
    <select id="s2" name="subCategory[]">
    </select>
    <p><input type='submit' value='Submit' class='submit' /></p>
</div>
</form>

<script type="text/javascript">
    var s1= document.getElementById("s1");
    var s2 = document.getElementById("s2");
    onchange(); //Change options after page load
    s1.onchange = onchange; // change options when s1 is changed

    function onchange() {
        <?php foreach ($category as $sa) {?>
        if (s1.value == '<?php echo $sa; ?>') {
            option_html = "";
            <?php if (isset($subCategory[$sa])) { ?> // Make sure position is exist
            <?php foreach ($subCategory[$sa] as $value) { ?>
            option_html += "<option><?php echo $value; ?></option>";
            <?php } ?>
            <?php } ?>
            s2.innerHTML = option_html;
        }
        <?php } ?>
    }
</script>