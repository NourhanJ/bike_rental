<?php
REQUIRE_ONCE('db/0_Connection.php');

$brand_list = ['Trek','Specialized','Giant','Cannondale','Santa Cruz','Scott','Yeti','Kona','Pivot','Rocky Mountain','Norco','Felt'];

//insert users
for($i=1;$i<=5;$i++){
    $d = mt_rand(0,8);
    $sql = "INSERT INTO `users`(`username`, `password`, `f_name`, `l_name`, `tel`, `addres`, `date_of_birth`)
                    VALUES ('user_$i','123','first$i','last$i','0$i-123456','user $i address','1995-05-07')";
    if ($conn->query($sql) === TRUE) {
        echo "User-$i added successfully<br>";
    } else {
        echo "Error insert User-$i: " . $conn->error . "<br>";
    }
}

//insert bikes
for($i=1;$i<=10;$i++){
    $b = mt_rand(0,11); $b_v = $brand_list[$b];
    $y = mt_rand(1990,2023);
    $t = mt_rand(0,1);
    $r = mt_rand(1,100);
    $sql = "  INSERT INTO `bike`(`bike_name`, `brand`, `year_model`, `image`, `description`, `rent_price_daily`) 
                    VALUES ('bike-$i','$b_v',$y,'$i.jpg','description of bike $i, its good',$r)";
    if ($conn->query($sql) === TRUE) {
        echo "bike-$i added successfully<br>";
    } else {
        echo "Error insert bike-$i: " . $conn->error . "<br>";
    }
}

//insert contact-us
$contact_us=['Avoid unnecessary information. The purpose of your contact us page is one of the most direct. If the information is not focused on explaining on how someone can communicate with you directly, it shouldn’t be there. .',
             'Don’t ask for unnecessary information. Keep your form fields to the point and don’t add questions you don’t immediately need the answers to to help the user on their journey.',
             'Offer more than one way to contact you. Sometimes users want to talk to you on the phone, or live chat, rather than fill out a form. Be sure to give them the option to choose the method they’re most comfortable with.',
             'Personalize as much as you can. Use features like smart content and conditional logic to adapt the page to the user’s needs. Do you have a different set of questions for a prospect than you do a user that needs support? Make sure your page displays the right questions and information no matter the persona.',
             'Set the right expectations. Reassure the user that you will contact them back. Highlight your response time, or let them know who they will be hearing from.'
            ];

for($i=1;$i<=5;$i++){
    $c_v = $contact_us[$i-1];
    $sql = "  INSERT INTO `contact_us`(`name`, `email`, `description`)
                    VALUES ('user 1$i','user1$i@gmail.com','$c_v')";
    if ($conn->query($sql) === TRUE) {
        echo "Contact-us-$i added successfully<br>";
    } else {
        echo "Error insert Contact-us-$i: " . $conn->error . "<br>";
    }
}

$conn->close();	
?>