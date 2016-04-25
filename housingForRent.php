<?php
/**
 * Created by PhpStorm.
 * User: kbhandari
 * Date: 24/04/16
 * Time: 5:41 PM
 */


require_once("config.php");

require_once("header.php");

if(!isUserLoggedIn()) {
    header("Location: index.php"); die();
}

echo "
<body>
<div id='wrapper'>

    <div id='content'>
        <h2>My Account</h2>
        <div id='left-nav'>";

include("left-nav.php");
echo "</div>";

if(!empty($_POST)) {
    print_r($_POST);
    $title = $_POST['title'];
    $body = $_POST['body'];
    $contactInfo = $_POST['contactInfo'];
    $catId = '1';
    $subCatId = '1';
    $adId = createAd($title, $body, $contactInfo, $catId, $subCatId);

    $location = $_POST['title'];
    $postal = $_POST['postal'];
    $ft_2 = $_POST['Sqft'];
    $rent = $_POST['Ask'];
    $availableOn = $_POST['title'];
    $beedroom = $_POST['title'];
    $bathroom = $_POST['title'];
    $laundry = $_POST['title'];
    $parking = $_POST['title'];
    $pets = $_POST['title'];

    $rec = createHousingForRentRec($adId, $location, $postal, $ft_2, $rent, $availableOn, $beedroom, $bathroom, $laundry, $parking, $pets);

    if ($adId == 1) {
        echo "<h1>Post created successfully:</h1>";
        echo "<pre>";
        print_r($_POST);
        echo "</pre>";
    } else {
        $errors[] = "Error while creating record: " . $rec;
    }

}

?>

<form name='hForRent' action='<?php $_SERVER['PHP_SELF'] ?>' method='post'>
<div id="adInfo">
    <p>
        <label>Title:</label>
        <input type="text" size="4" maxlength="70" tabindex="1" id="title" name="title" />
    </p>
    <p>
        <label>Body:</label>
        <textarea class="req" tabindex="1" rows="10" id="body" name="body"></textarea>
    </p>
    <p>
        <label>Contact Information:</label>
        <textarea class="req" tabindex="1" rows="3" id="contactInfo" name="contactInfo"></textarea>
    </p>
    <p>
        <label>Postal Code:</label>
        <input type="text" id='postal_code' name="postal" size="6" maxlength="15" />
    </p>
</div>
<br>

<div class="row">

</div>

<div class="row fields">
    <fieldset>
        <legend>Details</legend>
        <label class=""><div class="label">ft<sup>2</sup></div>
            <input type="text" tabindex="1" size="4" maxlength="6" name="Sqft" id="Sqft" value="0">
        </label>
        <label class="std"><div class="label">rent</div>
            &#x0024;<input type="text" tabindex="1" size="4" maxlength="11" name="Ask" value="">
        </label><label class="std">
            <div class="label">available on</div>
            <select style="margin-right:0px" name="moveinMonth" tabindex="1"><option value="1">jan</option><option value="2">feb</option><option value="3">mar</option><option  selected value="4">apr</option><option value="5">may</option><option value="6">jun</option><option value="7">jul</option><option value="8">aug</option><option value="9">sep</option><option value="10">oct</option><option value="11">nov</option><option value="12">dec</option></select>
            <input type="number" tabindex="1" placeholder="day"  min="1" max="31" size="2" step="1" value="24" name="moveinDay">
            <input type="number" tabindex="2" placeholder="year" min="2016" max="2026" size="4" step="1" value="2016" name="moveinYear">
        </label>
        <br><div class="attrline">
            <label class="req select"><div class="label">private room</div>

                <select tabindex="1" name="private_room" id="private_room">
                    <option value="" selected="selected">-</option> <option value="0">room not private</option> <option value="1">private room</option>
                </select>

            </label>

            <label class="req select"><div class="label">private bath</div>

                <select tabindex="1" name="private_bath" id="private_bath">
                    <option value="" selected="selected">-</option> <option value="0">no private bath</option> <option value="1">private bath</option>
                </select>

            </label>

            <label class="std select"><div class="label">laundry</div>

                <select tabindex="1" name="laundry" id="laundry">
                    <option value="">-</option> <option value="1">w/d in unit</option>
                    <option value="2">laundry in bldg</option>
                    <option value="3">laundry on site</option>
                    <option value="4">w/d hookups</option>
                    <option value="5">no laundry on site</option>

                </select>

            </label>

            <label class="std select"><div class="label">parking</div>

                <select tabindex="1" name="parking" id="parking">
                    <option value="">-</option> <option value="1">carport</option>
                    <option value="2">attached garage</option>
                    <option value="3">detached garage</option>
                    <option value="4">off-street parking</option>
                    <option value="5">street parking</option>
                    <option value="6">valet parking</option>
                    <option value="7">no parking</option>

                </select>

            </label>
        </div><div class="attrline">
            <label class="std checkbox"><div class="label">cats ok</div>
                <input type="checkbox" name="pets_cat" id="pets_cat" tabindex="1" value="1" >
            </label>

            <label class="std checkbox"><div class="label">dogs ok</div>
                <input type="checkbox" name="pets_dog" id="pets_dog" tabindex="1" value="1" >
            </label>

            <label class="std checkbox"><div class="label">furnished</div>
                <input type="checkbox" name="is_furnished" id="is_furnished" tabindex="1" value="1" >
            </label>

            <label class="std checkbox"><div class="label">no smoking</div>
                <input type="checkbox" name="no_smoking" id="no_smoking" tabindex="1" value="1" >
            </label>

            <label class="std checkbox"><div class="label">wheelchair accessible</div>
                <input type="checkbox" name="wheelchaccess" id="wheelchaccess" tabindex="1" value="1" >
            </label>
        </div>
    </fieldset>
</div>

<input type="submit" name="submit" value="Post"/>

</form>

</body>
</html>
