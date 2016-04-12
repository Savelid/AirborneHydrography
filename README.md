#  System overview database site for Airborne Hydrography

-----

#  Variables

+ $title - will be shown in the header (the big text).
+ $type - will be used by the 'post' pages to see what to do. Also passed on to log.

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
print.css tweaks a few things for prints (change line-break from always to auto if you like)

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

functions contain common functions.

config contains login info for database and arrays that might change often.

'database' help setup the database, fill it with test data or delete it.
Look in database_setup to see the structure of the database.

#  Components

### Combobox
requires following script somewhere on the page:

        <script type="text/javascript">
          $(document).ready(function(){
            $('.combobox').combobox();
          });
        </script>

### Popover
requires following script somewhere on the page:

        <script>
          $(function () {
            $('[data-toggle="popover"]').popover()
          })
        </script>

#  Functions

### listUnusedSerialNr
requires following code before the place it is used:

        <?php require_once('res/functions.inc.php'); ?>

Used to fill comboboxes with (unused) items.
takes 3 variables:
+ $serial_nr - the current item - to be shown first in the list.
+ $from - the database table to look in.
+ $where - the condition in the mysql query. To determine what items to show.

returns something like this (simplified):

        <option value="serial_nr">serial_nr</option>
        <option>-----</option>
        <option value="item1">item1</option>
        <option value="item1">item2</option>
        <option value="item1">item3</option>
