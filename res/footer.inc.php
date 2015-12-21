    </main>
    </div><!--End container -->

<script>
  /*
   * Menu for small screens
   * Open the drawer when the menu ison is clicked.
   */
  var menu = document.querySelector('#menu');
  var main = document.querySelector('main');
  var drawer = document.querySelector('.my_nav');

  menu.addEventListener('click', function(e) {
    drawer.classList.toggle('open');
    e.stopPropagation();
  });
  main.addEventListener('click', function() {
    drawer.classList.remove('open');
  });

</script>
  </body>
</html>
