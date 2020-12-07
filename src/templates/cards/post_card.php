<?php
    $post_pet = getPetPost($post);
    $postDate = DateTime::createFromFormat('d-m-Y H:i:s', $post['Date'])->format('j M Y \a\t H:i');
    $postpet_species = getSpecies($post_pet['SpeciesID']);
?>

<script src="scripts/post_form.js"></script>

<a> 
    <div id="adoption-post-card">
        <h2><a href="#" onclick="post('adoption_post.php', {id:<?=$post_pet['PetID']?>})"><?=$post['Title']?></a></h2>

        <div id="post-pet-info">
            <div id="pet-pic-post" >
                <img src="<?=$post_pet['Photo']?>" alt="Pet photo">
                <h3><?=$post_pet['Name']?></h3>
                <h4><?=$post['Location']?></h4>
            </div>
            <div id="pet_details_description">
                <h3>Pet Details</h3>
                <div id="pet_details">
                    <div id="pet-details-left">
                        <p><b>Age: </b><?=$post_pet['Age']?></p>
                        <p><b>Size: </b><?=convertSize($post_pet['Size'])?></p> 
                        <p><b>Color: </b><?=$post_pet['Color']?></p>
                    </div>
                    <div id="pet-details-right">
                        <p><b>Species: </b><?=$postpet_species['SpeciesName']?></p>
                        <p><b>Weight </b><?=$post_pet['Weight']?>kg</p>
                        <p><b>Gender: </b><?=convertGender($post_pet['Gender'])?></p>
                    </div>
                </div>
                <div id="pet-description">
                    <h3>Description</h3>
                    <p><?=$post['Description']?></p>
                </div>
            </div>
        </div>

        <footer>
            <p><?=$postDate?></p>
        </footer>
    </div>
</a> 