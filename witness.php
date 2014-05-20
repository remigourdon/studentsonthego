<?php

include_once("inc/HTMLTemplate.php");

$breadcrumb=<<<END
<div class="container">
<div class="row">
   <div class="col-md-2 col-md-offset-1">
      <ol class="breadcrumb">
        <li><a href="About.php">About</a></li>
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
      <article>
         <h3>Why you might want to take this trip</h3>
         <p>I was very excited about studying outside of France. I knew it would be a very fulfilling and I have to say, I was right ! I still think the main purpose of studying abroad still is learning as much as possible about both the culture and the language. Yes living your hometown can sometime be scary but sometimes, you need to leave your place to come back changed. I did just one semester abroad yet, in a Swedish university, and I can say that this experience gather a lot of amazing memories.</p>

         <h4>Some will tell you ..</h4>
         <p>"Studying abroad ?! It's all about partying, meeting girls, drink alcohol !" Others will be more like : "Of course not, this is all about studying, learning the most you can about the country, its history, its culture !". What I think about that is that there is NO actual predefined way to live this experience. It depends on each of us personnality, some will be only partying all the time, others only studying and some like me are doing a little bit of both. I really think you should just do what's necessary to enjoy it as much as you can, in your own way.</p>

         <h4>Yes, it's worth it</h4>
         <p>During this trip, I built <em>some amazing memories</em>, met some awesome people. One of the best pros is also that I can now imagine myself working in any english speaking country. I mean, before this trip I didn't feel so <em>confortable with the idea of working and living abroad</em>, it expended my worldview. It also made me a bit better about solving problems related to the integration in a foreign country. Moreover, I do think that my English is a bit better than before, I feel more confortable now. But beware of hanging out with people from your home country ! When you travel with your friends it is very easy not to speak the local language !</p>

         <p><em>Being more open</em> to different customs is also one of the symptoms of travelling, especially when you live like me in the same residence than other exchange students ! (Taiwanese, Austrian, Greeks, Chinese, Singaporian ..). You'll learn a bit more about yourself, become more independant and that's also one of the main reasons <em>it looks great on a resume</em>.</p>

         <p>You'll experiment a lot. From the education system to the local specialities and customs. And trust me this is not only fulfilling, it's really cool ! And you'll not do those activities alone, you'll make new friends, from all around the world.</p>

         <p>Of course this is just what happened to me and your experience could be not as good, but now that I know how much you can learn during a trip, i can only say it's your responsibility to take each benefit of this experience. This is why I would say help yourself for your future, give it a shot and live it a hundred percent.</p>

      </article>

   </div>
</div><!-- container  -->


END;


echo $header;
echo $breadcrumb;
echo $content;
echo footer();


?>