<?php

include_once("inc/HTMLTemplate.php");

$breadcrumb=<<<END
<div class="container">
<div class="row">
   <div class="col-md-2 col-md-offset-1">
      <ol class="breadcrumb">
        <li><a href="about.php">About</a></li>
        <li class="active">Witness</li>
      </ol>
   </div>
</div>
</div>
END;


$content=<<<END

<div class="container">
   <br>
   <div class="col-md-8 col-md-offset-2">
      <h2>Advices & usefull links</h2>
      <p>When you hear some students's stories that went abroad, you never hear anything about the paperwork, the deadlines neither the things they did to prepare it ! But I assure you, being well prepared make a true difference on how much you can appreciate this experience. Those tips are here to avoid some troubles and spendings you can quickly have. Because the more you prevent those surprises, the more time you'll have to enjoy this experience !</p>

      <h3>1 - Be informed !</h3>
      <p>The university you'll study in, the place you'll stay in but also the places you want to visit ! Get in touch with a student who 've been there if possible ! It surely will facilitate both the paperwork and your social life ! Do not forget to be aware of the social customs, otherwise it might lead you to some funny situations !</p>


      <h3>2 - Learn the basics of the language !</h3>
      <p>University classes, <a href="http://www.memrise.com">online classes</a>, private classes, books, there are many ways to prepare yourself to interact well. Many students fear to make mistakes at the beginning, the truth is, the sooner you practice it, the sooner you'll enjoy it !</p>

      <h3>3 - You always need more money.</h3>
      <p>You know that you don't go on a trip every sunday, you'll want to appreciate it a 100 percent ! Taste each speciality, experiment every local activities, parties .. All of that costs always more than you think ! Feel free to try the <a href="index.php#map">"travel-cost estimator"</a> it can help you with that. Oh, and talking about money, you should get used to the exchange rate as soon as possible, otherwise you don't want to spend money on stupid stuff.</p>

      <h3>4 - Don't overload your luggage/backpack.. </h3>
      <p>Before leaving, you always fear lacking something and it finishes with a tons of shirts in your backpack. Avoid this, you'll want to buy stuff and bring them home !</p>

      <h3>5 - Anticipate the bad stuff ..</h3>
      <p>Store your important informations like names, phone numbers, addresses .. On a cloud storage, in case you lose your phone/laptop you'll be covered. Be sure to get your passport/Visa quite early to avoid any stress. Moreover, you should also call your bank, to warn them that some transactions'll be made abroad . Also, to know if you'll be able to pay your rent without fees, those details can make your life a little easier.</p>

      <h3>6 - Get yourself an international student card !</h3>
      <p>All student cards are not warranty everywhere but this one is.</p>



   </div>
</div>

END;


echo $header;
echo $breadcrumb;
echo $content;
echo footer();

?>