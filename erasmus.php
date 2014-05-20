<?php

include_once("inc/HTMLTemplate.php");


$content=<<<END

<div class="container">
   <br>
   <div class="col-md-8 col-md-offset-2">
      <h2>The Erasmus program</h2>
      <p><a href="http://www.erasmusprogramme.com/" target="_blank">The Erasmus program</a> might require to fill out a lot of documents, reach some deadlines etc. It has become a common way for universities and more to send their students abroad. Moreover, it also provides you a grant ! Calculated according to a monthly rate and paid in two parts, 80% at the beginning of the trip and 20% at the end.</p>

      <p>Beside the opportunity to study abroad and some money to help you to go through your spendings, Erasmus also proposes some intensive language courses to prepare your trip, to help you to adapt yourself academically and of course, socially.</p>


      <p>As you understood, the Erasmus program is a little more than a standardized administrative required process, it's an help for students ! This is why I recommend students that want to study abroad to take information about Erasmus to their 2nd language teachers. They usually are aware of the existing exchange programs and potential links with foreign universities.</p>
   </div>
</div>

END;


echo $header;
echo $content;
echo footer();

?>