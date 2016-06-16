#  System overview database site for Airborne Hydrography

-----

#  Variables

+ $title - will be shown in the header (the big text).

#  Structure

### Root:
Contains all pages of the site.
'edit' pages are form pages for changing in the database.
'view' pages are subpages with more information about something.
'post' pages handles the database queries that changes the database.
'admin' pages are tools that can not be found on the page but might be useful.
'main' pages have there own link in the menu.

### css:
Contains stylesheets.
Bootstrap is a standard library
main.css is specific styles for this page
print.css tweaks a few things for prints (you can change line-break from always to auto if you prefer)

### js:
Contains javascripts needed for the page.

### fonts:
Contains fonts and symbols

### img:
Contains all images (not symbols)

### res:
Contains other resources.

header and footer contains boilerplate code and are included in all pages.
In header you find header, menu, links to css, links to javascripts and more.
In footer you find Javascripts.

functions - contain common functions.

config - contains login info for database and arrays that might be change often.
WARNING! This file is not in, and should not be in GIT since it contains passwords.

'database' help setup the database, fill it with test data or delete it.
Look in database_setup to see the structure of the database.

#  Components

### Combobox
requires following script somewhere on the page (see bootstraps webpage):

        <script type="text/javascript">
          $(document).ready(function(){
            $('.combobox').combobox();
          });
        </script>

### Popover
requires following script somewhere on the page (see bootstraps webpage):

        <script>
          $(function () {
            $('[data-toggle="popover"]').popover()
          })
        </script>

#  Functions
See comments in the functions file.
