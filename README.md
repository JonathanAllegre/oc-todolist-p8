# Todo-List OC
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/386a33a478b3481d921fdbb18a5c9dc5)](https://www.codacy.com/app/JonathanAllegre/oc-todolist-p8?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=JonathanAllegre/oc-todolist-p8&amp;utm_campaign=Badge_Grade)

The 8th project of the OpenClassRoom training: PHP / Symfony application developer  

## Getting Started  
These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. 

### Prerequisites  
 - php 7.2  
 - composer: https://getcomposer.org  
 
 ### Installing  
   
  #### Clone Or Download the project
  - Open your command shell prompt and enter:
  
 	> git clone https://github.com/JonathanAllegre/oc-todolist-p8.git
 	
  - Move in your folder application
  
  - Open folder with your favorite editor
  
   #### Configuration
   - Replace in .env file the value of DATABASE_URL by your own configuration.
   - Run:
  	 >      make install
  	 
  	 >      make database-create
  	
   #### DataFixtures
   - Run:
    
     >      make fixture
  
  ## Little thing to know
  
  Fixtures give you access as admin role with the username "admin" and as user role with the username "user". The password is "test".
     
  Enjoy !
