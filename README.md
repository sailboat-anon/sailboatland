```                                                                                                                                                                                   
                                     __                  ___                        __     ___            ___           __        
                __                  /\ \                /\_ \                      /\ \  /'___`\         /\_ \         /\ \       
   __    _____ /\_\       ___  __  _\ \ \____    __  _ _\//\ \      __      ___    \_\ \/\_\ /\ \      __\//\ \   __  _\ \ \____  
 /'__`\ /\ '__`\/\ \     /'___/\ \/\ \ \ '__`\ /'__`/\`'__\ \ \   /'__`\  /' _ `\  /'_` \/_/// /__    /'___\ \ \ /\ \/\ \ \ '__`\ 
/\ \L\.\\ \ \L\ \ \ \ __/\ \__\ \ \_\ \ \ \L\ /\  __\ \ \/ \_\ \_/\ \L\.\_/\ \/\ \/\ \L\ \ // /_\ \__/\ \__/\_\ \\ \ \_\ \ \ \L\ \
\ \__/.\_\ \ ,__/\ \_/\_\ \____\/`____ \ \_,__\ \____\ \_\ /\____\ \__/.\_\ \_\ \_\ \___,_/\______/\_\ \____/\____\ \____/\ \_,__/
 \/__/\/_/\ \ \/  \/_\/_/\/____/`/___/> \/___/ \/____/\/_/ \/____/\/__/\/_/\/_/\/_/\/__,_ \/_____/\/_/\/____\/____/\/___/  \/___/ 
           \ \_\                   /\___/                                                                                         
            \/_/                   \/__/                                                                                          

                                                                                                                                                         
 ```                                                                                                                                                                                  

                                                             

Welcome to api.cyberland2.club!  Should work with your legacy cyberland clients.
https://github.com/sailboat-anon/sailboatland

NEW FEATURES (4/15/20):
- We're hosted!  Thanks hostanon!
- Sorting works.  Sort by id, time, bumpcount in ASC or DESC order.
- Application-based IP blacklist

>im done making a client, how can i be a productive member of this project?
```fork -> commit to your fork -> make PR from your fork master to base repo master```

>im ready for my masters class, wut do?
```setup ansible playbooks, docker, and deploy to AWS/GCP/Azure using kubernetes```


*** TUTORIAL

URL (HTTPS-only)
```api.cyberland2.club:443```

*** POSTING
This will create a post to the off topic board containing the content x and replying to y, if y is unspecified, then it will be considered that it does not reply to anything.
```curl https://api.cyberland2.club/o/?content=x&replyTo=y```

*** GETTING POSTS
This will get y number of posts from the off topic board that reply to post number x with the newest first as a JSON object. If x is unspecified, just y number of recent posts will be returned. The number of posts you can recieve at once may be limited at some point depending on how this goes.

```curl https://api.cyberland2.club/o/?thread=x&num=y```

You can sort using sortOrder (values: "bumpCount", "time", "id") in ascending or descending order using sortHierarchy (values: "ASC", "DESC").

*** SORTING
If I wanted to get all posts on /t/, sorted by bumpcount I would do this:
```curl https://api.cyberland2.club/t/?sortOrder=bumpCount```

This would get you all the oldest posts first:
```curl https://api.cyberland2.club/t/?sortOrder=time&sortHierarchy=ASC```


```
                  .
                .'|     .8
               .  |    .8:
              .   |   .8;:        .8
             .    |  .8;;:    |  .8;
            .     n .8;;;:    | .8;;;
           .      M.8;;;;;:   |,8;;;;;
          .    .,"n8;;;;;;:   |8;;;;;;
         .   .',  n;;;;;;;:   M;;;;;;;;
        .  ,' ,   n;;;;;;;;:  n;;;;;;;;;
       . ,'  ,    N;;;;;;;;:  n;;;;;;;;;
      . '   ,     N;;;;;;;;;: N;;;;;;;;;;
     .,'   .      N;;;;;;;;;: N;;;;;;;;;;
    ..    ,       N6666666666 N6666666666
    I    ,        M           M
   ---nnnnn_______M___________M______mmnnn
         "-.                          /
  __________"-_______________________/_________
  ```