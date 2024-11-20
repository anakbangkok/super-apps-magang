// JavaScript to dynamically add 'active' class to the menu item
document.querySelectorAll('.menu-item > .menu-link').forEach(link => {
          link.addEventListener('click', function () {
              document.querySelectorAll('.menu-item.active').forEach(item => item.classList.remove('active'));
              this.parentElement.classList.add('active');
          });
      });
      

     