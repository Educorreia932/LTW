<?php
function getAdoptionMaxID() {
        global $db;
        
        $query =   'SELECT MAX(ID) AS M FROM AdoptionProposal';
        
        $stmt = $db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll();
    }
    

function proposedBefore($user_id, $pet_id) {
    global $db;
    
    $query =   'SELECT * FROM AdoptionProposal JOIN ProposalPets ON (AdoptionProposal.ID = ProposalPets.ProposalID)
                WHERE AdoptionProposal.AuthorID = ? AND ProposalPets.PetID = ?';
    
    $stmt = $db->prepare($query);
    $stmt->execute(array($user_id,$pet_id));

    return $stmt->fetch();
}

function getPostIDFromProposalId($proposal_id) {
    global $db;
    
    $query =   'SELECT * FROM (
                    (AdoptionProposal JOIN ProposalPets ON (AdoptionProposal.ID = ProposalPets.ProposalID))
                    JOIN Pets ON (ProposalPets.PetID = Pets.PetID))
                WHERE AdoptionProposal.ID = ?';
    
    $stmt = $db->prepare($query);
    $stmt->execute(array($proposal_id));

    return $stmt->fetch();
}

function acceptProposal($proposal_id) {
    global $db;
    
    $query =   'SELECT *
                FROM AdoptionProposal 
                WHERE Answered = 1 AND ID <> ?';

    $stmt = $db->prepare($query);
    $stmt->execute(array($proposal_id));
    if(count($stmt->fetchAll()) != 0)
        return FALSE;

    
    if($stmt = $db->prepare($query) && $stmt->execute(array($proposal_id))) {

        $query =   'UPDATE AdoptionProposal SET Answered=1, SeenAuthor=0
                WHERE AdoptionProposal.ID = ?';

        $query1 =   'UPDATE AdoptionProposal SET Answered=-1, SeenAuthor=0
                WHERE AdoptionProposal.ID <> ?';

        $query2 =   'UPDATE AdoptionPosts SET Closed=1
                WHERE AdoptionPostID = ?';
        
        $stmt = $db->prepare($query);
        if($stmt->execute(array($proposal_id)) && ($stmt = $db->prepare($query1)) && $stmt->execute(array($proposal_id))) {
            $post = getPostIDFromProposalId($proposal_id);
            $stmt = $db->prepare($query2);
            if($stmt->execute(array($post['AdoptionPostID'])));
                return TRUE;
        }
    }
    return FALSE;
}

function refuseProposal($proposal_id) {
    global $db;
    
    $query = 'UPDATE AdoptionProposal SET Answered=-1
              WHERE AdoptionProposal.ID = ?';
              
    if(($stmt = $db->prepare($query)) && $stmt->execute(array($proposal_id)))
        return TRUE;
    return FALSE;
}

function getNotifications($user_id) {
    global $db;
    
    $query =   'SELECT * FROM AdoptionProposal
                WHERE AdoptionProposal.AuthorID = ? AND SeenAuthor = 0';
    
    $stmt = $db->prepare($query);
    $stmt->execute(array($user_id));

    $newAnswers = $stmt->fetchAll();

    $newAnswers = array_map('setNewAnswer', $newAnswers);

    $query =   'SELECT * FROM AdoptionProposal JOIN ProposalPets ON AdoptionProposal.ID = ProposalPets.ProposalID
                WHERE AdoptionProposal.ID IN (
                    SELECT AdoptionProposal.ID FROM (
                        (AdoptionProposal JOIN ProposalPets ON (AdoptionProposal.ID = ProposalPets.ProposalID))
                        JOIN Pets ON (ProposalPets.PetID = Pets.PetID)
                        JOIN AdoptionPosts ON (AdoptionPosts.AdoptionPostID = Pets.AdoptionPostID)
                    )
                    WHERE AdoptionPosts.AuthorID = ?
                ) AND AdoptionProposal.SeenPostAuthor = 0';
    
    $stmt = $db->prepare($query);
    $stmt->execute(array($user_id));

    $newProposals = $stmt->fetchAll();

    $newProposals = array_map('setNewProposal', $newProposals);

    $notifications = array_merge($newAnswers, $newProposals);

    return $notifications;
}

function setNewAnswer($value) {
    $value['NotificationType'] = "NewAnswer";
    return $value;
}

function setNewProposal($value) {
    $value['NotificationType'] = "NewProposal";
    return $value;
}

function verifyPostNotifications($user_id, $post_id) {
    $notifications = getNotifications($user_id);

    foreach($notifications as $key => $notification) {
        if($notification['NotificationType'] != "NewProposal" || $notification['PetID'] != $post_id) {
            unset($notifications[$key]);
        }
    }
    if(count($notifications) == 0)
        return;

    $notifications = array_map('notifID', $notifications);
    

    $list = '';
    foreach($notifications as $n) {
        $list = $list . $n . ',';
    }
    $list = $list . '-1';

    echo("<script>console.log('PHP: " . $list . "');</script>");

    global $db;

    // $query =   'UPDATE AdoptionProposal SET SeenPostAuthor=1
    //             WHERE ID IN (?)';

    $query =   'SELECT * FROM AdoptionProposal
                WHERE ID IN (:ids)';
    
    $stmt = $db->prepare($query);
    $stmt->execute(array(':ids'=>$list));
    echo("<script>console.log('PHP: " . count($stmt->fetchAll()) . "');</script>");
}

function verifyProfileNotifications($user_id, $post_id) {
    $notifications = getNotifications($user['UserID']);

    foreach($notifications as $key => $notification) {
        if($notification['Type'] != "NewProposal" && $notification['ID'] != $post_id) {
            unset($notifications[$key]);
        }
    }
    if(count($notifications) == 0)
        return;

    $notifications = array_map('notifID', $notifications);
    echo("<script>console.log('PHP: " . $notifications . "');</script>");

    global $db;

    $query =   'UPDATE AdoptionProposal SET SeenPostAuthor=1
                WHERE AdoptionProposal.ID IN ?';
    
    $stmt = $db->prepare($query);
    $stmt->execute(array($notifications));
}

function notifID($value) {
    return $value['ID'];
}

?>