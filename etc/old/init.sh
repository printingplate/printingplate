#!/bin/bash
#

function _section {

  if [[ $1 == 'INFO' ]]; then
    COLOR='\033[0m' # No Color
  elif [[ $1 == 'INPUT' ]]; then
    COLOR='\033[0;33m' # Yellow
  elif [[ $1 == 'WARNING' ]]; then
    COLOR='\033[0;31m' # Red
  elif [[ $1 == 'SUCCESS' ]]; then
    COLOR='\033[0;32m' # Green
  elif [[ $1 == 'PROCESS' ]]; then
    COLOR='\033[0;34m' # Blue
  fi

  printf "$COLOR"
}

# return 1 if global command line program installed, else 0
# example
# echo "node: $(program_is_installed node)"
function program_is_installed {
  # set to 1 initially
  local return_=1
  # set to 0 if not found
  type $1 >/dev/null 2>&1 || { local return_=0; }
  # return value
  echo "$return_"
}

function prerequisites {

  WARNING=0

  if [[ 1 != $(program_is_installed npm) ]]; then
    echo $(_section 'WARNING')
    echo "PrintingPlate needs node and npm: https://nodejs.org/download/"
    WARNING=1
  fi

  if [[ 1 != $(program_is_installed bower) ]]; then
    echo $(_section 'WARNING')
    echo "PrintingPlate needs Bower: http://bower.io/#install-bower"
    WARNING=1
  fi

  if [[ 1 != $(program_is_installed gulp) ]]; then
    echo $(_section 'WARNING')
    echo "PrintingPlate needs Gulp: https://github.com/gulpjs/gulp/blob/master/docs/getting-started.md"
    WARNING=1
  fi

  if [[ 1 != $(program_is_installed wp) ]]; then
    echo $(_section 'WARNING')
    echo "PrintingPlate needs WP_CLI: http://wp-cli.org"
    WARNING=1
  fi

  if [[ 1 == $WARNING ]]; then
    echo $(_section 'INFO')
    printf "Please see to the issues above first and then run this script again.\n\n"
    exit
  fi

}

# Check dependencies
prerequisites

echo $(_section 'PROCESS')

# Setup local environment
cat 'etc/tools/init/ascii-art.txt'

echo $(_section 'INFO')

printf "\n\n\n${NC}Welcome to the ${BLUE}PrintingPlate${NC} setup.\n"

printf "\nLet's start with some basic information for your new project.\n"

echo $(_section 'INPUT')

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
    echo $(_section 'WARNING')
    printf "* The shortname can only contain letters, numbers and dashes.${NC}\n"
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

echo $(_section 'INFO')

printf "\n${NC}Awesome! Now let's configure your local development environment.${YELLOW}\n"

echo $(_section 'INPUT')

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

echo $(_section 'INFO')
printf "\nGreat, now PrintingPlate will now start building some files for your environment.\n"

# Auto-generate salts for wp-config.php

echo $(_section 'PROCESS')
printf "Download salts for wp-config.php......"

cp 'etc/tools/init/wp-config.tpl' 'app/www/wp-config.php'

salts=$(curl -s https://api.wordpress.org/secret-key/1.1/salt)

salts_e=$(echo $salts | sed 's/[|&]/\\&/g')

sed -i '' "s|<PPsalts>|$salts_e|" "app/www/wp-config.php"

echo $(_section 'SUCCESS')
printf "Done\n\n"

echo $(_section 'PROCESS')
printf "Installing PHP dependencies with composer......."

error=$(composer -q install)
if [[ 0 != $error ]]; then
  echo $(_section 'WARNING')
  echo $error
fi

echo $(_section 'SUCCESS')
printf "Done\n"

echo $(_section 'PROCESS')
printf "Installing Javascript dependencies with Bower......."

error=$(bower -l=error install)
if [[ 0 != $error ]]; then
  echo $(_section 'WARNING')
  echo $error
fi

echo $(_section 'SUCCESS')
printf "Done\n"

echo $(_section 'PROCESS')
printf "Installing npm tools......."

error=$(npm --loglevel=error install)
if [[ 0 != $error ]]; then
  echo $(_section 'WARNING')
  echo "Error occurred: "
  echo $error
fi

echo $(_section 'SUCCESS')
printf "Done\n"

echo $(_section 'PROCESS')
printf "Building assets with gulp......."

error=$(gulp)
if [[ 0 != $error ]]; then
  echo $(_section 'WARNING')
  echo $error
fi

echo $(_section 'SUCCESS')
printf "Done\n"

echo $(_section 'PROCESS')
printf "Instaling WordPress......."

error=$(wp --path=app/www/wp --url=${LOCAL_URL} core install)
if [[ 0 != $error ]]; then
  echo $(_section 'WARNING')
  echo $error
fi

