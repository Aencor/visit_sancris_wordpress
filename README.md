# Aencor WordPress Theme
 Aencor Marketing New Website Code
 
## Requirements
 - [Docker](https://www.docker.com/)
 - [Visual Studio Code](https://code.visualstudio.com/)
 - [NodeJS](https://nodejs.org/en/)

## Stack Used
 - [Tailwind] (https://tailwindcss.com/)
 - SASS
 - Javascript
 - [Webpack] (https://webpack.js.org/)
 - [GSAP] (https://gsap.com/)
 - [Font Awesome] (https://fontawesome.com/)

## How to use
 Clone the repo into your computer and once you do go to the folder on Terminal, Run Docker and use the command 
 ```
 docker compose up
 ```
This will install all the required elements and create the container for your work, after that on the same folder fun the following commands :

```
npm i
npm run watch
```

This will install all the required modules and you will be able to start working on the project, webpack will compile the files and they will be find under the **assets/build** folder

When your code is ready to deploy into staging or production please run the following command

```
npm run production
```

### Working Branch

You need to work on the **dev** branch, push your changes to **staging** to deploy into _staging_ environment and do a pull request to **main** when the code is ready to revision to deploy into _production_

## Project Structure

You will find all tou need to work under the route wp-content/themes/accelity

- wp-content
  - themes
    - accelity
      - assets (On this folder you will find the css, js and image elements)
        - scss (Here you must place all your scss files)
        - js (all JS files goes in here)
        - img (All Image files should be placed here)
        - build (This folder is the one that webpack will use to build the assets elements, no need to touch anything here)
      - functions (On this folder you will find the functions.php files to manage site stuff
      - blocks (On this folder you should place the block elements for your gutenberg custom blocks)
      - On the root you will find the main elements such as header, footer, index, etc...  
 
# visit_sancris_wordpress
