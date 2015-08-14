#!/bin/bash
#

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[0;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

printf "\n\n${YELLOW}"

# Setup local environment
cat 'etc/tools/init/ascii-art.txt'

printf "\n\n\n${NC}Welcome to the ${BLUE}PrintingPlate${NC} setup.\n"

printf "\nLet's start with some basic information for your new project.\n"

printf "${YELLOW}"

# Project full name
printf "Project full name (PrintingPlate): "

read NAME

if [[ '' == $NAME ]]; then
  NAME="PrintingPlate"
fi

# Project short name
SHORTNAME=''

while [[ '' == $SHORTNAME ]]; do
  printf "Project short name (printingplate): "
  read _SHORTNAME
  if [[ '' == $_SHORTNAME ]]; then
    SHORTNAME='printingplate'
  elif [[ $_SHORTNAME =~ [^a-zA-Z0-9\-] ]]; then
    printf "${RED}* The shortname can only contain letters, numbers and dashes.${NC}\n"
  else
    SHORTNAME=$_SHORTNAME
  fi
done

if [[ 'printingplate' != $SHORTNAME ]]; then
  mv 'app/www/content/themes/printingplate' "app/www/content/themes/$SHORTNAME"
fi

cp 'etc/tools/init/style.tpl' "app/www/content/themes/$SHORTNAME/style.css"

sed -i '' "s#<PPname>#$NAME#g" "app/www/content/themes/$SHORTNAME/style.css"

# Project URI
printf "Project URL: (http://printingplate.co) "

read URI

if [[ '' == $URI ]]; then
  URI="http://printingplate.co"
fi

sed -i '' "s#<PPuri>#$URI#g" "app/www/content/themes/$SHORTNAME/style.css"

# Project Author
printf "Project author: ($USER) "

read AUTHOR

if [[ '' == $AUTHOR ]]; then
  AUTHOR=$USER
fi

sed -i '' "s#<PPauthor>#$AUTHOR#g" "app/www/content/themes/$SHORTNAME/style.css"

# Project Description
printf "Short description (PrintingPlate based project): "

read DESCRIPTION

if [[ '' == $DESCRIPTION ]]; then
  DESCRIPTION="PrintingPlate based project"
fi

sed -i '' "s#<PPdescription>#$DESCRIPTION#g" "app/www/content/themes/$SHORTNAME/style.css"

printf "\n${NC}Awesome! Now let's configure your local development environment.${YELLOW}\n"

# Copy the env template
cp 'etc/tools/init/env.tpl' '.env'

# MySQL host
printf "MySQL host (localhost): "

read MYSQL_HOST

if [[ '' == $MYSQL_HOST ]]; then
  MYSQL_HOST="localhost"
fi

sed -i '' "s#<PPmysql_host>#$MYSQL_HOST#g" '.env'

# MySQL database name
printf "MySQL database name ($SHORTNAME): "

read MYSQL_DBNAME

if [[ '' == $MYSQL_DBNAME ]]; then
  MYSQL_DBNAME=$SHORTNAME
fi

sed -i '' "s#<PPmysql_dbname>#$MYSQL_DBNAME#g" '.env'

# MySQL database user
printf "MySQL user (root): "

read MYSQL_USER

if [[ '' == $MYSQL_USER ]]; then
  MYSQL_USER=$SHORTNAME
fi

sed -i '' "s#<PPmysql_user>#$MYSQL_USER#g" '.env'

# MySQL database password
printf "MySQL database password: "

read MYSQL_PASS

sed -i '' "s#<PPmysql_pass>#$MYSQL_PASS#g" '.env'

# Local dev URL
LOCAL_URL=''

URL_REGEX='(https?|ftp|file)://[-A-Za-z0-9\+&@#/%?=~_|!:,.;]*[-A-Za-z0-9\+&@#/%=~_|]'

while [[ '' == $LOCAL_URL ]]; do
  printf "Local development URL, including http(s)://  (http://loc.printingplate.co): "
  read _LOCAL_URL
  if [[ '' == $_LOCAL_URL ]]; then
    LOCAL_URL='http://loc.printingplate.co'
  elif ! [[ $_LOCAL_URL =~ URL_REGEX ]]; then
    printf "${RED}* Please enter a valid URL (make sure you include http:// or https://)${NC}\n"
  else
    LOCAL_URL=$_LOCAL_URL
  fi
done

sed -i '' "s#<PPlocalurl>#$LOCAL_URL#g" '.env'

printf "\n${NC}Great, now PrintingPlate will now start building some files for your environment.\n"

# Auto-generate salts for wp-config.php

printf "${BLUE}Download salts for wp-config.php......"

cp 'etc/tools/init/wp-config.tpl' 'app/www/wp-config.php'

salts=$(curl -s https://api.wordpress.org/secret-key/1.1/salt)

salts_e=$(echo $salts | sed 's/[|&]/\\&/g')

sed -i '' "s|<PPsalts>|$salts_e|" "app/www/wp-config.php"

printf "${GREEN}Done\n\n"

printf "${BLUE}Installing PHP dependencies with composer......."

composer -q install

printf "${GREEN}Done\n"

printf "${BLUE}Installing Javascript dependencies with Bower......."

bower install

printf "${GREEN}Done\n"

printf "${BLUE}Installing npm tools......."

npm install

printf "${GREEN}Done\n"

printf "${BLUE}Building assets with gulp......."

gulp

printf "${GREEN}Done\n"



