
# JSON data sorting task.

This is a single-page website, that reads JSON file, processes it, and stores vehicle data in the database.
Laravel is used as a framework, together with vue.js and Axios to make an HTTP request to the backend without page refresh. 
Vehicles are sorted in columns depending on data fetched from the database. Max allowed rows are 14. 
Each vehicle can be edited and moved from one column to another. Vehicles, where s_datums value is null
can be removed from the table. 
    
 Please follow instructions below to run it on your local environment.

    Open terminal within a folder, were you want to download this project.
    Enter command "git clone https://github.com/JanisDavidsons/csdd.git ."

   ![git demo](sampleImg/git.gif)

    Install Composer Dependencies.
    "composer install"

   ![git demo](sampleImg/1.gif)

    Install NPM Dependencies.
    "npm install"
    
   ![git demo](sampleImg/2.gif)

    Create a copy of your .env file.
    "cp .env.example .env"
    
   ![git demo](sampleImg/3.gif)

    Generate an app encryption key.
    "php artisan key:generate"
    
   ![git demo](sampleImg/4.gif)

    Create new file in databse folder with command "touch database.sqlite"
    You can use any database, but in this example I`m using sqlite.
    
   ![Image](sampleImg/5.gif)

     In the .env file, add database information to allow Laravel to connect to the database.  
     Leave only this part in database section "DB_CONNECTION=sqlite"  

   ![git demo](sampleImg/.env.png)
   ![Image](sampleImg/6.gif)

    Migrate the database
   ![git demo](sampleImg/7.gif)
   
    Run local server with command "php artisan serve"
    Open adress displayed in terminal!
   ![git demo](sampleImg/8.gif)
       
Sample of the project:

   ![git demo](sampleImg/sample.gif)
   
    https://youtu.be/9lV6NrncEDA
