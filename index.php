<?PHP   
echo "<pre>
 

             _               _                 _ ___        _       _     
            | |             | |               | |__ \      | |     | |    
   ___ _   _| |__   ___ _ __| | __ _ _ __   __| |  ) |  ___| |_   _| |__  
  / __| | | | '_ \ / _ \ '__| |/ _` | '_ \ / _` | / /  / __| | | | | '_ \ 
 | (__| |_| | |_) |  __/ |  | | (_| | | | | (_| |/ /_ | (__| | |_| | |_) |
  \___|\__, |_.__/ \___|_|  |_|\__,_|_| |_|\__,_|____(_)___|_|\__,_|_.__/ 
        __/ |                                                             
       |___/                                                              
                                                                                                        
                                                                                             
                  
      Welcome to cyberland2.club!  Under New Management™️!

      https://github.com/sailboat-anon/sailboatland

*** TUTORIAL

URL
cyberland2.club

*** POSTING
This will create a post to the off topic board containing the content x and replying to y, if y is unspecified, then it will be considered that it does not reply to anything.
curl https://cyberland2.club/o/?content=x&replyTo=y

*** GETTING POSTS
This will get y number of posts from the off topic board that reply to post number x with the newest first as a JSON object. If x is unspecified, just y number of recent posts will be returned. The number of posts you can recieve at once may be limited at some point depending on how this goes.

curl https://cyberland2.club/o/?thread=x&num=y

You can sort using sortOrder (values: \"bumpCount\", \"time\", \"id\") in ascending or descending order using sortHierarchy (values: \"ASC\", \"DESC\").

*** SORTING
If I wanted to get all posts on /t/, sorted by bumpcount I would do this:
curl https://cyberland2.club/t/?sortOrder=bumpCount

This would get you all the oldest posts first:
curl https:/cyberland2.club/t/?sortOrder=time&sortHierarchy=ASC

*** WHO ARE YOU ANON, and what's the name of your custom client?
https://cyberland2.club/whoami/

                ,_
                I~
                |\
               /|.\
              / || \
            ,'  |'  \
         .-'.-==|/_--'
         `--'-------'  
         </pre>
";
?>