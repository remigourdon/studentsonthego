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
      <p>The university you'll study in, the place you'll stay in, the weather .. But also the places you want to visit ! Get in touch with a student who 've been there if possible ! It surely will facilitate both the paperwork and your social life. The returnees always have good advices for you ! Do not forget to be aware of the social customs, otherwise it might lead you to some funny situations !</p>


      <h3>2 - Learn the basics of the language !</h3>
      <p>University classes, <a href="http://www.memrise.com">online classes</a>, private classes, books, there are many ways to prepare yourself to interact well. Many students fear to make mistakes at the beginning, the truth is, the sooner you practice it, the sooner you'll enjoy it !</p>

      <h3>3 - You always need more money.</h3>
      <p>You know that you don't go on a trip every sunday, you'll want to appreciate it a 100 percent ! Taste each speciality, experiment every local activity, party .. All of that costs always more than you think ! Feel free to try the <a href="index.php#map">"travel-cost estimator"</a> it can help you with that. Oh, and talking about money, you should get used to the exchange rate as soon as possible, otherwise you might spend money for no purpose.</p>

      <h3>4 - Don't overload your luggage/backpack.. </h3>
      <p>Before leaving, you always fear lacking something and it finishes with a tons of shirts in your backpack. Avoid this, you'll want to buy stuff and bring them home !</p>

      <h3>5 - Anticipate the bad stuff ..</h3>
      <p>Store your important informations like names, phone numbers, addresses .. On a cloud storage, in case you lose your phone/laptop you'll be covered. Be sure to get your passport/Visa quite early to avoid any stress. Moreover, you should also call your bank, to warn them that some transactions'll be made abroad . Also, to know if you'll be able to pay your rent without fees, those details can make your life a little easier.</p>

      <h3>6 - Get yourself an international student card !</h3>
      <p>All student cards are not warranty everywhere but this one is.</p>


      <h3>7 - Get involved !</h3>
      <p>Consider having a local job, internship or volunteering, it is a good way to both learn a job, develop yourself and meet more people. Beside the possibility of making some money, it also is an opportunity for you to practice the local language, moreover, local people often appreciate to meet someone trying to learn more about their culture.</p>

      <h3>8 - Think before buying your plane tickets !</h3>
      <p>Be sure to pass your very last exam to go back home, those mistakes either cost a lot of money or let you with a very bad memory at the end of your trip .. And don't forget to make sure that all your credits will be accepted by your home university</p>

      <h3>9 - Travel around</h3>
      <p>Take the time to visit other cities nearby, to experiment what the country has to offer. As I said, you are not abroad every sunday, so take a look at the best landscapes around !</p>

      <h3>10 - Be social !</h3>
      <p>Last but very not least, be open to meet new people, be active and share your best moments with other new friends ! There'll be other exchange students there and they aim for the same thing. So don't be shy and say YES when you're invited to an event !</p>





   </div>
</div>

END;


echo $header;
echo $breadcrumb;
echo $content;
echo footer();

?>